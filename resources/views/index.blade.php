
@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Seleccione una Bodega</h3>
    <div class="row">
        @foreach($bodegas as $bodega)
            <div class="col-md-3 mb-3">
                <a href="{{ route('home.bodega', $bodega->idbodega) }}" class="btn btn-outline-primary w-100">
                    {{ $bodega->nombrebodega }}
                </a>
            </div>
        @endforeach
        <div class="col-md-3 mb-3">
            <a href="{{ route('home.master') }}" class="btn btn-dark w-100">
                Bodega Master
            </a>
        </div>
    </div>
</div>
@endsection