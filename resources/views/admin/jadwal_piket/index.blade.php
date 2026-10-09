<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Guru Piket - Admin Panel</title>

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Modular Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
    <script src="/js/sidebar-toggle.js"></script>

    <style>
        .piket-day-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .piket-day-header {
            padding: 16px 24px;
            background: linear-gradient(135deg, #1e2538, #0f172a);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .piket-day-title {
            font-size: 1.1rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .piket-day-body {
            padding: 20px;
        }

        .piket-teacher-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 10px;
            transition: all 0.2s ease;
        }

        .piket-teacher-item:hover {
            border-color: #cbd5e1;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .piket-avatar {
            width: 40px !important;
            height: 40px !important;
            min-width: 40px !important;
            min-height: 40px !important;
            max-width: 40px !important;
            max-height: 40px !important;
            aspect-ratio: 1 / 1 !important;
            border-radius: 50% !important;
            flex-shrink: 0 !important;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            font-weight: 800;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.85rem;
        }

        /* Modal styling */
        .pk-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
        }

        .pk-modal-content {
            background-color: #ffffff;
            border-radius: 20px;
            padding: 28px;
            max-width: 520px;
            width: 90%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            position: relative;
        }

        .pk-weekbar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
        .pk-nav-btn { background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 9px 14px; border-radius: 10px; font-weight: 700; font-size: 0.825rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .pk-nav-btn:hover { background: #f1f5f9; }
        .pk-week-label { text-align: center; padding: 0 8px; }
        .pk-week-range { font-weight: 800; color: #1e2538; font-size: 1rem; }
        .pk-week-today { font-size: 0.75rem; font-weight: 700; color: #2563eb; text-decoration: none; }
        .pk-import-btn { margin-left: auto; background: #16a34a; color: #fff; border: none; padding: 10px 16px; border-radius: 10px; font-weight: 800; font-size: 0.825rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(22,163,74,.25); }
        .pk-role { padding: 10px 0 6px; border-top: 1px dashed #e2e8f0; }
        .pk-role:first-child { border-top: none; padding-top: 0; }
        .pk-role-head { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
        .pk-role-name { font-size: 0.72rem; font-weight: 800; letter-spacing: .03em; text-transform: uppercase; padding: 3px 9px; border-radius: 8px; color: #fff; }
        .pk-role-jam { font-size: 0.7rem; color: #64748b; font-weight: 700; }
        .pk-role-add { margin-left: auto; background: #f1f5f9; border: 1px solid #cbd5e1; color: #334155; width: 26px; height: 26px; border-radius: 8px; font-weight: 800; cursor: pointer; line-height: 1; }
        .pk-role-add:hover { background: #e2e8f0; }
        .pk-role-empty { font-size: 0.78rem; color: #94a3b8; padding: 2px 4px 6px; }
        .pk-teacher-row { display: flex; align-items: center; justify-content: space-between; gap: 8px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 8px 12px; margin-bottom: 6px; }
        .pk-report { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; border-radius: 16px; padding: 16px 20px; margin-bottom: 24px; font-size: 0.85rem; }
        .pk-report ul { margin: 8px 0 0 18px; padding: 0; }
        .pk-report li { margin-bottom: 4px; }
    </style>
</head>
<body class="dashboard-body">

    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    @include('partials.dash-sidebar')

    <!-- Main Content Area -->
    <main class="dash-main">
        <header class="dash-top-bar">
            <div style="display: flex; align-items: center; gap: 12px;">
                <button type="button" class="dash-hamburger-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>

                <div>
                    <h1 class="dash-header-title" style="font-size: 1.65rem;">Jadwal Guru Piket</h1>
                    <p class="dash-header-subtitle">Penugasan Guru Piket per tanggal &amp; peran (Senin - Jumat)</p>
                </div>
            </div>

            <div class="dash-top-right">
                <div class="dash-date-widget">
                    <svg class="dash-date-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <div class="dash-date-info">
                        <span class="date-str" id="live_date_str">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span>
                        <span class="time-str" id="live_time_str">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }} WIB</span>
                    </div>
                </div>

                @include('partials.dash-user-widget')
            </div>
        </header>

        <!-- Navigasi Minggu -->
        <div class="pk-weekbar">
            <a href="{{ route('jadwal-piket.index', ['minggu' => $prevMinggu]) }}" class="pk-nav-btn"><i class="fa-solid fa-chevron-left"></i> Minggu sebelumnya</a>
            <div class="pk-week-label">
                <div class="pk-week-range">{{ $senin->translatedFormat('d M') }} &ndash; {{ $jumat->translatedFormat('d M Y') }}</div>
                <a href="{{ route('jadwal-piket.index') }}" class="pk-week-today">Ke minggu ini</a>
            </div>
            <a href="{{ route('jadwal-piket.index', ['minggu' => $nextMinggu]) }}" class="pk-nav-btn">Minggu berikutnya <i class="fa-solid fa-chevron-right"></i></a>
            <button type="button" class="pk-import-btn" onclick="openImportModal()"><i class="fa-solid fa-file-import"></i> Impor Lembar Piket</button>
            <a href="{{ route('jadwal-piket.export-csv', ['minggu' => $senin->toDateString()]) }}" class="pk-nav-btn" style="border-color:#16a34a;color:#16a34a;"><i class="fa-solid fa-file-csv"></i> Export CSV (minggu ini)</a>
            <a href="{{ route('jadwal-piket.export-pdf', ['shift' => 'pagi', 'minggu' => $senin->toDateString()]) }}"
   class="pk-nav-btn">PDF Piket Pagi</a>

<a href="{{ route('jadwal-piket.export-pdf', ['shift' => 'siang', 'minggu' => $senin->toDateString()]) }}"
   class="pk-nav-btn">PDF Piket Siang</a>

<a href="{{ route('jadwal-piket.export-pdf', ['shift' => 'waka', 'minggu' => $senin->toDateString()]) }}"
   class="pk-nav-btn">PDF Piket Waka</a>
            <a href="{{ route('jadwal-piket.export-csv', ['semua' => 1]) }}" class="pk-nav-btn"><i class="fa-solid fa-file-csv"></i> Export Semua</a>
        </div>

        <div style="margin-bottom: 20px;">
            <p style="font-size: 0.875rem; color: #64748b; font-weight: 600;">
                Jadwal piket diisi <strong>per tanggal</strong> dan <strong>per peran</strong> sesuai lembar piket sekolah. Hanya guru yang terdaftar bertugas pada tanggal berkenaan yang diizinkan menerbitkan dispensasi/surat izin. Klik <strong>+</strong> pada peran untuk menambah satu petugas, atau pakai <strong>Impor Lembar Piket</strong> untuk memasukkan banyak sekaligus. ({{ $totalPetugas }} penugasan di minggu ini)
            </p>
        </div>

        <!-- Flash Success / Error Notifications -->
        @if(session('success'))
            <div style="background-color: #dcfce7; border: 1px solid #bbf7d0; color: #15803d; padding: 16px 20px; border-radius: 16px; margin-bottom: 24px; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 12px;">
                <i class="fa-solid fa-circle-check" style="font-size: 1.2rem;"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 16px 20px; border-radius: 16px; margin-bottom: 24px; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 12px;">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 1.2rem;"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 16px 20px; border-radius: 16px; margin-bottom: 24px; font-weight: 700; font-size: 0.9rem;">
                @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
            </div>
        @endif

        @if(!empty($importReport) && !empty($importReport['errors']))
            <div class="pk-report">
                <strong>Rincian impor:</strong> {{ $importReport['tambah'] }} ditambahkan, {{ $importReport['sudah_ada'] }} sudah ada (dilewati), {{ count($importReport['errors']) }} baris gagal.
                <ul>
                    @foreach(array_slice($importReport['errors'], 0, 50) as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                    @if(count($importReport['errors']) > 50)
                        <li>&hellip; dan {{ count($importReport['errors']) - 50 }} baris lainnya.</li>
                    @endif
                </ul>
                <div style="margin-top: 8px;">Perbaiki baris yang gagal lalu impor ulang &mdash; data yang sudah masuk tidak akan dobel.</div>
            </div>
        @endif

        @php
            $warnaPeran = [
                'Petugas KBM Pagi'      => '#2563eb',
                'Koordinator KBM Pagi'  => '#1e40af',
                'Petugas KBM Siang'     => '#d97706',
                'Koordinator KBM Siang' => '#92400e',
                'Piket Waka'            => '#9333ea',
            ];
        @endphp

        <!-- 5 Hari (Senin - Jumat) untuk minggu terpilih -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 20px;">
            @foreach($hariList as $d)
                @php $isToday = $d['is_today']; @endphp

                <div class="piket-day-card" @if($isToday) style="border: 2px solid #2563eb; box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);" @endif>
                    <div class="piket-day-header" @if($isToday) style="background: linear-gradient(135deg, #2563eb, #1d4ed8);" @endif>
                        <div class="piket-day-title">
                            <i class="fa-solid fa-calendar-day"></i>
                            <span>{{ strtoupper($d['hari']) }} <span style="font-weight: 600; opacity: .85; font-size: .9rem;">&middot; {{ $d['tanggal']->translatedFormat('d M Y') }}</span></span>
                            @if($isToday)
                                <span style="background: #ffffff; color: #1d4ed8; padding: 2px 8px; border-radius: 10px; font-size: 0.7rem; font-weight: 800;">HARI INI</span>
                            @endif
                        </div>
                        <button type="button" onclick='openAssignModal(@json($d['tanggal']->toDateString()), @json($d['hari'] . ', ' . $d['tanggal']->translatedFormat('d F Y')), "")' style="background: rgba(255,255,255,0.2); border: none; color: #ffffff; padding: 5px 12px; border-radius: 8px; font-weight: 700; font-size: 0.775rem; cursor: pointer;">
                            + Tambah
                        </button>
                    </div>

                    <div class="piket-day-body">
                        @foreach($peranList as $peran => $jam)
                            @php $items = $d['per_peran'][$peran]; @endphp
                            <div class="pk-role">
                                <div class="pk-role-head">
                                    <span class="pk-role-name" style="background: {{ $warnaPeran[$peran] ?? '#475569' }};">{{ $peran }}</span>
                                    @if($jam)<span class="pk-role-jam">{{ $jam }}</span>@endif
                                    <button type="button" class="pk-role-add" title="Tambah {{ $peran }}" onclick='openAssignModal(@json($d['tanggal']->toDateString()), @json($d['hari'] . ', ' . $d['tanggal']->translatedFormat('d F Y')), @json($peran))'>+</button>
                                </div>
                                @forelse($items as $item)
                                    @include('admin.jadwal_piket.row', ['item' => $item])
                                @empty
                                    <div class="pk-role-empty">Belum ada petugas.</div>
                                @endforelse
                            </div>
                        @endforeach

                        @if($d['lainnya']->isNotEmpty())
                            <div class="pk-role">
                                <div class="pk-role-head">
                                    <span class="pk-role-name" style="background: #64748b;">Tanpa peran (jadwal lama)</span>
                                </div>
                                @foreach($d['lainnya'] as $item)
                                    @include('admin.jadwal_piket.row', ['item' => $item])
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Modal Penugasan Guru Piket -->
    <div id="assignModal" class="pk-modal" onclick="closeAssignModal()">
        <div class="pk-modal-content" onclick="event.stopPropagation()">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #1e2538;">
                    <i class="fa-solid fa-user-plus" style="color: #2563eb; margin-right: 8px;"></i>Penugasan Guru Piket
                </h3>
                <button type="button" onclick="closeAssignModal()" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: #64748b;">&times;</button>
            </div>

            <form action="{{ route('jadwal-piket.store') }}" method="POST"
                data-confirm-type="create"
                data-confirm-title="Konfirmasi Penugasan Guru Piket"
                data-confirm-message="Apakah kamu yakin ingin menyimpan penugasan guru piket ini?">
                @csrf
                <input type="hidden" name="tanggal" id="modal_input_tanggal" value="">

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #1e2538; margin-bottom: 6px;">Tanggal Tugas Piket</label>
                    <div id="modal_display_tanggal" style="font-size: 1rem; font-weight: 800; color: #2563eb; background: #eff6ff; padding: 12px 16px; border-radius: 12px; border: 1px solid #bfdbfe; display: flex; align-items: center; gap: 8px;"></div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #1e2538; margin-bottom: 6px;">Peran</label>
                    <select name="peran" id="modal_input_peran" required style="width: 100%; padding: 11px 14px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff;">
                        <option value="">-- Pilih peran --</option>
                        @foreach($peranList as $peran => $jam)
                            <option value="{{ $peran }}">{{ $peran }}@if($jam) ({{ $jam }})@endif</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #1e2538; margin-bottom: 6px;">Pilih Guru Piket</label>
                    <input type="hidden" name="id_guru" id="assign_id_guru" value="" required>
                    <div class="searchable-select" id="assign_piket_ss">
                        <input type="text" class="form-field-input ss-input" id="assign_piket_input" placeholder="Ketik untuk cari" autocomplete="off" onclick="openPiketDropdown()" onkeyup="filterPiketDropdown()" required>
                        <div class="ss-dropdown" id="assign_piket_dropdown">
                            @foreach($guruList as $g)
                                <div class="ss-option" data-value="{{ $g->id_guru }}" onclick='pickPiketGuru(@json((string) $g->id_guru), @json($g->nama_guru . " (NIP: " . ($g->nip ?? "-") . ")"))'>
                                    <strong>{{ $g->nama_guru }}</strong>
                                    <small style="color: #64748b;">NIP: {{ $g->nip ?? '-' }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #1e2538; margin-bottom: 6px;">Keterangan (Opsional)</label>
                    <input type="text" name="keterangan" class="form-field-input" style="width: 100%; padding: 11px 14px; border-radius: 10px; border: 1px solid #cbd5e1;" placeholder="Kosongkan untuk memakai nama peran">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" onclick="closeAssignModal()" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569; padding: 10px 18px; border-radius: 10px; font-weight: 700; cursor: pointer;">Batal</button>
                    <button type="submit" style="background: #2563eb; color: #ffffff; border: none; padding: 10px 22px; border-radius: 10px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);">Simpan Penugasan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Penugasan Guru Piket -->
    <div id="editPiketModal" class="pk-modal" onclick="closeEditPiketModal()">
        <div class="pk-modal-content" onclick="event.stopPropagation()">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #1e2538;">
                    <i class="fa-solid fa-pen-to-square" style="color: #2563eb; margin-right: 8px;"></i>Edit Penugasan Guru Piket
                </h3>
                <button type="button" onclick="closeEditPiketModal()" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: #64748b;">&times;</button>
            </div>

            <form id="editPiketForm" action="" method="POST"
                data-confirm-type="update" data-confirm-title="Konfirmasi Simpan Perubahan"
                data-confirm="Apakah Anda yakin ingin menyimpan perubahan penugasan piket ini?">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #1e2538; margin-bottom: 6px;">Tanggal Tugas Piket</label>
                    <div id="edit_piket_tanggal" style="font-size: 1rem; font-weight: 800; color: #2563eb; background: #eff6ff; padding: 12px 16px; border-radius: 12px; border: 1px solid #bfdbfe; display: flex; align-items: center; gap: 8px;"></div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #1e2538; margin-bottom: 6px;">Peran</label>
                    <select name="peran" id="edit_piket_peran" required style="width: 100%; padding: 11px 14px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff;">
                        <option value="">-- Pilih peran --</option>
                        @foreach($peranList as $peran => $jam)
                            <option value="{{ $peran }}">{{ $peran }}@if($jam) ({{ $jam }})@endif</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #1e2538; margin-bottom: 6px;">Pilih Guru Piket</label>
                    <input type="hidden" name="id_guru" id="edit_piket_id_guru" value="" required>
                    <div class="searchable-select" id="edit_piket_ss">
                        <input type="text" class="form-field-input ss-input" id="edit_piket_input" placeholder="Ketik untuk cari" autocomplete="off" onclick="openEditPiketDropdown()" onkeyup="filterEditPiketDropdown()" required>
                        <div class="ss-dropdown" id="edit_piket_dropdown">
                            @foreach($guruList as $g)
                                <div class="ss-option" data-value="{{ $g->id_guru }}" onclick='pickEditPiketGuru(@json((string) $g->id_guru), @json($g->nama_guru . " (NIP: " . ($g->nip ?? "-") . ")"))'>
                                    <strong>{{ $g->nama_guru }}</strong>
                                    <small style="color: #64748b;">NIP: {{ $g->nip ?? '-' }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #1e2538; margin-bottom: 6px;">Keterangan (Opsional)</label>
                    <input type="text" name="keterangan" id="edit_piket_keterangan" class="form-field-input" style="width: 100%; padding: 11px 14px; border-radius: 10px; border: 1px solid #cbd5e1;" placeholder="Kosongkan untuk memakai nama peran">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" onclick="closeEditPiketModal()" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569; padding: 10px 18px; border-radius: 10px; font-weight: 700; cursor: pointer;">Batal</button>
                    <button type="submit" style="background: #2563eb; color: #ffffff; border: none; padding: 10px 22px; border-radius: 10px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Impor Lembar Piket -->
    <div id="importModal" class="pk-modal" onclick="closeImportModal()">
        <div class="pk-modal-content" style="max-width: 680px; max-height: 90vh; overflow-y: auto;" onclick="event.stopPropagation()">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #1e2538;">
                    <i class="fa-solid fa-file-import" style="color: #16a34a; margin-right: 8px;"></i>Impor Lembar Piket
                </h3>
                <button type="button" onclick="closeImportModal()" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: #64748b;">&times;</button>
            </div>

            <p style="font-size: 0.82rem; color: #475569; margin-bottom: 10px;">
                Satu baris = satu petugas, dengan 3 kolom: <strong>tanggal</strong>, <strong>peran</strong>, <strong>nama guru</strong>. Pemisah kolom: <strong>Tab</strong> (tempel dari Excel/Sheets), <strong>;</strong> atau <strong>|</strong>. (Jangan pakai koma, karena gelar guru memakai koma.)
            </p>
<pre style="background: #0f172a; color: #e2e8f0; padding: 12px 14px; border-radius: 10px; font-size: 0.72rem; overflow-x: auto; margin-bottom: 10px;">28 September 2026 ; Petugas KBM Pagi ; Siti Khoiriyah, S.Pd
28 September 2026 ; Koordinator KBM Pagi ; Dwi Rini Manfaati, S.Pd
28 September 2026 ; Petugas KBM Siang ; Elysa Yuli Nur'aini, S.Si
28 September 2026 ; Koordinator KBM Siang ; Dwi Kuswanto, S.Pd
28 September 2026 ; Piket Waka ; Setiyo Winarko, S.Pd</pre>
            <ul style="font-size: 0.78rem; color: #64748b; margin: 0 0 14px 18px; padding: 0;">
                <li>Tanggal boleh <code>2026-09-28</code>, <code>28/09/2026</code>, atau <code>Senin, 28 September 2026</code>.</li>
                <li>Peran: Petugas/Koordinator KBM Pagi/Siang, atau Piket Waka.</li>
                <li>Guru harus sudah ada di <strong>Data Guru</strong> (gelar boleh berbeda sedikit). Yang belum ada akan dilaporkan.</li>
                <li>Aman diulang: penugasan yang sudah ada dilewati.</li>
            </ul>

            <form action="{{ route('jadwal-piket.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <textarea name="data" rows="9" style="width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-family: ui-monospace, Consolas, monospace; font-size: 0.78rem; margin-bottom: 12px;" placeholder="Tempel data di sini...">{{ old('data') }}</textarea>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #1e2538; margin-bottom: 6px;">Atau unggah file .csv / .txt (opsional)</label>
                    <input type="file" name="file_csv" accept=".csv,.txt">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" onclick="closeImportModal()" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569; padding: 10px 18px; border-radius: 10px; font-weight: 700; cursor: pointer;">Batal</button>
                    <button type="submit" style="background: #16a34a; color: #ffffff; border: none; padding: 10px 22px; border-radius: 10px; font-weight: 800; cursor: pointer;">Impor</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAssignModal(tanggalIso, label, peran) {
            document.getElementById('modal_input_tanggal').value = tanggalIso;
            document.getElementById('modal_display_tanggal').innerHTML = '<i class="fa-solid fa-calendar-day"></i> ' + label;
            document.getElementById('modal_input_peran').value = peran || '';
            document.getElementById('assignModal').style.display = 'flex';
            resetPiketDropdown();
        }

        function closeAssignModal() {
            document.getElementById('assignModal').style.display = 'none';
        }

        function openImportModal() { document.getElementById('importModal').style.display = 'flex'; }
        function closeImportModal() { document.getElementById('importModal').style.display = 'none'; }

        function resetPiketDropdown() {
            document.getElementById('assign_id_guru').value = '';
            document.getElementById('assign_piket_input').value = '';
            document.getElementById('assign_piket_dropdown').classList.remove('ss-open');
        }

        function openPiketDropdown() {
            const input = document.getElementById('assign_piket_input');
            const dd = document.getElementById('assign_piket_dropdown');
            const rect = input.getBoundingClientRect();
            const spaceBelow = window.innerHeight - rect.bottom;
            const spaceAbove = rect.top;
            const dropdownHeight = 220;

            dd.classList.remove('ss-up', 'ss-down');
            if (spaceBelow < dropdownHeight && spaceAbove > spaceBelow) {
                dd.classList.add('ss-up');
            } else {
                dd.classList.add('ss-down');
            }

            dd.classList.add('ss-open');
            filterPiketDropdown();
        }

        function filterPiketDropdown() {
            const query = document.getElementById('assign_piket_input').value.toLowerCase();
            const dd = document.getElementById('assign_piket_dropdown');
            const items = dd.querySelectorAll('.ss-option');
            items.forEach(item => {
                const txt = item.textContent.toLowerCase();
                item.style.display = txt.includes(query) ? 'flex' : 'none';
            });
            dd.classList.add('ss-open');
        }

        function pickPiketGuru(value, label) {
            document.getElementById('assign_id_guru').value = value;
            document.getElementById('assign_piket_input').value = value ? label : '';
            document.getElementById('assign_piket_dropdown').classList.remove('ss-open');
        }

        document.addEventListener('click', function(e) {
            const ss = document.getElementById('assign_piket_ss');
            const dd = document.getElementById('assign_piket_dropdown');
            if (ss && dd && !ss.contains(e.target)) {
                dd.classList.remove('ss-open');
            }
        }, true);

        const editPiketUrlTemplate = @json(route('jadwal-piket.update', '__ID__'));

        function openEditPiketModal(btn) {
            const d = btn.dataset;
            document.getElementById('editPiketForm').action = editPiketUrlTemplate.replace('__ID__', d.id);
            document.getElementById('edit_piket_tanggal').innerHTML = '<i class="fa-solid fa-calendar-day"></i> ' + d.label;
            document.getElementById('edit_piket_peran').value = d.peran || '';
            document.getElementById('edit_piket_id_guru').value = d.guruId || '';
            document.getElementById('edit_piket_input').value = d.guruId ? d.guruLabel : '';
            document.getElementById('edit_piket_keterangan').value = d.keterangan || '';
            document.getElementById('edit_piket_dropdown').classList.remove('ss-open');
            document.getElementById('editPiketModal').style.display = 'flex';
        }

        function closeEditPiketModal() {
            document.getElementById('editPiketModal').style.display = 'none';
        }

        function openEditPiketDropdown() {
            const input = document.getElementById('edit_piket_input');
            const dd = document.getElementById('edit_piket_dropdown');
            const rect = input.getBoundingClientRect();
            const spaceBelow = window.innerHeight - rect.bottom;
            const spaceAbove = rect.top;

            dd.classList.remove('ss-up', 'ss-down');
            dd.classList.add(spaceBelow < 220 && spaceAbove > spaceBelow ? 'ss-up' : 'ss-down');
            dd.classList.add('ss-open');
            filterEditPiketDropdown();
        }

        function filterEditPiketDropdown() {
            const query = document.getElementById('edit_piket_input').value.toLowerCase();
            const dd = document.getElementById('edit_piket_dropdown');
            dd.querySelectorAll('.ss-option').forEach(item => {
                item.style.display = item.textContent.toLowerCase().includes(query) ? 'flex' : 'none';
            });
            dd.classList.add('ss-open');
        }

        function pickEditPiketGuru(value, label) {
            document.getElementById('edit_piket_id_guru').value = value;
            document.getElementById('edit_piket_input').value = value ? label : '';
            document.getElementById('edit_piket_dropdown').classList.remove('ss-open');
        }

        document.addEventListener('click', function(e) {
            const ss = document.getElementById('edit_piket_ss');
            const dd = document.getElementById('edit_piket_dropdown');
            if (ss && dd && !ss.contains(e.target)) {
                dd.classList.remove('ss-open');
            }
        }, true);

        function updateLiveClock() {
            const timeEl = document.getElementById('live_time_str');
            if (!timeEl) return;
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            timeEl.textContent = `${hours}:${minutes}:${seconds} WIB`;
        }
        setInterval(updateLiveClock, 1000);
    </script>

</body>
</html>