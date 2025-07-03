<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
    <div class="d-flex flex-column justify-content-center">
      <div class="mb-1">
        <span class="h5">Caja #<span id="cajaNumero">---</span></span>
        <span class="badge bg-label-success me-1 ms-2" id="estadoCaja" data-estado="abierta">Abierta</span>
        <span class="badge bg-label-info">Sin cierre automático</span>
      </div>
      <p class="mb-0">Aperturada el <span id="fechaAperturaCaja">--/--/----</span></p>
    </div>
    <div class="d-flex align-content-center flex-wrap gap-2">
      <button class="btn btn-label-danger" id="btnCerrarCaja">Cerrar caja</button>
      <button class="btn btn-label-info" id="btnAbrirCaja" data-bs-toggle="modal" data-bs-target="#modalAbrirCaja">Abrir caja</button>
    </div>
  </div>

  <div class="row">
    <!-- Ventas de la caja -->
    <div class="col-12 col-lg-8">
      <div class="card mb-6">
        <div class="card-header">
          <h5 class="card-title m-0">Ventas realizadas</h5>
        </div>
        <div class="card-datatable table-responsive">
          <table id="tablaVentasCaja" class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Comprobante</th>
                <th>Comprador</th>
                <th>Monto</th>
              </tr>
            </thead>
            <tbody id="ventas">
              <!-- Dinámico -->
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-end">Subtotal</th>
                <th id="subtotalCaja">S/. 0.00</th>
              </tr>
              <tr>
                <th colspan="4" class="text-end">Monto apertura</th>
                <th id="montoAperturaCaja">S/. 0.00</th>
              </tr>
              <tr>
                <th colspan="4" class="text-end">Vuelto</th>
                <th id="vueltoCaja">S/. 0.00</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <div class="card mb-6">
        <div class="card-header">
          <h5 class="card-title m-0">Cajas anteriores</h5>
        </div>
        <div class="card-body pt-1">
          <ul class="timeline pb-0 mb-0" id="historialCajas">
            <!-- Dinámico -->
          </ul>
        </div>
      </div>
    </div>

    <!-- Detalle lateral -->
    <div class="col-12 col-lg-4">
      <div class="card mb-6">
        <div class="card-header">
          <h5 class="card-title m-0">Caja actual</h5>
        </div>
        <div class="card-body" id="detailCaja">
          <div class="d-flex justify-content-between">
            <h6 class="mb-1">Detalle</h6>
            <!--<h6 class="mb-1"><a href="javascript:window.print()">Imprimir</a></h6>-->
          </div>
          <p class="mb-1 mt-3">Hora de apertura: <span id="horaApertura"></span></p>
          <p class="mb-0">Cajero: <span id="nombreCajero">---</span></p>
          <p class="mb-0">Estado: <strong id="estadoCajaTexto">---</strong></p>
          <p class="mb-0">Establecimiento: Oficina Principal</p>

          <div class="mt-4">
            <label class="form-label">Comentarios</label>
            <textarea class="form-control" rows="3" placeholder="Comentarios de la caja..." id="comentarioCaja"></textarea>
            <button class="btn btn-sm btn-primary mt-2" id="btnGuardarComentario">Guardar comentario</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Apertura de Caja -->
  <div class="modal fade" id="modalAbrirCaja" tabindex="-1" aria-labelledby="modalAbrirCajaLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalAbrirCajaLabel">Abrir Caja</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formAbrirCaja">
            <div class="mb-3">
              <label for="montoApertura" class="form-label">Monto de apertura</label>
              <input type="number" class="form-control" id="montoApertura" name="monto_apertura" step="0.01" min="0" required>
            </div>
            <div class="mb-3">
              <label for="comentarioApertura" class="form-label">Comentario (opcional)</label>
              <textarea class="form-control" id="comentarioApertura" name="observaciones" rows="3"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" form="formAbrirCaja" class="btn btn-primary">Abrir caja</button>
        </div>
      </div>
    </div>
  </div>

</div>
<!-- / Content -->

<!-- Modal Ventas de Caja Cerrada -->
  <div class="modal fade" id="modalVentasCaja" tabindex="-1" aria-labelledby="modalVentasCajaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalVentasCajaLabel">Ventas de Caja #<span id="cajaIdModal"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Comprobante</th>
                <th>Comprador</th>
                <th>Monto</th>
              </tr>
            </thead>
            <tbody id="ventasCajaCerrada">
              <!-- Dinámico -->
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-end">Subtotal</th>
                <th id="subtotalCajaCerrada">S/. 0.00</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- / Content -->

<div id="toas" class="bs-toast toast toast-ex animate__animated my-2 fade bg-primary animate__tada hide" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
    <div class="toast-header">
      <i class="icon-base bx bx-bell me-2"></i>
      <div class="me-auto fw-medium" id="title_toast">title</div>
      <small>Mensaje:</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body" id="mensaje_toast">mensaje</div>
  </div>


<script>
document.getElementById('formAbrirCaja').addEventListener('submit', function (e) {
  e.preventDefault();
  const data = new FormData(this);

  fetch('<?= BASE_URL ?>ventas/abrir', {
    method: 'POST',
    body: data
  })
  .then(res => res.json())
  .then(res => {
    if (res.status === 'ok') {
      bootstrap.Modal.getInstance(document.getElementById('modalAbrirCaja')).hide();
      cargarEstadoCaja();
      mostrarToast('Éxito', 'Caja abierta correctamente', 'bg-success');
    } else {
      mostrarToast('Error', res.message || 'No se pudo abrir la caja', 'bg-danger');
    }
  })
  .catch(() => {
    mostrarToast('Error', 'Error en el servidor', 'bg-danger');
  });
});

function cargarEstadoCaja() {
  fetch('<?= BASE_URL ?>ventas/cargarEstado')
    .then(res => res.json())
    .then(data => {
      const caja = data.caja_activa;
      const historial = data.historial;

      if (caja) {
        document.getElementById('btnAbrirCaja').style.display = 'none';
        document.getElementById('btnCerrarCaja').style.display = 'inline-block';
        document.getElementById('cajaNumero').textContent = caja.id_movimiento;
        document.getElementById('fechaAperturaCaja').textContent = caja.fecha_apertura;
        document.getElementById('horaApertura').textContent = caja.fecha_apertura.split(' ')[1];
        document.getElementById('nombreCajero').textContent = caja.nombre_usuario || '---';
        document.getElementById('estadoCaja').textContent = 'Abierta';
        document.getElementById('estadoCajaTexto').textContent = 'Abierta';
        document.getElementById('montoAperturaCaja').textContent = 'S/. ' + parseFloat(caja.monto_apertura).toFixed(2);
        document.getElementById('comentarioCaja').value = caja.observaciones || '';

        document.getElementById('detailCaja').style.display = 'block';

        cargarVentasDeCaja();

      } else {
        document.getElementById('btnAbrirCaja').style.display = 'inline-block';
        document.getElementById('btnCerrarCaja').style.display = 'none';
        document.getElementById('estadoCaja').textContent = 'Cerrada';
        document.getElementById('estadoCajaTexto').textContent = 'Cerrada';
        document.getElementById('detailCaja').style.display = 'none';
      }

      const lista = document.getElementById('historialCajas');
      lista.innerHTML = '';
      historial.forEach(c => {
        const li = document.createElement('li');
        li.classList.add('timeline-item', 'timeline-item-transparent', 'border-primary');
        li.innerHTML = `
          <span class="timeline-point timeline-point-primary"></span>
          <div class="timeline-event">
            <div class="timeline-header">
              <h6 class="mb-0">Caja #${c.id_movimiento} (Saldo: S/. ${parseFloat((c.monto_apertura - c.monto_cierre)).toFixed(2)})</h6>
              <small class="text-body-secondary">${c.fecha_apertura.split(' ')[0]}</small>
            </div>
            <p class="mt-3">
              Caja abierta: ${c.fecha_apertura} | Cerrada: ${c.fecha_cierre}<br/>
              Comentarios: ${c.observaciones || '---'}
            </p>
            <button class="btn btn-sm btn-outline-primary" onclick="verVentasCaja(${c.id_movimiento})">Ver ventas</button>
          </div>
        `;
        lista.appendChild(li);
      });
    });
}

function cargarVentasDeCaja() {
  fetch('<?= BASE_URL ?>ventas/ventasCaja')
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('ventas');
      const ventas = data.ventas || [];
      tbody.innerHTML = '';
      let subtotal = 0;

      ventas.forEach((venta, i) => {
        subtotal += parseFloat(venta.monto_total);
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${i + 1}</td>
          <td>${venta.creado_en}</td>
          <td>${venta.tipo_comprobante}</td>
          <td>${venta.comprador}</td>
          <td>S/. ${parseFloat(venta.monto_total).toFixed(2)}</td>
        `;
        tbody.appendChild(tr);
      });

      document.getElementById('subtotalCaja').textContent = 'S/. ' + subtotal.toFixed(2);
      const montoApertura = parseFloat(document.getElementById('montoAperturaCaja').textContent.replace('S/. ', '')) || 0;
      const vuelto = subtotal - montoApertura;
      document.getElementById('vueltoCaja').textContent = 'S/. ' + vuelto.toFixed(2);
    });
}


document.getElementById('btnCerrarCaja').addEventListener('click', function () {
  const id = document.getElementById('cajaNumero').textContent;
  const montoCierre = parseFloat(prompt('Monto final en caja:'));

  if (!isNaN(montoCierre) && montoCierre >= 0) {
    const data = new FormData();
    data.append('id_caja', id);
    data.append('monto_cierre', montoCierre);
    data.append('observaciones', document.getElementById('comentarioCaja').value);

    fetch('<?= BASE_URL ?>ventas/cerrar', {
      method: 'POST',
      body: data
    })
    .then(res => res.json())
    .then(res => {
      if (res.status === 'ok') {
        cargarEstadoCaja();
        mostrarToast('Éxito', 'Caja cerrada correctamente', 'bg-success');
        window.location.reload();
      } else {
        mostrarToast('Error', 'No se pudo cerrar la caja', 'bg-danger');
      }
    })
    .catch(() => {
      mostrarToast('Error', 'Error en el servidor', 'bg-danger');
    });
  }
});


document.getElementById('btnGuardarComentario').addEventListener('click', function () {
  const id = document.getElementById('cajaNumero').textContent;
  const observaciones = document.getElementById('comentarioCaja').value;

  fetch('<?= BASE_URL ?>ventas/guardarComentario', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ id_caja: id, observaciones })
  })
  .then(res => res.json())
  .then(res => {
    if (res.status === 'ok') {
      mostrarToast('Éxito', 'Comentario actualizado', 'bg-info');
    } else {
      mostrarToast('Error', 'No se pudo actualizar el comentario', 'bg-danger');
    }
  });
});

document.addEventListener('DOMContentLoaded', cargarEstadoCaja);





function verVentasCaja(id_caja) {
  fetch('<?= BASE_URL ?>ventas/verVentasCaja/' + id_caja)
    .then(res => res.json())
    .then(data => {
      document.getElementById('cajaIdModal').textContent = id_caja;
      const tbody = document.getElementById('ventasCajaCerrada');
      tbody.innerHTML = '';
      let subtotal = 0;

      data.ventas.forEach((venta, i) => {
        subtotal += parseFloat(venta.monto_total);
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${i + 1}</td>
          <td>${venta.creado_en}</td>
          <td>${venta.tipo_comprobante}</td>
          <td>${venta.comprador}</td>
          <td>S/. ${parseFloat(venta.monto_total).toFixed(2)}</td>
        `;
        tbody.appendChild(tr);
      });

      document.getElementById('subtotalCajaCerrada').textContent = 'S/. ' + subtotal.toFixed(2);
      new bootstrap.Modal(document.getElementById('modalVentasCaja')).show();
    });
}








function mostrarToast(title, message, type = 'bg-success') {
  const toast = document.getElementById('toas');
  toast.classList.remove('bg-primary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-info', 'bg-secondary', 'bg-dark', 'bg-light');
  toast.classList.add(type);
  document.getElementById('title_toast').textContent = title;
  document.getElementById('mensaje_toast').textContent = message;
  const toastBootstrap = new bootstrap.Toast(toast);
  toastBootstrap.show();
}
</script>