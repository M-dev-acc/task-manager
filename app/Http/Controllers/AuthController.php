<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request) {
        $validatedInputs = $request->validated();

        try {
            User::create([
                'name' => $validatedInputs['name'],
                'email' => $validatedInputs['email'],
                'password' => Hash::make($validatedInputs['password']),
            ]);

            return response()->json([
                'status' => true,
                'message' => "User registered successfully."
            ], 201);

        } catch (\Throwable $th) {
            throw new HttpResponseException(response()->json([
                'status' => false,
                'message' => "Something went wrong!",
            ], 500));
        }
    }

    public function login(LoginUserRequest $request) {
        $validatedInputs = $request->validated();

        $credentials = [
            'email' => $validatedInputs['username'],
            'password' => $validatedInputs['password'],
        ];

        if (!Auth::attempt($credentials)) {
            throw new HttpResponseException(response()->json([
                'status' => false,
                'message' => "Please enter correct password.",
            ], 401)); 
        }

        $authUser = Auth::user();
        
        return response()->json([
            'status' => true,
            'message' => "User logged in!",
            'token' => $authUser->createToken('task-manager')->plainTextToken,
            'user' => [
                'name' => $authUser['name'],
                'email' => $authUser['email'],
            ]
        ], 200);
    }

    public function logout() {
        $authUser = Auth::user();
        $authUser->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => "User logged out."
        ], 204);
    }
}
