'use strict';

document.addEventListener("DOMContentLoaded", function () {
  const dtCompras = document.querySelector(".datatables-compra-list");
  const selects = $(".select2");
  // Inyectar permisos del usuario desde PHP
  const permisos = window.PERMISOS_USUARIO || [];

  // Helper para verificar si el usuario tiene un permiso específico en la vista actual
  const tienePermisoVista = (permisoEsperado) => {
    return permisos.some(p =>
      p.ruta === 'compra/compra' && p.permiso === permisoEsperado
    );
  };

  // Activa select2
  if (selects.length) {
    selects.each(function () {
      const $this = $(this);
      $this.wrap('<div class="position-relative"></div>').select2({
        dropdownParent: $this.parent(),
        placeholder: $this.data("placeholder")
      });
    });
  }

  // Inicializa DataTable
  if (dtCompras) {

  const filtroCustomFeature = function(settings) {
    const container = document.createElement('div');
    container.classList.add('d-flex', 'align-items-center', 'gap-2', 'flex-wrap');

    container.innerHTML = `
    <div class="row gx-2 mx-3 mb-3 align-items-center">
      <div class="col-auto">
        <select id="compra-filtro-campo" class="form-select form-select-sm">
          <option value="codigo_compra">Código</option>
          <option value="nombre_proveedor">Proveedor</option>
        </select>
      </div>
      <div class="col">
        <input type="text" id="compra-filtro-valor" class="form-control form-control-sm" placeholder="Buscar por filtro seleccionado...">
      </div>
      <div class="col-auto">
        <button id="btnResetCompra" class="btn btn-sm btn-secondary">
          <i class="bx bx-reset"></i> Limpiar
        </button>
      </div>
    </div>
    `;

    return container;
  };


  const botonesTop = [];

    if (tienePermisoVista('visualizar')) {
      botonesTop.push({
        extend: 'collection',
        className: 'btn btn-label-info dropdown-toggle me-2',
        text: `<span class="d-flex align-items-center gap-2">
                <i class="bx bx-filter-alt icon-xs"></i>
                <span class="d-none d-sm-inline-block">Estado</span>
              </span>`,
        autoClose: true,
        buttons: [
          {
            text: 'Pendiente Recepción',
            className: 'dropdown-item',
            action: function () {
              tabla.column(5).search('^3$', true, false).draw();
            }
          },
          {
            text: 'Recibida Parcialmente',
            className: 'dropdown-item',
            action: function () {
              tabla.column(5).search('^4$', true, false).draw();
            }
          },
          {
            text: 'Completada',
            className: 'dropdown-item',
            action: function () {
              tabla.column(5).search('^5$', true, false).draw();
            }
          },
          {
            text: 'Todas',
            className: 'dropdown-item',
            action: function () {
              tabla.column(5).search('').draw();
            }
          }
        ]
      });
    }
  
    if (tienePermisoVista('exportar')) {
      botonesTop.push({
        extend: "collection",
        className: "btn btn-label-secondary dropdown-toggle me-4",
        text: `<span class="d-flex align-items-center gap-2">
                 <i class="icon-base bx bx-export icon-xs"></i>
                 <span class="d-none d-sm-inline-block">Exportar</span>
               </span>`,

        buttons: [
          {
            extend: "print",
            text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-printer me-1"></i>Print</span>`,
            className: "dropdown-item",
            exportOptions: { columns: [1,2,3,4,5] }
          },
          {
            extend: "csv",
            text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-file me-1"></i>Csv</span>`,
            className: "dropdown-item",
            exportOptions: { columns: [1,2,3,4,5] }
          },
          {
            extend: "excel",
            text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-export me-1"></i>Excel</span>`,
            className: "dropdown-item",
            exportOptions: { columns: [1,2,3,4,5] }
          },
          {
            extend: "pdf",
            text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-pdf me-1"></i>Pdf</span>`,
            className: "dropdown-item",
            exportOptions: { columns: [1,2,3,4,5] }
          },
          {
            extend: "copy",
            text: `<i class="icon-base bx bx-copy me-1"></i>Copy`,
            className: "dropdown-item",
            exportOptions: { columns: [1,2,3,4,5] }
          }
        ]
      });
    }

    if (tienePermisoVista('registrar')) {
      botonesTop.push({
        text: '<i class="bx bx-plus"></i><span class="d-none d-sm-inline-block">Nueva Compra</span>',
        className: "add-new btn btn-primary",
        attr: {
          "data-bs-toggle": "modal",
          "data-bs-target": "#modalRegistrarCompra"
        }
      });
    }

    const tabla = new DataTable(dtCompras, {
      ajax: assetsPath + "compraAjax/listar",
      columns: [
        { data: null },                 // Número oculto (control)
        { data: "codigo_compra" },               // correo
        { data: "nombre_proveedor"},       // razón social        
        { data: "fecha_registro" },          // rol
        { data: "total" },       // estado (SUNAT)          
        { data: "activo" },             // estado (badge)
        { data: "id_compra" }        // acciones con ID
      ],
      columnDefs: [
        {
          targets: 0,
          className: "text-start",
          orderable: true,
          searchable: false,
          render: (data, type, row, meta) => {
            return meta.row + 1; // Las filas empiezan en 0
          }
        },
        {
          targets: 1,
          className: "text-center",          
        },
        {
          targets: 2,
          className: "text-start",          
        },
        {
          targets: 3,
          className: "text-center",          
        },
        {
          targets: 4,
          className: "text-center",          
        },
        {
          targets: 5,
          className: "text-center",
          render: (data, type) => {
            if ( type === 'display') {
              switch (data) {
                case 3:                                  
                  return '<span class="badge bg-label-secondary">Pendiente Recepción</span>';
                case 4:
                  return '<span class="badge bg-label-warning">Recibida Parcialmente</span>';
                case 5:                  
                  return '<span class="badge bg-label-success">Completada</span>';
                default:
                  return `<span class="badge bg-label-secondary">${data}</span>`;
              }              
            }
            return data;
          }         
        },                 
        {
          targets: 6, // ACCIONES
          className: "text-center",
          orderable: false,
          searchable: false,
          render: (id) => {
            let botones = '';

            // Ver
            if (tienePermisoVista('visualizar')) {
              botones += `
                <button class="btn btn-sm btn-icon btn-info" title="Ver" data-id="${id}">
                  <i class="bx bx-show-alt"></i>
                </button>`;
            }

            // Solo si tiene editar
            if (tienePermisoVista('editar')) {
              botones += `
                <button class="btn btn-sm btn-icon btn-warning" title="Recepcionar" data-id="${id}">
                  <i class="bx bx-archive-in"></i>
                </button>`;
            }

            // Solo si tiene eliminar
            if (tienePermisoVista('eliminar')) {
              botones += `
                <button class="btn btn-sm btn-icon btn-danger" title="Eliminar" data-id="${id}">
                  <i class="bx bx-git-commit"></i>
                </button>`;
            }

            return `<div class="d-flex justify-content-center gap-1">${botones}</div>`;
          }
        }
      ],
      select: {
        style: "multi",
        selector: "td:nth-child(2)"
      },
      order: [[7, "asc"]],
      layout: {
        topStart: {
          rowClass: "row m-3 my-0 justify-content-between",
          features: [filtroCustomFeature]
        },
        topEnd: {
          rowClass: "row m-3 my-0 justify-content-between",
          features: {          
            pageLength: {
              menu: [5, 10, 15, 20],
              text: "_MENU_"
            },
            buttons: botonesTop
          }
        },
        bottomStart: {
          rowClass: "row mx-3 justify-content-between",
          features: ["info"]
        },
        bottomEnd: {
          paging: {
            firstLast: false
          }
        }
      },
      language: {
        emptyTable: "No se encontraron Compras registrados",
        paginate: {
          next: '<i class="bx bx-chevron-right scaleX-n1-rtl icon-18px"></i>',
          previous: '<i class="bx bx-chevron-left scaleX-n1-rtl icon-18px"></i>'
        }
      },
      responsive: {
        details: {
          display: DataTable.Responsive.display.modal({
            header: row => 'Detalles de ' + row.data().codigo_compra
          }),
          type: "column",
          renderer: DataTable.Responsive.renderer.listHiddenNodes()
        }
      }
    });

    /* Filtro de estado por defecto = Activo */
    tabla.column(5).search('^3$', true, false).draw();
    
    /* PARA FILTROS Y BUSQUEDAD*/
    $(document).on("input", "#compra-filtro-valor", function () {
      const campo = $("#compra-filtro-campo").val();
      const valor = $(this).val().trim();

      let colIndex = -1;
      switch (campo) {
        case "codigo_compra":
          colIndex = 1;
          break;
        case "nombre_proveedor":
          colIndex = 2;
          break;        
      }

      if (colIndex >= 0) {
        tabla.columns().search('');
        tabla.column(colIndex).search(valor).draw();
      }
    });

    // Resetear filtro personalizado
    $(document).on("click", "#btnResetCompra", function () {
      $("#compra-filtro-valor").val("");
      tabla.columns().search('');
      tabla.column(5).search('^3$', true, false).draw(); // Restaurar a activos
    });
  }


/* ---- INICIO: EVENTO REGISTRAR COMPRA ---- */
$(function () {
  let productosCompra = [];

  // Cargar proveedores al abrir modal
  $('#modalRegistrarCompra').on('show.bs.modal', function () {
    productosCompra = [];
    $('#tablaProductosCompra tbody').empty();
    actualizarTotalCompra();
    cargarProveedores();
    cargarProductos();
  });

  function cargarProveedores() {
    const select = $('#reg-proveedor');
    select.empty().append('<option disabled selected>Seleccione un proveedor</option>');
    fetch(`${assetsPath}compraAjax/listar_proveedores`)
      .then(res => res.json())
      .then(data => {
        data.forEach(p => {
          select.append(`<option value="${p.id_proveedor}">${p.nombre_proveedor}</option>`);
        });
      })
      .catch(() => Toastify({ text: 'Error al cargar proveedores ❌', style: { background: '#dc3545' } }).showToast());
  }

  // Evitar valores negativos en campos numéricos
$('#cantidad, #precio').on('input', function () {
  let val = parseFloat($(this).val());
  if (val < 0) {
    $(this).val(''); // Vacía el campo si es negativo
    Toastify({
      text: 'No se permiten valores negativos ❌',
      duration: 2000,
      style: { background: '#dc3545' }
    }).showToast();
  }
});


  function cargarProductos() {
    const select = $('#producto');
    select.empty().append('<option disabled selected>Seleccione un producto</option>');
    fetch(`${assetsPath}compraAjax/listar_productos`)
      .then(res => res.json())
      .then(data => {
        data.forEach(p => {
          select.append(`<option value="${p.id_producto}">${p.nombre_producto}</option>`);
        });
      })
      .catch(() => Toastify({ text: 'Error al cargar productos ❌', style: { background: '#dc3545' } }).showToast());
  }

  // Calcular subtotal dinámico
  $('#cantidad, #precio').on('input', function () {
    const cantidad = parseFloat($('#cantidad').val()) || 0;
    const precio = parseFloat($('#precio').val()) || 0;
    $('#subtotal').val((cantidad * precio).toFixed(2));
  });

  // Agregar producto a la tabla
  $('#btnAgregarProducto').on('click', function () {
    const idProducto = $('#producto').val();
    const nombreProducto = $('#producto option:selected').text();
    const cantidad = parseInt($('#cantidad').val());
    const precio = parseFloat($('#precio').val());
    const subtotal = cantidad * precio;

    if (!idProducto || cantidad <= 0 || precio <= 0) {
      Toastify({ text: 'Complete los campos del producto correctamente ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }

    productosCompra.push({ id_producto: idProducto, nombre: nombreProducto, cantidad, precio, subtotal });
    renderTablaProductos();
    limpiarInputsProducto();
    actualizarTotalCompra();
  });

  function renderTablaProductos() {
    const tbody = $('#tablaProductosCompra tbody');
    tbody.empty();
    productosCompra.forEach((prod, index) => {
      tbody.append(`
        <tr>
          <td>${prod.nombre}</td>
          <td>${prod.cantidad}</td>
          <td>S/ ${prod.precio.toFixed(2)}</td>
          <td>S/ ${prod.subtotal.toFixed(2)}</td>
          <td><button class="btn btn-danger btn-sm" onclick="eliminarProducto(${index})"><i class="bx bx-trash"></i></button></td>
        </tr>`);
    });
  }

  window.eliminarProducto = function (index) {
    productosCompra.splice(index, 1);
    renderTablaProductos();
    actualizarTotalCompra();
  }

  function limpiarInputsProducto() {
    $('#producto').val('').trigger('change');
    $('#cantidad').val('');
    $('#precio').val('');
    $('#subtotal').val('');
  }

  function actualizarTotalCompra() {
    const total = productosCompra.reduce((sum, prod) => sum + prod.subtotal, 0);
    $('#totalCompra').text(`S/ ${total.toFixed(2)}`);
  }

  // Enviar formulario
  $('#formRegistrarCompra').on('submit', function (e) {
    e.preventDefault();

    const idProveedor = $('#reg-proveedor').val();
    if (!idProveedor || productosCompra.length === 0) {
      Toastify({ text: 'Seleccione un proveedor y al menos un producto ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }

    const totalCompra = productosCompra.reduce((sum, prod) => sum + prod.subtotal, 0);
    const datosCompra = new FormData();
    datosCompra.append('id_proveedor', idProveedor);
    datosCompra.append('total', totalCompra);
    datosCompra.append('detalle_compra', JSON.stringify(productosCompra));

    fetch(`${assetsPath}compraAjax/registrar`, {
      method: 'POST',
      body: datosCompra
    })
    .then(res => res.json())
    .then(data => {
      if (data.success && data.id_compra) {
        // Registrar productos
        const promesas = productosCompra.map(p => {
          const formDataDetalle = new FormData();
          formDataDetalle.append('id_compra', data.id_compra);
          formDataDetalle.append('id_producto', p.id_producto);
          formDataDetalle.append('precio_compra', p.precio);
          formDataDetalle.append('cantidad', p.cantidad);
          return fetch(`${assetsPath}compraAjax/insertar_detalle_compra`, {
            method: 'POST',
            body: formDataDetalle
          });
        });
        return Promise.all(promesas);
      } else {
        throw new Error('Error al registrar compra.');
      }
    })
    .then(() => {
      Toastify({ text: 'Compra registrada correctamente ✅', style: { background: '#28a745' } }).showToast();
      bootstrap.Modal.getInstance(document.getElementById('modalRegistrarCompra')).hide();
      $('#formRegistrarCompra')[0].reset();
      productosCompra = [];
      renderTablaProductos();
      actualizarTotalCompra();
    })
    .catch(err => {
      console.error('Error en el registro de la compra:', err);
      Toastify({ text: 'Error registrando compra ❌', style: { background: '#dc3545' } }).showToast();
    });
  });
});
/* ---- FIN: EVENTO REGISTRAR COMPRA ---- */


/* ---- INICIO: EVENTO VISUALIZAR COMPRA ---- */
$(document).on('click', 'button[title="Ver"]', function () {
  const idCompra = $(this).data('id');

  fetch(`${assetsPath}compraAjax/obtener?id=${idCompra}`)
    .then(res => res.json())
    .then(data => {
      if (!data.success) {
        Toastify({ text: data.message || 'Error al obtener detalle ❌', style: { background: '#dc3545' } }).showToast();
        return;
      }

      const compra = data.data;

      $('#detalle-codigo-compra').val(compra.codigo_compra);
      $('#detalle-proveedor').val(compra.proveedor);
      $('#detalle-fecha-compra').val(compra.fecha_compra);

      const tbody = $('#tablaDetalleProductos tbody');
      tbody.empty();
      let total = 0;

      compra.productos.forEach(p => {
        total += parseFloat(p.subtotal);
        tbody.append(`
          <tr>
            <td>${p.nombre_producto}</td>            
            <td>${p.cantidad}</td>
            <td>S/ ${parseFloat(p.precio_compra).toFixed(2)}</td>
            <td>S/ ${parseFloat(p.subtotal).toFixed(2)}</td>
          </tr>
        `);
      });

      //Deshabilitar todos los elementos del formulario
      $('#modalDetalleCompra').find('input, select, textarea').prop('disabled', true);

      $('#detalle-total-compra').text(`S/ ${total.toFixed(2)}`);
      const modal = new bootstrap.Modal(document.getElementById('modalDetalleCompra'));
      modal.show();
    })
    .catch(err => {
      console.error('Error al obtener detalle', err);
      Toastify({ text: 'Error cargando detalle ❌', style: { background: '#dc3545' } }).showToast();
    });
});
/* ---- INICIO: EVENTO VISUALIZAR COMPRA ---- */





/* ---- INICIO: EVENTO RECEPCIONAR COMPRA ---- */

/* ---- FIN: EVENTO RECEPCIONAR COMPRA ---- */




});