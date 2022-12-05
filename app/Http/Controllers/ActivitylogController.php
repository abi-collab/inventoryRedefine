<?php

namespace App\Http\Controllers;
use App\activitylog;
use Illuminate\Http\Request;

class ActivitylogController extends Controller
{
    public function savelog(Request $request)
    {
        $validatedData = $request->validate([
            'activity' => 'required|max:255',
            'user_id' => 'required'
        ]);

        $log = new activitylog;
        $log->activity = $request->activity;
        $log->user_id = $request->user_id;
        $log->save();
    }
}
