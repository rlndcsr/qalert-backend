<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Your Appointment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .details {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .details p {
            margin: 5px 0;
        }
        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>QAlert</h1>
        <p>Appointment Confirmation</p>
    </div>
    <div class="content">
        <p>Hello {{ $appointment->user->first_name ?? 'there' }},</p>
        <p>You have booked an appointment. Please confirm it by clicking the button below:</p>

        <div class="details">
            <p><strong>Date:</strong> {{ $appointment->appointment_date->format('F j, Y') }}</p>
            <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>
        </div>

        <div class="text-center">
            <a href="{{ config('app.frontend_url', config('app.url')) }}/appointments/confirm?token={{ $token }}" class="btn">
                Confirm Appointment
            </a>
        </div>

        <p>This confirmation link will expire in <strong>24 hours</strong>.</p>
        <p>If you did not book this appointment, you can safely ignore this email.</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} QAlert. All rights reserved.</p>
    </div>
</body>
</html>
