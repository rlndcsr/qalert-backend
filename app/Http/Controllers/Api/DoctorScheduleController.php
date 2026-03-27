<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoctorSchedule;

class DoctorScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DoctorSchedule::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|integer|exists:schedules,schedule_id',
            'doctor_id' => 'required|integer|exists:doctors,doctor_id',
        ]);

        $doctorSchedule = DoctorSchedule::create($validated);
        return response()->json($doctorSchedule, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doctorSchedule = DoctorSchedule::find($id);
        if (!$doctorSchedule) {
            return response()->json(['message' => 'DoctorSchedule not found'], 404);
        }
        return response()->json($doctorSchedule);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $doctorSchedule = DoctorSchedule::find($id);
        if (!$doctorSchedule) {
            return response()->json(['message' => 'DoctorSchedule not found'], 404);
        }

        $validated = $request->validate([
            'schedule_id' => 'sometimes|required|integer|exists:schedules,schedule_id',
            'doctor_id' => 'sometimes|required|integer|exists:doctors,doctor_id',
        ]);

        $doctorSchedule->update($validated);
        return response()->json($doctorSchedule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $doctorSchedule = DoctorSchedule::find($id);
        if (!$doctorSchedule) {
            return response()->json(['message' => 'DoctorSchedule not found'], 404);
        }
        $doctorSchedule->delete();
        return response()->json(['message' => 'DoctorSchedule deleted successfully']);
    }
}
