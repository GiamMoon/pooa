'use strict';

document.addEventListener("DOMContentLoaded", function () {
    const dtRecepcion = document.querySelector(".datatables-recepcion-list");

    if (dtRecepcion) {
        const tabla = new DataTable(dtRecepcion, {
            ajax: `${site_url}/compraAjax/listarParaRecepcion`,
            columns: [
                { data: null },
                { data: "codigo_compra" },
                { data: "nombre_proveedor" },
                { data: "fecha_registro" },
                { data: "activo" },
                { data: "id_compra" }
            ],
            columnDefs: [
                { targets: 0, className: "text-start", orderable: false, searchable: false, render: (data, type, row, meta) => meta.row + 1 },
                { targets: 1, className: "text-center" },
                { targets: 2, className: "text-start" },
                { targets: 3, className: "text-center" },
                {
                    targets: 4, className: "text-center",
                    render: (data) => {
                        const estados = {
                            5: { title: 'Recibida Parcialmente', class: 'bg-label-info' },
                            8: { title: 'Pendiente a Entrega', class: 'bg-label-dark' }
                        };
                        const estado = estados[data] || { title: 'Desconocido', class: 'bg-label-secondary' };
                        return `<span class="badge ${estado.class}">${estado.title}</span>`;
                    }
                },
                {
                    targets: 5, className: "text-center", orderable: false, searchable: false,
                    render: (id_compra) => `<button class="btn btn-sm btn-warning" title="Recepcionar" data-id="${id_compra}"><i class="bx bx-archive-in me-1"></i> Recepcionar</button>`
                }
            ],
            order: [[0, "desc"]],
            language: {
                emptyTable: "No hay compras pendientes de recepción",
                paginate: { next: '<i class="bx bx-chevron-right scaleX-n1-rtl"></i>', previous: '<i class="bx bx-chevron-left scaleX-n1-rtl"></i>' }
            }
        });

        $(document).on('click', 'button[title="Recepcionar"]', function () {
            const idCompra = $(this).data('id');
            
            fetch(`${site_url}/compraAjax/obtenerDetalleParaRecepcion?id=${idCompra}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        Swal.fire('Error', data.message || 'No se pudo obtener el detalle de la compra.', 'error');
                        return;
                    }
                    
                    const compra = data.data;
                    $('#recepcion-id-compra').val(idCompra);
                    $('#recepcion-codigo-compra').val(compra.codigo_compra);
                    $('#recepcion-proveedor').val(compra.proveedor);

                    const infoDestino = `${compra.tipo_ubicacion_destino}: ${compra.direccion_destino}`;
                    $('#recepcion-info-destino').val(infoDestino);


                    const tbody = $('#tablaRecepcionProductos tbody');
                    tbody.empty();
                    if (compra.productos && Array.isArray(compra.productos)) {
                        compra.productos.forEach(p => {
                            if (p.cantidad_pendiente > 0) {
                                tbody.append(`
                                    <tr data-id-producto="${p.id_producto}" data-cantidad-esperada="${p.cantidad_total}" data-cantidad-pendiente="${p.cantidad_pendiente}">
                                        <td>${p.nombre_producto}</td>
                                        <td class="text-center"><span class="badge bg-primary">${p.cantidad_total}</span></td>
                                        <td class="text-center"><span class="badge bg-success">${p.cantidad_ya_recibida}</span></td>
                                        <td class="text-center"><span class="badge bg-warning">${p.cantidad_pendiente}</span></td>
                                        <td><input type="number" class="form-control cantidad-a-recibir" value="${p.cantidad_pendiente}" min="0" max="${p.cantidad_pendiente}"></td>
                                        <td><input type="file" class="form-control evidencia-producto" name="evidencia_producto_${p.id_producto}" accept="image/*,.pdf"></td>
                                    </tr>
                                `);
                            }
                        });
                    }

                    new bootstrap.Modal(document.getElementById('modalProcesarRecepcion')).show();
                })
                .catch(() => Swal.fire('Error', 'Ocurrió un problema al cargar los datos de la compra.', 'error'));
        });

        $('#formProcesarRecepcion').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            
            let productosRecibidos = [];
            $('#tablaRecepcionProductos tbody tr').each(function() {
                const fila = $(this);
                const cantidadARecibir = parseInt(fila.find('.cantidad-a-recibir').val()) || 0;
                
                if (cantidadARecibir > 0) {
                    productosRecibidos.push({
                        id_producto: fila.data('id-producto'),
                        cantidad_recibida: cantidadARecibir,
                        cantidad_esperada: fila.data('cantidad-pendiente')
                    });

                    const evidenciaInput = fila.find('.evidencia-producto')[0];
                    if (evidenciaInput.files.length > 0) {
                        formData.append(`evidencia_producto_${fila.data('id-producto')}`, evidenciaInput.files[0]);
                    }
                }
            });

            if (productosRecibidos.length === 0) {
                Swal.fire('Atención', 'Debe ingresar una cantidad a recibir para al menos un producto.', 'warning');
                return;
            }

            formData.append('productos_recibidos', JSON.stringify(productosRecibidos));

            fetch(`${site_url}/compraAjax/procesarRecepcionMejorada`, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    Swal.fire('¡Éxito!', response.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalProcesarRecepcion')).hide();
                    tabla.ajax.reload();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            })
            .catch(() => Swal.fire('Error', 'Ocurrió un problema de comunicación con el servidor.', 'error'));
        });
    }
});