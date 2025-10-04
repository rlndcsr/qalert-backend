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
        $user = User::where('email_address', $request->email_address)->first();
 
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email_address' => ['The provided credentials are incorrect.'],
            ]);
        }

        $response = [
            'user'  => $user,
            'token' => $user->createToken($request->email_address)->plainTextToken
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
