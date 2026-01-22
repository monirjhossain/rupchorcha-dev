<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class GoogleAuthController extends Controller
{
    public function handleGoogleLogin(Request $request)
    {
        try {
            $credential = $request->input('credential');
            \Log::info('Google login: Starting', [
                'has_credential' => !empty($credential),
                'credential_prefix' => substr($credential ?? '', 0, 20),
            ]);
            
            if (!$credential) {
                \Log::error('Google login: No credential provided');
                return response()->json([
                    'success' => false,
                    'message' => 'Credential is required'
                ], 400);
            }

            // Try to verify the Google token
            $payload = $this->verifyGoogleToken($credential);
            
            if (!$payload) {
                \Log::error('Google login: Token verification returned null');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to verify Google token. Please check your internet connection and try again.'
                ], 400);
            }

            $email = $payload['email'] ?? null;
            $name = $payload['name'] ?? 'User';
            $googleId = $payload['sub'] ?? null;

            \Log::info('Google token decoded successfully', [
                'email' => $email,
                'google_id' => substr($googleId ?? '', 0, 10)
            ]);

            if (!$email) {
                \Log::error('Google login: Email missing from token');
                return response()->json([
                    'success' => false,
                    'message' => 'Email not found in Google token'
                ], 400);
            }

            // Check if user exists, create if not
            $user = User::where('email', $email)->first();

            if (!$user) {
                \Log::info('Google login: Creating new user', ['email' => $email]);
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'phone' => '',
                    'password' => Hash::make(uniqid()),
                    'google_id' => $googleId,
                    'email_verified_at' => now(),
                ]);
            } else {
                \Log::info('Google login: User exists', ['user_id' => $user->id]);
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleId]);
                }
            }

            // Generate API token
            $token = $user->createToken('auth-token')->plainTextToken;

            \Log::info('Google login: Success', ['user_id' => $user->id, 'email' => $user->email]);

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ],
                'message' => 'Google login successful',
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Google login exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login. Please try again.',
                'debug' => $e->getMessage()
            ], 500);
        }
    }

    private function verifyGoogleToken($token)
    {
        try {
            \Log::info('Token verification: Starting', ['token_length' => strlen($token)]);
            
            $url = 'https://www.googleapis.com/oauth2/v1/tokeninfo?id_token=' . urlencode($token);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            curl_close($ch);
            
            \Log::info('Token verification: API response received', [
                'http_code' => $http_code,
                'response_length' => strlen($response ?? ''),
                'has_curl_error' => !empty($curl_error),
            ]);
            
            if ($curl_error) {
                \Log::error('Token verification: Curl error', ['error' => $curl_error]);
                return null;
            }
            
            if ($http_code !== 200) {
                \Log::error('Token verification: Bad HTTP status', [
                    'status' => $http_code,
                    'response' => substr($response ?? '', 0, 200)
                ]);
                return null;
            }
            
            $decoded = json_decode($response, true);
            
            if (!$decoded) {
                \Log::error('Token verification: Failed to decode JSON');
                return null;
            }
            
            if (!isset($decoded['email'])) {
                \Log::error('Token verification: Email missing from response', [
                    'keys' => array_keys($decoded)
                ]);
                return null;
            }
            
            \Log::info('Token verification: Success', ['email' => $decoded['email']]);
            return $decoded;
            
        } catch (\Exception $e) {
            \Log::error('Token verification: Exception', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }
}
