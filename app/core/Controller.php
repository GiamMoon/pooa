<?php

class Controller
{
    /**
     * Carga e instancia un modelo.
     *
     * @param string $model El nombre del modelo a cargar (ej. 'UsuarioModel').
     * @return object La instancia del modelo.
     */
    public function model(string $model): object
    {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    /**
     * Carga una vista y la envuelve en el layout principal.
     *
     * @param string $view La ruta de la vista a cargar (ej. 'dashboard/index').
     * @param array $data Datos para pasar a la vista.
     */
    public function view(string $view, array $data = []): void
    {
        $viewPath = '../app/views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            die('Error: La vista no existe: ' . $viewPath);
        }

        extract($data);

        $layoutPath = '../app/views/layout/layout.php';
        if (file_exists($layoutPath)) {
            require_once $layoutPath;
        } else {
            die('Error: El archivo de layout principal no se encuentra.');
        }
    }


    protected function setJsonResponse(): void
    {
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    }
}
