<script>
  window.PERMISOS_USUARIO = <?= json_encode($_SESSION['permisos']) ?>;
</script>
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="app-ecommerce-category">
                
              <!-- Modelo List Table -->
                <div class="card">
                  <div class="card-datatable">
                    <table class="datatables-category-list table">
                      <thead>
                        <tr>                      
                          <th class="text-nowrap text-sm-start">N°</th>
                          <th class="text-nowrap text-sm-start">Nombre</th>
                          <th class="text-lg-center">Año de Fabricación</th>
                          <th class="text-lg-center">Total de Productos</th>
                          <th class="text-lg-center">Estado</th>
                          <th class="text-lg-center">Acciones</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>

                <!-- MODAL REGISTRAR  -->                
                <div class="modal fade" id="modalRegistrarModelo" tabindex="-1" aria-labelledby="modalRegistrarModeloLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                      <form id="formRegistrarModelo" onsubmit="return true">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalRegistrarModeloLabel">Registrar Modelo</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body row g-3">

                          <!-- Nombre -->
                          <div class="col-12">
                            <label class="form-label" for="reg-nombre-modelo">Nombre del Modelo</label>
                            <div class="input-group">
                              <input type="text" maxlength="100" class="form-control text-uppercase" id="reg-nombre-modelo" name="nombre" placeholder="Ingrese el nombre del modelo">
                              <button id="verificarmodelo" type="button" class="btn btn-primary"><i class="bx bx-check-circle"></i></button>
                            </div>
                          </div>

                          <!-- Año -->
                          <div class="col-6">
                            <label class="form-label" for="reg-anio-modelo">Año de Fabricación</label>
                            <select id="reg-anio-modelo" name="anio" class="form-select">
                              <!-- opciones se generan vía JS -->
                            </select>
                          </div>

                          <!-- Marca Moto -->
                          <div class="col-6">
                            <label class="form-label" for="reg-id_marca">Marca</label>
                            <select id="reg-id_marca" name="id_moto" class="form-select select2" data-placeholder="Seleccione una marca">
                              <!-- opciones se cargan vía JS -->
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


                
                <!-- MODAL EDITAR -->
                <div class="modal fade" id="modalEditarModelo" tabindex="-1" aria-labelledby="modalEditarModeloLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                      <form id="formEditarModelo" onsubmit="return true">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalEditarModeloLabel">Editar Modelo</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body row g-3">
                          <input type="hidden" id="edit-id_modelo" name="id_modelo">
                          <!-- Nombre -->
                          <div class="col-12">
                            <label class="form-label" for="edit-nombre-modelo">Nombre del Modelo</label>
                            <div class="input-group">
                              <input type="text" maxlength="100" class="form-control" id="edit-nombre-modelo" name="nombre" placeholder="Ingrese el nombre del modelo">
                              <button id="editverificarmodelo" type="button" class="btn btn-primary"><i class="bx bx-check-circle"></i></button>
                            </div>
                          </div>

                          <!-- Año -->
                          <div class="col-6">
                            <label class="form-label" for="edit-anio-modelo">Año de Fabricación</label>
                            <select id="edit-anio-modelo" name="anio" class="form-select">
                              <!-- opciones se generan vía JS -->
                            </select>
                          </div>

                          <!-- Marca -->
                          <div class="col-6">
                            <label class="form-label" for="edit-id_moto">Marca</label>
                            <select id="edit-id_moto" name="id_moto" class="form-select select2" data-placeholder="Seleccione una marca">
                              <!-- opciones se cargan vía JS -->
                            </select>
                          </div>

                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Actualizar</button>
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
           
