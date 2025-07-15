<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Atestasi;
use Illuminate\Support\Facades\Auth;

class AnggotaAtestasiChart extends ChartWidget
{
    protected static ?string $heading = 'Anggota Berdasar Atestasi';

    protected function getData(): array
    {
        $query = Atestasi::query()->whereHas('anggota', function ($query) {
            if (! auth()->user()?->is_super_admin) {
                $query->where('organization_id', auth()->user()->organization_id);
            }
        });

        $atestasiData = [
            'Masuk' => (clone $query)->where('tipe', 'masuk')->count(),
            'Keluar' => (clone $query)->where('tipe', 'keluar')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah',
                    'data' => array_values($atestasiData),
                    'backgroundColor' => ['#36A2EB', '#FF6384'],
                ],
            ],
            'labels' => array_keys($atestasiData),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
