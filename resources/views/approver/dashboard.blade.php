<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Persetujuan - {{ auth()->user()->role_label }}</title>

    <!-- Font & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Modular Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
    <script src="/js/sidebar-toggle.js"></script>

    <style>
        :root {
            --app-bg: #f8fafc;
            --app-card: #ffffff;
            --app-border: #e5e7eb;
            --app-primary: #2563eb;
            --app-text: #111827;
            --app-subtext: #6b7280;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--app-bg); color: var(--app-text); min-height: 100vh; }

        .app-layout { display: flex; flex-direction: column; min-height: 100vh; }
        
        /* Top Navigation Header Bar */
        .app-header {
            background: #ffffff;
            color: #111827;
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--app-border);
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .app-brand { display: flex; align-items: center; gap: 12px; }
        .app-brand-icon {
            width: 32px; height: 32px;
            background: #111827; color: #ffffff;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 1.1rem; font-family: sans-serif;
        }
        .app-brand-title { font-size: 1rem; font-weight: 800; color: #111827; letter-spacing: 0.05em; text-transform: uppercase; }

        .main-container { max-width: 1350px; margin: 0 auto; width: 100%; padding: 24px 32px; }

        /* Welcome Card */
        .welcome-card {
            background: #ffffff;
            color: #111827;
            border: 1px solid var(--app-border);
            border-radius: 14px;
            padding: 20px 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .welcome-title { font-size: 1.15rem; font-weight: 800; margin-bottom: 4px; display: flex; align-items: center; gap: 8px; }
        .welcome-sub { font-size: 0.85rem; color: var(--app-subtext); line-height: 1.5; }

        /* Summary Stats Cards Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .stat-card {
            background: #ffffff;
            border: 1px solid var(--app-border);
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; flex-shrink: 0;
        }
        .stat-val { font-size: 1.65rem; font-weight: 800; color: var(--app-text); line-height: 1.1; }
        .stat-label { font-size: 0.675rem; font-weight: 800; color: var(--app-subtext); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 2px; }

        /* Main Content Container */
        .content-card {
            background: #ffffff;
            border: 1px solid var(--app-border);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            overflow: hidden;
        }

        /* Tabs Header */
        .tabs-header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--app-border);
            background: #ffffff;
            padding: 0 16px;
        }
        .tab-btn {
            padding: 14px 20px;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--app-subtext);
            text-decoration: none;
            border-bottom: 3px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .tab-btn:hover { color: var(--app-primary); }
        .tab-btn.active {
            color: var(--app-primary);
            border-bottom-color: var(--app-primary);
            background: #ffffff;
        }

        /* Filter Controls Bar */
        .filter-bar {
            padding: 14px 20px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid var(--app-border);
            background: #ffffff;
        }
        .filter-status-group { display: flex; align-items: center; gap: 6px; }
        .filter-chip {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
            color: var(--app-subtext);
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .filter-chip.active {
            background: #e2e8f0;
            color: #111827;
            border-color: #cbd5e1;
        }

        .search-container { position: relative; display: flex; align-items: center; }
        .search-input {
            padding: 7px 34px 7px 14px;
            border-radius: 8px;
            border: 1px solid var(--app-border);
            font-size: 0.85rem;
            width: 230px;
            outline: none;
        }
        .search-input:focus { border-color: var(--app-primary); }
        .search-icon-inside {
            position: absolute;
            right: 12px;
            color: #9ca3af;
            font-size: 0.85rem;
            pointer-events: none;
        }

        /* Data Table Styling */
        .table-responsive { width: 100%; overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem; }
        .data-table th {
            background: #f9fafb;
            padding: 12px 16px;
            font-size: 0.725rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #4b5563;
            border-bottom: 1px solid var(--app-border);
        }
        .data-table td { padding: 14px 16px; border-bottom: 1px solid var(--app-border); vertical-align: middle; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover td { background: #f8fafc; }

        /* Approval Dot Track */
        .vote-section { display: flex; flex-direction: column; gap: 3px; min-width: 140px; }
        .vote-title { font-size: 0.65rem; font-weight: 800; color: #6b7280; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 2px; }
        .vote-item { display: flex; align-items: center; gap: 6px; font-size: 0.78rem; color: #374151; }
        .dot-icon { font-size: 0.5rem; line-height: 1; }
        .dot-green { color: #10b981; }
        .dot-red { color: #ef4444; }
        .dot-gray { color: #9ca3af; }

        /* Action Buttons */
        .btn-sm-approve {
            background: #10b981; color: #ffffff; border: none; padding: 6px 14px;
            border-radius: 6px; font-size: 0.8rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; gap: 4px; text-decoration: none;
            transition: background 0.2s ease;
        }
        .btn-sm-approve:hover { background: #059669; }
        .btn-sm-reject {
            background: #ef4444; color: #ffffff; border: none; padding: 6px 14px;
            border-radius: 6px; font-size: 0.8rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; gap: 4px; text-decoration: none;
            transition: background 0.2s ease;
        }
        .btn-sm-reject:hover { background: #dc2626; }
        .btn-sm-reset {
            background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 5px 10px;
            border-radius: 6px; font-size: 0.75rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; gap: 4px; text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-sm-reset:hover { background: #e2e8f0; color: #0f172a; }

        .pagination-container { padding: 16px 20px; border-top: 1px solid var(--app-border); }
    </style>
</head>
<body>

<div class="app-layout">
    <!-- Top Header Navigation -->
    <header class="app-header">
        <div class="app-brand">
            <img src="{{ asset('assets/image/logo/logo-only.svg') }}" alt="Logo" style="height: 32px; width: auto;">
            <div class="app-brand-title">MULTI-APPROVAL</div>
        </div>

        <div>
            @include('partials.dash-user-widget')
        </div>
    </header>

    <!-- Main Container -->
    <main class="main-container">
        <!-- Flash Alert Messages -->
        @if(session('success'))
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 18px; border-radius: 12px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-circle-check" style="font-size: 1.1rem;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 18px; border-radius: 12px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 1.1rem;"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Welcome Banner Card -->
        <div class="welcome-card">
            <h1 class="welcome-title">
                <span>Halo, {{ auth()->user()->name }}. Anda masuk sebagai {{ auth()->user()->role_label }}.</span>
            </h1>
            <p class="welcome-sub">
                Alur Persetujuan {{ $isKepsek ? 'Izin Guru (Guru Piket → Waka → Waka Kurikulum → Kepala Sekolah)' : 'Izin Guru (4 Pihak) & Dispensasi Siswa (3 Pihak: Guru Piket → Waka → Waka Kurikulum)' }}.
                Anda dapat menyetujui, menolak, atau membatalkan keputusan kapan saja.
            </p>
        </div>

        <!-- Summary Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fff7ed; color: #ea580c;">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $izinStats['pending'] + ($isKepsek ? 0 : $dispensasiStats['pending']) }}</div>
                    <div class="stat-label">MENUNGGU PERSETUJUAN</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $izinStats['total'] }}</div>
                    <div class="stat-label">TOTAL IZIN GURU</div>
                </div>
            </div>

            @if(!$isKepsek)
                <div class="stat-card">
                    <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <div class="stat-val">{{ $dispensasiStats['total'] }}</div>
                        <div class="stat-label">TOTAL DISPENSASI SISWA</div>
                    </div>
                </div>
            @endif

            <div class="stat-card">
                <div class="stat-icon" style="background: #faf5ff; color: #9333ea;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $izinStats['disetujui'] + ($isKepsek ? 0 : $dispensasiStats['disetujui']) }}</div>
                    <div class="stat-label">DISETUJUI LENGKAP</div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="content-card">
            <!-- Tabs Navigation Header -->
            <div class="tabs-header">
                <a href="{{ route('approver.dashboard', ['tab' => 'izin', 'status' => $statusFilter]) }}" class="tab-btn {{ $activeTab === 'izin' ? 'active' : '' }}">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <span>Izin Guru</span>
                    @if($izinStats['pending'] > 0)
                        <span style="background: #ef4444; color: #ffffff; font-size: 0.7rem; padding: 2px 7px; border-radius: 10px;">{{ $izinStats['pending'] }}</span>
                    @endif
                </a>

                @if(!$isKepsek)
                    <a href="{{ route('approver.dashboard', ['tab' => 'dispensasi', 'status' => $statusFilter]) }}" class="tab-btn {{ $activeTab === 'dispensasi' ? 'active' : '' }}">
                        <i class="fa-solid fa-receipt"></i>
                        <span>Dispensasi Siswa</span>
                        @if($dispensasiStats['pending'] > 0)
                            <span style="background: #ef4444; color: #ffffff; font-size: 0.7rem; padding: 2px 7px; border-radius: 10px;">{{ $dispensasiStats['pending'] }}</span>
                        @endif
                    </a>
                @endif
            </div>

            <!-- Filter Controls Bar -->
            <div class="filter-bar">
                <div class="filter-status-group">
                    <span style="font-size: 0.725rem; font-weight: 800; color: var(--app-subtext); margin-right: 4px;">STATUS:</span>
                    <a href="{{ route('approver.dashboard', ['tab' => $activeTab, 'status' => 'all', 'search' => $search]) }}" class="filter-chip {{ $statusFilter === 'all' ? 'active' : '' }}">Semua</a>
                    <a href="{{ route('approver.dashboard', ['tab' => $activeTab, 'status' => 'pending', 'search' => $search]) }}" class="filter-chip {{ $statusFilter === 'pending' ? 'active' : '' }}">Menunggu</a>
                    <a href="{{ route('approver.dashboard', ['tab' => $activeTab, 'status' => 'disetujui', 'search' => $search]) }}" class="filter-chip {{ $statusFilter === 'disetujui' ? 'active' : '' }}">Disetujui Lengkap</a>
                    <a href="{{ route('approver.dashboard', ['tab' => $activeTab, 'status' => 'ditolak', 'search' => $search]) }}" class="filter-chip {{ $statusFilter === 'ditolak' ? 'active' : '' }}">Ditolak</a>
                </div>

                <form action="{{ route('approver.dashboard') }}" method="GET" class="search-form">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                    <div class="search-container">
                        <input type="text" name="search" class="search-input" placeholder="Cari..." value="{{ $search }}">
                        <i class="fa-solid fa-magnifying-glass search-icon-inside"></i>
                    </div>
                </form>
            </div>

            <!-- TAB CONTENT: IZIN GURU -->
            @if($activeTab === 'izin')
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>GURU PEMOHON</th>
                                <th>JENIS & WAKTU IZIN</th>
                                <th>KETERANGAN / ALASAN</th>
                                <th>PERSETUJUAN (4 STAGE)</th>
                                <th style="text-align: right;">AKSI CEPAT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($izinList as $idx => $item)
                                @php
                                    $isWakaOk = ($item->status_waka === 'disetujui');
                                    $isWakaNo = ($item->status_waka === 'ditolak');
                                    $isWakaKurikulumOk = ($item->status_waka_kurikulum === 'disetujui');
                                    $isWakaKurikulumNo = ($item->status_waka_kurikulum === 'ditolak');
                                    $isKepsekOk = ($item->status_kepsek === 'disetujui');
                                    $isKepsekNo = ($item->status_kepsek === 'ditolak');

                                    $userRole = auth()->user()->role;
                                    $isMyWaka = ($userRole === 'waka');
                                    $isMyWakaKurikulum = ($userRole === 'waka_kurikulum');
                                    $isMyKepsek = ($userRole === 'kepala_sekolah');

                                    $myApproved = ($isMyWaka && $isWakaOk) || ($isMyWakaKurikulum && $isWakaKurikulumOk) || ($isMyKepsek && $isKepsekOk);
                                    $myRejected = ($isMyWaka && $isWakaNo) || ($isMyWakaKurikulum && $isWakaKurikulumNo) || ($isMyKepsek && $isKepsekNo);
                                @endphp
                                <tr>
                                    <td>{{ $izinList->firstItem() + $idx }}</td>
                                    <td>
                                        <div style="font-weight: 700; color: #111827;">{{ $item->guru->nama_guru ?? 'Guru' }}</div>
                                        <small style="color: var(--app-subtext); font-size: 0.75rem;">NIP: {{ $item->guru->nip ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #111827;">{{ $item->kategori_label }}</div>
                                        <small style="color: #64748b; font-size: 0.75rem;">
                                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}
                                            @if($item->tanggal_mulai != $item->tanggal_selesai)
                                                - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                                            @endif
                                        </small>
                                    </td>
                                    <td style="max-width: 220px;">
                                        <div style="font-size: 0.85rem; color: #374151;">{{ $item->alasan_izin }}</div>
                                    </td>

                                    <td>
                                        <div class="vote-section">
                                            <div class="vote-title">VOTE PERSETUJUAN</div>
                                            <div class="vote-item">
                                                <i class="fa-solid fa-circle dot-icon dot-green"></i>
                                                <span>Piket Setuju</span>
                                            </div>
                                            <div class="vote-item">
                                                <i class="fa-solid fa-circle dot-icon {{ $isWakaOk ? 'dot-green' : ($isWakaNo ? 'dot-red' : 'dot-gray') }}"></i>
                                                <span>Waka {{ $isWakaOk ? 'Setuju' : ($isWakaNo ? 'Tolak' : 'Menunggu') }}</span>
                                            </div>
                                            <div class="vote-item">
                                                <i class="fa-solid fa-circle dot-icon {{ $isWakaKurikulumOk ? 'dot-green' : ($isWakaKurikulumNo ? 'dot-red' : 'dot-gray') }}"></i>
                                                <span>Waka Kurikulum {{ $isWakaKurikulumOk ? 'Setuju' : ($isWakaKurikulumNo ? 'Tolak' : 'Menunggu') }}</span>
                                            </div>
                                            <div class="vote-item">
                                                <i class="fa-solid fa-circle dot-icon {{ $isKepsekOk ? 'dot-green' : ($isKepsekNo ? 'dot-red' : 'dot-gray') }}"></i>
                                                <span>Kepsek {{ $isKepsekOk ? 'Setuju' : ($isKepsekNo ? 'Tolak' : 'Menunggu') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align: right;">
                                        @if($myApproved)
                                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                                                <span style="font-size: 0.775rem; font-weight: 800; color: #16a34a; background: #f0fdf4; padding: 3px 8px; border-radius: 6px; border: 1px solid #bbf7d0;">
                                                    <i class="fa-solid fa-check-double"></i> Anda Sudah Setuju
                                                </span>
                                                <form action="{{ route('approver.izin.reset', $item->id_izin_guru) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-reset" onclick="return confirm('Batalkan / reset persetujuan Anda untuk permohonan ini?')">
                                                        <i class="fa-solid fa-rotate-left"></i> Batalkan / Reset
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($myRejected)
                                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                                                <span style="font-size: 0.775rem; font-weight: 800; color: #dc2626; background: #fef2f2; padding: 3px 8px; border-radius: 6px; border: 1px solid #fecaca;">
                                                    <i class="fa-solid fa-xmark"></i> Anda Menolak
                                                </span>
                                                <form action="{{ route('approver.izin.reset', $item->id_izin_guru) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-reset" onclick="return confirm('Batalkan / reset penolakan Anda untuk permohonan ini?')">
                                                        <i class="fa-solid fa-rotate-left"></i> Batalkan / Reset
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div style="display: inline-flex; gap: 6px;">
                                                <form action="{{ route('approver.izin.approve', $item->id_izin_guru) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-approve" onclick="return confirm('Setujui permohonan izin ini?')">
                                                        Setujui
                                                    </button>
                                                </form>

                                                <form action="{{ route('approver.izin.reject', $item->id_izin_guru) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-reject" onclick="return confirm('Tolak permohonan izin ini?')">
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--app-subtext);">
                                        <i class="fa-solid fa-folder-open" style="font-size: 2rem; margin-bottom: 8px; color: #cbd5e1; display: block;"></i>
                                        Tidak ada data permohonan izin guru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($izinList->hasPages())
                    <div class="pagination-container">
                        {{ $izinList->links() }}
                    </div>
                @endif

            <!-- TAB CONTENT: DISPENSASI SISWA (WAKA ONLY) -->
            @elseif(!$isKepsek)
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NO. SURAT</th>
                                <th>NAMA SISWA & KELAS</th>
                                <th>KEGIATAN</th>
                                <th>PERSETUJUAN (3 STAGE)</th>
                                <th style="text-align: right;">AKSI CEPAT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dispensasiList as $idx => $item)
                                @php
                                    $isWakaOk = ($item->status_waka === 'disetujui');
                                    $isWakaNo = ($item->status_waka === 'ditolak');
                                    $isWakaKurikulumOk = ($item->status_waka_kurikulum === 'disetujui');
                                    $isWakaKurikulumNo = ($item->status_waka_kurikulum === 'ditolak');

                                    $userRole = auth()->user()->role;
                                    $isMyWaka = ($userRole === 'waka');
                                    $isMyWakaKurikulum = ($userRole === 'waka_kurikulum');

                                    $myApproved = ($isMyWaka && $isWakaOk) || ($isMyWakaKurikulum && $isWakaKurikulumOk);
                                    $myRejected = ($isMyWaka && $isWakaNo) || ($isMyWakaKurikulum && $isWakaKurikulumNo);
                                @endphp
                                <tr>
                                    <td>{{ $dispensasiList->firstItem() + $idx }}</td>
                                    <td>
                                        <span style="font-weight: 700; font-family: monospace; color: var(--app-primary);">{{ $item->nomor_surat }}</span>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #111827;">{{ optional($item->siswa)->nama_siswa ?? 'Siswa' }}</div>
                                        <small style="color: var(--app-subtext); font-size: 0.75rem;">
                                            {{ optional(optional($item->siswa)->kelas)->tingkat }} 
                                            {{ optional(optional(optional($item->siswa)->kelas)->jurusan)->kode_jurusan }} 
                                            {{ optional(optional($item->siswa)->kelas)->rombel }}
                                        </small>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #111827;">{{ $item->nama_kegiatan }}</div>
                                        <small style="color: var(--app-subtext); font-size: 0.75rem;">
                                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}
                                            @if($item->jam_mulai && $item->jam_selesai)
                                                ({{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }})
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <div class="vote-section">
                                            <div class="vote-title">VOTE PERSETUJUAN</div>
                                            <div class="vote-item">
                                                <i class="fa-solid fa-circle dot-icon dot-green"></i>
                                                <span>Piket Setuju</span>
                                            </div>
                                            <div class="vote-item">
                                                <i class="fa-solid fa-circle dot-icon {{ $isWakaOk ? 'dot-green' : ($isWakaNo ? 'dot-red' : 'dot-gray') }}"></i>
                                                <span>Waka {{ $isWakaOk ? 'Setuju' : ($isWakaNo ? 'Tolak' : 'Menunggu') }}</span>
                                            </div>
                                            <div class="vote-item">
                                                <i class="fa-solid fa-circle dot-icon {{ $isWakaKurikulumOk ? 'dot-green' : ($isWakaKurikulumNo ? 'dot-red' : 'dot-gray') }}"></i>
                                                <span>Waka Kurikulum {{ $isWakaKurikulumOk ? 'Setuju' : ($isWakaKurikulumNo ? 'Tolak' : 'Menunggu') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align: right;">
                                        @if($myApproved)
                                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                                                <span style="font-size: 0.775rem; font-weight: 800; color: #16a34a; background: #f0fdf4; padding: 3px 8px; border-radius: 6px; border: 1px solid #bbf7d0;">
                                                    <i class="fa-solid fa-check-double"></i> Anda Sudah Setuju
                                                </span>
                                                <form action="{{ route('approver.dispensasi.reset', $item->id_dispen) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-reset" onclick="return confirm('Batalkan / reset persetujuan Anda untuk surat dispensasi ini?')">
                                                        <i class="fa-solid fa-rotate-left"></i> Batalkan / Reset
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($myRejected)
                                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                                                <span style="font-size: 0.775rem; font-weight: 800; color: #dc2626; background: #fef2f2; padding: 3px 8px; border-radius: 6px; border: 1px solid #fecaca;">
                                                    <i class="fa-solid fa-xmark"></i> Anda Menolak
                                                </span>
                                                <form action="{{ route('approver.dispensasi.reset', $item->id_dispen) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-reset" onclick="return confirm('Batalkan / reset penolakan Anda untuk surat dispensasi ini?')">
                                                        <i class="fa-solid fa-rotate-left"></i> Batalkan / Reset
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div style="display: inline-flex; gap: 6px;">
                                                <form action="{{ route('approver.dispensasi.approve', $item->id_dispen) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-approve" onclick="return confirm('Setujui surat dispensasi ini?')">
                                                        Setujui
                                                    </button>
                                                </form>

                                                <form action="{{ route('approver.dispensasi.reject', $item->id_dispen) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-reject" onclick="return confirm('Tolak surat dispensasi ini?')">
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--app-subtext);">
                                        <i class="fa-solid fa-folder-open" style="font-size: 2rem; margin-bottom: 8px; color: #cbd5e1; display: block;"></i>
                                        Tidak ada data surat dispensasi siswa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($dispensasiList->hasPages())
                    <div class="pagination-container">
                        {{ $dispensasiList->links() }}
                    </div>
                @endif
            @endif
        </div>
    </main>
</div>

</body>
</html>
