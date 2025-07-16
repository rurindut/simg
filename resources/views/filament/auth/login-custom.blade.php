<div>
    @vite('resources/js/app.js')
    {{-- Elemen tambahan di luar main tapi tetap dalam root --}}
    <!-- <h3 id="slogan">{{ config('app.name') }}</h3> -->
    <div id="quote">"{{ $quote }}"</div>
    <div class="login-divider"></div>
    {{-- Ini akan tetap dibungkus <main> oleh Filament --}}
    <div class="p-8 flex flex-col justify-center">
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">
            {{ __('Login') }}
        </h2>

        <form wire:submit.prevent="authenticate" class="space-y-4">
            {{ $this->form }}

            <x-filament::button type="submit" class="w-full">
                {{ __('Login') }}
            </x-filament::button>
        </form>
    </div>
</div>
