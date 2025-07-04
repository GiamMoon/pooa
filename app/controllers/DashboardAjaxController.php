<?php

require_once '../app/core/SecureController.php';

class DashboardAjaxController extends SecureController
{
    private ?DashboardModel $dashboardModel = null;

    public function __construct()
    {
        parent::__construct();
        $this->dashboardModel = $this->model('DashboardModel');
    }

    private function getFechasFromRequest(): ?array
    {
        $fecha_inicio_str = $_POST['fecha_inicio'] ?? null;
        $fecha_fin_str = $_POST['fecha_fin'] ?? null;

        if (!$fecha_inicio_str || !$fecha_fin_str) {
            return null;
        }
        
        $fecha_inicio = DateTime::createFromFormat('Y-m-d', $fecha_inicio_str);
        $fecha_fin = DateTime::createFromFormat('Y-m-d', $fecha_fin_str);

        if (!$fecha_inicio || !$fecha_fin || $fecha_inicio->format('Y-m-d') !== $fecha_inicio_str || $fecha_fin->format('Y-m-d') !== $fecha_fin_str) {
             return null;
        }

        return [
            'inicio' => $fecha_inicio_str,
            'fin' => $fecha_fin_str
        ];
    }
    
    public function obtenerDatosDashboard()
    {
        $this->setJsonResponse();

        try {
            $fechas = $this->getFechasFromRequest();

            if (!$fechas) {
                echo json_encode(['error' => 'Fechas inválidas o no proporcionadas.']);
                return;
            }

            $datos = [];
            $datos['kpis'] = $this->dashboardModel->getKpis($fechas['inicio'], $fechas['fin']);
            $datos['ventasPeriodo'] = $this->dashboardModel->getVentasPorPeriodo($fechas['inicio'], $fechas['fin']);
            $datos['topProductos'] = $this->dashboardModel->getTopProductosVendidos($fechas['inicio'], $fechas['fin']);
            $datos['ventasSucursal'] = $this->dashboardModel->getVentasPorSucursal($fechas['inicio'], $fechas['fin']);
            $datos['comprasProveedor'] = $this->dashboardModel->getComprasPorProveedor($fechas['inicio'], $fechas['fin']);
            $datos['listaProductosCriticos'] = $this->dashboardModel->getListaProductosCriticos();

            echo json_encode($datos);

        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error general del servidor: ' . $e->getMessage()]);
        }
    }
}
