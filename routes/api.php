<?php

use App\Http\Controllers\CollectController;
use App\Http\Controllers\RevenueController;
use Illuminate\Support\Facades\Route;

Route::get('/revenue', [RevenueController::class, 'show'])->name('revenue.show');

Route::post('/collect', [CollectController::class, 'main'])->name('collect.main');
