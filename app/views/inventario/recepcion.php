<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Inventario /</span> Recepción de Compras
  </h4>

  <div class="app-ecommerce-category">
    <div class="card">
      <div class="card-datatable">
        <table class="datatables-recepcion-list table">
          <thead>
            <tr>
              <th class="text-lg-center">N°</th>
              <th class="text-lg-center">Código Compra</th>
              <th class="text-nowrap text-sm-start">Proveedor</th>
              <th class="text-nowrap text-sm-start">Fecha de Compra</th>
              <th class="text-lg-center">Estado</th>
              <th class="text-lg-center">Acciones</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalProcesarRecepcion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <form id="formProcesarRecepcion" class="modal-content" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Procesar Recepción de Compra</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="recepcion-id-compra" name="id_compra">

          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label">Código de Compra</label>
              <input type="text" class="form-control" id="recepcion-codigo-compra" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">Proveedor</label>
              <input type="text" class="form-control" id="recepcion-proveedor" readonly>
            </div>
          </div>
          
          <div class="row g-3 mb-4">
            <div class="col-md-12">
                <label class="form-label">Destino de la Compra</label>
                <input type="text" class="form-control" id="recepcion-info-destino" readonly>
            </div>
          </div>

          <div class="col-md-12">
            <h6>Detalle de Productos a Recepcionar</h6>
            <div class="table-responsive">
              <table class="table table-bordered" id="tablaRecepcionProductos">
                <thead class="table-light">
                  <tr>
                    <th style="width: 30%;">Producto</th>
                    <th class="text-center">Pedido</th>
                    <th class="text-center">Recibido</th>
                    <th class="text-center">Pendiente</th>
                    <th class="text-center" style="width: 15%;">Cant. a Recibir</th>
                    <th class="text-center">Evidencia (Opcional)</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Confirmar Recepción</button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>