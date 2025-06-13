<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class KonfigurasiHarga extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Konfigurasi Harga';

    protected static ?int $navigationSort = 3;
}
