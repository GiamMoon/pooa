<?php
class ProveedorAjaxController extends Controller {
    /* TABLA: Defecto*/
    public function listar(){
        $model = $this->model('ProveedorModel');
        $proveedores = $model->listarProveedores();

        $data = [];
        foreach ($proveedores as $proveedor) {
            $data[] = [            
                'id_proveedor'      => $proveedor['id_proveedor'],
                'razon_social'      => $proveedor['razon_social'],                
                'ruc'               => $proveedor['ruc'],
                'Ubicacion'         => $proveedor['Ubicacion'],
                'estado_sunat'      => $proveedor['estado_sunat'],
                'condicion_sunat'   => $proveedor['condicion_sunat'],
                'activo'            => $proveedor['activo'] === 1 ? 1 : 2
            ];
        }
        echo json_encode(["data" => $data]);
    }



    /* ACCION: Registrar*/
    public function registrar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $ruc = $_POST['ruc'] ?? '';
      $razon_social = $_POST['razon_social'] ?? '';
      $direccion = $_POST['direccion'] ?? '';
      $departamento = $_POST['departamento'] ?? '';
      $provincia = $_POST['provincia'] ?? '';
      $distrito = $_POST['distrito'] ?? '';
      $ubigeo = $_POST['ubigeo'] ?? '';
      $telefono = empty($_POST['telefono']) ? null : $_POST['telefono'];
      $correo = empty($_POST['correo']) ? null : $_POST['correo'];
      $contacto = empty($_POST['contacto']) ? null : $_POST['contacto'];
      $estado_sunat = $_POST['estado_sunat'] ?? '';
      $condicion_sunat = $_POST['condicion_sunat'] ?? '';
      $es_agente_retencion = isset($_POST['es_agente_retencion']) ? (int)$_POST['es_agente_retencion'] : 0;

      // Validación básica
      if (
        !$ruc || !$razon_social || !$direccion || !$departamento || !$provincia ||
        !$distrito || !$ubigeo
      ) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
        return;
      }

      $model = $this->model('ProveedorModel');
      $resultado = $model->registrarProveedor(
        $ruc, $razon_social, $direccion,
        $departamento, $provincia, $distrito,
        $ubigeo, $telefono, $correo, $contacto,
        $estado_sunat, $condicion_sunat, $es_agente_retencion
      );

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
        $model = $this->model('ProveedorModel');
        $proveedor = $model->obtenerDetalleProveedores($id);

        echo json_encode($proveedor);
    }
    /* ACCION: 'Eliminar'*/
    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['id_proveedor'])) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            return;
        }

        $id = $_POST['id_proveedor'];
        $model = $this->model('ProveedorModel');
        $resultado = $model->eliminarProveedor($id);

        echo json_encode(['success' => $resultado]);
        }
    }
    /* ACCION: Editar*/
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idProveedor = $_POST['id_proveedor'] ?? 0;
            $ruc = $_POST['ruc'] ?? '';
            $razon_social = $_POST['razon_social'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $departamento = $_POST['departamento'] ?? '';
            $provincia = $_POST['provincia'] ?? '';
            $distrito = $_POST['distrito'] ?? '';
            $ubigeo = $_POST['ubigeo'] ?? '';
            $telefono = empty($_POST['telefono']) ? null : $_POST['telefono'];
            $correo = empty($_POST['correo']) ? null : $_POST['correo'];
            $contacto = empty($_POST['contacto']) ? null : $_POST['contacto'];
            $estado_sunat = $_POST['estado_sunat'] ?? '';
            $condicion_sunat = $_POST['condicion_sunat'] ?? '';
            $es_agente_retencion = isset($_POST['es_agente_retencion']) ? (int)$_POST['es_agente_retencion'] : 0;

            // Validación básica (ajusta según necesidad)
            if (
                !$idProveedor || !$ruc || !$razon_social || !$direccion || !$departamento ||
                !$provincia || !$distrito || !$ubigeo
            ) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
                return;
            }     

        $model = $this->model('ProveedorModel');
        $resultado = $model->actualizarProveedor(
            $idProveedor,$ruc, $razon_social, $direccion,
            $departamento, $provincia, $distrito,
            $ubigeo, $telefono, $correo, $contacto,
            $estado_sunat, $condicion_sunat, $es_agente_retencion
        );

        echo json_encode(['success' => $resultado]);
        }
    }

    public function validar_proveedor() {
        try {            
            $ruc = $_GET['ruc'] ?? '';            

            if (!$ruc) {
                echo json_encode(['valid' => false, 'error' => 'RUC no recibido ⚠️']);
                return;
            }
            
            $model = $this->model('ProveedorModel');
            $existe = $model->existeProveedor($ruc);
            echo json_encode(['valid' => $existe > 0]);
        } catch (\Throwable $e) {
            echo json_encode(['valid' => false, 'error' => $e->getMessage()]);
        }
    }

    public function validar_correo_proveedor() {
        try {
            $correo = $_GET['correo'] ?? '';
            $idProveedor = $_GET['id_proveedor'] ?? null;

            if (!$correo) {
                echo json_encode(['valid' => false, 'error' => 'Correo no recibido ⚠️']);
                return;
            }
            
            $model = $this->model('ProveedorModel');
            $existe = $model->existeCorreoProv($correo,$idProveedor); //  corregido
            echo json_encode(['valid' => $existe > 0]);
        } catch (\Throwable $e) {
            echo json_encode(['valid' => false, 'error' => $e->getMessage()]);
        }
    }      
}