
<!doctype html>
<html lang="en" class=" layout-wide  customizer-hide" dir="ltr" data-skin="default" data-assets-path="../../assets/" data-template="vertical-menu-template" data-bs-theme="light">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Demo: Forgot Password Cover - Pages | sneat - Bootstrap Dashboard PRO</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/vendor/fonts/iconify-icons.css" />
    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->
    <link rel="stylesheet" href="../assets/vendor/libs/pickr/pickr-themes.css" />
    <link rel="stylesheet" href="../assets/vendor/css/core.css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <!-- endbuild -->
    <!-- Vendor -->
    <link rel="stylesheet" href="../assets/vendor/libs/@form-validation/form-validation.css" />
    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="../assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>
  <body>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0" style="display: none; visibility: hidden"></iframe></noscript>
    <!-- Content -->
    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">
        <!-- /Left Text -->
        <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
            <div class="w-100 d-flex justify-content-center">
                <img src="<?= base_url('assets/img/illustrations/cmp.png') ?>" class="img-fluid" alt="Login image" width="700" data-app-dark-img="illustrations/cmp.png" data-app-light-img="illustrations/cmp.png" />
            </div>
        </div>
        <!-- /Left Text -->  
        <!-- Forgot Password -->
        <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-12 p-6" style="background:white;height: 100%;position: absolute;right: 0;">
            <div class="w-px-400 mx-auto mt-sm-12 mt-8">
              <h4 class="mb-1">¿Olvidaste tu contraseña? 🔒</h4>
              <p class="mb-6">Ingresa tu correo electrónico, te enviaremos un codigo de verificación</p>
              <form id="formAuthentication" class="mb-6" action="<?= BASE_URL('auth/recover') ?>" method="POST">
                  <div class="mb-6 form-control-validation">
                  <label for="email" class="form-label">Email</label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" autofocus />
                  </div>
                  <button class="btn btn-primary d-grid w-100">Enviar</button>
              </form>
              <div class="text-center">
                  <a href="<?= BASE_URL('auth/') ?>" class="d-flex align-items-center justify-content-center">
                  <i class="bx bx-chevron-left icon-20px scaleX-n1-rtl me-1_5 align-top"></i> Regresar a login
                  </a>
              </div>
              <?php if (!empty($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                  <i class="bx bx-error-circle me-2"></i>
                  <?= htmlspecialchars($_GET['error']) ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>
            </div>
           
        </div>
        <!-- /Forgot Password --> 
        </div>
    </div> 
    <!-- / Content -->
   
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/theme.js  -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/@algolia/autocomplete-js.js"></script>
    <script src="../assets/vendor/libs/pickr/pickr.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/libs/hammer/hammer.js"></script>
    <script src="../assets/vendor/libs/i18n/i18n.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="../assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="../assets/vendor/libs/@form-validation/auto-focus.js"></script>
    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>
    <!-- Page JS -->
    <!--<script src="../assets/js/pages-auth.js"></script>-->
  </body>
</html>

