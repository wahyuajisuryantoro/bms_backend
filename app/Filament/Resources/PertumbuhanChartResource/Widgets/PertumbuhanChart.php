<?php

namespace App\Filament\Resources\PertumbuhanChartResource\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use App\Models\TransaksiPenjualan;

class PertumbuhanChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Pertumbuhan User & Transaksi (12 Bulan Terakhir)';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $userData = [];
        $transaksiData = [];
        $labels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            
            // Hitung user baru per bulan (role = user saja)
            $userCount = User::where('role', 'user')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            // Hitung transaksi per bulan
            $transaksiCount = TransaksiPenjualan::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $userData[] = $userCount;
            $transaksiData[] = $transaksiCount;
            $labels[] = $date->format('M Y');
        }
        return [
           'datasets' => [
                [
                    'label' => 'User Baru',
                    'data' => $userData,
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Transaksi',
                    'data' => $transaksiData,
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

     protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Periode',
                    ],
                ],
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah User Baru',
                    ],
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Transaksi',
                    ],
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
        ];
    }

     protected function getStats(): array
    {
        $currentMonth = now();
        $lastMonth = now()->subMonth();
        
        // User baru bulan ini vs bulan lalu
        $currentMonthUsers = User::where('role', 'user')
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->count();
            
        $lastMonthUsers = User::where('role', 'user')
            ->whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->count();
        
        // Transaksi bulan ini vs bulan lalu
        $currentMonthTransaksi = TransaksiPenjualan::whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->count();
            
        $lastMonthTransaksi = TransaksiPenjualan::whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->count();
        
        // Hitung persentase pertumbuhan
        $userGrowth = $lastMonthUsers > 0 
            ? round((($currentMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1)
            : ($currentMonthUsers > 0 ? 100 : 0);
            
        $transaksiGrowth = $lastMonthTransaksi > 0 
            ? round((($currentMonthTransaksi - $lastMonthTransaksi) / $lastMonthTransaksi) * 100, 1)
            : ($currentMonthTransaksi > 0 ? 100 : 0);
        
        return [
            'current_users' => $currentMonthUsers,
            'last_users' => $lastMonthUsers,
            'user_growth' => $userGrowth,
            'current_transaksi' => $currentMonthTransaksi,
            'last_transaksi' => $lastMonthTransaksi,
            'transaksi_growth' => $transaksiGrowth,
        ];
    }

    public function getDescription(): ?string
    {
        $stats = $this->getStats();
        
        $userTrend = $stats['user_growth'] >= 0 ? '📈' : '📉';
        $transaksiTrend = $stats['transaksi_growth'] >= 0 ? '📈' : '📉';
        
        return "User: {$stats['current_users']} ({$userTrend} {$stats['user_growth']}%) | " .
               "Transaksi: {$stats['current_transaksi']} ({$transaksiTrend} {$stats['transaksi_growth']}%) " .
               "dibanding bulan lalu";
    }
}
