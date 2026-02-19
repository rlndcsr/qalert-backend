<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use App\Models\QueueEntry;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with(['user', 'schedule'])->get();

        return response()->json([
            'appointments' => $appointments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        $validated = $request->validated();

        // Check if user already has an active (non-cancelled) queue entry for this schedule and date
        $existingActiveQueueEntry = QueueEntry::where('user_id', $validated['user_id'])
            ->where('schedule_id', $validated['schedule_id'])
            ->where('date', $validated['appointment_date'])
            ->whereNotIn('queue_status', ['cancelled'])
            ->first();

        if ($existingActiveQueueEntry) {
            return response()->json([
                'message' => 'User already has an active queue entry for this schedule and date.'
            ], 422);
        }

        // Also check if user already has an active (non-cancelled) appointment for this date
        $existingActiveAppointment = Appointment::where('user_id', $validated['user_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->whereNotIn('status', ['cancelled'])
            ->first();

        if ($existingActiveAppointment) {
            return response()->json([
                'message' => 'User already has an active appointment for this date.'
            ], 422);
        }

        // Use transaction to ensure both appointment and queue entry are created
        $result = DB::transaction(function () use ($validated) {
            // Create appointment with confirmed status
            $validated['status'] = 'confirmed';
            $appointment = Appointment::create($validated);

            // Determine queue number based on appointment time order
            $queueNumber = $this->determineQueueNumber(
                $validated['schedule_id'],
                $validated['appointment_date'],
                $validated['appointment_time'],
                $appointment->appointment_id
            );

            // Create queue entry
            $queueEntry = QueueEntry::create([
                'user_id'             => $validated['user_id'],
                'schedule_id'         => $validated['schedule_id'],
                'queue_number'        => $queueNumber,
                'queue_status'        => 'waiting',
                'estimated_time_wait' => null,
                'reason'              => 'Appointment',
                'reason_category_id'  => $validated['reason_category_id'] ?? null,
                'date'                => $validated['appointment_date'],
                'appointment_id'      => $appointment->appointment_id,
            ]);

            return [
                'appointment'  => $appointment,
                'queue_entry'  => $queueEntry,
            ];
        });

        return response()->json([
            'message'     => 'Appointment created and queue entry assigned',
            'appointment' => $result['appointment'],
            'queue_entry' => $result['queue_entry'],
        ], 201);
    }

    /**
     * Determine queue number based on appointment time order.
     * For same appointment times, later arrivals get higher queue numbers.
     */
    private function determineQueueNumber(int $scheduleId, string $date, string $time, int $currentAppointmentId): int
    {
        // Normalize the input time to H:i:s format for consistent comparison
        $normalizedTime = \Carbon\Carbon::parse($time)->format('H:i:s');

        // Count how many OTHER active appointments have an earlier time
        $earlierCount = Appointment::where('schedule_id', $scheduleId)
            ->where('appointment_date', $date)
            ->where('appointment_id', '!=', $currentAppointmentId)
            ->whereNotIn('status', ['cancelled'])
            ->whereRaw('TIME(appointment_time) < ?', [$normalizedTime])
            ->count();

        // Count OTHER appointments with the same time (they were created before this one, so they get priority)
        $sameTimeCount = Appointment::where('schedule_id', $scheduleId)
            ->where('appointment_date', $date)
            ->where('appointment_id', '!=', $currentAppointmentId)
            ->whereNotIn('status', ['cancelled'])
            ->whereRaw('TIME(appointment_time) = ?', [$normalizedTime])
            ->count();

        // Position = earlier appointments + same-time appointments + 1 (for 1-based numbering)
        $position = $earlierCount + $sameTimeCount + 1;

        // Reorder existing queue entries if needed
        $this->reorderQueueEntries($scheduleId, $date, $position);

        return $position;
    }

    /**
     * Reorder queue entries when a new appointment is inserted.
     */
    private function reorderQueueEntries(int $scheduleId, string $date, int $newPosition): void
    {
        // Get all active (non-cancelled) queue entries for this schedule and date with queue_number >= newPosition
        QueueEntry::where('schedule_id', $scheduleId)
            ->where('date', $date)
            ->where('queue_number', '>=', $newPosition)
            ->whereNotIn('queue_status', ['cancelled'])
            ->increment('queue_number');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $appointment = Appointment::with(['user', 'schedule'])->findOrFail($id);

        return response()->json([
            'appointment' => $appointment
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppointmentRequest $request, string $id)
    {
        $appointment = Appointment::findOrFail($id);
        $validated = $request->validated();

        // If status is being changed to cancelled, cancel the queue entry as well
        if (isset($validated['status']) && $validated['status'] === 'cancelled') {
            $this->cancelQueueEntry($appointment);
        }

        $appointment->update($validated);

        return response()->json([
            'message'     => 'Appointment updated successfully',
            'appointment' => $appointment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment::findOrFail($id);

        // Cancel or remove the associated queue entry
        $this->cancelQueueEntry($appointment);

        $appointment->delete();

        return response()->json([
            'message' => 'Appointment deleted successfully',
        ]);
    }

    /**
     * Cancel the queue entry associated with an appointment.
     */
    private function cancelQueueEntry(Appointment $appointment): void
    {
        $queueEntry = QueueEntry::where('appointment_id', $appointment->appointment_id)
            ->first();

        if ($queueEntry) {
            $queueEntry->update(['queue_status' => 'cancelled']);
        }
    }
}
