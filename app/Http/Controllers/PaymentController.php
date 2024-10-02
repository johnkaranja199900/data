<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    // Format phone number into the proper M-PESA format (254...)
    private function formatPhoneNumber($phoneNumber)
    {
        // Remove all non-digit characters
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        // Handle various formats
        if (strlen($phoneNumber) == 10 && $phoneNumber[0] == '0') {
            return '254' . substr($phoneNumber, 1);
        } elseif (strlen($phoneNumber) == 12 && substr($phoneNumber, 0, 3) == '254') {
            return $phoneNumber;
        } elseif (strlen($phoneNumber) == 13 && substr($phoneNumber, 0, 3) == '254') {
            return $phoneNumber;
        } else {
            throw new \InvalidArgumentException('Invalid phone number format.');
        }
    }

    // Method to initiate the payment
    public function initiatePayment(Request $request)
    {
        // Validate inputs
        $request->validate([
            'phone' => 'required|regex:/^\d{10,13}$/',
            'amount' => 'required|numeric|min:1'
        ]);

        $phone = $request->input('phone');
        $amount = (int)$request->input('amount');
        $accountReference = 'Mega Buying bundles'; // Customize as needed
        $phoneNumber = $this->formatPhoneNumber($phone);
        $transactionDesc = 'Payment for internet';

        Log::info('M-PESA Payment Request Parameters:', [
            'phoneNumber' => $phoneNumber,
            'amount' => $amount,
            'accountReference' => $accountReference,
            'transactionDesc' => $transactionDesc,
        ]);

        // Send payment request via MpesaService
        try {
            $response = $this->mpesaService->makePaymentRequest($amount, $phoneNumber, $accountReference, $transactionDesc);

            // Handle successful response
            if (isset($response['ResponseCode']) && $response['ResponseCode'] == '0') {
                return response()->json(['message' => 'Payment initiated successfully.']);
            }

            // Log and return error response
            Log::error('M-PESA Response Error:', $response);
            return response()->json(['error' => 'Payment initiation failed.'], 400);
        } catch (\Exception $e) {
            Log::error('Payment initiation exception:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while initiating payment.'], 500);
        }
    }

    // Callback handler for payment confirmation
    public function handlePaymentCallback(Request $request)
    {
        Log::info('Handling M-PESA Callback:', $request->all());

        $callbackData = $request->input('Body.stkCallback.CallbackMetadata.Item', []);

        // Ensure callbackData is an array and not null
        if (is_array($callbackData) && count($callbackData) > 0) {
            $phoneNumber = $this->getCallbackItem($callbackData, 'PhoneNumber');
            $amount = $this->getCallbackItem($callbackData, 'Amount');
            $transactionId = $this->getCallbackItem($callbackData, 'MpesaReceiptNumber'); // Updated field name

            // Generate random Wi-Fi password
            $wifiPassword = $this->generateRandomPassword();

            // Save payment details
            $this->storePaymentDetails($phoneNumber, $amount, $transactionId, now(), $wifiPassword);

            // Return response with Wi-Fi password
            return response()->json([
                'status' => 'success',
                'wifi_password' => $wifiPassword,
                'message' => 'Your payment was successful. Use the following Wi-Fi password: ' . $wifiPassword,
            ]);
        } else {
            Log::error('Invalid callback data format:', $callbackData);
            return response()->json(['error' => 'Invalid callback data received.'], 400);
        }
    }

    // Generate a random Wi-Fi password
    public function generateRandomPassword($length = 8)
    {
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
    }

    // Store the payment details in the database
    public function storePaymentDetails($phoneNumber, $amount, $transactionId, $paymentTime, $wifiPassword)
    {
        // Save the payment details and Wi-Fi password in the database
        DB::table('payments')->insert([
            'phone_number' => $phoneNumber,
            'amount' => $amount,
            'transaction_id' => $transactionId,
            'payment_time' => $paymentTime,
            'wifi_password' => $wifiPassword,
            'created_at' => now(),
        ]);
    }

    // Get item from M-PESA callback data
    private function getCallbackItem($items, $name)
    {
        if (is_array($items)) {
            foreach ($items as $item) {
                if (isset($item['Name']) && $item['Name'] === $name) {
                    return $item['Value'] ?? null; // Return Value or null if not set
                }
            }
        }
        return null;
    }

    // Register URLs for M-PESA C2B transactions
    public function registerMpesaUrls()
    {
        $validationUrl = 'https://your-domain.com/mpesa/validation'; // Update with actual domain
        $confirmationUrl = 'https://your-domain.com/mpesa/confirmation'; // Update with actual domain

        // Register URLs via MpesaService
        try {
            $response = $this->mpesaService->registerUrls($validationUrl, $confirmationUrl);

            if (isset($response['ResponseCode']) && $response['ResponseCode'] == '0') {
                Log::info('URLs registered successfully:', $response);
                return response()->json(['message' => 'URLs registered successfully.']);
            } else {
                Log::error('Failed to register URLs:', $response);
                return response()->json(['error' => 'URL registration failed.'], 400);
            }
        } catch (\Exception $e) {
            Log::error('URL registration exception:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while registering URLs.'], 500);
        }
    }
}
