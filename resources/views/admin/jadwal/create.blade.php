@extends('layouts.app')

@section('title', 'Tambah Jadwal Pelajaran')

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <div>
            <h2> Tambah Jadwal Pelajaran</h2>
            <p>Atur alokasi mata pelajaran, hari, dan jam mengajar guru.</p>
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

        <form action="{{ route('jadwal.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group col-6">
                    <label for="id_kelas">Kelas <span class="required">*</span></label>
                    <select name="id_kelas" id="id_kelas" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                Kelas {{ $k->tingkat }} {{ $k->jurusan->kode_jurusan ?? '' }} {{ $k->rombel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-6">
                    <label for="id_mapel">Mata Pelajaran <span class="required">*</span></label>
                    <select name="id_mapel" id="id_mapel" class="form-control" required>
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapel as $m)
                            <option value="{{ $m->id_mapel }}" {{ old('id_mapel') == $m->id_mapel ? 'selected' : '' }}>
                                {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-6">
                    <label for="hari">Hari <span class="required">*</span></label>
                    <select name="hari" id="hari" class="form-control" required>
                        <option value="">-- Pilih Hari --</option>
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                            <option value="{{ $h }}" {{ old('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-6">
                    <label for="jam_ke">Jam Ke- <span class="required">*</span></label>
                    <input type="number" name="jam_ke" id="jam_ke" class="form-control" value="{{ old('jam_ke', 1) }}" min="1" max="12" required>
                    <span class="help-text">Masukkan angka urutan jam pelajaran (1 - 12).</span>
                </div>

                <div class="form-group col-12">
                    <label for="id_guru">Guru Pengampu <span class="required">*</span></label>
                    <select name="id_guru" id="id_guru" class="form-control" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($guru as $g)
                            <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                                {{ $g->nama_guru }} (NUPTK: {{ $g->nuptk ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan Jadwal</button>
                <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection