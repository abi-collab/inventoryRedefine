<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupSqlite extends Command
{
    protected $signature = 'backup:sqlite
                            {--keep=14 : Number of backup files to retain}';

    protected $description = 'Copy local SQLite database into storage/app/backups with rotation';

    public function handle(): int
    {
        $source = database_path('database.sqlite');
        if (! is_file($source)) {
            $configured = config('database.connections.sqlite.database');
            $source = $configured && $configured !== ':memory:' ? $configured : $source;
        }

        if (! is_file($source)) {
            $this->error('SQLite database not found at '.$source);

            return self::FAILURE;
        }

        $dir = storage_path('app/backups');
        File::ensureDirectoryExists($dir);

        $stamp = now()->format('Y-m-d_His');
        $dest = $dir.DIRECTORY_SEPARATOR.'database_'.$stamp.'.sqlite';
        if (! copy($source, $dest)) {
            $this->error('Failed to copy database to '.$dest);

            return self::FAILURE;
        }

        $this->info('Backup written: '.$dest);

        $keep = max(1, (int) $this->option('keep'));
        $files = collect(File::files($dir))
            ->filter(fn ($f) => str_starts_with($f->getFilename(), 'database_') && str_ends_with($f->getFilename(), '.sqlite'))
            ->sortByDesc(fn ($f) => $f->getMTime())
            ->values();

        foreach ($files->slice($keep) as $old) {
            File::delete($old->getPathname());
            $this->line('Removed old backup: '.$old->getFilename());
        }

        return self::SUCCESS;
    }
}
