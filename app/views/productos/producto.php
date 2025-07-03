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
                
              <!-- Productos List Table -->
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

                <!-- Modal Visualizar Producto -->                
                <div class="modal fade" id="modalVerProducto" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold">VER DETALLE PRODUCTO</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row mb-3">
                          <div class="col-12">
                            <h6 class="text-muted mb-1">CÓDIGO PRODUCTO</h6>
                            <h5 id="ver-codigo-producto" class="fw-bold">-</h5>
                          </div>
                        </div>
                        
                        <div class="row mb-3">
                          <div class="col-12">
                            <h6 class="text-muted mb-1">NOMBRE PRODUCTO</h6>
                            <h5 id="ver-nombre-producto" class="fw-bold">-</h5>
                          </div>
                        </div>
                        
                        <div class="row mb-3">
                          <div class="col-12">
                            <h6 class="text-muted mb-1">DESCRIPCIÓN</h6>
                            <p id="ver-descripcion" class="mb-0">-</p>
                          </div>
                        </div>
                        
                        <div class="row mb-3">
                          <div class="col-md-4">
                            <h6 class="text-muted mb-1">MARCA</h6>
                            <p id="ver-marca" class="fw-bold mb-0">-</p>
                          </div>
                          <div class="col-md-4">
                            <h6 class="text-muted mb-1">CATEGORÍA</h6>
                            <p id="ver-categoria" class="fw-bold mb-0">-</p>
                          </div>
                          <div class="col-md-4">
                            <h6 class="text-muted mb-1">UNIDADES</h6>
                            <p id="ver-unidad" class="fw-bold mb-0">-</p>
                          </div>
                        </div>
                        
                        <div class="row mb-3">
                          <div class="col-md-4">
                            <h6 class="text-muted mb-1">STOCK ACTUAL</h6>
                            <p id="ver-stock-actual" class="fw-bold mb-0">-</p>
                          </div>
                          <div class="col-md-4">
                            <h6 class="text-muted mb-1">STOCK MÍNIMO</h6>
                            <p id="ver-stock-minimo" class="fw-bold mb-0">-</p>
                          </div>
                          <div class="col-md-4">
                            <h6 class="text-muted mb-1">STOCK MÁXIMO</h6>
                            <p id="ver-stock-maximo" class="fw-bold mb-0">-</p>
                          </div>
                        </div>
                        
                        <div class="row mb-3">
                          <div class="col-md-6">
                            <h6 class="text-muted mb-1">PRECIO DE VENTA</h6>
                            <p id="ver-precio-venta" class="fw-bold mb-0">-</p>
                          </div>
                          <div class="col-md-6">
                            <h6 class="text-muted mb-1">PRECIO SUGERIDO</h6>
                            <p id="ver-precio-sugerido" class="fw-bold mb-0">-</p>
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-12 text-center">
                            <h6 class="text-muted mb-2">IMAGEN DEL PRODUCTO</h6>
                            <div class="border rounded p-2" style="max-height: 300px; overflow: hidden;">
                              <img id="ver-imagen-producto" src="" class="img-fluid" style="max-height: 250px; display: none;">
                              <p id="sin-imagen" class="text-muted my-3">No hay imagen disponible</p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>
            
              </div>
            </div>

