<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CatalogoController extends Controller
{
    /**
     * Muestra el catálogo público de productos.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoriaSeleccionada = $request->input('categoria', 'Todos');

        // Obtener productos de BD o demostración si la tabla está vacía
        if (Producto::count() === 0) {
            $productosMapeados = self::getDemoProducts();
        } else {
            $productosDB = Producto::orderBy('nombre', 'ASC')->get();
            $productosMapeados = $productosDB->map(function($producto) {
                $meta = self::getProductMeta($producto->nombre, $producto->descripcion);
                $producto->meta_categoria = $meta['categoria'];
                $producto->meta_icono = $meta['icono'];
                $producto->meta_color = $meta['color'];
                $producto->meta_bg = $meta['bg'];
                return $producto;
            });
        }

        // Aplicar búsqueda por código, nombre o descripción
        if (!empty($search)) {
            $searchLower = mb_strtolower($search);
            $productosMapeados = $productosMapeados->filter(function($p) use ($searchLower) {
                return str_contains(mb_strtolower($p->nombre ?? ''), $searchLower) ||
                       str_contains(mb_strtolower($p->codigo ?? ''), $searchLower) ||
                       str_contains(mb_strtolower($p->descripcion ?? ''), $searchLower);
            });
        }

        // Categorías disponibles
        $categorias = [
            'Todos',
            'Cintas y Lazos',
            'Papeles y Envolturas',
            'Cajas y Empaques',
            'Flores y Follaje',
            'Bases y Espumas',
            'Artículos de Floristería'
        ];

        // Filtrar por categoría si se seleccionó una distinta de 'Todos'
        if ($categoriaSeleccionada !== 'Todos') {
            $productosMapeados = $productosMapeados->filter(function($p) use ($categoriaSeleccionada) {
                return ($p->meta_categoria ?? '') === $categoriaSeleccionada;
            });
        }

        // Paginar la colección manualmente (12 productos por página)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;
        $currentPageItems = $productosMapeados->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $productos = new LengthAwarePaginator(
            $currentPageItems,
            $productosMapeados->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('catalogo.index', compact('productos', 'categorias', 'categoriaSeleccionada', 'search'));
    }

    /**
     * Helper para determinar categoría floral, ícono y color basado en palabras clave.
     */
    public static function getProductMeta($nombre, $descripcion = '')
    {
        $texto = mb_strtolower($nombre . ' ' . $descripcion);

        if (str_contains($texto, 'cinta') || str_contains($texto, 'lazo') || str_contains($texto, 'satin') || str_contains($texto, 'organza')) {
            return [
                'categoria' => 'Cintas y Lazos',
                'icono' => 'fa-ribbon',
                'color' => '#E91E8C',
                'bg' => 'rgba(233, 30, 140, 0.1)'
            ];
        } elseif (str_contains($texto, 'papel') || str_contains($texto, 'coreano') || str_contains($texto, 'malla') || str_contains($texto, 'celofan') || str_contains($texto, 'envoltura') || str_contains($texto, 'snow') || str_contains($texto, 'pliego')) {
            return [
                'categoria' => 'Papeles y Envolturas',
                'icono' => 'fa-scroll',
                'color' => '#3F51B5',
                'bg' => 'rgba(63, 81, 181, 0.1)'
            ];
        } elseif (str_contains($texto, 'caja') || str_contains($texto, 'box') || str_contains($texto, 'estuche') || str_contains($texto, 'bolsa') || str_contains($texto, 'sombrerera')) {
            return [
                'categoria' => 'Cajas y Empaques',
                'icono' => 'fa-box-open',
                'color' => '#9C27B0',
                'bg' => 'rgba(156, 39, 176, 0.1)'
            ];
        } elseif (str_contains($texto, 'flor') || str_contains($texto, 'anturio') || str_contains($texto, 'rosa') || str_contains($texto, 'ramo') || str_contains($texto, 'foamy') || str_contains($texto, 'artificial') || str_contains($texto, 'follaje') || str_contains($texto, 'eucalipto')) {
            return [
                'categoria' => 'Flores y Follaje',
                'icono' => 'fa-seedling',
                'color' => '#E91E8C',
                'bg' => 'rgba(233, 30, 140, 0.1)'
            ];
        } elseif (str_contains($texto, 'espuma') || str_contains($texto, 'oasis') || str_contains($texto, 'base') || str_contains($texto, 'florero') || str_contains($texto, 'cristal') || str_contains($texto, 'cilindro') || str_contains($texto, 'jaula')) {
            return [
                'categoria' => 'Bases y Espumas',
                'icono' => 'fa-cube',
                'color' => '#009688',
                'bg' => 'rgba(0, 150, 136, 0.1)'
            ];
        } else {
            return [
                'categoria' => 'Artículos de Floristería',
                'icono' => 'fa-spa',
                'color' => '#673AB7',
                'bg' => 'rgba(103, 58, 183, 0.1)'
            ];
        }
    }

    /**
     * Productos de demostración en caso de que la BD aún no tenga registros.
     */
    public static function getDemoProducts()
    {
        $demos = [
            (object)[
                'codigo' => 'FLOR-001',
                'nombre' => 'Cinta Satinada Premium Magenta 4cm x 45m',
                'descripcion' => 'Cinta de satén de alta densidad con brillo sedoso, ideal para lazos florales, decoración de ramos y empaques de regalo de lujo.',
                'cantidad' => 120,
                'tipoempaque' => 'Rollo',
            ],
            (object)[
                'codigo' => 'FLOR-002',
                'nombre' => 'Papel Coreano Impermeable Bicolor Rosa/Blanco',
                'descripcion' => 'Pliego de papel coreano mate 100% impermeable, espesor premium de 60 micras, perfecto para envolturas de ramos estilo coreano.',
                'cantidad' => 350,
                'tipoempaque' => 'Paquete (20 pliegos)',
            ],
            (object)[
                'codigo' => 'FLOR-003',
                'nombre' => 'Caja Sombrerera Floral Cilíndrica Negra con Tapa',
                'descripcion' => 'Caja rígida de cartón prensado con recubrimiento impermeable interior. Ideal para arreglos de rosas y anturios de alta gama.',
                'cantidad' => 45,
                'tipoempaque' => 'Juego (3 tamaños)',
            ],
            (object)[
                'codigo' => 'FLOR-004',
                'nombre' => 'Espuma Floral Oasis De Alta Absorción (Ladrillo)',
                'descripcion' => 'Ladrillo de espuma floral de densidad profesional. Retiene hasta 40 veces su peso en agua para mantener tallos frescos por más de 10 días.',
                'cantidad' => 200,
                'tipoempaque' => 'Caja (20 ladrillos)',
            ],
            (object)[
                'codigo' => 'FLOR-005',
                'nombre' => 'Malla Snow Para Envoltura De Ramos Blanco Perla',
                'descripcion' => 'Malla tipo red con textura nevada suave, aporta volumen y elegancia como capa exterior en ramos florales.',
                'cantidad' => 85,
                'tipoempaque' => 'Rollo (10 metros)',
            ],
            (object)[
                'codigo' => 'FLOR-006',
                'nombre' => 'Florero De Cristal Nórdico Para Anturios 30cm',
                'descripcion' => 'Jarrón de vidrio templado transparente con diseño geométrico nórdico, excelente soporte y contrapeso para tallos largos.',
                'cantidad' => 60,
                'tipoempaque' => 'Unidad',
            ],
            (object)[
                'codigo' => 'FLOR-007',
                'nombre' => 'Cinta de Organza Traslúcida Con Borde Dorado 2.5cm',
                'descripcion' => 'Cinta ligera y vaporosa con hilo metálico dorado en los bordes, aporta un toque delicado y festivo a cualquier arreglo floral.',
                'cantidad' => 150,
                'tipoempaque' => 'Rollo',
            ],
            (object)[
                'codigo' => 'FLOR-008',
                'nombre' => 'Ramo de Eucalipto Artificial Real Touch 65cm',
                'descripcion' => 'Follaje artificial de alta fidelidad con textura real al tacto y acabado escarchado mate. Excelente durabilidad y apariencia.',
                'cantidad' => 90,
                'tipoempaque' => 'Docena',
            ]
        ];

        return collect($demos)->map(function($producto) {
            $meta = self::getProductMeta($producto->nombre, $producto->descripcion);
            $producto->meta_categoria = $meta['categoria'];
            $producto->meta_icono = $meta['icono'];
            $producto->meta_color = $meta['color'];
            $producto->meta_bg = $meta['bg'];
            return $producto;
        });
    }
}
