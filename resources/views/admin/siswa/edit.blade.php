@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2> Edit Data Siswa</h2>
            <p>Perbarui NISN, Nama, atau Kelas siswa.</p>
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

        <form action="{{ route('siswa.update', $siswa) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group col-6">
                    <label for="nisn">NISN <span class="required">*</span></label>
                    <input type="text" name="nisn" id="nisn" class="form-control" value="{{ old('nisn', $siswa->nisn) }}" placeholder="10 digit NISN" maxlength="10" inputmode="numeric" oninput="this.value=this.value.replace(/\D/g,'')" required>
                </div>

                <div class="form-group col-6">
                    <label for="nama_siswa">Nama Lengkap Siswa <span class="required">*</span></label>
                    <input type="text" name="nama_siswa" id="nama_siswa" class="form-control" value="{{ old('nama_siswa', $siswa->nama_siswa) }}" required>
                </div>

                <div class="form-group col-12">
                    <label for="id_kelas">Kelas <span class="required">*</span></label>
                    <select name="id_kelas" id="id_kelas" class="form-control" required>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $siswa->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                                Kelas {{ $k->tingkat }} {{ $k->jurusan->kode_jurusan ?? '' }} {{ $k->rombel }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">🔄 Perbarui Data</button>
                <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nisnInput = document.getElementById('nisn');
    if (!nisnInput) return;
    
    const badge = document.createElement('span');
    badge.style.cssText = 'font-size: 0.75rem; font-weight: 800; padding: 2px 6px; border-radius: 6px; margin-left: 8px; font-family: monospace; transition: all 0.2s ease;';
    const label = nisnInput.parentNode.querySelector('label');
    if (label) label.appendChild(badge);

    function updateBadge() {
        const len = nisnInput.value.length;
        badge.textContent = len + ' / 10 digit';
        if (len === 10) {
            badge.style.background = '#dcfce7';
            badge.style.color = '#15803d';
        } else {
            badge.style.background = '#fee2e2';
            badge.style.color = '#b91c1c';
        }
    }

    nisnInput.addEventListener('input', updateBadge);
    updateBadge();

    nisnInput.closest('form').addEventListener('submit', function(e) {
        if (nisnInput.value.length !== 10) {
            e.preventDefault();
            alert('NISN harus berisi tepat 10 digit angka.');
            nisnInput.focus();
        }
    });
});
</script>
@endsection