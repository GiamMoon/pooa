<?php
class CategoriaAjaxController extends Controller {
    /* TABLA: Defecto*/
    public function listar(){
        $model = $this->model('CategoriaModel');
        $categorias = $model->listarCategorias();

        $data = [];
        foreach ($categorias as $categoria) {
            $data[] = [            
                'id_categoria' => $categoria['id_categoria'],
                'nombre' => $categoria['nombre'],
                'descripcion' => $categoria['descripcion'],
                'total_productos' => (int) $categoria['total_productos'],                
                'activo' => $categoria['activo'] === 1 ? 1 : 2
            ];
        }
        echo json_encode(["data" => $data]);
    }

    /* ACCION: Registrar*/
    public function registrar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = mb_strtoupper(trim($_POST['nombre'] ?? ''));
            $descripcion = $_POST['descripcion'] ?? '';        

            //validación básica
            if (!$nombre || !$descripcion) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
                return;
            }
            
            $model = $this->model('CategoriaModel');
            $resultado = $model->registrarCategoria($nombre,$descripcion);
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
        $model = $this->model('CategoriaModel');
        $categoria = $model->obtenerDetalleCategoria($id);

        echo json_encode($categoria);
    }
    /* ACCION: 'Eliminar'*/
    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['id_categoria'])) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            return;
        }

        $id = $_POST['id_categoria'];
        $model = $this->model('CategoriaModel');
        $resultado = $model->eliminarCategoria($id);

        echo json_encode(['success' => $resultado]);
        }   
    }
    /* ACCION: Editar*/
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_categoria'];
            $nombre = mb_strtoupper(trim($_POST['nombre']));
            $descripcion = $_POST['descripcion'];

            $model = $this->model('CategoriaModel');
            $resultado = $model->actualizarDatos($id, $nombre, $descripcion);

            echo json_encode(['success' => $resultado]);
        }
    }
    

    public function listar_categorias(){
        $model = $this->model('CategoriaModel');
        $categorias = $model->listarCategoriasActivas();
        echo json_encode($categorias);
    }

    
    /*VERIFICAR*/    
    public function validar_categoria() {
        try {            
            $categoria = $_GET['nombre'] ?? '';
            $idCategoria = $_GET['id_categoria'] ?? null;

            if (!$categoria) {
                echo json_encode(['valid' => false, 'error' => 'Categoría no recibida ⚠️']);
                return;
            }
            
            $model = $this->model('CategoriaModel');
            $existe = $model->existeCategoria($categoria,$idCategoria);
            echo json_encode(['valid' => $existe > 0]);
        } catch (\Throwable $e) {
            echo json_encode(['valid' => false, 'error' => $e->getMessage()]);
        }
    }

}