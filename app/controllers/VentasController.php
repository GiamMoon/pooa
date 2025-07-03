<?php

require_once "../app/models/PosModel.php";
require_once "../app/models/VentasModel.php";
require_once "../app/models/CajaModel.php";
require_once "../app/models/UsuarioModel.php";

class VentasController extends Controller {

    private $posModel;
    private $ventasModel;
    private $cajaModel;
    private $userModel;

    public function __construct() {
        $this->posModel = new PosModel();
        $this->ventasModel = new VentasModel();
        $this->cajaModel = new CajaModel();
        $this->userModel = new UsuarioModel();
    }

    public function index() {
        $ventas = $this->ventasModel->getVentas();

        $totalVentas = $this->ventasModel->getTotalVentas();
        $ventasHoy = $this->ventasModel->getVentasDelDia();
        $ventasAyer = $this->ventasModel->getVentasAyer();
        $ventasActivas = $this->ventasModel->getVentasActivas();
        $totalRecaudado = $this->ventasModel->getTotalRecaudado();
        $crecimientoHoy = $ventasAyer > 0 ? (($ventasHoy - $ventasAyer) / $ventasAyer) * 100 : 100;

        $this->view('ventas/list', [
            'title' => 'Ventas',
            'activePage' => 'index',
            'openMenu' => 'ventas',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css',
                'https://vs.ifonts.dev/2/if.css'
            ],
            'vendors' => [
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js' ,
                'assets/vendor/libs/select2/select2.js',
            ],
            'ventas' => $ventas,
            'totalVentas' => $totalVentas,
            'ventasHoy' => $ventasHoy,
            'ventasActivas' => $ventasActivas,
            'crecimientoHoy' => number_format($crecimientoHoy, 1),
            'totalRecaudado' => $totalRecaudado
        ]);
    } 

    public function buscar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $query = $_POST['query'] ?? '';
            $id_sucursal = $_POST['id_sucursal'] ?? 1; 

            if (strlen($query) < 2) {
                echo json_encode([]);
                return;
            }

            $productos = $this->ventasModel->buscarProductos($query, $id_sucursal);
            echo json_encode($productos);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
        }
    }


    public function pos() {
        
        $tieneCaja = $this->cajaModel->tieneCajaAbierta($_SESSION['id_usuario']);

        $productos = $this->posModel->getProductos();
        $categorias = $this->posModel->getCategorias();
        $comprobantes = $this->posModel->getComprobantes();
        $clientes = $this->posModel->getClientes();

        $this->view('ventas/pos', [
            'title' => 'VENTA POS',
            'activePage' => 'pos',
            'openMenu' => 'ventas',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css',
                'https://vs.ifonts.dev/2/if.css'
            ],
            'vendors' => [
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js' ,
                'assets/vendor/libs/select2/select2.js', 'assets/vendor/libs/tagify/tagify.js', 'assets/vendor/libs/bootstrap-select/bootstrap-select.js',
                'assets/vendor/libs/typeahead-js/typeahead.js'. 'assets/vendor/libs/bloodhound/bloodhound.js'
            ],
            'scripts' => ['assets/js/app-ecommerce-product-list.js'],
            'productos' => $productos,
            'total_productos' => count($productos),
            'categorias' => $categorias,
            'comprobantes' => $comprobantes,
            'clientes' => $clientes,
            'estadoCaja' => $tieneCaja,
        ]);
    }


    public function create(){

        $id_usuario = $_SESSION['id_usuario'] ?? 1;
        if (!$this->cajaModel->tieneCajaAbierta($id_usuario)) {
            // Si no hay caja abierta, redirige a la vista para abrir caja
            $this->view('ventas/caja', [
                'title' => 'Abrir Caja',
                'activePage' => 'caja',
                'openMenu' => 'ventas'
            ]);
            return;
        }

        $this->view('ventas/create', [
            'title' => 'Nueva ventaS',
            'activePage' => 'crear',
            'openMenu' => 'ventas',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css', 'assets/vendor/libs/quill/typography.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css','assets/vendor/libs/quill/editor.css', 'assets/vendor/libs/quill/katex.css',
                'assets/vendor/libs/select2/select2.css','assets/vendor/libs/tagify/tagify.css', 'assets/vendor/libs/flatpickr/flatpickr.css','assets/vendor/libs/dropzone/dropzone.css',
                'https://vs.ifonts.dev/2/if.css'
            ],
            'vendors' => [ 
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js' ,
                'assets/vendor/libs/quill/katex.js', 'assets/vendor/libs/quill/quill.js', 'assets/vendor/libs/select2/select2.js',
                'assets/vendor/libs/dropzone/dropzone.js'. 'assets/vendor/libs/jquery-repeater/jquery-repeater.js','assets/vendor/libs/flatpickr/flatpickr.js','assets/vendor/libs/tagify/tagify.js'
            ],
            'scripts' => ['assets/js/app-producto-lista.js'],
             
        ]);
    }

    public function caja(){
        $this->view('ventas/caja', [
            'title' => 'Caja',
            'activePage' => 'caja',
            'openMenu' => 'ventas',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css', 'assets/vendor/libs/quill/typography.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css','assets/vendor/libs/quill/editor.css', 'assets/vendor/libs/quill/katex.css',
                'assets/vendor/libs/select2/select2.css','assets/vendor/libs/tagify/tagify.css', 'assets/vendor/libs/flatpickr/flatpickr.css','assets/vendor/libs/dropzone/dropzone.css',
                'https://vs.ifonts.dev/2/if.css'
            ],
            'vendors' => [ 
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js' ,
                'assets/vendor/libs/quill/katex.js', 'assets/vendor/libs/quill/quill.js', 'assets/vendor/libs/select2/select2.js',
                'assets/vendor/libs/dropzone/dropzone.js'. 'assets/vendor/libs/jquery-repeater/jquery-repeater.js','assets/vendor/libs/flatpickr/flatpickr.js','assets/vendor/libs/tagify/tagify.js'
            ],
            'scripts' => [''],
            'user' => $_SESSION['id_usuario'],
        ]);
    }
    public function abrir() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         
        $id_usuario = $_SESSION['id_usuario'];
        $monto = $_POST['monto_apertura'];
        $obs = $_POST['observaciones'] ?? null;
        $id_caja = $this->cajaModel->abrirCaja($id_usuario, $monto, $obs);

        if (!$id_caja) {
        echo json_encode(['status' => 'error', 'message' => 'Ya hay una caja abierta.']);
        return;
        }

        $caja = $this->cajaModel->getCajaAbierta($id_usuario);
        echo json_encode(['status' => 'ok', 'caja' => $caja]);
    }
    }

    public function cargarEstado() {
    $id_usuario = $_SESSION['id_usuario'];
    $abierta = $this->cajaModel->getCajaAbierta($id_usuario);
    $historial = $this->cajaModel->getHistorialCajas($id_usuario);

    echo json_encode([
        'caja_activa' => $abierta,
        'historial' => $historial
    ]);
    }
    
public function ventasCaja(){
    $id_usuario = $_SESSION['id_usuario'];
    $ventas = $this->cajaModel->getVentasDeCaja($id_usuario);

    echo json_encode(['ventas' => $ventas]);
}


    public function cerrar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_caja = $_POST['id_caja'];
        $monto = $_POST['monto_cierre'];
        $obs = $_POST['observaciones'] ?? null;
        $exito = $this->cajaModel->cerrarCaja($id_caja, $monto, $obs);
        echo json_encode(['status' => $exito ? 'ok' : 'error']);
    }
    }

    public function guardarComentario() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_caja = $_POST['id_caja'];
        $obs = $_POST['observaciones'];
        $exito = $this->cajaModel->actualizarComentario($id_caja, $obs);
        echo json_encode(['status' => $exito ? 'ok' : 'error']);
    }
    }

    public function verVentasCaja($id_caja) {
        $ventas = $this->cajaModel->getVentasDeCajaCerrada($id_caja);
        echo json_encode(['ventas' => $ventas]);
    }





    public function addventa() {
        $ventaData = json_decode(file_get_contents('php://input'), true);

        if (!$ventaData || empty($ventaData['productos'])) {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
            return;
        }

        try {
            $id_venta = $this->ventasModel->saveVenta($ventaData);
            echo json_encode(['status' => 'ok', 'id_venta' => $id_venta]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function checkout() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['cliente']) || !isset($input['comprobante']) || !isset($input['productos'])) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }

        $cliente = $input['cliente'];
        $comprobante = $input['comprobante'];
        $modo_pago = $input['pago'] ?? 'EFECTIVO';
        $productos = $input['productos']; // ← recibes los productos desde JS

        $subtotal = 0;
        foreach ($productos as $item) {
            $subtotal += (float)$item['precio_unitario'] * (int)$item['cantidad'];
        }

        $igv = $subtotal * 0.18;
        $total = $subtotal + $igv;

        $venta = [
            'id_usuario' => $_SESSION['id_usuario'],
            'id_cliente' => $cliente,
            'tipo_comprobante' => (int)$comprobante,
            'modo_pago' => $modo_pago,
            'monto_igv' => $igv,
            'submonto' => $subtotal,
            'monto_total' => $total
        ];

        try {
            $idVenta = $this->posModel->setPedido($venta, $productos);

            echo json_encode([
                'success' => true,
                'message' => 'Pedido registrado exitosamente',
                'id_venta' => $idVenta
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al registrar el pedido', 'error' => $e->getMessage()]);
        }
    }



    public function resumen($id) {
        $resumen = $this->ventasModel->getResumenVenta($id);
         
        echo json_encode(['success' => true, 'resumen' => $resumen, 'pdf' => $id]);
    }

    public function pdf($id) {
        $type = $_GET['type'] ?? 'A4'; // por defecto A4

        $data = $this->ventasModel->getResumenVenta($id);
        $detalle = $this->ventasModel->getProductosVenta($id);

        require_once '../vendor/fpdf/fpdf.php';

        $empresa_nombre = "REPUESTOS CAMPOS";
        $empresa_ruc = 'R.U.C. 51686515';
        $empresa_direccion = "Local 3 - Centro 4";
        $empresa_contacto = "Tel: 845215862";
        
        // Crear instancia según tipo
        if ($type === 'ticket') {
            $pdf = new FPDF('P', 'mm', array(75, 300));
            $pdf->SetTitle($data['numero']);
            $pdf->AddPage();
            $pdf->SetLeftMargin(2); // o 0 si quieres desde el borde mismo
            $pdf->SetRightMargin(2);
            $pdf->SetAutoPageBreak(false);
            $pdf->SetFont('Arial', '', 9);

            // LOGO (ajusta la ruta)
            $pdf->Image('../public/assets/img/logo.png', 25, 5, 25);
            $pdf->Ln(28);

            // DATOS EMPRESA
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->MultiCell(0, 4, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $empresa_nombre ), 0, 'C');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(0, 4, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',$empresa_direccion . "\n" .$empresa_contacto . "\n" . $empresa_ruc ), 0, 'C');
            $pdf->Ln(2);
            $pdf->Cell(0, 0, str_repeat("-", 70), 0, 1, 'C');
            $pdf->Ln(2);

            // TÍTULO Y NÚMERO
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(0, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "BOLETA DE VENTA ELECTRÓNICA"), 0, 1, 'C');
            $pdf->Cell(0, 5, $data['serie'] . ' - ' . $data['numero'], 0, 1, 'C');
            //$pdf->Cell(0, 0, str_repeat("-", 60), 0, 1, 'C');
            $pdf->Ln(2);
            $pdf->Ln(2);
            $pdf->Cell(0, 0, str_repeat("-", 70), 0, 1, 'C');
            $pdf->Ln(2);
            // DATOS CLIENTE
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 5, 'CLIENTE:');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(0, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $data['cliente']), 0, 1);

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(15, 5, 'DNI:');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(30, 5, $data['ruc_dni']);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(15, 5, 'HORA:');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(0, 5, $data['fecha'], 0, 1);

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT','FECHA EMISIÓN:') );
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(25, 5, date('d/m/Y', strtotime($data['fecha'])));
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(12, 5, 'MONEDA:');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(0, 5, 'Soles', 0, 1);

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(15, 5, 'IGV:');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(0, 5, '18%', 0, 1);
            $pdf->Ln(2);
            $pdf->Cell(0, 0, str_repeat("-", 70), 0, 1, 'C');
            $pdf->Ln(2);

            // ENCABEZADO DETALLE
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(10, 5, '[CANT.]');
            $pdf->Cell(32, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT','DESCRIPCIÓN') );
            $pdf->Cell(15, 5, 'P/U', 0, 0, 'R');
            $pdf->Cell(0, 5, 'TOTAL', 0, 1, 'R');
            $pdf->SetFont('Arial', '', 8);

            // DETALLE PRODUCTOS
            foreach ($detalle as $item) {
                $pdf->Cell(10, 5, '[' . $item['cantidad'] . ']', 0, 0);
                $pdf->MultiCell(50, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $item['nombre_producto']));
                $pdf->Cell(42, 5, '', 0);
                $pdf->Cell(15, 5, number_format($item['precio_unitario'], 2), 0, 0, 'R');
                $pdf->Cell(0, 5, number_format($item['subtotal'], 2), 0, 1, 'R');
            }

            // TOTALES
            $pdf->Ln(1);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(0, 5, 'OP.GRAVADA S/ ' . number_format($data['submonto'], 2), 0, 1, 'R');
            $pdf->Cell(0, 5, 'IGV(18%) S/ ' . number_format($data['monto_igv'], 2), 0, 1, 'R');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(0, 6, 'TOTAL A PAGAR S/ ' . number_format($data['monto_total'], 2), 0, 1, 'R');

            // MONTO EN LETRAS
            $pdf->Ln(2);
            $pdf->SetFont('Arial', '', 8);
            $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
            $entero = floor($data['monto_total']);
            $decimal = sprintf('%02d', ($data['monto_total'] - $entero) * 100);
            $letras = strtoupper($formatter->format($entero));
            $pdf->MultiCell(0, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "IMPORTE EN LETRAS: " . ucfirst(strtolower($letras)) . " con " . $decimal . "/100 Soles"));

            // MÉTODO DE PAGO
            $pdf->Ln(1);
            $pdf->Cell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Metodo de Pago: Efectivo"));
            $pdf->Cell(0, 5, "Recibido: " . number_format($data['monto_total'], 0), 0, 1);

            // FOOTER
            $pdf->Ln(3);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(0, 4, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Representación impresa de la\nBOLETA DE VENTA ELECTRÓNICA, visita https://ventasonline.pse.la/buscar"), 0, 'C');
        }else {
            $pdf = new FPDF();
            $pdf->SetTitle($data['numero']);
            $pdf->AddPage();

            // Logo
            $pdf->Image('../public/assets/img/logo.png', 10, 10, 25);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetXY(40, 10);
            $pdf->MultiCell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT',
                $empresa_nombre . "\n" .
                $empresa_direccion . "\n" .
                $empresa_contacto), 0);

            // Recuadro derecha: RUC y tipo de comprobante
            $pdf->SetXY(145, 10);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(55, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $empresa_ruc), 1, 2, 'C');
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(55, 8, 'BOLETA DE VENTA', 1, 2, 'C');
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(55, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $data['serie'].' - N° '). $data['numero'], 1, 2, 'C');

            $pdf->Ln(20);

            // Datos cliente
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(30, 7, 'CLIENTE:', 0, 0);
            $pdf->SetFont('Arial', '', 10);
            
            $pdf->Cell(70, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $data['cliente'] ), 0, 0);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(30, 7, 'MONEDA:', 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 7, 'Soles', 0, 1);

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(30, 7, 'DNI:', 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(70, 7, $data['ruc_dni'], 0, 0);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(30, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'FECHA EMISIÓN:'), 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 7, $data['fecha'], 0, 1);

            // Encabezado de tabla
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetFillColor(0);
            $pdf->SetTextColor(255);
            $pdf->Cell(10, 8, 'Item', 1, 0, 'C', true);
            $pdf->Cell(40, 8, 'Cod.', 1, 0, 'C', true);
            $pdf->Cell(80, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Descripción'), 1, 0, 'C', true);
            $pdf->Cell(10, 8, 'Cant.', 1, 0, 'C', true);
            $pdf->Cell(25, 8, 'P/U', 1, 0, 'C', true);
            $pdf->Cell(25, 8, 'Importe', 1, 1, 'C', true);
            $pdf->SetTextColor(0);

            // Detalle de productos
            $pdf->SetFont('Arial', '', 10);
            $i = 1;
            foreach ($detalle as $item) {
                $pdf->Cell(10, 7, $i++, 1, 0, 'C');
                $pdf->Cell(40, 7, $item['codigo_producto'], 1, 0, 'C');
                $pdf->Cell(80, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $item['nombre_producto']), 1);
                $pdf->Cell(10, 7, $item['cantidad'], 1, 0, 'C');
                $pdf->Cell(25, 7, 'S/ ' . number_format($item['precio_unitario'], 3), 1, 0, 'R');
                $pdf->Cell(25, 7, 'S/ ' . number_format($item['subtotal'], 2), 1, 1, 'R');
            }

            // SON: monto en letras
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(25, 6, 'SON:', 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
            $entero = floor($data['monto_total']);
            $decimal = sprintf('%02d', ($data['monto_total'] - $entero) * 100);
            $letras = strtoupper($formatter->format($entero));
            $pdf->MultiCell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $letras . ' CON ' . $decimal . '/100 SOLES'), 0);

            // Condición de pago
            $pdf->Ln(2);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(55, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Condición de pago:'), 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->MultiCell(60, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $data['condicion']), 1);

            // Totales
            $pdf->SetXY(145, $pdf->GetY() - 20);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(30, 6, 'Op. Gravadas', 0);
            $pdf->Cell(25, 6, 'S/ ' . number_format($data['submonto'], 2), 0, 1, 'R');

            $pdf->SetX(145);
            $pdf->Cell(30, 6, 'IGV (18%)', 0);
            $pdf->Cell(25, 6, 'S/ ' . number_format($data['monto_igv'], 2), 0, 1, 'R');

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetX(145);
            $pdf->Cell(30, 8, 'Total', 0);//1
            $pdf->Cell(25, 8, 'S/ ' . number_format($data['monto_total'], 2), 0, 1, 'R');

            // Footer personalizado
        $pdf->Sety(250); // Coloca el cursor 20 mm desde el borde inferior
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->SetTextColor(100, 100, 100);
        $footerText = 'Representación impresa del COMPROBANTE ELECTRÓNICO, visita https://repuestoscampos.com/ventas/buscar';
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $footerText), 0, 0, 'C');

        } 

        // Salida
        header('Content-Type: application/pdf');
        $pdf->Output();
    }
 

}