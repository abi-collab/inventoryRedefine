<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // public function savelog(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'activity' => 'required|max:255',
    //         'user_id' => 'required'
    //     ]);

    //     $log = new activitylog;
    //     $log->activity = $request->activity;
    //     $log->user_id = $request->user_id;
    //     $log->save();
    // }

    public function getUsers(Request $request)
    {
        $users=DB::table('users')->orderBy('id','DESC')->get();
        return response()->json($users);
    }
}
