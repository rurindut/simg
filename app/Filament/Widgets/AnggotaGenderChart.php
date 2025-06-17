<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;

class AnggotaGenderChart extends ChartWidget
{
    protected static ?string $heading = 'Anggota Berdasar Jenis Kelamin';

    protected function getData(): array
    {
        $query = Anggota::query();

        if (!Auth::user()->hasRole('super_admin')) {
            $query->where('organization_id', Auth::user()->organization_id);
        }

        $genderData = [
            'Laki-laki' => (clone $query)->where('jenis_kelamin', 'Laki-laki')->count(),
            'Perempuan' => (clone $query)->where('jenis_kelamin', 'Perempuan')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah',
                    'data' => array_values($genderData),
                    'backgroundColor' => ['#36A2EB', '#FF6384'],
                ],
            ],
            'labels' => array_keys($genderData),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
