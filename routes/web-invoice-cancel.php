<?php

use Illuminate\Support\Facades\Route;

Route::patch('invoices/{invoice}/cancel', [\App\Http\Controllers\Sales\Invoices::class, 'cancel'])
    ->name('invoices.cancel')
    ->middleware(['web', 'auth']);
