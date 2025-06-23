<!doctype html>
<html lang="es" class=" layout-menu-fixed layout-compact" data-template="vertical-menu-template-free">
  <?php require 'head.php'; ?>
<body>
    <!-- ?PROD Only: Google Tag Manager (noscript) (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0" style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
      <div class="layout-container">
        <?php require_once 'aside.php'; ?>
        <?php require_once 'topbar.php'; ?>
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <?php require $viewPath; ?>
            <?php require 'footer.php'; ?>
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/theme.js  -->
    <script src="<?= base_url('assets/vendor/libs/jquery/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/libs/popper/popper.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/js/bootstrap.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/libs/@algolia/autocomplete-js.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/libs/pickr/pickr.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/libs/hammer/hammer.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/js/menu.js') ?>"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <?php if (!empty($vendors)): ?>
    <?php foreach ($vendors as $vendor): ?>
        <script src="<?= base_url($vendor) ?>"></script>
      <?php endforeach; ?>
    <?php endif; ?> 
    <!-- Main JS -->
    <script src="<?= base_url('assets/js/main.js') ?>"></script>
     <!-- Page JS -->
    <?php if (!empty($scripts)): ?>
    <?php foreach ($scripts as $script): ?>
        <script src="<?= base_url($script) ?>"></script>
      <?php endforeach; ?>
    <?php endif; ?>
  
  
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  
  </body>
</html>
<script>
  const assetsPath = '<?= BASE_URL ?>';
</script>
