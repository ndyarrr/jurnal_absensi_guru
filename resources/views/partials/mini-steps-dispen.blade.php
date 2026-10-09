@php
    // Alur dispensasi siswa: Guru Piket -> Waka -> Waka Kurikulum (Kepala Sekolah tidak diperlukan).
    $tahapWaka = $dispen->status_waka;
    $tahapKur  = $dispen->status_waka_kurikulum;
    $ditolakSemua = $dispen->status_approval === 'ditolak';

    $aktif = null;
    if (! $ditolakSemua) {
        if ($tahapWaka !== 'disetujui') { $aktif = 'waka'; }
        elseif ($tahapKur !== 'disetujui') { $aktif = 'kur'; }
    }
    $cls = function ($status, $key) use ($aktif) {
        if ($status === 'disetujui') return 'ok';
        if ($status === 'ditolak') return 'no';
        return $aktif === $key ? 'now' : '';
    };
    $ico = fn ($c) => $c === 'ok' ? 'fa-check' : ($c === 'no' ? 'fa-xmark' : 'fa-ellipsis');
@endphp
<div class="mini-steps" title="Piket → Waka → Waka Kurikulum">
    <span class="mini-step ok" title="Guru Piket"><i class="fa-solid fa-check"></i></span><span class="mini-line"></span>
    <span class="mini-step {{ $cls($tahapWaka, 'waka') }}" title="Waka"><i class="fa-solid {{ $ico($cls($tahapWaka, 'waka')) }}"></i></span><span class="mini-line"></span>
    <span class="mini-step {{ $cls($tahapKur, 'kur') }}" title="Waka Kurikulum"><i class="fa-solid {{ $ico($cls($tahapKur, 'kur')) }}"></i></span>
</div>