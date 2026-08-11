<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        $users = DB::table('users')
            ->select('id', 'name', 'username', 'email', 'user_role', 'created_at', 'updated_at')
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_role' => ['required', 'integer', 'in:1,2'],
        ]);

        $id = DB::table('users')->insertGetId([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_role' => (int) $validated['user_role'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = DB::table('users')
            ->select('id', 'name', 'username', 'email', 'user_role', 'created_at', 'updated_at')
            ->where('id', $id)
            ->first();

        return response()->json($user, 201);
    }

    public function updateUser(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$id],
            'user_role' => ['required', 'integer', 'in:1,2'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'user_role' => (int) $validated['user_role'],
            'updated_at' => now(),
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        DB::table('users')->where('id', $id)->update($data);

        return response()->json(['ok' => true]);
    }

    public function getTheUser($id)
    {
        $user = DB::table('users')
            ->select('id', 'name', 'username', 'email', 'user_role', 'created_at', 'updated_at')
            ->where('id', $id)
            ->first();

        return response()->json($user);
    }
}
