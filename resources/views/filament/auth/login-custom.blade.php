<div>
    <style>
        body {
            background: rgb(34,193,195) !important;
            background: linear-gradient(0deg, rgba(34,193,195,1) 0%, rgba(253,187,45,1) 100%) !important;
        }

        @media screen and (min-width: 1024px) {
            main {
                position: absolute;
                right: 100px;
            }

            main:before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: darkcyan;
                border-radius: 12px;
                z-index: -9;

                -webkit-transform: rotate(7deg);
                -moz-transform: rotate(7deg);
                -o-transform: rotate(7deg);
                -ms-transform: rotate(7deg);
                transform: rotate(7deg);
            }

            .fi-logo {
                position: fixed;
                left: 100px;
                font-size: 3em;
                color: cornsilk;
            }

            #slogan {
                position: fixed;
                left: 100px;
                margin-top: 50px;
                color: bisque;
                font-family: cursive;
                font-size: 2em;
                font-weight: bold;
                width: 45%;
                text-shadow: #3f6212 2px 2px 5px;
            }

            #quote {
                position: fixed;
                left: 100px;
                margin-top: 80px;
                color: floralwhite;
                font-family: Georgia, serif;
                font-size: 1.2em;
                font-style: italic;
                width: 45%;
                text-shadow: #1e3a8a 1px 1px 3px;
            }
        }

        @media screen and (max-width: 1023px) {
            #slogan {
                text-align: center;
                color: black;
                font-family: cursive;
                font-size: 1.5em;
                font-weight: bold;
                margin-top: 20px;
                margin-bottom: 10px;
                text-shadow: none;
            }

            #quote {
                text-align: center;
                color: black;
                font-family: Georgia, serif;
                font-size: 1em;
                font-style: italic;
                padding: 0 20px;
                margin-bottom: 20px;
                text-shadow: none;
            }

            .login-divider {
                border-top: 2px solid #555;
                margin: 1.5rem auto;
                width: 60%;
            }
        }
    </style>
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
