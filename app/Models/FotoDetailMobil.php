<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoDetailMobil extends Model
{
    use HasFactory;

    protected $table = 'foto_detail_mobil';

    protected $fillable = [
        'mobil_id',
        'foto_path',
        'jenis_foto',
        'urutan',
    ];

    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }
}
