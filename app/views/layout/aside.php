<?php
function tienePermiso($ruta, $permisoEsperado = 'visualizar')
{
    if (!isset($_SESSION['permisos']) || !is_array($_SESSION['permisos'])) return false;

    $ruta = strtolower(rtrim($ruta, '/'));

    foreach ($_SESSION['permisos'] as $permiso) {
        $permisoRuta = strtolower(rtrim($permiso['ruta'], '/'));
        $permisoNombre = strtolower($permiso['permiso']);

        if ($permisoRuta === $ruta && $permisoNombre === strtolower($permisoEsperado)) {
            return true;
        }
    }
    return false;
}
?>
<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo ">
        <a href="" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <defs>
                            <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" id="path-1"></path>
                            <path d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z" id="path-3"></path>
                            <path d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z" id="path-4"></path>
                            <path d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z" id="path-5"></path>
                        </defs>
                        <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                                <g id="Icon" transform="translate(27.000000, 15.000000)">
                                    <g id="Mask" transform="translate(0.000000, 8.000000)">
                                        <mask id="mask-2" fill="white">
                                            <use xlink:href="#path-1"></use>
                                        </mask>
                                        <use fill="currentColor" xlink:href="#path-1"></use>
                                        <g id="Path-3" mask="url(#mask-2)">
                                            <use fill="currentColor" xlink:href="#path-3"></use>
                                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                        </g>
                                        <g id="Path-4" mask="url(#mask-2)">
                                            <use fill="currentColor" xlink:href="#path-4"></use>
                                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                        </g>
                                    </g>
                                    <g id="Triangle" transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                        <use fill="currentColor" xlink:href="#path-5"></use>
                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </span>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">Repuestos</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto"><i class="icon-base bx bx-chevron-left"></i></a>
    </div>
    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <li class="menu-item <?= ($activePage === 'home') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>home" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Email">Panel Principal</div>
            </a>
        </li>


        <!-- ENLACE AL DASHBOARD DE BI -->
        <?php if (tienePermiso('dashboard')) : ?>
            <li class="menu-item <?= ($activePage === 'dashboard') ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>dashboard" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-tachometer"></i>
                    <div class="text-truncate" data-i18n="Dashboard">Dashboard</div>
                </a>
            </li>
        <?php endif; ?>


        <!-- Modulos disponibles -->
        <!-- Inventario -->
        <?php if (tienePermiso('inventario')) : ?>
            <li class="menu-item <?= ($openMenu === 'inventario') ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-cart"></i>
                    <div class="text-truncate" data-i18n="Dashboards">Inventario</div>
                </a>
                <ul class="menu-sub">

                    <?php if (tienePermiso('inventario/index')) : ?>
                        <li class="menu-item <?= ($activePage === 'index') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>inventario/index" class="menu-link">
                                <div class="text-truncate" data-i18n="Analytics">Cuadro de Control</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('inventario/movimientos')) : ?>
                        <li class="menu-item <?= ($activePage === 'movimientos') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>inventario/movimientos" class="menu-link">
                                <div class="text-truncate" data-i18n="Analytics">Historial de Movimientos</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('inventario/transferencias')) : ?>
                        <li class="menu-item <?= ($activePage === 'transferencias') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>inventario/transferencias" class="menu-link">
                                <div class="text-truncate" data-i18n="Analytics">Transferencias</div>
                            </a>
                        </li>
                    <?php endif; ?>


                    <?php if (tienePermiso('inventario/recepcion')) : ?>
                        <li class="menu-item <?= ($activePage === 'recepcion_compras') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>inventario/recepcion" class="menu-link">
                                <div class="text-truncate" data-i18n="Analytics">Recepción de Compras</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('inventario/ubicacion')) : ?>
                        <li class="menu-item <?= ($activePage === 'ubicacion') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>inventario/ubicacion" class="menu-link">
                                <div class="text-truncate" data-i18n="Analytics">Ubicaciones</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('inventario/moto')) : ?>
                        <li class="menu-item <?= ($activePage === 'moto') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>inventario/moto" class="menu-link">
                                <div class="text-truncate" data-i18n="eCommerce">Motos</div>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (tienePermiso('inventario/modelo')) : ?>
                        <li class="menu-item <?= ($activePage === 'modelo') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>inventario/modelo" class="menu-link">
                                <div class="text-truncate" data-i18n="eCommerce">Modelos</div>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>

        <!-- Productos -->
        <?php if (tienePermiso('productos')) : ?>
            <li class="menu-item <?= ($openMenu === 'productos') ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-package"></i>
                    <div class="text-truncate" data-i18n="Dashboards">Productos</div>
                </a>
                <ul class="menu-sub">

                    <?php if (tienePermiso('productos/producto')) : ?>
                        <li class="menu-item <?= ($activePage === 'producto') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>productos/producto" class="menu-link">
                                <div class="text-truncate" data-i18n="Analytics">Productos</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('productos/stock_actual')) : ?>
                        <li class="menu-item <?= ($activePage === 'stock_actual') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>productos/stock_actual" class="menu-link">
                                <div class="text-truncate" data-i18n="Analytics">Stock Actual</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('productos/unidad')) : ?>
                        <li class="menu-item <?= ($activePage === 'unidad') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>productos/unidad" class="menu-link">
                                <div class="text-truncate" data-i18n="Analytics">Tipos de unidad</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('productos/categoria')) : ?>
                        <li class="menu-item <?= ($activePage === 'categoria') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>productos/categoria" class="menu-link">
                                <div class="text-truncate" data-i18n="CRM">Categorías</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('productos/marca')) : ?>
                        <li class="menu-item <?= ($activePage === 'marca') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>productos/marca" class="menu-link">
                                <div class="text-truncate" data-i18n="eCommerce">Marcas</div>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>

        <!-- Ventas -->
        <?php if (tienePermiso('ventas')) : ?>
            <li class="menu-item <?= ($openMenu === 'ventas') ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-store"></i>
                    <div class="text-truncate" data-i18n="Layouts">Ventas</div>
                </a>
                <ul class="menu-sub">

                    <?php if (tienePermiso('ventas/index')) : ?>
                        <li class="menu-item <?= ($activePage === 'index') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>ventas/index" class="menu-link">
                                <div class="text-truncate" data-i18n="Landing">Listado de ventas</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('ventas/pos')) : ?>
                        <li class="menu-item <?= ($activePage === 'pos') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>ventas/pos" class="menu-link">
                                <div class="text-truncate" data-i18n="Without menu">Venta Pos</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php 
                    ?>
                    <li class="menu-item <?= ($activePage === 'crear') ? 'active' : '' ?>">
                        <a href="<?= BASE_URL ?>ventas/create" class="menu-link">
                            <div class="text-truncate" data-i18n="Without menu">Nueva venta</div>
                        </a>
                    </li>
                    <?php  
                    ?>

                    <?php if (tienePermiso('ventas/cliente')) : ?>
                        <li class="menu-item <?= ($activePage === 'cliente') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>ventas/cliente" class="menu-link">
                                <div class="text-truncate" data-i18n="Container">Clientes</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('ventas/comprobante')) : ?>
                        <li class="menu-item <?= ($activePage === 'emitir_comprobante') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>ventas/emitir_comprobante" class="menu-link">
                                <div class="text-truncate" data-i18n="Without navbar">Emitir Comprobante</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="menu-item <?= ($activePage === 'caja') ? 'active' : '' ?>">
                        <a href="<?= BASE_URL ?>ventas/caja" class="menu-link">
                            <div class="text-truncate" data-i18n="Without navbar">Caja</div>
                        </a>
                    </li>

                </ul>
            </li>
        <?php endif; ?>

        <!-- Compras -->
        <?php if (tienePermiso('compra')) : ?>
            <li class="menu-item <?= ($openMenu === 'compra') ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-receipt"></i>
                    <div class="text-truncate" data-i18n="Layouts">Compras</div>
                </a>
                <ul class="menu-sub">

                    <?php if (tienePermiso('compra/compra')) : ?>
                        <li class="menu-item <?= ($activePage === 'compra') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>compra/compra" class="menu-link">
                                <div class="text-truncate" data-i18n="Landing">Historial de Compras</div>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>

        <!-- contactos -->
        <?php if (tienePermiso('contactos')) : ?>
            <li class="menu-item <?= ($openMenu === 'contactos') ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-book"></i>
                    <div class="text-truncate" data-i18n="Layouts">Contactos</div>
                </a>
                <ul class="menu-sub">
                    <?php if (tienePermiso('contactos/clientes')) : ?>
                        <li class="menu-item <?= ($activePage === 'clientes') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>contactos/clientes" class="menu-link">
                                <div class="text-truncate" data-i18n="Landing">Clientes</div>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (tienePermiso('contactos/proveedor')) : ?>
                        <li class="menu-item <?= ($activePage === 'proveedor') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>contactos/proveedores" class="menu-link">
                                <div class="text-truncate" data-i18n="Pricing">Proveedores</div>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <!-- Reportes-->
        <?php if (tienePermiso('reportes')) : ?>
            <li class="menu-item <?= ($openMenu === 'reportes') ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-chart"></i>
                    <div class="text-truncate" data-i18n="Front Pages">Reportes</div>
                </a>
                <ul class="menu-sub">

                    <?php if (tienePermiso('reportes/movimientos')) : ?>
                        <li class="menu-item <?= ($activePage === 'reporte_movimientos') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>reportes/movimientos" class="menu-link">
                                <div class="text-truncate" data-i18n="Pricing">Reporte de Movimientos</div>
                            </a>
                        </li>
                    <?php endif; ?>


                    <?php if (tienePermiso('reportes/registroventa')) : ?>
                        <li class="menu-item <?= ($activePage === 'registro_ventas') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>reportes/registro_ventas" class="menu-link">
                                <div class="text-truncate" data-i18n="Pricing">Registro de Ventas</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('reportes/ventasxcategoria')) : ?>
                        <li class="menu-item <?= ($activePage === 'ventasxcategoria') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>reportes/ventasxcategoria" class="menu-link">
                                <div class="text-truncate" data-i18n="Payment">Ventas por Categoria</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('reportes/ventasxproducto')) : ?>
                        <li class="menu-item <?= ($activePage === 'ventasxproducto') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>reportes/ventasxproducto" class="menu-link">
                                <div class="text-truncate" data-i18n="Checkout">Ventas por producto</div>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>

        <!-- Administración Arreglar -->
        <?php if (tienePermiso('admi')) : ?>
            <li class="menu-item <?= ($openMenu === 'admi') ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-buildings"></i>
                    <div class="text-truncate" data-i18n="Front Pages">Administración</div>
                </a>
                <ul class="menu-sub">

                    <?php if (tienePermiso('seguridad/clientes')) : ?>
                        <li class="menu-item <?= ($activePage === 'clientes') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>admi/clientes" class="menu-link">
                                <div class="text-truncate" data-i18n="Pricing">Clientes</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('seguridad/proveedores')) : ?>
                        <li class="menu-item <?= ($activePage === 'proveedores') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>admi/proveedores" class="menu-link">
                                <div class="text-truncate" data-i18n="Payment">Proveedores</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('seguridad/tipocomprobante')) : ?>
                        <li class="menu-item <?= ($activePage === 'tipo_comprobante') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>admi/tipo_comprobante" class="menu-link">
                                <div class="text-truncate" data-i18n="Checkout">Tipo de Comprobante</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('seguridad/tipoafectacion')) : ?>
                        <li class="menu-item <?= ($activePage === 'tipo_afectacion') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>admi/tipo_afectacion" class="menu-link">
                                <div class="text-truncate" data-i18n="Checkout">Tipo de Afectación</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('seguridad/series')) : ?>
                        <li class="menu-item <?= ($activePage === 'series') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>admi/series" class="menu-link">
                                <div class="text-truncate" data-i18n="Checkout">Series</div>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>

        <!--SEGURIDAD Y MANTENIMIENTO-->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text"></span>
        </li>

        <!-- Panel Seguridad -->
        <?php if (tienePermiso('seguridad')) : ?>
            <li class="menu-item <?= ($openMenu === 'seguridad') ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div class="text-truncate" data-i18n="Front Pages">Seguridad</div>
                </a>
                <ul class="menu-sub">

                    <?php if (tienePermiso('seguridad/usuario')) : ?>
                        <li class="menu-item <?= ($activePage === 'usuario') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>seguridad/usuario" class="menu-link">
                                <div class="text-truncate" data-i18n="Landing">Usuarios</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('seguridad/rol')) : ?>
                        <li class="menu-item <?= ($activePage === 'rol') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>seguridad/rol" class="menu-link">
                                <div class="text-truncate" data-i18n="Landing">Roles</div>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>

        <!-- Panel Mantenimiento -->
        <?php if (tienePermiso('administracion')) : ?>
            <li class="menu-item <?= ($openMenu === 'administracion') ? 'open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-collection"></i>
                    <div class="text-truncate" data-i18n="Front Pages">Administración</div>
                </a>
                <ul class="menu-sub">

                    <?php if (tienePermiso('administracion/empresa')) : ?>
                        <li class="menu-item <?= ($activePage === 'empresa') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>administracion/empresa" class="menu-link">
                                <div class="text-truncate" data-i18n="Landing">Empresa</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('administracion/sucursal')) : ?>
                        <li class="menu-item <?= ($activePage === 'sucursal') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>administracion/sucursal" class="menu-link">
                                <div class="text-truncate" data-i18n="Landing">Sucursales</div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (tienePermiso('administracion/almacen')) : ?>
                        <li class="menu-item <?= ($activePage === 'almacen') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>administracion/almacen" class="menu-link">
                                <div class="text-truncate" data-i18n="Landing">Almacenes</div>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>

    </ul>
</aside>
<!-- / Menu -->
