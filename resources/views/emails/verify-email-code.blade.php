<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QAlert Email Verification</title>
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
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #4CAF50;
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border: 2px dashed #4CAF50;
            border-radius: 5px;
            margin: 20px 0;
            letter-spacing: 5px;
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
    </div>
    <div class="content">
        <h2>Hello, {{ $name }}!</h2>
        
        <p>Thank you for registering with QAlert. To complete your registration, please verify your email address using the verification code below:</p>
        
        <div class="code">{{ $code }}</div>
        
        <p><strong>Instructions:</strong></p>
        <ul>
            <li>Enter this 6-digit code in the verification form.</li>
            <li>This code will expire after a reasonable time for security purposes.</li>
            <li>If you did not create an account with QAlert, please ignore this email.</li>
        </ul>
        
        <p>If you have any questions or need assistance, please contact our support team.</p>
        
        <p>Best regards,<br>The QAlert Team</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} QAlert. All rights reserved.</p>
        <p>This is an automated message, please do not reply directly to this email.</p>
    </div>
</body>
</html>
