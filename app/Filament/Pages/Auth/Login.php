<?php

namespace App\Filament\Pages\Auth;

use App\Models\LoginQuote;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    // protected static string $view = 'filament.auth.login-custom';

    public string $quote = '';

    public function mount(): void
    {
        parent::mount();

        $random = LoginQuote::inRandomOrder()->first();

        if ($random) {
            $this->quote = $random->quote;
        }
    }
}
