<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobilWarna extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mobil_warna';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mobil_id',
        'warna_id',
    ];

    /**
     * Get the mobil that owns the warna.
     */
    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    /**
     * Get the warna that belongs to the mobil.
     */
    public function warna()
    {
        return $this->belongsTo(Warna::class);
    }
}
