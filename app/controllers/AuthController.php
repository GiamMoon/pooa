<?php

require_once __DIR__ . '/../../config/smtp.php';
require_once "../app/models/UsuarioModel.php";

class AuthController extends Controller{

    private $usuarioModel;
    private $url_auth;

    public function __construct() {
        // Iniciar sesión si aún no está
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->usuarioModel = new UsuarioModel();
        $this->url_auth = BASE_URL('auth/');
    } 
    
    public function index() {
        if (isset($_SESSION['id_usuario'])) {
            header("Location: " . BASE_URL('home'));
            exit;
        }

        $error = $_SESSION['error_login'] ?? null;
        unset($_SESSION['error_login']); 

        $this->renderAuthView('auth/login',compact('error'));
    }

    public function login() {
        if (isset($_SESSION['id_usuario'])) {
            header("Location: " . BASE_URL('home'));
            exit;
        }
        $this->renderAuthView('auth/login');
    }

    public function forgot() {
        $this->renderAuthView('auth/forgot' ); 
    }

    private function renderAuthView($view,$data = []) {
        $this->view($view,$data,false);
    }




    //functions of actions
    public function acceder() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = trim($_POST['username']); $clave = trim($_POST['password']);
            $datos = $this->usuarioModel->login($usuario);
            //$permisos = $this->usuarioModel->obtenerPermisosUsuario($datos['id_usuario']);
            if (!$datos) {                
                $_SESSION['error_login'] = "Usuario o contraseña inválidos";
                header("Location: " . BASE_URL('auth/login'));
                exit;
            }
            if (isset($datos['activo']) && $datos['activo'] == 2) {
                $_SESSION['error_login'] = "Contacte con el administrador del sistema. Su usuario se encuentra inactivo.";
                header("Location: " . BASE_URL('auth/login'));
                exit;
            }
            if (!password_verify($clave,$datos['contrasena'])) {
                $_SESSION['error_login'] = "Contraseña incorrecta";
                header("Location: " . BASE_URL('auth/login'));
                exit;
            }
            if($datos['primer_login'] === 1){ //0 = yess pass change - 1 not pass change
                $_SESSION['type_access'] = 'change_primer_login';
                $_SESSION['id_user_temp'] = $datos['id_usuario'];
                $this->renderAuthView('auth/change',['title' => 'Actualizar Contraseña', 'subtitle' => 'Por seguridad, debes actualizar tu contraseña antes de continuar.' ]); 
            }else{                 
                $_SESSION['usuario'] = $datos['nombre_usuario'];
                //$_SESSION['permisos'] = $permisos;
                //return $this->redirect('home');
                

                // Generate 2FA code
                $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                $_SESSION['2fa_code'] = $code;
                $_SESSION['2fa_user'] = $datos['id_usuario'];
                $_SESSION['2fa_email'] = $datos['correo']; 

                // Send code by email
                $result = sendMail($datos['correo'], 'two-factor', 'Código de verificación', [
                    'code' => $code,
                    'altBody' => "Tu código de verificación es: $code"
                ]);

                if (!$result) {
                    error_log("❌ Error al enviar correo 2FA a " . $datos['correo']);
                    die("No se pudo enviar el código 2FA.");
                } else {
                    error_log("✅ Correo 2FA enviado a " . $datos['correo']);
                }
                return $this->renderAuthView('auth/two-factor');

            }    
        }else{
            return $this->redirect('auth/');
        }
    } 

    public function logout() {
        session_destroy();
        header("Location: " . BASE_URL('auth/'));
        exit;
    }
 
    public function recover() {
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        if (empty($email)) {
            return $this->redirect($this->url_auth . 'forgot?error=' . urlencode('El correo es obligatorio.'));
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->redirect($this->url_auth . 'forgot?error=' . urlencode('El correo no tiene un formato válido.'));
        }

        $user = $this->usuarioModel->findByEmail($email);
        if (!$user) {
            return $this->redirect($this->url_auth . 'forgot?error=' . urlencode('El correo no existe.'));
        }
        
        $code = str_pad(rand(0, pow(10, 6) - 1), 6, '0', STR_PAD_LEFT);
        //$expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        $this->usuarioModel->setRecoveryCode($email, $code);

        // Enviar correo
        if (sendMail($email, 'email-recovery', 'Código de Recuperación', [
            'code' => $code,
            'time' => 15,
            'altBody' => "Tu código de recuperación para tu cuenta es: $code. Válido por 15 minutos."
        ])) {
            $this->renderAuthView('auth/recover', ['email' => $email]);
        } else {
            return $this->redirect($this->url_auth . 'forgot?error=' . urlencode('Código no enviado. Intenta de nuevo.'));
        }
    }


    public function verifyCode() {
        $email = $_POST['email'];
        $code = $_POST['code'];
        if ($this->usuarioModel->validarCodigo($email, $code)) {
              
            $_SESSION['type_access'] = 'change_recovery';
            $_SESSION['email_temp'] = $email;
            $this->renderAuthView('auth/change',['title' => 'Cambiar Contraseña', 'subtitle' => 'Ingrese su nueva contraseña.' ]);
        } else { 
            $this->renderAuthView('auth/recover', ['email' => $email, 'error' => 'Código inválido o expirado.']);
        }
    }
    
    public function save_password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $nuevaClave = trim($_POST['nueva_clave']);
            $confirmarClave = trim($_POST['confirmar_clave']);

            if ($nuevaClave !== $confirmarClave) {
                die("Las contraseñas no coinciden.");
            } 
            
            if( isset($_SESSION['type_access']) and $_SESSION['type_access'] == 'change_primer_login' ){
                $hash = password_hash($nuevaClave, PASSWORD_ARGON2ID);
                $this->usuarioModel->actualizarClave($_SESSION['id_user_temp'], $hash);
                unset($_SESSION['type_access']);
                unset($_SESSION['id_user_temp']);
                return $this->redirect($this->url_auth.'auth?msg=' . urlencode('Su contraseña ha sido cambiada, inicie sesion con su nueva contraseña'));
            }

            if( isset($_SESSION['type_access']) and $_SESSION['type_access'] == 'change_recovery' ){

                $email = $_SESSION['email_temp'];
                $usuario = $this->usuarioModel->findByEmail($email);
                $hash = password_hash($nuevaClave, PASSWORD_ARGON2ID);
                $this->usuarioModel->actualizarClave($usuario['id_usuario'], $hash);

                unset($_SESSION['type_access']);
                unset($_SESSION['email_temp']);

                return $this->redirect($this->url_auth.'auth?msg=' . urlencode('Su contraseña ha sido cambiada, inicie sesion con su nueva contraseña'));
            }
 
        }
    }

    //Doble factor
    public function verify2fa() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inputCode = trim($_POST['code']);
            if (isset($_SESSION['2fa_code']) && $inputCode === $_SESSION['2fa_code']) {
                
                // Success: log in the user
                $_SESSION['id_usuario'] = $_SESSION['2fa_user'];
                $_SESSION['correo'] = $_SESSION['2fa_email'];;

                //Obtener datos y permisos
                $permisos = $this->usuarioModel->obtenerPermisosUsuario($_SESSION['id_usuario']);
                $_SESSION['permisos'] = $permisos;

                // Limpieza de datos 2FA
                unset($_SESSION['2fa_code'], $_SESSION['2fa_user'], $_SESSION['2fa_email']);


                return $this->redirect('home');
            } else {
                $error = "Código incorrecto. Intenta de nuevo.";
                return $this->renderAuthView('auth/two-factor', compact('error'));
            }
        }
        return $this->redirect('auth/');
    }


}