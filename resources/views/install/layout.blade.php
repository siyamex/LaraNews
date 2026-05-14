<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install — {{ config('app.name', 'LaraNews') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-2xl">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-red-600 rounded-2xl mb-4">
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h1 class="text-2xl font-black text-gray-900">LaraNews Installer</h1>
        <p class="text-gray-500 text-sm mt-1">Follow the steps below to set up your news platform</p>
    </div>

    {{-- Step indicator --}}
    @isset($steps)
    <div class="flex items-center justify-center gap-2 mb-8">
        @foreach($steps as $i => $label)
        <div class="flex items-center gap-2">
            <div class="flex items-center justify-center w-8 h-8 rounded-full text-xs font-bold
                        {{ $currentStep > $i + 1 ? 'bg-green-500 text-white' : ($currentStep === $i + 1 ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-500') }}">
                @if($currentStep > $i + 1)
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                @else
                {{ $i + 1 }}
                @endif
            </div>
            <span class="text-xs font-medium {{ $currentStep === $i + 1 ? 'text-gray-900' : 'text-gray-400' }} hidden sm:inline">{{ $label }}</span>
            @if($i < count($steps) - 1)
            <div class="w-8 h-px bg-gray-200 hidden sm:block"></div>
            @endif
        </div>
        @endforeach
    </div>
    @endisset

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        @yield('card')
    </div>

</div>

</body>
</html>
