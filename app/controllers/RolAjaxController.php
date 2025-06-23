<?php
class RolAjaxController extends Controller {
    private $model;

    public function listar()
    {
        header('Content-Type: application/json');
        $model = $this->model('RolModel');
        $roles = $model->listarRoles();

        $data = [];
        foreach ($roles as $rol) {
            $data[] = [
                'id_rol' => $rol['id_rol'],
                'nombre_rol' => $rol['nombre_rol'],
                'usuarios_asignados' => (int) $rol['usuarios_asignados'],
                'usuarios_activos' => (int) $rol['usuarios_activos'],
                'activo' => $rol['activo'] === 1 ? 1 : 2
            ];
        }
        echo json_encode(["data" => $data]);//, JSON_UNESCAPED_UNICODE
    }

    //

    public function listar_permisos()
    {
        header('Content-Type: application/json');
        $model = $this->model('RolModel');
        $data = $model->listarPermisos();
        echo json_encode($data);
    }

    public function listar_recursos()
    {
        header('Content-Type: application/json');
        $model = $this->model('RolModel');
        $data = $model->listarRecursos();
        echo json_encode($data);
    }

    public function registrar()
    {
        header('Content-Type: application/json');
        $nombre = mb_strtolower(trim($_POST['nombre'] ?? ''));
        $asignaciones = $_POST['asignaciones'] ?? '[]';

        if (!$nombre || !$asignaciones) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
            return;
        }

        $model = $this->model('RolModel');
        $resultado = $model->registrarRol($nombre, $asignaciones);
        echo json_encode(['success' => $resultado]);
    }

    public function detalle()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $idRol = $_GET['id_rol'] ?? 0;
            if (!$idRol) {
                echo json_encode(['success' => false, 'message' => 'ID de rol inválido']);
                return;
            }
            $model = $this->model('RolModel');
            $data = $model->detalleRol($idRol);
            echo json_encode(['success' => true, 'data' => $data]);
        }
    }

    public function actualizar()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idRol = $_POST['id_rol'] ?? 0;
            $nombre = mb_strtolower(trim($_POST['nombre'] ?? ''));            
            $asignacionesJson = $_POST['asignaciones'] ?? '[]';

            if (!$idRol || !$nombre) {
                echo json_encode(['success' => false, 'message' => 'ID de rol o nombre inválidos']);
                return;
            }

            // Validar JSON válido
            $asignaciones = json_decode($asignacionesJson, true);
            if (!is_array($asignaciones)) {
                echo json_encode(['success' => false, 'message' => 'Asignaciones inválidas']);
                return;
            }

            $model = $this->model('RolModel');
            $resultado = $model->actualizarRol($idRol, $nombre, $asignacionesJson);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Rol actualizado correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el rol']);
            }
        }
    }


    //Para eliminar
    public function eliminar_rol_invitado() {
        try {
            
            $input = json_decode(file_get_contents('php://input'), true);
            $idRol = $input['id_rol'] ?? null;
            if (!$idRol) {
                echo json_encode(['success' => false, 'message' => 'ID de rol no recibido']);
                return;
            }
            $model = $this->model('RolModel');
            $model->reasignarUsuariosAInvitado($idRol); // esto es seguro aunque no haya usuarios
            $success = $model->eliminarRol($idRol);
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error interno: '.$e->getMessage()]);
        }
    }

    public function eliminar_rol_reasignado() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $idRol = $input['id_rol'] ?? null;
            $nuevoRol = $input['nuevo_rol'] ?? null;
            if (!$idRol || !$nuevoRol) {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                return;
            }
            $model = $this->model('RolModel');
            $model->reasignarUsuariosAOtroRol($idRol, $nuevoRol);
            $success = $model->eliminarRol($idRol);
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error interno: '.$e->getMessage()]);
        }
    }

    public function eliminar_rol_personalizado() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $idRol = $input['id_rol'] ?? null;
            $usuarios = $input['usuarios'] ?? [];
            if (!$idRol || empty($usuarios)) {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                return;
            }
            $model = $this->model('RolModel');
            foreach ($usuarios as $u) {
                $model->reasignarRolPorUsuario($u['id_usuario'], $u['nuevo_rol']);
            }
            $success = $model->eliminarRol($idRol);
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error interno: '.$e->getMessage()]);
        }
    }

    public function contar_usuarios_rol() {
        $idRol = $_GET['id_rol'] ?? null;
        if (!$idRol) {
            echo json_encode(['total' => 0, 'activos' => 0]);
            return;
        }
        $model = $this->model('RolModel');
        $data = $model->contarUsuariosPorRol($idRol);
        echo json_encode($data ?: ['total' => 0, 'activos' => 0]);
    }

    public function listar_roles_excepto() {
        $ex1 = $_GET['ex1'] ?? 0;
        $ex2 = $_GET['ex2'] ?? 0;
        $model = $this->model('RolModel');
        $roles = $model->listarRolesExcepto($ex1, $ex2);
        echo json_encode($roles);
    }

    public function listar_usuarios_por_rol() {
        $idRol = $_GET['id_rol'] ?? null;
        $model = $this->model('RolModel');
        $usuarios = $model->listarUsuariosPorRol($idRol);
        echo json_encode($usuarios);
    }


    public function validar_rol() {
        try {            
            $nombre = $_GET['nombre'] ?? '';
            $idRol = $_GET['id_rol'] ?? null;

            if (!$nombre) {
                echo json_encode(['valid' => false, 'error' => 'Rol no recibido ⚠️']);
                return;
            }
            
            $model = $this->model('RolModel');
            $existe = $model->existeRol($nombre,$idRol);
            echo json_encode(['valid' => $existe > 0]);
        } catch (\Throwable $e) {
            echo json_encode(['valid' => false, 'error' => $e->getMessage()]);
        }
    }


}