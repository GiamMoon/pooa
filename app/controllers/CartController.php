<?php
require_once "../app/models/PosModel.php";
class CartController extends Controller {
    private $posModel;

    public function __construct() {
        $this->posModel = new PosModel();
    }

  public function add() {
      $input = json_decode(file_get_contents('php://input'), true);

      if(!$input || !isset($input['codigo_producto']) || !isset($input['id_producto'])) {
          echo json_encode(['success' => false, 'msg' => 'Datos inválidos']);
          return;
      }

      $codigo = $input['codigo_producto'];
      $id_producto = $input['id_producto'];  
      $cantidad = isset($input['cantidad']) ? (int)$input['cantidad'] : 1;
      $precio = $input['precio_producto'];
      $nombre = $input['nombre_producto'];
  
      if(!isset($_SESSION['cart'])) {
          $_SESSION['cart'] = [];
      }

      if(isset($_SESSION['cart'][$codigo])) {
          $_SESSION['cart'][$codigo]['cantidad'] += $cantidad;
      } else {
          $_SESSION['cart'][$codigo] = [
              'codigo' => $codigo,
              'id_producto' => $id_producto,  
              'nombre' => $nombre,
              'precio' => $precio,
              'cantidad' => $cantidad
          ];
      }

      echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
  }

  public function get() { 
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        echo json_encode(['success' => true,'cart' => $_SESSION['cart'] ?? []]);
    }

  }

  public function list() {
    $cart = $_SESSION['cart'] ?? [];
    echo json_encode(['success' => true, 'cart' => $cart]);
  }

  public function empty(){ unset($_SESSION['cart']); }
 
  public function update() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['codigo_producto']) || !isset($input['cambio'])) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            return;
        }
        $codigo = $input['codigo_producto'];
        $cambio = (int) $input['cambio'];
        if (!isset($_SESSION['cart'][$codigo])) {
            echo json_encode(['success' => false, 'message' => 'Producto no encontrado en el carrito']);
            return;
        }
        $_SESSION['cart'][$codigo]['cantidad'] += $cambio;
        // Si la cantidad llega a 0 o menos, se elimina
        if ($_SESSION['cart'][$codigo]['cantidad'] <= 0) {unset($_SESSION['cart'][$codigo]);}
        echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
  }

  public function remove() {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['codigo_producto'])) {
        echo json_encode(['success' => false, 'message' => 'Código de producto no recibido']);
        return;
    }
    $codigo = $input['codigo_producto'];
    if (isset($_SESSION['cart'][$codigo])) {unset($_SESSION['cart'][$codigo]); }
    echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
  }

  public function checkout() {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['cliente']) || !isset($input['comprobante'])) {
      echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
      return;
    }

    $cliente = $input['cliente'];
    $comprobante = $input['comprobante'];
    $modo_pago = $input['pago'] ?? 'EFECTIVO'; // por defecto efectivo
    $igv = $input['igv'] ?? 0;
    $subtotal = $input['subtotal'] ?? 0;
    $total = $input['total'] ?? 0;

    $cart = $_SESSION['cart'] ?? [];

    if (empty($cart)) {
      echo json_encode(['success' => false, 'message' => 'El carrito está vacío']);
      return;
    }

    try {
      // Reestructurar productos
      $productos = [];
      foreach ($cart as $item) {
        $productos[] = [
          'id_producto' => $item['id_producto'],
          'precio_unitario' => $item['precio'],
          'cantidad' => $item['cantidad']
        ];
      }

      $venta = [
        'id_usuario' => 1,
        'id_cliente' => $cliente,
        'tipo_comprobante' => (int) $comprobante,
        'modo_pago' => $modo_pago,
        'monto_igv' => $igv,
        'submonto' => $subtotal,
        'monto_total' => $total
      ]; 

      $idVenta = $this->posModel->setPedido($venta, $productos);

      unset($_SESSION['cart']);

      echo json_encode(['success' => true, 'message' => 'Pedido '.$idVenta.' registrado exitosamente']);
    } catch (Exception $e) {
      echo json_encode(['success' => false, 'message' => 'Error al registrar el pedido', 'error' => $e->getMessage()]);
    }
  }



}
