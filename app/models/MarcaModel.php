<?php
class MarcaModel extends Model {
    /*INICIO ACCIONES */
        public function listarMarcas() {
        $stmt = $this->db->prepare("CALL SP_GET_MARCAS_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function obtenerDetalleMarca($idMarca) {
        $stmt = $this->db->prepare("CALL SP_GET_MARCA_DETALLE(?)");
        $stmt->execute([$idMarca]);
        return $stmt->fetch();
    }
    public function registrarMarca($nombre) {
        $stmt = $this->db->prepare("CALL SP_REGISTRAR_MARCA(?,?)");
        return $stmt->execute([$nombre,$_SESSION['id_usuario']]);
    }  
    public function actualizarDatos($idMarca,$nombre) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_MARCA(?,?,?)");
        return $stmt->execute([$idMarca,$nombre,$_SESSION['id_usuario']]);
    }
    public function eliminarMarca($idMarca) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_ESTADO_MARCA(?)");
        return $stmt->execute([$idMarca]);
    }//FALTA AÑADIR CON PRODUCTOS
    /*FIN ACCIONES */


    public function listarMarcasActivas() {
        $stmt = $this->db->prepare("CALL CP_LISTAR_MARCAS_P_ACTIVAS()");
        $stmt->execute();
        return $stmt->fetchAll();
    }


    /*VERIFICACIONES*/
    public function existeMarca($nombre, $idMarca = null) {
        $stmt = $this->db->prepare("CALL SP_VERIFICAR_MARCA(?,?)");
        $stmt->execute([$nombre, $idMarca]);
        return $stmt->fetchColumn(); // Devuelve 0 o 1
    }    
}