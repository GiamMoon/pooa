<?php
class VentasModel extends Model { 

    public function getVentas() {
        $stmt = $this->db->prepare("CALL SP_VENTAS_LISTAR()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getResumenVenta($idVenta) {
        $stmt = $this->db->prepare("CALL SP_VENTA_RESUMEN(?)");
        $stmt->execute([$idVenta]); 
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getProductosVenta($idVenta) {
        $stmt = $this->db->prepare("CALL SP_VENTA_DETALLE_PRODUCTOS(?)");
        $stmt->execute([$idVenta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getTotalVentas() {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM TB_VENTA");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getVentasDelDia() {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM TB_VENTA WHERE DATE(creado_en) = CURDATE()");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getVentasActivas() {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM TB_VENTA WHERE activo = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getVentasAyer() {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM TB_VENTA WHERE DATE(creado_en) = CURDATE() - INTERVAL 1 DAY");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getTotalRecaudado() {
        $stmt = $this->db->prepare("SELECT SUM(monto_total) AS total FROM TB_VENTA WHERE activo = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function buscarProductos($query, $id_sucursal) {
        $stmt = $this->db->prepare("
            SELECT 
                p.id_producto,
                p.nombre,
                p.codigo_producto,
                p.precio_venta,
                p.url_imagen,
                IFNULL(i.cantidad, 0) AS stock
            FROM TB_PRODUCTO p
            LEFT JOIN CAT_INVENTARIO i 
                ON p.id_producto = i.id_producto AND i.id_sucursal = :id_sucursal
            WHERE p.nombre LIKE :query1 OR p.codigo_producto LIKE :query2
            LIMIT 10
        ");

        $param = '%' . $query . '%';
        $stmt->bindParam(':query1', $param, PDO::PARAM_STR);
        $stmt->bindParam(':query2', $param, PDO::PARAM_STR);
        $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function saveVenta($venta) {
    $this->db->beginTransaction();

    $usuario = $_SESSION['id_usuario'];

   
    // Calcular montos
    $subtotal = 0;
    foreach ($venta['productos'] as $p) {
        $subtotal += $p['total'];
    }

    $igv = $subtotal * 0.18;
    $total = $subtotal + $igv;

    // Insertar venta
    $stmt = $this->db->prepare("
        INSERT INTO TB_VENTA (
            id_usuario,
            id_cliente,
            tipo_comprobante,
            modo_pago,
            monto_igv,
            submonto,
            monto_total,
            activo,
            creado_en
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())
    ");

    $stmt->execute([
        $usuario,
        $venta['id_cliente'],
        $venta['tipo_comprobante'],
        $venta['modo_pago'] ?? 'EFECTIVO',
        $igv,
        $subtotal,
        $total
    ]);

    $id_venta = $this->db->lastInsertId();

    // Insertar productos
    $stmtProd = $this->db->prepare("
        INSERT INTO R_VENTA_PRODUCTO (id_venta, id_producto, precio_unitario, cantidad)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($venta['productos'] as $p) {
        $stmtProd->execute([
            $id_venta,
            $p['id_producto'],
            $p['precio'],
            $p['cantidad']
        ]);
    }

    $this->db->commit();
    return $id_venta;
}


}