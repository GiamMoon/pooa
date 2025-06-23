<script>
  window.PERMISOS_USUARIO = <?= json_encode($_SESSION['permisos']) ?>;
  window.USUARIO_ACTUAL_ID = <?= json_encode($_SESSION['id_usuario']) ?>;
</script>
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="app-ecommerce-category">

                <!-- Usuarios List Table -->
                <div class="card">
                  <div class="card-datatable">
                    <table class="datatables-category-list table">
                      <thead>
                        <tr>                          
                          <th class="text-nowrap text-sm-start">N°</th>  
                          <th class="text-nowrap text-sm-start">Usuarios</th>
                          <th class="text-nowrap text-sm-start">Correo &nbsp;</th>
                          <th class="text-nowrap text-sm-start">Rol</th>
                          <th class="text-lg-center">Estado</th>
                          <th class="text-lg-center">Acciones</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>

                <!-- MODEL REGISTRAR-->
                <div class="modal fade" id="modalRegistrarUsuario" tabindex="-1" aria-labelledby="modalRegistrarUsuarioLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalRegistrarUsuarioLabel">Registrar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>                      

                      <form id="formRegistrarUsuario" onsubmit="return true">
                        <div class="modal-body row g-3">                          
                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="reg-dni">DNI</label>                            
                            <div class="input-group">
                              <input type="text" maxlength="8" inputmode="numeric" pattern="[0-9]*" class="form-control" id="reg-dni" name="dni" placeholder="Ingrese el DNI"/>
                              <button id="buscar" class="btn btn-primary"><i class="bx bx-search-alt"></i></button>                            
                            </div>
                          </div>
                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="reg-nombre">Nombre</label>
                            <input type="text" maxlength="60" class="form-control" id="reg-nombre" name="nombre" placeholder="Ingrese el nombre" readonly/>
                          </div>
                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="reg-apellido">Apellido</label>
                            <input type="text" maxlength="60" class="form-control" id="reg-apellido" name="apellido" placeholder="Ingrese el apellido" readonly/>
                          </div>                      
                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="reg-telefono">Teléfono</label>
                            <input type="text" maxlength="9" inputmode="numeric" pattern="[0-9]*" class="form-control" id="reg-telefono" name="telefono" placeholder="Ingrese el Teléfono"/>
                          </div>                                                  
                          <div class="col-12 form-control-validation">
                            <label class="form-label" for="reg-direccion">Dirección</label>
                            <input type="text" maxlength="250" class="form-control" id="reg-direccion" name="direccion" placeholder="Ingrese la Dirección"/>
                          </div>                          
                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="reg-correo">Correo</label>                            
                            <div class="input-group">
                              <input type="email" maxlength="255" class="form-control" id="reg-correo" name="correo" placeholder="Ingrese el Email"/>
                              <button id="verificarcorreo" type="button" class="btn btn-primary"><i class="bx bx-message-check"></i></button>                            
                            </div>
                          </div>
                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="reg-usuario">Usuario</label>
                            <div class="input-group">
                              <input type="text" maxlength="255" class="form-control text-lowercase" id="reg-usuario" name="usuario" placeholder="Ingrese el Usuario"/>
                              <button id="verificarcusuario" type="button" class="btn btn-primary"><i class="bx bx-user-check"></i></button>                            
                            </div>                            
                          </div>
                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="reg-rol">Rol</label>
                            <select class="form-select select2" id="reg-rol" name="id_rol" >
                              <!-- Rellenar dinámicamente -->
                            </select>
                          </div>
                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="reg-fecha-limite">Fecha Límite (opcional)</label>
                            <input class="form-control" id="reg-fecha-limite" name="fecha_limite" placeholder="Tiempo indefinido">
                          </div>                          

                          <!-- Botones submit y cancelar -->
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Registrar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          </div>
                        </div>                        
                      </form>
                                            
                    </div>
                  </div>
                </div>

                <!-- MODAL VER-->
                <div class="modal fade" id="modalDetalleUsuario" tabindex="-1" aria-labelledby="modalDetalleUsuarioLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Detalle del Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row g-3">
                          <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="detalle-nombre">
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="detalle-apellido">
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">DNI</label>
                            <input type="text" class="form-control" id="detalle-dni">
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="detalle-telefono">
                          </div>
                          <div class="col-md-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="detalle-direccion">
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Correo</label>
                            <input type="email" class="form-control" id="detalle-correo">
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="detalle-usuario">
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Rol</label>
                            <input type="text" class="form-control" id="detalle-rol">
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <input type="text" class="form-control" id="detalle-estado">
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Fecha Límite (opcional)</label>
                            <input class="form-control" id="detalle-fecha-limite" name="fecha_limite" />
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- MODAL EDITAR-->
                <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <form id="formEditarUsuario">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Detalle Usuario</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">
                          <input type="hidden" id="edit-id_usuario" name="id_usuario" />
                          <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" maxlength="60" id="edit-nombre" name="nombre" readonly class="form-control bg-light text-muted"/>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Apellido</label>
                            <input type="text" maxlength="60" id="edit-apellido" name="apellido" readonly class="form-control bg-light text-muted"/>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">DNI</label>
                            <input type="text" maxlength="8" inputmode="numeric" pattern="[0-9]*" id="edit-dni" name="dni" readonly class="form-control bg-light text-muted"/>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" maxlength="9" inputmode="numeric" pattern="[0-9]*" class="form-control" id="edit-telefono" name="telefono"/>
                          </div>
                          <div class="col-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" maxlength="250" class="form-control" id="edit-direccion" name="direccion"/>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Correo</label>                      
                            <div class="input-group">
                              <input type="email" maxlength="255" class="form-control" id="edit-correo" name="correo"/>
                              <button id="verificarEditcorreo" type="button" class="btn btn-primary"><i class="bx bx-message-check"></i></button>                            
                            </div>

                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Usuario</label>                            
                            <div class="input-group">
                              <input type="text" maxlength="255" class="form-control text-lowercase" id="edit-usuario" name="usuario"/>
                              <button id="verificarEditusuario" type="button" class="btn btn-primary"><i class="bx bx-message-check"></i></button>                            
                            </div>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Rol</label>
                            <select class="form-select select2" name="id_rol" id="edit-rol">
                              <!-- Rellenar dinámicamente -->
                            </select>
                          </div>
                          <!--<div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="id_estado" id="edit-estado">
                              --><!-- Rellenar dinámicamente -->
                            <!--</select>
                          </div>-->
                          <div class="col-md-6">
                            <label class="form-label">Fecha Límite (opcional)</label>
                            <input class="form-control" id="edit-fecha-limite" name="fecha_limite" />
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
           