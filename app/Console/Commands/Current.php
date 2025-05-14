<?php

namespace App\Console\Commands;

use App\Services\OpenWeatherMapService;
use Illuminate\Console\Command;

class Current extends Command
{
    public function __construct( private OpenWeatherMapService $weatherService )
    {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'current {city} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->info('Current command executed successfully!');

        $city = $this->argument('city');
        $this->info("City: {$city}");
        $weatherData = $this->weatherService->getCurrent($city);
        if ($weatherData) {
            $this->info("Current weather for {$city}:");
            $this->info("Temperature: {$weatherData['temperature']}°C");
            $this->info("Weather: {$weatherData['description']}");
            $this->info("Date: {$weatherData['date']}");
            $this->info("City: {$weatherData['city']}, Country: {$weatherData['country']}");
        } else {
            $this->error("No current weather data available for {$city}.");
        }
    }
}
