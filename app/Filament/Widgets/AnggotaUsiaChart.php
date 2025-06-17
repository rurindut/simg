<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;

class AnggotaUsiaChart extends ChartWidget
{
    protected static ?string $heading = 'Anggota Berdasar Usia';

    protected function getData(): array
    {
        $query = Anggota::query();

        // Filter jika bukan super admin
        if (!Auth::user()->hasRole('super_admin')) {
            $query->where('organization_id', Auth::user()->organization_id);
        }
        
        $usiaData = [
            'Anak (<13)' => (clone $query)->where('tanggal_lahir', '>=', now()->subYears(13))->count(),
            'Remaja (13-19)' => (clone $query)->whereBetween('tanggal_lahir', [now()->subYears(19), now()->subYears(13)])->count(),
            'Dewasa (20-59)' => (clone $query)->whereBetween('tanggal_lahir', [now()->subYears(59), now()->subYears(20)])->count(),
            'Lansia (60+)' => (clone $query)->where('tanggal_lahir', '<', now()->subYears(60))->count(),
        ];
    
        return [
            'datasets' => [
                [
                    'label' => 'Jumlah',
                    'data' => array_values($usiaData),
                    'backgroundColor' => ['#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                ],
            ],
            'labels' => array_keys($usiaData),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

}
