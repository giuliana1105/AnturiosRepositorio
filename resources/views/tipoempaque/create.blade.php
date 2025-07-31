@extends('layouts.app')
@section('content')
<div class="row">
    <section class="content">
        <div class="col-md-8 col-md-offset-2">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Error!</strong> Revise los campos obligatorios.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Nuevo Tipo de Empaque</h3>
                </div>
                <div class="panel-body">
                    <div class="table-container">
                        <form method="POST" action="{{ route('tipoempaque.store') }}" role="form">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="codigotipoempaque" id="codigotipoempaque" class="form-control input-sm" placeholder="Código Tipo Empaque">
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="nombretipoempaque" id="nombretipoempaque" class="form-control input-sm" placeholder="Nombre Tipo Empaque">
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <input type="submit" value="Guardar" class="btn btn-success btn-block">
                                    <a href="{{ route('tipoempaque.index') }}" class="btn btn-info btn-block">Atrás</a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
            
        </div>
    </section>
    @endsection