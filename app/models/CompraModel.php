<?php
class CompraModel extends Model {
    public function listarCompras() {
        $stmt = $this->db->prepare("CALL SP_GET_COMPRAS_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function obtenerDetalleCompra($idCompra) {
        $stmt = $this->db->prepare("CALL SP_GET_COMPRA_DETALLE(?)");
        $stmt->execute([$idCompra]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function listarProveedoresActivos() {
        $stmt = $this->db->prepare("CALL CP_LISTAR_PROVEEDORES_ACTIVOS()");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function listarProductosActivos() {
        $stmt = $this->db->prepare("CALL CP_LISTAR_PRODUCTOS_ACTIVOS()");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function registrarCompra($idProveedor, $total, &$idCompra) {
        $stmt = $this->db->prepare("CALL SP_REGISTRAR_COMPRA_P(?, ?, ?, @id_compra)");
        $stmt->execute([$_SESSION['id_usuario'], $idProveedor, $total]);

        $result = $this->db->query("SELECT @id_compra AS id_compra")->fetch(PDO::FETCH_ASSOC);
        $idCompra = $result['id_compra'] ?? null;
        return $idCompra !== null;
    }

    public function registrarDetalleCompra($idCompra, $idProducto, $precioCompra, $cantidad) {
        $stmt = $this->db->prepare("CALL SP_INSERTAR_R_COMPRA_PRODUCTO(?, ?, ?, ?)");
        return $stmt->execute([$idCompra, $idProducto, $precioCompra, $cantidad]);
    }
}