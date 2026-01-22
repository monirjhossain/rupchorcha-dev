<?php
namespace App\Services;

use GuzzleHttp\Client;

class SmsService
{
    public static function send($phone, $message)
    {
        $driver = config('services.sms.driver', 'firebase');
        
        if ($driver === 'firebase') {
            return self::sendFirebase($phone, $message);
        } elseif ($driver === 'twilio') {
            return self::sendTwilio($phone, $message);
        } elseif ($driver === 'nexmo') {
            return self::sendNexmo($phone, $message);
        }
        
        return false;
    }

    private static function sendFirebase($phone, $message)
    {
        try {
            // Development mode - log OTP instead of sending via API
            if (env('APP_ENV') === 'local') {
                \Log::info('Firebase SMS (DEV MODE) to ' . $phone . ': ' . $message);
                
                // Also save to database for easy access
                $otp = preg_replace('/[^0-9]/', '', $message);
                if (strlen($otp) >= 4) {
                    // Store OTP separately for debug
                    \Log::info('OTP Code: ' . $otp);
                }
                
                return true;
            }
            
            // Production - use Firebase REST API
            $projectId = env('FIREBASE_PROJECT_ID');
            $apiKey = env('FIREBASE_API_KEY');
            
            if (!$projectId || !$apiKey) {
                \Log::warning('Firebase SMS: Project ID or API key not configured');
                return false;
            }
            
            // Format phone number
            if (!str_starts_with($phone, '+')) {
                if (str_starts_with($phone, '0')) {
                    $phone = '+88' . substr($phone, 1);
                } else {
                    $phone = '+88' . $phone;
                }
            }
            
            // Use Firebase Cloud Functions or custom SMS API
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://firebaseapp.com/send-sms');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'phone' => $phone,
                'message' => $message,
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ]);
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($http_code === 200) {
                \Log::info('Firebase SMS sent to ' . $phone);
                return true;
            } else {
                \Log::error('Firebase SMS error: HTTP ' . $http_code);
                return false;
            }
        } catch (\Exception $e) {
            \Log::error('Firebase SMS exception: ' . $e->getMessage());
            return false;
        }
    }

    private static function sendTwilio($phone, $message)
    {
        $accountSid = config('services.sms.twilio.account_sid');
        $authToken = config('services.sms.twilio.auth_token');
        $verifySid = config('services.sms.twilio.verify_sid');
        
        if (!$accountSid || !$authToken || !$verifySid) {
            \Log::warning('Twilio SMS: Credentials not configured');
            return false;
        }
        
        try {
            // Format phone number: add +88 if not present
            if (!str_starts_with($phone, '+')) {
                if (str_starts_with($phone, '0')) {
                    $phone = '+88' . substr($phone, 1);
                } else {
                    $phone = '+88' . $phone;
                }
            }
            
            // Use Twilio Verify API
            require_once base_path('vendor/autoload.php');
            $twilio = new \Twilio\Rest\Client($accountSid, $authToken);
            
            $verification = $twilio->verify->v2->services($verifySid)
                                               ->verifications
                                               ->create($phone, "sms");
            
            \Log::info('Twilio OTP sent: ' . $verification->sid . ' to ' . $phone);
            return true;
        } catch (\Exception $e) {
            \Log::error('Twilio SMS error: ' . $e->getMessage());
            return false;
        }
    }

    private static function sendNexmo($phone, $message)
    {
        $apiKey = config('services.sms.nexmo.api_key');
        $apiSecret = config('services.sms.nexmo.api_secret');
        
        if (!$apiKey || !$apiSecret) {
            \Log::warning('Nexmo SMS: Credentials not configured');
            return false;
        }
        
        try {
            $client = new Client();
            $response = $client->post('https://rest.nexmo.com/sms/json', [
                'form_params' => [
                    'api_key' => $apiKey,
                    'api_secret' => $apiSecret,
                    'to' => $phone,
                    'from' => 'Rupchorcha',
                    'text' => $message,
                ]
            ]);
            
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            \Log::error('Nexmo SMS error: ' . $e->getMessage());
            return false;
        }
    }
}
