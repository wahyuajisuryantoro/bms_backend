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
                'opsiPembayaran.konfigurasiKredit:id,nama_template,tenor_bunga_config,is_active'
            ])
                ->findOrFail($id);

            $opsiPembayaran = $mobil->opsiPembayaran->where('is_active', true)->first();
            
            if (!$opsiPembayaran) {
                return response()->json([
                    'status' => false,
                    'message' => 'Opsi pembayaran tidak tersedia untuk mobil ini'
                ], 404);
            }

            $harga = $opsiPembayaran->harga;

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

            // Prepare opsi kredit data
            $opsiKredit = null;
            $tenorTersedia = [];

            if ($opsiPembayaran->is_kredit && $opsiPembayaran->konfigurasiKredit) {
                $konfigKredit = $opsiPembayaran->konfigurasiKredit;
                $tenorBungaConfig = $konfigKredit->tenor_bunga_config;
                
                if (is_array($tenorBungaConfig) && !empty($tenorBungaConfig)) {
                    $tenorTersedia = array_keys($tenorBungaConfig);
                    sort($tenorTersedia); // Sort ascending
                    
                    $opsiKredit = [
                        'nama_template' => $konfigKredit->nama_template,
                        'tenor_tersedia' => $tenorTersedia,
                        'tenor_bunga_config' => $tenorBungaConfig,
                        'dp_minimal_percentage' => 10, // 10% minimal
                        'dp_maksimal_percentage' => 70, // 70% maksimal
                    ];
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
                'opsi_kredit' => $opsiKredit,
                'metode_pembayaran' => $opsiPembayaran->available_methods,
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

        // Get mobil and opsi pembayaran first for validation
        $mobil = Mobil::with([
            'opsiPembayaran.konfigurasiKredit:id,nama_template,tenor_bunga_config,is_active'
        ])->find($id);

        if (!$mobil) {
            return response()->json([
                'status' => false,
                'message' => 'Mobil tidak ditemukan'
            ], 404);
        }

        $opsiPembayaran = $mobil->opsiPembayaran->where('is_active', true)->first();
        
        if (!$opsiPembayaran) {
            return response()->json([
                'status' => false,
                'message' => 'Opsi pembayaran tidak tersedia untuk mobil ini'
            ], 404);
        }

        if (!$opsiPembayaran->is_kredit || !$opsiPembayaran->konfigurasiKredit) {
            return response()->json([
                'status' => false,
                'message' => 'Opsi kredit tidak tersedia untuk mobil ini'
            ], 404);
        }

        $hargaOtr = $opsiPembayaran->harga;
        $konfigKredit = $opsiPembayaran->konfigurasiKredit;
        $tenorBungaConfig = $konfigKredit->tenor_bunga_config;

        // Dynamic validation based on available tenor and DP limits
        $tenorTersedia = array_keys($tenorBungaConfig);
        $dpMinimal = ($hargaOtr * 10) / 100; // 10% dari harga
        $dpMaksimal = ($hargaOtr * 70) / 100; // 70% dari harga

        $validator = Validator::make($request->all(), [
            'tenor' => 'required|integer|in:' . implode(',', $tenorTersedia),
            'dp_amount' => [
                'required',
                'numeric',
                'min:' . $dpMinimal,
                'max:' . $dpMaksimal
            ],
        ], [
            'tenor.in' => 'Tenor yang dipilih tidak tersedia. Tenor tersedia: ' . implode(', ', $tenorTersedia) . ' bulan',
            'dp_amount.min' => 'DP minimal adalah Rp ' . number_format($dpMinimal, 0, ',', '.') . ' (10% dari harga)',
            'dp_amount.max' => 'DP maksimal adalah Rp ' . number_format($dpMaksimal, 0, ',', '.') . ' (70% dari harga)',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tenorBulan = $request->tenor;
            $dpAmount = $request->dp_amount;

            // Get bunga from konfigurasi kredit
            $bungaTahunan = $tenorBungaConfig[$tenorBulan];
            $bungaBulanan = $bungaTahunan / 12 / 100;

            // Calculate DP percentage
            $dpPercentage = ($dpAmount / $hargaOtr) * 100;

            // Hitung pokok kredit
            $pokokKredit = $hargaOtr - $dpAmount;

            // Hitung angsuran bulanan menggunakan rumus anuitas
            // PMT = PV * [r * (1 + r)^n] / [(1 + r)^n - 1]
            $angsuranBulanan = $pokokKredit * $bungaBulanan * pow((1 + $bungaBulanan), $tenorBulan) / (pow((1 + $bungaBulanan), $tenorBulan) - 1);

            // Biaya-biaya tambahan (sesuai permintaan = 0)
            $biayaAdmin = 0;
            $biayaAsuransi = 0;

            // Total pembayaran
            $totalKredit = ($angsuranBulanan * $tenorBulan) + $dpAmount + $biayaAdmin + $biayaAsuransi;

            // Rincian angsuran per bulan (12 bulan pertama)
            $rincianAngsuran = [];
            $sisaPokok = $pokokKredit;
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
                    'konfigurasi_kredit' => [
                        'id' => $konfigKredit->id,
                        'nama_template' => $konfigKredit->nama_template,
                    ],
                    'harga_otr' => $hargaOtr,
                    'tenor' => $tenorBulan,
                    'bunga_tahunan' => $bungaTahunan,
                    'bunga_bulanan' => round($bungaBulanan * 100, 4),
                    'dp_percentage' => round($dpPercentage, 2),
                    'dp_amount' => round($dpAmount),
                    'pokok_kredit' => round($pokokKredit),
                    'angsuran_per_bulan' => round($angsuranBulanan),
                    'biaya_admin' => $biayaAdmin,
                    'biaya_asuransi' => $biayaAsuransi,
                    'total_pembayaran' => round($totalKredit),
                    'total_bunga' => round($totalKredit - $hargaOtr),
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
     * Get tenor options for specific mobil based on konfigurasi kredit
     */
    public function getTenorOptions(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Anda harus login untuk menggunakan fitur ini'
            ], 401);
        }

        try {
            $mobil = Mobil::with([
                'opsiPembayaran.konfigurasiKredit:id,nama_template,tenor_bunga_config,is_active'
            ])->find($id);

            if (!$mobil) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mobil tidak ditemukan'
                ], 404);
            }

            $opsiPembayaran = $mobil->opsiPembayaran->where('is_active', true)->first();
            
            if (!$opsiPembayaran || !$opsiPembayaran->is_kredit || !$opsiPembayaran->konfigurasiKredit) {
                return response()->json([
                    'status' => false,
                    'message' => 'Opsi kredit tidak tersedia untuk mobil ini'
                ], 404);
            }

            $konfigKredit = $opsiPembayaran->konfigurasiKredit;
            $tenorBungaConfig = $konfigKredit->tenor_bunga_config;

            $tenorOptions = [];
            foreach ($tenorBungaConfig as $tenor => $bunga) {
                $tahun = intval($tenor / 12);
                $sisaBulan = $tenor % 12;
                
                $label = $tenor . ' Bulan';
                if ($tahun > 0) {
                    $label .= ' (' . $tahun . ' Tahun';
                    if ($sisaBulan > 0) {
                        $label .= ' ' . $sisaBulan . ' Bulan';
                    }
                    $label .= ')';
                }

                $tenorOptions[] = [
                    'tenor' => intval($tenor),
                    'label' => $label,
                    'bunga_tahunan' => $bunga
                ];
            }

            // Sort by tenor ascending
            usort($tenorOptions, function($a, $b) {
                return $a['tenor'] <=> $b['tenor'];
            });

            return response()->json([
                'status' => true,
                'message' => 'Opsi tenor kredit berhasil diambil',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'data' => [
                    'konfigurasi_kredit' => [
                        'id' => $konfigKredit->id,
                        'nama_template' => $konfigKredit->nama_template,
                    ],
                    'tenor_options' => $tenorOptions
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

    /**
     * Get DP validation info for specific mobil
     */
    public function getDpValidationInfo(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Anda harus login untuk menggunakan fitur ini'
            ], 401);
        }

        try {
            $mobil = Mobil::with(['opsiPembayaran'])->find($id);

            if (!$mobil) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mobil tidak ditemukan'
                ], 404);
            }

            $opsiPembayaran = $mobil->opsiPembayaran->where('is_active', true)->first();
            
            if (!$opsiPembayaran) {
                return response()->json([
                    'status' => false,
                    'message' => 'Opsi pembayaran tidak tersedia untuk mobil ini'
                ], 404);
            }

            $hargaOtr = $opsiPembayaran->harga;
            $dpMinimal = ($hargaOtr * 10) / 100; // 10%
            $dpMaksimal = ($hargaOtr * 70) / 100; // 70%

            return response()->json([
                'status' => true,
                'message' => 'Informasi DP berhasil diambil',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'data' => [
                    'harga_otr' => $hargaOtr,
                    'dp_minimal_amount' => $dpMinimal,
                    'dp_maksimal_amount' => $dpMaksimal,
                    'dp_minimal_percentage' => 10,
                    'dp_maksimal_percentage' => 70,
                    'validation_message' => 'DP minimal 10% (Rp ' . number_format($dpMinimal, 0, ',', '.') . ') dan maksimal 70% (Rp ' . number_format($dpMaksimal, 0, ',', '.') . ') dari harga mobil'
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