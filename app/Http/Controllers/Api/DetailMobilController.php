<?php

namespace App\Http\Controllers\Api;

use App\Models\Mobil;
use App\Models\Favorit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DetailMobilController extends Controller
{
    public function getDetailMobil(Request $request, $id)
    {
        try {
            $user = $request->user();
            $mobil = Mobil::with([
                'merk:id,nama_merk,foto_merk',
                'transmisi:id,jenis_transmisi',
                'tipeBodi:id,nama_tipe',
                'bahanBakar:id,jenis_bahan_bakar',
                'kapasitasMesin:id,kapasitas',
                'warna:id,nama_warna',
                'fotoDetail:id,mobil_id,foto_path,jenis_foto',
                'opsiPembayaran'
            ])
                ->findOrFail($id);

            $cashOption = $mobil->opsiPembayaran->where('metode', 'Cash')->first();
            $harga = $cashOption ? $cashOption->harga : 0;
            $kreditOption = $mobil->opsiPembayaran
                ->where('metode', 'Kredit')
                ->sortBy('tenor')
                ->first();

            $fotoDetail = $mobil->fotoDetail->map(function ($foto) {
                return [
                    'id' => $foto->id,
                    'mobil_id' => $foto->mobil_id,
                    'foto_path' => $foto->foto_path ? asset('storage/' . $foto->foto_path) : null,
                    'jenis_foto' => $foto->jenis_foto,
                ];
            });

            // Cek apakah mobil sudah masuk favorit user
            $isFavorited = 0;
            $favoriteId = null;

            if ($user) {
                $favorite = \App\Models\Favorit::forUserAndMobil($user->id, $id)
                    ->favorited()
                    ->first();

                if ($favorite) {
                    $isFavorited = 1;
                    $favoriteId = $favorite->id;
                }
            }

            $detailMobil = [
                'id' => $mobil->id,
                'nama_mobil' => $mobil->nama_mobil,
                'merk' => $mobil->merk->nama_merk,
                'merk_id' => $mobil->merk->id,
                'foto_merk' => $mobil->merk->foto_merk ? asset('storage/' . $mobil->merk->foto_merk) : null,
                'transmisi' => $mobil->transmisi->jenis_transmisi,
                'transmisi_id' => $mobil->transmisi->id,
                'tipe_bodi' => $mobil->tipeBodi->nama_tipe,
                'tipe_bodi_id' => $mobil->tipeBodi->id,
                'bahan_bakar' => $mobil->bahanBakar->jenis_bahan_bakar,
                'bahan_bakar_id' => $mobil->bahanBakar->id,
                'kapasitas_mesin' => $mobil->kapasitasMesin->kapasitas,
                'kapasitas_mesin_id' => $mobil->kapasitasMesin->id,
                'tahun_keluaran' => $mobil->tahun_keluaran,
                'thumbnail_foto' => $mobil->thumbnail_foto ? asset('storage/' . $mobil->thumbnail_foto) : null,
                'deskripsi' => $mobil->deskripsi,
                'tersedia' => $mobil->tersedia,
                'warna' => $mobil->warna->pluck('nama_warna')->toArray(),
                'harga_cash' => $harga,
                'foto_detail' => $fotoDetail,
                'opsi_kredit' => $kreditOption ? [
                    'tenor' => $kreditOption->tenor,
                    'dp_minimal' => $kreditOption->dp_minimal,
                    'angsuran_per_bulan' => $kreditOption->angsuran_per_bulan,
                ] : null,
                'opsi_pembayaran' => $mobil->opsiPembayaran->map(function ($opsi) {
                    return [
                        'id' => $opsi->id,
                        'metode' => $opsi->metode,
                        'tenor' => $opsi->tenor,
                        'harga' => $opsi->harga,
                        'dp_minimal' => $opsi->dp_minimal,
                        'angsuran_per_bulan' => $opsi->angsuran_per_bulan,
                    ];
                }),
                'is_favorited' => $isFavorited,
                'favorite_id' => $favoriteId,
            ];

            $userData = null;
            if ($user) {
                $userData = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            }

            return response()->json([
                'status' => true,
                'message' => 'Detail mobil berhasil diambil',
                'user' => $userData,
                'data' => $detailMobil
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function simulasiKredit(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Anda harus login untuk menggunakan fitur ini'
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'tenor' => 'required|integer|min:12|max:60',
            'dp_percentage' => 'required|numeric|min:10|max:70',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $mobil = Mobil::with([
                'opsiPembayaran' => function ($query) {
                    $query->where('metode', 'Cash');
                }
            ])->findOrFail($id);

            $cashOption = $mobil->opsiPembayaran->first();
            if (!$cashOption) {
                return response()->json([
                    'status' => false,
                    'message' => 'Opsi pembayaran cash tidak tersedia untuk mobil ini'
                ], 404);
            }

            $hargaOtr = $cashOption->harga;
            $tenorBulan = $request->tenor;
            $dpPercentage = $request->dp_percentage;

            // Hitung DP
            $dpAmount = ($dpPercentage / 100) * $hargaOtr;

            // Tentukan suku bunga berdasarkan tenor
            $bungaTahunan = $this->getBungaTahunan($tenorBulan);
            $bungaBulanan = $bungaTahunan / 12 / 100;

            // Hitung pokok kredit
            $pokokKredit = $hargaOtr - $dpAmount;

            // Hitung angsuran bulanan (rumus: A = P × r × (1 + r)^n / ((1 + r)^n - 1))
            // A = angsuran bulanan
            // P = pokok kredit
            // r = suku bunga bulanan
            // n = jumlah periode pembayaran (tenor)
            $angsuranBulanan = $pokokKredit * $bungaBulanan * pow((1 + $bungaBulanan), $tenorBulan) / (pow((1 + $bungaBulanan), $tenorBulan) - 1);

            // Biaya-biaya tambahan (dibuat 0 sesuai permintaan)
            $biayaAdmin = 0;
            $biayaAsuransi = 0;

            // Total pembayaran
            $totalKredit = ($angsuranBulanan * $tenorBulan) + $dpAmount + $biayaAdmin + $biayaAsuransi;

            // Rincian angsuran per bulan
            $rincianAngsuran = [];
            $sisaPokok = $pokokKredit;

            // Batasi rincian angsuran untuk 12 bulan pertama saja
            $maxRincianBulan = min($tenorBulan, 12);

            for ($bulan = 1; $bulan <= $maxRincianBulan; $bulan++) {
                $bungaBulanIni = $sisaPokok * $bungaBulanan;
                $pokokBulanIni = $angsuranBulanan - $bungaBulanIni;
                $sisaPokok -= $pokokBulanIni;

                $rincianAngsuran[] = [
                    'bulan' => $bulan,
                    'angsuran' => round($angsuranBulanan),
                    'pokok' => round($pokokBulanIni),
                    'bunga' => round($bungaBulanIni),
                    'sisa_pokok' => max(0, round($sisaPokok))
                ];
            }

            return response()->json([
                'status' => true,
                'message' => 'Simulasi kredit berhasil dihitung',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'data' => [
                    'mobil' => [
                        'id' => $mobil->id,
                        'nama_mobil' => $mobil->nama_mobil,
                        'merk' => $mobil->merk->nama_merk,
                        'thumbnail_foto' => $mobil->thumbnail_foto ? asset('storage/' . $mobil->thumbnail_foto) : null,
                    ],
                    'harga_otr' => $hargaOtr,
                    'tenor' => $tenorBulan,
                    'bunga_tahunan' => $bungaTahunan,
                    'dp_percentage' => $dpPercentage,
                    'dp_amount' => round($dpAmount),
                    'pokok_kredit' => round($pokokKredit),
                    'angsuran_per_bulan' => round($angsuranBulanan),
                    'biaya_admin' => $biayaAdmin,
                    'biaya_asuransi' => $biayaAsuransi,
                    'total_pembayaran' => round($totalKredit),
                    'rincian_angsuran' => $rincianAngsuran,
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

    public function toggleFavoriteStatus(Request $request, $mobilId)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            if (!is_numeric($mobilId)) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID mobil tidak valid'
                ], 422);
            }

            $mobil = Mobil::find($mobilId);
            if (!$mobil) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mobil tidak ditemukan'
                ], 404);
            }

            $favorite = Favorit::forUserAndMobil($user->id, $mobilId)->first();

            if (!$favorite) {
                $favorite = Favorit::create([
                    'user_id' => $user->id,
                    'mobil_id' => $mobilId,
                    'is_favorited' => 1
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Mobil berhasil ditambahkan ke favorit',
                    'data' => [
                        'id' => $favorite->id,
                        'mobil_id' => $favorite->mobil_id,
                        'is_favorited' => $favorite->is_favorited,
                        'action' => 'added'
                    ]
                ], 201);
            } else {
                $newStatus = $favorite->is_favorited == 1 ? 0 : 1;
                $favorite->update(['is_favorited' => $newStatus]);

                $message = $newStatus == 1
                    ? 'Mobil berhasil ditambahkan ke favorit'
                    : 'Mobil berhasil dihapus dari favorit';

                $action = $newStatus == 1 ? 'added' : 'removed';

                return response()->json([
                    'status' => true,
                    'message' => $message,
                    'data' => [
                        'id' => $favorite->id,
                        'mobil_id' => $favorite->mobil_id,
                        'is_favorited' => $favorite->is_favorited,
                        'action' => $action
                    ]
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan bunga tahunan berdasarkan tenor
     */
    private function getBungaTahunan($tenor)
    {
      
        if ($tenor <= 12) {
            return 8.5;
        } elseif ($tenor <= 24) {
            return 9.5; 
        } elseif ($tenor <= 36) {
            return 10.5;
        } elseif ($tenor <= 48) {
            return 11.5;
        } else {
            return 12.5;
        }
    }

    /**
     * Mendapatkan opsi-opsi tenor kredit yang tersedia
     */
    public function getTenorOptions(Request $request)
    {
        // Mendapatkan user yang sedang login
        $user = $request->user();

        // Pastikan user telah login
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Anda harus login untuk menggunakan fitur ini'
            ], 401);
        }

        $tenorOptions = [
            ['tenor' => 12, 'label' => '12 Bulan (1 Tahun)'],
            ['tenor' => 24, 'label' => '24 Bulan (2 Tahun)'],
            ['tenor' => 36, 'label' => '36 Bulan (3 Tahun)'],
            ['tenor' => 48, 'label' => '48 Bulan (4 Tahun)'],
            ['tenor' => 60, 'label' => '60 Bulan (5 Tahun)'],
        ];

        return response()->json([
            'status' => true,
            'message' => 'Opsi tenor kredit berhasil diambil',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'data' => $tenorOptions
        ], 200);
    }

    /**
     * Mendapatkan opsi-opsi DP percentage yang tersedia
     */
    public function getDpOptions(Request $request)
    {
        // Mendapatkan user yang sedang login
        $user = $request->user();

        // Pastikan user telah login
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Anda harus login untuk menggunakan fitur ini'
            ], 401);
        }

        $dpOptions = [
            ['percentage' => 10, 'label' => '10%'],
            ['percentage' => 15, 'label' => '15%'],
            ['percentage' => 20, 'label' => '20%'],
            ['percentage' => 25, 'label' => '25%'],
            ['percentage' => 30, 'label' => '30%'],
            ['percentage' => 35, 'label' => '35%'],
            ['percentage' => 40, 'label' => '40%'],
            ['percentage' => 50, 'label' => '50%'],
        ];

        return response()->json([
            'status' => true,
            'message' => 'Opsi DP kredit berhasil diambil',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'data' => $dpOptions
        ], 200);
    }
}
