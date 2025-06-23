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
                          <th class="text-nowrap text-sm-start">Unidad</th>
                          <th class="text-lg-center">Abreviatura</th>   
                          <th class="text-nowrap text-sm-start">Description</th>                          
                          <th class="text-lg-center">Estado</th>
                          <th class="text-lg-center">Acciones</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>

                <!-- MODAL REGISTRAR  -->
                <div class="modal fade" id="modalRegistrarUnidad" tabindex="-1" aria-labelledby="modalRegistrarUnidadLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                      <form id="formRegistrarUnidad" onsubmit="return true">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalRegistrarUnidadLabel">Registrar Tipo de Unidad</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body row g-3">
                          <div class="col-12">
                            <label class="form-label" for="reg-nombre-unidad">Nombre</label>
                            <div class="input-group">
                              <input type="text" maxlength="100" class="form-control text-uppercase" id="reg-nombre-unidad" name="nombre" placeholder="Ingrese el nombre de la categoría" required> 
                              <button id="verificarunidad" type="button" class="btn btn-primary"><i class="bx bx-check-circle"></i></button>
                            </div>                            
                          </div>
                          <div class="col-6">
                            <label class="form-label" for="reg-abreviatura-unidad">Abreviatura</label>
                            <div class="input-group">
                              <input maxlength="3" class="form-control text-uppercase" id="reg-abreviatura-unidad" name="abreviatura" placeholder="Generelo o edite" required>
                              <button id="generarAbreviatura" type="button" class="btn btn-secondary"><i class="bx bx-cog"></i></button>
                            </div>
                          </div>
                          <div class="col-6">
                            <label class="form-label" for="reg-sunat-unidad">Codigo sunat</label>
                            <input maxlength="3" class="form-control text-uppercase" id="reg-sunat-unidad" name="codigo_sunat" placeholder="Ingrese el código sunat" required></input>
                          </div>

                          <div class="col-12">
                            <label class="form-label" for="reg-descripcion-unidad">Descripción</label>
                            <textarea maxlength="250" class="form-control" id="reg-descripcion-unidad" name="descripcion" placeholder="Ingrese la descripción" rows="3" required></textarea>
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


                
                <!-- MODAL EDITAR  -->
                <div class="modal fade" id="modalEditarUnidad" tabindex="-1" aria-labelledby="modalEditarUnidadLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                      <form id="formEditarUnidad" onsubmit="return true">
                        <input type="hidden" id="edit-id_tipo_unidad" name="id_tipo_unidad">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalEditarUnidadLabel">Editar Unidad</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body row g-3">
                          <div class="col-12">
                            <label class="form-label" for="edit-nombre-unidad">Nombre</label>                            
                            <div class="input-group">
                              <input type="text" maxlength="100" class="form-control" id="edit-nombre-unidad" name="nombre" required>
                              <button id="edit-verificarunidad" type="button" class="btn btn-primary"><i class="bx bx-check-circle"></i></button>
                            </div>
                          </div>
                          <div class="col-6">
                            <label class="form-label" for="edit-abreviatura-unidad">Abreviatura</label>                            
                            <div class="input-group">
                              <input type="text" maxlength="3" class="form-control" id="edit-abreviatura-unidad" name="abreviatura" required>
                              <button id="edit-generarAbreviatura" type="button" class="btn btn-secondary"><i class="bx bx-cog"></i></button>
                            </div>
                          </div>
                          <div class="col-6">
                            <label class="form-label" for="edit-codigo-sunat-unidad">Código SUNAT</label>
                            <input type="text" maxlength="3" class="form-control" id="edit-codigo-sunat-unidad" name="codigo_sunat" required>
                          </div>
                          <div class="col-12">
                            <label class="form-label" for="edit-descripcion-unidad">Descripción</label>
                            <textarea maxlength="250" class="form-control" id="edit-descripcion-unidad" name="descripcion" rows="3" required></textarea>
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
                  <!-- MODAL DE CONFIRMACIÓN -->
                  <div class="modal fade" id="modalAdvertenciaSunat" tabindex="-1" aria-labelledby="modalAdvertenciaSunatLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                          <h5 class="modal-title">⚠️ Advertencia</h5>
                        </div>
                        <div class="modal-body">
                          El espacio <strong>CODIGO SUNAT</strong> es un dato oficial. Si no coincide con los códigos de SUNAT, no se convalidará la operación.<br><br>¿Estás seguro de editarlo?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" id="sunatCancelar">No</button>
                          <button type="button" class="btn btn-warning" id="sunatConfirmar">Sí</button>
                        </div>
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
           
