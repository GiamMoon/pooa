<?php 

class CajaModel extends Model {
  public function abrirCaja($id_usuario, $monto_apertura, $observaciones = null) {
    // Verifica si ya hay una caja abierta
    $abierta = $this->getCajaAbierta($id_usuario);
    if ($abierta) return false;

    $stmt = $this->db->prepare("INSERT INTO TB_CAJA_MOVIMIENTO (id_usuario, monto_apertura, observaciones) VALUES (?, ?, ?)");
    $stmt->execute([$id_usuario, $monto_apertura, $observaciones]);
    return $this->db->lastInsertId();
  }

  public function cerrarCaja($id_caja, $monto_cierre, $observaciones = null) {
    $stmt = $this->db->prepare("UPDATE TB_CAJA_MOVIMIENTO SET fecha_cierre = NOW(), monto_cierre = ?, observaciones = ?, estado = 'CERRADA' WHERE id_movimiento = ?");
    return $stmt->execute([$monto_cierre, $observaciones, $id_caja]);
  }

    public function getCajaAbierta($id_usuario) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.nombre AS nombre_usuario
            FROM TB_CAJA_MOVIMIENTO m
            JOIN TB_USUARIO u ON m.id_usuario = u.id_usuario
            WHERE m.id_usuario = ? AND m.estado = 'ABIERTA'
            LIMIT 1
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


  public function getHistorialCajas($id_usuario) {
    $stmt = $this->db->prepare("SELECT * FROM TB_CAJA_MOVIMIENTO WHERE id_usuario = ? AND estado = 'CERRADA' ORDER BY fecha_cierre DESC");
    $stmt->execute([$id_usuario]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function actualizarComentario($id_caja, $observaciones) {
    $stmt = $this->db->prepare("UPDATE TB_CAJA_MOVIMIENTO SET observaciones = ? WHERE id_movimiento = ?");
    return $stmt->execute([$observaciones, $id_caja]);
  }

  public function getCajaById($id) {
    $stmt = $this->db->prepare("SELECT * FROM TB_CAJA_MOVIMIENTO WHERE id_movimiento = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

public function getVentasDeCaja($id_usuario) {
    // Obtener la caja abierta actual
    $caja = $this->getCajaAbierta($id_usuario);
    if (!$caja) return [];

    $fechaInicio = $caja['fecha_apertura'];
    $fechaFin = date('Y-m-d H:i:s'); // hasta ahora

    $stmt = $this->db->prepare("
        SELECT v.id_venta, v.creado_en, v.monto_total, v.tipo_comprobante, 
            CASE 
                WHEN c.tipo_cliente = 'NATURAL' THEN CONCAT(n.nombre, ' ', n.apellido)
                ELSE j.razon_social
            END AS comprador
        FROM TB_VENTA v
        JOIN TB_CLIENTE c ON c.id_cliente = v.id_cliente
        LEFT JOIN TB_CLIENTE_NATURAL n ON c.id_cliente = n.id_cliente
        LEFT JOIN TB_CLIENTE_JURIDICO j ON c.id_cliente = j.id_cliente
        WHERE v.id_usuario = ?
        AND v.creado_en BETWEEN ? AND ?
        ORDER BY v.creado_en DESC
    ");
    $stmt->execute([$id_usuario, $fechaInicio, $fechaFin]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function tieneCajaAbierta($id_usuario) {
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM TB_CAJA_MOVIMIENTO WHERE id_usuario = ? AND estado = 'ABIERTA'");
    $stmt->execute([$id_usuario]);
    return $stmt->fetchColumn() > 0;
}

public function getVentasDeCajaCerrada($id_caja) {
    $stmt = $this->db->prepare("
        SELECT v.id_venta, v.creado_en, v.monto_total, v.tipo_comprobante,
            CASE 
                WHEN c.tipo_cliente = 'NATURAL' THEN CONCAT(n.nombre, ' ', n.apellido)
                ELSE j.razon_social
            END AS comprador
        FROM TB_VENTA v
        JOIN TB_CLIENTE c ON c.id_cliente = v.id_cliente
        LEFT JOIN TB_CLIENTE_NATURAL n ON c.id_cliente = n.id_cliente
        LEFT JOIN TB_CLIENTE_JURIDICO j ON c.id_cliente = j.id_cliente
        WHERE v.id_usuario = (SELECT id_usuario FROM TB_CAJA_MOVIMIENTO WHERE id_movimiento = ?)
        AND v.creado_en BETWEEN (
            SELECT fecha_apertura FROM TB_CAJA_MOVIMIENTO WHERE id_movimiento = ?
        ) AND (
            SELECT fecha_cierre FROM TB_CAJA_MOVIMIENTO WHERE id_movimiento = ?
        )
        ORDER BY v.creado_en
    ");
    $stmt->execute([$id_caja, $id_caja, $id_caja]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}