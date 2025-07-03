<?php

require_once "../app/models/ClienteModel.php"; 

class ContactosController extends Controller {

    private $clienteModel;

    public function __construct() {
        $this->clienteModel = new ClienteModel();
    }

    public function clientes(){ 
        $clientes = $this->clienteModel->getAll();
        $this->view('contactos/clientes', [
            'title' => 'Clientes',
            'activePage' => 'clientes',
            'openMenu' => 'contactos',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css','assets/vendor/libs/notyf/notyf.css', 'assets/vendor/libs/animate-css/animate.css',
                'https://vs.ifonts.dev/2/if.css'
            ],
            'vendors' => [
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js' ,
                'assets/vendor/libs/select2/select2.js',
            ],
            'clientes' => $clientes
        ]);
    }
 
    public function guardarCliente() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_cliente' => $_POST['id_cliente'] ?? null,
                'tipo_cliente' => $_POST['tipo_cliente'],
                'id_tipo_documento' => $_POST['id_tipo_documento'],
                'numero_documento' => $_POST['numero_documento'],
                'email' => $_POST['email'],
                'telefono' => $_POST['telefono'],
                'direccion' => $_POST['direccion'],
                'id_ubigeo' => $_POST['id_ubigeo'],

                // NATURAL
                'nombre' => $_POST['nombre'] ?? null,
                'apellido' => $_POST['apellido'] ?? null,

                // JURIDICO
                'razon_social' => $_POST['razon_social'] ?? null,
                'nombre_representante' => $_POST['nombre_representante'] ?? null,
            ];

            $resultado = $this->clienteModel->guardarCliente($data);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => $resultado['success'],
                'message' => $resultado['success'] ? 'Cliente guardado correctamente' : $resultado['error'],
                'cliente' => $resultado['cliente'] ?? null
            ]);

        }
    }
    public function eliminarCliente() {
        $input = json_decode(file_get_contents('php://input'), true);
        $this->clienteModel->delete($input['id']);
        echo json_encode(['status' => 'ok']);
    }

    public function clienteById($id) {
        $cliente = $this->clienteModel->getById($id);
        echo json_encode($cliente);
    }

    public function buscar() {
        $query = $_POST['query'];
        $clientes = $this->clienteModel->buscarCoincidencias($query); // Retorna varios
        echo json_encode($clientes);
    }

    public function toggleEstado() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['id_cliente']) || !isset($input['activo'])) {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
            return;
        }

        $success = $this->clienteModel->actualizarEstado($input['id_cliente'], $input['activo']);
        echo json_encode(['status' => $success ? 'ok' : 'error']);
    }


    //Proveedores actions
    public function proveedores() {
        $this->view('contactos/proveedor', [
            'title' => 'Proveedores',
            'activePage' => 'proveedor',
            'openMenu' => 'contactos',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css'
            ],
            'vendors' => ['assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'assets/vendor/libs/select2/select2.js'],
            'scripts' => ['assets/js/app-proveedor-lista.js'],
            'permisos' => $_SESSION['permisos'] ?? []
        ]);
    }

}