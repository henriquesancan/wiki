<?php

use App\Http\Controllers\CollectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CollectController::class, 'index'])->name('collect.index');
