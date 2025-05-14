<?php

namespace App\Console\Commands;

use App\Services\OpenWeatherMapService;
use Illuminate\Console\Command;

class Forecast extends Command
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
    protected $signature = 'forecast {city} {--days=5}';

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

        $this->info('Forecast command executed successfully!');

        $city = $this->argument('city');
        $days = $this->option('days');
        $this->info("City: {$city}");
        $this->info("Days: {$days}");
        $weatherData = $this->weatherService->getForecast($city, $days);
        if ($weatherData) {
            $this->info("Forecast for {$city}:");
            foreach ($weatherData['forecast'] as $day) {
                $this->info("Date: {$day['date']}, Temperature: {$day['temperature']}°C, Weather: {$day['description']}");
            }
        } else {
            $this->error("No forecast data available for {$city}.");
        }

    }
}
