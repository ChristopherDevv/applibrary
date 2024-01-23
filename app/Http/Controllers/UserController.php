<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try{
            $request->validate([
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:6|max:255'
            ]);

            if(!auth()->attempt($request->only('email', 'password'))){
                return response()->json([
                    'message' => 'Invalid login details'
                ], 401);
            }

            $access_token = auth()->user()->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'user login successfully',
                'user' => auth()->user(),
                'access_token' => $access_token,
                'token_type' => 'Bearer'
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error to login user',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function register(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:6|confirmed|max:255'
            ]);

            $user = new User();
            $user->name = $request->name;
            $user->email = strtolower($request->email);
            $user->password = bcrypt($request->password);
            $user->role_id = Role::where('role_name', 'member')->first()->id;
            $user->save();

            //asignamos el token al usuario
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'user created successfully',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 201);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error to create user',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function logout()
    {
        try{
            auth()->user()->tokens()->delete();
            return response()->json([
                'message' => 'user logout successfully'
            ], 200);

        } catch(\Exception $e){
            return response()->json([
                'message' => 'Error to logout user',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    
}
