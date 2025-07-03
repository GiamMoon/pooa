

                <!-- Modal Registrar Proveedor -->
                <div class="modal fade" id="modalRegistrarProveedor" tabindex="-1" aria-labelledby="modalRegistrarProveedorLabel" aria-hidden="true">
                  <div class="modal-dialog modal-xl modal-dialog-centered">
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

                <!-- Modal Registrar Producto -->
                <div class="modal fade" id="modalRegistrarProducto" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-xl">
                    <form id="formRegistrarProducto" class="modal-content" enctype="multipart/form-data">
                      <div class="modal-header">
                        <h5 class="modal-title">Registrar Nuevo Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row g-3">
                          <div class="col-md-6">
                            <label for="reg-nombre-producto" class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control text-uppercase" id="reg-nombre-producto" name="nombre" maxlength="150">
                          </div>

                          <div class="col-md-6">
                            <label for="reg-categoria" class="form-label">Categoría</label>
                            <select class="form-select select2" id="reg-categoria" name="id_categoria" data-placeholder="Seleccione una categoría"></select>
                          </div>

                          <div class="col-md-6">
                            <label for="reg-marca" class="form-label">Marca del Producto</label>
                            <select class="form-select select2" id="reg-marca" name="id_marca" data-placeholder="Seleccione una marca"></select>
                          </div>

                          <div class="col-md-6">
                            <label for="reg-tipo-unidad" class="form-label">Tipo de Unidad</label>
                            <select class="form-select select2" id="reg-tipo-unidad" name="id_tipo_unidad" data-placeholder="Seleccione tipo de unidad"></select>
                          </div>                          

                          <div class="col-md-12">
                            <label for="reg-descripcion" class="form-label">Descripción (opcional)</label>
                            <textarea class="form-control" id="reg-descripcion" name="descripcion" rows="2" maxlength="250"></textarea>
                          </div>

                          <div class="col-md-6">
                            <label for="reg-precio-venta" class="form-label">Precio de Venta (opcional)</label>
                            <input type="number" step="0.01" class="form-control" id="reg-precio-venta" name="precio_venta" min="0.00">
                          </div>

                          <div class="col-md-6">
                            <label for="reg-url-imagen" class="form-label">Imagen (opcional)</label>
                            <input type="file" class="form-control" id="reg-url-imagen" name="url_imagen" accept="image/png, image/jpeg, image/webp">
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Registrar Producto</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                      </div>
                    </form>
                  </div>
                </div>

<script src="<?= BASE_URL ?>/assets/js/app-producto-lista.js"></script>

<script src="<?= BASE_URL ?>/assets/js/app-proveedor-lista.js"></script>
