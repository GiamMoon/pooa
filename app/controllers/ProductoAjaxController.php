<?php
class ProductoAjaxController extends Controller {   
    public function listar(){
        $model = $this->model('ProductoModel');
        $productos = $model->listarProductos();

        $data = [];
        foreach ($productos as $producto) {
            $data[] = [            
                'id_producto'           => $producto['id_producto'],
                'codigo_producto'       => $producto['codigo_producto'],
                'url_imagen'            => $producto['url_imagen'],
                'nombre_producto'       => $producto['nombre_producto'],
                'nombre_categoria'      => $producto['nombre_categoria'],
                'nombre_marca'          => $producto['nombre_marca'],                
                'precio_venta'          => (float)$producto['precio_venta'],
                'activo'                => $producto['activo'] === 1 ? 1 : 2
            ];
        }
        echo json_encode(["data" => $data]);
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar datos básicos
                $idCategoria = $_POST['id_categoria'] ?? null;
                $idMarca = $_POST['id_marca'] ?? null;
                $idUnidad = $_POST['id_tipo_unidad'] ?? null;
                $nombre = mb_strtoupper(trim($_POST['nombre'] ?? ''));
                $descripcion = $_POST['descripcion'] ?? null;
                $pVenta = $_POST['precio_venta'] ?? 0.00;

                if (empty($nombre) || empty($idCategoria) || empty($idMarca) || empty($idUnidad)) {
                    throw new Exception('Faltan datos obligatorios');
                }

                $model = $this->model('ProductoModel');

                // Procesar imagen si existe
                $imagenNombre = null;
                $hasImage = isset($_FILES['url_imagen']) && $_FILES['url_imagen']['error'] === UPLOAD_ERR_OK;

                if ($hasImage) {
                    $file = $_FILES['url_imagen'];
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    
                    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        throw new Exception('Formato de imagen no válido. Use JPG, PNG o WEBP.');
                    }
                }

                // Registrar producto primero (sin imagen)
                $result = $model->registrarProducto(
                    $idCategoria, $idMarca, $idUnidad, $nombre, $descripcion, null, $pVenta
                );

                if (!$result['success'] || empty($result['codigo'])) {
                    throw new Exception('Error al registrar el producto');
                }

                $codigoGenerado = $result['codigo'];

                // Procesar imagen después de registrar el producto
                if ($hasImage) {
                    $uploadDir = __DIR__ . '/../../public/uploads/products/';
                    
                    // Crear directorio si no existe
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $filename = $codigoGenerado . '.' . $ext;
                    $targetPath = $uploadDir . $filename;

                    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                        throw new Exception('Error al guardar la imagen');
                    }

                    // Actualizar producto con el nombre de la imagen
                    if (!$model->actualizarImagenProducto($codigoGenerado, $filename)) {
                        throw new Exception('Error al actualizar la imagen del producto');
                    }
                }

                echo json_encode(['success' => true, 'codigo' => $codigoGenerado]);
                
            } catch (Exception $e) {
                error_log("Error en registro de producto: " . $e->getMessage());
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }


    
    
    
    
    public function listar_motos_modelos(){
        $model = $this->model('ProductoModel');
        $productos = $model->listarMotosconModelos();
        
        $datos = [];
        foreach ($productos as $producto) {
            $id = $producto['id_moto'];
            if (!isset($datos[$id])) {
                $datos[$id] = [
                    'id_moto' => $id,
                    'nombre_marca' => $producto['nombre_marca'],
                    'modelos' => []
                ];
            }
            $datos[$id]['modelos'][] = [
                'id_modelo' => $producto['id_modelo'],
                'nombre' => $producto['nombre'],
                'anio' => $producto['anio']
            ];
        }

        echo json_encode(array_values($datos));
    }
    private function guardarimagenProducto($file, $codigo_producto) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $directorio_destino = 'uploads/products/';
        if (!is_dir($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }
        $nombre_archivo = "{$codigo_producto}" . uniqid() . '_' . basename($file['name']);
        $ruta_destino = $directorio_destino . $nombre_archivo;
        
        if (move_uploaded_file($file['tmp_name'], $ruta_destino)) {
            return $ruta_destino;
        }
        return null;
    }
}