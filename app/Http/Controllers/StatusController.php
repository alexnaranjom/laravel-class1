<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class StatusController extends Controller
{
    public function hello(): JsonResponse
    {
        return response()->json(['message' => 'Hello, Alex! Laravel is running smoothly.']);
    }

    public function status(): JsonResponse
    {
        return response()->json([
            'laravel' => app()->version(),
            'php'     => PHP_VERSION,
            'env'     => config('app.env'),
            'time'    => now()->toIso8601String(),
        ]);
    }
}

