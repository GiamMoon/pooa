<?php
class CompraAjaxController extends Controller {
    public function listar() {
        $model = $this->model('CompraModel');
        $compras = $model->listarCompras();

        $data = [];
        foreach ($compras as $compra) {
            $data[] = [
                'id_compra'            => $compra['id_compra'],
                'codigo_compra'        => $compra['codigo_compra'],
                'nombre_proveedor'     => $compra['nombre_proveedor'],
                'fecha_registro'       => $compra['fecha_registro'],
                'total'                => $compra['total'],
                'activo'               => $compra['activo']
            ];
        }        
        echo json_encode(["data" => $data]);
    }

    public function obtener() {
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $id = (int)$_GET['id'];

        $model = $this->model('CompraModel');
        $compra = $model->obtenerDetalleCompra($id);

        if (!$compra) {
            echo json_encode(['error' => 'Compra no encontrada']);
            return;
        }

        // Decodificar productos JSON
        $compra['productos'] = json_decode($compra['productos'], true);

        echo json_encode(['success' => true, 'data' => $compra]);
    }
        

    public function listar_proveedores(){
        $model = $this->model('CompraModel');
        $proveedores = $model->listarProveedoresActivos();
        echo json_encode($proveedores);
    }

    public function listar_productos(){
        $model = $this->model('CompraModel');
        $productos = $model->listarProductosActivos();
        echo json_encode($productos);
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {            
            $idProveedor = $_POST['id_proveedor'] ?? null;
            $detalle = $_POST['detalle_compra'] ?? '[]';
            $detalle = json_decode($detalle, true);

            if (!$idProveedor || empty($detalle)) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Faltan datos requeridos'
                ]);
                return;
            }

            $total = 0;
            foreach ($detalle as $item) {
                if (!isset($item['precio']) || !isset($item['cantidad'])) continue;
                $total += floatval($item['precio']) * intval($item['cantidad']);
            }

            $model = $this->model('CompraModel');
            $idCompra = null;
            $success = $model->registrarCompra($idProveedor, $total, $idCompra);

            if (!$success || !$idCompra) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'No se pudo registrar la compra'
                ]);
                return;
            }

            // Registrar detalle
            foreach ($detalle as $item) {
                $model->registrarDetalleCompra(
                    $idCompra,
                    $item['id_producto'],
                    $item['precio'],
                    $item['cantidad']
                );
            }

            echo json_encode([
                'success' => true, 
                'message' => 'Compra registrada correctamente',
                'id_compra' => $idCompra
            ]);
        }
    }
}