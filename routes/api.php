<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

// sanity check route
Route::get('ping', fn () => response()->json(['pong' => true]));

Route::apiResource('contacts', ContactController::class);
