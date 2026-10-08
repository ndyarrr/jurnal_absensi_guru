{{-- Modal konfirmasi keputusan Kepala Sekolah (setujui / tolak) --}}
<div class="modal-backdrop" id="modalSetujui" role="dialog" aria-modal="true" aria-labelledby="modalSetujuiTitle">
    <div class="modal-box">
        <form method="POST" id="formSetujui" action="">
            @csrf
            <div class="modal-head">
                <span id="modalSetujuiTitle"><i class="fa-solid fa-circle-check" style="color:#10b981;"></i> Setujui <span data-fill="jenis-title">Izin Guru</span></span>
                <button type="button" class="modal-close" data-close-modal aria-label="Tutup">&times;</button>
            </div>
            <div class="modal-body">
                <p style="font-size:.88rem; color:#374151; margin-bottom:14px;">
                    Anda akan menyetujui <span data-fill="jenis">izin</span> <strong data-fill="guru">-</strong>
                    (<span data-fill="periode">-</span>). Anda tetap bisa membatalkan keputusan ini nanti.
                </p>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn-light" data-close-modal>Batal</button>
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Setujui</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-backdrop" id="modalTolak" role="dialog" aria-modal="true" aria-labelledby="modalTolakTitle">
    <div class="modal-box">
        <form method="POST" id="formTolak" action="">
            @csrf
            <div class="modal-head">
                <span id="modalTolakTitle"><i class="fa-solid fa-circle-xmark" style="color:#ef4444;"></i> Tolak <span data-fill="jenis-title">Izin Guru</span></span>
                <button type="button" class="modal-close" data-close-modal aria-label="Tutup">&times;</button>
            </div>
            <div class="modal-body">
                <p style="font-size:.88rem; color:#374151; margin-bottom:14px;">
                    Anda akan menolak <span data-fill="jenis">izin</span> <strong data-fill="guru">-</strong>
                    (<span data-fill="periode">-</span>). Anda tetap bisa membatalkan keputusan ini nanti.
                </p>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn-light" data-close-modal>Batal</button>
                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-xmark"></i> Tolak</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    function openModal(id, trigger) {
        var modal = document.getElementById(id);
        if (!modal) return;
        var form = modal.querySelector('form');
        form.setAttribute('action', trigger.getAttribute('data-action'));
        modal.querySelectorAll('[data-fill="guru"]').forEach(function (el) { el.textContent = trigger.getAttribute('data-guru') || '-'; });
        modal.querySelectorAll('[data-fill="periode"]').forEach(function (el) { el.textContent = trigger.getAttribute('data-periode') || '-'; });
        var jenis = trigger.getAttribute('data-jenis') || 'izin';
        var jenisTitle = trigger.getAttribute('data-jenis-title') || 'Izin Guru';
        modal.querySelectorAll('[data-fill="jenis"]').forEach(function (el) { el.textContent = jenis; });
        modal.querySelectorAll('[data-fill="jenis-title"]').forEach(function (el) { el.textContent = jenisTitle; });
        modal.classList.add('open');
    }
    function closeAll() {
        document.querySelectorAll('.modal-backdrop.open').forEach(function (m) { m.classList.remove('open'); });
    }

    document.addEventListener('click', function (e) {
        var setuju = e.target.closest('[data-open-setujui]');
        if (setuju) { e.preventDefault(); openModal('modalSetujui', setuju); return; }

        var tolak = e.target.closest('[data-open-tolak]');
        if (tolak) { e.preventDefault(); openModal('modalTolak', tolak); return; }

        if (e.target.closest('[data-close-modal]') || e.target.classList.contains('modal-backdrop')) { closeAll(); }
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closeAll(); } });
})();
</script>