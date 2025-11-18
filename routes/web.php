<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\Web\ContactWebController;

Route::get('/hello', [StatusController::class, 'hello']);
Route::get('/status', [StatusController::class, 'status']);
Route::get('/contacts', [ContactWebController::class, 'index'])->name('contact.index');
