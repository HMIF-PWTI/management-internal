<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RfidApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');
        $validApiKey = config('services.rfid.api_key');

        if (! $apiKey || ! $validApiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. API key wajib dikirim.',
            ], 401);
        }

        if (! hash_equals($validApiKey, $apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. API key tidak valid.',
            ], 401);
        }

        return $next($request);
    }
}