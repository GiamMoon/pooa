'use strict';

document.addEventListener("DOMContentLoaded", function () {
    const selects = $("#form-transferencia .select2");
    if (selects.length) {
        selects.each(function () {
            const $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                dropdownParent: $this.parent(),
                placeholder: $this.data("placeholder") || 'Seleccione una opción'
            });
        });
    }

    let productosEnTransferencia = [];
    let ubicacionesDisponibles = [];
    let productosConStock = [];

    const selectSucursal = $('#select-sucursal');
    const selectOrigen = $('#select-origen');
    const selectDestino = $('#select-destino');
    const selectProducto = $('#select-producto-transfer');
    const inputCantidad = $('#input-cantidad-transfer');
    const labelStock = $('#stock-disponible-label');
    const btnAgregar = $('#btn-agregar-producto-transfer');
    const tablaProductosBody = $('#tabla-productos-transfer tbody');
    const formTransferencia = $('#form-transferencia');

    selectSucursal.on('change', function () {
        const idSucursal = $(this).val();
        selectOrigen.empty().append('<option value="" disabled selected>Seleccione una sucursal primero</option>').prop('disabled', true).trigger('change');
        if (idSucursal) {
            fetchUbicaciones(idSucursal);
        }
    });

    selectOrigen.on('change', function () {
        const idOrigenSeleccionado = $(this).val();
        resetSeccionProductos();
        productosEnTransferencia = [];
        actualizarTablaTransferencia();

        if (idOrigenSeleccionado) {
            const opcionesDestino = ubicacionesDisponibles.filter(u => u.valor_opcion !== idOrigenSeleccionado);
            
            selectDestino.empty().append('<option value="" disabled selected>Seleccione un destino</option>');
            opcionesDestino.forEach(u => {
                selectDestino.append(new Option(u.texto_opcion, u.valor_opcion));
            });
            selectDestino.prop('disabled', false).trigger('change');

            fetchProductosConStock(idOrigenSeleccionado);
        } else {
            selectDestino.prop('disabled', true).empty().append('<option value="" disabled selected>Seleccione un origen</option>').trigger('change');
        }
    });
    
    selectProducto.on('change', function() {
        const idProducto = $(this).val();
        if (idProducto) {
            const productoSeleccionado = productosConStock.find(p => p.id_producto == idProducto); 
            if (productoSeleccionado) {
                const stock = productoSeleccionado.stock_disponible;
                labelStock.val(stock);
                inputCantidad.val('').attr('max', stock).prop('disabled', false);
                btnAgregar.prop('disabled', false);
            } else {
                labelStock.val('N/A');
                inputCantidad.val('').prop('disabled', true);
                btnAgregar.prop('disabled', true);
            }
        } else {
            labelStock.val('N/A');
            inputCantidad.val('').prop('disabled', true);
            btnAgregar.prop('disabled', true);
        }
    });

    btnAgregar.on('click', function () {
        const idProducto = selectProducto.val();
        const nombreProducto = selectProducto.find('option:selected').text();
        const cantidad = parseInt(inputCantidad.val());
        const stockDisponible = parseInt(labelStock.val());

        if (!idProducto || !cantidad || cantidad <= 0) {
            Swal.fire('Atención', 'Seleccione un producto y una cantidad válida.', 'warning');
            return;
        }
        if (cantidad > stockDisponible) {
            Swal.fire('Error', `La cantidad a transferir (${cantidad}) no puede ser mayor al stock disponible (${stockDisponible}).`, 'error');
            return;
        }
        const productoExistente = productosEnTransferencia.find(p => p.id_producto == idProducto);
        if (productoExistente) {
             Swal.fire('Atención', 'Este producto ya ha sido agregado a la transferencia.', 'warning');
             return;
        }
        productosEnTransferencia.push({ id_producto: idProducto, nombre_producto: nombreProducto, cantidad: cantidad });
        actualizarTablaTransferencia();
        resetInputsProducto();
    });
    
    window.eliminarProductoTransfer = function(idProducto) {
        productosEnTransferencia = productosEnTransferencia.filter(p => p.id_producto != idProducto);
        actualizarTablaTransferencia();
    };

    formTransferencia.on('submit', function(e) {
        e.preventDefault();
        if (productosEnTransferencia.length === 0) {
            Swal.fire('Error', 'Debe agregar al menos un producto a la transferencia.', 'error');
            return;
        }
        const formData = new FormData(this);
        formData.append('productos_transferir', JSON.stringify(productosEnTransferencia));
        formData.append('id_usuario', 1);
        
        Swal.fire({
            title: '¿Confirmar Transferencia?',
            text: "Esta acción moverá el stock entre las ubicaciones seleccionadas y no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`${site_url}/inventarioAjax/registrarTransferencia`, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(response => {
                    if(response.success) {
                        Swal.fire('¡Éxito!', response.message, 'success');
                        resetFormularioCompleto();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Ocurrió un problema de comunicación con el servidor.', 'error'));
            }
        });
    });

    function fetchUbicaciones(idSucursal) {
        fetch(`${site_url}/inventarioAjax/getUbicacionesPorSucursal/${idSucursal}`)
            .then(res => res.json())
            .then(data => {
                ubicacionesDisponibles = data;
                selectOrigen.empty().append('<option value="" disabled selected>Seleccione un origen</option>');
                ubicacionesDisponibles.forEach(u => {
                    selectOrigen.append(new Option(u.texto_opcion, u.valor_opcion));
                });
                selectOrigen.prop('disabled', false).trigger('change');
            });
    }

    function fetchProductosConStock(origenCompuesto) {
        fetch(`${site_url}/inventarioAjax/getProductosConStock/${origenCompuesto}`)
            .then(res => res.json())
            .then(data => {
                productosConStock = data;
                selectProducto.empty().append('<option value="" disabled selected>Seleccione un producto</option>');
                if (productosConStock.length > 0) {
                    productosConStock.forEach(p => {
                        const textoOpcion = `${p.nombre_producto} (Stock: ${p.stock_disponible})`;
                        selectProducto.append(new Option(textoOpcion, p.id_producto));
                    });
                    selectProducto.prop('disabled', false);
                } else {
                     selectProducto.append('<option value="" disabled>No hay productos con stock</option>');
                }
                selectProducto.trigger('change');
            });
    }

    function actualizarTablaTransferencia() {
        tablaProductosBody.empty();
        if(productosEnTransferencia.length > 0) {
            productosEnTransferencia.forEach(p => {
                tablaProductosBody.append(`
                    <tr>
                        <td>${p.nombre_producto}</td>
                        <td>${p.cantidad}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-icon btn-danger" onclick="eliminarProductoTransfer('${p.id_producto}')" title="Eliminar">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });
        } else {
            tablaProductosBody.append('<tr><td colspan="3" class="text-center">Aún no hay productos en esta transferencia.</td></tr>');
        }
    }
    
    function resetFormularioCompleto() {
        formTransferencia[0].reset();
        selectSucursal.val(null).trigger('change');
    }

    function resetSeccionProductos() {
        selectProducto.empty().append('<option value="" disabled selected>Seleccione un origen</option>').prop('disabled', true).trigger('change');
        resetInputsProducto();
    }
    
    function resetInputsProducto() {
        selectProducto.val(null).trigger('change.select2');
        inputCantidad.val('').prop('disabled', true);
        labelStock.val('N/A');
        btnAgregar.prop('disabled', true);
    }
    
    actualizarTablaTransferencia();
});