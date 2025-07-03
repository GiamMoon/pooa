<?php
class AlmacenModel extends Model {
    public function listarAlmacenesTB() {
        $stmt = $this->db->prepare("CALL SP_GET_ALMACENES_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function registrarDatos($idSucursal,$direccion,$telefono,$dep,$prov,$dis) {
        $stmt = $this->db->prepare("CALL SP_REGISTRAR_ALMACEN(?,?,?,?,?,?)");
        return $stmt->execute([$idSucursal,$direccion,$telefono,$dep,$prov,$dis]);
    }
}