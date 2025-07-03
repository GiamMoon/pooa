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
                          <th class="text-nowrap text-sm-start">Marcas</th>
                          <th class="text-lg-center">Total de Productos</th>
                          <th class="text-lg-center">Productos Activos</th>
                          <th class="text-lg-center">Estado</th>
                          <th class="text-lg-center">Acciones</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
                
                <!-- MODAL REGISTRAR MARCA -->
                <div class="modal fade" id="modalRegistrarMarca" tabindex="-1" aria-labelledby="modalRegistrarMarcaLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                      <form id="formRegistrarMarca" onsubmit="return true">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalRegistrarMarcaLabel">Registrar Marca</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body row g-3">
                          <div class="col-12">
                            <label class="form-label" for="reg-nombre-marca">Nombre</label>
                            <div class="input-group">
                              <input type="text" maxlength="100" class="form-control text-uppercase" id="reg-nombre-marca" name="nombre" placeholder="Ingrese el nombre de la marca"> 
                              <button id="verificarmarca" type="button" class="btn btn-primary"><i class="bx bx-check-circle"></i></button>
                            </div>                            
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

                <!-- MODAL EDITAR MARCA -->
                <div class="modal fade" id="modalEditarMarca" tabindex="-1" aria-labelledby="modalEditarMarcaLabel" aria-hidden="true">
                  <div class="modal-dialog modal-md">
                    <div class="modal-content">
                      <form id="formEditarMarca">
                        <div class="modal-header">
                          <h5 class="modal-title">Editar Marca</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body row g-3">
                          <input type="hidden" id="edit-id_marca" name="id_marca" />
                          <div class="col-12">
                            <label class="form-label" for="edit-nombre-marca">Nombre</label>
                            <div class="input-group">
                              <input type="text" maxlength="100" id="edit-nombre-marca" name="nombre" class="form-control text-uppercase"/>
                              <button id="editverificarmarca" type="button" class="btn btn-primary"><i class="bx bx-check-circle"></i></button>
                            </div> 
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
           
