<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;

class AnggotaStatusJemaatChart extends ChartWidget
{
    protected static ?string $heading = 'Anggota Berdasar Status Jemaat';

    protected function getData(): array
    {
        $query = Anggota::query();

        if (!Auth::user()->hasRole('super_admin')) {
            $query->where('organization_id', Auth::user()->organization_id);
        }

        $statusData = [
            'Anggota' => (clone $query)->where('status_jemaat', 'anggota')->count(),
            'Simpatisan' => (clone $query)->where('status_jemaat', 'simpatisan')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah',
                    'data' => array_values($statusData),
                    'backgroundColor' => ['#36A2EB', '#FF6384'],
                ],
            ],
            'labels' => array_keys($statusData),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
