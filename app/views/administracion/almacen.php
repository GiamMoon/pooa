<script>
  window.PERMISOS_USUARIO = <?= json_encode($_SESSION['permisos']) ?>;
</script>   
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="app-ecommerce-category">
                <!-- Sucursal List Table -->
                <div class="card">
                  <div class="card-datatable">
                    <table class="datatables-category-list table">
                      <thead>
                        <tr>
                          <th class="text-center"><input type="checkbox" class="form-check-input select-all" id="selectAll"></th>
                          <th class="text-nowrap text-sm-start">Sucursal</th>
                          <th class="text-nowrap text-sm-start">Dirección &nbsp;</th>
                          <th class="text-lg-center">Telefono encargado</th>
                          <th class="text-lg-center">Estado</th>
                          <th class="text-lg-center">Acciones</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
                
                <!-- MODAL REGISTRAR -->                
                <div class="modal fade" id="modalRegistrarAlmacen" tabindex="-1" aria-labelledby="modalRegistrarAlmacenLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <form id="formRegistrarAlmacen">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Registrar Almacen</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">                          
                          
                          <div class="col-md-6">
                              <label class="form-label">Sucursal</label>
                              <select id="reg-sucursal" name="id_sucursal" class="form-select select2">                                
                              <!-- Rellenar dinámicamente -->
                              </select>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" maxlength="9" inputmode="numeric" pattern="[0-9]*" class="form-control" id="reg-telefono" name="telefono" placeholder="999 999 999"/>
                          </div>
                          
                          <div class="col-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" maxlength="250" class="form-control text-uppercase" id="reg-direccion" name="direccion" placeholder="Ingrese Dirección"/>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label" for="reg-departamento">Departamento</label>
                            <select id="reg-departamento" name="departamento" class="form-select select2" data-placeholder="Seleccione departamento"></select>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label" for="reg-provincia">Provincia</label>
                            <select id="reg-provincia" name="provincia" class="form-select select2" disabled data-placeholder="Seleccione provincia"></select>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label" for="reg-distrito">Distrito</label>
                            <select id="reg-distrito" name="distrito" class="form-select select2" disabled data-placeholder="Seleccione distrito"></select>
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

                <!-- MODAL VER-->                

                <!-- MODAL EDITAR  -->
                <div class="modal fade" id="modalEditarSucursal" tabindex="-1" aria-labelledby="modalEditarSucursalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <form id="formEditarSucursal">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Editar Sucursal</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">
                          <input type="hidden" id="edit-id_sucursal" name="id_sucursal" />
                          <div class="col-md-12">
                            <label class="form-label">Nombre Comercial</label>
                            <input type="text" maxlength="255" id="edit-nombre_comercial" name="nombre_comercial" class="form-control text-uppercase" placeholder="Ingrese Nombre Comercial"/>
                          </div>                          
                          <div class="col-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" maxlength="250" class="form-control text-uppercase" id="edit-direccion" name="direccion" placeholder="Ingrese Dirección"/>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label" for="edit-departamento">Departamento</label>
                            <select id="edit-departamento" name="departamento" class="form-select select2" data-placeholder="Seleccione departamento"></select>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label" for="edit-provincia">Provincia</label>
                            <select id="edit-provincia" name="provincia" class="form-select select2" disabled data-placeholder="Seleccione provincia"></select>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label" for="edit-distrito">Distrito</label>
                            <select id="edit-distrito" name="distrito" class="form-select select2" disabled data-placeholder="Seleccione distrito"></select>
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


                <!-- MODAL ELIMINAR -->
                  <!-- Modal Confirmación 1 -->
                  <div class="modal fade" id="modalConfirmDelete1" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Confirmar Cambio</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body" id="modalConfirmDelete1Body">
                          <!-- Texto dinámico aquí -->
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button type="button" class="btn btn-danger" id="btnConfirmDelete1">Cambiar</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Modal Confirmación 2 -->
                  <div class="modal fade" id="modalConfirmDelete2" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Última confirmación</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body" id="modalConfirmDelete2Body">
                          <!-- Texto dinámico aquí -->
                          
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button type="button" class="btn btn-danger" id="btnConfirmDelete2">Sí, cambiar</button>
                        </div>
                      </div>
                    </div>
                  </div>


              </div>
            </div>
           
