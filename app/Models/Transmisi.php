<?php

namespace App\Models;

use App\Models\Mobil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transmisi extends Model
{
    protected $table = 'transmisi';
    protected $fillable = [
        'jenis_transmisi',
    ];
    public function mobil(): HasMany
    {
        return $this->hasMany(Mobil::class, 'transmisi_id');
    }
}
