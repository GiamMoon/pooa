<?php
class ModeloModel extends Model {

    /*INICIO ACCIONES */
        public function listarModelos() {
        $stmt = $this->db->prepare("CALL SP_GET_MODELOS_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }



    public function listarMotosActivas() {
        $stmt = $this->db->prepare("CALL CP_LISTAR_MOTOS_M_ACTIVAS()");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function listarModelosActivos() {
        $stmt = $this->db->prepare("CALL CP_LISTAR_MODELOS_M_ACTIVAS()");
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function obtenerDetalleModelo($idModelo) {
        $stmt = $this->db->prepare("CALL SP_GET_MODELO_DETALLE(?)");
        $stmt->execute([$idModelo]);
        return $stmt->fetch();
    }

    public function registrarModelo($nombre, $anio, $id_moto) {
        $stmt = $this->db->prepare("CALL SP_REGISTRAR_MODELO(?,?,?,?)");
        return $stmt->execute([$nombre, $anio, $id_moto, $_SESSION['id_usuario']]);
    }

    public function actualizarDatos($idModelo, $nombre, $anio, $id_moto) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_MODELO(?,?,?,?,?)");
        return $stmt->execute([$idModelo, $nombre, $anio, $id_moto, $_SESSION['id_usuario']]);
    }

    public function eliminarModelo($idModelo) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_ESTADO_MODELO(?)");
        return $stmt->execute([$idModelo]);
    }

    public function existeModelo($nombre, $idModelo = null) {
        $stmt = $this->db->prepare("CALL SP_VERIFICAR_MODELO(?,?)");
        $stmt->execute([$nombre, $idModelo]);
        return $stmt->fetchColumn(); // 0 o 1
    }
}