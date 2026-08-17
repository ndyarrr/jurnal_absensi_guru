@extends('layouts.app')

@section('title', 'Tambah Guru')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2> Tambah Guru</h2>
            <p>Masukkan data pengajar baru ke dalam sistem.</p>
        </div>
    </div>

    <div class="form-card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <span><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg></span>
                <div>
                    <strong>Terjadi kesalahan input:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('guru.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group col-6">
                    <label for="nuptk">NUPTK / NIP <span class="required">*</span></label>
                    <input type="text" name="nuptk" id="nuptk" class="form-control" value="{{ old('nuptk') }}" placeholder="16 digit NUPTK atau 18 digit NIP" maxlength="18" inputmode="numeric" oninput="this.value=this.value.replace(/\D/g,'')" required>
                </div>

                <div class="form-group col-6">
                    <label for="nama_guru">Nama Lengkap Guru <span class="required">*</span></label>
                    <input type="text" name="nama_guru" id="nama_guru" class="form-control" value="{{ old('nama_guru') }}" placeholder="Nama beserta gelar" required>
                </div>

                <div class="form-group col-12">
                    <label for="no_hp">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789">
                    <span class="help-text">Nomor kontak aktif yang dapat dihubungi.</span>
                </div>

                <div class="form-group col-12">
                    <label>Mata Pelajaran yang Diampu</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; margin-top: 8px; background: rgba(255,255,255,0.03); padding: 16px; border-radius: 8px; border: 1px solid var(--border-color, #e2e8f0);">
                        @forelse($mapel as $m)
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.95rem;">
                                <input type="checkbox" name="mapel[]" value="{{ $m->id_mapel }}" {{ is_array(old('mapel')) && in_array($m->id_mapel, old('mapel')) ? 'checked' : '' }}>
                                <span>{{ $m->nama_mapel }}</span>
                            </label>
                        @empty
                            <span style="color: var(--text-light); grid-column: 1 / -1;">Belum ada data mata pelajaran.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan Guru</button>
                <a href="{{ route('guru.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nuptkInput = document.getElementById('nuptk');
    if (!nuptkInput) return;

    const badge = document.createElement('span');
    badge.style.cssText = 'font-size: 0.75rem; font-weight: 800; padding: 2px 6px; border-radius: 6px; margin-left: 8px; font-family: monospace; transition: all 0.2s ease;';
    const label = nuptkInput.parentNode.querySelector('label');
    if (label) label.appendChild(badge);

    function updateBadge() {
        const len = nuptkInput.value.length;
        if (len === 16) {
            badge.textContent = '16 / 16 (NUPTK Pas)';
            badge.style.background = '#dcfce7';
            badge.style.color = '#15803d';
        } else if (len === 18) {
            badge.textContent = '18 / 18 (NIP Pas)';
            badge.style.background = '#dcfce7';
            badge.style.color = '#15803d';
        } else {
            badge.textContent = len + ' / 16 atau 18 digit';
            badge.style.background = '#fee2e2';
            badge.style.color = '#b91c1c';
        }
    }

    nuptkInput.addEventListener('input', updateBadge);
    updateBadge();

    nuptkInput.closest('form').addEventListener('submit', function(e) {
        const len = nuptkInput.value.trim().length;
        if (len !== 16 && len !== 18) {
            e.preventDefault();
            alert('NUPTK harus tepat 16 digit atau NIP 18 digit angka.');
            nuptkInput.focus();
        }
    });
});
</script>
@endsection
