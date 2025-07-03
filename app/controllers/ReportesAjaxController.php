<?php

class ReportesAjaxController extends Controller
{

    public function index()
    {
    }

    public function generarReporteMovimientos()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fecha_inicio = $_POST['fecha_inicio'] ?? null;
            $fecha_fin = $_POST['fecha_fin'] ?? null;

            if (empty($fecha_inicio) || empty($fecha_fin)) {
                echo json_encode(["data" => []]); 
                return;
            }

            $model = $this->model('ReportesModel');
            $reporte = $model->generarReporteMovimientos($fecha_inicio, $fecha_fin);
            
            echo json_encode(["data" => $reporte]);
        }
    }
}