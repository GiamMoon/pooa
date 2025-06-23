<!DOCTYPE html>
<html lang="es" data-assets-path="<?= base_url('assets/') ?>" data-template="vertical-menu-template" class="light-style layout-wide">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon/cmp.ico') ?>" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans&display=swap" rel="stylesheet" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/css/core.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/vendor/css/theme-default.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/demo.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/vendor/css/pages/page-auth.css') ?>" />

    <!-- Helpers -->
    <script src="<?= base_url('assets/vendor/js/helpers.js') ?>"></script>
    <script src="<?= base_url('assets/js/config.js') ?>"></script>
</head>
<body>
<div class="authentication-wrapper authentication-cover">
    <div class="authentication-inner row m-0">
        <!-- Imagen lateral -->
        <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
            <div class="w-100 d-flex justify-content-center">
                <img src="<?= base_url('assets/img/illustrations/cmp.png') ?>" class="img-fluid" alt="Imagen" width="700" />
            </div>
        </div>

        <!-- Formulario -->
        <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-12 p-6" style="background:white; height: 100%;">
            <div class="w-px-400 mx-auto">
                <h4 class="mb-2">Cambiar Contraseña 🔒</h4>
                <p class="mb-4">Por seguridad, debes actualizar tu contraseña antes de continuar.</p>

                <form method="POST" action="<?= base_url('login/guardarNuevaClave') ?>">
                    <div class="mb-3">
                        <label for="nueva_clave" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="nueva_clave" name="nueva_clave" required autofocus />
                    </div>
                    <div class="mb-3">
                        <label for="confirmar_clave" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="confirmar_clave" required />
                    </div>

                    <button type="submit" class="btn btn-primary d-grid w-100">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Core JS -->
<script src="<?= base_url('assets/vendor/libs/jquery/jquery.js') ?>"></script>
<script src="<?= base_url('assets/vendor/js/bootstrap.js') ?>"></script>
<script src="<?= base_url('assets/vendor/js/menu.js') ?>"></script>
<script>
    // Validación básica de coincidencia
    document.querySelector('form').addEventListener('submit', function (e) {
        const clave1 = document.getElementById('nueva_clave').value;
        const clave2 = document.getElementById('confirmar_clave').value;
        if (clave1 !== clave2) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
        }
    });
</script>
</body>
</html>
