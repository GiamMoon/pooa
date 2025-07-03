<?php if (!$estadoCaja) { ?>
<div class="modal fade" id="modalCajaRequerida" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title mb-5" id="modalLabel">Caja no iniciada</h5>
      </div>
      <div class="modal-body">
        No puedes realizar ventas porque no tienes una caja abierta actualmente.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" onclick="abrirCaja()">Abrir una caja</button>
      </div>
    </div>
  </div>
</div>

<script>
  const estadoCaja = false;

  window.addEventListener('DOMContentLoaded', () => {
    const modal = new bootstrap.Modal(document.getElementById('modalCajaRequerida'));
    modal.show();

    // Bloquear inputs fuera del modal
    document.querySelectorAll('input, button, select, textarea').forEach(el => {
      if (!el.closest('.modal')) el.disabled = true;
    });

    // Impedir cerrar el modal
    document.getElementById('modalCajaRequerida').addEventListener('hidden.bs.modal', () => {
      modal.show();
    });
  });

  function abrirCaja() {
    window.location.href = '<?= BASE_URL ?>ventas/caja';
  }
</script>
<?php } ?>
 

<style>
.quantity {
  display: flex;
  border: 2px solid var(--bs-primary);
  border-radius: 4px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.quantity button {
  background-color: var(--bs-primary);
  color: #fff;
  border: none;
  cursor: pointer;
  font-size:15px;
  width: 100%;
  height: auto;
  text-align: center;
  transition: background-color 0.2s;
}

.quantity button:hover { background-color: #2980b9;}

.input-box {
  width: 30px;
  text-align: center;
  border: none;
  padding: 3px 5px;
  font-size: 11px;
  outline: none;
  background: var(--bs-primary-bg-subtle); ;
}

/* Hide the number input spin buttons */
.input-box::-webkit-inner-spin-button,
.input-box::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.input-box[type="number"] { -moz-appearance: textfield; }

input.soloNumero::-webkit-inner-spin-button,
input.soloNumero::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.titleproduct{
      position: absolute;
    right: 0;
    left: 0;
    background: #edececc2;
    bottom: 0;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
}
.titleproduct p{
      margin: 0px;
    font-size: 0.775rem;
    line-height: 1.57;
    font-weight: 400;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.ocultar-stock {
  display: none !important;
}

</style>
 
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-6">
      <div class="col-lg-8">
        <div class="card mb-6">
          <div class="card-header d-flex flex-wrap justify-content-between gap-4">
            <div class="card-title mb-0 me-1">
              <h5 class="mb-0">Productos <sup class="mb-0">(<?= $total_productos ?>)</sup></h5>
              <div class="nav-item d-flex align-items-center">
                <span class="w-px-22 h-px-22"><i class="icon-base bx bx-search icon-md"></i></span>
                <input type="text" id="buscar_producto" class="form-control border-0 shadow-none ps-1 ps-sm-2 d-md-block d-none" placeholder="buscar..." aria-label="Buscar...">
              </div>        
            </div>
            <div class="d-flex justify-content-md-end align-items-sm-center align-items-start column-gap-6 flex-sm-row flex-column row-gap-4">
            <select class="form-select">
                <option value="">Todas las categorias</option>
                <?php foreach ($categorias as $c): ?>
                <option value="<?= $c['id_categoria'] ?>"><?= $c['nombre_categoria'] ?></option> 
                <?php endforeach; ?> 
              </select>
              <div class="form-check form-switch my-2 ms-2">
                <input type="checkbox" class="form-check-input" id="ocultar_stocks">
                <label class="form-check-label text-nowrap mb-0" for="ocultar_stocks">Ocultar Stocks</label>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row gy-6 mb-6">
              <?php foreach ($productos as $p): ?>
              <div class="col-sm-6 col-lg-3 producto" categoria-id="<?= $p['id_categoria'] ?>" producto-nombre="<?= $p['nombre'] ?>" producto-codigo="<?= $p['codigo_producto'] ?>" >
                <div class="card p-2 h-100 shadow-none border btn-agregar" data-repeater-create="" data-pcode="<?= $p['codigo_producto'] ?>" data-pid="<?= $p['id_producto'] ?>"data-pprecio="<?= number_format($p['precio_venta'], 2) ?>"data-pnombre="<?= $p['nombre'] ?>">
                    <div class="rounded-2 text-center mb-4" style="position: relative;">
                        <div style="position: absolute;margin: 5px;display:flex;justify-content: space-between;width: 95%;">
                            <button type="button" class="btn text-nowrap btn-xs d-inline-block me-n2 stock">
                              <span class="icon-base bx icon-xs bx-box"></span>
                              <span class="badge text-bg-primary badge-notifications"><?= $p['stock'] ?></span>
                            </button>
                            <span class="badge bg-label-info" style="font-size: 11px;display: flex;align-items: center;justify-content: center;padding: 0px 5px;">S/. <?= number_format($p['precio_venta'], 2) ?></span>
                        </div>
                        <img class="img-fluid" src="<?= '../uploads/products/' . $p['url_imagen'] ?>" alt="<?= $p['nombre'] ?>">
                    </div>
                    <div class="card-body p-2 pt-2 titleproduct" > <p class="mt-1 "><?= $p['nombre'] ?></p></div>
                </div>
              </div>
              <?php endforeach; ?> 
            </div>
            <nav aria-label="Page navigation" class="d-flex align-items-center justify-content-center">
              <ul class="pagination mb-0 pagination-rounded">
                <li class="page-item prev disabled">
                  <a class="page-link" href="javascript:void(0);"><i class="ifs if-arrow-left-from-line"></i></a>
                </li>
                <li class="page-item active">
                  <a class="page-link" href="javascript:void(0);">1</a>
                </li> 
                <li class="page-item next">
                  <a class="page-link" href="javascript:void(0);"><i class="ifs if-arrow-right-from-line"></i></a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="border rounded p-6 mb-4 stick-top course-content-fixed" style="background: #fff;"> <!--class="accordion stick-top accordion-custom-button course-content-fixed"-->
          
          <div style="display: flex;justify-content: space-between;">
             <div>
                <select class="form-select" style="width: 150px;" name="comprobante">
                  <?php foreach ($comprobantes as $c): ?>
                    <option value="<?= $c['id'] ?>">
                      <?= $c['nombre'] ?>
                    </option>
                  <?php endforeach; ?> 
                </select>
             </div><!--
             <div>
                <select class="form-select" style="width: 150px;" name="clientes"> 
                  <?php foreach ($clientes as $c): ?>
                    <option value="<?= $c['id_cliente'] ?>"  <?php if($c['id_cliente'] == 1){ echo 'selected'; } ?>>
                      <?= $c['nombre'] ?> - <?= $c['numero_documento'] ?>
                    </option>
                  <?php endforeach; ?>  
                </select>
             </div> -->
          </div>
          
          <hr>
              <div class="table-responsive">
                <table class="table table-borderless table-sm">
                    <thead>
                      <tr>
                        <th style="padding: 5px;">Producto</th>
                        <th style="padding: 5px;">Cantidad</th>
                        <th style="padding: 5px;">Total</th>
                      </tr>
                    </thead>
                    <tbody id="carrito"></tbody>
                </table>
                </div>
              <hr class="mx-n6 my-6">
              <h6>Detalle de la venta</h6>
              <dl class="row mb-0 text-heading">
                <dt class="col-6 fw-normal">Subtotal</dt>
                <dd class="col-6 text-end" id="subtotal">S/. 00.00</dd><!--
                <dt class="col-6 fw-normal">Cupón de descuento</dt>
                <dd class="col-6 text-primary text-end">Aplicar cuppon</dd>-->
                <dt class="col-6 fw-normal">IGV</dt>
                <dd class="col-6 text-end"><s class="text-body-secondary">18%</s> <span class="badge bg-label-info ms-1">18%</span></dd>
                <dt class="col-6 fw-normal">Total del pedido</dt>
                <dd class="col-6 text-end" id="total_pedido">S/. 0.00</dd>
              </dl>
              <hr class="mx-n6 my-6">
              <dl class="row mb-0">
                <dt class="col-6 text-heading">Total</dt>
                <dd class="col-6 fw-medium text-end text-heading mb-0" id="total">S/. 00.00</dd>
              </dl>
              <hr class="mx-n6 my-6">
              <div class="d-grid">
                <button class="btn btn-primary" id="realizar-pedido">Realizar pedido</button>
              </div>
            </div>
          </div>
    </div>
</div>
<div class="modal fade" id="pedidoExitosoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content text-center">
      <div class="modal-header">
        <h5 class="modal-title">¡Pedido realizado correctamente!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p class="mb-3">Puedes descargar tu comprobante:</p>
        <div class="d-flex justify-content-center gap-2">
          <button id="btnPedidoA4" class="btn btn-label-primary abrir-pdf" data-url="">
            <i class="bx bx-download"></i> A4
          </button>
          <button id="btnPedidoTicket" class="btn btn-label-secondary abrir-pdf" data-url="">
            <i class="bx bx-receipt"></i> Ticket
          </button>
        </div>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- / Content -->
<script>
// --- INICIO: Carrito con localStorage ---
const CART_KEY = 'carrito';

function obtenerCarrito() {
  return JSON.parse(localStorage.getItem(CART_KEY)) || {};
}

function guardarCarrito(cart) {
  localStorage.setItem(CART_KEY, JSON.stringify(cart));
}

function renderCart(cart) {
  const carritoDiv = document.getElementById('carrito');
  carritoDiv.innerHTML = '';

  if (Object.keys(cart).length === 0) {
    carritoDiv.innerHTML = '<p>El carrito está vacío</p>';
    actualizarTotales(0);
    return;
  }

  let html = '';
  let subtotal = 0;
  for (const codigo in cart) {
    const item = cart[codigo];
    const precioUnitario = Number(item.precio);
    const cantidad = Number(item.cantidad);
    const total = precioUnitario * cantidad;
    subtotal += total;
    html += `
      <tr data-codigo="${codigo}">
        <td style="padding: 5px;">
          <div class="d-flex justify-content-start align-items-center">
            <div class="d-flex flex-column">
              <h6 class="mb-0 text-truncate" style="max-width: 105px;">${item.nombre}</h6>
              <small class="text-truncate text-body">S/. ${precioUnitario.toFixed(2)} xU</small>
            </div>
          </div>
        </td>
        <td style="padding:5px;"> 
          <div class="quantity">
            <button class="minus" aria-label="Decrease" data-codigo="${codigo}">&minus;</button>
            <input type="number soloNumero" class="input-box" value="${cantidad}" min="1" max="10" disabled>
            <button class="plus" aria-label="Increase" data-codigo="${codigo}">&plus;</button>
          </div>
        </td>
        <td style="padding: 5px;">
          <span class="fw-medium">S/. ${total.toFixed(2)}</span>
        </td>
        <td style="padding: 5px;">
          <button type="button" class="btn-eliminar btn btn-xs rounded-pill btn-icon btn-danger" data-codigo="${codigo}"><span class="icon-base bx bx-x icon-sm" style="color: white;"></span></button>
        </td>
      </tr>
    `;
  }

  carritoDiv.innerHTML = `
    <table class="table">
      <tbody>${html}</tbody>
    </table>
  `;
  agregarListeners();
  actualizarTotales(subtotal);
}

function actualizarTotales(subtotal) {
  const igv = subtotal * 0.18;
  const total = subtotal + igv;
  document.getElementById('subtotal').textContent = `S/. ${subtotal.toFixed(2)}`;
  document.getElementById('total_pedido').textContent = `S/. ${total.toFixed(2)}`;
  document.getElementById('total').textContent = `S/. ${total.toFixed(2)}`;
}

function agregarListeners() {
  document.querySelectorAll('.plus').forEach(btn => {
    btn.onclick = () => modificarCantidad(btn.dataset.codigo, 1);
  });
  document.querySelectorAll('.minus').forEach(btn => {
    btn.onclick = () => modificarCantidad(btn.dataset.codigo, -1);
  });
  document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.onclick = () => eliminarProducto(btn.dataset.codigo);
  });
}

function modificarCantidad(codigo, delta) {
  const cart = obtenerCarrito();
  if (cart[codigo]) {
    cart[codigo].cantidad += delta;
    if (cart[codigo].cantidad <= 0) delete cart[codigo];
    guardarCarrito(cart);
    renderCart(cart);
  }
}

function eliminarProducto(codigo) {
  const cart = obtenerCarrito();
  delete cart[codigo];
  guardarCarrito(cart);
  renderCart(cart);
}

// Evento para agregar productos
function registrarBotonesAgregar() {
  document.querySelectorAll('.btn-agregar').forEach(btn => {
    btn.onclick = () => {
      const codigo = btn.dataset.pcode;
      const precio = btn.dataset.pprecio;
      const pid = btn.dataset.pid;
      const nombre = btn.dataset.pnombre;
      const cart = obtenerCarrito();

      if (cart[codigo]) {
        cart[codigo].cantidad++;
      } else {
        cart[codigo] = { codigo, id_producto: pid, nombre, precio, cantidad: 1 };
      }
      guardarCarrito(cart);
      renderCart(cart);
    };
  });
}

document.addEventListener('DOMContentLoaded', () => {
  renderCart(obtenerCarrito());
  registrarBotonesAgregar();
});

document.getElementById('realizar-pedido').addEventListener('click', () => {
  const cliente = '1'; // document.querySelector('select[name="clientes"]').value;
  const comprobante = document.querySelector('select[name="comprobante"]').value;
  const carrito = obtenerCarrito();


  if (Object.keys(carrito).length === 0) {
    alert('El carrito está vacío');
    return;
  }

  // Preparar array de productos
  const productos = Object.values(carrito).map(item => ({
    id_producto: item.id_producto,
    precio_unitario: parseFloat(item.precio),
    cantidad: parseInt(item.cantidad),
    stock: ''
  }));

  fetch('<?= BASE_URL ?>/ventas/checkout', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ cliente, comprobante, productos })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success && data.id_venta) {
      const idVenta = data.id_venta;

      // Setear URLs de descarga
      document.getElementById('btnPedidoA4').setAttribute('data-url', `<?= BASE_URL ?>ventas/pdf/${idVenta}?type=A4`);
      document.getElementById('btnPedidoTicket').setAttribute('data-url', `<?= BASE_URL ?>ventas/pdf/${idVenta}?type=ticket`);

      // Mostrar el modal
      const modal = new bootstrap.Modal(document.getElementById('pedidoExitosoModal'));
      modal.show();

      localStorage.removeItem('carrito'); // vaciar carrito
      renderCart({});
    }

  })
  .catch(err => {
    console.error('Error al enviar el pedido:', err);
    alert('Error inesperado al enviar el pedido');
  });
});

document.querySelectorAll('.abrir-pdf').forEach(btn => {
  btn.addEventListener('click', function () {
    const url = this.dataset.url;
    if (!url) return;
    const width = 800;
    const height = 500;
    const left = (window.screen.width / 2) - (width / 2);
    const top = (window.screen.height / 2) - (height / 2);
    window.open(url, '_blank', `toolbar=no,location=no,menubar=no,scrollbars=yes,resizable=yes,width=${width},height=${height},top=${top},left=${left}`);
  });
});




const productosPorPagina = 9;
let paginaActual = 1;

/** Ocultar stock */
document.getElementById('ocultar_stocks').addEventListener('change', function () {
  const ocultar = this.checked;
  document.querySelectorAll('.stock').forEach(el => {
    el.classList.toggle('ocultar-stock', ocultar);
  });
});


/** Filtrar por categorías */
document.querySelector('select.form-select').addEventListener('change', function () {
  const catID = this.value;
  paginaActual = 1; // Reiniciar paginación

  document.querySelectorAll('.producto').forEach(p => {
    const productoCategoria = p.getAttribute('categoria-id');
    p.style.display = (!catID || productoCategoria === catID) ? 'block' : 'none';
  });

  filtrarYPaginarProductos();
});

/** Paginar productos */
function paginarProductos() {
  const productos = Array.from(document.querySelectorAll('.producto')).filter(p => p.style.display !== 'none');
  const totalPaginas = Math.ceil(productos.length / productosPorPagina);

  productos.forEach((prod, index) => {
    prod.style.display = (index >= (paginaActual - 1) * productosPorPagina && index < paginaActual * productosPorPagina) ? 'block' : 'none';
  });

  renderPaginacion(totalPaginas);
}

/** Renderizar la paginación */
function renderPaginacion(totalPaginas) {
  const paginacion = document.querySelector('.pagination');
  if (!paginacion) return;

  let pagHTML = `
    <li class="page-item ${paginaActual === 1 ? 'disabled' : ''}">
      <a class="page-link" href="#" onclick="cambiarPagina(${paginaActual - 1})"><i class="ifs if-arrow-left-from-line"></i></a>
    </li>
  `;

  for (let i = 1; i <= totalPaginas; i++) {
    pagHTML += `
      <li class="page-item ${paginaActual === i ? 'active' : ''}">
        <a class="page-link" href="#" onclick="cambiarPagina(${i})">${i}</a>
      </li>
    `;
  }

  pagHTML += `
    <li class="page-item ${paginaActual === totalPaginas ? 'disabled' : ''}">
      <a class="page-link" href="#" onclick="cambiarPagina(${paginaActual + 1})"><i class="ifs if-arrow-right-from-line"></i></a>
    </li>
  `;

  paginacion.innerHTML = pagHTML;
}

/** Cambiar de página */
function cambiarPagina(nuevaPagina) {
  paginaActual = nuevaPagina;
  filtrarYPaginarProductos();
}

/** Iniciar */
document.addEventListener('DOMContentLoaded', () => {
  filtrarYPaginarProductos();
});

document.getElementById('buscar_producto').addEventListener('input', function () {
  paginaActual = 1;
  filtrarYPaginarProductos();
});

/** Filtrar por categoría + búsqueda y aplicar paginación */
function filtrarYPaginarProductos() {
  const catID = document.querySelector('select.form-select').value;
  const busqueda = document.getElementById('buscar_producto').value.toLowerCase().trim();

  const todos = document.querySelectorAll('.producto');
  let visibles = [];

  todos.forEach(p => {
    const nombre = p.getAttribute('producto-nombre')?.toLowerCase() || '';
    const codigo = p.getAttribute('producto-codigo')?.toLowerCase() || '';
    const categoria = p.getAttribute('categoria-id');

    const coincideCategoria = !catID || categoria === catID;
    const coincideBusqueda = !busqueda || nombre.includes(busqueda) || codigo.includes(busqueda);

    if (coincideCategoria && coincideBusqueda) {
      p.style.display = 'block';
      visibles.push(p);
    } else {
      p.style.display = 'none';
    }
  });

  // Aplicar paginación
  const totalPaginas = Math.ceil(visibles.length / productosPorPagina);
  visibles.forEach((p, i) => {
    p.style.display = (i >= (paginaActual - 1) * productosPorPagina && i < paginaActual * productosPorPagina) ? 'block' : 'none';
  });

  renderPaginacion(totalPaginas);
}







</script>
            