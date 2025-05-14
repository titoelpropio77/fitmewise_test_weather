<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\WeatherApiController;

Route::get('/weather/current', [WeatherApiController::class, 'current']);

Route::get('/weather/forecast', [WeatherApiController::class, 'forecast']);
