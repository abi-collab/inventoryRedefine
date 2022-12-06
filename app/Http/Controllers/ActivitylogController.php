<?php

namespace App\Http\Controllers;
use App\activitylog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivitylogController extends Controller
{
    public function savelog(Request $request)
    {
        // $validatedData = $request->validate([
        //     'activity' => 'required|max:255',
        //     'user_id' => 'required'
        // ]);

        $log = new activitylog;
        $log->activity = $request->activity;
        $log->createdby = $request->createdby;
        $log->save();
    }

    public function getlog(Request $request)
    {
        $log=DB::table('activitylogs')->orderBy('id','DESC')->get();
        return response()->json($log);
    }
}
