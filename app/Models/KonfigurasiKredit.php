<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KonfigurasiKredit extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_kredit';

    protected $fillable = [
        'nama_template',
        'tenor_bunga_config',
        'is_active'
    ];

    protected $casts = [
        'tenor_bunga_config' => 'array',
        'is_active' => 'boolean'
    ];

    public function opsiPembayaran(): HasMany
    {
        return $this->hasMany(OpsiPembayaran::class, 'konfigurasi_kredit_id');
    }

    public function getTenorTersediaAttribute(): array
    {
        if (empty($this->tenor_bunga_config)) {
            return [];
        }

        return array_keys($this->tenor_bunga_config);
    }
}
