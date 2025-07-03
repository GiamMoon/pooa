<?php

class InventarioAjaxController extends Controller
{
 
    public function index()
    {
    }

    public function listarMovimientos()
    {
        $model = $this->model('InventarioModel');
        $movimientos = $model->listarMovimientos();
        echo json_encode(["data" => $movimientos]);
    }

    /**
     * @param int $id_movimiento El ID del movimiento a consultar.
     */
    public function getMovimientoDetalle($id_movimiento)
    {
        $model = $this->model('InventarioModel');
        $detalle = $model->getMovimientoDetalle($id_movimiento);
        echo json_encode(['success' => true, 'data' => $detalle]);
    }

    public function listarStockActual()
    {
        $model = $this->model('InventarioModel');
        $stock = $model->consultarStockActual();
        echo json_encode(["data" => $stock]);
    }

    public function listarStockAgrupado()
    {
        $model = $this->model('InventarioModel');
        $stock = $model->consultarStockAgrupado();
        echo json_encode(["data" => $stock]);
    }

    public function getUbicacionesPorSucursal($id_sucursal)
    {
        $model = $this->model('InventarioModel');
        $ubicaciones = $model->getUbicacionesPorSucursal($id_sucursal);
        echo json_encode($ubicaciones);
    }

    public function getProductosConStock($origen_compuesto)
    {
        list($id_ubicacion, $tipo_ubicacion) = explode('_', $origen_compuesto);

        $model = $this->model('InventarioModel');
        $productos = $model->getProductosConStock($id_ubicacion, $tipo_ubicacion);
        echo json_encode($productos);
    }

        public function registrarTransferencia()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_sucursal = $_POST['id_sucursal'] ?? null;
            $origen_compuesto = $_POST['id_ubicacion_origen'] ?? null;
            $destino_compuesto = $_POST['id_ubicacion_destino'] ?? null;
            $productos_json = $_POST['productos_transferir'] ?? '[]';
            $observacion = $_POST['observacion'] ?? '';
            $id_usuario = $_SESSION['id_usuario'] ?? 1;

            if (empty($id_sucursal) || empty($origen_compuesto) || empty($destino_compuesto) || $productos_json === '[]') {
                echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos para la transferencia.']);
                return;
            }
            
            if ($origen_compuesto === $destino_compuesto) {
                echo json_encode(['success' => false, 'message' => 'La ubicación de origen y destino no pueden ser la misma.']);
                return;
            }

            list($id_ubicacion_origen, $tipo_ubicacion_origen) = explode('_', $origen_compuesto);
            list($id_ubicacion_destino, $tipo_ubicacion_destino) = explode('_', $destino_compuesto);

            $model = $this->model('InventarioModel');
            try {
                $model->registrarTransferencia(
                    $id_sucursal, 
                    $id_usuario, 
                    $id_ubicacion_origen, 
                    $tipo_ubicacion_origen,
                    $id_ubicacion_destino,
                    $tipo_ubicacion_destino,
                    $productos_json, 
                    $observacion
                );
                echo json_encode(['success' => true, 'message' => 'Transferencia registrada con éxito.']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error al registrar la transferencia: ' . $e->getMessage()]);
            }
        }
    }
}