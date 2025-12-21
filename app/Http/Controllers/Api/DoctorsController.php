<?php

namespace App\Http\Controllers\Api;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorsRequest;

class DoctorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Doctor::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DoctorsRequest $request)
    {
        $validated = $request->validated();

        $doctor = Doctor::create($validated);

        return response()->json([
            'message' => 'Doctor created successfully',
            'doctor' => $doctor
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Doctor::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DoctorsRequest $request, string $id)
    {
        $validated = $request->validated();

        $doctor = Doctor::findOrFail($id);

        $doctor->update($validated);

        return response()->json([
            'message' => 'Doctor updated successfully',
            'doctor' => $doctor
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
