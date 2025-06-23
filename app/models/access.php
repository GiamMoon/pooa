<?php
require_once __DIR__ . '/../core/Model.php';

class Access extends Model {
    public function check($action,$controlador){
        $idUsuario = $_SESSION['is_usuario'] ?? null;
        if(!$idUsuario){return false;}

        $accionesValidas = ['visualizar','editar','registrar','eliminar','exportar'];
        if (!in_array($action, $accionesValidas)) {
            throw new Exception('Acción no permitida');
        }
        
        
    }
}