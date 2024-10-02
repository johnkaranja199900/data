<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

// Route to the homepage
Route::get('/', function () {
    return view('index');
});

// Route to initiate M-PESA payment
Route::post('/initiate-payment', [PaymentController::class, 'initiatePayment'])->name('initiatePayment');

// Route to handle M-PESA callback
Route::post('/callback', [PaymentController::class, 'handlePaymentCallback']);

// Route for registering M-PESA URLs
Route::get('/mpesa/register-urls', [PaymentController::class, 'registerMpesaUrls'])->name('mpesa.register.urls');

// M-PESA validation and confirmation callback routes
Route::post('/mpesa/validation', [PaymentController::class, 'handleValidation'])->name('mpesa.validation');
Route::post('/mpesa/confirmation', [PaymentController::class, 'handleConfirmation'])->name('mpesa.confirmation');
