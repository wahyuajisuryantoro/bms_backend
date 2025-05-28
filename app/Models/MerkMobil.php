<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MerkMobil extends Model
{
    protected $table = 'merk_mobil';
    protected $fillable = [
        'nama_merk',
        'foto_merk',
    ];
    public function mobils(): HasMany
    {
        return $this->hasMany(Mobil::class, 'merk_id');
    }
}
