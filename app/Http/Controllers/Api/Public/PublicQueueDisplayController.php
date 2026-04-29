<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\QueueEntry;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Models\ReasonCategory;
use App\Models\DoctorSchedule;
use App\Models\EmergencyEncounter;
use Illuminate\Http\Request;

class PublicQueueDisplayController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        $todayDay = $dayNames[now()->dayOfWeek];

        // Queues — today's queue entries
        $queues = QueueEntry::where('date', $today)
            ->get(['queue_entry_id', 'queue_number', 'queue_status', 'user_id', 'appointment_id', 'reason_category_id']);

        // Collect IDs for filtering related data
        $userIds = $queues->pluck('user_id')->filter()->unique();
        $appointmentIds = $queues->pluck('appointment_id')->filter()->unique();

        // Users — only those in today's queues
        $users = User::whereIn('user_id', $userIds)
            ->get(['user_id', 'id_number', 'name', 'phone_number']);

        // Appointments — only linked to today's queues
        $appointments = Appointment::whereIn('appointment_id', $appointmentIds)
            ->get(['appointment_id', 'appointment_time', 'schedule_id', 'doctor_id']);

        // Schedules — ALL schedules for today (AM + PM), not just ones with queue entries
        $schedules = Schedule::where('day', $todayDay)
            ->get(['schedule_id', 'shift', 'day']);

        $scheduleIds = $schedules->pluck('schedule_id')->unique();

        // Doctor schedules — linked to ALL today's schedules (not just queue-linked ones)
        $doctorSchedules = DoctorSchedule::whereIn('schedule_id', $scheduleIds)
            ->get(['schedule_id', 'doctor_id']);

        // Doctors — linked to today's doctor schedules (gets ALL doctors for the day)
        $doctorIds = $doctorSchedules->pluck('doctor_id')->filter()->unique();
        $doctors = Doctor::whereIn('doctor_id', $doctorIds)
            ->get(['doctor_id', 'doctor_name']);

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
            'reason_categories' => ReasonCategory::all(),
        ]);
    }
}