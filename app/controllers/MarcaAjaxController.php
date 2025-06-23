<?php
class MarcaAjaxController extends Controller {
    /* TABLA: Defecto*/
    public function listar(){
        $model = $this->model('MarcaModel');
        $marcas = $model->listarMarcas();

        $data = [];
        foreach ($marcas as $marca) {
            $data[] = [            
                'id_marca'          => $marca['id_marca'],
                'nombre_marca'      => $marca['nombre_marca'],                
                'total_productos'     => (int)$marca['total_productos'],
                'productos_activos'   => (int)$marca['productos_activos'],                
                'activo'            => $marca['activo'] === 1 ? 1 : 2
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
            
            $model = $this->model('MarcaModel');
            $resultado = $model->registrarMarca($nombre);
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
        $model = $this->model('MarcaModel');
        $marca = $model->obtenerDetalleMarca($id);

        echo json_encode($marca);
    }
    /* ACCION: 'Eliminar'*/
    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['id_marca'])) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            return;
        }

        $id = $_POST['id_marca'];
        $model = $this->model('MarcaModel');
        $resultado = $model->eliminarMarca($id);

        echo json_encode(['success' => $resultado]);
        }
    }
    /* ACCION: Editar*/
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_marca'];
            $nombre = mb_strtoupper(trim($_POST['nombre']));    

            $model = $this->model('MarcaModel');
            $resultado = $model->actualizarDatos($id, $nombre);

            echo json_encode(['success' => $resultado]);
        }
    }
    


    public function listar_marcas() {
        $model = $this->model('MarcaModel');
        $marcas = $model->listarMarcasActivas();
        echo json_encode($marcas);
    }


    
    /*VERIFICAR*/    
    public function validar_marca() {
        try {            
            $marca = $_GET['nombre'] ?? '';
            $idMarca = $_GET['id_marca'] ?? null;

            if (!$marca) {
                echo json_encode(['valid' => false, 'error' => 'Marca no recibida ⚠️']);
                return;
            }
            
            $model = $this->model('MarcaModel');
            $existe = $model->existeMarca($marca,$idMarca);
            echo json_encode(['valid' => $existe > 0]);
        } catch (\Throwable $e) {
            echo json_encode(['valid' => false, 'error' => $e->getMessage()]);
        }
    }    
}