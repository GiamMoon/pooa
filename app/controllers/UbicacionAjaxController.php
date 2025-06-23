<?php
class UbicacionAjaxController extends Controller {
    public function listar() {
        $model = $this->model("UbicacionModel");
        $ubicaciones = $model->listarUbicaciones();

        $data = [];
        foreach ($ubicaciones as $ubicacion) {
            $data[] = [
                'id_ubicacion'    =>$ubicacion['id_ubicacion'],
                'direccion'            =>$ubicacion['direccion'],
                'telefono'       =>$ubicacion['telefono'],
                'tipo_ubicacion'       =>$ubicacion['tipo_ubicacion'],
                'activo'            =>$ubicacion['activo'] === 1 ? 1 : 2
            ];
        }
        echo json_encode(["data" => $data]);
    }




    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $direccion = mb_strtoupper(trim($_POST['direccion'] ?? ''));
            $telefono = trim($_POST['telefono'] ?? '');
            $tipo = strtoupper(trim($_POST['tipo_ubicacion'] ?? ''));

            if (!$direccion || !$telefono || !$tipo) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
                return;
            }

            $model = $this->model('UbicacionModel');
            $resultado = $model->registrarUbicacion($direccion, $telefono, $tipo);
            echo json_encode(['success' => $resultado]);
        }
    }

    public function obtener() {
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $id = (int)$_GET['id'];
        $model = $this->model('UbicacionModel');
        $ubicacion = $model->obtenerDetalleUbicacion($id);

        echo json_encode($ubicacion);
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_ubicacion'] ?? null;
            $direccion = mb_strtoupper(trim($_POST['direccion'] ?? ''));
            $telefono = trim($_POST['telefono'] ?? '');
            $tipo = strtoupper(trim($_POST['tipo_ubicacion'] ?? ''));

            if (!$id || !$direccion || !$telefono || !$tipo) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
                return;
            }

            $model = $this->model('UbicacionModel');
            $resultado = $model->actualizarUbicacion($id, $direccion, $telefono, $tipo);
            echo json_encode(['success' => $resultado]);
        }
    }

    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['id_ubicacion'])) {
                echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
                return;
            }

            $id = $_POST['id_ubicacion'];
            $model = $this->model('UbicacionModel');
            $resultado = $model->eliminarUbicacion($id);

            echo json_encode(['success' => $resultado]);
        }
    }

    public function validar_ubicacion() {
        try {
            $direccion = $_GET['direccion'] ?? '';
            $idUbicacion = $_GET['id_ubicacion'] ?? null;

            if (!$direccion) {
                echo json_encode(['valid' => false, 'error' => 'Dirección no recibida ⚠️']);
                return;
            }

            $model = $this->model('UbicacionModel');
            $existe = $model->existeUbicacion($direccion, $idUbicacion);
            echo json_encode(['valid' => $existe > 0]);
        } catch (\Throwable $e) {
            echo json_encode(['valid' => false, 'error' => $e->getMessage()]);
        }
    }
}