<script>
  window.PERMISOS_USUARIO = <?= json_encode($_SESSION['permisos']) ?>;
</script> 
            <!-- Content -->
             <div class="container-xxl flex-grow-1 container-p-y">
              <div class="app-ecommerce-category">
                
                <!-- Roles List Table -->
                <div class="card">
                  <div class="card-datatable">
                    <table class="datatables-category-list table">
                      <thead>
                        <tr>                          
                          <th class="text-nowrap text-sm-start">N°</th>  
                          <th class="text-nowrap text-sm-start">Roles</th>
                          <th class="text-nowrap text-sm-start">Usuarios asignados</th>
                          <th class="text-nowrap text-sm-start">Usuarios activos</th>
                          <th class="text-lg-center">Estado</th>
                          <th class="text-lg-center">Acciones</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
                

                <!-- MODEL REGISTRAR-->                
                <div class="modal fade" id="modalRegistrarRol" tabindex="-1" aria-labelledby="modalRegistrarRolLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalRegistrarRolLabel">Registrar Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>                      

                      <form id="formRegistrarRol" onsubmit="return true">
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                          <div class="mb-3">
                            <label class="form-label" for="reg-rol-nombre">Nombre del rol</label>
                            <div class="input-group">
                              <input type="text" class="form-control text-lowercase" id="reg-rol-nombre" name="nombre" placeholder="Nombre del Rol">
                              <button id="verificarrol" type="button" class="btn btn-primary"><i class="bx bx-check-circle"></i></button>
                            </div>                            
                          </div>
                          <div id="estructura-permisos" class="accordion"></div>
                        </div>

                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Registrar</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>                      
                      </form>
                    </div>
                  </div>
                </div>

                <!-- MODAL VISUALIZAR ROL -->
                <div class="modal fade" id="modalVerRol" tabindex="-1" aria-labelledby="modalVerRolLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">                                
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Detalle del Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>                      
                      <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <div class="row g-3">
                          <div class="col-md-12">
                          <label class="form-label fw-bold">Nombre del rol:</label>
                          <!--<p id="ver-rol-nombre"></p>-->
                          <input type="text" class="form-control" id="ver-rol-nombre">
                        </div>
                        <div class="col-md-4">
                          <label class="form-label fw-bold">Usuarios asignados:</label>
                          <!--<p id="ver-usuarios-asignados"></p>-->
                          <input type="text" class="form-control" id="ver-usuarios-asignados">
                        </div>
                        <div class="col-md-4">
                          <label class="form-label fw-bold">Usuarios activos:</label>
                          <!--<p id="ver-usuarios-activos"></p>-->
                          <input type="text" class="form-control" id="ver-usuarios-activos">
                        </div>
                        <div class="col-md-4">
                          <label class="form-label fw-bold">Estado:</label>
                          <!--<p id="ver-estado"></p>-->
                          <input type="text" class="form-control" id="ver-estado">
                        </div>
                        </div>                          
                        <hr>
                        <div id="ver-estructura-permisos" class="accordion"></div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      </div>                      
                    </div>
                  </div>
                </div>

                <!-- MODAL EDITAR ROL -->
                <div class="modal fade" id="modalEditarRol" tabindex="-1" aria-labelledby="modalEditarRolLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarRolLabel">Editar Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>
                      <form id="formEditarRol" onsubmit="return false">
                        <input type="hidden" id="rol-id" name="id_rol" />
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                          <div class="mb-3">
                            <label class="form-label" for="edit-rol-nombre">Nombre del rol</label>                                                        
                            <div class="input-group">
                              <input type="text" class="form-control" id="edit-rol-nombre" name="nombre" placeholder="Nombre del Rol">
                              <button id="verificareditrol" type="button" class="btn btn-primary"><i class="bx bx-check-circle"></i></button>
                            </div> 

                          </div>
                          <div id="editar-estructura-permisos" class="accordion"></div>
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
                  <div class="modal fade" id="modalConfirmDeleteRol1" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Confirmar Cambio de Estado</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body" id="modalConfirmDeleteRol1Body"></div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button type="button" class="btn btn-danger" id="btnConfirmDeleteRol1">Cambiar</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Modal Confirmación 2 -->
                  <div class="modal fade" id="modalConfirmDeleteRol2" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content border-danger">
                        <div class="modal-header bg-danger text-white">
                          <h5 class="modal-title">Advertencia</h5>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body" id="modalConfirmDeleteRol2Body"></div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button type="button" class="btn btn-danger" id="btnConfirmDeleteRol2">Continuar</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Modal Confirmación 3 -->
                  <div class="modal fade" id="modalConfirmDeleteRol3" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">¿Qué deseas hacer con los usuarios asignados a este rol?</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                          <div class="d-flex flex-column gap-2">
                            <button type="button" class="btn btn-secondary" id="optReasignarInvitado">
                              Pasar todos al rol "Invitado"
                            </button>
                            <button type="button" class="btn btn-secondary" id="optReasignarRolExistente">
                              Pasar todos a un rol existente
                            </button>
                            <button type="button" class="btn btn-secondary" id="optReasignarPorUsuario">
                              Asignar rol personalizado a cada usuario
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Modal Reasignar a otro rol -->
                  <div class="modal fade" id="modalReasignarRolExistente" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Seleccione un rol para reasignar</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                          <select id="selectRolReasignar" class="form-select"></select>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button type="button" class="btn btn-primary" id="btnConfirmReasignarRolExistente">Confirmar</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Modal Reasignar por usuario -->
                  <div class="modal fade" id="modalReasignarPorUsuario" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Asignar rol personalizado a cada usuario</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                          <table class="table table-striped" id="tablaReasignarUsuarios">
                            <thead>
                              <tr>
                                <th>Usuario</th>              
                                <th>Nuevo Rol</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                          </table>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button type="button" class="btn btn-primary" id="btnConfirmReasignarPorUsuario">Confirmar reasignación</button>
                        </div>
                      </div>
                    </div>
                  </div>





        

                
              </div>
            </div>
                           