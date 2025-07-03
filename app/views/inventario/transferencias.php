<?php
?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Registrar Transferencia de Stock</h4>

    <div class="card">
        <div class="card-body">
            <form id="form-transferencia">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="select-sucursal" class="form-label">Sucursal</label>
                        <select id="select-sucursal" name="id_sucursal" class="form-select select2" required>
                            <option value="" disabled selected>Seleccione una sucursal</option>
                            <?php if (isset($sucursales) && is_array($sucursales)): ?>
                                <?php foreach ($sucursales as $sucursal): ?>
                                    <option value="<?php echo htmlspecialchars($sucursal['id_sucursal']); ?>">
                                        <?php echo htmlspecialchars($sucursal['nombre_comercial']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="select-origen" class="form-label">Ubicación de Origen</label>
                        <select id="select-origen" name="id_ubicacion_origen" class="form-select select2" required disabled>
                            <option value="" disabled selected>Seleccione una sucursal primero</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="select-destino" class="form-label">Ubicación de Destino</label>
                        <select id="select-destino" name="id_ubicacion_destino" class="form-select select2" required disabled>
                            <option value="" disabled selected>Seleccione un origen primero</option>
                        </select>
                    </div>

                    <div class="col-md-12 mt-4">
                        <h5 class="border-bottom pb-2">Agregar Productos a la Transferencia</h5>
                        <div class="row align-items-end g-2 mb-2">
                            <div class="col-md-6">
                                <label for="select-producto-transfer" class="form-label">Producto</label>
                                <select id="select-producto-transfer" class="form-select select2" data-placeholder="Seleccione un producto" disabled></select>
                            </div>
                            <div class="col-md-2">
                                <label for="input-cantidad-transfer" class="form-label">Cantidad a Transferir</label>
                                <input type="number" class="form-control" id="input-cantidad-transfer" min="1" disabled>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Stock Disponible</label>
                                <input type="text" class="form-control" id="stock-disponible-label" readonly value="N/A">
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="button" class="btn btn-primary" id="btn-agregar-producto-transfer" disabled>
                                    <i class="bx bx-plus me-1"></i> Agregar
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <h6>Productos en esta Transferencia</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tabla-productos-transfer">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="textarea-observacion" class="form-label">Observación (Opcional)</label>
                        <textarea class="form-control" id="textarea-observacion" name="observacion" rows="3" placeholder="Ej: Transferencia para reabastecimiento semanal."></textarea>
                    </div>

                    <div class="col-md-12 text-end mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="bx bx-check-double me-1"></i> Confirmar Transferencia
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>