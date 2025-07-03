<?php
class ProductosController extends Controller {

    public function stock_actual()
    {
        $this->view('productos/stock_actual', [
            'title'      => 'Stock Actual',
            'activePage' => 'stock_actual',
            'openMenu'   => 'productos',
            'styles'     => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
            ],
            'vendors'    => [
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
            ],
            'scripts'    => ['assets/js/app-stock-actual.js'],
            'permisos'   => $_SESSION['permisos'] ?? []
        ]);
    }

    public function producto()
    {
        $this->view('productos/producto', [
            'title' => 'Productos',
            'activePage' => 'producto',
            'openMenu' => 'productos',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css',
                'assets/vendor/libs/dropzone/dropzone.css'
            ],
            'vendors' => [
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js' ,
                'assets/vendor/libs/select2/select2.js',
                'assets/vendor/libs/dropzone/dropzone.js'
            ],
            'scripts' => ['assets/js/app-producto-lista.js'],
            'permisos' => $_SESSION['permisos'] ?? []
        ]);
    }

    public function categoria() 
    {
        $this->view('productos/categoria', [
            'title' => 'Categorías',
            'activePage' => 'categoria',
            'openMenu' => 'productos',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css'
            ],
            'vendors' => [
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js' ,
                'assets/vendor/libs/select2/select2.js',
            ],
            'scripts' => ['assets/js/app-categoria-lista.js'],
            'permisos' => $_SESSION['permisos']
        ]);
    }

    public function marca() 
        {
        $this->view('productos/marca', [
            'title' => 'Marcas',
            'activePage' => 'marca',
            'openMenu' => 'productos',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css'
            ],
            'vendors' => [
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js' ,
                'assets/vendor/libs/select2/select2.js',
            ],
            'scripts' => ['assets/js/app-marca-lista.js'],
            'permisos' => $_SESSION['permisos']
        ]);
    }

    public function unidad() 
    {
        $this->view('productos/unidad', [
            'title' => 'Tipos de Unidades',
            'activePage' => 'unidad',
            'openMenu' => 'productos',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css'
            ],
            'vendors' => [
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js' ,
                'assets/vendor/libs/select2/select2.js',
            ],
            'scripts' => ['assets/js/app-unidad-lista.js'],
            'permisos' => $_SESSION['permisos']
        ]);
    }    

}