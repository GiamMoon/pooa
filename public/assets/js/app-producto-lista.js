'use strict';

document.addEventListener("DOMContentLoaded", function () {
  const dtProductos = document.querySelector(".datatables-category-list");
  const selects = $(".select2");
  // Inyectar permisos del usuario desde PHP
  const permisos = window.PERMISOS_USUARIO || [];

  // Helper para verificar si el usuario tiene un permiso específico en la vista actual
  const tienePermisoVista = (permisoEsperado) => {
    return permisos.some(p =>
      p.ruta === 'inventario/producto' && p.permiso === permisoEsperado
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
  if (dtProductos) {

  const filtroCustomFeature = function(settings) {
    const container = document.createElement('div');
    container.classList.add('d-flex', 'align-items-center', 'gap-2', 'flex-wrap');

    container.innerHTML = `
    <div class="row gx-2 mx-3 mb-3 align-items-center">
      <div class="col-auto">
        <select id="producto-filtro-campo" class="form-select form-select-sm">
          <option value="nombre_producto">Producto</option>
          <option value="codigo_producto">Código</option>
          <option value="nombre_categoria">Categoría</option>
        </select>
      </div>
      <div class="col">
        <input type="text" id="producto-filtro-valor" class="form-control form-control-sm" placeholder="Buscar por filtro seleccionado...">
      </div>
      <div class="col-auto">
        <button id="btnResetProducto" class="btn btn-sm btn-secondary">
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
            text: 'Activos',
            className: 'dropdown-item',
            action: function () {
              tabla.column(7).search('^1$', true, false).draw();
            }
          },
          {
            text: 'Inactivos',
            className: 'dropdown-item',
            action: function () {
              tabla.column(7).search('^2$', true, false).draw();
            }
          },
          {
            text: 'Todos',
            className: 'dropdown-item',
            action: function () {
              tabla.column(7).search('').draw();
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
            exportOptions: { columns: [0,1,2,3,4,5,6,7,8] }
          },
          {
            extend: "csv",
            text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-file me-1"></i>Csv</span>`,
            className: "dropdown-item",
            exportOptions: { columns: [0,1,2,3,4,5,6,7,8] }
          },
          {
            extend: "excel",
            text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-export me-1"></i>Excel</span>`,
            className: "dropdown-item",
            exportOptions: { columns: [0,1,2,3,4,5,6,7,8] }
          },
          {
            extend: "pdf",
            text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-pdf me-1"></i>Pdf</span>`,
            className: "dropdown-item",
            exportOptions: { columns: [0,1,2,3,4,5,6,7,8] }
          },
          {
            extend: "copy",
            text: `<i class="icon-base bx bx-copy me-1"></i>Copy`,
            className: "dropdown-item",
            exportOptions: { columns: [0,1,2,3,4,5,6,7,8] }
          }
        ]
      });
    }

    if (tienePermisoVista('registrar')) {
      botonesTop.push({
        text: '<i class="bx bx-plus"></i><span class="d-none d-sm-inline-block">Nuevo Producto</span>',
        className: "add-new btn btn-primary",
        attr: {
          "data-bs-toggle": "modal",//offcanvas
          "data-bs-target": "#modalRegistrarProducto"//#offcanvasEcommerceCategoryList
        }
      });
    }

    const tabla = new DataTable(dtProductos, {
      ajax: assetsPath + "productoAjax/listar",
      columns: [
        { data: null },         // Número oculto (control)
        { data: "url_imagen" },         // Número oculto (control)
        { data: "nombre_producto" },         // Número oculto (control)
        { data: "nombre_marca" },         // Número oculto (control)        
        { data: "nombre_categoria" },         
        { data: "modelos_compatibles" },         
        { data: "precio_venta" },         
        { data: "activo" },             
        { data: "id_producto" }          
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
          render: (data, type, row) => {
            if (type === 'display') {
              const url = row.url_imagen ? `../../uploads/products/${row.url_imagen}` : '../img/illustrations/default.png';
              return `<img src="${url}" alt="Img" style="width: 60px; height: auto; border-radius: 6px;">`;
            }
            return data;
          }
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
          className: "text-start",          
        },
        {
          targets: 6,
          className: "text-center",          
        },
        {
          targets: 7, // ESTADO
          className: "text-center",
          render: (data, type) => {
            if (type === 'display') {
              return data == 1
                ? '<span class="badge bg-label-success">Activo</span>'
                : '<span class="badge bg-label-danger">Inactivo</span>';
            }
            return data; // En type !== display (e.g., filter), devuelve valor original
          }
        },
        {
          targets: 8, // ACCIONES
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
                <button class="btn btn-sm btn-icon btn-warning" title="Editar" data-id="${id}">
                  <i class="bx bx-edit-alt"></i>
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
      order: [[8, "asc"]],
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
        emptyTable: "No se encontraron productos registrados",
        paginate: {
          next: '<i class="bx bx-chevron-right scaleX-n1-rtl icon-18px"></i>',
          previous: '<i class="bx bx-chevron-left scaleX-n1-rtl icon-18px"></i>'
        }
      },
      responsive: {
        details: {
          display: DataTable.Responsive.display.modal({
            header: row => 'Detalles de ' + row.data().nombre_producto
          }),
          type: "column",
          renderer: DataTable.Responsive.renderer.listHiddenNodes()
        }
      }
    });

    /* Filtro de estado por defecto = Activo */
    tabla.column(7).search('^1$', true, false).draw();
    
    /* PARA FILTROS Y BUSQUEDAD*/
    $(document).on("input", "#producto-filtro-valor", function () {
      const campo = $("#producto-filtro-campo").val();
      const valor = $(this).val().trim();

      let colIndex = -1;
      switch (campo) {
        case "nombre_producto":
          colIndex = 1;
          break;
        case "codigo_producto":
          colIndex = 2;
          break;
        case "nombre_categoria":
          colIndex = 3;
          break;
      }

      if (colIndex >= 0) {
        tabla.columns().search('');
        tabla.column(colIndex).search(valor).draw();
      }
    });

    // Resetear filtro personalizado
    $(document).on("click", "#btnResetProducto", function () {
      $("#producto-filtro-valor").val("");
      tabla.columns().search('');
      tabla.column(7).search('^1$', true, false).draw(); // Restaurar a activos
    });

  }


/* ---- INICIO: EVENTO REGISTRAR ---- */
  $(function () {

    let isSubmitting = false;
    const selectMarcas = document.getElementById("reg-marcas-moto");
    const contenedorModelos = document.getElementById("contenedor-marcas-modelos");
    let modelosPorMarca = {}; // { id_moto: [{id_modelo, nombre, anio, nombre_marca}] }

    // Al abrir modal, cargar listas y marcas/modelos
    $('#modalRegistrarProducto').on('show.bs.modal', function () {
      const selectCat = $('#reg-categoria');
      const selectMar = $('#reg-marca');
      const selectUni = $('#reg-tipo-unidad');

      selectCat.empty().append('<option disabled selected> Cargando... </option>');
      selectMar.empty().append('<option disabled selected> Cargando... </option>');
      selectUni.empty().append('<option disabled selected> Cargando... </option>');

      fetch(`${assetsPath}categoriaAjax/listar_categorias`).then(res => res.json()).then(categorias => {
        selectCat.empty().append('<option value="" disabled selected>Seleccione una Categoría</option>');
        categorias.forEach(c => selectCat.append(`<option value="${c.id_categoria}">${c.nombre_categoria}</option>`));
      });

      fetch(`${assetsPath}marcaAjax/listar_marcas`).then(res => res.json()).then(marcas => {
        selectMar.empty().append('<option value="" disabled selected>Seleccione una Marca</option>');
        marcas.forEach(m => selectMar.append(`<option value="${m.id_marca}">${m.nombre_marca}</option>`));
      });

      fetch(`${assetsPath}unidadAjax/listar_unidades`).then(res => res.json()).then(unidades => {
        selectUni.empty().append('<option value="" disabled selected>Seleccione una Unidad</option>');
        unidades.forEach(u => selectUni.append(`<option value="${u.id_tipo_unidad}">${u.nombre_unidad}</option>`));
      });

      // cargar marcas y modelos
      fetch(`${assetsPath}productoAjax/listar_motos_modelos`)
      .then(res => res.json())
      .then(data => {
        modelosPorMarca = data.reduce((acc, marca) => {
          acc[marca.id_moto] = marca.modelos.map(m => ({ ...m, nombre_marca: marca.nombre_marca }));
          return acc;
        }, {});

        selectMarcas.innerHTML = '';
        Object.entries(modelosPorMarca).forEach(([id_moto, modelos]) => {
          const option = document.createElement("option");
          option.value = id_moto;
          option.textContent = modelos[0].nombre_marca;
          selectMarcas.appendChild(option);
        });

        $(selectMarcas).val(null).trigger('change').select2({
          placeholder: "Seleccione una o más marcas",
          dropdownParent: $('#modalRegistrarProducto')
        });

        // ✅ Reasignar evento después de aplicar Select2
        $(selectMarcas).off('change').on('change', function () {
          const seleccionadas = $(this).val();
          contenedorModelos.innerHTML = "";
          if (!seleccionadas || seleccionadas.length === 0) return;

          seleccionadas.forEach(id => {
            const modelos = modelosPorMarca[id] || [];
            const bloque = document.createElement("div");
            bloque.className = "mb-3 border rounded p-3 bg-white shadow-sm";
            bloque.innerHTML = `
              <h6 class="mb-2">Modelos de <strong>${$(selectMarcas).find(`option[value='${id}']`).text()}</strong></h6>
              <div class="row">
                ${modelos.map(m => `
                  <div class="col-md-4">
                    <div class="form-check">
                      <input class="form-check-input modelo-checkbox" type="checkbox" value="${m.id_modelo}" id="modelo-${m.id_modelo}">
                      <label class="form-check-label" for="modelo-${m.id_modelo}">${m.nombre} (${m.anio})</label>
                    </div>
                  </div>`).join('')}
              </div>`;
            contenedorModelos.appendChild(bloque);
          });
        });

      });
    });  

    function obtenerModelosSeleccionados() {
      return Array.from(document.querySelectorAll(".modelo-checkbox:checked"))
        .map(cb => ({ id_modelo: parseInt(cb.value) }));
    }

    $('#formRegistrarProducto').on('submit', function (e) {
      e.preventDefault();
      const form = this;
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const formData = new FormData(form);
      const modelos = obtenerModelosSeleccionados();
      formData.append("json_modelos", JSON.stringify(modelos));

      const submitBtn = $(form).find('button[type="submit"]');
      submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Registrando...');

      fetch(`${assetsPath}productoAjax/registrar`, {
        method: 'POST',
        body: formData
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            Toastify({
              text: 'Producto registrado correctamente ✅',
              duration: 3000,
              style: { background: '#28a745' }
            }).showToast();
            bootstrap.Modal.getInstance(document.getElementById('modalRegistrarProducto')).hide();
            $('.datatables-category-list').DataTable().ajax.reload(null, false);
            form.reset();
            $(selectMarcas).val(null).trigger("change");
            contenedorModelos.innerHTML = "";
          } else {
            Toastify({
              text: data.message || 'Error al registrar producto ⚠️',
              duration: 3000,
              style: { background: '#dc3545' }
            }).showToast();
          }
        })
        .catch(err => {
          console.error("Error en registro:", err);
          Toastify({
            text: 'Error de red ❌',
            duration: 3000,
            style: { background: '#dc3545' }
          }).showToast();
        })
        .finally(() => {
          submitBtn.prop('disabled', false).html('Registrar Producto');
          isSubmitting = false;
        });
    });
  });
/* ---- FIN: EVENTO REGISTRAR ---- */



/* ---- INICIO: EVENTO CAMBIAR ---- */
  let deleteUserId = null;
  let deleteUserName = '';
  let deleteUserEstado = 0;

  // Primera Confirmación
  $(document).on('click', '.btn-danger[data-id]', function () {
    deleteUserId = $(this).data('id');

    // Obtener nombre de usuario
    fetch(`${assetsPath}usuarioAjax/obtener?id=${deleteUserId}`)
      .then(res => res.json())
      .then(data => {
        if (!data || !data.id_usuario) {
          alert("No se encontró la información del usuario.");
          return;
        }
        deleteUserName = data.nombre_usuario;
        deleteUserEstado = data.activo;

        $('#modalConfirmDelete1Body').text(`¿Está seguro de cambiar el estado del usuario "${deleteUserName}"?`);

        // Mostrar modal 1
        const modal1 = new bootstrap.Modal(document.getElementById('modalConfirmDelete1'));
        modal1.show();
      })
      .catch(err => {
        console.error("Error al obtener usuario para cambiar:", err);
        alert("Error al obtener datos del usuario.");
      });
  });

  // Confirmación modal 1 - pasa a modal 2
  $('#btnConfirmDelete1').on('click', function () {
    const modal1El = document.getElementById('modalConfirmDelete1');
    const modal1 = bootstrap.Modal.getInstance(modal1El);
    modal1.hide();

    let mensaje = "";
    if (deleteUserEstado === 1) {
      mensaje = "Al cambiar de estado el usuario no podrá acceder al sistema, ¿desea continuar?";
    } else if (deleteUserEstado === 2) {
      mensaje = "Al cambiar de estado el usuario podrá acceder al sistema nuevamente, ¿desea continuar?";
    } else {
      mensaje = "¿Desea continuar con el cambio de estado?";
    }
  $('#modalConfirmDelete2Body').text(mensaje);

    // Mostrar modal 2
    const modal2 = new bootstrap.Modal(document.getElementById('modalConfirmDelete2'));
    modal2.show();
  });

  // Confirmación modal 2 - proceder a eliminar usuario
  $('#btnConfirmDelete2').on('click', function () {
    const modal2El = document.getElementById('modalConfirmDelete2');
    const modal2 = bootstrap.Modal.getInstance(modal2El);

    fetch(`${assetsPath}usuarioAjax/eliminar`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id_usuario: deleteUserId })
    })
      .then(res => res.json())
      .then(resp => {
        if (resp.success) {
          Toastify({
            text: `Usuario "${deleteUserName}" eliminado correctamente ✅`,
            duration: 3000,
            style: { background: "#28a745" }
          }).showToast();

          // Recargar tabla
          $('.datatables-category-list').DataTable().ajax.reload(null, false);

          modal2.hide();
        } else {
          Toastify({
            text: `Error al eliminar usuario ⚠️`,
            duration: 3000,
            style: { background: "#dc3545" }
          }).showToast();
        }
      })
      .catch(err => {
        console.error("Error al eliminar usuario:", err);
        Toastify({
          text: "Error al eliminar usuario ⚠️",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      });
  });
  /* ---- FIN: EVENTO CAMBIAR ---- */

});