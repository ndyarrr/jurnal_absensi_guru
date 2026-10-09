{{-- Kartu identitas anak + pemilih anak (jika punya lebih dari satu). Butuh: $anakList, $anak, $routeName --}}
@php
    $namaKelasAnak = optional($anak->kelas)->nama_lengkap ?: '-';
    $inisial = strtoupper(mb_substr(trim($anak->nama_siswa), 0, 1));
    $hubungan = optional($anak->pivot)->hubungan;
@endphp
<div class="ot-child-card">
    <div class="ot-child-main">
        <div class="ot-child-avatar">{{ $inisial }}</div>
        <div>
            <div class="ot-child-name">{{ $anak->nama_siswa }}</div>
            <div class="ot-child-meta">
                Kelas {{ $namaKelasAnak }} &middot; NISN {{ $anak->nisn ?: '-' }}
                @if($hubungan) &middot; Terdaftar sebagai {{ ucfirst($hubungan) }} @endif
            </div>
        </div>
    </div>

    @if($anakList->count() > 1)
        <div class="ot-tabs">
            @foreach($anakList as $a)
                <a href="{{ route($routeName, ['anak' => $a->id_siswa]) }}"
                   class="ot-tab {{ $a->id_siswa === $anak->id_siswa ? 'active' : '' }}">{{ strtok($a->nama_siswa, ' ') }}</a>
            @endforeach
        </div>
    @endif
</div>