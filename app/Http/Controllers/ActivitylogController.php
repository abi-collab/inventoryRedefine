<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivitylogController extends Controller
{
    public function savelog(Request $request)
    {
        $log = new ActivityLog;
        $log->activity = $request->activity;
        $log->createdby = $request->createdby;
        $log->save();
    }

    public function getlog(Request $request)
    {
        $log = DB::table('activitylogs')->orderBy('id', 'DESC')->get();

        return response()->json($log);
    }
}
