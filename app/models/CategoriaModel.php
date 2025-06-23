<?php
class CategoriaModel extends Model {
    /*INICIO ACCIONES */
    public function listarCategorias() {
        $stmt = $this->db->prepare("CALL SP_GET_CATEGORIAS_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function obtenerDetalleCategoria($idCategoria) {
        $stmt = $this->db->prepare("CALL SP_GET_CATEGORIA_DETALLE(?)");
        $stmt->execute([$idCategoria]);
        return $stmt->fetch();
    }
    public function registrarCategoria($nombre,$descripcion) {
        $stmt = $this->db->prepare("CALL SP_REGISTRAR_CATEGORIA(?,?,?)");
        return $stmt->execute([$nombre,$descripcion,$_SESSION['id_usuario']]);
    }  
    public function actualizarDatos($idCategoria,$nombre,$descripcion) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_CATEGORIA(?,?,?,?)");
        return $stmt->execute([$idCategoria,$nombre,$descripcion,$_SESSION['id_usuario']]);
    }
    public function eliminarCategoria($idCategoria) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_ESTADO_CATEGORIA(?)");
        return $stmt->execute([$idCategoria]);
    }//FALTA AÑADIR CON PRODUCTOS
    /*FIN ACCIONES */


    public function listarCategoriasActivas() {
        $stmt = $this->db->prepare("CALL CP_LISTAR_CATEGORIAS_ACTIVAS()");
        $stmt->execute();
        return $stmt->fetchAll();
    }


    /*VERIFICACIONES*/
    public function existeCategoria($nombre, $idCategoria = null) {
        $stmt = $this->db->prepare("CALL SP_VERIFICAR_CATEGORIA(?,?)");
        $stmt->execute([$nombre, $idCategoria]);
        return $stmt->fetchColumn(); // Devuelve 0 o 1
    }

}