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
                'modelos_compatibles'   => $producto['modelos_compatibles'],
                'precio_venta'          => (int)$producto['precio_venta'],
                'activo'                => $producto['activo'] === 1 ? 1 : 2
            ];
        }
        echo json_encode(["data" => $data]);
    }

    public function registrar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $idCategoria = $_POST['id_categoria'] ?? null;
            $idMarca = $_POST['id_marca'] ?? null;
            $idUnidad = $_POST['id_tipo_unidad'] ?? null;
            $nombre = mb_strtoupper(trim($_POST['nombre'] ?? ''));
            $imagen = $_POST['url_imagen'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;
            $pVenta = $_POST['precio_venta'] ?? null;
            $cantidadMin = $_POST['cantidad_min'] ?? null;
            $cantidadMax = $_POST['cantidad_max'] ?? null;
            $asignaciones = $_POST['json_modelos'] ?? '[]';

            //validación básica
            if (!$nombre || !$idCategoria || !$idMarca || !$idUnidad || !$cantidadMin || !$cantidadMax || !$asignaciones) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
                return;
            }

            $model = $this->model('ProductoModel');
            $resultado = $model->registrarProducto($idCategoria,$idMarca,$idUnidad,$nombre,$descripcion,$imagen,$pVenta,$cantidadMin,$cantidadMax, $asignaciones);
            echo json_encode(['success' => $resultado]);
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
}