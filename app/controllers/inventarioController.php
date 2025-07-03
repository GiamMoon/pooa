<?php
class InventarioController extends Controller 
{
    public function index() 
    {
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

    public function movimientos()
    {
        $this->view('inventario/movimientos', [
            'title'      => 'Historial de Movimientos',
            'activePage' => 'movimientos',
            'openMenu'   => 'inventario',
            'styles'     => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
            ],
            'vendors'    => [
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
            ],
            'scripts'    => ['assets/js/app-inventario-lista.js'],
            'permisos'   => $_SESSION['permisos']
        ]);
    }    

    public function transferencias()
    {
        $model = $this->model('SucursalModel'); 
        $sucursales = $model->getSucursalesActivas();

        $this->view('inventario/transferencias', [ 
            'title'      => 'Transferencia de Stock',
            'activePage' => 'transferencias',
            'openMenu'   => 'inventario',
            'sucursales' => $sucursales,
            'styles'     => [
                'assets/vendor/libs/select2/select2.css',
                'assets/vendor/libs/sweetalert2/sweetalert2.css'
            ],
            'vendors'    => [
                'assets/vendor/libs/select2/select2.js',
                'assets/vendor/libs/sweetalert2/sweetalert2.js'
            ],
            'scripts'    => ['assets/js/app-transferencias.js'], 
            'permisos'   => $_SESSION['permisos'] ?? []
        ]);
    }    

    public function recepcion() 
    {
        $data = [
            'title'      => 'Recepción de Compras',
            'activePage' => 'recepcion_compras', 
            'openMenu'   => 'inventario', 
            'styles'     => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css',
                'assets/vendor/libs/sweetalert2/sweetalert2.css'
            ],
            'vendors'    => [
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
                'assets/vendor/libs/select2/select2.js',
                'assets/vendor/libs/sweetalert2/sweetalert2.js'
            ],
            'scripts'    => ['assets/js/app-recepcion-lista.js'],
            'permisos'   => $_SESSION['permisos'] ?? []
        ];
        
        $this->view('inventario/recepcion', $data);
    }
}