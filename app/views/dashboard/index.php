<div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Dashboard /</span> Business Intelligence
    </h4>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Filtros de Período</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="dashboard-date-range" class="form-label">Rango de Fechas</label>
                    <input type="text" class="form-control" id="dashboard-date-range" placeholder="YYYY-MM-DD a YYYY-MM-DD" />
                </div>
                <div class="col-md-8 d-flex align-items-end">
                    <div class="btn-group" role="group" aria-label="Quick-select date ranges">
                        <button type="button" class="btn btn-outline-primary" data-range="today">Hoy</button>
                        <button type="button" class="btn btn-outline-primary" data-range="this_month">Este Mes</button>
                        <button type="button" class="btn btn-outline-primary" data-range="last_month">Mes Pasado</button>
                        <button type="button" class="btn btn-outline-primary" data-range="this_year">Este Año</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Total de Ventas</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2" id="kpi-total-ventas">S/ 0.00</h3>
                            </div>
                            <small>Ventas en el período seleccionado</small>
                        </div>
                        <span class="badge bg-label-primary rounded p-2">
                            <i class="bx bx-dollar bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Total de Compras</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2" id="kpi-total-compras">S/ 0.00</h3>
                            </div>
                            <small>Compras en el período seleccionado</small>
                        </div>
                        <span class="badge bg-label-success rounded p-2">
                            <i class="bx bx-shopping-bag bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Clientes Totales</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2" id="kpi-total-clientes">0</h3>
                            </div>
                            <small>Clientes activos en el sistema</small>
                        </div>
                        <span class="badge bg-label-warning rounded p-2">
                            <i class="bx bx-user-plus bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card" id="card-stock-critico" style="cursor: pointer;" title="Haz clic para ver el detalle">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Stock Crítico</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2" id="kpi-productos-criticos">0</h3>
                            </div>
                            <small>Productos bajo el stock mínimo</small>
                        </div>
                        <span class="badge bg-label-danger rounded p-2">
                            <i class="bx bx-error-alt bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8"><div class="card h-100"><div class="card-header"><h5 class="card-title mb-0">Resumen de Ventas</h5><small class="text-muted">Ventas totales durante el período seleccionado</small></div><div class="card-body"><div id="salesByPeriodChart"></div></div></div></div>
        <div class="col-12 col-xl-4"><div class="card h-100"><div class="card-header"><h5 class="card-title mb-0">Top 5 Productos Vendidos</h5></div><div class="card-body"><div id="topProductsChart"></div></div></div></div>
        <div class="col-12 col-xl-6"><div class="card h-100"><div class="card-header"><h5 class="card-title mb-0">Ventas por Sucursal</h5></div><div class="card-body"><div id="salesByBranchChart"></div></div></div></div>
        <div class="col-12 col-xl-6"><div class="card h-100"><div class="card-header"><h5 class="card-title mb-0">Top 5 Compras por Proveedor</h5></div><div class="card-body"><div id="purchasesBySupplierChart"></div></div></div></div>
    </div>
</div>

<div class="modal fade" id="criticalStockModal" tabindex="-1" aria-labelledby="criticalStockModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="criticalStockModalLabel">Productos en Stock Crítico</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Stock Actual</th>
                <th>Stock Mínimo</th>
              </tr>
            </thead>
            <tbody id="criticalStockTableBody">
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo BASE_URL('public/assets/js/app-dashboard.js'); ?>"></script>