<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?= $title ?? 'Mi sitio' ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon/favicon.ico') ?>" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('assets/vendor/fonts/iconify-icons.css') ?>" />
    <!-- Core CSS -->
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- build:css assets/vendor/css/theme.css -->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/pickr/pickr-themes.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/vendor/css/core.css') ?>" /> 
    <link rel="stylesheet" href="<?= base_url('assets/css/demo.css') ?>" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') ?>" />
    <!-- endbuild -->
    <?php if (!empty($styles)): ?>
      <?php foreach ($styles as $style): ?>
        <?php
          // Verificar si el estilo empieza con "http" o "https"
          $is_external = preg_match('/^https?:\/\//', $style);
          $href = $is_external ? $style : base_url($style);
        ?>
        <link rel="stylesheet" href="<?= $href ?>">
      <?php endforeach; ?>
    <?php endif; ?>

    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="<?= base_url('assets/vendor/js/helpers.js') ?>"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js. 
    <script src="<?= base_url('assets/vendor/js/template-customizer.js') ?>"></script> -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?= base_url('assets/js/config.js') ?>"></script>

    <script>
        const site_url = "<?= base_url() ?>";
    </script>
</head>