<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'age' => 'required|integer|min:1|max:120',
            'address' => 'required|max:255',
            'contact_no' => 'required|max:255',
            'education_attainment' => 'required|max:255',
            'role' => 'max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
        try {
            $fields['role'] = $fields['role'] ?? 'guest';

            $user = User::create([
                'firstname' => $fields['firstname'],
                'lastname' => $fields['lastname'],
                'age' => $fields['age'],
                'address' => $fields['address'],
                'contact_no' => $fields['contact_no'],
                'education_attainment' => $fields['education_attainment'],
                'role' => $fields['role'],
                'email' => $fields['email'],
                'password' => Hash::make($fields['password']),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'message' => $e
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        try {

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return [
                    "message" => 'The provided credentials are incorrect.'
                ];
            }

            $token = $user->createToken("auth_token");

            $user->makeHidden(['created_at', 'updated_at']);

            return [
                'user' => $user,
                'token' => $token->plainTextToken
            ];
        } catch (\Exception $e) {

            return response()->json([
                'message' => $e
            ], 500);
        }
    }



    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return [
                "message" => "You are logout."
            ];
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e
            ], 500);
        }
    }
}
