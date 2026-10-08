@php
    // Piket selalu dianggap sudah setuju (izin baru masuk setelah piket meneruskan).
    $tahapWaka = $izin->status_waka;
    $tahapKur  = $izin->status_waka_kurikulum;
    $tahapKep  = $izin->status_kepsek;
    $ditolakSemua = $izin->status_approval === 'ditolak';

    // Tahap aktif = tahap pertama yang belum disetujui (jika belum ada yang menolak)
    $aktif = null;
    if (! $ditolakSemua) {
        if ($tahapWaka !== 'disetujui') { $aktif = 'waka'; }
        elseif ($tahapKur !== 'disetujui') { $aktif = 'kur'; }
        elseif ($tahapKep !== 'disetujui') { $aktif = 'kep'; }
    }
    $cls = function ($status, $key) use ($aktif) {
        if ($status === 'disetujui') return 'ok';
        if ($status === 'ditolak') return 'no';
        return $aktif === $key ? 'now' : '';
    };
    $ico = fn ($c) => $c === 'ok' ? 'fa-check' : ($c === 'no' ? 'fa-xmark' : 'fa-ellipsis');
@endphp
<div class="mini-steps" title="Piket → Waka → Waka Kurikulum → Kepala Sekolah">
    <span class="mini-step ok" title="Guru Piket"><i class="fa-solid fa-check"></i></span><span class="mini-line"></span>
    <span class="mini-step {{ $cls($tahapWaka, 'waka') }}" title="Waka"><i class="fa-solid {{ $ico($cls($tahapWaka, 'waka')) }}"></i></span><span class="mini-line"></span>
    <span class="mini-step {{ $cls($tahapKur, 'kur') }}" title="Waka Kurikulum"><i class="fa-solid {{ $ico($cls($tahapKur, 'kur')) }}"></i></span><span class="mini-line"></span>
    <span class="mini-step {{ $cls($tahapKep, 'kep') }}" title="Kepala Sekolah"><i class="fa-solid {{ $ico($cls($tahapKep, 'kep')) }}"></i></span>
</div>