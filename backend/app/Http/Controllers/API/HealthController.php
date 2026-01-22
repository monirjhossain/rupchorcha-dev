<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    /**
     * Check API health status
     * Used by frontend to verify backend availability
     */
    public function check(): JsonResponse
    {
        try {
            // Test database connection
            DB::connection()->getPdo();

            return response()->json([
                'status' => 'ok',
                'message' => 'API is healthy',
                'timestamp' => now()->toIso8601String(),
                'version' => config('app.version', '1.0.0'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'API health check failed',
                'error' => $e->getMessage(),
            ], 503);
        }
    }

    /**
     * Get detailed system status
     * For debugging and monitoring purposes
     */
    public function status(): JsonResponse
    {
        try {
            DB::connection()->getPdo();
            $dbHealth = 'connected';
        } catch (\Exception $e) {
            $dbHealth = 'disconnected';
        }

        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
            'database' => $dbHealth,
            'disk_space' => disk_free_space('/') / 1024 / 1024 . ' MB',
            'uptime' => php_uname('a'),
        ], 200);
    }
}
