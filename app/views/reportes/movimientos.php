<?php
?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Reporte de Movimientos de Inventario</h4>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filtros del Reporte</h5>
        </div>
        <div class="card-body">
            <form id="form-reporte-movimientos">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label" for="reporte-fecha-inicio">Fecha de Inicio</label>
                        <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="reporte-fecha-inicio" name="fecha_inicio" />
                    </div>
                    <div class="col-md-5">
                        <label class="form-label" for="reporte-fecha-fin">Fecha de Fin</label>
                        <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="reporte-fecha-fin" name="fecha_fin" />
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-filter-alt me-1"></i> Generar Reporte
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="tabla-reporte-movimientos" class="table">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Código Movimiento</th>
                        <th>Tipo</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>