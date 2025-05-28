<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBakar extends Model
{
    use HasFactory;

    protected $table = 'bahan_bakar';

    protected $fillable = [
        'jenis_bahan_bakar',
    ];

    public function mobil()
    {
        return $this->hasMany(Mobil::class);
    }
}
