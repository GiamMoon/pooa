<script>
  window.PERMISOS_USUARIO = <?= json_encode($_SESSION['permisos']) ?>;
</script>
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="app-ecommerce-category">
                
              <!-- Category List Table -->
                <div class="card">
                  <div class="card-datatable">
                    <table class="datatables-category-list table">
                      <thead>
                        <tr>                      
                          <th class="text-nowrap text-sm-start">N°</th>
                          <th class="text-nowrap text-sm-start">Dirección</th>
                          <th class="text-lg-center">Teléfono</th>   
                          <th class="text-lg-center">Tipo</th>                          
                          <th class="text-lg-center">Estado</th>
                          <th class="text-lg-center">Acciones</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>

                <!-- MODAL REGISTRAR  -->
                <div class="modal fade" id="modalRegistrarUbicacion" tabindex="-1" aria-labelledby="modalRegistrarUbicacionLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                      <form id="formRegistrarUbicacion" onsubmit="return true">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalRegistrarUbicacionLabel">Registrar Ubicación</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body row g-3">
                          <div class="col-12">
                            <label class="form-label" for="reg-direccion-ubicacion">Dirección</label>
                            <div class="input-group">
                              <input type="text" maxlength="200" class="form-control text-uppercase" id="reg-direccion-ubicacion" name="direccion" placeholder="Ingrese dirección" required>
                              <button id="verificarUbicacion" type="button" class="btn btn-primary"><i class="bx bx-check-circle"></i></button>                              
                              <button id="seleccionarDireccion" type="button" class="btn btn-info"><i class="bx bx-map"></i>Abrir</button>
                            </div>
                          </div>

                          <div class="col-6">
                            <label class="form-label" for="reg-telefono-ubicacion">Teléfono</label>
                            <input type="text" maxlength="9" class="form-control" id="reg-telefono-ubicacion" name="telefono" placeholder="Ingrese teléfono" required>
                          </div>

                          <div class="col-6">
                            <label class="form-label" for="reg-tipo-ubicacion">Tipo de ubicación</label>
                            <select class="form-select" id="reg-tipo-ubicacion" name="tipo_ubicacion" required>
                              <option value="">Seleccione tipo</option>
                              <option value="ALMACEN">ALMACÉN</option>
                              <option value="TIENDA">TIENDA</option>
                            </select>
                          </div>
                        </div>

                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Registrar</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>


                <!-- MODAL VER-->


                <!-- Modal Google Maps -->
                <div class="modal fade" id="modalGoogleMap" tabindex="-1" aria-labelledby="modalGoogleMapLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <form id="formSeleccionarUbicacion">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalGoogleMapLabel">Seleccionar Ubicación</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                          <!-- Mapa de Google -->
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button type="button" class="btn btn-primary" id="acceptLocation">Aceptar Ubicación</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>


                
                <!-- MODAL EDITAR  -->
                <div class="modal fade" id="modalEditarUbicacion" tabindex="-1" aria-labelledby="modalEditarUbicacionLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                      <form id="formEditarUbicacion" onsubmit="return true">
                        <input type="hidden" id="edit-id_ubicacion" name="id_ubicacion">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalEditarUbicacionLabel">Editar Ubicación</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body row g-3">
                          <div class="col-12">
                            <label class="form-label" for="edit-direccion-ubicacion">Dirección</label>
                            <div class="input-group">
                              <input type="text" maxlength="200" class="form-control text-uppercase" id="edit-direccion-ubicacion" name="direccion" required>
                              <button id="edit-verificarUbicacion" type="button" class="btn btn-primary"><i class="bx bx-check-circle"></i></button>
                            </div>
                          </div>

                          <div class="col-6">
                            <label class="form-label" for="edit-telefono-ubicacion">Teléfono</label>
                            <input type="text" maxlength="9" class="form-control" id="edit-telefono-ubicacion" name="telefono" required>
                          </div>

                          <div class="col-6">
                            <label class="form-label" for="edit-tipo-ubicacion">Tipo de ubicación</label>
                            <select class="form-select" id="edit-tipo-ubicacion" name="tipo_ubicacion" required>
                              <option value="">Seleccione tipo</option>
                              <option value="ALMACEN">ALMACÉN</option>
                              <option value="TIENDA">TIENDA</option>
                            </select>
                          </div>
                        </div>

                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Guardar cambios</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                      </form>
                    </div>
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
           
