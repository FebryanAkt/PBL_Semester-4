<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Arahkan pengunjung web ke HomeController
Route::get('/', [HomeController::class, 'index']);