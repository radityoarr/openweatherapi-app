<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\SettingsController;

Route::get('/', [WeatherController::class, 'index'])->name('index');
Route::get('/forecast', [WeatherController::class, 'forecast'])->name('forecast');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
