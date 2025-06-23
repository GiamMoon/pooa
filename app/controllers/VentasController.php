<?php

require_once "../app/models/PosModel.php";

class VentasController extends Controller {

    private $posModel;

    public function __construct() {
        $this->posModel = new PosModel();
    }

    public function index() {
    
    } 

    public function pos() {

        $productos = $this->posModel->getProductos();
        $categorias = $this->posModel->getCategorias();
        $comprobantes = $this->posModel->getComprobantes();
        $clientes = $this->posModel->getClientes();

        $this->view('ventas/pos', [
            'title' => 'VENTA POS',
            'activePage' => 'pos',
            'openMenu' => 'ventas',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css',
                'https://vs.ifonts.dev/2/if.css'
            ],
            'vendors' => [
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js' ,
                'assets/vendor/libs/select2/select2.js',
            ],
            'scripts' => ['assets/js/app-ecommerce-product-list.js'],
            'productos' => $productos,
            'categorias' => $categorias,
            'comprobantes' => $comprobantes,
            'clientes' => $clientes,
        ]);
    }
}