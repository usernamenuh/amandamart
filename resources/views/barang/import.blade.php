@extends('layouts.app') {{-- atau sesuaikan layout kamu --}}

@section('content')
<div class="container">
    <h2>Import Data Barang</h2>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div style="color: red;">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('barang.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="file">Pilih File Excel:</label>
            <input type="file" name="file" required>
        </div>
        <br>
        <button type="submit">Import</button>
    </form>
</div>
@endsection
