<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;    //-----------
use DB;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login']]);      //------was default------
        //$this->middleware('auth:api', ['except' => ['login','signup']]);     //-------------
        $this->middleware('JWT', ['except' => ['login','signup']]);     //-------------
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validateData = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'min:8'],
        ]);

        $credentials = request(['username', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Username or Password is Invalid'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
       
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    public function signup(Request $request)
    {
        return response()->json([
            'error' => 'Public signup is disabled. Ask an admin to create your account.',
        ], 403);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([           //---------------------------
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'name'         => auth()->user()->name,             //--OR-- Auth::user()->name
            'user_id'      => auth()->user()->id,
            'email'        => auth()->user()->email,            //-- Auth::user()->email
            'username'     => auth()->user()->username, 
            'user_role'    => auth()->user()->user_role
        ]);
    }
}
