<?php
require_once "../app/models/UsuarioModel.php";

class LoginController extends Controller {
    private $usuarioModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->usuarioModel = new UsuarioModel();
    }

    public function index() {
        if (isset($_SESSION['id_usuario'])) {
            header("Location: " . BASE_URL('home'));
            exit;
        }

        $error = $_SESSION['error_login'] ?? null;
        unset($_SESSION['error_login']);
        
        $this->renderAuthView('login',compact('error'));
    }

    public function acceder() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = trim($_POST['username']);
            $clave = trim($_POST['password']);

            $datos = $this->usuarioModel->login($usuario);

            if (!$datos) {              
                $_SESSION['error_login'] = "Usuario o contraseña inválidos";
                header("Location: " . BASE_URL('login'));
                exit;
            }

            if (isset($datos['activo']) && $datos['activo'] == 2) {
                $_SESSION['error_login'] = "Contacte con el administrador del sistema. Su usuario se encuentra inactivo.";
                header("Location: " . BASE_URL('login'));
                exit;
            }

            if (!password_verify($clave,$datos['contrasena'])) {
                $_SESSION['error_login'] = "Contraseña incorrecta";
                header("Location: " . BASE_URL('login'));
                exit;
            }

                $_SESSION['id_usuario'] = $datos['id_usuario'];
                $_SESSION['usuario'] = $datos['nombre_usuario'];
                $_SESSION['correo'] = $datos['correo'];
                $_SESSION['rol'] = $datos['rol'];
                $_SESSION['id_sucursal'] = $datos['id_sucursal'];
                
                
                $_SESSION['id_rol'] = $datos['id_rol'];
  
                $permisos = $this->usuarioModel->obtenerPermisosUsuario($datos['id_usuario']);
                $_SESSION['permisos'] = $permisos;

                $url = $datos['primer_login'] ? 'seguridad/cambiarClave' : 'home';
                header("Location: " . BASE_URL($url));
                exit;            
        }
    }

    public function guardarNuevaClave() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nuevaClave = trim($_POST['nueva_clave']);
            $hash = password_hash($nuevaClave,PASSWORD_ARGON2ID);

            $this->usuarioModel->actualizarClave($_SESSION['id_usuario'],$hash);

            $_SESSION['primer_login'] = 0;
            header("Location: " . BASE_URL('home'));
        }
    }

    public function salir() {
        session_destroy();
        header("Location: " . BASE_URL('login'));
        exit;
    }

    private function renderAuthView($view,$data = []) {
        $this->view($view,$data,false);
    }
}