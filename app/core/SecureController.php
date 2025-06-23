<?php
class SecureController extends Controller{
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL('auth/login'));
            exit;
        }
    }    

    public function index() {
        header('Location: ' . BASE_URL('public/index'));
        /*if (!isset($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL('auth/login'));
            exit;
        }*/
    }
}