<?php
class RolModel extends Model
{

    public function listarRoles()
    {
        $stmt = $this->db->prepare("CALL SP_GET_ROLES_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function listarRolesActivos()
    {
        $stmt = $this->db->prepare("CALL SP_GET_ROLES_ACTIVOS()");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function listarPermisos()
    {
        $stmt = $this->db->prepare("CALL SP_GET_PERMISOS_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function listarRecursos()
    {
        $stmt = $this->db->prepare("CALL SP_GET_RECURSOS_TB()");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function registrarRol($nombre, $jsonAsignaciones)
    {
        $stmt = $this->db->prepare("CALL SP_REGISTRAR_ROL_CON_PERMISOS(?, ?)");
        return $stmt->execute([$nombre, $jsonAsignaciones]);
    }

    public function detalleRol($idRol)
    {
        $stmt = $this->db->prepare("CALL SP_GET_DETALLE_ROL(?)");
        $stmt->execute([$idRol]);

        // Primer result: Datos Rol
        $rol = $stmt->fetch();

        // Segundo result: permisos asignados
        $stmt->nextRowset();
        $permisosAsignados = $stmt->fetchAll();

        // Tercer result: todos los permisos
        $stmt->nextRowset();
        $todosPermisos = $stmt->fetchAll();

        //Cuarto result: todos los recursos
        // Siguiente result set: todos los recursos
        $stmt->nextRowset();
        $todosRecursos = $stmt->fetchAll();

        return [
            'rol' => $rol,
            'permisosAsignados' => $permisosAsignados,
            'todosPermisos' => $todosPermisos,
            'todosRecursos' => $todosRecursos
        ];
    }



    public function actualizarRol($idRol, $nombre, $jsonAsignaciones)
    {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_ROL_CON_PERMISOS(?, ?, ?)");
        return $stmt->execute([$idRol, $nombre, $jsonAsignaciones]);
    }


    // Para Eliminar
    public function contarUsuariosPorRol($idRol) {
        $stmt = $this->db->prepare("CALL SP_CONTAR_USUARIOS_POR_ROL(?)");
        $stmt->execute([$idRol]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function reasignarUsuariosAInvitado($idRol) {
        $stmt = $this->db->prepare("CALL SP_REASIGNAR_USUARIOS_A_INVITADO(?)");
        return $stmt->execute([$idRol]);
    }

    public function reasignarUsuariosAOtroRol($idRolActual, $idRolNuevo) {
        $stmt = $this->db->prepare("CALL SP_REASIGNAR_USUARIOS_A_OTRO_ROL(?, ?)");
        return $stmt->execute([$idRolActual, $idRolNuevo]);
    }

    public function eliminarRol($idRol) {
        $stmt = $this->db->prepare("CALL SP_ACTUALIZAR_ESTADO_ROL(?)");
        return $stmt->execute([$idRol]);
    }

    public function listarUsuariosPorRol($idRol) {
        $stmt = $this->db->prepare("CALL SP_LISTAR_USUARIOS_POR_ROL(?)");
        $stmt->execute([$idRol]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function reasignarRolPorUsuario($idUsuario, $nuevoRol) {
        $stmt = $this->db->prepare("CALL SP_REASIGNAR_ROL_POR_USUARIO(?, ?)");
        return $stmt->execute([$idUsuario, $nuevoRol]);
    }

    public function listarRolesExcepto($ex1, $ex2) {
        $stmt = $this->db->prepare("CALL SP_LISTAR_ROLES_EXCEPTO(?, ?)");
        $stmt->execute([$ex1, $ex2]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function existeRol($nombre,$idRol = null) {
        $stmt = $this->db->prepare("CALL SP_VERIFICAR_ROL(?,?)");
        $stmt->execute([$nombre,$idRol]);
        return $stmt->fetchColumn();
    }

}