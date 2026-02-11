<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QAlert Monthly Analytics Report - {{ $month }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            padding: 20px;
        }

        /* Header Styles */
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .clinic-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 18px;
            color: #374151;
            margin-bottom: 5px;
        }

        .report-month {
            font-size: 14px;
            color: #6b7280;
        }

        /* Summary Section */
        .summary-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .summary-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-grid td {
            padding: 10px 15px;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
        }

        .summary-label {
            font-weight: bold;
            color: #374151;
        }

        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #2563eb;
            text-align: right;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 9px;
        }

        .data-table th {
            background-color: #2563eb;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1e40af;
        }

        .data-table td {
            padding: 6px;
            border: 1px solid #e5e7eb;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f3f4f6;
        }

        .data-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .data-table tbody tr:hover {
            background-color: #e5e7eb;
        }

        /* Status badges */
        .status-completed {
            color: #059669;
            font-weight: bold;
        }

        .status-pending {
            color: #d97706;
            font-weight: bold;
        }

        .status-cancelled {
            color: #dc2626;
            font-weight: bold;
        }

        .status-serving {
            color: #2563eb;
            font-weight: bold;
        }

        .status-waiting {
            color: #6b7280;
            font-weight: bold;
        }

        /* No data message */
        .no-data {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-style: italic;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            margin-bottom: 25px;
        }

        /* Footer Styles */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 8px;
            color: #6b7280;
        }

        .footer-content {
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            text-align: left;
        }

        .footer-right {
            display: table-cell;
            text-align: right;
        }

        /* Page break handling */
        .page-break {
            page-break-after: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="clinic-name">QAlert Clinic</div>
            <div class="report-title">Monthly Analytics Report</div>
            <div class="report-month">{{ $month }}</div>
        </div>

        <!-- Summary Section -->
        <div class="summary-section avoid-break">
            <div class="section-title">Summary / Metrics</div>
            <table class="summary-grid">
                <tr>
                    <td class="summary-label">Total Appointments</td>
                    <td class="summary-value">{{ $summary['total_appointments'] }}</td>
                    <td class="summary-label">Total Queue Entries</td>
                    <td class="summary-value">{{ $summary['total_queues'] }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Completed Appointments</td>
                    <td class="summary-value" style="color: #059669;">{{ $summary['completed_appointments'] }}</td>
                    <td class="summary-label">Pending Appointments</td>
                    <td class="summary-value" style="color: #d97706;">{{ $summary['pending_appointments'] }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Cancelled Appointments</td>
                    <td class="summary-value" style="color: #dc2626;">{{ $summary['cancelled_appointments'] }}</td>
                    <td class="summary-label">Emergency Encounters</td>
                    <td class="summary-value" style="color: #7c3aed;">{{ $summary['emergency_encounters_count'] }}</td>
                </tr>
            </table>
        </div>

        <!-- Appointments Section -->
        <div class="avoid-break">
            <div class="section-title">Appointments</div>
            @if($appointments->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 8%;">#</th>
                            <th style="width: 15%;">Date & Time</th>
                            <th style="width: 20%;">Patient Name</th>
                            <th style="width: 22%;">Email</th>
                            <th style="width: 20%;">Reason / Purpose</th>
                            <th style="width: 15%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $index => $appointment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $appointment->appointment_date ? $appointment->appointment_date->format('M d, Y') : 'N/A' }}
                                    <br>
                                    {{ $appointment->appointment_time ? \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') : 'N/A' }}
                                </td>
                                <td>{{ $appointment->user->name ?? 'N/A' }}</td>
                                <td>{{ $appointment->user->email_address ?? 'N/A' }}</td>
                                <td>{{ $appointment->reasonCategory->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-{{ strtolower($appointment->status ?? 'pending') }}">
                                        {{ ucfirst($appointment->status ?? 'Pending') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">No appointments found for {{ $month }}</div>
            @endif
        </div>

        <!-- Queue Entries Section -->
        <div class="avoid-break">
            <div class="section-title">Queue Entries</div>
            @if($queueEntries->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 8%;">#</th>
                            <th style="width: 15%;">Queue Number</th>
                            <th style="width: 17%;">Date</th>
                            <th style="width: 20%;">Patient Name</th>
                            <th style="width: 20%;">Queue Status</th>
                            <th style="width: 20%;">Appointment ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($queueEntries as $index => $queue)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $queue->queue_number ?? 'N/A' }}</td>
                                <td>{{ $queue->date ? \Carbon\Carbon::parse($queue->date)->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $queue->user->name ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $statusClass = match(strtolower($queue->queue_status ?? '')) {
                                            'completed' => 'status-completed',
                                            'serving' => 'status-serving',
                                            'waiting' => 'status-waiting',
                                            'cancelled' => 'status-cancelled',
                                            default => 'status-pending'
                                        };
                                    @endphp
                                    <span class="{{ $statusClass }}">
                                        {{ ucfirst($queue->queue_status ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>{{ $queue->appointment_id ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">No queue entries found for {{ $month }}</div>
            @endif
        </div>

        <!-- Emergency Encounters Section -->
        <div class="avoid-break">
            <div class="section-title">Emergency Encounters</div>
            @if($emergencyEncounters->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 6%;">#</th>
                            <th style="width: 18%;">Patient Name</th>
                            <th style="width: 15%;">Contact</th>
                            <th style="width: 18%;">Date & Time</th>
                            <th style="width: 43%;">Details / Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($emergencyEncounters as $index => $encounter)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $encounter->patient_name ?? 'N/A' }}</td>
                                <td>{{ $encounter->contact_number ?? 'N/A' }}</td>
                                <td>
                                    {{ $encounter->date ? $encounter->date->format('M d, Y') : 'N/A' }}
                                    <br>
                                    {{ $encounter->time ? \Carbon\Carbon::parse($encounter->time)->format('h:i A') : 'N/A' }}
                                </td>
                                <td>{{ $encounter->details ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">No emergency encounters found for {{ $month }}</div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-content">
            <div class="footer-left">
                Generated on: {{ $generatedAt }}
            </div>
            <div class="footer-right">
                QAlert Clinic - Monthly Analytics Report
            </div>
        </div>
    </div>
</body>
</html>
