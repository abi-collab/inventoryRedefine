<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        $users=DB::table('users')->orderBy('id','DESC')->get();
        return response()->json($users);
    }

    public function updateUser(Request $request, $id)
    {
        $data = array();
        $data['name']=$request->name;
        $data['username']=$request->username;
        $data['user_role']=$request->user_role;
       
        DB::table('users')->where('id',$id)->update($data);
        
    }

    public function getTheUser($id)
    {
        $user=DB::table('users')->where('id',$id)->first();
        return response()->json($user);
    }
}
