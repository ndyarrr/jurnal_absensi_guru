{{-- Global Confirmation Modal Component --}}
<style>
    .gcm-overlay {
        position: fixed;
        inset: 0;
        z-index: 999999;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }
    .gcm-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    .gcm-card {
        background: #ffffff;
        border-radius: 20px;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.35), 0 0 0 1px rgba(15, 23, 42, 0.05);
        overflow: hidden;
        transform: scale(0.92) translateY(10px);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    .gcm-overlay.active .gcm-card {
        transform: scale(1) translateY(0);
    }
    .gcm-close-btn {
        position: absolute;
        top: 14px;
        right: 16px;
        background: #f1f5f9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        font-size: 1.2rem;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        line-height: 1;
    }
    .gcm-close-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .gcm-body {
        padding: 28px 24px 20px 24px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .gcm-icon-wrap {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .gcm-icon-wrap svg {
        width: 28px;
        height: 28px;
        stroke-width: 2;
        flex-shrink: 0;
    }
    /* Icon Preset Styles */
    .gcm-icon-wrap.logout,
    .gcm-icon-wrap.delete,
    .gcm-icon-wrap.reject,
    .gcm-icon-wrap.danger {
        background: #fef2f2;
        color: #dc2626;
        border: 2px solid #fecaca;
    }
    .gcm-icon-wrap.create,
    .gcm-icon-wrap.approve,
    .gcm-icon-wrap.success {
        background: #f0fdf4;
        color: #16a34a;
        border: 2px solid #bbf7d0;
    }
    .gcm-icon-wrap.update,
    .gcm-icon-wrap.save,
    .gcm-icon-wrap.primary {
        background: #eff6ff;
        color: #2563eb;
        border: 2px solid #bfdbfe;
    }
    .gcm-icon-wrap.warning {
        background: #fefce8;
        color: #ca8a04;
        border: 2px solid #fef08a;
    }

    .gcm-title {
        margin: 0 0 8px 0;
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.3;
    }
    .gcm-message {
        margin: 0;
        font-size: 0.9rem;
        color: #475569;
        line-height: 1.5;
        font-weight: 500;
    }
    .gcm-subtext {
        margin: 8px 0 0 0;
        font-size: 0.78rem;
        color: #94a3b8;
        line-height: 1.4;
        font-style: italic;
    }
    .gcm-footer {
        padding: 16px 24px 24px 24px;
        display: flex;
        gap: 12px;
        justify-content: center;
        background: #ffffff;
    }
    .gcm-btn-cancel,
    .gcm-btn-confirm {
        flex: 1;
        padding: 11px 18px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 700;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-family: inherit;
    }
    .gcm-btn-cancel {
        background: #f1f5f9;
        color: #475569;
        border-color: #cbd5e1;
    }
    .gcm-btn-cancel:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    /* Confirm Button Presets */
    .gcm-btn-confirm.logout,
    .gcm-btn-confirm.delete,
    .gcm-btn-confirm.reject,
    .gcm-btn-confirm.danger {
        background: #dc2626;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
    }
    .gcm-btn-confirm.logout:hover,
    .gcm-btn-confirm.delete:hover,
    .gcm-btn-confirm.reject:hover,
    .gcm-btn-confirm.danger:hover {
        background: #b91c1c;
    }

    .gcm-btn-confirm.create,
    .gcm-btn-confirm.approve,
    .gcm-btn-confirm.success {
        background: #16a34a;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
    }
    .gcm-btn-confirm.create:hover,
    .gcm-btn-confirm.approve:hover,
    .gcm-btn-confirm.success:hover {
        background: #15803d;
    }

    .gcm-btn-confirm.update,
    .gcm-btn-confirm.save,
    .gcm-btn-confirm.primary {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }
    .gcm-btn-confirm.update:hover,
    .gcm-btn-confirm.save:hover,
    .gcm-btn-confirm.primary:hover {
        background: #1d4ed8;
    }

    .gcm-btn-confirm.warning {
        background: #d97706;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25);
    }
    .gcm-btn-confirm.warning:hover {
        background: #b45309;
    }
</style>

<div id="globalConfirmModalOverlay" class="gcm-overlay" aria-hidden="true">
    <div class="gcm-card">
        <button type="button" class="gcm-close-btn" onclick="closeGlobalConfirmModal()">&times;</button>
        
        <div class="gcm-body">
            <div class="gcm-icon-wrap logout" id="gcmIconWrap">
                {{-- Inline SVG inserted via JS --}}
            </div>
            
            <h3 class="gcm-title" id="gcmTitle">Konfirmasi Keluar Akun</h3>
            <p class="gcm-message" id="gcmMessage">Apakah Anda yakin ingin keluar dari akun ini?</p>
            <p class="gcm-subtext" id="gcmSubtext" style="display: none;"></p>
        </div>

        <div class="gcm-footer">
            <button type="button" class="gcm-btn-cancel" id="gcmCancelBtn" onclick="closeGlobalConfirmModal()">Batal</button>
            <button type="button" class="gcm-btn-confirm logout" id="gcmConfirmBtn">Ya, Keluar Akun</button>
        </div>
    </div>
</div>

<script>
(function() {
    // Standalone SVG Icons for each preset
    const GCM_ICONS = {
        logout:  `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>`,
        delete:  `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>`,
        create:  `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>`,
        update:  `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>`,
        approve: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`,
        reject:  `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`,
        warning: `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`,
        danger:  `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`,
    };

    // Default Presets Configuration
    const GCM_PRESETS = {
        logout: {
            typeClass: 'logout',
            title: 'Konfirmasi Keluar Akun',
            message: 'Apakah Anda yakin ingin keluar dari akun ini? Sesi Anda saat ini akan diakhiri.',
            confirmText: 'Ya, Keluar Akun',
            cancelText: 'Batal'
        },
        delete: {
            typeClass: 'delete',
            title: 'Konfirmasi Hapus Data',
            message: 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
            confirmText: 'Ya, Hapus Data',
            cancelText: 'Batal'
        },
        create: {
            typeClass: 'create',
            title: 'Konfirmasi Tambah Data',
            message: 'Apakah Anda yakin ingin menambahkan data baru ini ke dalam sistem?',
            confirmText: 'Ya, Tambahkan',
            cancelText: 'Batal'
        },
        update: {
            typeClass: 'update',
            title: 'Konfirmasi Simpan Perubahan',
            message: 'Apakah Anda yakin ingin menyimpan perubahan data ini?',
            confirmText: 'Ya, Simpan',
            cancelText: 'Batal'
        },
        approve: {
            typeClass: 'approve',
            title: 'Konfirmasi Persetujuan',
            message: 'Apakah Anda yakin ingin menyetujui permohonan ini?',
            confirmText: 'Ya, Setujui',
            cancelText: 'Batal'
        },
        reject: {
            typeClass: 'reject',
            title: 'Konfirmasi Penolakan',
            message: 'Apakah Anda yakin ingin menolak permohonan ini?',
            confirmText: 'Ya, Tolak',
            cancelText: 'Batal'
        },
        warning: {
            typeClass: 'warning',
            title: 'Konfirmasi Tindakan',
            message: 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
            confirmText: 'Ya, Lanjutkan',
            cancelText: 'Batal'
        }
    };

    let currentConfirmCallback = null;

    /**
     * Show Global Confirmation Modal
     * @param {Object} options
     */
    window.showConfirmModal = function(options = {}) {
        const overlay    = document.getElementById('globalConfirmModalOverlay');
        const iconWrap   = document.getElementById('gcmIconWrap');
        const titleEl    = document.getElementById('gcmTitle');
        const messageEl  = document.getElementById('gcmMessage');
        const subtextEl  = document.getElementById('gcmSubtext');
        const confirmBtn = document.getElementById('gcmConfirmBtn');
        const cancelBtn  = document.getElementById('gcmCancelBtn');

        if (!overlay) return;

        const type      = (options.type || 'warning').toLowerCase();
        const preset    = GCM_PRESETS[type] || GCM_PRESETS.warning;
        const typeClass = preset.typeClass;

        // Apply Icon SVG & styling class
        const iconSvg = GCM_ICONS[typeClass] || GCM_ICONS.warning;
        iconWrap.className = 'gcm-icon-wrap ' + typeClass;
        iconWrap.innerHTML = iconSvg;

        confirmBtn.className = 'gcm-btn-confirm ' + typeClass;

        // Apply Text — title as textContent, message as innerHTML to allow <strong> / <br> formatting
        titleEl.textContent = options.title || preset.title;
        messageEl.innerHTML = options.message || preset.message;

        if (options.subtext) {
            subtextEl.innerHTML   = options.subtext;
            subtextEl.style.display = 'block';
        } else {
            subtextEl.style.display = 'none';
        }

        confirmBtn.textContent = options.confirmText || preset.confirmText;
        cancelBtn.textContent  = options.cancelText  || preset.cancelText;

        // Set Confirm Action Callback
        currentConfirmCallback = function() {
            closeGlobalConfirmModal();
            if (typeof options.onConfirm === 'function') {
                options.onConfirm();
            } else if (options.form && typeof options.form.submit === 'function') {
                options.form.dataset.gcmConfirmed = 'true';
                options.form.submit();
            }
        };

        // Show Modal
        overlay.style.display = 'flex';
        requestAnimationFrame(() => {
            overlay.classList.add('active');
        });

        // Set Focus to Confirm Button
        setTimeout(() => confirmBtn.focus(), 50);
    };

    window.closeGlobalConfirmModal = function() {
        const overlay = document.getElementById('globalConfirmModalOverlay');
        if (!overlay) return;

        overlay.classList.remove('active');
        setTimeout(() => {
            overlay.style.display = 'none';
            currentConfirmCallback = null;
        }, 200);
    };

    /**
     * Show Global Toast Notification
     * @param {String} message
     * @param {String} type - 'success' | 'error' | 'warning'
     */
    window.showToast = function(message, type = 'success') {
        let container = document.getElementById('globalToastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'globalToastContainer';
            container.style.cssText = 'position: fixed; top: 24px; right: 24px; z-index: 9999999; display: flex; flex-direction: column; gap: 10px; pointer-events: none;';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        const isErr = type === 'error';
        const isWarn = type === 'warning';

        const bg = isErr ? '#fef2f2' : (isWarn ? '#fffbe6' : '#f0fdf4');
        const border = isErr ? '#fecaca' : (isWarn ? '#ffe58f' : '#bbf7d0');
        const color = isErr ? '#dc2626' : (isWarn ? '#d97706' : '#16a34a');

        const icon = isErr
            ? `<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`
            : (isWarn
                ? `<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`
                : `<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`
            );

        toast.style.cssText = `background: ${bg}; border: 1px solid ${border}; color: ${color}; padding: 12px 18px; border-radius: 12px; font-weight: 700; font-size: 0.875rem; box-shadow: 0 10px 25px rgba(15,23,42,0.15); display: flex; align-items: center; gap: 10px; opacity: 0; transform: translateY(-12px); transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1); pointer-events: auto; font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;`;
        toast.innerHTML = `${icon} <span>${message}</span>`;

        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        });

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-12px)';
            setTimeout(() => toast.remove(), 250);
        }, 3500);
    };

    // Attach Confirm Click Handler
    document.addEventListener('DOMContentLoaded', function() {
        const confirmBtn = document.getElementById('gcmConfirmBtn');
        const overlay    = document.getElementById('globalConfirmModalOverlay');

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                if (typeof currentConfirmCallback === 'function') {
                    currentConfirmCallback();
                }
            });
        }

        // Close on ESC key or backdrop click
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && overlay && overlay.classList.contains('active')) {
                closeGlobalConfirmModal();
            }
        });

        if (overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    closeGlobalConfirmModal();
                }
            });
        }

        // Auto Interceptor for forms with data-confirm attributes
        document.body.addEventListener('submit', function(e) {
            const form = e.target;
            if (!form || form.tagName !== 'FORM') return;

            // If already confirmed by GCM, let it submit
            if (form.dataset.gcmConfirmed === 'true') {
                delete form.dataset.gcmConfirmed;
                return;
            }

            const confirmMsg   = form.getAttribute('data-confirm');
            const confirmType  = form.getAttribute('data-confirm-type');
            const confirmTitle = form.getAttribute('data-confirm-title');

            if (confirmMsg || confirmType) {
                e.preventDefault();
                showConfirmModal({
                    type:    confirmType || 'warning',
                    title:   confirmTitle,
                    message: confirmMsg,
                    form:    form
                });
            }
        }, true);
    });
})();
</script>
