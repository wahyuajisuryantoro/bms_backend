<?php

namespace App\Filament\Resources\JumlahUserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class JumlahUser extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::where('role', 'user')->count();
        $verifiedUsers = User::where('role', 'user')
            ->whereNotNull('email_verified_at')
            ->count();
        $unverifiedUsers = User::where('role', 'user')
            ->whereNull('email_verified_at')
            ->count();
        $verificationRate = $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100, 1) : 0;
        return [
            Stat::make('Jumlah Pengguna', $totalUsers)
                ->description('Jumlah Pengguna Aplikasi BMS')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Pengguna Terverifikasi', $verifiedUsers)
                ->description("{$verificationRate}% dari total pengguna")
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
    protected function getColumns(): int
    {
        return 2;
    }
    protected static ?int $sort = 1;

}
