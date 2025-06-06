<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeBodi extends Model
{
    use HasFactory;

    protected $table = 'tipe_bodi';


    protected $fillable = [
        'nama_tipe',
    ];

    public function mobil()
    {
        return $this->hasMany(Mobil::class);
    }
}
