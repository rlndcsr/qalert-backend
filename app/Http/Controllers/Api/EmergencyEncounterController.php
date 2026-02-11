<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmergencyEncounterRequest;
use App\Models\EmergencyEncounter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmergencyEncounterController extends Controller
{
    /**
     * Display a listing of emergency encounters.
     * Optionally filter by date using ?date=YYYY-MM-DD query parameter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = EmergencyEncounter::query();

        // Optional date filter
        if ($request->has('date')) {
            $query->whereDate('date', $request->input('date'));
        }

        $encounters = $query->orderBy('date', 'desc')
                            ->orderBy('time', 'desc')
                            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Emergency encounters retrieved successfully.',
            'data'    => $encounters,
        ], 200);
    }

    /**
     * Store a newly created emergency encounter.
     *
     * @param  \App\Http\Requests\EmergencyEncounterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EmergencyEncounterRequest $request): JsonResponse
    {
        $encounter = EmergencyEncounter::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Emergency encounter recorded successfully.',
            'data'    => $encounter,
        ], 201);
    }

    /**
     * Display the specified emergency encounter.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $encounter = EmergencyEncounter::find($id);

        if (!$encounter) {
            return response()->json([
                'success' => false,
                'message' => 'Emergency encounter not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Emergency encounter retrieved successfully.',
            'data'    => $encounter,
        ], 200);
    }

    /**
     * Update the specified emergency encounter.
     *
     * @param  \App\Http\Requests\EmergencyEncounterRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EmergencyEncounterRequest $request, int $id): JsonResponse
    {
        $encounter = EmergencyEncounter::find($id);

        if (!$encounter) {
            return response()->json([
                'success' => false,
                'message' => 'Emergency encounter not found.',
            ], 404);
        }

        $encounter->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Emergency encounter updated successfully.',
            'data'    => $encounter,
        ], 200);
    }

    /**
     * Remove the specified emergency encounter.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $encounter = EmergencyEncounter::find($id);

        if (!$encounter) {
            return response()->json([
                'success' => false,
                'message' => 'Emergency encounter not found.',
            ], 404);
        }

        $encounter->delete();

        return response()->json([
            'success' => true,
            'message' => 'Emergency encounter deleted successfully.',
        ], 200);
    }
}
