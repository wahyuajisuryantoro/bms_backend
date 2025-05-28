<?php

namespace App\Http\Controllers\Api;

use App\Models\Mobil;
use App\Models\MerkMobil;
use App\Models\Transmisi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
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
                'merk',
                'transmisi',
                'tipeBodi',
                'kapasitasMesin',
                'opsiPembayaran'
            ])
                ->where('tersedia', true)
                ->orderBy('created_at', 'desc')
                ->get();

            $mobilData = $mobil->map(function ($item) {
                $cashOption = $item->opsiPembayaran->where('metode', 'Cash')->first();
                $harga = $cashOption ? $cashOption->harga : $item->harga;

                return [
                    'id' => $item->id,
                    'nama_mobil' => $item->nama_mobil,
                    'thumbnail_foto' => $item->thumbnail_foto ? asset('storage/' . $item->thumbnail_foto) : null,
                    'harga' => $harga, 
                    'tahun_keluaran' => $item->tahun_keluaran,
                    'merk_id' => $item->merk_id,
                    'merk' => $item->merk->nama_merk,
                    'foto_merk' => $item->merk->foto_merk ? asset('storage/' . $item->merk->foto_merk) : null,
                    'transmisi_id' => $item->transmisi_id,
                    'transmisi' => $item->transmisi->jenis_transmisi,
                    'tipe_bodi' => $item->tipeBodi->nama_tipe,
                    'kapasitas_mesin' => $item->kapasitasMesin->kapasitas
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
