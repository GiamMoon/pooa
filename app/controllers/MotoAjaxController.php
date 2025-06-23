<?php
class MotoAjaxController extends Controller {
    /* TABLA: Defecto*/
    public function listar(){
        $model = $this->model('MotoModel');
        $motos = $model->listarMotos();

        $data = [];
        foreach ($motos as $moto) {
            $data[] = [            
                'id_moto'          => $moto['id_moto'],
                'nombre_marca'      => $moto['nombre_marca'],                
                'total_modelos'     => (int)$moto['total_modelos'],
                'modelos_activos'   => (int)$moto['modelos_activos'],                
                'activo'            => $moto['activo'] === 1 ? 1 : 2
            ];
        }
        echo json_encode(["data" => $data]);
    }


    /* ACCION: Registrar*/
    public function registrar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = mb_strtoupper(trim($_POST['nombre'] ?? ''));            

            //validación básica
            if (!$nombre) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
                return;
            }
            
            $model = $this->model('MotoModel');
            $resultado = $model->registrarMoto($nombre);
            echo json_encode(['success' => $resultado]);
        }
    }
    /* ACCION: Visualizar*/
    public function obtener() {
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $id = (int)$_GET['id'];
        $model = $this->model('MotoModel');
        $moto = $model->obtenerDetalleMoto($id);

        echo json_encode($moto);
    }
    /* ACCION: 'Eliminar'*/
    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['id_moto'])) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            return;
        }

        $id = $_POST['id_moto'];
        $model = $this->model('MotoModel');
        $resultado = $model->eliminarMoto($id);

        echo json_encode(['success' => $resultado]);
        }
    }
    /* ACCION: Editar*/
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_moto'];
            $nombre = mb_strtoupper(trim($_POST['nombre']));    

            $model = $this->model('MotoModel');
            $resultado = $model->actualizarDatos($id, $nombre);

            echo json_encode(['success' => $resultado]);
        }
    }
    
    
    /*VERIFICAR*/    
    public function validar_moto() {
        try {            
            $moto = $_GET['nombre'] ?? '';
            $idMoto = $_GET['id_moto'] ?? null;

            if (!$moto) {
                echo json_encode(['valid' => false, 'error' => 'Marca de moto no recibida ⚠️']);
                return;
            }
            
            $model = $this->model('MotoModel');
            $existe = $model->existeMoto($moto,$idMoto);
            echo json_encode(['valid' => $existe > 0]);
        } catch (\Throwable $e) {
            echo json_encode(['valid' => false, 'error' => $e->getMessage()]);
        }
    }    
}