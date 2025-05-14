<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\WeatherApiCurrentRequest;
use App\Http\Requests\WeatherApiForecastRequest;
use App\Services\OpenWeatherMapService;


class WeatherApiController extends Controller
{
    public function __construct(private OpenWeatherMapService $weatherService)
    {
    }
    public function current(WeatherApiCurrentRequest $request)
    {
        $weatherData = $this->weatherService->getCurrent($request->input('city'));
        return response()->json([
            'data' => [
                'temperature' =>  $weatherData['temperature'],
                'weather' => $weatherData['description'],
                'date' => $weatherData['date'],
                'city' => $weatherData['city'] . ', ' . $weatherData['country'],
                'country' => $weatherData['country'],
                'icon' => $weatherData['icon'],
            ]
        ]);
    }
    public function forecast(WeatherApiForecastRequest $request)
    {
        $weatherData = $this->weatherService->getForecast($request->input('city'));
        return response()->json([
            'data' =>  [
                'forecast' => $weatherData['forecast'],
                'city' => $weatherData['city'] ,
            ]
        ]);
    }
}
