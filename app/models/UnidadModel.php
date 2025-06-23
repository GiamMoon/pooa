<?php
class UnidadModel extends Model {
    //Para tabla
    public function listarUnidades() {
        $stmt = $this->db->prepare("CALL SP_GET_UNIDAD_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function registrarUnidad($nombre,$abreviatura,$codigo_sunat,$decripcion) {
        $stmt = $this->db->prepare("CALL SP_REGISTRAR_UNIDAD(?,?,?,?,?)");
        return $stmt->execute([$nombre,$abreviatura,$codigo_sunat,$decripcion,$_SESSION['id_usuario']]);
    }
    public function obtenerDetalleUnidad($idUnidad) {
        $stmt = $this->db->prepare("CALL SP_GET_UNIDAD_DETALLE(?)");
        $stmt->execute([$idUnidad]);
        return $stmt->fetch();
    }
    public function actualizarDatos($idUnidad,$nombre,$abreviatura,$codigo_sunat,$decripcion) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_UNIDAD(?,?,?,?,?,?)");
        return $stmt->execute([$idUnidad,$nombre,$abreviatura,$codigo_sunat,$decripcion,$_SESSION['id_usuario']]);        
    }
    public function eliminarUnidad($idUnidad) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_ESTADO_UNIDAD(?)");
        return $stmt->execute([$idUnidad]);
    }

        public function listarUnidadesActivas() {
        $stmt = $this->db->prepare("CALL CP_LISTAR_UNIDADES_ACTIVAS()");
        $stmt->execute();
        return $stmt->fetchAll();
    }




    public function existeunidad($nombre,$idUnidad = null) {
        $stmt = $this->db->prepare("CALL SP_VERIFICAR_UNIDAD(?,?)");
        $stmt->execute([$nombre,$idUnidad]);
        return $stmt->fetchColumn();
    }
}