<?php

namespace App\Services;

use App\Models\City;
use App\Models\WeatherLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CityWeatherService
{
    public function fetchAndSave(City $city): ?WeatherLog
    {
        try {
            // Task 2: Integrate Weather API with Error Handling
            $apiKey = config('services.openweather.key');

            if (!$apiKey) {
                throw new \RuntimeException('OpenWeatherMap API key is not configured.');
            }

            $response = Http::timeout(10)->get('https://api.openweathermap.org/data/2.5/weather', [
                'q' => $city->name,
                'appid' => $apiKey,
                'units' => 'metric',
            ]);

            if (!$response->successful()) {
                throw new \RuntimeException('OpenWeatherMap request failed: ' . $response->status());
            }

            $data = $response->json();

            $temperature = data_get($data, 'main.temp');
            $humidity = data_get($data, 'main.humidity');
            $windSpeed = data_get($data, 'wind.speed');
            $description = data_get($data, 'weather.0.description');

            if (!is_numeric($temperature) || !is_numeric($humidity) || !is_numeric($windSpeed) || !$description) {
                throw new \RuntimeException('OpenWeatherMap returned incomplete data.');
            }

            return WeatherLog::create([
                'city_id' => $city->id,
                'temperature' => (float) $temperature,
                'humidity' => (int) $humidity,
                'wind_speed' => (float) $windSpeed,
                'description' => (string) $description,
                'fetched_at' => Carbon::now(),
            ]);

        } catch (\Throwable $e) {
            Log::error("Weather fetch failed for city {$city->name}: " . $e->getMessage());
            return null;
        }
    }
}
