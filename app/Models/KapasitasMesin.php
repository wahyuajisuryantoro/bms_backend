<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KapasitasMesin extends Model
{
    use HasFactory;

    protected $table = 'kapasitas_mesin';


    protected $fillable = [
        'kapasitas',
    ];


    public function mobil()
    {
        return $this->hasMany(Mobil::class);
    }
}
