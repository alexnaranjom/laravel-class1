<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatusController;

Route::get('/hello', [StatusController::class, 'hello']);
Route::get('/status', [StatusController::class, 'status']);
