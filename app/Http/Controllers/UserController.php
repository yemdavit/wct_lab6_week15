<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                "name" => "required|string",
                "email" => "required|email",
                "password" => "required|string"
            ]);
            $user = new User();
            $user->name = $request->first_name ;
            $user->name = $request->last_name ;
             // Assuming name is a combination of first and last name
            
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json(["message" => "User created successfully"], 201);
        } catch (\Throwable $th) {
            return error_response($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return User::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                "name" => "required|string",
                "email" => "required|email",
                "password" => "required|string"
            ]);
            $user = User::findOrFail($id);
             $user->name = $request->name ; // Assuming name is a combination of first and last name
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json(["message" => "User updated successfully"], 200);
        } catch (\Throwable $th) {
            return error_response($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            User::findOrFail($id)->delete();
            return response()->json(["message" => "User deleted successfully"], 200);
        } catch (\Throwable $th) {
            return error_response($th);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                "email" => "required|email",
                "password" => "required|string"
            ]);
            $user = User::where("email", $request->email)->first();
            if (!$user) {
                return response()->json(["message" => "User not found"], 404);
            }
            if (!password_verify($request->password, $user->password)) {
                return response()->json(["message" => "Invalid password"], 401);
            }
            return response()->json(["message" => "Login successful"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], 500);
        }
    }
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(["message" => "Logout successful"], 200);
        } catch (\Throwable $th) {
            return error_response($th);
        }
    }
    public function register(Request $request)
    {
        try {
            $request->validate([
                "name" => "required|string",
                "email" => "required|email|unique:users,email",
                "password" => "required|string|min:8"
            ]);
            $user = new User();
            $user->name = $request->name ;
    

            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json(["message" => "User registered successfully"], 201);
        } catch (\Throwable $th) {
            return error_response($th);
        }
    }
}
