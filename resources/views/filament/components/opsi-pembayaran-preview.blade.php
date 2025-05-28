<div x-data="{
    calculateTotal() {
        // Fungsi ini bisa dipanggil sesuai kebutuhan jika ingin update dinamis
    }
}">
    @php
        $mobilId = $getState()['mobil_id'] ?? null;
        $hargaCash = $getState()['harga_cash'] ?? null;
        $opsiKredit = $getState()['opsi_kredit'] ?? [];

        $mobilNama = null;
        if ($mobilId) {
            $mobil = \App\Models\Mobil::find($mobilId);
            $mobilNama = $mobil ? $mobil->nama_mobil : 'Mobil #' . $mobilId;
        }
    @endphp

    @if (!$mobilId || !$hargaCash || empty($opsiKredit))
        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
            <p class="text-gray-500">Lengkapi formulir untuk melihat preview data.</p>
        </div>
    @else
        <div class="space-y-4">
            <!-- Informasi Mobil dan Harga Cash -->
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-lg font-bold">{{ $mobilNama }}</h3>
                <p class="font-medium">Harga Cash: Rp {{ number_format($hargaCash, 0, ',', '.') }}</p>
            </div>

            <!-- Opsi Kredit -->
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-lg font-bold mb-2">Opsi Kredit:</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($opsiKredit as $index => $opsi)
                        @php
                            $tenor = intval($opsi['tenor'] ?? 0);
                            $dpMinimal = floatval($opsi['dp_minimal'] ?? 0);
                            $angsuranPerBulan = floatval($opsi['angsuran_per_bulan'] ?? 0);
                            $bunga = floatval($opsi['bunga'] ?? 0);
                            $biayaAdmin = floatval($opsi['biaya_admin'] ?? 0);
                            $biayaAsuransi = floatval($opsi['biaya_asuransi'] ?? 0);

                            if ($tenor && $angsuranPerBulan) {
                                $totalAngsuran = $angsuranPerBulan * $tenor;
                                $totalBiaya = $totalAngsuran + $dpMinimal + $biayaAdmin + $biayaAsuransi;
                            }
                        @endphp

                        @if ($tenor && $angsuranPerBulan)
                            <div class="p-3 border border-gray-200 rounded bg-white">
                                <div class="bg-primary-50 -m-3 mb-3 p-3 rounded-t border-b border-gray-200">
                                    <p class="font-medium text-primary-700">Tenor {{ $tenor }} bulan</p>
                                </div>

                                <div class="space-y-1">
                                    <div class="flex justify-between py-1">
                                        <span>DP Minimal:</span>
                                        <span>Rp {{ number_format($dpMinimal, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between py-1">
                                        <span>Angsuran:</span>
                                        <span>Rp {{ number_format($angsuranPerBulan, 0, ',', '.') }}/bulan</span>
                                    </div>
                                    <div class="flex justify-between py-1">
                                        <span>Bunga:</span>
                                        <span>{{ $bunga }}%</span>
                                    </div>
                                    <div class="flex justify-between py-1">
                                        <span>Biaya Admin:</span>
                                        <span>Rp {{ number_format($biayaAdmin, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between py-1">
                                        <span>Biaya Asuransi:</span>
                                        <span>Rp {{ number_format($biayaAsuransi, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="flex justify-between py-2 mt-2 border-t border-gray-200 font-bold">
                                        <span>Total Biaya:</span>
                                        <span class="text-primary-700">Rp
                                            {{ number_format($totalBiaya, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
