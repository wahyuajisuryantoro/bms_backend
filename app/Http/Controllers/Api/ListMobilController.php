<?php

namespace App\Http\Controllers\Api;

use App\Models\Mobil;
use App\Models\Warna;
use App\Models\Favorit;
use App\Models\TipeBodi;
use App\Models\MerkMobil;
use App\Models\Transmisi;
use App\Models\BahanBakar;
use Illuminate\Http\Request;
use App\Models\KapasitasMesin;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ListMobilController extends Controller
{
public function getAllDataMobil(Request $request)
{
    try {
        // Ambil data user yang sedang login
        $user = $request->user();
        $userId = $user ? $user->id : null;

        // Query data mobil dengan semua relasinya
        $mobil = Mobil::with([
            'merk:id,nama_merk,foto_merk',
            'transmisi:id,jenis_transmisi',
            'tipeBodi:id,nama_tipe',
            'bahanBakar:id,jenis_bahan_bakar',
            'kapasitasMesin:id,kapasitas',
            'warna:id,nama_warna',
            'fotoDetail:id,mobil_id,foto_path,jenis_foto',
            'opsiPembayaran.konfigurasiKredit:id,nama_template,tenor_bunga_config,is_active'
        ])
        ->where('tersedia', true)
        ->get();

        // Cek favorit untuk setiap mobil jika user terautentikasi
        if ($userId) {
            $userFavorites = Favorit::where('user_id', $userId)
                ->where('is_favorited', 1)
                ->pluck('mobil_id')
                ->toArray();

            // Tambahkan field is_favorite ke setiap mobil
            $mobil->each(function ($item) use ($userFavorites) {
                $item->is_favorite = in_array($item->id, $userFavorites);
            });
        } else {
            // Jika user tidak login, semua mobil tidak favorit
            $mobil->each(function ($item) {
                $item->is_favorite = false;
            });
        }

        // Format data untuk respons API
        $formattedMobil = $mobil->map(function ($item) {
            // Ambil opsi pembayaran yang aktif
            $opsiPembayaran = $item->opsiPembayaran->where('is_active', true)->first();
            
            // Default values jika tidak ada opsi pembayaran
            $harga = 0;
            $opsiKredit = null;
            $metodeList = ['Cash']; // Default hanya cash

            if ($opsiPembayaran) {
                $harga = $opsiPembayaran->harga;
                
                // Jika ada opsi kredit
                if ($opsiPembayaran->is_kredit && $opsiPembayaran->konfigurasiKredit) {
                    $metodeList[] = 'Kredit';
                    
                    $konfigKredit = $opsiPembayaran->konfigurasiKredit;
                    $tenorBungaConfig = $konfigKredit->tenor_bunga_config;
                    
                    if (is_array($tenorBungaConfig) && !empty($tenorBungaConfig)) {
                        // Ambil tenor terpendek untuk preview
                        $tenorTerpendek = min(array_keys($tenorBungaConfig));
                        $bungaTahunan = $tenorBungaConfig[$tenorTerpendek];
                        
                        // Hitung estimasi angsuran dengan DP 20%
                        $dpEstimasi = $harga * 0.20;
                        $pokokKredit = $harga - $dpEstimasi;
                        $bungaBulanan = $bungaTahunan / 12 / 100;
                        
                        $angsuranEstimasi = $pokokKredit * $bungaBulanan * 
                                          pow((1 + $bungaBulanan), $tenorTerpendek) / 
                                          (pow((1 + $bungaBulanan), $tenorTerpendek) - 1);
                        
                        $opsiKredit = [
                            'tenor_minimal' => $tenorTerpendek,
                            'bunga_tahunan' => $bungaTahunan,
                            'dp_minimal' => $harga * 0.10, // 10% minimal
                            'estimasi_angsuran' => round($angsuranEstimasi),
                            'template_kredit' => $konfigKredit->nama_template,
                        ];
                    }
                }
            }

            // Format foto detail dengan URL lengkap
            $fotoDetail = $item->fotoDetail->map(function($foto) {
                return [
                    'id' => $foto->id,
                    'mobil_id' => $foto->mobil_id,
                    'foto_path' => $foto->foto_path ? asset('storage/' . $foto->foto_path) : null,
                    'jenis_foto' => $foto->jenis_foto,
                ];
            });

            return [
                'id' => $item->id,
                'nama_mobil' => $item->nama_mobil,
                'merk' => $item->merk->nama_merk,
                'merk_id' => $item->merk->id,
                'foto_merk' => $item->merk->foto_merk ? asset('storage/' . $item->merk->foto_merk) : null,
                'transmisi' => $item->transmisi->jenis_transmisi,
                'transmisi_id' => $item->transmisi->id,
                'tipe_bodi' => $item->tipeBodi->nama_tipe,
                'tipe_bodi_id' => $item->tipeBodi->id,
                'bahan_bakar' => $item->bahanBakar->jenis_bahan_bakar,
                'bahan_bakar_id' => $item->bahanBakar->id,
                'kapasitas_mesin' => $item->kapasitasMesin->kapasitas,
                'kapasitas_mesin_id' => $item->kapasitasMesin->id,
                'tahun_keluaran' => $item->tahun_keluaran,
                'thumbnail_foto' => $item->thumbnail_foto ? asset('storage/' . $item->thumbnail_foto) : null,
                'deskripsi' => $item->deskripsi,
                'tersedia' => $item->tersedia,
                'is_favorite' => $item->is_favorite,
                'warna' => $item->warna->pluck('nama_warna'),
                'harga' => $harga,
                'harga_cash' => $harga, // Alias untuk kompatibilitas
                'foto_detail' => $fotoDetail,
                'opsi_kredit' => $opsiKredit,
                'metode_pembayaran' => $metodeList,
                'has_kredit' => $opsiPembayaran ? $opsiPembayaran->is_kredit : false,
            ];
        });

        // Metode pembayaran untuk filter
        $opsiPembayaranFilter = [
            ['id' => 'cash', 'name' => 'Cash'],
            ['id' => 'kredit', 'name' => 'Kredit'],
        ];

        // Ambil data master untuk filter dengan URL gambar yang lengkap
        $merks = MerkMobil::select('id', 'nama_merk as name', 'foto_merk as image')->get()
            ->map(function($merk) {
                $merk->image = $merk->image ? asset('storage/' . $merk->image) : null;
                return $merk;
            });
            
        $transmisi = Transmisi::select('id', 'jenis_transmisi as name')->get();
        $tipeBodi = TipeBodi::select('id', 'nama_tipe as name')->get();
        $warna = Warna::select('id', 'nama_warna as name')->get();
        $bahanBakar = BahanBakar::select('id', 'jenis_bahan_bakar as name')->get();
        
        // Ambil list tahun keluaran yang tersedia di database
        $years = DB::table('mobil')
            ->select(DB::raw('DISTINCT tahun_keluaran as id'), DB::raw('tahun_keluaran as name'))
            ->where('tersedia', true)
            ->orderBy('tahun_keluaran', 'DESC')
            ->get();
            
        // Ambil rentang kapasitas mesin untuk filter
        $kapasitasMesin = KapasitasMesin::select('id', 'kapasitas as name')
            ->orderBy('kapasitas')
            ->get()
            ->map(function($item) {
                $item->name = $item->name . ' cc';
                return $item;
            });

        return response()->json([
            'status' => true,
            'message' => 'Data mobil berhasil diambil',
            'data' => $formattedMobil,
            'filter_options' => [
                'brands' => $merks,
                'transmisi' => $transmisi,
                'tipe_bodi' => $tipeBodi,
                'warna' => $warna,
                'bahan_bakar' => $bahanBakar,
                'tahun_keluaran' => $years,
                'kapasitas_mesin' => $kapasitasMesin,
                'metode_pembayaran' => $opsiPembayaranFilter,
            ]
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            'data' => null
        ], 500);
    }
}
}