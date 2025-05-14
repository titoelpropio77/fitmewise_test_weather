<?php

namespace App\Services;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\{
    Http,
    Log,
};

class OpenWeatherMapService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENWEATHERMAP_API_KEY');
    }
    public function getCurrent($city)
    {
        $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$this->apiKey}&units=metric&lang=es";
        try {

            $response = Http::get($url)->throw(function ($response, $e) {
                Log::error($e);
            });
            $data = $response->json();
            if (isset($data['main'])) {
                return [
                    'temperature' => $data['main']['temp'],
                    'date'=> date('Y-m-d H:i:s', $data['dt']),
                    'city' => $data['name'],
                    'country' => $data['sys']['country'],
                    'description' => $data['weather'][0]['description'],
                    'icon' => $data['weather'][0]['icon']
                ];
            } else {
                return null;
            }
        } catch (RequestException $e) {
            Log::error("OpenWeatherMap API request failed: " . $e->getMessage());
            return null;
        }
    }
    public function getForecast($city,int $days = 5)
    {
        $url = "https://api.openweathermap.org/data/2.5/forecast?q={$city}&appid={$this->apiKey}&units=metric&lang=es&cnt=" . ($days * 8);
        try {
            $response = Http::get($url)->throw(function ($response, $e) {
                Log::error($e);
            });

            $data = $response->json();

            if (isset($data['list'])) {
                $forecast = [];
                $seenDates = [];

                foreach ($data['list'] as $item) {
                    $date = substr($item['dt_txt'], 0, 10); // Solo la fecha: YYYY-MM-DD
                    $hour = substr($item['dt_txt'], 11, 5); // Hora: HH:MM

                    // Queremos solo una entrada por día, idealmente cercana al mediodía (12:00)
                    if (!isset($seenDates[$date]) && in_array($hour, ['12:00', '15:00', '09:00'])) {
                        $forecast[] = [
                            'date' => $item['dt_txt'],
                            'temperature' => $item['main']['temp'],
                            'description' => $item['weather'][0]['description'],
                            'icon' => $item['weather'][0]['icon']
                        ];
                        $seenDates[$date] = true;
                    }

                    if (count($forecast) >= 5) {
                        break;
                    }
                }

                return [
                    'forecast' => $forecast,
                    'city' => $data['city']['name'] . ', ' . $data['city']['country'],
                ];
            }

            return null;
        } catch (RequestException $e) {
            Log::error("OpenWeatherMap API request failed: " . $e->getMessage());
            return null;
        }
    }
}
