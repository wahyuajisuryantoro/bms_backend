<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mobil';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_mobil',
        'merk_id',
        'transmisi_id',
        'tipe_bodi_id',
        'bahan_bakar_id',
        'kapasitas_mesin_id',
        'tahun_keluaran',
        'thumbnail_foto',
        'deskripsi',
        'tersedia',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga' => 'decimal:2',
        'tersedia' => 'boolean',
    ];

    /**
     * Get the merk that owns the mobil.
     */
    public function merk()
    {
        return $this->belongsTo(MerkMobil::class, 'merk_id');
    }

    /**
     * Get the transmisi that owns the mobil.
     */
    public function transmisi()
    {
        return $this->belongsTo(Transmisi::class);
    }

    /**
     * Get the tipe bodi that owns the mobil.
     */
    public function tipeBodi()
    {
        return $this->belongsTo(TipeBodi::class, 'tipe_bodi_id');
    }

    /**
     * Get the bahan bakar that owns the mobil.
     */
    public function bahanBakar()
    {
        return $this->belongsTo(BahanBakar::class, 'bahan_bakar_id');
    }

    /**
     * Get the kapasitas mesin that owns the mobil.
     */
    public function kapasitasMesin()
    {
        return $this->belongsTo(KapasitasMesin::class, 'kapasitas_mesin_id');
    }

    /**
     * Get the warna for the mobil.
     */
    public function warna()
    {
        return $this->belongsToMany(Warna::class, 'mobil_warna');
    }

    /**
     * Get the foto detail for the mobil.
     */
    public function fotoDetail()
    {
        return $this->hasMany(FotoDetailMobil::class);
    }

    /**
     * Get the favorit for the mobil.
     */
    public function favorit()
    {
        return $this->hasMany(Favorit::class);
    }
    public function opsiPembayaran()
    {
        return $this->hasMany(OpsiPembayaran::class, 'mobil_id');
    }
    public function transaksiPenjualan()
    {
        return $this->hasMany(TransaksiPenjualan::class);
    }
}
