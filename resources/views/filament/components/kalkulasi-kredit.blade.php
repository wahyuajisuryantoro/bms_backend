<div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
    <h3 class="text-lg font-medium mb-4">Kalkulasi</h3>
    
    @if(isset($tenor) && isset($angsuranPerBulan) && isset($dpMinimal) && isset($biayaAdmin) && isset($biayaAsuransi))
        <dl class="grid grid-cols-2 gap-y-2">
            <dt class="font-medium">Total Angsuran:</dt>
            <dd>Rp {{ number_format($totalAngsuran, 0, ',', '.') }}</dd>
            
            <dt class="font-medium">DP Minimal:</dt>
            <dd>Rp {{ number_format($dpMinimal, 0, ',', '.') }}</dd>
            
            <dt class="font-medium">Biaya Admin:</dt>
            <dd>Rp {{ number_format($biayaAdmin, 0, ',', '.') }}</dd>
            
            <dt class="font-medium">Biaya Asuransi:</dt>
            <dd>Rp {{ number_format($biayaAsuransi, 0, ',', '.') }}</dd>
            
            <dt class="font-medium text-primary-600">Total Biaya:</dt>
            <dd class="font-bold text-primary-600">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</dd>
        </dl>
    @else
        <p class="text-gray-500">Silakan isi data tenor dan angsuran untuk melihat kalkulasi.</p>
    @endif
</div>