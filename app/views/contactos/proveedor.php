<script>
  window.PERMISOS_USUARIO = <?= json_encode($_SESSION['permisos']) ?>;
</script>
<!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="app-ecommerce-category">

              <!-- Proveedor List Table-->
              <div class="card">
                <div class="card-datatable">
                  <table class="datatables-category-list table">
                    <thead class="border-top">
                      <tr>                        
                        <th class="text-nowrap text-sm-start">N°</th>
                        <th class="text-nowrap text-sm-start">Razón Social</th>
                        <th class="text-nowrap text-sm-start">RUC</th>
                        <th class="text-nowrap text-sm-start">Ubicación</th>                                                
                        <th class="text-lg-center">Estado SUNAT</th>
                        <th class="text-lg-center">Condición SUNAT</th>
                        <th class="text-lg-center">Estado</th>
                        <th class="text-lg-center">Acciones</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>

                <!-- MODEL REGISTRAR-->
                <div class="modal fade" id="modalRegistrarProveedor" tabindex="-1" aria-labelledby="modalRegistrarProveedorLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalRegistrarProveedorLabel">Registrar Proveedor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>

                      <form id="formRegistrarProveedor" onsubmit="return true">
                        <div class="modal-body row g-3">
                          
                          <!-- RUC + botón buscar -->
                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="prov-ruc">RUC</label>
                            <div class="input-group">
                              <input type="text" maxlength="11" inputmode="numeric" pattern="[0-9]*" class="form-control" id="prov-ruc" name="ruc" placeholder="Ingrese RUC del proveedor" />
                              <button id="buscarProveedor" type="button" class="btn btn-primary">
                                <i class="bx bx-search-alt"></i>
                              </button>
                            </div>
                          </div>

                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="prov-razon">Razón Social</label>
                            <input type="text" maxlength="100" id="prov-razon" name="razon_social" placeholder="Ingrese razón social" readonly class="form-control bg-light text-muted" />
                          </div>

                          <div class="col-md-12 form-control-validation">
                            <label class="form-label" for="prov-direccion">Dirección</label>
                            <input type="text" maxlength="255" id="prov-direccion" name="direccion" placeholder="Dirección del proveedor" readonly class="form-control bg-light text-muted"/>
                          </div>

                          <div class="col-md-4 form-control-validation">
                            <label class="form-label" for="prov-departamento">Departamento</label>
                            <input type="text" maxlength="100" id="prov-departamento" name="departamento" placeholder="Departamento" readonly class="form-control bg-light text-muted"/>
                          </div>

                          <div class="col-md-4 form-control-validation">
                            <label class="form-label" for="prov-provincia">Provincia</label>
                            <input type="text" maxlength="100" id="prov-provincia" name="provincia" placeholder="Provincia" readonly class="form-control bg-light text-muted"/>
                          </div>

                          <div class="col-md-4 form-control-validation">
                            <label class="form-label" for="prov-distrito">Distrito</label>
                            <input type="text" maxlength="100" id="prov-distrito" name="distrito" placeholder="Distrito" readonly class="form-control bg-light text-muted"/>
                          </div>

                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="prov-ubigeo">Ubigeo</label>
                            <input type="text" maxlength="6" id="prov-ubigeo" name="ubigeo" placeholder="Ubigeo (6 dígitos)" readonly class="form-control bg-light text-muted"/>
                          </div>

                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="prov-telefono">Teléfono (opcional)</label>
                            <input type="text" maxlength="9" class="form-control" id="prov-telefono" name="telefono" placeholder="Teléfono del proveedor" />
                          </div>

                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="prov-correo">Correo (opcional)</label>
                            <div class="input-group">
                              <input type="email" maxlength="255" class="form-control" id="prov-correo" name="correo" placeholder="Correo electrónico del proveedor" />
                              <button id="verificarcorreo" type="button" class="btn btn-primary"><i class="bx bx-message-check"></i></button>
                            </div>                              
                          </div>                          

                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="prov-contacto">Persona de contacto (opcional)</label>
                            <input type="text" maxlength="120" class="form-control" id="prov-contacto" name="contacto" placeholder="Persona de contacto" />
                          </div>

                          <div class="col-md-4 form-control-validation">
                            <label class="form-label" for="prov-estado-sunat">Estado SUNAT</label>
                            <select id="prov-estado-sunat" name="estado_sunat" class="form-select bg-light text-muted">
                              <option value="ACTIVO">Activo</option>
                              <option value="SUSPENSION TEMPORAL">Suspensión Temporal</option>
                              <option value="BAJA PROVISIONAL">Baja Provisional</option>
                              <option value="BAJA DEFINITIVA">Baja Definitiva</option>
                              <option value="BAJA PROVISIONAL DE OFICIO">Baja Provisional de Oficio</option>
                              <option value="BAJA DEFINITIVA DE OFICIO">Baja Definitiva de Oficio</option>
                            </select>
                          </div>

                          <div class="col-md-4 form-control-validation">
                            <label class="form-label" for="prov-condicion-sunat">Condición SUNAT</label>
                            <select id="prov-condicion-sunat" name="condicion_sunat" class="form-select bg-light text-muted">
                              <option value="HABIDO">Habido</option>
                              <option value="NO HABIDO">No habido</option>
                              <option value="NO HALLADO">No hallado</option>  
                            </select>
                          </div>

                          <div class="col-md-4 form-control-validation">
                            <label class="form-label" for="prov-agente-retencion">Agente de retención</label>
                            <select id="prov-agente-retencion" name="es_agente_retencion" class="form-select bg-light text-muted">
                              <option value="1">Sí</option>
                              <option value="0">No</option>
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


                <!-- MODEL VISUALIZAR-->
                <div class="modal fade" id="modalDetalleProveedor" tabindex="-1" aria-labelledby="modalDetalleProveedorLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Detalle del Proveedor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row g-3">
                          <div class="col-md-6">
                            <label class="form-label">RUC</label>
                            <input type="text" class="form-control" id="detalle-prov-ruc">
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Razón Social</label>
                            <input type="text" class="form-control" id="detalle-prov-razon">
                          </div>
                          <div class="col-md-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="detalle-prov-direccion">
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Departamento</label>
                            <input type="text" class="form-control" id="detalle-prov-departamento">
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Provincia</label>
                            <input type="text" class="form-control" id="detalle-prov-provincia">
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Distrito</label>
                            <input type="text" class="form-control" id="detalle-prov-distrito">
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Ubigeo</label>
                            <input type="text" class="form-control" id="detalle-prov-ubigeo">
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Teléfono (opcional)</label>
                            <input type="text" class="form-control" id="detalle-prov-telefono" placeholder="(vacío)">
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Correo (opcional)</label>
                            <input type="text" class="form-control" id="detalle-prov-correo" placeholder="(vacío)">
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Persona de contacto (opcional)</label>
                            <input type="text" class="form-control" id="detalle-prov-contacto" placeholder="(vacío)">
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Estado SUNAT</label>
                            <input type="text" class="form-control" id="detalle-prov-estado-sunat">
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Condición SUNAT</label>
                            <input type="text" class="form-control" id="detalle-prov-condicion-sunat">
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Agente de Retención</label>
                            <input type="text" class="form-control" id="detalle-prov-agente-retencion">
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Estado</label>
                            <input type="text" class="form-control" id="detalle-prov-estado">
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>


                <!-- MODEL EDITAR-->
                <div class="modal fade" id="modalEditarProveedor" tabindex="-1" aria-labelledby="modalEditarProveedorLabel"  aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">                    
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarProveedorLabel">Editar Proveedor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>

                      <form id="formEditarProveedor">
                        <div class="modal-body row g-3">
                          <input type="hidden" id="prov-edit-id_proveedor" name="id_proveedor"/>
                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="prov-edit-ruc">RUC</label>
                            <div class="input-group">
                              <input type="text" maxlength="11" inputmode="numeric" pattern="[0-9]*" id="prov-edit-ruc" name="ruc" placeholder="Ingrese RUC del proveedor" readonly class="form-control bg-light text-muted" />
                              <button id="refrescarProveedor" type="button" class="btn btn-primary"><i class="bx  bx-undo"></i></button>
                            </div>
                          </div>

                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="prov-edit-razon">Razón Social</label>
                            <input type="text" maxlength="100" id="prov-edit-razon" name="razon_social" placeholder="Ingrese razón social" readonly class="form-control bg-light text-muted" />
                          </div>

                          <div class="col-md-12 form-control-validation">
                            <label class="form-label" for="prov-edit-direccion">Dirección</label>
                            <input type="text" maxlength="255" id="prov-edit-direccion" name="direccion" placeholder="Dirección del proveedor" readonly class="form-control bg-light text-muted"/>
                          </div>

                          <div class="col-md-4 form-control-validation">
                            <label class="form-label" for="prov-edit-departamento">Departamento</label>
                            <input type="text" maxlength="100" id="prov-edit-departamento" name="departamento" placeholder="Departamento" readonly class="form-control bg-light text-muted"/>
                          </div>

                          <div class="col-md-4 form-control-validation">
                            <label class="form-label" for="prov-edit-provincia">Provincia</label>
                            <input type="text" maxlength="100" id="prov-edit-provincia" name="provincia" placeholder="Provincia" readonly class="form-control bg-light text-muted"/>
                          </div>

                          <div class="col-md-4 form-control-validation">
                            <label class="form-label" for="prov-edit-distrito">Distrito</label>
                            <input type="text" maxlength="100" id="prov-edit-distrito" name="distrito" placeholder="Distrito" readonly class="form-control bg-light text-muted"/>
                          </div>

                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="prov-edit-ubigeo">Ubigeo</label>
                            <input type="text" maxlength="6" id="prov-edit-ubigeo" name="ubigeo" placeholder="Ubigeo (6 dígitos)" readonly class="form-control bg-light text-muted"/>
                          </div>

                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="prov-edit-telefono">Teléfono (opcional)</label>
                            <input type="text" maxlength="9" class="form-control" id="prov-edit-telefono" name="telefono" placeholder="(vacío)" />
                          </div>

                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="prov-edit-correo">Correo (opcional)</label>
                            <div class="input-group">
                              <input type="email" maxlength="255" class="form-control" id="prov-edit-correo" name="correo" placeholder="(vacío)" />
                              <button id="verificarEditcorreo" type="button" class="btn btn-primary"><i class="bx bx-message-check"></i></button>
                            </div>                              
                          </div>                          

                          <div class="col-md-6 form-control-validation">
                            <label class="form-label" for="prov-edit-contacto">Persona de contacto (opcional)</label>
                            <input type="text" maxlength="120" class="form-control" id="prov-edit-contacto" name="contacto" placeholder="(vacío)" />
                          </div>

                          <div class="col-md-4 form-control-validation">
                            <label class="form-label" for="prov-edit-estado-sunat">Estado SUNAT</label>
                            <select id="prov-edit-estado-sunat" name="estado_sunat" class="form-select bg-light text-muted">
                              <option value="ACTIVO">Activo</option>
                              <option value="SUSPENSION TEMPORAL">Suspensión Temporal</option>
                              <option value="BAJA PROVISIONAL">Baja Provisional</option>
                              <option value="BAJA DEFINITIVA">Baja Definitiva</option>
                              <option value="BAJA PROVISIONAL DE OFICIO">Baja Provisional de Oficio</option>
                              <option value="BAJA DEFINITIVA DE OFICIO">Baja Definitiva de Oficio</option>
                            </select>
                          </div>

                          <div class="col-md-4 form-control-validation">
                            <label class="form-label" for="prov-edit-condicion-sunat">Condición SUNAT</label>
                            <select id="prov-edit-condicion-sunat" name="condicion_sunat" class="form-select bg-light text-muted">
                              <option value="HABIDO">Habido</option>
                              <option value="NO HABIDO">No habido</option>
                              <option value="NO HALLADO">No hallado</option>
                            </select>
                          </div>

                          <div class="col-md-4 form-control-validation">
                            <label class="form-label" for="prov-edit-agente-retencion">Agente de retención</label>
                            <select id="prov-edit-agente-retencion" name="es_agente_retencion" class="form-select bg-light text-muted">
                              <option value="1">Sí</option>
                              <option value="0">No</option>
                            </select>
                          </div>
                        </div>

                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary"> Guardar Cambios</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>

                      </form>
                    </div>                    
                  </div>
                </div>


                <!-- MODEL CAMBIAR-->                
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
            <!-- / Content -->
