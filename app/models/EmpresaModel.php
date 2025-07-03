<?php
class EmpresaModel extends Model {

    public function listarEmpresa() {
        $stmt = $this->db->prepare("CALL SP_GET_EMPRESA_TB()");        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDetalleEmpresa($idEmpresa) {
        $stmt = $this->db->prepare("CALL SP_GET_EMPRESA_DETALLE(?)");
        $stmt->execute([$idEmpresa]);
        return $stmt->fetch();
    }

    public function actualizarDatos($idEmpresa,$razonsocial,$ruc,$direccion,$dep,$prov,$dis) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_EMPRESA(?,?,?,?,?,?,?)");
        return $stmt->execute([$idEmpresa,$razonsocial,$ruc,$direccion,$dep,$prov,$dis]);    
    }
}