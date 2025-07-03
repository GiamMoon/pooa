<?php
class EmpresaAjaxController extends Controller {

    public function listar(){
        $model = $this->model('EmpresaModel');
        $empresas = $model->listarEmpresa();

        $data = [];
        foreach ($empresas as $empresa) {
            $data[] = [            
                'id_empresa'        => $empresa['id_empresa'],
                'razon_social'      => $empresa['razon_social'],                
                'ruc'               => $empresa['ruc'],
                'direccion'          => $empresa['direccion']                
            ];
        }
        echo json_encode(["data" => $data]);
    }

    public function obtener() {
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $id = (int)$_GET['id'];
        $model = $this->model('EmpresaModel');
        $empresa = $model->obtenerDetalleEmpresa($id);

        echo json_encode($empresa);
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_empresa'];
            $razonsocial = $_POST['razon_social'];            
            $ruc = $_POST['ruc'];
            $direccion = $_POST['direccion'];
            $dep = $_POST['departamento'];
            $prov = $_POST['provincia'];    
            $dis = $_POST['distrito'];                        

            //validación básica
            if (!$razonsocial || !$ruc || !$direccion || !$dep || !$prov || !$dis) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
                return;
            }                        

            $model = $this->model('EmpresaModel');
            $resultado = $model->actualizarDatos($id, $razonsocial, $ruc, $direccion,$dep,$prov,$dis);

            echo json_encode(['success' => $resultado]);
        }
    }
}