<?php

namespace App\Livewire\Front;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class WeatherWidget extends Component
{
    public ?array $weather = null;

    public function mount(): void
    {
        $this->weather = Cache::remember('weather_male', 1800, function () {
            $apiKey = config('services.openweather.key');
            if (! $apiKey) return null;

            $response = Http::timeout(5)->get('https://api.openweathermap.org/data/2.5/weather', [
                'q'     => 'Male,MV',
                'appid' => $apiKey,
                'units' => 'metric',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'temp'        => round($data['main']['temp']),
                    'feels_like'  => round($data['main']['feels_like']),
                    'humidity'    => $data['main']['humidity'],
                    'description' => $data['weather'][0]['description'],
                    'icon'        => $data['weather'][0]['icon'],
                    'wind'        => round($data['wind']['speed']),
                ];
            }
            return null;
        });
    }

    public function render()
    {
        return view('livewire.front.weather-widget');
    }
}
