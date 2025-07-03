<?php
?>
<script>
  window.PERMISOS_USUARIO = <?= json_encode($permisos ?? []) ?>;
  window.USER_ROLE = <?= json_encode($_SESSION['id_rol'] ?? null) ?>;
</script>

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="app-ecommerce-category">
    <div class="card">
      <div class="card-datatable">
        <table class="datatables-compra-list table">
          <thead>
            <tr>
              <th class="text-center"><input type="checkbox" class="form-check-input select-all" id="selectAll"></th>
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

    <div class="modal fade" id="modalRegistrarCompra" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form id="formRegistrarCompra" class="modal-content" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title">Registrar Nueva Compra</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <?php if (isset($es_admin) && $es_admin === true): ?>
              <div class="col-md-6">
                <label for="id_sucursal" class="form-label">Sucursal de Compra</label>
                <select id="id_sucursal" name="id_sucursal" class="form-select select2" required>
                  <option value="" disabled selected>Seleccione una sucursal</option>
                  <?php foreach ($sucursales as $sucursal): ?>
                  <option value="<?php echo htmlspecialchars($sucursal['id_sucursal']); ?>">
                    <?php echo htmlspecialchars($sucursal['nombre_comercial']); ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <?php endif; ?>
              <div class="col-md-6">
                <label for="reg-proveedor" class="form-label">Proveedor</label>
                <select class="form-select select2" id="reg-proveedor" name="id_proveedor" required>
                  <option disabled selected>Seleccione un proveedor</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="reg-tipo-ubicacion-destino" class="form-label">Tipo de Destino</label>
                <select id="reg-tipo-ubicacion-destino" name="tipo_ubicacion_destino" class="form-select" required>
                  <option value="" disabled selected>Seleccione un tipo</option>
                  <option value="TIENDA">Tienda (Sucursal)</option>
                  <option value="ALMACEN">Almacén</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="reg-ubicacion-destino" class="form-label">Ubicación de Destino Específica</label>
                <select id="reg-ubicacion-destino" name="id_ubicacion_destino" class="form-select select2" required disabled>
                  <option value="" disabled selected>Seleccione primero una sucursal y tipo</option>
                </select>
              </div>
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
                    <button type="button" class="btn btn-primary" id="btnAgregarProducto"><i class="bx bx-plus"></i></button>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <table class="table table-bordered table-hover" id="tablaProductosCompra">
                  <thead class="table-light">
                    <tr>
                      <th>Producto</th>
                      <th>Cantidad</th>
                      <th>T.U.</th>
                      <th>Precio Costo</th>
                      <th>Subtotal</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                  <tfoot>
                    <tr>
                      <th colspan="4" class="text-end">Total:</th>
                      <th id="totalCompra">S/ 0.00</th>
                      <th></th>
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

    <div class="modal fade" id="modalDetalleCompra" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de Compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="detalle-codigo-compra" class="form-label">Código de Compra</label>
                            <input type="text" id="detalle-codigo-compra" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="detalle-proveedor" class="form-label">Proveedor</label>
                            <input type="text" id="detalle-proveedor" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="detalle-fecha-compra" class="form-label">Fecha de Compra</label>
                            <input type="text" id="detalle-fecha-compra" class="form-control" readonly>
                        </div>
                        <div class="col-12">
                            <h6 class="mt-3">Productos en la Compra</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tablaDetalleProductos">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>T.U.</th>
                                            <th>Precio Unitario</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">Total General:</th>
                                            <th id="detalle-total-compra">S/ 0.00</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-generar-oc" class="btn btn-primary">Generar/Descargar OC</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalEditarCompra" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form id="formEditarCompra" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar Compra</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="edit-id-compra" name="id_compra">
            <div class="row g-3">
              <div class="col-md-12">
                <label for="edit-proveedor" class="form-label">Proveedor</label>
                <select class="form-select select2" id="edit-proveedor" name="id_proveedor" required></select>
              </div>
              <div class="col-md-6">
                <label for="edit-tipo-ubicacion-destino" class="form-label">Tipo de Destino</label>
                <select id="edit-tipo-ubicacion-destino" name="tipo_ubicacion_destino" class="form-select" required>
                  <option value="" disabled selected>Seleccione un tipo</option>
                  <option value="TIENDA">Tienda (Sucursal)</option>
                  <option value="ALMACEN">Almacén</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="edit-ubicacion-destino" class="form-label">Ubicación de Destino Específica</label>
                <select id="edit-ubicacion-destino" name="id_ubicacion_destino" class="form-select select2" required disabled>
                  <option value="" disabled selected>Seleccione primero un tipo</option>
                </select>
              </div>
              <div class="col-md-12 mt-3">
                <h6>Agregar o Modificar Productos</h6>
                <div class="row align-items-end g-2 mb-2">
                  <div class="col-md-5">
                    <label for="edit-producto" class="form-label">Producto</label>
                    <select id="edit-producto" class="form-select select2" data-placeholder="Seleccione un producto"></select>
                  </div>
                  <div class="col-md-2">
                    <label for="edit-cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="edit-cantidad" min="1">
                  </div>
                  <div class="col-md-2">
                    <label for="edit-precio" class="form-label">Precio Costo</label>
                    <input type="number" class="form-control" id="edit-precio" step="0.01" min="0.01">
                  </div>
                  <div class="col-md-2">
                    <label for="edit-subtotal" class="form-label">Subtotal</label>
                    <input type="text" class="form-control" id="edit-subtotal" readonly>
                  </div>
                  <div class="col-md-1 d-grid">
                    <button type="button" class="btn btn-primary" id="btnAgregarProductoEdit"><i class="bx bx-plus"></i></button>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <table class="table table-bordered table-hover" id="tablaProductosCompraEdit">
                  <thead class="table-light">
                    <tr>
                      <th>Producto</th>
                      <th>Cantidad</th>
                      <th>T.U.</th>
                      <th>Precio Costo</th>
                      <th>Subtotal</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                  <tfoot>
                    <tr>
                      <th colspan="4" class="text-end">Total:</th>
                      <th id="totalCompraEdit">S/ 0.00</th>
                      <th></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade" id="modalRecepcionarCompra" tabindex="-1" aria-hidden="true">
    </div>
  </div>
</div>