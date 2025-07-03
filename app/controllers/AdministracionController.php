<?php
class AdministracionController extends Controller {

        public function sucursal() 
    {
        $this->view('administracion/sucursal', [
            'title' => 'Sucursales',
            'activePage' => 'sucursal',
            'openMenu' => 'administracion',
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
            'scripts' => ['assets/js/app-sucursal-lista.js'],
            'permisos' => $_SESSION['permisos']
        ]);
    }        

    public function empresa() 
    {
        $this->view('administracion/empresa', [
            'title' => 'Datos de Empresa',
            'activePage' => 'empresa',
            'openMenu' => 'administracion',
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
            'scripts' => ['assets/js/app-empresa-lista.js'],
            'permisos' => $_SESSION['permisos'] ?? []
        ]);
    }

    public function almacen() 
    {
        $this->view('administracion/almacen', [
            'title' => 'Almacenes',
            'activePage' => 'almacen',
            'openMenu' => 'administracion',
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
            'scripts' => ['assets/js/app-almacen-lista.js'],
            'permisos' => $_SESSION['permisos']
        ]);
    }

}