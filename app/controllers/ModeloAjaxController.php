<?php
class ModeloAjaxController extends Controller {

    public function listar() {
        $model = $this->model('ModeloModel');
        $modelos = $model->listarModelos();

        $data = [];
        foreach ($modelos as $modelo) {
            $data[] = [            
                'id_modelo' => $modelo['id_modelo'],
                'nombre' => $modelo['nombre'],
                'anio' => $modelo['anio'],
                'total_productos' => (int) $modelo['total_productos'],                
                'activo' => $modelo['activo'] === 1 ? 1 : 2
            ];
        }
        echo json_encode(["data" => $data]);
    }


    public function listar_motos() {
        $model = $this->model('ModeloModel');
        $motos = $model->listarMotosActivas();
        echo json_encode($motos);
    }

    public function listar_modelos() {
        $model = $this->model('ModeloModel');
        $modelos = $model->listarModelosActivos();
        echo json_encode($modelos);
    }

    
    /* ACCIÓN: Registrar */
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = mb_strtoupper(trim($_POST['nombre'] ?? ''));
            $anio = $_POST['anio'] ?? null;
            $id_moto = $_POST['id_moto'] ?? null;

            if (!$nombre || !$anio || !$id_moto) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
                return;
            }

            $model = $this->model('ModeloModel');
            $resultado = $model->registrarModelo($nombre, $anio, $id_moto);

            echo json_encode(['success' => $resultado]);
        }
    }

    /* ACCIÓN: Obtener detalle */
    public function obtener() {
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $id = (int)$_GET['id'];
        $model = $this->model('ModeloModel');
        $modelo = $model->obtenerDetalleModelo($id);

        echo json_encode($modelo);
    }

    /* ACCIÓN: Actualizar */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_modelo'] ?? null;
            $nombre = mb_strtoupper(trim($_POST['nombre'] ?? ''));
            $anio = $_POST['anio'] ?? null;
            $id_moto = $_POST['id_moto'] ?? null;

            if (!$id || !$nombre || !$anio || !$id_moto) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos.']);
                return;
            }

            $model = $this->model('ModeloModel');
            $resultado = $model->actualizarDatos($id, $nombre, $anio, $id_moto);

            echo json_encode(['success' => $resultado]);
        }
    }

    /* ACCIÓN: Eliminar / Cambiar estado */
    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_modelo'] ?? null;

            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
                return;
            }

            $model = $this->model('ModeloModel');
            $resultado = $model->eliminarModelo($id);

            echo json_encode(['success' => $resultado]);
        }
    }

    /* VERIFICACIÓN: Nombre duplicado */
    public function validar_modelo() {
        try {
            $nombre = $_GET['nombre'] ?? '';
            $idModelo = $_GET['id_modelo'] ?? null;

            if (!$nombre) {
                echo json_encode(['valid' => false, 'error' => 'Nombre no recibido ⚠️']);
                return;
            }

            $model = $this->model('ModeloModel');
            $existe = $model->existeModelo($nombre, $idModelo);
            echo json_encode(['valid' => $existe > 0]);
        } catch (\Throwable $e) {
            echo json_encode(['valid' => false, 'error' => $e->getMessage()]);
        }
    }



}