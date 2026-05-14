<div>
    @if($weather)
    <div class="flex items-center justify-between">
        <div>
            <div class="text-4xl font-black">{{ $weather['temp'] }}°C</div>
            <div class="text-sm opacity-80 capitalize mt-1">{{ $weather['description'] }}</div>
        </div>
        <img src="https://openweathermap.org/img/wn/{{ $weather['icon'] }}@2x.png" alt="{{ $weather['description'] }}" class="w-16 h-16">
    </div>
    <div class="mt-3 grid grid-cols-3 gap-2 text-xs opacity-80">
        <div class="text-center">
            <div class="font-semibold">{{ $weather['feels_like'] }}°</div>
            <div>{{ app()->getLocale() === 'dv' ? 'ހީވާ ގޮތް' : 'Feels' }}</div>
        </div>
        <div class="text-center">
            <div class="font-semibold">{{ $weather['humidity'] }}%</div>
            <div>{{ app()->getLocale() === 'dv' ? 'ހިތިކަން' : 'Humidity' }}</div>
        </div>
        <div class="text-center">
            <div class="font-semibold">{{ $weather['wind'] }} m/s</div>
            <div>{{ app()->getLocale() === 'dv' ? 'ވައި' : 'Wind' }}</div>
        </div>
    </div>
    @else
    <p class="text-sm opacity-70">{{ app()->getLocale() === 'dv' ? 'ލިބޭ ގޮތެއް ނެތް' : 'Weather unavailable' }}</p>
    @endif
</div>
