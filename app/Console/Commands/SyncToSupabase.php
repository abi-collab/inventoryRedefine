<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SyncToSupabase extends Command
{
    protected $signature = 'sync:supabase
                            {--force : Run even if SUPABASE_SYNC_ENABLED is false}
                            {--table= : Sync a single table name}
                            {--full : Ignore checkpoints and push all rows}';

    protected $description = 'Incrementally push local SQLite data to Supabase Postgres (cloud mirror)';

    /**
     * FK-safe order. Ephemeral cart table "pos" is intentionally omitted.
     */
    protected array $tables = [
        'users',
        'employees',
        'suppliers',
        'categories',
        'customers',
        'products',
        'expenses',
        'salaries',
        'orders',
        'order_details',
        'extras',
        'activitylogs',
        'serials',
        'serialnumbers',
        'returnitems',
    ];

    public function handle(): int
    {
        $enabled = filter_var(env('SUPABASE_SYNC_ENABLED', false), FILTER_VALIDATE_BOOLEAN);
        if (! $enabled && ! $this->option('force')) {
            $this->warn('Supabase sync is disabled. Set SUPABASE_SYNC_ENABLED=true or pass --force.');

            return self::SUCCESS;
        }

        if (! $this->canConnect()) {
            $this->error('Cannot reach Supabase. POS continues offline; will retry next schedule.');
            Log::warning('sync:supabase skipped — connection failed');

            return self::FAILURE;
        }

        $only = $this->option('table');
        $tables = $only ? [$only] : $this->tables;
        $full = (bool) $this->option('full');
        $chunk = 250;
        $ok = true;

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                $this->warn("Local table missing, skip: {$table}");
                continue;
            }

            if (! Schema::connection('supabase')->hasTable($table)) {
                $this->warn("Remote table missing, skip: {$table}. Run: php artisan migrate --database=supabase");
                $this->markCheckpoint($table, 'missing_remote', 'Remote table does not exist', 0);
                $ok = false;
                continue;
            }

            try {
                $rows = $this->syncTable($table, $full, $chunk);
                $this->info("Synced {$table}: {$rows} row(s)");
                $this->markCheckpoint($table, 'ok', null, $rows);
            } catch (Throwable $e) {
                $ok = false;
                $this->error("Failed {$table}: {$e->getMessage()}");
                Log::error('sync:supabase table failed', [
                    'table' => $table,
                    'error' => $e->getMessage(),
                ]);
                $this->markCheckpoint($table, 'error', $e->getMessage(), 0);
            }
        }

        return $ok ? self::SUCCESS : self::FAILURE;
    }

    protected function canConnect(): bool
    {
        try {
            DB::connection('supabase')->select('select 1');

            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    protected function syncTable(string $table, bool $full, int $chunkSize): int
    {
        $checkpoint = DB::table('sync_checkpoints')->where('table_name', $table)->first();
        $since = (! $full && $checkpoint?->last_synced_at)
            ? $checkpoint->last_synced_at
            : null;

        $query = DB::table($table)->orderBy('id');
        if ($since && Schema::hasColumn($table, 'updated_at')) {
            $query->where('updated_at', '>', $since);
        }

        $total = 0;
        $maxId = $checkpoint->last_id ?? null;
        $syncedAt = now();

        $query->chunkById($chunkSize, function ($rows) use ($table, &$total, &$maxId) {
            $payload = [];
            foreach ($rows as $row) {
                $data = (array) $row;
                // Keep password hashes for admin recovery on cloud; never log them.
                $payload[] = $data;
                $maxId = max((int) $maxId, (int) $row->id);
            }

            if ($payload === []) {
                return;
            }

            DB::connection('supabase')->transaction(function () use ($table, $payload) {
                foreach ($payload as $row) {
                    DB::connection('supabase')->table($table)->upsert(
                        $row,
                        ['id'],
                        array_values(array_diff(array_keys($row), ['id']))
                    );
                }
            });

            $total += count($payload);
        });

        DB::table('sync_checkpoints')->updateOrInsert(
            ['table_name' => $table],
            [
                'last_synced_at' => $syncedAt,
                'last_id' => $maxId,
                'status' => 'ok',
                'error' => null,
                'rows_synced' => $total,
                'updated_at' => now(),
                'created_at' => $checkpoint->created_at ?? now(),
            ]
        );

        return $total;
    }

    protected function markCheckpoint(string $table, string $status, ?string $error, int $rows): void
    {
        DB::table('sync_checkpoints')->updateOrInsert(
            ['table_name' => $table],
            [
                'status' => $status,
                'error' => $error,
                'rows_synced' => $rows,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
