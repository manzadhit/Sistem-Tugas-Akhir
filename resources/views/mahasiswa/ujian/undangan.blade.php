@extends('layouts.app')

@section('title', 'Undangan Ujian ' . ucfirst($jenis))

@section('sidebar')
    @include('mahasiswa.sidebar')
@endsection

@section('content')
    <div class="container">
        <h1>Undangan Ujian {{ ucfirst($jenis) }}</h1>
    </div>
@endsection