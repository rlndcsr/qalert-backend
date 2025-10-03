<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        $user = User::create($validated);
        
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
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
}
