<?php

namespace Tests\Feature;

use App\Services\OpenWeatherMapService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WeatherCurrentTest extends TestCase
{
    /**
     * @test
     */
    public function it_valide_wather_current_success(): void
    {
        $this->mock(OpenWeatherMapService::class, function ($mock) {
            $mock->shouldReceive('getCurrent')
                ->once()
                ->with('Madrid,ESP')
                ->andReturn([
                    'temperature' => 25,
                    'date' => '2023-10-01 12:00:00',
                    'city' => 'Madrid',
                    'country' => 'ES',
                    'description' => 'Clear sky',
                    'icon' => '01d'
                ]);
        });
        $response = $this->get('/api/weather/current?city=Madrid,ESP');
        $response->assertSuccessful();
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'temperature',
            'weather',
            'date',
            'city',
            'country',
            'icon'
        ]]);

    }
}
