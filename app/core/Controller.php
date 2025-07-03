<?php
class Controller {
    public function model($model) {
        require_once "../app/models/$model.php";
        return new $model();
    }
    
    public function view($view, $data = [], $useLayout = true) {
        extract($data);
        

        $viewPath = dirname(__DIR__) . "/views/{$view}.php";
        
        if (file_exists($viewPath)) {
            if ($useLayout) {
                require_once dirname(__DIR__) . "/views/layout/layout.php";
            } else {
                require $viewPath;
            }
        } else {
            die("Error crítico: La vista no existe en la ruta especificada: " . $viewPath);
        }
    }

    public function redirect($url) {
        header("Location: $url");
        exit;
    }
}