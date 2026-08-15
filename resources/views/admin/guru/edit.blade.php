@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2> Edit Data Guru</h2>
            <p>Perbarui informasi data guru pengajar.</p>
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

        <form action="{{ route('guru.update', $guru) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group col-6">
                    <label for="nuptk">NUPTK / NIP <span class="required">*</span></label>
                    <input type="text" name="nuptk" id="nuptk" class="form-control" value="{{ old('nuptk', $guru->nuptk) }}" required>
                </div>

                <div class="form-group col-6">
                    <label for="nama_guru">Nama Lengkap Guru <span class="required">*</span></label>
                    <input type="text" name="nama_guru" id="nama_guru" class="form-control" value="{{ old('nama_guru', $guru->nama_guru) }}" required>
                </div>

                <div class="form-group col-12">
                    <label for="no_hp">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp', $guru->no_hp) }}">
                </div>

                @php
                    $assignedMapelIds = old('mapel', $guru->mapel->pluck('id_mapel')->toArray());
                @endphp
                <div class="form-group col-12">
                    <label>Mata Pelajaran yang Diampu</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; margin-top: 8px; background: rgba(255,255,255,0.03); padding: 16px; border-radius: 8px; border: 1px solid var(--border-color, #e2e8f0);">
                        @forelse($mapel as $m)
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.95rem;">
                                <input type="checkbox" name="mapel[]" value="{{ $m->id_mapel }}" {{ is_array($assignedMapelIds) && in_array($m->id_mapel, $assignedMapelIds) ? 'checked' : '' }}>
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
                <button type="submit" class="btn btn-primary">🔄 Perbarui Data</button>
                <a href="{{ route('guru.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
