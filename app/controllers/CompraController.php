<?php
class CompraController extends Controller {
    
    public function compra() {
        $this->view('compra/compra', [
            'title' => 'Historial de Compras',
            'activePage' => 'compra',
            'openMenu' => 'compra',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css',                
            ],
            'vendors' => [
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js' ,
                'assets/vendor/libs/select2/select2.js',                
            ],
            'scripts' => ['assets/js/app-compra-lista.js'],
            'permisos' => $_SESSION['permisos']
        ]);
    }
    
    public function proveedor() {
        $this->view('compra/proveedor', [
            'title' => 'Proveedores',
            'activePage' => 'proveedor',
            'openMenu' => 'compra',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css'
            ],
            'vendors' => ['assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js','assets/vendor/libs/select2/select2.js'],
            'scripts' => ['assets/js/app-proveedor-lista.js'],
            'permisos' => $_SESSION['permisos']
        ]);
    }
}