<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\QueueEntry;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\EmergencyEncounter;
use Illuminate\Http\Request;

class PublicQueueDisplayController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // Queues — today's queue entries
        $queues = QueueEntry::where('date', $today)
            ->get(['queue_entry_id', 'queue_number', 'queue_status', 'user_id', 'appointment_id']);

        // Collect IDs for filtering related data
        $userIds = $queues->pluck('user_id')->filter()->unique();
        $appointmentIds = $queues->pluck('appointment_id')->filter()->unique();

        // Users — only those in today's queues
        $users = User::whereIn('user_id', $userIds)
            ->get(['user_id', 'id_number']);

        // Appointments — only linked to today's queues
        $appointments = Appointment::whereIn('appointment_id', $appointmentIds)
            ->get(['appointment_id', 'appointment_time', 'schedule_id', 'doctor_id']);

        $scheduleIds = $appointments->pluck('schedule_id')->filter()->unique();
        $doctorIds = $appointments->pluck('doctor_id')->filter()->unique();

        // Schedules — linked to those appointments
        $schedules = Schedule::whereIn('schedule_id', $scheduleIds)
            ->get(['schedule_id', 'shift', 'day']);

        // Doctors — linked to those appointments
        $doctors = Doctor::whereIn('doctor_id', $doctorIds)
            ->get(['doctor_id', 'doctor_name']);

        // Doctor schedules — linked to those schedules
        $doctorSchedules = DoctorSchedule::whereIn('schedule_id', $scheduleIds)
            ->get(['schedule_id', 'doctor_id']);

        // Emergency encounters — today's active
        $emergencyEncounters = EmergencyEncounter::where('date', $today)
            ->where('status', 'active')
            ->get();

        return response()->json([
            'queues' => $queues,
            'users' => $users,
            'appointments' => $appointments,
            'schedules' => $schedules,
            'doctors' => $doctors,
            'doctor_schedules' => $doctorSchedules,
            'emergency_encounters' => $emergencyEncounters,
        ]);
    }
}