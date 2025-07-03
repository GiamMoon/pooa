<script>
  window.PERMISOS_USUARIO = <?= json_encode($_SESSION['permisos']) ?>;    
</script>

<!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="app-ecommerce-category">

                <!-- Empresa List Table -->
                <div class="card">
                  <div class="card-datatable">
                    <table class="datatables-category-list table">
                      <thead>
                        <tr>                          
                          <th class="text-center"><input type="checkbox" class="form-check-input select-all" id="selectAll"></th>
                          <th class="text-nowrap text-sm-start">Razón Social</th>
                          <th class="text-lg-center">RUC</th>
                          <th class="text-nowrap text-sm-start">Dirección &nbsp;</th>
                          <th class="text-lg-center">Acciones</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>

                <!-- MODAL EDITAR-->
                <div class="modal fade" id="modalEditarEmpresa" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <form id="formEditarEmpresa">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Detalle Empresa</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">
                          <input type="hidden" id="id_empresa" name="id_empresa" />
                          <div class="col-md-6">
                            <label class="form-label">Razón Social</label>
                            <input type="text" maxlength="255" id="razon_social" name="razon_social" class="form-control"/>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">RUC</label>
                            <input type="text" maxlength="11" inputmode="numeric" pattern="[0-9]*" class="form-control" id="ruc" name="ruc"/>
                          </div>
                          <div class="col-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" maxlength="250" class="form-control" id="direccion" name="direccion"/>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label" for="departamento">Departamento</label>
                            <select id="departamento" name="departamento" class="form-select select2" data-placeholder="Seleccione departamento"></select>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label" for="provincia">Provincia</label>
                            <select id="provincia" name="provincia" class="form-select select2" disabled data-placeholder="Seleccione provincia"></select>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label" for="distrito">Distrito</label>
                            <select id="distrito" name="distrito" class="form-select select2" disabled data-placeholder="Seleccione distrito"></select>
                          </div>
                          
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- MODEL REGISTRAR-->
                <!-- MODAL VER-->                
                <!-- MODAL ELIMINAR -->

              </div>
            </div>