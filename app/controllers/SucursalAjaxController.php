<?php
class SucursalAjaxController extends Controller {

    public function listar(){
        $model = $this->model('SucursalModel');
        $sucursales = $model->listarSucursalesTB();

        $data = [];
        foreach ($sucursales as $sucursal) {
            $data[] = [            
                'id_sucursal'           => $sucursal['id_sucursal'],
                'nombre_comercial'      => $sucursal['nombre_comercial'],                
                'direccion'             => $sucursal['direccion'],
                'usuarios_asignados'    => (int)$sucursal['usuarios_asignados'],
                'activo'                => $sucursal['activo'] === 1 ? 1 : 2
            ];
        }
        echo json_encode(["data" => $data]);
    }

    public function registrar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = mb_strtoupper(trim($_POST['nombre_comercial'] ?? ''));            
            $direccion = mb_strtoupper(trim($_POST['direccion'] ?? ''));
            $dep = trim($_POST['departamento'] ?? '');
            $prov = trim($_POST['provincia'] ?? '');
            $dis = trim($_POST['distrito'] ?? '');            
            
            //validación básica
            if (!$nombre || !$direccion || !$dep || !$prov || !$dis) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
                return;
            }                    

            $model = $this->model('SucursalModel');
            $resultado = $model->registrarDatos($nombre,$direccion,$dep,$prov,$dis);
            echo json_encode(['success' => $resultado]);
        }
    }

    public function obtener() {
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $id = (int)$_GET['id'];
        $model = $this->model('SucursalModel');
        $sucursal = $model->obtenerDetalle($id);

        echo json_encode($sucursal);
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_sucursal'];
            $nombre = mb_strtoupper(trim($_POST['nombre_comercial']));            
            $direccion = mb_strtoupper(trim($_POST['direccion']));
            $dep = $_POST['departamento'];
            $prov = $_POST['provincia'];
            $dis = $_POST['distrito'];                        

            //validación básica
            if (!$nombre || !$direccion || !$dep || !$prov || !$dis) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
                return;
            }
           
            $model = $this->model('SucursalModel');
            $resultado = $model->actualizarDatos($id, $nombre, $direccion, $dep,$prov,$dis);

            echo json_encode(['success' => $resultado]);
        }
    }

    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['id_sucursal'])) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            return;
        }

        $id = $_POST['id_sucursal'];
        $model = $this->model('SucursalModel');
        $resultado = $model->eliminarSucursalCompleto($id);

        echo json_encode(['success' => $resultado]);
        }
    }
    

}