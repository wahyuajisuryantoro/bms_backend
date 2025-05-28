<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsiPembayaran extends Model
{
     use HasFactory;
     protected $table = 'opsi_pembayaran';
     protected $fillable = [
        'mobil_id',
        'metode',
        'tenor',
        'harga',
        'dp_minimal',
        'angsuran_per_bulan',
        'bunga',
        'biaya_admin',
        'biaya_asuransi',
    ];
    protected $casts = [
        'harga' => 'decimal:2',
        'dp_minimal' => 'decimal:2',
        'angsuran_per_bulan' => 'decimal:2',
        'bunga' => 'decimal:2',
        'biaya_admin' => 'decimal:2',
        'biaya_asuransi' => 'decimal:2',
        'tenor' => 'integer',
    ];
    public function mobil()
    {
        return $this->belongsTo(Mobil::class, 'mobil_id');
    }


}
