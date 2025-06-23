<?php
class UsuarioAjaxController extends Controller{

    /* TABLA: Defecto*/
    public function listar(){
        $model = $this->model('UsuarioModel');
        $usuarios = $model->listarUsuarios();

        $data = [];
        foreach ($usuarios as $usuario) {
            $data[] = [            
                'id_usuario'        => $usuario['id_usuario'],
                'nombre_usuario'    => $usuario['nombre_usuario'],
                'ini_nombre'        => strtoupper($usuario['nombre_usuario'][0]),
                'correo'            => $usuario['correo'],
                'rol'               => $usuario['rol'],
                'activo'            => $usuario['activo'] === 1 ? 1 : 2
            ];
        }
        echo json_encode(["data" => $data]);
    }

    
    /* ACCION: Visualizar*/
    public function obtener() {
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $id = (int)$_GET['id'];
        $model = $this->model('UsuarioModel');
        $usuario = $model->obtenerDetalleUsuario($id);

        echo json_encode($usuario);
    }
    /* ACCION: Registrar*/
    public function registrar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $dni = $_POST['dni'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $usuario = mb_strtolower(trim($_POST['usuario'] ?? ''));
            $idRol = $_POST['id_rol'] ?? 0;
            $fechaLimite = $_POST['fecha_limite'] ?? null;

            //validación básica
            if (!$nombre || !$apellido || !$dni || !$direccion || !$telefono || !$correo || !$usuario || !$idRol) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
                return;
            }

            //Validar y normalizar fecha
            $fechaLimite = !empty($fechaLimite) ? date('Y-m-d H:i:s', strtotime($fechaLimite)) : null;

            //Hash contraseña
            $contrasena = password_hash($dni, PASSWORD_ARGON2ID);

            $model = $this->model('UsuarioModel');
            $resultado = $model->registrarUsuario($nombre,$apellido,$dni,$direccion,$telefono,$correo,$usuario,$contrasena,$idRol,$fechaLimite);
            echo json_encode(['success' => $resultado]);
        }
    }
    /* ACCION: 'Eliminar'*/
    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['id_usuario'])) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            return;
        }

        $id = $_POST['id_usuario'];
        $model = $this->model('UsuarioModel');
        $resultado = $model->eliminarUsuario($id);

        echo json_encode(['success' => $resultado]);
        }
    }
    /* ACCION: Editar*/
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_usuario'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $dni = $_POST['dni'];
            $direccion = $_POST['direccion'];
            $telefono = $_POST['telefono'];
            $correo = $_POST['correo'];
            $usuario = mb_strtolower(trim($_POST['usuario']));
            $idRol = $_POST['id_rol'];            
            $fechaLimite = $_POST['fecha_limite'] ?? null;

            // Parse fecha (puede venir vacía o con valor)
            $fechaLimite = !empty($fechaLimite) ? date('Y-m-d H:i:s', strtotime($fechaLimite)) : null;

            $model = $this->model('UsuarioModel');
            $resultado = $model->actualizarDatos($id, $nombre, $apellido, $dni, $direccion, $telefono, $correo, $usuario, $idRol, $fechaLimite);

            echo json_encode(['success' => $resultado]);
        }
    }


    
    /* INICIO: Desplegables */
    public function listar_roles(){
        $model = $this->model('RolModel');
        $roles = $model->listarRolesActivos();
        echo json_encode($roles);
    }
    
    public function listar_estados1(){
        $model = $this->model('UsuarioModel');
        $estados = $model->listarEstados1();
        echo json_encode($estados);
    }
    /* FIN: Desplegables */



    // Para validar
    public function validar_usuario() {
        try {            
            $usuario = $_GET['usuario'] ?? '';
            $idUsuario = $_GET['id_usuario'] ?? null;

            if (!$usuario) {
                echo json_encode(['valid' => false, 'error' => 'Usuario no recibido ⚠️']);
                return;
            }
            
            $model = $this->model('UsuarioModel');
            $existe = $model->existeUsuario($usuario,$idUsuario);
            echo json_encode(['valid' => $existe > 0]);
        } catch (\Throwable $e) {
            echo json_encode(['valid' => false, 'error' => $e->getMessage()]);
        }
    }
    public function validar_correo1() {
        try {
            $correo = $_GET['correo'] ?? '';
            $idUsuario = $_GET['id_usuario'] ?? null;

            if (!$correo) {
                echo json_encode(['valid' => false, 'error' => 'Correo no recibido ⚠️']);
                return;
            }
            
            $model = $this->model('UsuarioModel');
            $existe = $model->existeCorreo($correo,$idUsuario); //  corregido
            echo json_encode(['valid' => $existe > 0]);
        } catch (\Throwable $e) {
            echo json_encode(['valid' => false, 'error' => $e->getMessage()]);
        }
    }
    public function validar_registrodni() {
        try {            
            $dni = $_GET['dni'] ?? '';        

            if (!$dni) {
                echo json_encode(['valid' => false, 'error' => 'DNI no recibido ⚠️']);
                return;
            }
            
            $model = $this->model('UsuarioModel');
            $existe = $model->existePersona($dni);
            echo json_encode(['valid' => $existe > 0]);
        } catch (\Throwable $e) {
            echo json_encode(['valid' => false, 'error' => $e->getMessage()]);
        }
    }
}