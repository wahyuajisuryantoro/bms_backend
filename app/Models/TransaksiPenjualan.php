<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiPenjualan extends Model
{
    use HasFactory;
    protected $table = 'transaksi_penjualan';
    protected $fillable = [
        'user_id',
        'mobil_id',
        'metode_pembelian',
        'total_transaksi',
    ];
    protected $casts = [
        'total_transaksi' => 'decimal:2',
    ];
     public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }
}
