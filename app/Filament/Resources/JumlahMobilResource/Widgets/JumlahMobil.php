<?php

namespace App\Filament\Resources\JumlahMobilResource\Widgets;

use App\Models\Mobil;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class JumlahMobil extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Mobil', Mobil::count())
                ->description('Total semua mobil dalam database')
                ->descriptionIcon('heroicon-m-truck')
                ->color('success'),

            Stat::make('Mobil Tersedia', Mobil::where('tersedia', true)->count())
                ->description('Mobil yang tersedia')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('primary'),

            Stat::make('Mobil Tidak Tersedia', Mobil::where('tersedia', false)->count())
                ->description('Mobil yang sedang tidak tersedia')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
    protected function getColumns(): int
    {
        return 3;
    }
    protected static ?int $sort = 2;

}
