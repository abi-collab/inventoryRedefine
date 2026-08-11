<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SyncStatusController extends Controller
{
    public function status()
    {
        $rows = DB::table('sync_checkpoints')->orderBy('table_name')->get();
        $last = $rows->max('last_synced_at');
        $failed = $rows->firstWhere('status', 'error');

        return response()->json([
            'enabled' => filter_var(env('SUPABASE_SYNC_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
            'last_synced_at' => $last,
            'has_errors' => (bool) $failed,
            'checkpoints' => $rows,
        ]);
    }

    public function syncNow(Request $request)
    {
        $key = 'sync_now_'.auth()->id();
        if (Cache::has($key)) {
            return response()->json([
                'ok' => false,
                'error' => 'Sync was just triggered. Wait 60 seconds before retrying.',
            ], 429);
        }

        Cache::put($key, true, 60);

        $exit = Artisan::call('sync:supabase', ['--force' => true]);

        return response()->json([
            'ok' => $exit === 0,
            'output' => Artisan::output(),
        ], $exit === 0 ? 200 : 500);
    }
}
