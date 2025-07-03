<?php
class UsuarioModel extends Model{
    /* INICIO: Para Acceso */
    public function login($usuario) {
        $stmt = $this->db->prepare("CALL SP_LOGIN_USUARIO(?)");
        $stmt->execute([$usuario]);
        return $stmt->fetch();
    }
    public function obtenerPermisosUsuario($idUsuario) {
        $stmt = $this->db->prepare("CALL SP_LISTAR_RECURSOS_PERMISOS_USUARIO(?)");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }
    /* FIN: Para Acceso */



    /* INICIO: Para Acciones */
    public function listarUsuarios() {
        $idSucursal = $_SESSION['id_sucursal'] ?? null;

        $stmt = $this->db->prepare("CALL SP_GET_USUARIOS_TB(?)");
        $stmt->bindValue(1, $idSucursal, is_null($idSucursal) ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function obtenerDetalleUsuario($idUsuario) {
        $stmt = $this->db->prepare("CALL SP_GET_USUARIO_DETALLE(?)");
        $stmt->execute([$idUsuario]);
        return $stmt->fetch();
    }

    public function obtenerDetallePerfil() {
        $idU = $_SESSION['id_usuario'];

        $stmt = $this->db->prepare("CALL SP_GET_USUARIO_DETALLE(?)");
        $stmt->bindValue(1,$idU);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function obtenercontrasena() {
        $idU = $_SESSION['id_usuario'];

        $stmt = $this->db->prepare("SELECT contrasena FROM TB_USUARIO WHERE id_usuario = ?");
        $stmt->bindValue(1, $idU, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);        
    }


    public function listarDepartamentos() {        
        $stmt = $this->db->prepare("CALL SP_LISTAR_DEPARTAMENTOS()");        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarProvincias($dep) {        
        $stmt = $this->db->prepare("CALL SP_LISTAR_PROVINCIAS_POR_DEPARTAMENTO(?)");
        $stmt->bindParam(1,$dep);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarDistritos($dep,$prov) {        
        $stmt = $this->db->prepare("CALL SP_LISTAR_DISTRITOS_POR_PROVINCIA(?,?)");
        $stmt->bindParam(1, $dep);
        $stmt->bindParam(2, $prov);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function listarEstados1() {
        $stmt = $this->db->prepare("CALL SP_GET_ESTADOS_TB1()");
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function registrarUsuario($nombre,$apellido,$dni,$direccion,$telefono,$correo,$usuario,$contrasena,$idRol,$idSucursal,$dep,$prov,$dis,$fechaLimite) {
        $stmt = $this->db->prepare("CALL SP_REGISTRAR_USUARIO(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        return $stmt->execute([$nombre,$apellido,$dni,$direccion,$telefono,$correo,$usuario,$contrasena,$idRol,$idSucursal,$dep,$prov,$dis,$fechaLimite]);
    }
    public function actualizarDatos($idUsuario,$nombre,$apellido,$dni,$direccion,$telefono,$correo,$nombreusuario,$idRol,$idSucursal,$dep,$prov,$dis,$fechaLimite) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_USUARIO(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        return $stmt->execute([$idUsuario,$nombre,$apellido,$dni,$direccion,$telefono,$correo,$nombreusuario,$idRol,$idSucursal,$dep,$prov,$dis,$fechaLimite]);    
    }
    public function eliminarUsuario($idUsuario) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_ESTADO_USUARIO(?)");
        return $stmt->execute([$idUsuario]);
    }
    /* FIN: Para Acciones */
    


    /* INICIO: Para Validar existencias*/
    public function existeUsuario($nombre_usuario,$idUsuario = null) {
        $stmt = $this->db->prepare("CALL SP_VERIFICAR_USUARIOS(?,?)");
        $stmt->execute([$nombre_usuario,$idUsuario]);
        return $stmt->fetchColumn();
    }
    public function existeCorreo($correo,$idUsuario = null) {
        $stmt = $this->db->prepare("CALL SP_VERIFICAR_CORREOS(?,?)");
        $stmt->execute([$correo,$idUsuario]);
        return $stmt->fetchColumn();
    }
    public function existePersona($dni) {
        $stmt = $this->db->prepare("CALL SP_VERIFICAR_DNI(?)");
        $stmt->execute([$dni]);
        return $stmt->fetchColumn();
    }
    /* FIN: Para Validar existencias*/



    /* INICIO: Para Recuperacion y cambio (auth)*/
    public function setRecoveryCode($email, $code) {
        $stmt = $this->db->prepare("CALL sp_insertar_codigo(?, ?)");
        return $stmt->execute([$email, $code]);
    } 
    public function validarCodigo($email, $codigo) {
        $stmt = $this->db->prepare("CALL sp_validar_codigo(?, ?, @valido)");
        $stmt->execute([$email, $codigo]);
        $result = $this->db->query("SELECT @valido AS valido")->fetch();
        return (bool)$result['valido'];
    }
    public function findByEmail($email) {
        $stmt = $this->db->prepare("CALL SP_BUSCAR_USUARIO_POR_CORREO(?)");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor(); // importante para liberar conexión y permitir próximas consultas - considera agregar
        return $result; 
    }


    public function actualizarClave($idUsuario,$nuevaClave) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_CONTRASENA(?,?)");
        return $stmt->execute([$idUsuario,$nuevaClave]);
    }
    /* FIN: Para Recuperacion y cambio (auth)*/

    public function actualizarDatos1($idUsuario,$nombreusuario,$correo,$telefono) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_DATOS_1(?,?,?,?)");
        return $stmt->execute([$idUsuario,$nombreusuario,$correo,$telefono]);
    }
    public function actualizarDatos2($idUsuario,$direccion,$dep,$prov,$dis) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_DATOS_2(?,?,?,?,?)");
        return $stmt->execute([$idUsuario,$direccion,$dep,$prov,$dis]);
    }


}