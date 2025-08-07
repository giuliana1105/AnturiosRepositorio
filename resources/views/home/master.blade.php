@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Bodega Master</h3>
    <div class="mb-4">
        
        <a href="{{ route('productos.index') }}" class="btn btn-primary">Productos</a>
        <a href="{{ route('empleados.index') }}" class="btn btn-primary">Empleados</a>
        <a href="{{ route('bodegas.index') }}" class="btn btn-primary">Bodegas</a>
        <a href="{{ route('tipoNota.index') }}" class="btn btn-primary">Tipo Nota</a>
        <a href="{{ route('transaccionProducto.index') }}" class="btn btn-primary">Transacción Producto</a>
        ?>
    </div>
    <!-- Puedes mostrar resúmenes o tablas aquí si lo deseas -->
</div>
@endsection