<?php
class MotoModel extends Model {
    /*INICIO ACCIONES */
        public function listarMotos() {
        $stmt = $this->db->prepare("CALL SP_GET_MOTOS_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function obtenerDetalleMoto($idMoto) {
        $stmt = $this->db->prepare("CALL SP_GET_MOTO_DETALLE(?)");
        $stmt->execute([$idMoto]);
        return $stmt->fetch();
    }
    public function registrarMoto($nombre) {
        $stmt = $this->db->prepare("CALL SP_REGISTRAR_MOTO(?,?)");
        return $stmt->execute([$nombre,$_SESSION['id_usuario']]);
    }  
    public function actualizarDatos($idMoto,$nombre) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_MOTO(?,?,?)");
        return $stmt->execute([$idMoto,$nombre,$_SESSION['id_usuario']]);
    }
    public function eliminarMoto($idMoto) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_ESTADO_MOTO(?)");
        return $stmt->execute([$idMoto]);
    }//FALTA AÑADIR CON PRODUCTOS
    /*FIN ACCIONES */


    /*VERIFICACIONES*/
    public function existeMoto($nombre, $idMoto = null) {
        $stmt = $this->db->prepare("CALL SP_VERIFICAR_MOTO(?,?)");
        $stmt->execute([$nombre, $idMoto]);
        return $stmt->fetchColumn(); // Devuelve 0 o 1
    }    
}