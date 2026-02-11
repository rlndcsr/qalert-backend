<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

// Temporary test route for email verification
Route::get('/test-email', function () {
    $testEmail = 'qalertgpt@gmail.com'; // Change this to your test email address
    
    Mail::raw('This is a test email from QAlert.', function ($message) use ($testEmail) {
        $message->to($testEmail)
                ->subject('QAlert Test Email');
    });

    return 'Test email sent';
});
