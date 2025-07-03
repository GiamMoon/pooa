'use strict';

document.addEventListener("DOMContentLoaded", function () {
    const dtReporte = document.querySelector("#tabla-reporte-movimientos");
    const fechaInicio = document.querySelector('#reporte-fecha-inicio');
    const fechaFin = document.querySelector('#reporte-fecha-fin');

    if (fechaInicio) {
        fechaInicio.flatpickr({
            monthSelectorType: 'static',
            locale: 'es'
        });
    }
    if (fechaFin) {
        fechaFin.flatpickr({
            monthSelectorType: 'static',
            locale: 'es'
        });
    }

    const tabla = new DataTable(dtReporte, {
        columns: [
            { data: "fecha_registro" },
            { data: "codigo_movimiento" },
            { data: "tipo_movimiento" },
            { data: "nombre_producto" },
            { data: "cantidad" },
            { data: "ubicacion_origen" },
            { data: "ubicacion_destino" },
            { data: "usuario" }
        ],
        columnDefs: [
             {
                targets: 2,
                className: "text-center",
                render: function (data, type, row) {
                    const tipos = {
                        'ENTRADA': { title: 'ENTRADA', class: 'bg-label-success' },
                        'SALIDA': { title: 'SALIDA', class: 'bg-label-danger' },
                        'ENTRADA-T': { title: 'ENTRADA-T', class: 'bg-label-info' },
                        'SALIDA-T': { title: 'SALIDA-T', class: 'bg-label-warning' }
                    };
                    const tipo = tipos[data] || { title: data, class: 'bg-label-secondary' };
                    return `<span class="badge ${tipo.class}">${tipo.title}</span>`;
                }
            },
            { targets: [4], className: "text-center" }
        ],
        order: [[0, "desc"]],
        dom: 'Bfrtip', 
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="bx bxs-file-export me-1"></i>Exportar a Excel',
                className: 'btn btn-success',
                titleAttr: 'Exportar a Excel'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="bx bxs-file-pdf me-1"></i>Exportar a PDF',
                className: 'btn btn-danger',
                titleAttr: 'Exportar a PDF'
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
            emptyTable: "Seleccione un rango de fechas y genere el reporte.",
        }
    });

    $('#form-reporte-movimientos').on('submit', function (e) {
        e.preventDefault();

        const fechaInicioVal = $('#reporte-fecha-inicio').val();
        const fechaFinVal = $('#reporte-fecha-fin').val();

        if (!fechaInicioVal || !fechaFinVal) {
            Swal.fire('Atención', 'Debe seleccionar una fecha de inicio y una fecha de fin.', 'warning');
            return;
        }

        const formData = new FormData(this);
        
        tabla.clear().draw();
        tabla.settings()[0].oLanguage.sEmptyTable = "Cargando datos...";
        tabla.draw();

        fetch(`${site_url}/reportesAjax/generarReporteMovimientos`, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(response => {
            tabla.clear();
            tabla.rows.add(response.data);
            tabla.draw();
        })
        .catch(err => {
            console.error('Error al generar el reporte:', err);
            Swal.fire('Error', 'Ocurrió un problema al generar el reporte.', 'error');
        })
        .finally(() => {
            tabla.settings()[0].oLanguage.sEmptyTable = "No se encontraron movimientos para el rango de fechas seleccionado.";
        });
    });
});