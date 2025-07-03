'use strict';

document.addEventListener("DOMContentLoaded", function () {
    const dtInventario = document.querySelector(".datatables-inventario-list");

    if (dtInventario) {
        const tabla = new DataTable(dtInventario, {
            ajax: `${site_url}/inventarioAjax/listarMovimientos`,
            columns: [
                { data: "id_movimiento" },
                { data: "codigo_movimiento" },
                { data: "tipo_movimiento" },
                { data: "origen" },             
                { data: "destino" },            
                { data: "usuario" },
                { data: "fecha" },              
                { data: null }
            ],
            columnDefs: [
                { targets: 0, className: "text-center" },
                { targets: 1, className: "text-center" },
                {
                    targets: 2,
                    className: "text-center",
                    render: function (data, type, row) {
                        const tipos = {
                            'ENTRADA': { title: 'ENTRADA', class: 'bg-label-success' },
                            'SALIDA': { title: 'SALIDA', class: 'bg-label-danger' },
                            'ENTRADA-T': { title: 'ENTRADA-T', class: 'bg-label-success' }, // Añadido
                            'SALIDA-T': { title: 'SALIDA-T', class: 'bg-label-danger' },   // Añadido
                            'TRANSFERENCIA': { title: 'TRANSFERENCIA', class: 'bg-label-info' }
                        };
                        const tipo = tipos[data] || { title: data, class: 'bg-label-secondary' };
                        return `<span class="badge ${tipo.class}">${tipo.title}</span>`;
                    }
                },
                { targets: [3, 4, 5], className: "text-start" },
                { targets: 6, className: "text-center" },
                {
                    targets: 7,
                    title: 'Acciones',
                    orderable: false,
                    searchable: false,
                    className: "text-center",
                    render: function (data, type, row) {
                        return `<button class="btn btn-sm btn-icon btn-info btn-ver-detalle" data-id="${row.id_movimiento}" data-observacion="${row.observacion}" title="Ver Detalle"><i class="bx bx-show-alt"></i></button>`;
                    }
                }
            ],
            order: [[0, "desc"]],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
                emptyTable: "No se encontraron movimientos registrados",
                paginate: {
                    next: '<i class="bx bx-chevron-right scaleX-n1-rtl icon-18px"></i>',
                    previous: '<i class="bx bx-chevron-left scaleX-n1-rtl icon-18px"></i>'
                }
            },
        });

        $(dtInventario).on('click', '.btn-ver-detalle', function () {
            const idMovimiento = $(this).data('id');
            const observacion = $(this).data('observacion');

            $('#modalDetalleMovimientoTitle').text(`Detalle del Movimiento #${idMovimiento}`);
            $('#detalle-observacion').text(observacion || 'Sin observaciones.');

            const tbody = $('#tablaDetalleProductosMovimiento');
            tbody.html('<tr><td colspan="2" class="text-center">Cargando...</td></tr>');

            const modal = new bootstrap.Modal(document.getElementById('modalDetalleMovimiento'));
            modal.show();

            fetch(`${site_url}/inventarioAjax/getMovimientoDetalle/${idMovimiento}`)
                .then(res => res.json())
                .then(response => {
                    if (response.success && response.data.length > 0) {
                        tbody.empty();
                        response.data.forEach(item => {
                            tbody.append(`<tr><td>${item.nombre_producto}</td><td>${item.cantidad}</td></tr>`);
                        });
                    } else {
                        tbody.html('<tr><td colspan="2" class="text-center">No se encontraron productos en este movimiento.</td></tr>');
                    }
                })
                .catch(err => {
                    console.error('Error al cargar detalle:', err);
                    tbody.html('<tr><td colspan="2" class="text-center">Error al cargar los detalles.</td></tr>');
                });
        });
    }
});