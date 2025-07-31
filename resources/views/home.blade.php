@extends('layouts.app')
@section('title')
    Home
@endsection
@section('content')
    <h1>Home</h1>
    Bienvenido {{ $nombre ?? 'Bienvenido a ' }}
@endsection
