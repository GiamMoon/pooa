<?php
class ProductoModel extends Model {
    public function listarProductos() {
        $stmt = $this->db->prepare("CALL SP_GET_PRODUCTOS_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }
  
    public function registrarProducto($idCategoria,$idMarca,$idUnidad,$nombre,$descripcion,$imagen,$PVenta,$CantMin,$CantMax, $jsonModelos)
    {
        $stmt = $this->db->prepare("CALL SP_REGISTRAR_PRODUCTO_COMPLETO(?,?,?,?,?,?,?,?,?,?)");
        return $stmt->execute([$idCategoria,$idMarca,$idUnidad,$nombre,$descripcion,$imagen,$PVenta,$CantMin,$CantMax, $jsonModelos]);
    }


    public function listarMotosconModelos() {
        $stmt = $this->db->prepare("CALL SP_LISTAR_MARCAS_CON_MODELOS()");
        $stmt->execute();
        return $stmt->fetchAll();
    }




}