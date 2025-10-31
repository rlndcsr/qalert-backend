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
}
