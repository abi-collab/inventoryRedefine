<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class RecordsTombstone
{
    public static function for(string $table, int|string $id): void
    {
        DB::table('sync_tombstones')->insert([
            'table_name' => $table,
            'record_id' => (int) $id,
            'deleted_at' => now(),
        ]);
    }
}
