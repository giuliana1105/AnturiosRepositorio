@extends('layouts.app')
@section('content')
<div class="row">
    <section class="content">
        <div class="col-md-8 col-md-offset-2">
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Editar Bodega</h3>
                </div>
                <div class="panel-body">
                    <div class="table-container">
                        <form method="POST" action="{{ route('bodegas.update', $bodega->idbodega) }}" role="form">
                            @csrf
                            @method('PATCH')
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="nombrebodega">Nombre de la Bodega</label>
                                        <input type="text" name="nombrebodega" id="nombrebodega" class="form-control input-sm" value="{{ $bodega->nombrebodega }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <input type="submit" value="Actualizar" class="btn btn-success btn-block">
                                    <a href="{{ route('bodegas.index') }}" class="btn btn-info btn-block">Atrás</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
