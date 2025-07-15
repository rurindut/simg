<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\AnggotaUsiaChart;
use App\Filament\Widgets\AnggotaGenderChart;
use App\Filament\Widgets\AnggotaStatusJemaatChart;
use App\Filament\Widgets\AnggotaAtestasiChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getWidgets(): array
    {
        return [
            AnggotaUsiaChart::class,
            AnggotaGenderChart::class,
            AnggotaStatusJemaatChart::class,
            AnggotaAtestasiChart::class,
        ];
    }

    protected function shouldShowWelcomeWidget(): bool
    {
        return false;
    }

    protected function shouldShowFilamentVersion(): bool
    {
        return false;
    }
}
