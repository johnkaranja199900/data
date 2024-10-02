<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// M-PESA Callback Route
Route::post('/mpesa/callback', function (Request $request) {
    Log::info('Received M-PESA Callback:', $request->all());
    return response()->json(['status' => 'success']);
});
