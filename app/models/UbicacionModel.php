<?php
class UbicacionModel extends Model {
    public function listarUbicaciones() {
        $stmt = $this->db->prepare("CALL SP_GET_UBICACION_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function registrarUbicacion($direccion, $telefono, $tipo_ubicacion) {
        $stmt = $this->db->prepare("CALL SP_REGISTRAR_UBICACION(?,?,?,?)");
        return $stmt->execute([$direccion, $telefono, $tipo_ubicacion, $_SESSION['id_usuario']]);
    }

    public function obtenerDetalleUbicacion($idUbicacion) {
        $stmt = $this->db->prepare("CALL SP_GET_UBICACION_DETALLE(?)");
        $stmt->execute([$idUbicacion]);
        return $stmt->fetch();
    }

    public function actualizarUbicacion($idUbicacion, $direccion, $telefono, $tipo_ubicacion) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_UBICACION(?,?,?,?,?)");
        return $stmt->execute([$idUbicacion, $direccion, $telefono, $tipo_ubicacion, $_SESSION['id_usuario']]);
    }

    public function eliminarUbicacion($idUbicacion) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_ESTADO_UBICACION(?)");
        return $stmt->execute([$idUbicacion]);
    }

    public function existeUbicacion($direccion, $idUbicacion = null) {
        $stmt = $this->db->prepare("CALL SP_VERIFICAR_UBICACION(?,?)");
        $stmt->execute([$direccion, $idUbicacion]);
        return $stmt->fetchColumn();
    }
    
}