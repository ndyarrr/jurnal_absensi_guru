@extends('layouts.app')

@section('content')
    <h2>Detail Mapel</h2>

    <p><strong>Nama Mapel:</strong> {{ $mapel->nama_mapel }}</p>

    <a href="{{ route('mapel.index') }}">← Kembali</a>
@endsection