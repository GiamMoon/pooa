<?php

class CompraAjaxController extends Controller
{
    public function listar()
    {
        $model = $this->model('CompraModel');
        $compras = $model->listarCompras();

        $data = [];
        foreach ($compras as $compra) {
            $data[] = [
                'id_compra'        => $compra['id_compra'],
                'codigo_compra'    => $compra['codigo_compra'],
                'nombre_proveedor' => $compra['nombre_proveedor'],
                'fecha_registro'   => $compra['fecha_registro'],
                'total'            => $compra['total'],
                'activo'           => $compra['activo']
            ];
        }
        echo json_encode(["data" => $data]);
    }

    public function obtener()
    {
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
        $compra['productos'] = json_decode($compra['productos'], true);
        echo json_encode(['success' => true, 'data' => $compra]);
    }

    public function listar_proveedores()
    {
        $model = $this->model('CompraModel');
        $proveedores = $model->listarProveedoresActivos();
        echo json_encode($proveedores);
    }

    public function listar_productos()
    {
        $model = $this->model('CompraModel');
        $productos = $model->listarProductosActivos();
        echo json_encode($productos);
    }

    public function registrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idProveedor = $_POST['id_proveedor'] ?? null;
            $detalleJson = $_POST['detalle_compra'] ?? '[]';
            $detalle = json_decode($detalleJson, true);
            $id_usuario = $_SESSION['id_usuario'];

            $id_ubicacion_destino = $_POST['id_ubicacion_destino'] ?? null;
            $tipo_ubicacion_destino = $_POST['tipo_ubicacion_destino'] ?? null;

            if (!$idProveedor || empty($detalle) || !$id_ubicacion_destino || !$tipo_ubicacion_destino) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos (Proveedor, Destino o Productos).']);
                return;
            }

            $rolesPermitidos = [1, 2]; // Administrador y Encargado Compras
            $id_sucursal = null;
            if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $rolesPermitidos)) {
                if (empty($_POST['id_sucursal'])) {
                    echo json_encode(['success' => false, 'message' => 'Como administrador, debe seleccionar una sucursal.']);
                    return;
                }
                $id_sucursal = $_POST['id_sucursal'];
            } else {
                if (empty($_SESSION['id_sucursal'])) {
                    echo json_encode(['success' => false, 'message' => 'Su usuario no tiene una sucursal asignada.']);
                    return;
                }
                $id_sucursal = $_SESSION['id_sucursal'];
            }
            
            $total = 0;
            foreach ($detalle as $item) {
                $total += floatval($item['precio']) * intval($item['cantidad']);
            }

            $model = $this->model('CompraModel');
            
            $idCompra = $model->registrarCompra($id_usuario, $idProveedor, $id_sucursal, $total, $detalle, $id_ubicacion_destino, $tipo_ubicacion_destino);

            if ($idCompra) {
                echo json_encode(['success' => true, 'message' => 'Compra registrada correctamente', 'id_compra' => $idCompra]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ocurrió un error al registrar la compra.']);
            }
        }
    }

    public function listarDestinosPorSucursal($id_sucursal)
    {
        if (empty($id_sucursal)) {
            echo json_encode([]);
            return;
        }
        $model = $this->model('CompraModel');
        $ubicaciones = $model->listarDestinosPorSucursal($id_sucursal);
        
        header('Content-Type: application/json');
        echo json_encode($ubicaciones);
    }
    
    public function listarParaRecepcion()
    {
        $model = $this->model('CompraModel');
        $compras = $model->listarComprasParaRecepcion(); 
        echo json_encode(["data" => $compras]);
    }

    public function obtenerDetalleParaRecepcion()
    {
        if (!isset($_GET['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            return;
        }
        $id = (int)$_GET['id'];
        $model = $this->model('CompraModel');
        $detalle = $model->obtenerDetalleConPendientes($id);

        if (!$detalle) {
            echo json_encode(['success' => false, 'message' => 'Compra no encontrada o sin productos.']);
            return;
        }
        
        echo json_encode(['success' => true, 'data' => $detalle]);
    }

public function procesarRecepcionMejorada()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }

        $id_compra = $_POST['id_compra'] ?? null;
        $productos_recibidos_json = $_POST['productos_recibidos'] ?? '[]';
        $id_usuario = $_SESSION['id_usuario'];

        $model = $this->model('CompraModel');
        $compra_original = $model->obtenerDetalleCompra($id_compra);
        $id_sucursal = $compra_original['id_sucursal'];

        $evidencias_por_producto = [];
        foreach ($_FILES as $key => $file) {
            if (strpos($key, 'evidencia_producto_') === 0) {
                $id_producto = str_replace('evidencia_producto_', '', $key);
                $ruta_evidencia = $this->guardarArchivoEvidencia($file, $id_compra, $id_producto);
                if ($ruta_evidencia) {
                    $evidencias_por_producto[$id_producto] = $ruta_evidencia;
                }
            }
        }
        $evidencias_por_producto_json = json_encode($evidencias_por_producto);
        
        try {
            $exito = $model->registrarRecepcionMejorada($id_compra, $id_sucursal, $id_usuario, $productos_recibidos_json, $evidencias_por_producto_json);
            echo json_encode(['success' => true, 'message' => 'Recepción registrada y stock actualizado con éxito.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error de BD: ' . $e->getMessage()]);
        }
    }

    private function guardarArchivoEvidencia($file, $id_compra, $id_producto) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $directorio_destino = 'uploads/compras/evidencias_producto/';
        if (!is_dir($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }
        $nombre_archivo = "compra_{$id_compra}_prod_{$id_producto}_" . uniqid() . '_' . basename($file['name']);
        $ruta_destino = $directorio_destino . $nombre_archivo;
        
        if (move_uploaded_file($file['tmp_name'], $ruta_destino)) {
            return $ruta_destino;
        }
        return null;
    }
}