<?php
class InventarioModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array Lista de movimientos de inventario.
     */
    public function listarMovimientos()
    {
        $stmt = $this->db->prepare("CALL SP_GET_MOVIMIENTOS_HISTORIAL()");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id_movimiento El ID del movimiento a consultar.
     * @return array Lista de productos y cantidades del movimiento.
     */
    public function getMovimientoDetalle($id_movimiento)
    {
        $stmt = $this->db->prepare("CALL SP_GET_MOVIMIENTO_DETALLE(?)");
        $stmt->execute([$id_movimiento]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array Lista del inventario actual.
     */
    public function consultarStockActual()
    {
        $stmt = $this->db->prepare("CALL SP_CONSULTAR_STOCK_ACTUAL()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * @return array Lista del inventario agrupado.
     */
    public function consultarStockAgrupado()
    {
        $stmt = $this->db->prepare("CALL SP_CONSULTAR_STOCK_AGRUPADO()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id_sucursal El ID de la sucursal.
     * @return array Lista de ubicaciones diferenciadas.
     */
    public function getUbicacionesPorSucursal($id_sucursal)
    {
        $stmt = $this->db->prepare("CALL SP_LISTAR_UBICACIONES_DIFERENCIADAS(?)");
        $stmt->execute([$id_sucursal]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id_ubicacion El ID de la ubicación de origen.
     * @return array Lista de productos con stock.
     */
    public function getProductosConStock($id_ubicacion, $tipo_ubicacion)
    {
        $stmt = $this->db->prepare("CALL SP_GET_PRODUCTOS_CON_STOCK_EN_UBICACION(?, ?)");
        $stmt->execute([$id_ubicacion, $tipo_ubicacion]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarTransferencia($id_sucursal, $id_usuario, $id_ubicacion_origen, $tipo_ubicacion_origen, $id_ubicacion_destino, $tipo_ubicacion_destino, $productos_json, $observacion)
    {
        $stmt = $this->db->prepare("CALL SP_REGISTRAR_TRANSFERENCIA(?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $id_sucursal,
            $id_usuario,
            $id_ubicacion_origen,
            $tipo_ubicacion_origen,
            $id_ubicacion_destino,
            $tipo_ubicacion_destino,
            $productos_json,
            $observacion
        ]);
        return true;
    }
}