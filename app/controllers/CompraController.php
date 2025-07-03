<?php
require_once '../vendor/fpdf/fpdf.php';
class PDF_OrdenCompra extends FPDF {
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

class CompraController extends Controller {

    public function compra() {
        $data = [
            'title' => 'Historial de Compras',
            'activePage' => 'compra',
            'openMenu' => 'compra',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css',
                'assets/vendor/libs/sweetalert2/sweetalert2.css'
            ],
            'vendors' => [
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
                'assets/vendor/libs/select2/select2.js',
                'assets/vendor/libs/sweetalert2/sweetalert2.js'
            ],
            'scripts' => ['assets/js/app-compra-lista.js'],
            'permisos' => $_SESSION['permisos'] ?? []
        ];

        $rolesPermitidos = [1, 2]; // Administrador y Encargado Compras
        if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $rolesPermitidos)) {
            $sucursalModel = $this->model('SucursalModel');
            $data['sucursales'] = $sucursalModel->getSucursalesActivas();
            $data['es_admin'] = true;
        } else {
            $data['sucursales'] = [];
            $data['es_admin'] = false;
        }

        $this->view('compra/compra', $data);
    }



    public function generarOrdenDeCompra($id_compra) {
        ob_start();

        $compraModel = $this->model('CompraModel');
        $compra = $compraModel->obtenerDetalleCompra($id_compra);

        if (!$compra) {
            die("Error: Compra no encontrada.");
        }

        if ($compra['activo'] == 7) {
            $compraModel->actualizarEstadoCompra($id_compra, 8);
        }

        $productos = json_decode($compra['productos'], true);

        $pdf = new PDF_OrdenCompra('P', 'mm', 'A4');
        $pdf->AliasNbPages(); 
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'ORDEN DE COMPRA', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 7, 'Nro: ' . $compra['codigo_compra'], 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 7, 'PROVEEDOR:', 0, 0, 'L');
        $pdf->Cell(95, 7, 'ENVIAR A:', 0, 1, 'L');
        $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 95, $pdf->GetY());
        $pdf->Line($pdf->GetX() + 100, $pdf->GetY(), $pdf->GetX() + 195, $pdf->GetY());
        $pdf->Ln(1);

        $pdf->SetFont('Arial', '', 9);
        $y_inicial = $pdf->GetY();
        $pdf->MultiCell(95, 5, utf8_decode($compra['proveedor'] ?? ''), 0, 'L');
        $pdf->SetX(10);
        $pdf->Cell(95, 5, 'RUC: ' . ($compra['proveedor_ruc'] ?? ''), 0, 1, 'L');
        $pdf->SetX(10);
        $pdf->Cell(95, 5, 'Email: ' . ($compra['proveedor_email'] ?? ''), 0, 1, 'L');
        $y1 = $pdf->GetY();

        $pdf->SetXY(110, $y_inicial);
        $pdf->MultiCell(95, 5, utf8_decode("CMP MOTO REPUESTOS\n" . ($compra['sucursal_direccion'] ?? 'Direccion no especificada') . "\nSucursal: " . ($compra['sucursal_nombre'] ?? '')), 0, 'L');
        $y2 = $pdf->GetY();

        $pdf->SetY(max($y1, $y2) + 5);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(15, 7, 'ITEM', 1, 0, 'C', true);
        $pdf->Cell(85, 7, 'DESCRIPCION', 1, 0, 'C', true);
        $pdf->Cell(15, 7, 'CANT.', 1, 0, 'C', true);
        $pdf->Cell(15, 7, 'T.U.', 1, 0, 'C', true); 
        $pdf->Cell(25, 7, 'P. UNIT.', 1, 0, 'C', true);
        $pdf->Cell(35, 7, 'SUBTOTAL', 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 9);
        $total_final = 0;
        $item = 1; 
        if (is_array($productos)) {
            foreach ($productos as $producto) {
                $subtotal_linea = $producto['cantidad'] * $producto['precio_compra'];
                
                $pdf->Cell(15, 7, $item++, 1, 0, 'C');
                $pdf->Cell(85, 7, utf8_decode($producto['nombre_producto']), 1, 0, 'L');
                $pdf->Cell(15, 7, $producto['cantidad'], 1, 0, 'C');
                $pdf->Cell(15, 7, utf8_decode($producto['abreviatura'] ?? ''), 1, 0, 'C'); // Imprimir la abreviatura
                $pdf->Cell(25, 7, 'S/ ' . number_format($producto['precio_compra'], 2), 1, 0, 'R');
                $pdf->Cell(35, 7, 'S/ ' . number_format($subtotal_linea, 2), 1, 1, 'R');
                $total_final += $subtotal_linea; 
            }
        }
        
        $igv_calculado = $total_final * 0.18;
        $subtotal_base = $total_final - $igv_calculado;

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(125, 7, '', 0, 0); // Ajuste de espacio
        $pdf->Cell(30, 7, 'SUBTOTAL', 1, 0, 'R');
        $pdf->Cell(35, 7, 'S/ ' . number_format($subtotal_base, 2), 1, 1, 'R'); 
        $pdf->Cell(125, 7, '', 0, 0);
        $pdf->Cell(30, 7, 'IGV (18%)', 1, 0, 'R');
        $pdf->Cell(35, 7, 'S/ ' . number_format($igv_calculado, 2), 1, 1, 'R'); 
        $pdf->Cell(125, 7, '', 0, 0);
        $pdf->SetFillColor(220, 220, 220); 
        $pdf->Cell(30, 7, 'TOTAL', 1, 0, 'R', true);
        $pdf->Cell(35, 7, 'S/ ' . number_format($total_final, 2), 1, 1, 'R', true); 
        ob_end_clean(); 
        $pdf->Output('I', 'OC-' . $compra['codigo_compra'] . '.pdf');
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_compra = $_POST['id_compra'] ?? 0;
            $id_proveedor = $_POST['id_proveedor'] ?? 0;
            $detalle_json = $_POST['detalle_compra'] ?? '[]';
            $detalle = json_decode($detalle_json, true);
            
            $id_ubicacion_destino = $_POST['id_ubicacion_destino'] ?? null;
            $tipo_ubicacion_destino = $_POST['tipo_ubicacion_destino'] ?? null;

            if (empty($id_compra) || empty($id_proveedor) || !is_array($detalle) || empty($detalle) || !$id_ubicacion_destino || !$tipo_ubicacion_destino) {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos para actualizar la compra.']);
                return;
            }

            $total = 0;
            foreach ($detalle as $item) {
                $total += ($item['cantidad'] ?? 0) * ($item['precio'] ?? 0);
            }

            $compraModel = $this->model('CompraModel');
            
            $resultado = $compraModel->actualizarCompra($id_compra, $id_proveedor, $total, $detalle, $id_ubicacion_destino, $tipo_ubicacion_destino);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Compra actualizada con éxito.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la compra en la base de datos.']);
            }
        } else {
            http_response_code(405); 
            echo json_encode(['success' => false, 'message' => 'Acceso denegado.']);
        }
    }
}