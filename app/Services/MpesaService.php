<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    protected $client;
    protected $consumerKey;
    protected $consumerSecret;
    protected $shortCode;
    protected $passKey;
    protected $callbackUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->consumerKey = config('services.mpesa.consumer_key');
        $this->consumerSecret = config('services.mpesa.consumer_secret');
        $this->shortCode = config('services.mpesa.shortcode');
        $this->passKey = config('services.mpesa.passkey');
        $this->callbackUrl = config('services.mpesa.callback_url');
    }

    /**
     * Get OAuth token from Safaricom API
     *
     * @return string|null
     */
    public function getToken()
    {
        $credentials = base64_encode("{$this->consumerKey}:{$this->consumerSecret}");
        try {
            $response = $this->client->request('GET', 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials', [
                'headers' => [
                    'Authorization' => 'Basic ' . $credentials,
                ],
                'verify' => false,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            return $body['access_token'] ?? null;

        } catch (ClientException $e) {
            Log::error('M-PESA Token Error: ' . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            Log::error('M-PESA Token General Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Make STK Push Payment Request
     *
     * @param float  $amount
     * @param string $phoneNumber
     * @param string $accountReference
     * @param string $transactionDesc
     * @return array
     */
    public function makePaymentRequest($amount, $phoneNumber, $accountReference, $transactionDesc)
    {
        $token = $this->getToken();

        if (!$token) {
            return ['error' => 'Failed to retrieve access token'];
        }

        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortCode . $this->passKey . $timestamp);

        $data = [
            'BusinessShortCode' => $this->shortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => (int)$amount,
            'PartyA' => (int)$phoneNumber,  // Phone number initiating the payment
            'PartyB' => $this->shortCode,   // Paybill or shortcode being paid to
            'PhoneNumber' => (int)$phoneNumber,
            'CallBackURL' => $this->callbackUrl,
            'AccountReference' => $accountReference,
            'TransactionDesc' => $transactionDesc,
        ];

        try {
            $response = $this->client->request('POST', 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
                'verify' => false,
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (ClientException $e) {
            Log::error('M-PESA Payment Client Error: ' . $e->getMessage());
            if ($e->hasResponse()) {
                return json_decode($e->getResponse()->getBody()->getContents(), true);
            }
            return ['error' => 'Payment initiation failed due to client error'];
        } catch (\Exception $e) {
            Log::error('M-PESA Payment General Error: ' . $e->getMessage());
            return ['error' => 'Payment initiation failed'];
        }
    }

    /**
     * Register validation and confirmation URLs for C2B
     *
     * @param string $validationUrl
     * @param string $confirmationUrl
     * @return array
     */
    public function registerUrls($validationUrl, $confirmationUrl)
    {
        $token = $this->getToken();

        if (!$token) {
            return ['error' => 'Failed to retrieve access token'];
        }

        $data = [
            'ShortCode' => $this->shortCode,
            'ResponseType' => 'Completed',
            'ConfirmationURL' => $confirmationUrl,
            'ValidationURL' => $validationUrl,
        ];

        try {
            $response = $this->client->post('https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
                'verify' => false,
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (ClientException $e) {
            Log::error('M-PESA URL Registration Client Error: ' . $e->getMessage());
            if ($e->hasResponse()) {
                return json_decode($e->getResponse()->getBody()->getContents(), true);
            }
            return ['error' => 'URL registration failed due to client error'];
        } catch (\Exception $e) {
            Log::error('M-PESA URL Registration General Error: ' . $e->getMessage());
            return ['error' => 'URL registration failed'];
        }
    }
}
