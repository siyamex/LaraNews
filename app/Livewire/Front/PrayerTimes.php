<?php

namespace App\Livewire\Front;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class PrayerTimes extends Component
{
    public ?array $times = null;

    public function mount(): void
    {
        $this->times = Cache::remember('prayer_times_male_' . today()->toDateString(), 86400, function () {
            $response = Http::timeout(5)->get('https://api.aladhan.com/v1/timingsByCity', [
                'city'    => 'Male',
                'country' => 'MV',
                'method'  => 4,
            ]);

            if ($response->successful()) {
                $timings = $response->json('data.timings');
                return [
                    'Fajr'    => $timings['Fajr'],
                    'Dhuhr'   => $timings['Dhuhr'],
                    'Asr'     => $timings['Asr'],
                    'Maghrib' => $timings['Maghrib'],
                    'Isha'    => $timings['Isha'],
                ];
            }
            return null;
        });
    }

    public function render()
    {
        return view('livewire.front.prayer-times');
    }
}
