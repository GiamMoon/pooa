<script>
  window.PERMISOS_USUARIO = <?= json_encode($_SESSION['permisos']) ?>;
</script>

          <!-- Content -->
           <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Product List Widget -->
              <div class="card mb-6">
                <div class="card-widget-separator-wrapper">
                  <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                      <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                          <div>
                            <p class="mb-1">En Tienda</p>                            
                            <p class="mb-0"><span class="me-2">(#valor)</span><span class="badge bg-label-success">+5.7%</span></p>
                          </div>
                          <span class="avatar me-sm-6">
                          <span class="avatar-initial rounded w-px-44 h-px-44">
                          <i class="icon-base bx bx-store-alt icon-lg text-heading"></i>
                          </span>
                          </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-6" />
                      </div>
                      <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                          <div>
                            <p class="mb-1">En Almacen</p>                            
                            <p class="mb-0"><span class="me-2">(#valor)</span><span class="badge bg-label-success">+12.4%</span></p>
                          </div>
                          <span class="avatar p-2 me-lg-6">
                          <span class="avatar-initial rounded w-px-44 h-px-44">
                          <i class="icon-base bx bx-archive icon-lg text-heading"></i>
                          </span>
                          </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none" />
                      </div>
                      <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                          <div>
                            <p class="mb-1">Productos Activos</p>                            
                            <p class="mb-0">(#valor)</p>
                          </div>
                          <span class="avatar p-2 me-sm-6">
                          <span class="avatar-initial rounded w-px-44 h-px-44">
                          <i class="icon-base bx bx-check-circle icon-lg text-heading"></i>
                          </span>
                          </span>
                        </div>
                      </div>
                      <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start">
                          <div>
                            <p class="mb-1">Productos Inactivos</p>                            
                            <p class="mb-0"><span class="me-2">(#valor)</span><span class="badge bg-label-danger">-3.5%</span></p>
                          </div>
                          <span class="avatar p-2">
                          <span class="avatar-initial rounded w-px-44 h-px-44">
                          <i class="icon-base bx bx-error-circle icon-lg text-heading"></i>
                          </span>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="app-ecommerce-category">
                
              <!-- Usuarios List Table -->
                <div class="card">
                  <div class="card-datatable">
                    <table class="datatables-category-list table">
                      <thead>
                        <tr>                          
                          <th class="text-nowrap text-sm-start">N°</th>  
                          <th class="text-lg-center">Imagen</th>
                          <th class="text-nowrap text-sm-start">Producto</th>                          
                          <th class="text-lg-center">Marca</th>
                          <th class="text-lg-center">Categoria</th>
                          <th class="text-nowrap text-sm-start">Modelos compatibles</th>
                          <th class="text-lg-center">Precio</th>                          
                          <th class="text-lg-center">Estado</th>
                          <th class="text-lg-center">Acciones</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>

                <!-- Modal Registrar Producto -->
                <div class="modal fade" id="modalRegistrarProducto" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-xl modal-dialog-scrollable">
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

                          <div class="col-md-6">
                            <label for="reg-cantidad-min" class="form-label">Cantidad Mínima</label>
                            <input type="number" class="form-control" id="reg-cantidad-min" name="cantidad_min" min="15">
                          </div>

                          <div class="col-md-6">
                            <label for="reg-cantidad-max" class="form-label">Cantidad Máxima</label>
                            <input type="number" class="form-control" id="reg-cantidad-max" name="cantidad_max" min="16">
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

                          <div class="col-md-12">
                            <label for="reg-marcas-moto" class="form-label">Marcas de Moto</label>
                            <select id="reg-marcas-moto" class="form-select select2-multiple" multiple="multiple" data-placeholder="Seleccione una o más marcas"></select>
                          </div>

                          <div class="col-md-12 mt-3" id="contenedor-marcas-modelos">
                            <!-- Aquí se renderizarán dinámicamente los modelos agrupados por marca -->
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




            
              </div>
            </div>

