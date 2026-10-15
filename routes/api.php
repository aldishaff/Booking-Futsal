<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::post('/midtrans/callback',
    [TransactionController::class, 'callback']);