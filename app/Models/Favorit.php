<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorit extends Model
{
    use HasFactory;

    protected $table = 'favorit';

    protected $fillable = [
        'user_id',
        'mobil_id',
        'is_favorited',
    ];

    protected $casts = [
        'is_favorited' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    public function scopeForUserAndMobil($query, $userId, $mobilId)
    {
        return $query->where('user_id', $userId)->where('mobil_id', $mobilId);
    }

    public function scopeFavorited($query)
    {
        return $query->where('is_favorited', 1);
    }

    public function scopeNotFavorited($query)
    {
        return $query->where('is_favorited', 0);
    }
}