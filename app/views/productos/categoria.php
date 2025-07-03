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
                          <th class="text-nowrap text-sm-start">Categorías</th>
                          <th class="text-nowrap text-sm-start">Description</th>
                          <th class="text-lg-center">Total de Productos</th>
                          <th class="text-lg-center">Estado</th>
                          <th class="text-lg-center">Acciones</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>

                <!-- MODAL REGISTRAR CATEGORÍA -->
                <div class="modal fade" id="modalRegistrarCategoria" tabindex="-1" aria-labelledby="modalRegistrarCategoriaLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                      <form id="formRegistrarCategoria" onsubmit="return true">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalRegistrarCategoriaLabel">Registrar Categoría</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body row g-3">
                          <div class="col-12">
                            <label class="form-label" for="reg-nombre-categoria">Nombre</label>
                            <div class="input-group">
                              <input type="text" maxlength="100" class="form-control text-uppercase" id="reg-nombre-categoria" name="nombre" placeholder="Ingrese el nombre de la categoría"> 
                              <button id="verificarcategoria" type="button" class="btn btn-primary"><i class="bx bx-check-circle"></i></button>
                            </div>                            
                          </div>
                          <div class="col-12">
                            <label class="form-label" for="reg-descripcion-categoria">Descripción</label>
                            <textarea maxlength="250" class="form-control" id="reg-descripcion-categoria" name="descripcion" placeholder="Ingrese la descripción" rows="3"></textarea>
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

                <!-- MODAL EDITAR CATEGORÍA -->
                <div class="modal fade" id="modalEditarCategoria" tabindex="-1" aria-labelledby="modalEditarCategoriaLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md">
                    <div class="modal-content">
                      <form id="formEditarCategoria">
                        <div class="modal-header">
                          <h5 class="modal-title">Editar Categoría</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body row g-3">
                          <input type="hidden" id="edit-id_categoria" name="id_categoria" />
                          <div class="col-12">
                            <label class="form-label" for="edit-nombre-categoria">Nombre</label>
                            <div class="input-group">
                              <input type="text" maxlength="100" id="edit-nombre-categoria" name="nombre" class="form-control text-uppercase"/>
                              <button id="editverificarcategoria" type="button" class="btn btn-primary"><i class="bx bx-check-circle"></i></button>
                            </div> 
                          </div>                                                                                                                                   

                          <div class="col-12">
                            <label class="form-label" for="edit-descripcion-categoria">Descripción</label>
                            <textarea maxlength="250" id="edit-descripcion-categoria" name="descripcion" class="form-control" rows="3"></textarea>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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
           
