<?php
class PerfilAjaxController extends Controller {

    public function obtener_perfil() {
        $model = $this->model('UsuarioModel');
        $perfil = $model->obtenerDetallePerfil();

        echo json_encode($perfil);
    }
    public function listar_departamentos(){
        $model = $this->model('UsuarioModel');
        $departamentos = $model->listarDepartamentos();
        echo json_encode($departamentos);
    }

    public function listar_provincias() {
        if (!isset($_GET['departamento'])) {
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $dep = $_GET['departamento'];
        $model = $this->model('UsuarioModel');
        $provincias = $model->listarProvincias($dep);

        echo json_encode($provincias);
    }

    public function listar_distritos() {
        if (!isset($_GET['departamento']) || !isset($_GET['provincia'])) {
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $dep = $_GET['departamento'];
        $prov = $_GET['provincia'];
        $model = $this->model('UsuarioModel');
        $distritos = $model->listarDistritos($dep,$prov);

        echo json_encode($distritos);
    }


    
    public function save_contacto() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_usuario1'];
            $usuario = mb_strtolower(trim($_POST['usuario']));
            $correo = $_POST['correo'];
            $telefono = $_POST['telefono'];
            
            //validación básica
            if (!$telefono || !$correo || !$usuario) {
                echo json_encode(['success' => false, 'message' => 'Tiene que llenar todos los campos de Contacto.']);
                return;
            }

            $model = $this->model('UsuarioModel');
            $resultado = $model->actualizarDatos1($id,$usuario,$correo,$telefono);
            echo json_encode(['success' => $resultado]);
        }
    }
    public function save_direccion() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {        
            $id = $_POST['id_usuario2'];
            $direccion = $_POST['direccion'];
            $dep = $_POST['departamento'];
            $prov = $_POST['provincia'];
            $dis = $_POST['distrito'];
            
            //validación básica
            if (!$direccion || !$dep || !$prov || !$dis) {
                echo json_encode(['success' => false, 'message' => 'Tiene que llenar todos los campos de Ubicación.']);
                return;
            }

            $model = $this->model('UsuarioModel');
            $resultado = $model->actualizarDatos2($id,$direccion,$dep,$prov,$dis);

            echo json_encode(['success' => $resultado]);
        }
    }
    public function save_password() {
        header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id_usuario3'];
        $nuevaClave = trim($_POST['nueva_pws']);
        $confirmarClave = trim($_POST['rep_nueva_pws']);

        if ($nuevaClave !== $confirmarClave) {
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden.']);
            return;
        }

        $model = $this->model('UsuarioModel');
        $perfil = $model->obtenercontrasena();
        

        $hashActual = $perfil ? $perfil['contrasena'] : null;

        if (!$hashActual) {
            echo json_encode(['success' => false, 'message' => 'No se encontró la contraseña actual.']);
            return;
        }

        if (password_verify($nuevaClave, $hashActual)) {
            echo json_encode(['success' => false, 'message' => 'La nueva contraseña no puede ser igual a la actual.']);
            return;
        }

        $hash = password_hash($nuevaClave, PASSWORD_ARGON2ID);
        $resultado = $model->actualizarClave($id, $hash);
             echo json_encode(['success' => $resultado]);
        }
    }
}