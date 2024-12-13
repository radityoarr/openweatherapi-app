<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

Route::get('/', [WeatherController::class, 'index'])->name('index');
Route::get('/forecast', [WeatherController::class, 'forecast'])->name('forecast');
