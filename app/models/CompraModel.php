<?php

class CompraModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listarCompras()
    {
        $stmt = $this->db->prepare("CALL SP_GET_COMPRAS_TB()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDetalleCompra($idCompra)
    {
        $stmt = $this->db->prepare("CALL SP_GET_COMPRA_DETALLE(?)");
        $stmt->execute([$idCompra]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarProveedoresActivos()
    {
        $stmt = $this->db->prepare("CALL CP_LISTAR_PROVEEDORES_ACTIVOS()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarProductosActivos()
    {
        $stmt = $this->db->prepare("CALL CP_LISTAR_PRODUCTOS_ACTIVOS()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        public function registrarCompra($id_usuario, $id_proveedor, $id_sucursal, $total, $detalle, $id_ubicacion_destino, $tipo_ubicacion_destino)
    {
        try {
            $stmt_code = $this->db->prepare("CALL SP_GENERAR_CODIGO_COMPRA(?, @codigo_generado)");
            $stmt_code->bindParam(1, $id_sucursal, PDO::PARAM_INT);
            $stmt_code->execute();
            $stmt_select_code = $this->db->query("SELECT @codigo_generado AS codigo_compra");
            $result_code = $stmt_select_code->fetch(PDO::FETCH_ASSOC);
            $codigo_compra = $result_code['codigo_compra'];

            $this->db->beginTransaction();

            $sql_compra = "INSERT INTO TB_COMPRA (codigo_compra, id_usuario, id_proveedor, id_sucursal, total, id_ubicacion_destino, tipo_ubicacion_destino, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_compra = $this->db->prepare($sql_compra);
            $stmt_compra->execute([$codigo_compra, $id_usuario, $id_proveedor, $id_sucursal, $total, $id_ubicacion_destino, $tipo_ubicacion_destino, 7]);
            $idCompra = $this->db->lastInsertId();

            foreach ($detalle as $item) {
                $this->registrarDetalleCompra(
                    $idCompra,
                    $item['id_producto'],
                    $item['precio'],
                    $item['cantidad']
                );
            }

            $this->db->commit();
            return $idCompra;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('CompraModel::registrarCompra -> ' . $e->getMessage());
            return false;
        }
    }
    public function registrarDetalleCompra($idCompra, $idProducto, $precioCompra, $cantidad)
    {
        $stmt = $this->db->prepare("CALL SP_INSERTAR_R_COMPRA_PRODUCTO(?, ?, ?, ?)");
        return $stmt->execute([$idCompra, $idProducto, $precioCompra, $cantidad]);
    }

    public function actualizarEstadoCompra($id_compra, $nuevo_estado)
    {
        try {
            $sql = "UPDATE TB_COMPRA SET activo = ? WHERE id_compra = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nuevo_estado, $id_compra]);
        } catch (PDOException $e) {
            error_log('CompraModel::actualizarEstadoCompra -> ' . $e->getMessage());
            return false;
        }
    }

    public function listarDestinosPorSucursal($id_sucursal)
    {
        try {
            $stmt = $this->db->prepare("CALL SP_LISTAR_DESTINOS_POR_SUCURSAL(?)");
            $stmt->execute([$id_sucursal]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('CompraModel::listarDestinosPorSucursal -> ' . $e->getMessage());
            return [];
        }
    }
    
    public function actualizarCompra($id_compra, $id_proveedor, $total, $detalle, $id_ubicacion_destino, $tipo_ubicacion_destino) {
        try {
            $this->db->beginTransaction();

            $sql_update_compra = "UPDATE TB_COMPRA SET id_proveedor = ?, total = ?, id_ubicacion_destino = ?, tipo_ubicacion_destino = ? WHERE id_compra = ?";
            $stmt_update = $this->db->prepare($sql_update_compra);
            $stmt_update->execute([$id_proveedor, $total, $id_ubicacion_destino, $tipo_ubicacion_destino, $id_compra]);

            $sql_delete_detalle = "DELETE FROM R_COMPRA_PRODUCTO WHERE id_compra = ?";
            $stmt_delete = $this->db->prepare($sql_delete_detalle);
            $stmt_delete->execute([$id_compra]);

            foreach ($detalle as $item) {
                $this->registrarDetalleCompra(
                    $id_compra,
                    $item['id_producto'],
                    $item['precio'], 
                    $item['cantidad']
                );
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('CompraModel::actualizarCompra -> ' . $e->getMessage());
            return false;
        }
    }


    public function listarComprasParaRecepcion()
    {
        $stmt = $this->db->prepare("CALL SP_GET_COMPRAS_PARA_RECEPCION()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDetalleConPendientes($id_compra)
    {
        $stmt = $this->db->prepare("CALL SP_GET_DETALLE_CON_PENDIENTES(?)");
        $stmt->execute([$id_compra]);
        $compra = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($compra && isset($compra['productos'])) {
            $compra['productos'] = json_decode($compra['productos'], true);
        }

        return $compra;
    }

    public function registrarRecepcionMejorada($id_compra, $id_sucursal, $id_usuario, $productos_recibidos_json, $evidencias_por_producto_json)
    {
        $stmt = $this->db->prepare("CALL SP_PROCESAR_RECEPCION_MEJORADA(?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $id_compra,
            $id_sucursal,
            $id_usuario,
            $productos_recibidos_json,
            $evidencias_por_producto_json
        ]);
        
        return true;
    }
}