<?php
class ProveedorModel extends Model {
    /* INICIO: Para Acciones */
    public function listarProveedores() {
        $stmt = $this->db->prepare("CALL SP_GET_PROVEEDORES_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function registrarProveedor($ruc,$razon_social,$direccion,$departamento,$provincia,$distrito,$ubigeo,$telefono,$correo,$contacto,$estado_sunat,$condicion_sunat,$es_agente_retencion) {
        $stmt = $this->db->prepare("CALL SP_REGISTRAR_PROVEEDOR(?,?,?,?,?,?,?,?,?,?,?,?,?)");
        return $stmt->execute([$ruc,$razon_social,$direccion,$departamento,$provincia,$distrito,$ubigeo,$telefono,$correo,$contacto,$estado_sunat,$condicion_sunat,$es_agente_retencion]);
    }
    public function obtenerDetalleProveedores($idProveedor) {
        $stmt = $this->db->prepare("CALL SP_GET_PROVEEDOR_DETALLE(?)");
        $stmt->execute([$idProveedor]);
        return $stmt->fetch();
    }
    public function eliminarProveedor($idProveedor) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_ESTADO_PROVEEDOR(?)");
        return $stmt->execute([$idProveedor]);
    }
    public function actualizarProveedor($idProveedor,$ruc,$razon,$direccion,$departamento,$provincia,$distrito,$ubigeo,$telefono,$correo,$contacto,$estado_sunat,$condicion_sunat,$es_agente_retencion) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_PROVEEDOR(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        return $stmt->execute([$idProveedor,$ruc,$razon,$direccion,$departamento,$provincia,$distrito,$ubigeo,$telefono,$correo,$contacto,$estado_sunat,$condicion_sunat,$es_agente_retencion]);    
    }
    /* FIN: Para Acciones */





    /* INICIO: Para Validar existencias*/
    public function existeProveedor($ruc) {
        $stmt = $this->db->prepare("CALL SP_VERIFICAR_PROVEEDORES(?)");
        $stmt->execute([$ruc]);
        return $stmt->fetchColumn();
    }
    public function existeCorreoProv($correo,$idProveedor = null) {
        $stmt = $this->db->prepare("CALL SP_VERIFICAR_CORREO_PROVEEDOR(?,?)");
        $stmt->execute([$correo,$idProveedor]);
        return $stmt->fetchColumn();
    }    
    /* FIN: Para Validar existencias*/


}