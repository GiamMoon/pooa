<script>
  window.PERMISOS_USUARIO = <?= json_encode($_SESSION['permisos']) ?>;
</script>

            <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
              
            <div class="app-ecommerce-category">

            
            <!-- Product List Table -->
              <div class="card">
                <div class="card-datatable">
                  <table class="datatables-compra-list table">
                    <thead>
                      <tr>                        
                        <th class="text-lg-center">N°</th>
                        <th class="text-lg-center">Código Compra</th>                      
                        <th class="text-nowrap text-sm-start">Proveedor</th>
                        <th class="text-nowrap text-sm-start">Fecha de Compra</th>                    
                        <th class="text-lg-center">Costo Total</th>
                        <th class="text-lg-center">Estado</th>
                        <th class="text-lg-center">Acciones</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>

                <!-- Modal Registrar Compra -->
                <div class="modal fade" id="modalRegistrarCompra" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <form id="formRegistrarCompra" class="modal-content" enctype="multipart/form-data">
                      <div class="modal-header">
                        <h5 class="modal-title">Registrar Nueva Compra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row g-3">

                          <!-- Selección de Proveedor -->
                          <div class="col-md-6">
                            <label for="reg-proveedor" class="form-label">Proveedor</label>
                            <select class="form-select select2" id="reg-proveedor" name="id_proveedor" required>
                              <option disabled selected>Seleccione un proveedor</option>
                            </select>
                          </div>

                          <!-- Agregar Productos -->
                          <div class="col-md-12 mt-3">
                            <h6>Agregar Producto</h6>
                            <div class="row align-items-end g-2 mb-2">
                              <div class="col-md-5">
                                <label for="producto" class="form-label">Producto</label>
                                <select id="producto" class="form-select select2" data-placeholder="Seleccione un producto"></select>
                              </div>
                              <div class="col-md-2">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad" min="1">
                              </div>
                              <div class="col-md-2">
                                <label for="precio" class="form-label">Precio Costo</label>
                                <input type="number" class="form-control" id="precio" step="0.01" min="0.01">
                              </div>
                              <div class="col-md-2">
                                <label for="subtotal" class="form-label">Subtotal</label>
                                <input type="text" class="form-control" id="subtotal" readonly>
                              </div>
                              <div class="col-md-1 d-grid">
                                <button type="button" class="btn btn-primary" id="btnAgregarProducto">
                                  <i class="bx bx-plus"></i>
                                </button>
                              </div>
                            </div>
                          </div>

                          <!-- Tabla Productos Agregados -->
                          <div class="col-md-12">
                            <table class="table table-bordered table-hover" id="tablaProductosCompra">
                              <thead class="table-light">
                                <tr>
                                  <th>Producto</th>
                                  <th>Cantidad</th>
                                  <th>Precio Costo</th>
                                  <th>Subtotal</th>                                  
                                  <th>Acciones</th>
                                </tr>
                              </thead>
                              <tbody>
                                <!-- Se llenará dinámicamente -->
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th colspan="3" class="text-end">Total:</th>
                                  <th id="totalCompra">S/ 0.00</th>
                                  <th colspan="2"></th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Registrar Compra</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- Modal Detalle Compra -->
                <div class="modal fade" id="modalDetalleCompra" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Detalle de Compra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row g-3">
                          <!-- Información General -->
                          <div class="col-md-4">
                            <label class="form-label">Código de Compra</label>
                            <input type="text" class="form-control" id="detalle-codigo-compra" readonly>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Fecha de Compra</label>
                            <input type="text" class="form-control" id="detalle-fecha-compra" readonly>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Proveedor</label>
                            <input type="text" class="form-control" id="detalle-proveedor" readonly>
                          </div>

                          <!-- Tabla Detalle de Productos -->
                          <div class="col-md-12 mt-4">
                            <h6>Productos Comprados</h6>
                            <div class="table-responsive">
                              <table class="table table-bordered table-hover" id="tablaDetalleProductos">
                                <thead class="table-light">
                                  <tr>
                                    <th>Producto</th>                                    
                                    <th>Cantidad</th>
                                    <th>Precio Compra</th>
                                    <th>Subtotal</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Se llenará desde JS -->
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <th colspan="4" class="text-end">Total:</th>
                                    <th id="detalle-total-compra">S/ 0.00</th>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>

                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>



                <!-- Modal Recepcionar Compra -->
                <div class="modal fade" id="modalRecepcionarCompra" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <form id="formRecepcionarCompra" class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Recepción de Productos de Compra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row g-3">

                          <!-- Código de Compra -->
                          <div class="col-md-6">
                            <label class="form-label">Código de Compra</label>
                            <input type="text" class="form-control" id="codigo-compra-recep" readonly>
                          </div>

                          <!-- Proveedor -->
                          <div class="col-md-6">
                            <label class="form-label">Proveedor</label>
                            <input type="text" class="form-control" id="proveedor-recep" readonly>
                          </div>

                          <!-- Tabla Lotes y Productos -->
                          <div class="col-md-12 mt-4">
                            <h6>Detalle de Lotes a Recepcionar</h6>
                            <div class="table-responsive">
                              <table class="table table-bordered table-hover" id="tablaLotesRecepcion">
                                <thead class="table-light">
                                  <tr>
                                    <th>Producto</th>
                                    <th>Cantidad Esperada</th>
                                    <th>Cantidad Recibida</th>
                                    <th>Observación</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- Se llenan desde JS -->
                                </tbody>
                              </table>
                            </div>
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
          </div>
            <!-- / Content -->
