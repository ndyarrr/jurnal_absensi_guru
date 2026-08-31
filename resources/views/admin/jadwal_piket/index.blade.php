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
                    <p class="dash-header-subtitle">Penugasan Harian Guru Piket (Senin - Jumat)</p>
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

        <!-- Subheader Info -->
        <div style="margin-bottom: 20px;">
            <p style="font-size: 0.875rem; color: #64748b; font-weight: 600;">
                Kelola petugas Guru Piket harian. Hanya guru yang terdaftar bertugas pada hari berkenaan yang diizinkan menerbitkan dispensasi/surat izin. Klik <strong>+ Tambah</strong> pada kartu hari untuk menambah penugasan.
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

        <!-- 5 Days Schedule Grid (Senin - Jumat) -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
            @foreach($days as $day)
                @php
                    $isToday = (\Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l') === $day);
                    $list = $jadwalGrouped[$day] ?? collect();
                @endphp

                <div class="piket-day-card" @if($isToday) style="border: 2px solid #2563eb; box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);" @endif>
                    <div class="piket-day-header" @if($isToday) style="background: linear-gradient(135deg, #2563eb, #1d4ed8);" @endif>
                        <div class="piket-day-title">
                            <i class="fa-solid fa-calendar-day"></i> HARI {{ strtoupper($day) }}
                            @if($isToday)
                                <span style="background: #ffffff; color: #1d4ed8; padding: 2px 8px; border-radius: 10px; font-size: 0.7rem; font-weight: 800;">HARI INI</span>
                            @endif
                        </div>
                        <button type="button" onclick="openAssignModal('{{ $day }}')" style="background: rgba(255,255,255,0.2); border: none; color: #ffffff; padding: 5px 12px; border-radius: 8px; font-weight: 700; font-size: 0.775rem; cursor: pointer; transition: background 0.2s;">
                            + Tambah
                        </button>
                    </div>

                    <div class="piket-day-body">
                        @forelse($list as $item)
                            <div class="piket-teacher-item">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div class="piket-avatar">
                                        {{ strtoupper(mb_substr(optional($item->guru)->nama_guru ?? 'G', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 800; font-size: 0.9rem; color: #1e2538;">{{ optional($item->guru)->nama_guru ?? 'Guru Piket' }}</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">
                                            NUPTK: {{ optional($item->guru)->nuptk ?? '-' }} . {{ $item->keterangan ?? 'Petugas Piket' }}
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('jadwal-piket.destroy', $item->id_piket) }}" method="POST" onsubmit="return confirm('Hapus penugasan piket ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 6px 10px; border-radius: 8px; cursor: pointer; font-size: 0.8rem;" title="Hapus Tugas">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div style="text-align: center; color: #94a3b8; padding: 24px 12px; font-size: 0.85rem;">
                                Belum ada Guru Piket terdaftar untuk hari {{ $day }}.
                            </div>
                        @endforelse
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

            <form action="{{ route('jadwal-piket.store') }}" method="POST">
                @csrf
                <input type="hidden" name="hari" id="modal_input_hari" value="Senin">

                <!-- Display Locked Target Day -->
                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #1e2538; margin-bottom: 6px;">Hari Tugas Piket</label>
                    <div id="modal_display_hari" style="font-size: 1rem; font-weight: 800; color: #2563eb; background: #eff6ff; padding: 12px 16px; border-radius: 12px; border: 1px solid #bfdbfe; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-calendar-day"></i> Hari: Senin
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #1e2538; margin-bottom: 6px;">Pilih Guru Piket</label>
                    <select name="id_guru" class="form-field-input" style="width: 100%; padding: 11px 14px; border-radius: 10px; border: 1px solid #cbd5e1;" required>
                        <option value="">-- Pilih Nama Guru --</option>
                        @foreach($guruList as $g)
                            <option value="{{ $g->id_guru }}">{{ $g->nama_guru }} (NUPTK: {{ $g->nuptk ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #1e2538; margin-bottom: 6px;">Keterangan / Pos Tugas (Opsional)</label>
                    <input type="text" name="keterangan" class="form-field-input" style="width: 100%; padding: 11px 14px; border-radius: 10px; border: 1px solid #cbd5e1;" placeholder="Contoh: Petugas Piket Utama Hari Ini">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" onclick="closeAssignModal()" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569; padding: 10px 18px; border-radius: 10px; font-weight: 700; cursor: pointer;">
                        Batal
                    </button>
                    <button type="submit" style="background: #2563eb; color: #ffffff; border: none; padding: 10px 22px; border-radius: 10px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);">
                        Simpan Penugasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAssignModal(day = 'Senin') {
            document.getElementById('modal_input_hari').value = day;
            document.getElementById('modal_display_hari').innerHTML = '<i class="fa-solid fa-calendar-day"></i> Hari Tugas: <strong>' + day + '</strong>';
            document.getElementById('assignModal').style.display = 'flex';
        }

        function closeAssignModal() {
            document.getElementById('assignModal').style.display = 'none';
        }

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
