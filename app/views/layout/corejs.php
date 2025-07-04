<script src="<?= base_url('assets/vendor/libs/jquery/jquery.js') ?>"></script>
<script src="<?= base_url('assets/vendor/libs/popper/popper.js') ?>"></script>
<script src="<?= base_url('assets/vendor/js/bootstrap.js') ?>"></script>
<script src="<?= base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') ?>"></script>
<script src="<?= base_url('assets/vendor/js/menu.js') ?>"></script>

<script src="<?= base_url('assets/js/main.js') ?>"></script>

<?php if (!empty($scripts)) : ?>
    <?php foreach ($scripts as $script) : ?>
        <?php
        $is_external = preg_match('/^https?:\/\//', $script);
        $src = $is_external ? $script : base_url($script);
        ?>
        <script src="<?= $src ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
