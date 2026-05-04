<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Mobil;
use App\Models\MerkMobil;
use App\Models\Transmisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{

    public function homeData(Request $request)
    {
        try {
            $brands = MerkMobil::select('id', 'nama_merk', 'foto_merk')
                ->get()
                ->map(function ($brand) {
                    return [
                        'id' => $brand->id,
                        'nama_merk' => $brand->nama_merk,
                        'foto_merk' => $brand->foto_merk ? asset('storage/' . $brand->foto_merk) : null
                    ];
                });

            $transmissions = Transmisi::select('id', 'jenis_transmisi')->get();

            $cars = Mobil::select(
                'id',
                'nama_mobil',
                'merk_id',
                'transmisi_id',
                'tipe_bodi_id',
                'kapasitas_mesin_id',
                'tahun_keluaran',
                'thumbnail_foto'
            )
                ->with([
                    'merk:id,nama_merk,foto_merk',
                    'transmisi:id,jenis_transmisi',
                    'tipeBodi:id,nama_tipe',
                    'kapasitasMesin:id,kapasitas',

                    'opsiPembayaran' => function ($query) {
                        $query->where('is_active', true);
                    }
                ])
                ->where('tersedia', 1)
                ->orderByDesc('created_at')
                ->limit(12)
                ->get()
                ->map(function ($car) {

                    $opsiPembayaran = $car->opsiPembayaran->where('is_active', true)->first();
                    $harga = $opsiPembayaran ? $opsiPembayaran->harga : 0;

                    return [
                        'id' => $car->id,
                        'nama_mobil' => $car->nama_mobil,
                        'merk_id' => $car->merk_id,
                        'transmisi_id' => $car->transmisi_id,
                        'tipe_bodi_id' => $car->tipe_bodi_id,
                        'kapasitas_mesin_id' => $car->kapasitas_mesin_id,
                        'tahun_keluaran' => $car->tahun_keluaran,
                        'thumbnail_foto' => $car->thumbnail_foto ? asset('storage/' . $car->thumbnail_foto) : null,
                        'merk' => $car->merk ? [
                            'id' => $car->merk->id,
                            'nama_merk' => $car->merk->nama_merk,
                            'foto_merk' => $car->merk->foto_merk ? asset('storage/' . $car->merk->foto_merk) : null
                        ] : null,
                        'transmisi' => $car->transmisi ? [
                            'id' => $car->transmisi->id,
                            'jenis_transmisi' => $car->transmisi->jenis_transmisi
                        ] : null,
                        'tipe_bodi' => $car->tipeBodi ? $car->tipeBodi->nama_tipe : null,
                        'kapasitas_mesin' => $car->kapasitasMesin ? $car->kapasitasMesin->kapasitas : null,

                        'harga' => $harga,
                    ];
                });

            return response()->json([
                'status' => true,
                'brands' => $brands,
                'transmissions' => $transmissions,
                'cars' => $cars,
            ], 200);

        } catch (Exception $e) {
            Log::error('HomeData error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan pada server'
            ], 500);
        }
    }
    public function getAllDataMerkMobil(Request $request)
    {
        try {
            $user = $request->user();
            $merkMobil = MerkMobil::all();
            $merkMobil = $merkMobil->map(function ($merk) {
                if ($merk->foto_merk) {
                    $merk->foto_merk = asset('storage/' . $merk->foto_merk);
                } else {
                    $merk->foto_merk = asset('images/car_placeholder.png');
                }
                return $merk;
            });

            return response()->json([
                'status' => true,
                'message' => 'Data merk mobil berhasil diambil',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ],
                'data' => $merkMobil
            ]);
        } catch (\Exception $e) {
            \Log::error('Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan',
                'data' => []
            ], 500);
        }
    }

    public function getAllDataTransmisi(Request $request)
    {
        try {
            $user = $request->user();
            $transmisi = Transmisi::all();

            return response()->json([
                'status' => true,
                'message' => 'Data transmisi berhasil diambil',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ],
                'data' => $transmisi
            ]);
        } catch (\Exception $e) {
            \Log::error('Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan',
                'data' => []
            ], 500);
        }
    }

    public function getDataMobilDashboard(Request $request)
    {
        try {
            $user = $request->user();
            $mobil = Mobil::with([
                'merk:id,nama_merk,foto_merk',
                'transmisi:id,jenis_transmisi',
                'tipeBodi:id,nama_tipe',
                'kapasitasMesin:id,kapasitas',
                'opsiPembayaran.konfigurasiKredit:id,nama_template,tenor_bunga_config,is_active'
            ])
                ->where('tersedia', true)
                ->orderBy('created_at', 'desc')
                ->get();

            $mobilData = $mobil->map(function ($item) {

                $opsiPembayaran = $item->opsiPembayaran->where('is_active', true)->first();


                $harga = 0;
                $hasKredit = false;

                if ($opsiPembayaran) {
                    $harga = $opsiPembayaran->harga;
                    $hasKredit = $opsiPembayaran->is_kredit && $opsiPembayaran->konfigurasiKredit;
                }

                return [
                    'id' => $item->id,
                    'nama_mobil' => $item->nama_mobil,
                    'thumbnail_foto' => $item->thumbnail_foto ? asset('storage/' . $item->thumbnail_foto) : null,
                    'harga' => $harga,
                    'harga_cash' => $harga,
                    'tahun_keluaran' => $item->tahun_keluaran,
                    'merk_id' => $item->merk_id,
                    'merk' => $item->merk->nama_merk,
                    'foto_merk' => $item->merk->foto_merk ? asset('storage/' . $item->merk->foto_merk) : null,
                    'transmisi_id' => $item->transmisi_id,
                    'transmisi' => $item->transmisi->jenis_transmisi,
                    'tipe_bodi' => $item->tipeBodi->nama_tipe,
                    'kapasitas_mesin' => $item->kapasitasMesin->kapasitas,
                    'has_kredit' => $hasKredit,
                    'metode_pembayaran' => $hasKredit ? ['Cash', 'Kredit'] : ['Cash'],
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Data mobil berhasil diambil',
                'data' => $mobilData
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting car data: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan',
                'data' => []
            ], 500);
        }
    }
}
