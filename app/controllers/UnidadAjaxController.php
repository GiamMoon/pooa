<?php
class UnidadAjaxController extends Controller {
    public function listar() {
        $model = $this->model("UnidadModel");
        $unidades = $model->listarUnidades();

        $data = [];
        foreach ($unidades as $unidad) {
            $data[] = [
                'id_tipo_unidad'    =>$unidad['id_tipo_unidad'],
                'nombre'            =>$unidad['nombre'],
                'abreviatura'       =>$unidad['abreviatura'],
                'descripcion'       =>$unidad['descripcion'],
                'activo'            =>$unidad['activo'] === 1 ? 1 : 2
            ];
        }
        echo json_encode(["data" => $data]);
    }


    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = mb_strtoupper(trim($_POST['nombre'] ?? ''));
            $abreviatura = mb_strtoupper(trim($_POST['abreviatura'] ?? ''));
            $codigo_sunat = mb_strtoupper(trim($_POST['codigo_sunat'] ?? ''));
            $descripcion = $_POST['descripcion'] ?? '';

            //Validación
            if (!$nombre || !$abreviatura || !$codigo_sunat || !$descripcion) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
                return;
            }

            $model = $this->model('UnidadModel');
            $resultado = $model->registrarUnidad($nombre, $abreviatura, $codigo_sunat, $descripcion);
            echo json_encode(['success' => $resultado]);
        }
    }

    public function obtener() {
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $id = (int)$_GET['id'];
        $model = $this->model('UnidadModel');
        $unidad = $model->obtenerDetalleUnidad($id);

        echo json_encode($unidad);
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_tipo_unidad'] ?? null;
            $nombre = mb_strtoupper(trim($_POST['nombre'] ?? ''));
            $abreviatura = mb_strtoupper(trim($_POST['abreviatura'] ?? ''));
            $codigo_sunat = mb_strtoupper(trim($_POST['codigo_sunat'] ?? ''));
            $descripcion = $_POST['descripcion'] ?? '';

            //Validación
            if (!$id || !$nombre || !$abreviatura || !$codigo_sunat || !$descripcion) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
                return;
            }

            $model = $this->model('UnidadModel');
            $resultado = $model->actualizarDatos($id,$nombre, $abreviatura, $codigo_sunat, $descripcion);
            echo json_encode(['success' => $resultado]);
        }
    }

    public function eliminar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['id_tipo_unidad'])) {
                echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
                return;
            }

            $id = $_POST['id_tipo_unidad'];
            $model = $this->model('UnidadModel');
            $resultado = $model->eliminarUnidad($id);

            echo json_encode(['success' => $resultado]);
        }
    }

    public function listar_unidades() {
        $model = $this->model("UnidadModel");
        $unidades = $model->listarUnidadesActivas();
        echo json_encode($unidades);        
    }


    public function validad_unidad() {
        try {
            $unidad = $_GET['nombre'] ?? '';
            $idUnidad = $_GET['id_tipo_unidad'] ?? null;

            if (!$unidad) {
                echo json_encode(['valid' => false, 'error' => 'Unidad no recibida ⚠️']);
                return;
            }
            
            $model = $this->model('UnidadModel');
            $existe = $model->existeunidad($unidad,$idUnidad);
            echo json_encode(['valid' => $existe > 0]);
        } catch (\Throwable $e) {
            echo json_encode(['valid' => false, 'error' => $e->getMessage()]);
        }
    }
}