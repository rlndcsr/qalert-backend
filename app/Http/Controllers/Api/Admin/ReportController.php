<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\EmergencyEncounter;
use App\Models\QueueEntry;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * Generate and download monthly analytics PDF report.
     *
     * @param Request $request
     * @param PDF $pdf
     * @return \Illuminate\Http\Response
     */
    public function generatePdf(Request $request, PDF $pdf)
    {
        // Validate month parameter
        $validator = Validator::make($request->all(), [
            'month' => 'required|date_format:Y-m',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid month format. Use YYYY-MM format.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $month = $request->input('month');
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        // Fetch appointments for the month
        $appointments = Appointment::with(['user', 'schedule', 'reasonCategory'])
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        // Fetch queue entries for the month
        $queueEntries = QueueEntry::with(['user', 'appointment', 'reasonCategory'])
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('queue_number')
            ->get();

        // Fetch emergency encounters for the month
        $emergencyEncounters = EmergencyEncounter::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        // Calculate summary statistics
        $summary = [
            'total_appointments' => $appointments->count(),
            'completed_appointments' => $appointments->where('status', 'completed')->count(),
            'cancelled_appointments' => $appointments->where('status', 'cancelled')->count(),
            'pending_appointments' => $appointments->where('status', 'pending')->count(),
            'total_queues' => $queueEntries->count(),
            'emergency_encounters_count' => $emergencyEncounters->count(),
        ];

        // Format month for display
        $formattedMonth = $startDate->format('F Y');

        // Generate PDF
        $pdf->loadView('reports.monthly-analytics', [
            'appointments' => $appointments,
            'queueEntries' => $queueEntries,
            'emergencyEncounters' => $emergencyEncounters,
            'summary' => $summary,
            'month' => $formattedMonth,
            'generatedAt' => Carbon::now()->format('F d, Y h:i A'),
        ]);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Generate filename
        $filename = 'QAlert_Report_' . $startDate->format('F') . '_' . $startDate->format('Y') . '.pdf';

        return $pdf->download($filename);
    }
}
