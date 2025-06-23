<?php
class PosModel extends Model { 

    public function getProductos() {
        $stmt = $this->db->prepare("CALL SP_POS_LISTAR_PRODUCTOS()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategorias() {
        $stmt = $this->db->prepare("CALL CP_LISTAR_CATEGORIAS_ACTIVAS()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getComprobantes() {
        $stmt = $this->db->prepare("CALL SP_POS_LISTAR_COMPROBANTES()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClientes() {
        $stmt = $this->db->prepare("CALL SP_POS_LISTAR_CLIENTES()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function setPedido($venta, $productos) {
        // Insertar venta
        $stmt = $this->db->prepare("CALL SP_POS_INSERTAR_VENTA(?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $venta['id_usuario'],
            $venta['id_cliente'],
            $venta['tipo_comprobante'],
            $venta['modo_pago'],
            $venta['monto_igv'],
            $venta['submonto'],
            $venta['monto_total']
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_venta = $result['id_venta'] ?? null;

        // ⚠️ CERRAR EL CURSOR para evitar el error 2014
        $stmt->closeCursor();

        if (!$id_venta) {
            throw new Exception('No se pudo obtener el ID de la venta');
        }

        // Insertar detalle
        $stmtDetalle = $this->db->prepare("CALL SP_POS_INSERTAR_DETALLE_VENTA(?, ?, ?, ?)");

        foreach ($productos as $p) {
            $stmtDetalle->execute([
                $id_venta,
                $p['id_producto'],
                $p['precio_unitario'],
                $p['cantidad']
            ]);

            // Opcionalmente también puedes cerrar aquí
            $stmtDetalle->closeCursor();
        }

        return $id_venta;
    }
}
