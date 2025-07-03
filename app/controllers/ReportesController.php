<?php

class ReportesController extends Controller
{

    public function movimientos()
    {
        $this->view('reportes/movimientos', [ 
            'title'      => 'Reporte de Movimientos',
            'activePage' => 'reporte_movimientos', 
            'openMenu'   => 'reportes',
            'styles'     => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/flatpickr/flatpickr.css'
            ],
            'vendors'    => [
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
                'assets/vendor/libs/flatpickr/flatpickr.js', 
                'assets/vendor/libs/flatpickr/l10n/es.js'
            ],
            'scripts'    => ['assets/js/app-reporte-movimientos.js'], 
            'permisos'   => $_SESSION['permisos'] ?? []
        ]);
    }
}