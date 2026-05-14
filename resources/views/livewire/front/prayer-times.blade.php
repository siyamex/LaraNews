<div>
    @if($times)
    <dl class="space-y-2">
        @foreach($times as $name => $time)
        <div class="flex justify-between items-center text-sm">
            <dt class="opacity-80">{{ app()->getLocale() === 'dv' ? match($name) {
                'Fajr' => 'ފަތިސް', 'Dhuhr' => 'މެންދުރު', 'Asr' => 'އަސްރު', 'Maghrib' => 'މަޣްރިބް', 'Isha' => 'އިޝާ', default => $name
            } : $name }}</dt>
            <dd class="font-semibold">{{ $time }}</dd>
        </div>
        @endforeach
    </dl>
    <p class="text-xs opacity-60 mt-3">{{ app()->getLocale() === 'dv' ? 'މާލެ • ދިވެހިރާއްޖެ' : 'Malé, Maldives' }}</p>
    @else
    <p class="text-sm opacity-70">{{ app()->getLocale() === 'dv' ? 'ލިބޭ ގޮތެއް ނެތް' : 'Prayer times unavailable' }}</p>
    @endif
</div>
