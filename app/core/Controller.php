<?php
class Controller {
    public function model($model) {
        require_once "../app/models/$model.php";
        return new $model();
    }
    
    public function view($view, $data = [], $useLayout= true) {
        extract($data);
        $viewPath = "../app/views/{$view}.php";
        
        if ($useLayout) {
            require_once "../app/views/layout/layout.php";
        } else {
            require $viewPath;
        }
        
    }

    public function redirect($url) {
        header("Location: $url");
        exit;
    }

}