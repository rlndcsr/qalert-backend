<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login using the specified resource.
     */
    public function login(UserRequest $request)
    {
        $loginInput = $request->input('login');

        // Determine if input is an email or ID number
        $user = filter_var($loginInput, FILTER_VALIDATE_EMAIL)
            ? User::where('email_address', $loginInput)->first()
            : User::where('id_number', $loginInput)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if email is verified
        if (is_null($user->email_verified_at)) {
            return response()->json([
                'message' => 'Please verify your email before logging in.'
            ], 403);
        }

        $response = [
            'user'  => $user,
            'token' => $user->createToken($loginInput)->plainTextToken
        ];

        return $response;
    }

     /**
     * Logout using the specified resource.
     */
    public function Logout(Request $request)
    {
       $request->user()->tokens()->delete();

       $response = [
        'message' => 'Logged out'
       ];

        return $response;
    }

    /**
     * Admin login with email_address and password only.
     * Returns a Sanctum token named 'adminToken'.
     */
    public function adminLogin(Request $request)
    {
        $validated = $request->validate([
            'email_address' => ['required','email'],
            'password' => ['required']
        ]);

        $user = User::where('email_address', $validated['email_address'])
            ->where('role', 'admin')
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email_address' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if email is verified
        if (is_null($user->email_verified_at)) {
            return response()->json([
                'message' => 'Please verify your email before logging in.'
            ], 403);
        }

        $token = $user->createToken('adminToken')->plainTextToken;

        return [
            'user' => $user,
            'adminToken' => $token,
        ];
    }
}
