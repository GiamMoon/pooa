<?php
?>
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">Historial de Movimientos de Inventario</h4>

  <div class="card">
    <div class="card-datatable">
      <table class="datatables-inventario-list table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Código Movimiento</th>
            <th>Tipo</th>
            <th>Origen</th>
            <th>Destino</th>
            <th>Usuario</th>
            <th>Fecha</th>
            <th>Acciones</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <div class="modal fade" id="modalDetalleMovimiento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDetalleMovimientoTitle">Detalle del Movimiento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p><strong>Observación:</strong> <span id="detalle-observacion"></span></p>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th>Producto</th>
                  <th>Cantidad</th>
                </tr>
              </thead>
              <tbody id="tablaDetalleProductosMovimiento">
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

</div>