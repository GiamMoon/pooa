<?php
class ProductoModel extends Model {
    public function listarProductos() {
        $stmt = $this->db->prepare("CALL SP_GET_PRODUCTOS_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function registrarProducto($idCategoria, $idMarca, $idUnidad, $nombre, $descripcion, $imagen, $pVenta)
    {
        try {
            // Preparar la llamada al procedimiento almacenado
            $this->db->beginTransaction();
            
            // Primero llamamos al procedimiento
            $stmt = $this->db->prepare("CALL SP_REGISTRAR_PRODUCTO(?, ?, ?, ?, ?, ?, ?, @codigo)");
            $stmt->execute([$idCategoria, $idMarca, $idUnidad, $nombre, $descripcion, $imagen, $pVenta]);

            // Luego obtenemos el código generado
            $stmt = $this->db->query("SELECT @codigo AS codigo");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->db->commit();
            
            return [
                'success' => !empty($result['codigo']),
                'codigo' => $result['codigo'] ?? null
            ];
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error al registrar producto: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function actualizarImagenProducto($codigoProducto, $nombreArchivo)
    {
        $stmt = $this->db->prepare("UPDATE TB_PRODUCTO SET url_imagen = ? WHERE codigo_producto = ?");
        return $stmt->execute([$nombreArchivo, $codigoProducto]);
    }





    public function listarMotosconModelos() {
        $stmt = $this->db->prepare("CALL SP_LISTAR_MARCAS_CON_MODELOS()");
        $stmt->execute();
        return $stmt->fetchAll();
    }


}