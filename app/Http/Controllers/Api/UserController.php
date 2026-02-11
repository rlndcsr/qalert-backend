<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Mail\VerifyEmailCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\UserRequest;

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
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        if (empty($validated['role'])) {
            $validated['role'] = 'patient';
        }

        // Generate 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Add verification fields
        $validated['email_verification_code'] = $verificationCode;
        $validated['email_verified_at'] = null;

        $user = User::create($validated);

        // Send verification email
        Mail::to($user->email_address)->send(new VerifyEmailCodeMail($verificationCode, $user->name));
        
        return response()->json([
            'message' => 'Verification code sent to your email',
            'email' => $user->email_address
        ]);
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
    public function update(UserRequest $request, string $id)
    {
        $validated = $request->validated();

        $user = User::findOrFail($id);

        $user->update($validated);
        
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();
        
        return response()->json([
            'message' => 'User deleted successfully',
            'user' => $user
        ]);
    }

    /**
     * Verify user's email with verification code.
     */
    public function verifyEmail(Request $request)
    {
        $validated = $request->validate([
            'email_address' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $user = User::where('email_address', $validated['email_address'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Email is already verified.'
            ], 400);
        }

        if ($user->email_verification_code !== $validated['code']) {
            return response()->json([
                'message' => 'Invalid verification code.'
            ], 400);
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_code' => null,
        ]);

        return response()->json([
            'message' => 'Email verified successfully'
        ]);
    }

    /**
     * Resend verification code to user's email.
     */
    public function resendVerificationCode(Request $request)
    {
        $validated = $request->validate([
            'email_address' => 'required|email',
        ]);

        $user = User::where('email_address', $validated['email_address'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Email is already verified.'
            ], 400);
        }

        // Generate new 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'email_verification_code' => $verificationCode,
        ]);

        // Send verification email
        Mail::to($user->email_address)->send(new VerifyEmailCodeMail($verificationCode, $user->name));

        return response()->json([
            'message' => 'Verification code sent to your email',
            'email' => $user->email_address
        ]);
    }
}
