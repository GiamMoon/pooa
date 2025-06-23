<?php
class SeguridadController extends Controller {
    public function usuario() {
        $this->view('seguridad/usuario',[
            'title' => 'Usuarios',
            'activePage' => 'usuario',
            'openMenu' => 'seguridad',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css',
                'assets/vendor/libs/@form-validation/form-validation.css',
                'assets/vendor/libs/quill/typography.css',
                'assets/vendor/libs/quill/katex.css',
                'assets/vendor/libs/quill/editor.css'
            ],
            'vendors' => [
                'assets/vendor/libs/moment/moment.js',
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js' ,
                'assets/vendor/libs/select2/select2.js',
                'assets/vendor/libs/@form-validation/popular.js',
                'assets/vendor/libs/@form-validation/bootstrap5.js',
                'assets/vendor/libs/@form-validation/auto-focus.js',
                'assets/vendor/libs/quill/katex.js',
                'assets/vendor/libs/quill/quill.js'
            ],
            'scripts' => ['assets/js/app-usuario-lista.js'],
            'permisos' => $_SESSION['permisos']
        ]);   
    }

    public function rol() {
        $this->view('seguridad/rol',[
            'title' => 'Roles',
            'activePage' => 'rol',
            'openMenu' => 'seguridad',
            'styles' => [
                'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css',
                'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css',
                'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css',
                'assets/vendor/libs/select2/select2.css',
                'assets/vendor/libs/@form-validation/form-validation.css',
                'assets/vendor/libs/quill/typography.css',
                'assets/vendor/libs/quill/katex.css',
                'assets/vendor/libs/quill/editor.css'
            ],
            'vendors' => [
                'assets/vendor/libs/moment/moment.js',
                'assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js' ,
                'assets/vendor/libs/select2/select2.js',
                'assets/vendor/libs/@form-validation/popular.js',
                'assets/vendor/libs/@form-validation/bootstrap5.js',
                'assets/vendor/libs/@form-validation/auto-focus.js',
                'assets/vendor/libs/quill/katex.js',
                'assets/vendor/libs/quill/quill.js'
            ],
            'scripts' => ['assets/js/app-rol-lista.js'],
            'permisos' => $_SESSION['permisos']
        ]);   
    }

}