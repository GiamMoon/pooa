<?php
class InventarioController extends Controller {
    public function index() {
        $this->view('inventario/index', [
            'title' => 'Cuadro de Control',
            'activePage' => 'index',
            'openMenu' => 'inventario',
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
            'scripts' => ['assets/js/app-movimiento-lista.js'],
            'permisos' => $_SESSION['permisos']
        ]);
    }

    public function producto() {
        $this->view('inventario/producto', [
            'title' => 'Productos',
            'activePage' => 'producto',
            'openMenu' => 'inventario',
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
            'permisos' => $_SESSION['permisos']            
        ]);
    }


    public function categoria() {
        $this->view('inventario/categoria', [
            'title' => 'Categorías',
            'activePage' => 'categoria',
            'openMenu' => 'inventario',
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

    public function moto() {
        $this->view('inventario/moto', [
            'title' => 'Motos',
            'activePage' => 'moto',
            'openMenu' => 'inventario',
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
            'scripts' => ['assets/js/app-moto-lista.js'],
            'permisos' => $_SESSION['permisos']
        ]);
    }

        public function marca() {
        $this->view('inventario/marca', [
            'title' => 'Marcas',
            'activePage' => 'marca',
            'openMenu' => 'inventario',
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

    public function unidad() {
        $this->view('inventario/unidad', [
            'title' => 'Tipos de Unidades',
            'activePage' => 'unidad',
            'openMenu' => 'inventario',
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

    public function ubicacion() {
        $this->view('inventario/ubicacion', [
            'title' => 'Ubicaciones',
            'activePage' => 'ubicacion',
            'openMenu' => 'inventario',
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
            'scripts' => ['assets/js/app-ubicacion-lista.js'],
            'permisos' => $_SESSION['permisos']
        ]);
    }

    public function modelo() {
        $this->view('inventario/modelo', [
            'title' => 'Modelos',
            'activePage' => 'modelo',
            'openMenu' => 'inventario',
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
            'scripts' => ['assets/js/app-modelo-lista.js'],
            'permisos' => $_SESSION['permisos']
        ]);
    }
}