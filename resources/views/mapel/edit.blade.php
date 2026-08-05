@extends('layouts.app')

@section('content')
    <h2>Edit Mapel</h2>

    @if($errors->any())
        <ul style="color:red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('mapel.update', $mapel) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nama Mapel</label><br>
        <input type="text" name="nama_mapel" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required><br><br>

        <button type="submit">Update</button>
        <a href="{{ route('mapel.index') }}">Batal</a>
    </form>
@endsection