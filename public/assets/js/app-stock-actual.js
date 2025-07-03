'use strict';

document.addEventListener("DOMContentLoaded", function () {
  const dtStockActual = document.querySelector(".datatables-stock-actual");

  if (dtStockActual) {
    const tabla = new DataTable(dtStockActual, {
      ajax: `${site_url}/inventarioAjax/listarStockActual`,
      
      columns: [
        { data: "nombre_producto" },
        { data: "nombre_categoria" },
        { data: "sucursal" },
        { data: "tipo_ubicacion" },
        { data: "ubicacion_especifica" },
        { data: "cantidad" },
        { data: "stock_minimo" }
      ],
      columnDefs: [
        {
          targets: 3,
          className: "text-center",
          render: function (data, type, row) {
            if (data === 'TIENDA') {
              return `<span class="badge bg-label-primary">${data}</span>`;
            } else if (data === 'ALMACEN') {
              return `<span class="badge bg-label-info">${data}</span>`;
            }
            return data;
          }
        },
        {
          targets: 5,
          className: "text-center fw-bold",
          render: function (data, type, row) {
            if (row.alerta_stock == 1) {
              return `<span class="badge bg-label-danger fs-6">${data}</span>`;
            }
            return `<span class="badge bg-label-success fs-6">${data}</span>`;
          }
        },
        { targets: [0, 1, 2, 4], className: "text-start" },
        { targets: [6], className: "text-center" }
      ],
      order: [[2, "asc"], [0, "asc"], [3, "asc"]], 
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
        emptyTable: "No se encontraron productos con stock.",
      },
      rowGroup: {
          dataSrc: 'sucursal'
      }
    });
  }
});