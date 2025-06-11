<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsiPembayaran extends Model
{
    use HasFactory;

    protected $table = 'opsi_pembayaran';

    protected $fillable = [
        'mobil_id',
        'harga',
        'is_active',
        'is_kredit',
        'konfigurasi_kredit_id'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_active' => 'boolean',
        'is_kredit' => 'boolean'
    ];

    public function mobil(): BelongsTo
    {
        return $this->belongsTo(Mobil::class, 'mobil_id');
    }

    public function konfigurasiKredit(): BelongsTo
    {
        return $this->belongsTo(KonfigurasiKredit::class, 'konfigurasi_kredit_id');
    }

    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getHargaCashAttribute(): float
    {
        return $this->harga;
    }

    public function getHargaKreditAttribute(): float
    {
        return $this->harga;
    }

    public function getAvailableMethodsAttribute(): array
    {
        $methods = ['Cash'];
        
        if ($this->is_kredit && $this->konfigurasiKredit) {
            $methods[] = 'Kredit';
        }
        
        return $methods;
    }
}
