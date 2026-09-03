<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengaturan & Notifikasi WhatsApp - Jurnal Absensi Guru</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS Modules -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
    <script src="/js/sidebar-toggle.js"></script>

    <style>
        body, button, input, select, textarea, table, th, td, h1, h2, h3, h4, h5, h6, span, p, a, label {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        }

        .wa-tab-nav {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            border-bottom: 2px solid var(--dash-cream-border, #e8e2d5);
            padding-bottom: 12px;
            flex-wrap: wrap;
        }
        .wa-tab-link {
            padding: 10px 18px;
            font-size: 0.88rem;
            font-weight: 700;
            border-radius: 12px;
            background: #ffffff;
            color: #64748b;
            border: 1px solid var(--dash-cream-border, #cbd5e1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .wa-tab-link:hover {
            color: var(--dash-navy);
            border-color: var(--dash-navy);
        }
        .wa-tab-link.active {
            background: var(--dash-navy);
            color: #ffffff;
            border-color: var(--dash-navy);
            box-shadow: 0 4px 12px rgba(35, 41, 59, 0.2);
        }
        .status-badge-custom {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
        }
        .status-connected { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .status-disconnected { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
        .status-qr_ready { background: #fef3c7; color: #b45309; border: 1px solid #fde047; }
        .status-offline { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }
        
        .card-box {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--dash-cream-border, #e2e8f0);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03);
            padding: 24px;
            margin-bottom: 24px;
        }
        .qr-code-img {
            max-width: 250px;
            border: 4px solid #f1f5f9;
            border-radius: 16px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.08);
        }
        .pairing-code-box {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: 6px;
            background: #f8fafc;
            color: var(--dash-navy);
            padding: 16px 28px;
            border-radius: 14px;
            border: 2px dashed #94a3b8;
            display: inline-block;
            margin: 16px 0;
            font-family: 'Plus Jakarta Sans', monospace !important;
        }
        .form-switch-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 12px;
        }
        .form-switch-input {
            width: 48px;
            height: 24px;
            cursor: pointer;
        }
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 16px;
        }
        .table-custom th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 2px solid #e2e8f0;
        }
        .table-custom td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #334155;
        }
        
        /* ==========================================================================
           WhatsApp Chat Bubble & Pill Filter Styling (Matching Uploaded Design)
           ========================================================================== */
        .wa-filter-pill-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .wa-filter-pill {
            padding: 8px 20px;
            font-size: 0.88rem;
            font-weight: 700;
            border-radius: 12px;
            background: #ffffff;
            color: #334155;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .wa-filter-pill:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        .wa-filter-pill.active {
            background: var(--dash-navy);
            color: #ffffff;
            border-color: var(--dash-navy);
            box-shadow: 0 4px 10px rgba(35, 41, 59, 0.2);
        }

        .wa-chat-canvas {
            background: #efeae2;
            background-image: radial-gradient(#d1d7db 1px, transparent 1px);
            background-size: 16px 16px;
            border-radius: 18px;
            padding: 24px;
            border: 1px solid #d1d7db;
            min-height: 350px;
        }

        .wa-chat-bubble-wrap {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
        }

        .wa-chat-bubble {
            background: #274e24;
            color: #ffffff;
            border-radius: 16px 0 16px 16px;
            padding: 18px 22px;
            max-width: 650px;
            width: 100%;
            position: relative;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
            line-height: 1.6;
        }

        .wa-bubble-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px dashed rgba(255,255,255,0.2);
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .wa-bubble-title {
            font-size: 1rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.3;
        }
        .wa-bubble-code {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
            font-family: monospace;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .wa-bubble-text {
            font-size: 0.92rem;
            white-space: pre-wrap;
            color: #f1f5f9;
            word-break: break-word;
        }

        /* ── Edit-mode contenteditable styles ─────────────────────────── */
        .bubble-pesan[contenteditable="true"] {
            border: 1px dashed rgba(255,255,255,0.4) !important;
            border-radius: 8px !important;
            padding: 10px 12px !important;
            cursor: text !important;
            min-height: 60px;
            outline: none;
        }
        .bubble-pesan[contenteditable="true"]:focus {
            outline: none;
        }
        .bubble-nama[contenteditable="true"],
        .bubble-kode[contenteditable="true"] {
            background: rgba(255,255,255,0.15);
            border-radius: 6px !important;
            cursor: text !important;
            outline: none;
            padding: 2px 6px;
        }
        /* var-tags-bar */
        .var-tags-bar button {
            background: rgba(255, 255, 255, 0.12) !important;
            color: #fef08a !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 14px !important;
            padding: 4px 10px !important;
            font-size: 0.72rem !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .var-tags-bar button:hover {
            background: rgba(255, 255, 255, 0.25) !important;
            border-color: rgba(255, 255, 255, 0.4) !important;
        }

        .wa-bubble-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 14px;
            padding-top: 10px;
            border-top: 1px dashed rgba(255,255,255,0.2);
            font-size: 0.8rem;
            color: rgba(255,255,255,0.8);
        }

        .wa-bubble-actions {
            display: flex;
            gap: 8px;
        }
        .wa-btn-act {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .wa-btn-act:hover {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }
        .wa-btn-act-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }
        .wa-btn-act-danger:hover {
            background: rgba(239, 68, 68, 0.4);
            color: #ffffff;
        }

        .tag-pill-var {
            color: #fde68a;
            font-weight: 800;
            font-size: 0.875rem;
        }
        .tag-pill-var-invalid {
            color: #fca5a5;
            background: rgba(239, 68, 68, 0.25);
            border-bottom: 2px dashed #ef4444;
            border-radius: 4px;
            padding: 1px 4px;
            font-weight: 800;
            font-size: 0.85rem;
        }
    </style>
</head>
<body class="dashboard-body">

    <div class="dash-layout">

        <!-- Left Sidebar Navigation -->
        @include('partials.dash-sidebar')

        <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Main Content Region -->
        <main class="dash-main">
            
            <!-- Top Header Bar -->
            <header class="dash-top-bar">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <button type="button" class="dash-hamburger-btn" onclick="toggleSidebar()" title="Menu">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <div>
                        <h1 class="dash-header-title">Pengaturan & Notifikasi WhatsApp</h1>
                        <p class="dash-header-subtitle">Konfigurasi status bot, pengingat 15 menit, template pesan, dan penerima</p>
                    </div>
                </div>

                <div class="dash-top-right">
                    <div class="dash-date-widget">
                        <svg class="dash-date-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <rect x="7" y="14" width="3" height="3" fill="currentColor"></rect>
                        </svg>
                        <div class="dash-date-info">
                            <span class="date-str" id="live_date_str">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span>
                            <span class="time-str" id="live_time_str">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }} WIB</span>
                        </div>
                    </div>

                    @include('partials.dash-user-widget')
                </div>
            </header>

            <div class="dash-content" style="padding: 24px;">
                @if(session('success'))
                    <div style="background: #dcfce7; color: #15803d; border: 1px solid #86efac; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div style="background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Navigation Tabs -->
                <div class="wa-tab-nav">
                    <a href="{{ route('pengaturan-wa.index', ['tab' => 'bot-status']) }}" class="wa-tab-link {{ $activeTab === 'bot-status' ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                        <span>Status Bot WA</span>
                    </a>
                    <a href="{{ route('pengaturan-wa.index', ['tab' => 'settings']) }}" class="wa-tab-link {{ $activeTab === 'settings' ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        <span>Pengaturan & Reminder</span>
                    </a>
                    <a href="{{ route('pengaturan-wa.index', ['tab' => 'templates']) }}" class="wa-tab-link {{ $activeTab === 'templates' ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        <span>Pesan Template</span>
                    </a>
                    <a href="{{ route('pengaturan-wa.index', ['tab' => 'recipients']) }}" class="wa-tab-link {{ $activeTab === 'recipients' ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Daftar Penerima</span>
                    </a>
                    <a href="{{ route('pengaturan-wa.index', ['tab' => 'test-send']) }}" class="wa-tab-link {{ $activeTab === 'test-send' ? 'active' : '' }}" style="margin-left: auto; background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        <span>Uji Coba Kirim WA</span>
                    </a>
                </div>

                <!-- TAB 1: STATUS KONEKSI BOT WA -->
                @if($activeTab === 'bot-status')
                    <div class="card-box">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">Status Sesi WhatsApp Bot</h3>
                                <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: #64748b;">Informasi koneksi Baileys WhatsApp Web Service</p>
                            </div>

                            <div id="statusBadgeContainer">
                                @if(($botInfo['status'] ?? '') === 'connected')
                                    <span class="status-badge-custom status-connected">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                        <span>Bot Terhubung</span>
                                    </span>
                                @elseif(($botInfo['status'] ?? '') === 'qr_ready')
                                    <span class="status-badge-custom status-qr_ready">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                        <span>Silakan Scan QR Code</span>
                                    </span>
                                @elseif(($botInfo['status'] ?? '') === 'connecting')
                                    <span class="status-badge-custom status-qr_ready">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line></svg>
                                        <span>Menghubungkan...</span>
                                    </span>
                                @else
                                    <span class="status-badge-custom status-offline">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                        <span>Bot Belum Terhubung / Offline</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; align-items: start;">
                            @php
                                $proc = $botInfo['process'] ?? [];
                                $procStatus = $proc['available'] ? ($proc['status'] ?? 'na') : 'na';
                                $procOnline = $procStatus === 'online';
                            @endphp
                            <!-- Proses Bot / PM2 Control -->
                            <div style="background: #ffffff; border-radius: 14px; padding: 20px; border: 1px solid #e2e8f0;">
                                <h4 style="margin-top: 0; color: #0f172a; font-size: 0.95rem; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                    <span>Kontrol Proses Bot (PM2)</span>
                                </h4>

                                <div style="display: flex; align-items: center; gap: 10px; margin: 14px 0 16px 0;">
                                    @if(!($proc['available'] ?? true))
                                        <span class="status-badge-custom status-offline">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                            <span>PM2 Tidak Terdeteksi</span>
                                        </span>
                                    @elseif($procStatus === 'online')
                                        <span class="status-badge-custom status-connected">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                            <span>Proses Berjalan (Online)</span>
                                        </span>
                                    @elseif($procStatus === 'stopped')
                                        <span class="status-badge-custom status-offline">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="6" y="6" width="12" height="12" rx="1"></rect></svg>
                                            <span>Proses Berhenti (Stopped)</span>
                                        </span>
                                    @elseif($procStatus === 'errored')
                                        <span class="status-badge-custom status-offline">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                            <span>Proses Error</span>
                                        </span>
                                    @elseif(!($proc['registered'] ?? false))
                                        <span class="status-badge-custom status-qr_ready">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
                                            <span>Belum Terdaftar di PM2</span>
                                        </span>
                                    @else
                                        <span class="status-badge-custom status-offline">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle></svg>
                                            <span>Status Tidak Diketahui</span>
                                        </span>
                                    @endif
                                </div>

                                <ul style="list-style: none; padding: 0; margin: 0 0 16px 0; font-size: 0.9rem; color: #475569;">
                                    <li style="margin-bottom: 10px; display: flex; justify-content: space-between;">
                                        <strong>PID Proses:</strong>
                                        <span>{{ $proc['pid'] ?? '-' }}</span>
                                    </li>
                                    <li style="margin-bottom: 10px; display: flex; justify-content: space-between;">
                                        <strong>Restart (PM2):</strong>
                                        <span>{{ isset($proc['restarts']) ? $proc['restarts'] . 'x' : '-' }}</span>
                                    </li>
                                    <li style="display: flex; justify-content: space-between;">
                                        <strong>Uptime Proses:</strong>
                                        <span>{{ $proc['uptimeHuman'] ?? '-' }}</span>
                                    </li>
                                </ul>

                                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                    <form action="{{ route('pengaturan-wa.start') }}" method="POST" style="margin: 0; display: inline-flex;">
                                        @csrf
                                        <button type="submit" {{ $procOnline ? 'disabled' : '' }} style="{{ $procOnline ? 'opacity: 0.5; cursor: not-allowed;' : '' }} background: #16a34a; color: white; border-radius: 10px; height: 42px; padding: 0 20px; font-weight: 700; font-size: 0.85rem; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.2s;">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                            <span style="line-height: 1;">Hidupkan Bot</span>
                                        </button>
                                    </form>

                                    <form action="{{ route('pengaturan-wa.stop') }}" method="POST" style="margin: 0; display: inline-flex;" onsubmit="return confirm('Matikan proses bot via PM2? Bot akan benar-benar berhenti (tidak auto-reconnect).')">
                                        @csrf
                                        <button type="submit" {{ !$procOnline ? 'disabled' : '' }} style="{{ !$procOnline ? 'opacity: 0.5; cursor: not-allowed;' : '' }} background: #dc2626; color: white; border-radius: 10px; height: 42px; padding: 0 20px; font-weight: 700; font-size: 0.85rem; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.2s;">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><rect x="6" y="6" width="12" height="12" rx="1"></rect></svg>
                                            <span style="line-height: 1;">Matikan Bot</span>
                                        </button>
                                    </form>
                                </div>

                                <p style="font-size: 0.78rem; color: #64748b; margin: 14px 0 0 0;">
                                    Silahkan gunakan tombol di atas untuk menghidupkan atau mematikan bot.
                                </p>
                            </div>

                            <!-- Bot Device / User Info -->
                            <div style="background: #f8fafc; border-radius: 14px; padding: 20px; border: 1px solid #e2e8f0;">
                                <h4 style="margin-top: 0; color: #334155; font-size: 0.95rem; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                                    <span>Informasi Perangkat Bot</span>
                                </h4>
                                <ul style="list-style: none; padding: 0; margin: 16px 0; font-size: 0.9rem; color: #475569;">
                                    <li style="margin-bottom: 10px; display: flex; justify-content: space-between;">
                                        <strong>Status Bot:</strong>
                                        <span id="botStatusText" style="text-transform: capitalize; font-weight: 700;">{{ $botInfo['status'] ?? 'Offline' }}</span>
                                    </li>
                                    <li style="margin-bottom: 10px; display: flex; justify-content: space-between;">
                                        <strong>Nomor Telepon:</strong>
                                        <span id="botPhoneText" style="font-weight: 700;">{{ $botInfo['user']['id'] ?? ($settings['bot_phone_number'] ?? 'Belum terhubung') }}</span>
                                    </li>
                                    <li style="margin-bottom: 10px; display: flex; justify-content: space-between;">
                                        <strong>Nama Sesi:</strong>
                                        <span id="botNameText">{{ $botInfo['user']['name'] ?? 'sijurnalsesion' }}</span>
                                    </li>
                                    <li style="display: flex; justify-content: space-between;">
                                        <strong>Uptime Node:</strong>
                                        <span>{{ isset($botInfo['uptimeSeconds']) ? floor($botInfo['uptimeSeconds'] / 60) . ' menit' : '-' }}</span>
                                    </li>
                                </ul>

                                <div style="display: flex; align-items: center; gap: 12px; margin-top: 20px; flex-wrap: wrap;">
                                    <form action="{{ route('pengaturan-wa.reconnect') }}" method="POST" style="margin: 0; display: inline-flex;">
                                        @csrf
                                        <button type="submit" style="background: #0284c7; color: white; border-radius: 10px; height: 40px; padding: 0 18px; font-weight: 700; font-size: 0.85rem; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.2s;">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                                            <span style="line-height: 1;">Muat Ulang Sesi</span>
                                        </button>
                                    </form>

                                    <form action="{{ route('pengaturan-wa.logout') }}" method="POST" style="margin: 0; display: inline-flex;" onsubmit="return confirm('Apakah Anda yakin ingin logout dari bot WA?')">
                                        @csrf
                                        <button type="submit" style="background: #ef4444; color: white; border-radius: 10px; height: 40px; padding: 0 18px; font-weight: 700; font-size: 0.85rem; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.2s;">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                            <span style="line-height: 1;">Logout & Hapus Sesi</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- QR Code & Pairing Area -->
                            <div style="text-align: center; background: #ffffff; border-radius: 14px; padding: 20px; border: 1px solid #e2e8f0;">
                                <h4 style="margin-top: 0; color: #334155; font-size: 0.95rem; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                    <span>Sambungkan WhatsApp</span>
                                </h4>

                                <div id="qrCodeContainer" style="margin: 16px 0;">
                                    @if(isset($botInfo['qrCode']) && $botInfo['qrCode'])
                                        <img src="{{ $botInfo['qrCode'] }}" class="qr-code-img" alt="Scan QR Code WA">
                                        <p style="font-size: 0.85rem; color: #64748b; margin-top: 10px;">
                                            Buka WhatsApp di Ponsel -> <b>Perangkat Tertaut</b> -> <b>Tautkan Perangkat</b> -> Scan QR Code di atas.
                                        </p>
                                    @elseif(($botInfo['status'] ?? '') === 'connected')
                                        <div style="padding: 30px; background: #f0fdf4; border-radius: 12px; border: 1px dashed #4ade80;">
                                            <svg width="48" height="48" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24" style="margin: 0 auto 12px auto; display: block;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                            <h4 style="color: #15803d; margin: 0 0 4px 0; font-size: 1.05rem; font-weight: 800;">Bot Terhubung Sempurna!</h4>
                                            <p style="font-size: 0.85rem; color: #166534; margin: 0;">Sistem siap mengirimkan notifikasi pengingat & presensi.</p>
                                        </div>
                                    @else
                                        <div style="padding: 24px; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                                            <svg width="40" height="40" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24" style="margin: 0 auto 10px auto; display: block;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                            <p style="font-size: 0.9rem; color: #64748b; margin-top: 10px;">
                                                Service Bot sedang offline atau sedang inisialisasi. Gunakan tombol <b>Hidupkan Bot</b> di kartu PM2, atau jalankan manual via terminal:
                                            </p>
                                            <code style="background: #1e293b; color: #38bdf8; padding: 6px 12px; border-radius: 6px; font-weight: 600; display: inline-block;">cd bot && npm start</code>
                                        </div>
                                    @endif
                                </div>

                                <hr style="margin: 20px 0; border: none; border-top: 1px solid #f1f5f9;">

                                <!-- Option: Pairing Code -->
                                <div>
                                    <h5 style="margin: 0 0 10px 0; color: #475569; font-size: 0.9rem; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 2l-2 2m-2-2l2 2m7 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"></path></svg>
                                        <span>Sambungkan dengan Kode Pairing (Alternatif)</span>
                                    </h5>
                                    <div style="display: flex; gap: 8px; justify-content: center; max-width: 380px; margin: 0 auto;">
                                        <input type="text" id="pairPhoneNumber" class="form-control" placeholder="Contoh: 628123456789" value="{{ $settings['bot_phone_number'] ?? '' }}" style="padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; width: 60%;">
                                        <button type="button" onclick="requestPairingCode()" style="background: var(--dash-navy); color: white; border-radius: 8px; padding: 8px 14px; font-weight: 700; font-size: 0.85rem; border: none; cursor: pointer;">
                                            Minta Kode
                                        </button>
                                    </div>

                                    <div id="pairingCodeDisplay" style="display: none;">
                                        <p style="font-size: 0.85rem; color: #475569; margin-top: 12px;">Kode Pairing WhatsApp Anda:</p>
                                        <div id="pairingCodeText" class="pairing-code-box">--------</div>
                                        <p style="font-size: 0.8rem; color: #64748b;">Masukkan kode 8 digit ini pada notifikasi tautkan perangkat di ponsel Anda.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- TAB 2: PENGATURAN NOTIFIKASI & REMINDER -->
                @if($activeTab === 'settings')
                    <div class="card-box">
                        <h3 style="margin-top: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            <span>Konfigurasi Notifikasi & Reminder</span>
                        </h3>
                        <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 24px;">Atur saklar utama notifikasi, interval pengingat jurnal mengajar, dan penerima pesan.</p>

                        <form action="{{ route('pengaturan-wa.settings.update') }}" method="POST">
                            @csrf

                            <div class="form-switch-label">
                                <div>
                                    <strong style="color: #1e293b; font-size: 0.95rem; display: block;">Aktifkan Layanan WhatsApp Notifikasi</strong>
                                    <span style="font-size: 0.85rem; color: #64748b;">Jika dinonaktifkan, seluruh pengiriman pesan otomatis WA akan dihentikan secara global.</span>
                                </div>
                                <input type="checkbox" name="wa_enabled" class="form-switch-input" value="1" {{ ($settings['wa_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                            </div>

                            <div class="form-switch-label">
                                <div>
                                    <strong style="color: #1e293b; font-size: 0.95rem; display: block;">Pengingat (Reminder) Jurnal Mengajar Guru</strong>
                                    <span style="font-size: 0.85rem; color: #64748b;">Kirim pesan pengingat ke WhatsApp Guru sebelum jam pelajaran berakhir agar langsung mengabsen & mengisi jurnal.</span>
                                </div>
                                <input type="checkbox" name="reminder_jurnal_enabled" class="form-switch-input" value="1" {{ ($settings['reminder_jurnal_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                            </div>

                            <div style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px; margin-bottom: 20px;">
                                <label style="font-weight: 700; color: #334155; display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    <span>Waktu Pengingat Sebelum Jam Selesai (Menit)</span>
                                </label>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <input type="number" name="reminder_before_minutes" class="form-control" value="{{ $settings['reminder_before_minutes'] ?? '15' }}" min="1" max="120" style="width: 120px; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; font-weight: 700; font-size: 1rem;">
                                    <span style="font-size: 0.9rem; color: #475569; font-weight: 600;">Menit sebelum jam pelajaran berakhir</span>
                                </div>
                                <p style="font-size: 0.8rem; color: #64748b; margin: 8px 0 0 0;">
                                    Sistem akan mengecek jadwal pelajaran aktif dan mengirim notifikasi tepat <b>{{ $settings['reminder_before_minutes'] ?? '15' }} menit</b> sebelum waktu selesai jam pelajaran.
                                </p>
                            </div>

                            <div style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px; margin-bottom: 24px;">
                                <label style="font-weight: 700; color: #334155; display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    <span>Target Penerima Notifikasi Laporan Absensi Piket</span>
                                </label>
                                @php
                                    $targetRoles = json_decode($settings['notification_target_roles'] ?? '[]', true) ?: ['admin', 'guru_piket'];
                                @endphp
                                <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 10px;">
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem; color: #334155; cursor: pointer;">
                                        <input type="checkbox" name="target_roles[]" value="admin" {{ in_array('admin', $targetRoles) ? 'checked' : '' }}> Administrator
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem; color: #334155; cursor: pointer;">
                                        <input type="checkbox" name="target_roles[]" value="guru_piket" {{ in_array('guru_piket', $targetRoles) ? 'checked' : '' }}> Guru Piket
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem; color: #334155; cursor: pointer;">
                                        <input type="checkbox" name="target_roles[]" value="wali_kelas" {{ in_array('wali_kelas', $targetRoles) ? 'checked' : '' }}> Wali Kelas
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem; color: #334155; cursor: pointer;">
                                        <input type="checkbox" name="target_roles[]" value="kepala_sekolah" {{ in_array('kepala_sekolah', $targetRoles) ? 'checked' : '' }}> Kepala Sekolah
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-svg" style="background: var(--dash-navy); color: white; padding: 12px 24px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                                <span>Simpan Perubahan Pengaturan</span>
                            </button>
                        </form>
                    </div>
                @endif

                <!-- TAB 3: PESAN TEMPLATE (WHATSAPP CHAT BUBBLE VIEW matching Uploaded Screenshot) -->
                @if($activeTab === 'templates')
                    <div class="card-box">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                    <span>Template Pesan WhatsApp</span>
                                </h3>
                                <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: #64748b;">Pratinjau & edit pesan otomatis WhatsApp per kategori dalam bentuk Gelembung Chat.</p>
                            </div>
                            <button type="button" onclick="showVarGuideModal()" class="btn-svg" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; border-radius: 10px; padding: 9px 16px; font-weight: 800; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                <span>Panduan Variabel</span>
                            </button>
                        </div>

                        <!-- Category Filter Pills (no "Semua") -->
                        <div class="wa-filter-pill-bar">
                            <button type="button" class="wa-filter-pill active" onclick="filterTemplates('reminder', this)">Reminder</button>
                            <button type="button" class="wa-filter-pill" onclick="filterTemplates('izin', this)">Izin</button>
                            <button type="button" class="wa-filter-pill" onclick="filterTemplates('dispensasi', this)">Dispensasi</button>
                            <button type="button" class="wa-filter-pill" onclick="filterTemplates('presensi', this)">Presensi</button>
                        </div>

                        <!-- WhatsApp Chat Canvas Background -->
                        <div class="wa-chat-canvas">
                            <div id="templatesChatList">
                                @forelse($templates as $tmpl)
                                    @php
                                        $allSystemVars = [
                                            '{nama_guru}','{nama_siswa}','{nama_kelas}','{jam_ke}','{mapel}','{alasan}',
                                            '{jenis_izin}','{nama_kegiatan}','{lokasi}','{nama_piket}','{tanggal}','{status}',
                                            '{waktu_selesai}','{sisa_menit}','{keterangan}'
                                        ];
                                    @endphp
                                    <div class="wa-chat-bubble-wrap template-item-card"
                                         data-category="{{ $tmpl->kategori }}"
                                         data-id="{{ $tmpl->id }}"
                                         data-kode="{{ $tmpl->kode }}"
                                         data-allowed-vars="{{ json_encode($allSystemVars) }}"
                                         style="display: flex; flex-direction: column; align-items: flex-end; margin-bottom: 24px;">

                                        {{-- Meta Header: Title & Code sit cleanly above the WhatsApp Chat Bubble --}}
                                        <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; max-width: 650px; margin-bottom: 8px; padding: 0 4px;">
                                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                                <span class="bubble-nama" id="nama-{{ $tmpl->id }}" data-original="{{ e($tmpl->nama) }}" style="font-weight: 800; font-size: 0.95rem; color: #0f172a; outline: none;">{{ $tmpl->nama }}</span>
                                                <span class="bubble-kode" id="kode-{{ $tmpl->id }}" data-original="{{ e($tmpl->kode) }}" contenteditable="false" style="font-size: 0.75rem; color: #64748b; font-family: monospace; font-weight: 600; background: #e2e8f0; padding: 2px 8px; border-radius: 6px; outline: none;">{{ $tmpl->kode }}</span>
                                            </div>
                                            <span class="bubble-kat-badge" id="kat-badge-{{ $tmpl->id }}" style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; background: #0f172a; color: #ffffff; padding: 3px 10px; border-radius: 12px; letter-spacing: 0.5px;">{{ $tmpl->kategori }}</span>
                                        </div>

                                        {{-- 100% Pure Solid WhatsApp Speech Bubble --}}
                                        <div class="wa-chat-bubble" id="bubble-{{ $tmpl->id }}">

                                            {{-- Pesan body (contenteditable) --}}
                                            <div class="wa-bubble-text bubble-pesan"
                                                 id="pesan-{{ $tmpl->id }}"
                                                 data-original="{{ e($tmpl->format_pesan) }}"
                                                 contenteditable="false"
                                                 oninput="checkVariablesInTemplate({{ $tmpl->id }})"
                                                 style="outline: none; white-space: pre-wrap; word-break: break-word;">@php
                                                $formatted = e($tmpl->format_pesan);
                                                $formatted = preg_replace('/\*([^\*]+)\*/', '<strong>$1</strong>', $formatted);
                                                // Format tags: check if valid for this template
                                                $formatted = preg_replace_callback('/\{([^\}]+)\}/', function($m) use ($allSystemVars) {
                                                    $tag = '{' . $m[1] . '}';
                                                    if (in_array($tag, $allSystemVars)) {
                                                        return '<span class="tag-pill-var">' . $tag . '</span>';
                                                    } else {
                                                        return '<span class="tag-pill-var-invalid" title="Variabel ' . $tag . ' tidak tersedia">' . $tag . '</span>';
                                                    }
                                                }, $formatted);
                                                echo $formatted;
                                            @endphp</div>

                                            {{-- Quick-insert var tags (edit mode only - all system variables) --}}
                                            <div class="var-tags-bar" id="var-tags-{{ $tmpl->id }}" style="display: none; flex-wrap: wrap; gap: 5px; margin: 10px 0 4px;">
                                                @foreach($allSystemVars as $var)
                                                    <button type="button"
                                                        onclick="insertVarAtCursor('{{ $var }}')"
                                                        title="Sisipkan {{ $var }}">{{ $var }}</button>
                                                @endforeach
                                            </div>

                                            {{-- Warning banner for invalid variables --}}
                                            <div class="va-warn-banner" id="warn-banner-{{ $tmpl->id }}" style="display: none; background: rgba(239,68,68,0.2); border: 1px solid rgba(239,68,68,0.4); border-radius: 8px; padding: 8px 12px; margin: 10px 0 4px; font-size: 0.78rem; color: #fca5a5; align-items: center; gap: 8px;">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                                <span id="warn-text-{{ $tmpl->id }}"></span>
                                            </div>



                                            {{-- Footer --}}
                                            <div class="wa-bubble-footer">
                                                <div style="display: flex; align-items: center; gap: 4px;">
                                                    <span>{{ \Carbon\Carbon::parse($tmpl->updated_at)->format('H:i') }}</span>
                                                    <svg width="16" height="16" fill="none" stroke="#4ade80" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline><polyline points="20 12 13 19"></polyline></svg>
                                                </div>

                                                {{-- VIEW mode actions --}}
                                                <div class="wa-bubble-actions view-actions" id="view-actions-{{ $tmpl->id }}">
                                                    <button type="button" onclick="startInlineEdit({{ $tmpl->id }})" class="wa-btn-act">
                                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                        <span>Edit</span>
                                                    </button>
                                                </div>

                                                {{-- EDIT mode actions --}}
                                                <div class="wa-bubble-actions edit-actions" id="edit-actions-{{ $tmpl->id }}" style="display: none;">
                                                    <button type="button" onclick="cancelInlineEdit({{ $tmpl->id }})" class="wa-btn-act">
                                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                        <span>Batal</span>
                                                    </button>
                                                    <button type="button" onclick="saveInlineEdit({{ $tmpl->id }})"
                                                        style="background: #ffffff; color: #1a3d18; border: none; border-radius: 8px; padding: 6px 14px; font-weight: 800; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; font-family: 'Plus Jakarta Sans', sans-serif;">
                                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                                                        Simpan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div style="text-align: center; color: #64748b; padding: 40px;">
                                        <p style="margin: 0; font-weight: 700;">Belum ada template pesan yang ditambahkan.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif

                <!-- TAB 4: DAFTAR PENERIMA KHUSUS -->
                @if($activeTab === 'recipients')
                    <div class="card-box">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    <span>Daftar Penerima Khusus Notifikasi</span>
                                </h3>
                                <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: #64748b;">Kontak pengawas / admin yang selalu menerima tembusan notifikasi sistem.</p>
                            </div>
                            <button type="button" onclick="showAddRecipientModal()" class="btn-svg" style="background: var(--dash-navy); color: white; border-radius: 10px; padding: 10px 18px; font-weight: 700; font-size: 0.85rem; border: none; cursor: pointer;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="17" y1="11" x2="23" y2="11"></line></svg>
                                <span>Tambah Penerima</span>
                            </button>
                        </div>

                        <div style="overflow-x: auto;">
                            <table class="table-custom">
                                <thead>
                                    <tr>
                                        <th>Nama Kontak</th>
                                        <th>Nomor WhatsApp</th>
                                        <th>Peran / Jabatan</th>
                                        <th>Terima Notifikasi</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recipients as $rec)
                                        <tr>
                                            <td style="font-weight: 700; color: #0f172a;">{{ $rec->nama }}</td>
                                            <td style="font-weight: 700; color: #0369a1;">{{ $rec->nomor_wa }}</td>
                                            <td><span style="background: #e2e8f0; color: #334155; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 0.8rem;">{{ $rec->peran }}</span></td>
                                            <td>
                                                @if($rec->terima_notifikasi)
                                                    <span class="status-badge-custom status-connected" style="font-size: 0.75rem; padding: 4px 10px;">Ya</span>
                                                @else
                                                    <span class="status-badge-custom status-offline" style="font-size: 0.75rem; padding: 4px 10px;">Tidak</span>
                                                @endif
                                            </td>
                                            <td style="color: #64748b; font-size: 0.85rem;">{{ $rec->catatan ?? '-' }}</td>
                                            <td>
                                                <button type="button" onclick="editRecipient({{ json_encode($rec) }})" style="background: #e2e8f0; color: #1e293b; border-radius: 6px; padding: 6px 12px; font-weight: 700; font-size: 0.8rem; border: none; cursor: pointer; margin-right: 4px;">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </button>
                                                <form action="{{ route('pengaturan-wa.recipients.destroy', $rec->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus penerima ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="background: #fee2e2; color: #dc2626; border-radius: 6px; padding: 6px 12px; font-weight: 700; font-size: 0.8rem; border: none; cursor: pointer;">
                                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" style="text-align: center; color: #94a3b8; padding: 30px;">Belum ada daftar penerima khusus.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- TAB 5: UJI COBA KIRIM PESAN -->
                @if($activeTab === 'test-send')
                    <div class="card-box" style="max-width: 650px; margin: 0 auto;">
                        <h3 style="margin-top: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                            <span>Uji Coba Kirim Pesan WhatsApp</span>
                        </h3>
                        <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 24px;">Gunakan form ini untuk menguji pengiriman pesan via bot WhatsApp yang sedang aktif.</p>

                        <form action="{{ route('pengaturan-wa.test-send') }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 16px;">
                                <label style="font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Nomor Tujuan (Diawali 62 atau 08):</label>
                                <input type="text" name="phone" class="form-control" required placeholder="Contoh: 628123456789" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid #cbd5e1; font-weight: 600;">
                            </div>

                            <div style="margin-bottom: 20px;">
                                <label style="font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Isi Pesan Uji Coba:</label>
                                <textarea name="message" rows="5" class="form-control" required placeholder="Tuliskan pesan uji coba di sini..." style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid #cbd5e1;">Testing Notifikasi Bot WA SiJurnal Absensi Guru. Pesan ini terkirim dengan sukses!</textarea>
                            </div>

                            <button type="submit" class="btn btn-svg" style="background: #2563eb; color: white; padding: 12px 24px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; width: 100%; justify-content: center;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                <span>Kirim Pesan Uji Coba Sekarang</span>
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </main>
    </div>

    <!-- Modal Template (With Live Real-Time WhatsApp Speech Bubble Preview!) -->
    <div id="templateModal" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 20px; overflow-y: auto;">
        <div style="background: white; border-radius: 20px; width: 100%; max-width: 900px; padding: 28px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 id="templateModalTitle" style="margin: 0; font-size: 1.2rem; font-weight: 800; color: #0f172a;">Form Editor Template Pesan WA</h3>
                <button type="button" onclick="closeTemplateModal()" style="background: none; border: none; color: #64748b; cursor: pointer;" title="Tutup">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <form id="templateForm" method="POST">
                @csrf
                <div id="templateMethodContainer"></div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; align-items: start;">
                    <!-- Left Column: Form Controls -->
                    <div>
                        <div style="margin-bottom: 14px;">
                            <label style="font-weight: 700; font-size: 0.85rem; color: #475569; display: block; margin-bottom: 4px;">Kode Template (unik):</label>
                            <input type="text" name="kode" id="tmplKode" class="form-control" required placeholder="Contoh: reminder_jurnal" style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #cbd5e1;">
                        </div>

                        <div style="margin-bottom: 14px;">
                            <label style="font-weight: 700; font-size: 0.85rem; color: #475569; display: block; margin-bottom: 4px;">Nama Template:</label>
                            <input type="text" name="nama" id="tmplNama" class="form-control" required placeholder="Contoh: Pengingat Jurnal Guru" oninput="updateLivePreview()" style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #cbd5e1;">
                        </div>

                        <div style="margin-bottom: 14px;">
                            <label style="font-weight: 700; font-size: 0.85rem; color: #475569; display: block; margin-bottom: 4px;">Kategori:</label>
                            <select name="kategori" id="tmplKategori" class="form-control" onchange="updateLivePreview()" style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #cbd5e1;">
                                <option value="reminder">Reminder Pengingat</option>
                                <option value="presensi">Presensi & Laporan Piket</option>
                                <option value="izin">Surat Izin Siswa / Guru</option>
                                <option value="dispensasi">Surat Dispensasi Siswa</option>
                                <option value="umum">Umum</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label style="font-weight: 700; font-size: 0.85rem; color: #475569; display: block; margin-bottom: 4px;">Format Pesan:</label>
                            <textarea name="format_pesan" id="tmplFormat" rows="6" class="form-control" required oninput="updateLivePreview()" style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #cbd5e1; line-height: 1.5;"></textarea>
                        </div>

                        <!-- Quick Insert Variable Tag Pills -->
                        <div style="margin-bottom: 16px;">
                            <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 6px;">Klik untuk menyisipkan variabel dinamis:</span>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                <span class="tag-pill" onclick="insertVariable('{nama_guru}')">{nama_guru}</span>
                                <span class="tag-pill" onclick="insertVariable('{nama_siswa}')">{nama_siswa}</span>
                                <span class="tag-pill" onclick="insertVariable('{nama_kelas}')">{nama_kelas}</span>
                                <span class="tag-pill" onclick="insertVariable('{jam_ke}')">{jam_ke}</span>
                                <span class="tag-pill" onclick="insertVariable('{mapel}')">{mapel}</span>
                                <span class="tag-pill" onclick="insertVariable('{alasan}')">{alasan}</span>
                                <span class="tag-pill" onclick="insertVariable('{jenis_izin}')">{jenis_izin}</span>
                                <span class="tag-pill" onclick="insertVariable('{nama_kegiatan}')">{nama_kegiatan}</span>
                                <span class="tag-pill" onclick="insertVariable('{nama_piket}')">{nama_piket}</span>
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem; color: #334155; cursor: pointer;">
                                <input type="checkbox" name="is_active" id="tmplActive" value="1" checked> Status Template Aktif
                            </label>
                        </div>
                    </div>

                    <!-- Right Column: Live WhatsApp Chat Bubble Preview (Matching Uploaded Screenshot!) -->
                    <div style="background: #efeae2; background-image: radial-gradient(#d1d7db 1px, transparent 1px); background-size: 16px 16px; border-radius: 16px; padding: 20px; border: 1px solid #d1d7db;">
                        <div style="font-size: 0.8rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <span>Live WhatsApp Chat Speech Bubble Preview</span>
                        </div>

                        <div class="wa-chat-bubble-wrap" style="margin-bottom: 0;">
                            <div class="wa-chat-bubble" style="max-width: 100%;">
                                <div class="wa-bubble-header">
                                    <div>
                                        <span id="previewModalTitle" class="wa-bubble-title">Preview Nama Template</span>
                                    </div>
                                    <span id="previewModalCategory" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 800; background: rgba(255,255,255,0.25); padding: 3px 10px; border-radius: 12px;">
                                        REMINDER
                                    </span>
                                </div>

                                <div id="previewModalText" class="wa-bubble-text">Preview format pesan akan muncul di sini secara real-time...</div>

                                <div class="wa-bubble-footer" style="justify-content: flex-end;">
                                    <div style="display: flex; align-items: center; gap: 4px;">
                                        <span>10:45</span>
                                        <svg width="16" height="16" fill="none" stroke="#4ade80" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline><polyline points="20 12 13 19"></polyline></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                    <button type="button" onclick="closeTemplateModal()" style="background: #e2e8f0; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; cursor: pointer;">Batal</button>
                    <button type="submit" style="background: var(--dash-navy); color: white; border: none; padding: 10px 24px; border-radius: 10px; font-weight: 700; cursor: pointer;">Simpan Template</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Recipient -->
    <div id="recipientModal" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: white; border-radius: 16px; width: 100%; max-width: 500px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
            <h3 id="recipientModalTitle" style="margin-top: 0; font-size: 1.1rem; color: #0f172a;">Tambah Penerima Khusus</h3>
            <form id="recipientForm" method="POST">
                @csrf
                <div id="recipientMethodContainer"></div>

                <div style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.85rem; color: #475569; display: block; margin-bottom: 4px;">Nama Lengkap:</label>
                    <input type="text" name="nama" id="recNama" class="form-control" required placeholder="Contoh: Bpk. Ahmad (Admin Piket)" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1;">
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.85rem; color: #475569; display: block; margin-bottom: 4px;">Nomor WhatsApp:</label>
                    <input type="text" name="nomor_wa" id="recNomor" class="form-control" required placeholder="Contoh: 628123456789" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1;">
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.85rem; color: #475569; display: block; margin-bottom: 4px;">Peran / Jabatan:</label>
                    <input type="text" name="peran" id="recPeran" class="form-control" required placeholder="Contoh: Admin / Guru Piket" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1;">
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="font-weight: 700; font-size: 0.85rem; color: #475569; display: block; margin-bottom: 4px;">Catatan (Opsional):</label>
                    <input type="text" name="catatan" id="recCatatan" class="form-control" placeholder="Contoh: Penerima rekap harian" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem; color: #334155; cursor: pointer;">
                        <input type="checkbox" name="terima_notifikasi" id="recTerima" value="1" checked> Terima Notifikasi
                    </label>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeRecipientModal()" style="background: #e2e8f0; border: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; cursor: pointer;">Batal</button>
                    <button type="submit" style="background: var(--dash-navy); color: white; border: none; padding: 8px 18px; border-radius: 8px; font-weight: 700; cursor: pointer;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: PANDUAN VARIABEL SYSTEM -->
    <div id="varGuideModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); z-index: 9999; justify-content: center; align-items: center; padding: 20px;">
        <div style="background: white; border-radius: 16px; width: 100%; max-width: 680px; max-height: 90vh; overflow-y: auto; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 12px;">
                <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <svg width="20" height="20" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    <span>Daftar Variabel Dinamis Sistem (15 Variabel)</span>
                </h3>
                <button type="button" onclick="closeVarGuideModal()" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: #64748b; line-height: 1;">&times;</button>
            </div>
            <p style="font-size: 0.85rem; color: #64748b; margin-top: 0; margin-bottom: 16px;">
                Variabel dinamis berikut akan otomatis digantikan dengan data riil dari sistem saat notifikasi dikirimkan ke WhatsApp:
            </p>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 10px; margin-bottom: 20px;">
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{nama_guru}</code> <span>Nama Guru Mengajar/Piket</span></div>
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{nama_siswa}</code> <span>Nama Siswa Izin/Dispen</span></div>
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{nama_kelas}</code> <span>Nama Kelas / Rombel</span></div>
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{jam_ke}</code> <span>Jam Pelajaran Ke-</span></div>
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{mapel}</code> <span>Mata Pelajaran</span></div>
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{alasan}</code> <span>Alasan Izin Siswa/Guru</span></div>
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{keterangan}</code> <span>Keterangan Tambahan</span></div>
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{jenis_izin}</code> <span>Jenis Izin (Sakit / Izin)</span></div>
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{nama_kegiatan}</code> <span>Nama Tugas / Dispensasi</span></div>
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{lokasi}</code> <span>Lokasi / Penyelenggara</span></div>
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{nama_piket}</code> <span>Nama Guru Piket</span></div>
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{tanggal}</code> <span>Tanggal Notifikasi</span></div>
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{status}</code> <span>Status Absensi</span></div>
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{waktu_selesai}</code> <span>Jam Selesai Pelajaran</span></div>
                <div style="font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"><code style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:6px; font-weight:800; font-family:monospace;">{sisa_menit}</code> <span>Sisa Menit Reminder</span></div>
            </div>
            <div style="display: flex; justify-content: flex-end;">
                <button type="button" onclick="closeVarGuideModal()" style="background: var(--dash-navy); color: white; border: none; padding: 8px 20px; border-radius: 8px; font-weight: 700; cursor: pointer;">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        function showVarGuideModal() {
            document.getElementById('varGuideModal').style.display = 'flex';
        }
        function closeVarGuideModal() {
            document.getElementById('varGuideModal').style.display = 'none';
        }
        // Live Date & Time Clock
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
            const timeEl = document.getElementById('live_time_str');
            if (timeEl) timeEl.innerText = timeStr;
        }
        setInterval(updateClock, 1000);

        // Filter WhatsApp Chat Bubbles by Category (no "all")
        function filterTemplates(cat, btn) {
            document.querySelectorAll('.wa-filter-pill').forEach(el => el.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.template-item-card').forEach(card => {
                card.style.display = (card.getAttribute('data-category') === cat) ? 'flex' : 'none';
            });
        }

        // On page load: show only Reminder bubbles
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.template-item-card').forEach(card => {
                if (card.getAttribute('data-category') !== 'reminder') {
                    card.style.display = 'none';
                }
            });
        });

        // Track which contenteditable is active so insertVarAtCursor works
        let activeEditEl = null;
        let savedRange = null;

        function startInlineEdit(id) {
            const namaEl   = document.getElementById('nama-' + id);
            const kodeEl   = document.getElementById('kode-' + id);
            const pesanEl  = document.getElementById('pesan-' + id);
            const varBar   = document.getElementById('var-tags-' + id);
            const viewAct  = document.getElementById('view-actions-' + id);
            const editAct  = document.getElementById('edit-actions-' + id);

            // Make editable — keep formatted HTML so variable highlights stay visible
            if (namaEl)  namaEl.contentEditable  = 'true';
            if (kodeEl)  kodeEl.contentEditable  = 'true';
            if (pesanEl) pesanEl.contentEditable = 'true';

            // Show variable pills + edit actions
            if (varBar)  varBar.style.display   = 'flex';
            if (viewAct) viewAct.style.display  = 'none';
            if (editAct) editAct.style.display  = 'flex';

            // Focus text area
            if (pesanEl) pesanEl.focus();
            activeEditEl = pesanEl;
        }

        function cancelInlineEdit(id) {
            const namaEl   = document.getElementById('nama-' + id);
            const kodeEl   = document.getElementById('kode-' + id);
            const pesanEl  = document.getElementById('pesan-' + id);
            const varBar   = document.getElementById('var-tags-' + id);
            const viewAct  = document.getElementById('view-actions-' + id);
            const editAct  = document.getElementById('edit-actions-' + id);

            // Restore original values
            if (namaEl)  namaEl.innerText  = namaEl.getAttribute('data-original');
            if (kodeEl)  kodeEl.innerText  = kodeEl.getAttribute('data-original');
            if (pesanEl) {
                pesanEl.innerText = pesanEl.getAttribute('data-original');
                rerenderBubbleText(pesanEl);
            }

            if (namaEl)  namaEl.contentEditable  = 'false';
            if (kodeEl)  kodeEl.contentEditable  = 'false';
            if (pesanEl) pesanEl.contentEditable = 'false';

            if (varBar)  varBar.style.display  = 'none';
            if (viewAct) viewAct.style.display = 'flex';
            if (editAct) editAct.style.display = 'none';
            activeEditEl = null;
        }

        const MASTER_SYSTEM_VARS = [
            '{nama_guru}', '{nama_siswa}', '{nama_kelas}', '{jam_ke}', '{mapel}',
            '{alasan}', '{jenis_izin}', '{nama_kegiatan}', '{nama_piket}', '{tanggal}',
            '{status}', '{waktu_selesai}', '{sisa_menit}', '{lokasi}', '{keterangan}'
        ];

        function checkVariablesInTemplate(tmplId) {
            const card = document.querySelector(`.template-item-card[data-id="${tmplId}"]`);
            const pesanEl = document.getElementById('pesan-' + tmplId);
            const warnBanner = document.getElementById('warn-banner-' + tmplId);
            const warnText = document.getElementById('warn-text-' + tmplId);

            if (!card || !pesanEl) return [];

            let allowedVars = [];
            try {
                allowedVars = JSON.parse(card.getAttribute('data-allowed-vars') || '[]');
            } catch(e) {}

            const validSet = (allowedVars && allowedVars.length > 0) ? allowedVars : MASTER_SYSTEM_VARS;
            const rawText = pesanEl.innerText || '';
            const foundTags = rawText.match(/\{[^\}]+\}/g) || [];

            const invalidTags = [];
            foundTags.forEach(tag => {
                if (!validSet.includes(tag) && !MASTER_SYSTEM_VARS.includes(tag)) {
                    if (!invalidTags.includes(tag)) invalidTags.push(tag);
                }
            });

            if (invalidTags.length > 0 && warnBanner && warnText) {
                warnText.innerText = `Peringatan: Variabel ${invalidTags.join(', ')} tidak dikenal / tidak tersedia untuk template ini.`;
                warnBanner.style.display = 'flex';
            } else if (warnBanner) {
                warnBanner.style.display = 'none';
            }

            return invalidTags;
        }

        function saveInlineEdit(id) {
            const namaEl  = document.getElementById('nama-' + id);
            const kodeEl  = document.getElementById('kode-' + id);
            const pesanEl = document.getElementById('pesan-' + id);

            const nama        = namaEl  ? namaEl.innerText.trim()  : '';
            const kode        = kodeEl  ? kodeEl.innerText.trim()  : '';
            const formatPesan = pesanEl ? pesanEl.innerText.trim() : '';

            if (!nama || !formatPesan) {
                alert('Nama dan Format Pesan tidak boleh kosong!');
                return;
            }

            // Check for invalid variables before saving
            const invalidVars = checkVariablesInTemplate(id);
            if (invalidVars && invalidVars.length > 0) {
                if (!confirm(`⚠️ Peringatan: Terdapat variabel yang tidak dikenal (${invalidVars.join(', ')}).\nApakah Anda yakin ingin tetap menyimpan template ini?`)) {
                    return;
                }
            }

            const btn = document.getElementById('edit-actions-' + id).querySelector('button[onclick*="saveInlineEdit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span>Menyimpan...</span>';
            btn.disabled = true;

            fetch('/pengaturan-wa/templates/' + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ _method: 'PUT', nama, kode, format_pesan: formatPesan, is_active: 1 })
            })
            .then(res => {
                if (!res.ok) throw new Error('Server error ' + res.status);
                return res.json().catch(() => ({}));
            })
            .then(() => {
                // Restore button state first
                btn.innerHTML = originalText;
                btn.disabled = false;

                // Update data-original with new values
                if (namaEl) namaEl.setAttribute('data-original', nama);
                if (kodeEl) kodeEl.setAttribute('data-original', kode);
                if (pesanEl) pesanEl.setAttribute('data-original', formatPesan);

                // Re-render pesan with highlighting
                if (pesanEl) rerenderBubbleText(pesanEl);

                // Exit edit mode
                cancelInlineEdit(id);
            })
            .catch(err => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                alert('Gagal menyimpan: ' + err.message);
            });
        }

        function rerenderBubbleText(el) {
            const tmplId = el.id.replace('pesan-', '');
            const card = el.closest('.template-item-card');
            let allowedVars = [];
            if (card) {
                try { allowedVars = JSON.parse(card.getAttribute('data-allowed-vars') || '[]'); } catch(e) {}
            }
            const validSet = (allowedVars && allowedVars.length > 0) ? allowedVars : MASTER_SYSTEM_VARS;

            let text = el.getAttribute('data-original') || el.innerText;
            text = text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            text = text.replace(/\*([^\*]+)\*/g, '<strong>$1</strong>');

            text = text.replace(/\{([^\}]+)\}/g, function(match, varName) {
                const tag = '{' + varName + '}';
                if (validSet.includes(tag) || MASTER_SYSTEM_VARS.includes(tag)) {
                    return '<span class="tag-pill-var">' + tag + '</span>';
                } else {
                    return '<span class="tag-pill-var-invalid" title="Variabel ' + tag + ' tidak dikenal">' + tag + '</span>';
                }
            });

            el.innerHTML = text;
            checkVariablesInTemplate(tmplId);
        }

        // Save caret position before button click
        document.addEventListener('mousedown', function(e) {
            if (activeEditEl && activeEditEl.contains(document.activeElement)) {
                const sel = window.getSelection();
                if (sel.rangeCount > 0) savedRange = sel.getRangeAt(0).cloneRange();
            }
        });

        function insertVarAtCursor(tag) {
            if (!activeEditEl) return;
            activeEditEl.focus();
            const sel = window.getSelection();
            if (savedRange) {
                sel.removeAllRanges();
                sel.addRange(savedRange);
            }
            if (sel.rangeCount > 0) {
                const range = sel.getRangeAt(0);
                range.deleteContents();
                range.insertNode(document.createTextNode(tag));
                range.collapse(false);
                sel.removeAllRanges();
                sel.addRange(range);
                savedRange = range.cloneRange();
            } else {
                activeEditEl.innerText += tag;
            }
        }

        function requestPairingCode() {
            const phone = document.getElementById('pairPhoneNumber').value;
            if(!phone) {
                alert('Silakan masukkan nomor WhatsApp diawali dengan 62!');
                return;
            }

            fetch("{{ route('pengaturan-wa.pair-code') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ phone_number: phone })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('pairingCodeText').innerText = data.code;
                    document.getElementById('pairingCodeDisplay').style.display = 'block';
                    alert(data.message);
                } else {
                    alert('Gagal: ' + data.message);
                }
            })
            .catch(err => {
                alert('Error: ' + err.message);
            });
        }

        // Modal Helpers for Templates
        function showAddTemplateModal() {
            document.getElementById('templateModalTitle').innerText = 'Tambah Template Pesan Baru';
            document.getElementById('templateForm').action = "{{ route('pengaturan-wa.templates.store') }}";
            document.getElementById('templateMethodContainer').innerHTML = '';
            document.getElementById('tmplKode').value = '';
            document.getElementById('tmplKode').readOnly = false;
            document.getElementById('tmplNama').value = '';
            document.getElementById('tmplKategori').value = 'reminder';
            document.getElementById('tmplFormat').value = '';
            document.getElementById('tmplActive').checked = true;
            updateLivePreview();
            document.getElementById('templateModal').style.display = 'flex';
        }

        function editTemplate(tmpl) {
            document.getElementById('templateModalTitle').innerText = 'Edit Template: ' + tmpl.nama;
            document.getElementById('templateForm').action = "/pengaturan-wa/templates/" + tmpl.id;
            document.getElementById('templateMethodContainer').innerHTML = '@method("PUT")';
            document.getElementById('tmplKode').value = tmpl.kode;
            document.getElementById('tmplKode').readOnly = true;
            document.getElementById('tmplNama').value = tmpl.nama;
            document.getElementById('tmplKategori').value = tmpl.kategori;
            document.getElementById('tmplFormat').value = tmpl.format_pesan;
            document.getElementById('tmplActive').checked = tmpl.is_active ? true : false;
            updateLivePreview();
            document.getElementById('templateModal').style.display = 'flex';
        }

        function closeTemplateModal() {
            document.getElementById('templateModal').style.display = 'none';
        }

        // Modal Helpers for Recipients
        function showAddRecipientModal() {
            document.getElementById('recipientModalTitle').innerText = 'Tambah Penerima Khusus';
            document.getElementById('recipientForm').action = "{{ route('pengaturan-wa.recipients.store') }}";
            document.getElementById('recipientMethodContainer').innerHTML = '';
            document.getElementById('recNama').value = '';
            document.getElementById('recNomor').value = '';
            document.getElementById('recPeran').value = 'Admin';
            document.getElementById('recCatatan').value = '';
            document.getElementById('recTerima').checked = true;
            document.getElementById('recipientModal').style.display = 'flex';
        }

        function editRecipient(rec) {
            document.getElementById('recipientModalTitle').innerText = 'Edit Penerima: ' + rec.nama;
            document.getElementById('recipientForm').action = "/pengaturan-wa/recipients/" + rec.id;
            document.getElementById('recipientMethodContainer').innerHTML = '@method("PUT")';
            document.getElementById('recNama').value = rec.nama;
            document.getElementById('recNomor').value = rec.nomor_wa;
            document.getElementById('recPeran').value = rec.peran;
            document.getElementById('recCatatan').value = rec.catatan || '';
            document.getElementById('recTerima').checked = rec.terima_notifikasi ? true : false;
            document.getElementById('recipientModal').style.display = 'flex';
        }

        function closeRecipientModal() {
            document.getElementById('recipientModal').style.display = 'none';
        }
    </script>
</body>
</html>
