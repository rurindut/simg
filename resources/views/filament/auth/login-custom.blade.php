<div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 px-4">
    <div class="w-full max-w-7xl grid grid-cols-1 md:grid-cols-2 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        {{-- Kolom Kiri: Quote --}}
        <div class="hidden md:flex items-center justify-center p-8 bg-gray-200 dark:bg-gray-700">
            <div class="text-center max-w-md">
                <p class="text-xl italic text-gray-800 dark:text-gray-100">"{{ $quote }}"</p>
            </div>
        </div>

        {{-- Kolom Kanan: Login Form --}}
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
</div>
