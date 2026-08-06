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
                <span>⚠️</span>
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
                    <input type="text" name="nuptk" id="nuptk" class="form-control" value="{{ old('nuptk') }}" placeholder="Contoh: 1234567890" required>
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
                    <span class="help-text" style="margin-top: 6px; display: block;">Pilih satu atau lebih mata pelajaran yang diajarkan oleh guru ini.</span>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan Guru</button>
                <a href="{{ route('guru.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
