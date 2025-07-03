<?php

class SucursalModel extends Model {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Obtiene todas las sucursales activas de la base de datos.
     * Es utilizado por el administrador para seleccionar en qué sucursal realizar una compra.
     *
     * @return array Lista de sucursales activas.
     */
    public function getSucursalesActivas() {
        try {
            $sql = "SELECT id_sucursal, CONCAT('S',id_sucursal,': ',direccion) AS nombre_comercial FROM TB_SUCURSAL WHERE activo = 1 ORDER BY nombre_comercial ASC";//cambié direccion pon nombre_comercial
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('SucursalModel::getSucursalesActivas -> ' . $e->getMessage());
            return [];
        }
    }

    public function listarSucursalesTB() {
        $stmt = $this->db->prepare("CALL SP_GET_SUCURSALES_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function registrarDatos($nombre,$direccion,$dep,$prov,$dis) {
        $stmt = $this->db->prepare("CALL SP_REGISTRAR_SUCURSAL_ALMACEN(?,?,?,?,?)");
        return $stmt->execute([$nombre,$direccion,$dep,$prov,$dis]);
    }
    public function obtenerDetalle($idSucursal) {
        $stmt = $this->db->prepare("CALL SP_GET_SUCURSAL_DETALLE(?)");
        $stmt->execute([$idSucursal]);
        return $stmt->fetch();
    }

    public function actualizarDatos($idSucursal,$nombre,$direccion,$dep,$prov,$dis) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_SUCURSAL_ALMACEN(?,?,?,?,?,?)");
        return $stmt->execute([$idSucursal,$nombre,$direccion,$dep,$prov,$dis]);    
    }

    public function eliminarSucursalCompleto($idSucursal) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_ESTADO_SUCURSAL_CONJUNTO(?)");
        return $stmt->execute([$idSucursal]);
    }


    public function listarSucursales() {
        $stmt = $this->db->prepare("CALL SP_GET_SUCURSALES()");
        $stmt->execute();
        return $stmt->fetchAll();
    }


}