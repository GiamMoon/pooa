<?php
class AlmacenAjaxController extends Controller {

    public function listar(){
        $model = $this->model('AlmacenModel');
        $almacenes = $model->listarAlmacenesTB();

        $data = [];
        foreach ($almacenes as $almacen) {
            $data[] = [            
                'id_almacen'            => $almacen['id_almacen'],
                'nombre_comercial'      => $almacen['nombre_comercial'],                
                'direccion'             => $almacen['direccion'],
                'telefono'              => $almacen['telefono'],
                'activo'                => $almacen['activo'] === 1 ? 1 : 2
            ];
        }
        echo json_encode(["data" => $data]);
    }

    public function registrar(){
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idSucursal = $_POST['id_sucursal'] ?? 0;
            $direccion = mb_strtoupper(trim($_POST['direccion'] ?? ''));
            $telefono = $_POST['telefono'] ?? null;
            $dep = trim($_POST['departamento'] ?? '');
            $prov = trim($_POST['provincia'] ?? '');
            $dis = trim($_POST['distrito'] ?? '');            
            
            //validación básica
            if (!$idSucursal || !$direccion || !$dep || !$prov || !$dis) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
                return;
            }                    

            $model = $this->model('AlmacenModel');
            $resultado = $model->registrarDatos($idSucursal,$direccion,$telefono,$dep,$prov,$dis);
            echo json_encode(['success' => $resultado]);
        }
    }
}