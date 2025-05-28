<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function getAllDataFavorite(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $favorites = Favorit::with([
                'mobil.merk:id,nama_merk,foto_merk',
                'mobil.transmisi:id,jenis_transmisi',
                'mobil.tipeBodi:id,nama_tipe',
                'mobil.bahanBakar:id,jenis_bahan_bakar',
                'mobil.kapasitasMesin:id,kapasitas',
                'mobil.warna:id,nama_warna',
                'mobil.opsiPembayaran'
            ])
            ->where('user_id', $user->id)
            ->where('is_favorited', 1)
            ->orderBy('created_at', 'desc')
            ->get();

            $favoritesData = $favorites->map(function ($favorite) {
                $mobil = $favorite->mobil;

                $cashOption = $mobil->opsiPembayaran->where('metode', 'Cash')->first();
                $harga = $cashOption ? $cashOption->harga : 0;

                return [
                    'favorite_id' => $favorite->id,
                    'mobil_id' => $mobil->id,
                    'nama_mobil' => $mobil->nama_mobil,
                    'merk' => $mobil->merk->nama_merk ?? '',
                    'merk_id' => $mobil->merk->id ?? null,
                    'foto_merk' => $mobil->merk->foto_merk ? asset('storage/' . $mobil->merk->foto_merk) : null,
                    'transmisi' => $mobil->transmisi->jenis_transmisi ?? '',
                    'tipe_bodi' => $mobil->tipeBodi->nama_tipe ?? '',
                    'bahan_bakar' => $mobil->bahanBakar->jenis_bahan_bakar ?? '',
                    'kapasitas_mesin' => $mobil->kapasitasMesin->kapasitas ?? 0,
                    'tahun_keluaran' => $mobil->tahun_keluaran,
                    'thumbnail_foto' => $mobil->thumbnail_foto ? asset('storage/' . $mobil->thumbnail_foto) : null,
                    'harga_cash' => $harga,
                    'warna' => $mobil->warna->pluck('nama_warna')->toArray(),
                    'is_favorited' => $favorite->is_favorited,
                    'created_at' => $favorite->created_at,
                    'updated_at' => $favorite->updated_at,
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengambil data favorit',
                'data' => $favoritesData,
                'total' => $favoritesData->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function removeFavorite(Request $request, $favoriteId)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $favorite = Favorit::where('id', $favoriteId)
                ->where('user_id', $user->id)
                ->first();

            if (!$favorite) {
                return response()->json([
                    'status' => false,
                    'message' => 'Favorit tidak ditemukan'
                ], 404);
            }

            $favorite->update(['is_favorited' => 0]);

            return response()->json([
                'status' => true,
                'message' => 'Favorit berhasil dihapus',
                'data' => [
                    'favorite_id' => $favorite->id,
                    'mobil_id' => $favorite->mobil_id,
                    'is_favorited' => $favorite->is_favorited
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function clearAllFavorites(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $updated = Favorit::where('user_id', $user->id)
                ->where('is_favorited', 1)
                ->update(['is_favorited' => 0]);

            return response()->json([
                'status' => true,
                'message' => 'Semua favorit berhasil dihapus',
                'data' => [
                    'cleared_count' => $updated
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}