@php
    $hasSignature = !empty($surat->ttd_siswa_path);
    $signedName   = $surat->ttd_siswa_signed_name ?? $surat->siswa->nama_siswa ?? 'Siswa';
    $signedAt     = $surat->ttd_siswa_signed_at;
@endphp

<div class="dp-ttd-siswa-wrapper" id="dp_ttd_siswa_wrapper" data-surat-id="{{ $surat->id_dispen }}" data-nama-siswa="{{ $signedName }}" data-has-signature="{{ $hasSignature ? '1' : '0' }}">

    @if($hasSignature)
        <img src="{{ $surat->ttd_siswa_url }}" alt="TTD {{ $signedName }}" class="dp-ttd-img" id="dp_ttd_siswa_img" style="max-height: 80px; max-width: 100%; display:block; margin: 0 auto 4px auto;" onerror="this.style.display='none';">
        <div style="border-top: 1px solid #000; padding-top: 4px; font-weight: bold;" id="dp_ttd_siswa_nama">
            ( {{ $signedName }} )
        </div>
    @else
        <div class="dp-signature-container" id="dp_signature_container" style="border:1px dashed #555; border-radius:4px; padding:6px; background:#fafafa; margin: 0 auto 4px auto; max-width: 320px;">
            <canvas id="dp_canvas_siswa" width="300" height="120" style="width:100%; height:120px; background:#fff; touch-action: none; display:block; margin: 0 auto; cursor: crosshair;"></canvas>
            <div style="display:flex; gap:6px; justify-content:center; margin-top:4px;">
                <button type="button" class="dp-btn-clear" id="dp_btn_clear_ttd" style="font-size:0.7rem; padding:3px 8px; background:#e5e7eb; border:1px solid #9ca3af; color:#374151; border-radius:3px; cursor:pointer;">
                    Bersihkan
                </button>
                <button type="button" class="dp-btn-simpan" id="dp_btn_simpan_ttd" style="font-size:0.7rem; padding:3px 8px; background:#1e2538; border:1px solid #1e2538; color:#fff; border-radius:3px; cursor:pointer;" disabled>
                    Simpan TTD
                </button>
            </div>
        </div>
        <div style="border-top: 1px solid #000; padding-top: 4px; font-weight: bold; margin-top:6px; min-height: 22px;" id="dp_ttd_siswa_nama">
            ( <span style="color:#888;">menunggu tanda tangan</span> )
        </div>
    @endif

</div>
