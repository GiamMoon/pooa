# SYS_CMP
Sistema Web de Gestión de Ventas, Compras e Inventario

# MVC
SYS_CMP/
│
├── app/
│   ├── controllers/         <-- Lógica de flujo de cada acción
│   ├── models/              <-- Interacción con BD
│   ├── views/               <-- Vistas separadas por módulos
│   │   ├── ventas/
│   │   │   ├── hstventa.php    <-- Vista del historial de Ventas
│   │   │   └── cliente.php     <-- Vista del historial de Clientes
│   │   ├── compras/
│   │   │   ├── hstcompra.php   <-- Vista del historial de Compras
│   │   │   └── proveedor.php   <-- Vista del historial de Proveedores
│   │   ├── inventario/
│   │   │   ├── producto.php    <-- Vista del historial de Productos
│   │   │   ├── marca.php       <-- Vista del historial de Marcas
│   │   │   ├── categoria.php   <-- Vista del historial de Categorías de productos
│   │   │   ├── tipoUnidad.php  <-- Vista del historial de Tipos de unidad que se manejan
│   │   │   ├── movimientos.php <-- Vista del historial de Movimientos de Inventario
│   │   │   └── ubicacion.php   <-- Vista del historial de Ubicaciones (tiendas, almacenes)
│   │   ├── seguridad/
│   │   │   ├── usuario.php     <-- Vista del historial de Usuarios
│   │   │   ├── rol.php         <-- Vista del historial de Roles
│   │   │   ├── permiso.php     <-- Vista del historial de Permisos
│   │   │   └── recurso.php     <-- Vista del historial de Recursos
│   │   ├── dashboard/
│   │   ├── home.php         <-- Home donde se cargan los demás
│   │   └── login.php        <-- Login del sistema
│   └── core/                <-- App base, controlador y modelo abstractos
│       ├── App.php
│       ├── Controller.php
│       └── Model.php
│
├── config/                  <-- Configuración general y base de datos
│   ├── config.php
│   └── database.php
│
├── public/                  <-- Front controller y recursos estáticos
│   ├── index.php            <-- punto de entrada
│   ├── .htaccess            <-- rutas amigables
│   └── assets/
│       ├── css/
│       ├── js/
│       ├── json/
│       ├── img/
│       └── vendor/
│           ├── css/
│           ├── fonts/
│           ├── js/
│           └── libs/
│
└── .htaccess                <-- redirige a /public


