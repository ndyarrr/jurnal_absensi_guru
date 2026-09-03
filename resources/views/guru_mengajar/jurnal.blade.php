@extends('layouts.guru_mengajar')

@section('title', 'Jurnal Harian')
@section('page-title', 'Jurnal Harian')
@section('page-subtitle', 'Isi & kelola jurnal mengajar serta presensi siswa')

@section('content')

    <div class="gm-card">
        <div class="gm-card-header">
            <h3 class="gm-card-title"><i class="fa-solid fa-list-check" style="color: var(--dash-navy);"></i> Riwayat Jurnal Mengajar Saya</h3>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="button" class="gm-btn gm-btn-navy" onclick="openPilihJadwalModal()">
                    <i class="fa-solid fa-plus"></i> Isi Jurnal Baru
                </button>
                <a href="{{ route('guru-mengajar.export-csv', request()->query()) }}" class="gm-btn gm-btn-tan">
                    <i class="fa-solid fa-file-csv"></i> Ekspor CSV
                </a>
            </div>
        </div>

        <div class="gm-card-body">
            <!-- Filter Controls -->
            <form method="GET" action="{{ route('guru-mengajar.jurnal') }}" class="gm-filter-bar" style="margin-bottom: 20px;">
                <input type="text" name="search" class="gm-input" placeholder="Cari materi / mapel..." value="{{ request('search') }}" style="min-width: 220px;">
                <input type="date" name="tanggal" class="gm-input" value="{{ request('tanggal') }}">

                <select name="id_kelas" class="gm-select">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($allKelas as $k)
                        <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->tingkat }} {{ optional($k->jurusan)->kode_jurusan }} {{ $k->rombel }}
                        </option>
                    @endforeach
                </select>

                <select name="id_mapel" class="gm-select">
                    <option value="">-- Semua Mapel --</option>
                    @foreach($allMapel as $m)
                        <option value="{{ $m->id_mapel }}" {{ request('id_mapel') == $m->id_mapel ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="gm-btn gm-btn-navy"><i class="fa-solid fa-filter"></i> Filter</button>
                <a href="{{ route('guru-mengajar.jurnal') }}" class="gm-btn gm-btn-outline"><i class="fa-solid fa-rotate-left"></i> Reset</a>
            </form>

            <!-- Table -->
            <div class="gm-table-responsive">
                <table class="gm-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Materi Pembelajaran</th>
                            <th>Presensi Siswa</th>
                            <th>Catatan KBM</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jurnalHistory as $jurnal)
                            @php
                                $kelasObj = optional($jurnal->jadwal)->kelas;
                                $kelasStr = $kelasObj ? ($kelasObj->tingkat . ' ' . optional($kelasObj->jurusan)->kode_jurusan . ' ' . $kelasObj->rombel) : '-';
                                $mapelStr = optional(optional($jurnal->jadwal)->mapel)->nama_mapel ?? '-';
                            @endphp
                            <tr>
                                <td>
                                    <div style="font-weight: 800; color: var(--dash-navy);">{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d M Y') }}</div>
                                    <div style="font-size: 0.775rem; color: var(--dash-text-muted);">{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('l') }}</div>
                                </td>
                                <td><span style="font-weight: 700; color: var(--dash-navy);">{{ $kelasStr }}</span></td>
                                <td><span style="font-weight: 700; color: var(--dash-navy);">{{ $mapelStr }}</span></td>
                                <td><div style="max-width: 250px; white-space: normal; line-height: 1.4;">{{ $jurnal->materi ?? '-' }}</div></td>
                                <td>
                                    <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                                        <span class="gm-status-badge filled">Hadir: {{ $jurnal->jumlah_hadir ?? 0 }}</span>
                                        @if(($jurnal->jumlah_tidak_hadir ?? 0) > 0)
                                            <span style="background: #fee2e2; color: #dc2626; padding: 3px 10px; border-radius: 20px; font-weight: 800; font-size: 0.7rem;">
                                                Absen: {{ $jurnal->jumlah_tidak_hadir }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td><div style="max-width: 200px; font-size: 0.8rem; color: var(--dash-text-muted);">{{ $jurnal->catatan ? \Illuminate\Support\Str::limit($jurnal->catatan, 50) : '-' }}</div></td>
                                <td>
                                    <button type="button" class="gm-btn gm-btn-outline" style="padding: 6px 12px; font-size: 0.8rem;" onclick="openInputJurnalModal({{ optional($jurnal->jadwal)->id_jadwal ?? 0 }}, '{{ $jurnal->tanggal }}')">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="gm-empty-state">
                                        <div class="gm-empty-icon"><i class="fa-regular fa-folder-open"></i></div>
                                        <div class="gm-empty-title">Belum Ada Riwayat Jurnal</div>
                                        <p>Jurnal yang Anda isi akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($jurnalHistory->hasPages())
                <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                    {{ $jurnalHistory->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL: PILIH JADWAL (untuk isi jurnal baru) -->
    <div class="gm-modal-overlay" id="pilihJadwalModal">
        <div class="gm-modal" style="max-width: 460px;">
            <div class="gm-modal-header">
                <h3 class="gm-modal-title"><i class="fa-solid fa-calendar-check"></i> Pilih Jadwal Mengajar</h3>
                <button type="button" class="gm-modal-close" onclick="closePilihJadwalModal()">&times;</button>
            </div>
            <div class="gm-modal-body">
                <div class="gm-form-group">
                    <label class="gm-form-label">Tanggal Pelaksanaan KBM</label>
                    <input type="date" id="pilih_tanggal" class="gm-input" style="width: 100%;" value="{{ \Carbon\Carbon::now('Asia/Jakarta')->toDateString() }}">
                </div>
                <div class="gm-form-group" style="margin-bottom: 0;">
                    <label class="gm-form-label">Jadwal Mengajar</label>
                    <select id="pilih_jadwal" class="gm-select" style="width: 100%;">
                        <option value="">-- Pilih Kelas & Mapel --</option>
                        @foreach($jadwalList as $j)
                            <option value="{{ $j->id_jadwal }}">
                                {{ $j->hari }} · {{ $j->kelas ? ($j->kelas->tingkat . ' ' . optional($j->kelas->jurusan)->kode_jurusan . ' ' . $j->kelas->rombel) : '-' }} · {{ optional($j->mapel)->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="gm-modal-footer">
                <button type="button" class="gm-btn gm-btn-outline" onclick="closePilihJadwalModal()">Batal</button>
                <button type="button" class="gm-btn gm-btn-navy" onclick="lanjutkanPilihJadwal()">Lanjutkan <i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>
    </div>

    <!-- MODAL: INPUT JURNAL & PRESENSI SISWA -->
    <div class="gm-modal-overlay" id="jurnalModal">
        <div class="gm-modal">
            <div class="gm-modal-header">
                <h3 class="gm-modal-title" id="modalJurnalTitle"><i class="fa-solid fa-pen-to-square"></i> Input Jurnal Mengajar & Presensi Siswa</h3>
                <button type="button" class="gm-modal-close" onclick="closeJurnalModal()">&times;</button>
            </div>

            <form action="{{ route('guru-mengajar.jurnal.store') }}" method="POST" id="jurnalForm">
                @csrf
                <input type="hidden" name="id_jadwal" id="form_id_jadwal" value="">

                <div class="gm-modal-body">
                    <div style="background: var(--dash-cream-light); border: 1px solid var(--dash-cream-border); border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px;">
                        <div>
                            <span style="font-size: 0.775rem; color: var(--dash-text-muted); font-weight: 600;">Kelas</span>
                            <div style="font-weight: 800; color: var(--dash-navy);" id="modal_info_kelas">-</div>
                        </div>
                        <div>
                            <span style="font-size: 0.775rem; color: var(--dash-text-muted); font-weight: 600;">Mata Pelajaran</span>
                            <div style="font-weight: 800; color: var(--dash-navy);" id="modal_info_mapel">-</div>
                        </div>
                        <div>
                            <span style="font-size: 0.775rem; color: var(--dash-text-muted); font-weight: 600;">Waktu & Jam</span>
                            <div style="font-weight: 800; color: var(--dash-navy);" id="modal_info_jam">-</div>
                        </div>
                    </div>

                    <div class="gm-form-group">
                        <label class="gm-form-label">Tanggal Pelaksanaan KBM</label>
                        <input type="date" name="tanggal" id="form_tanggal" class="gm-input" style="width: 100%;" required>
                    </div>

                    <div class="gm-form-group">
                        <label class="gm-form-label">Materi / Pokok Bahasan <span style="color: #dc2626;">*</span></label>
                        <textarea name="materi" id="form_materi" class="gm-textarea" placeholder="Tuliskan topik / materi KBM hari ini..." required></textarea>
                    </div>

                    <div class="gm-form-group">
                        <label class="gm-form-label">Catatan KBM / Evaluasi Singkat</label>
                        <textarea name="catatan" id="form_catatan" class="gm-textarea" placeholder="Catatan dinamika kelas, kendala, atau tugas siswa (opsional)..." style="min-height: 70px;"></textarea>
                    </div>

                    <div style="margin-top: 24px; border-top: 1.5px dashed var(--dash-cream-border); padding-top: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
                            <label class="gm-form-label" style="margin: 0; font-size: 1rem;">
                                <i class="fa-solid fa-clipboard-user" style="color: var(--dash-navy);"></i> Presensi Kehadiran Siswa
                            </label>
                            <button type="button" class="gm-btn gm-btn-tan" style="padding: 4px 12px; font-size: 0.775rem;" onclick="markAllHadir()">
                                <i class="fa-solid fa-check-double"></i> Tandai Semua Hadir
                            </button>
                        </div>

                        <div class="gm-table-responsive" style="max-height: 280px; overflow-y: auto; border: 1px solid var(--dash-cream-border); border-radius: 12px;">
                            <table class="gm-attendance-table">
                                <thead style="position: sticky; top: 0; background: var(--dash-navy); color: #fff; z-index: 10;">
                                    <tr>
                                        <th style="width: 40px; text-align: center;">#</th>
                                        <th>Nama Siswa</th>
                                        <th style="width: 220px; text-align: center;">Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody id="siswaAttendanceRows"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="gm-modal-footer">
                    <button type="button" class="gm-btn gm-btn-outline" onclick="closeJurnalModal()">Batal</button>
                    <button type="submit" class="gm-btn gm-btn-navy"><i class="fa-solid fa-floppy-disk"></i> Simpan Jurnal & Presensi</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openPilihJadwalModal() {
        document.getElementById('pilihJadwalModal').classList.add('active');
    }
    function closePilihJadwalModal() {
        document.getElementById('pilihJadwalModal').classList.remove('active');
    }
    function lanjutkanPilihJadwal() {
        const idJadwal = document.getElementById('pilih_jadwal').value;
        const tanggal = document.getElementById('pilih_tanggal').value;
        if (!idJadwal) {
            alert('Pilih jadwal mengajar terlebih dahulu.');
            return;
        }
        closePilihJadwalModal();
        openInputJurnalModal(idJadwal, tanggal);
    }

    function openInputJurnalModal(idJadwal, tanggal) {
        if (!idJadwal) return;
        tanggal = tanggal || '{{ \Carbon\Carbon::now("Asia/Jakarta")->toDateString() }}';

        const modal = document.getElementById('jurnalModal');
        document.getElementById('form_id_jadwal').value = idJadwal;
        document.getElementById('form_tanggal').value = tanggal;

        const rowsContainer = document.getElementById('siswaAttendanceRows');
        rowsContainer.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px;"><i class="fa-solid fa-spinner fa-spin"></i> Memuat data siswa...</td></tr>';

        modal.classList.add('active');

        fetch(`/guru-mengajar/jadwal/${idJadwal}/siswa?tanggal=${tanggal}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('modal_info_kelas').textContent = data.jadwal.kelas;
                document.getElementById('modal_info_mapel').textContent = data.jadwal.mapel;
                document.getElementById('modal_info_jam').textContent = data.jadwal.waktu + ' (' + data.jadwal.jam + ')';

                if (data.jurnal) {
                    document.getElementById('form_materi').value = data.jurnal.materi || '';
                    document.getElementById('form_catatan').value = data.jurnal.catatan || '';
                } else {
                    document.getElementById('form_materi').value = '';
                    document.getElementById('form_catatan').value = '';
                }

                rowsContainer.innerHTML = '';
                if (!data.siswa || data.siswa.length === 0) {
                    rowsContainer.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px;">Belum ada data siswa di kelas ini.</td></tr>';
                    return;
                }

                const existingDetails = {};
                if (data.jurnal && data.jurnal.detail_ketidakhadiran) {
                    const statusReverse = { S: 'Sakit', I: 'Izin', A: 'Alpa' };
                    data.jurnal.detail_ketidakhadiran.forEach(d => {
                        existingDetails[d.id_siswa] = { status: statusReverse[d.status] || 'Alpa', keterangan: d.keterangan || '' };
                    });
                }

                data.siswa.forEach((s, idx) => {
                    const existing = existingDetails[s.id_siswa];
                    const statusVal = existing ? existing.status : 'Hadir';
                    const ketVal = existing ? existing.keterangan : '';

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td style="text-align: center; font-weight: 700;">${idx + 1}</td>
                        <td>
                            <div style="font-weight: 700; color: var(--dash-navy);">${s.nama_siswa}</div>
                            <div style="font-size: 0.75rem; color: var(--dash-text-muted);">NISN: ${s.nisn || '-'}</div>
                            <input type="hidden" name="presensi[${idx}][id_siswa]" value="${s.id_siswa}">
                        </td>
                        <td>
                            <div class="gm-radio-group" style="justify-content: center;">
                                <label class="gm-radio-label hadir"><input type="radio" name="presensi[${idx}][status]" value="Hadir" ${statusVal === 'Hadir' ? 'checked' : ''}> H</label>
                                <label class="gm-radio-label sakit"><input type="radio" name="presensi[${idx}][status]" value="Sakit" ${statusVal === 'Sakit' ? 'checked' : ''}> S</label>
                                <label class="gm-radio-label izin"><input type="radio" name="presensi[${idx}][status]" value="Izin" ${statusVal === 'Izin' ? 'checked' : ''}> I</label>
                                <label class="gm-radio-label alpa"><input type="radio" name="presensi[${idx}][status]" value="Alpa" ${statusVal === 'Alpa' ? 'checked' : ''}> A</label>
                            </div>
                        </td>
                        <td>
                            <input type="text" name="presensi[${idx}][keterangan]" class="gm-input" style="padding: 5px 8px; font-size: 0.8rem; width: 100%;" placeholder="Ket..." value="${ketVal}">
                        </td>
                    `;
                    rowsContainer.appendChild(tr);
                });
            })
            .catch(err => {
                console.error(err);
                rowsContainer.innerHTML = '<tr><td colspan="4" style="text-align: center; color: red; padding: 20px;">Gagal memuat data siswa.</td></tr>';
            });
    }

    function closeJurnalModal() {
        document.getElementById('jurnalModal').classList.remove('active');
    }

    function markAllHadir() {
        document.querySelectorAll('#siswaAttendanceRows input[type="radio"][value="Hadir"]').forEach(r => r.checked = true);
    }
</script>
@endpush