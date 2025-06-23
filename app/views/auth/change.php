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

     <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="<?= base_url('assets/vendor/fonts/iconify-icons.css') ?>" />

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
        <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-12 p-6" style="background:white;height: 100%;position: absolute;right: 0;">
            <div class="w-px-400 mx-auto">
                <h4 class="mb-2"><?= $title ?> 🔒</h4>
                <p class="mb-4"><?= $subtitle ?></p>
                <form method="POST" id="passwordForm" action="<?= base_url('auth/save_password') ?>">
                    <!-- Campo Nueva Contraseña -->
                    <div class="input-group">
                        <input type="password" class="form-control" id="nueva_clave" name="nueva_clave" required autofocus placeholder="············">
                        <span id="generarClave" class="input-group-text cursor-pointer"><i class='icon-base bx bx-edit-alt'></i></span>
                        <span class="input-group-text cursor-pointer toggle-pass" data-target="nueva_clave">
                            <i class="icon-base bx bx-hide"></i>
                        </span>
                    </div>
                    <br>
                    <!-- Campo Confirmar Contraseña -->
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirmar_clave" name="confirmar_clave" required autofocus placeholder="············"/>
                        <span class="input-group-text cursor-pointer toggle-pass" data-target="confirmar_clave">
                            <i class="icon-base bx bx-hide"></i>
                        </span>
                    </div>
                    <div class=" mt-4">
                        <button type="submit" class="btn btn-primary d-grid w-100">Guardar</button>
                    </div>
                </form>
                <div id="contenedor-alertas" class="mt-3"></div>
                <?php if (!empty($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                  <i class="bx bx-error-circle me-2"></i>
                  <?= htmlspecialchars($_GET['error']) ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
function validarContrasena(password) {
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])(?!.*\s).{8,}$/;
    return regex.test(password);
}

function mostrarAlerta(mensaje, tipo = 'info') {
    const contenedor = document.getElementById('contenedor-alertas');

    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show`;
    alerta.role = 'alert';

    alerta.innerHTML = `
        <i class='bx bx-error-circle me-2'></i> ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    contenedor.appendChild(alerta);
}

document.getElementById('passwordForm').addEventListener('submit', function (e) {
    const nueva = document.getElementById('nueva_clave').value;
    const confirmar = document.getElementById('confirmar_clave').value;

    // Limpiar alertas anteriores
    document.getElementById('contenedor-alertas').innerHTML = '';

    if (!validarContrasena(nueva)) {
        e.preventDefault();
        mostrarAlerta('La contraseña debe tener al menos:<br>• 8 caracteres<br>• 1 minúscula<br>• 1 mayúscula<br>• 1 número<br>• 1 carácter especial<br>• Sin espacios');
        return;
    }

    if (nueva !== confirmar) {
        e.preventDefault();
        mostrarAlerta('Las contraseñas no coinciden.');
        return;
    }
});

function generarContrasenaSegura() {
    const longitud = 12;
    const mayusculas = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const minusculas = "abcdefghijklmnopqrstuvwxyz";
    const numeros = "0123456789";
    const especiales = "!@#$%^&*()-_=+[]{}|;:',.<>?";
    const todos = mayusculas + minusculas + numeros + especiales;

    let clave = [
        mayusculas[Math.floor(Math.random() * mayusculas.length)],
        minusculas[Math.floor(Math.random() * minusculas.length)],
        numeros[Math.floor(Math.random() * numeros.length)],
        especiales[Math.floor(Math.random() * especiales.length)]
    ];

    for (let i = clave.length; i < longitud; i++) {
        clave.push(todos[Math.floor(Math.random() * todos.length)]);
    }

    clave = clave.sort(() => Math.random() - 0.5).join("");

    document.getElementById('nueva_clave').value = clave;
    document.getElementById('confirmar_clave').value = clave;

    // Limpiar alertas anteriores
    document.getElementById('contenedor-alertas').innerHTML = '';
}

document.getElementById('generarClave').addEventListener('click', generarContrasenaSegura);
</script>
<script>
document.querySelectorAll('.toggle-pass').forEach(btn => {
    btn.addEventListener('click', () => {
        const inputId = btn.getAttribute('data-target');
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bx-hide');
            icon.classList.add('bx-show');
        } else {
            input.type = 'password';
            icon.classList.remove('bx-show');
            icon.classList.add('bx-hide');
        }
    });
});
</script>

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
            mostrarAlerta('Las contraseñas no coinciden');
        }
    });
</script>
</body>
</html>
