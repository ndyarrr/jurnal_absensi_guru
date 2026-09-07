@php
    $user = Auth::user();
@endphp

@if($user && !$user->isAdmin() && (in_array($user->role, ['guru', 'guru_mengajar', 'wali_kelas', 'guru_piket'], true) || session()->has('active_role')))
    @php
        $idGuru = $user->id_guru ?: optional($user->guru)->id_guru;
        if (!$idGuru && !empty($user->name)) {
            $matched = \App\Models\Guru::where('nama_guru', $user->name)->first();
            if ($matched) {
                $idGuru = $matched->id_guru;
            }
        }

        $kelasWali = $idGuru ? \App\Models\Kelas::where('id_guru_wali', $idGuru)->first() : null;
        $kelasStr = $kelasWali ? ($kelasWali->tingkat . ' ' . optional($kelasWali->jurusan)->kode_jurusan . ' ' . $kelasWali->rombel) : null;

        $piketHari = ($idGuru && \Illuminate\Support\Facades\Schema::hasTable('jadwal_piket'))
            ? \App\Models\JadwalPiket::where('id_guru', $idGuru)->pluck('hari')->toArray()
            : [];
        $hariStr = !empty($piketHari) ? implode(', ', $piketHari) : null;

        $availableRoles = [];

        // 1. Guru Mengajar (Mapel) - Always present for teachers
        $availableRoles[] = [
            'key' => 'guru_mengajar',
            'label' => 'Guru Mengajar (Mapel)',
            'icon' => 'fa-chalkboard-user',
        ];

        // 2. Wali Kelas - Only if explicitly assigned to a class in `kelas` table OR primary role is wali_kelas
        if ($kelasWali || $user->role === 'wali_kelas') {
            $availableRoles[] = [
                'key' => 'wali_kelas',
                'label' => 'Wali Kelas' . ($kelasStr ? ' (' . $kelasStr . ')' : ''),
                'icon' => 'fa-user-graduate',
            ];
        }

        // 3. Guru Piket - Only if explicitly assigned in `jadwal_piket` table OR primary role is guru_piket
        $isPiket = $idGuru && \Illuminate\Support\Facades\Schema::hasTable('jadwal_piket')
            ? \App\Models\JadwalPiket::where('id_guru', $idGuru)->exists()
            : false;

        if ($isPiket || $user->role === 'guru_piket') {
            $availableRoles[] = [
                'key' => 'guru_piket',
                'label' => 'Guru Piket' . ($hariStr ? ' (' . $hariStr . ')' : ''),
                'icon' => 'fa-shield-halved',
            ];
        }

        $activeRole = session('active_role', $user->role === 'guru' ? 'guru_mengajar' : $user->role);
    @endphp

    @if(count($availableRoles) > 1)
        <div class="dash-role-switcher-widget" style="margin: 12px 0; padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.6px; color: #475569; display: inline-flex; align-items: center; gap: 5px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20 7h-9"></path><path d="M14 4l3 3-3 3"></path><path d="M4 17h9"></path><path d="M10 20l-3-3 3-3"></path></svg>
                    <span>Switch Peran / Tampilan</span>
                </span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                @foreach($availableRoles as $r)
                    @php $isActive = ($activeRole === $r['key']); @endphp
                    <form action="{{ route('switch-role') }}" method="POST" style="margin: 0;">
                        @csrf
                        <input type="hidden" name="role" value="{{ $r['key'] }}">
                        <button type="submit" title="Beralih ke tampilan {{ $r['label'] }}"
                                style="width: 100%; text-align: left; padding: 8px 10px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: space-between; transition: all 0.2s ease; {{ $isActive ? 'background: #1e293b; color: #ffffff; border: 1px solid #0f172a;' : 'background: #ffffff; color: #334155; border: 1px solid #cbd5e1;' }}">
                            <span style="display: inline-flex; align-items: center; gap: 8px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <i class="fa-solid {{ $r['icon'] }}" style="{{ $isActive ? 'color: #60a5fa;' : 'color: #64748b;' }}"></i>
                                <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $r['label'] }}</span>
                            </span>
                            @if($isActive)
                                <span style="background: #2563eb; color: #ffffff; font-size: 0.65rem; padding: 2px 6px; border-radius: 6px; font-weight: 800; flex-shrink: 0;">Aktif</span>
                            @endif
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    @endif
@endif
