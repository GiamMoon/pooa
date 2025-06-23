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

</style>
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-6">
      <div class="col-lg-8">
        <div class="card mb-6">
          <div class="card-header d-flex flex-wrap justify-content-between gap-4">
            <div class="card-title mb-0 me-1">
              <h5 class="mb-0">Productos <sup class="mb-0">(165)</sup></h5>
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
              <div class="col-sm-6 col-lg-4 producto" categoria-id="<?= $p['id_categoria'] ?>" producto-nombre="<?= $p['nombre'] ?>">
                <div class="card p-2 h-100 shadow-none border">
                    <div class="rounded-2 text-center mb-4" style="position: relative;">
                        <div style="position: absolute;margin: 5px;display:flex;justify-content: space-between;width: 95%;">
                            <span class="badge bg-label-success" >Stock<sup>(<?= $p['stock'] ?> u)</sup></span>
                            <span class="badge bg-label-info">S/. <?= number_format($p['precio_venta'], 2) ?></span>
                        </div>
                        <img class="img-fluid" src="<?= '../uploads/products/' . $p['url_imagen'] ?>" alt="<?= $p['nombre'] ?>">
                    </div>
                    <div class="card-body p-2 pt-2">
                         <p class="mt-1"><?= $p['nombre'] ?></p>
                        <div class="d-flex flex-column flex-md-row gap-4 text-nowrap flex-wrap flex-md-nowrap flex-lg-wrap flex-xxl-nowrap">
                            <button type="button" class="w-100 btn btn-label-primary d-flex align-items-center btn-agregar" data-repeater-create="" 
                            data-pcode="<?= $p['codigo_producto'] ?>" data-pid="<?= $p['id_producto'] ?>"
                            data-pprecio="<?= number_format($p['precio_venta'], 2) ?>"
                            data-pnombre="<?= $p['nombre'] ?>" >
                                <i class="ifs if-cart-plus"></i> Agregar producto
                            </button>
                        </div>
                    </div>
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
              <?php echo 'u: '.$_SESSION['usuario'] ?>
                <select class="form-select" style="width: 150px;" name="comprobante">
                  <?php foreach ($comprobantes as $c): ?>
                    <option value="<?= $c['id'] ?>">
                      <?= $c['nombre'] ?>
                    </option>
                  <?php endforeach; ?> 
                </select>
             </div>
             <div>
                <select class="form-select" style="width: 150px;" name="clientes"> 
                  <?php foreach ($clientes as $c): ?>
                    <option value="<?= $c['id_cliente'] ?>"  <?php if($c['id_cliente'] == 1){ echo 'selected'; } ?>>
                      <?= $c['nombre'] ?> - <?= $c['numero_documento'] ?>
                    </option>
                  <?php endforeach; ?>  
                </select>
             </div>
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
                <dd class="col-6 text-end" id="subtotal">S/. 00.00</dd>
                <dt class="col-6 fw-normal">Cupón de descuento</dt>
                <dd class="col-6 text-primary text-end">Aplicar cuppon</dd>
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
<!-- / Content -->
<script>
function renderCart(cart) {
  const carritoDiv = document.getElementById('carrito');
  carritoDiv.innerHTML = '';

  if (Object.keys(cart).length === 0) {
    carritoDiv.innerHTML = '<p>El carrito está vacío</p>';
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

  // Asegúrate de que el contenedor tenga una <table> donde insertar estas filas
  carritoDiv.innerHTML = `
    <table class="table">
      <tbody>${html}</tbody>
    </table>
  `;agregarListeners();actualizarTotales(subtotal);
}

function actualizarTotales(subtotal) {
  const igv = subtotal * 0.18;
  const total = subtotal + igv;

  document.getElementById('subtotal').textContent = `S/. ${subtotal.toFixed(2)}`;
  document.getElementById('total_pedido').textContent = `S/. ${total.toFixed(2)}`;
  document.getElementById('total').textContent = `S/. ${total.toFixed(2)}`;
}


document.addEventListener('DOMContentLoaded', () => { cargarCarritoInicial(); });

function cargarCarritoInicial() {
  fetch('<?= BASE_URL ?>/cart/get')
    .then(res => res.json())
    .then(data => {
      if(data.success) {
        renderCart(data.cart);
      } else {
        console.error('Error al obtener el carrito');
      }
    });
}


// Cuando agregas un producto
document.querySelectorAll('.btn-agregar').forEach(btn => {
  btn.addEventListener('click', () => {
    const codigo = btn.getAttribute('data-pcode');
    const precio = btn.getAttribute('data-pprecio');
    const pid = btn.getAttribute('data-pid');
    const nombre = btn.getAttribute('data-pnombre');
  
    fetch('<?= BASE_URL ?>/cart/add', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({codigo_producto: codigo, id_producto: pid, cantidad: 1, precio_producto: precio, nombre_producto:nombre})
    })
    .then(res => res.json())
    .then(data => {
      if(data.success){
        renderCart(data.cart);
      } else {
        alert('Error al agregar producto');
      }
    });
  });
});

function agregarListeners() {
  // Aumentar cantidad
  document.querySelectorAll('.plus').forEach(btn => {
    btn.addEventListener('click', () => {
      const codigo = btn.getAttribute('data-codigo');
      actualizarCantidad(codigo, 1); // +1
    });
  });

  // Disminuir cantidad
  document.querySelectorAll('.minus').forEach(btn => {
    btn.addEventListener('click', () => {
      const codigo = btn.getAttribute('data-codigo');
      actualizarCantidad(codigo, -1); // -1
    });
  });

  // Eliminar producto
  document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.addEventListener('click', () => {
      const codigo = btn.getAttribute('data-codigo');
      eliminarProducto(codigo);
    });
  });
}

function actualizarCantidad(codigo, cambio) {
  fetch('<?= BASE_URL ?>/cart/update', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({codigo_producto: codigo, cambio: cambio})
  })
  .then(res => res.json())
  .then(data => {if (data.success) {renderCart(data.cart);} else {alert(data.message || 'Error al actualizar cantidad');}});
}

function eliminarProducto(codigo) {
  fetch('<?= BASE_URL ?>/cart/remove', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({codigo_producto: codigo})
  }).then(res => res.json()).then(data => { if (data.success) {renderCart(data.cart);} else {alert(data.message || 'Error al eliminar producto');}});
}


const productosPorPagina = 9;
let paginaActual = 1;

/** Ocultar stock */
document.getElementById('ocultar_stocks').addEventListener('change', function () {
  const mostrar = !this.checked;
  document.querySelectorAll('.producto .badge.bg-label-success').forEach(el => {
    el.style.display = mostrar ? 'inline-block' : 'none';
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

  paginarProductos(); // aplicar paginación al nuevo filtro
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
  paginarProductos();
}

/** Iniciar */
document.addEventListener('DOMContentLoaded', () => {
  paginarProductos();
});




/***realizar pedido */
document.getElementById('realizar-pedido').addEventListener('click', () => {
  const cliente = document.querySelector('select[name="clientes"]').value;
  const comprobante = document.querySelector('select[name="comprobante"]').value;

  fetch('<?= BASE_URL ?>/cart/checkout', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ cliente, comprobante })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('Pedido realizado correctamente');
      renderCart({}); // vacía el carrito
    } else {
      alert(data.message || 'Error al realizar el pedido');
    }
  });
});


</script>
            