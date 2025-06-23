'use strict';

document.addEventListener("DOMContentLoaded", function () {
  const dtModelos = document.querySelector(".datatables-category-list");
  const selects = $(".select2");
  // Inyectar permisos del usuario desde PHP
  const permisos = window.PERMISOS_USUARIO || [];

  // Helper para verificar si el usuario tiene un permiso específico en la vista actual
  const tienePermisoVista = (permisoEsperado) => {
    return permisos.some(p =>
      p.ruta === 'inventario/modelo' && p.permiso === permisoEsperado
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
  if (dtModelos) {

    const filtroCustomFeature = function(settings) {
    const container = document.createElement('div');
    container.classList.add('d-flex', 'align-items-center', 'gap-2', 'flex-wrap');

    container.innerHTML = `
    <div class="row gx-2 mx-3 mb-3 align-items-center">      
      <div class="col">
        <input type="text" id="modelo-filtro-valor" class="form-control form-control-sm" placeholder="Buscar por Nombre...">
      </div>
      <div class="col-auto">
        <button id="btnResetmodelo" class="btn btn-sm btn-secondary">
          <i class="bx bx-reset"></i> Limpiar
        </button>
      </div>
    </div>
    `;

    return container;
  };

    const botonesTop =[];

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
              tabla.column(4).search('^1$', true, false).draw();
            }
          },
          {
            text: 'Inactivos',
            className: 'dropdown-item',
            action: function () {
              tabla.column(4).search('^2$', true, false).draw();
            }
          },
          {
            text: 'Todos',
            className: 'dropdown-item',
            action: function () {
              tabla.column(4).search('').draw();
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
          { extend: "print", text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-printer me-1"></i>Print</span>`, className: "dropdown-item", exportOptions: { columns: [1, 2, 3] } },
          { extend: "csv",   text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-file me-1"></i>Csv</span>`,   className: "dropdown-item", exportOptions: { columns: [1, 2, 3] } },
          { extend: "excel", text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-export me-1"></i>Excel</span>`, className: "dropdown-item", exportOptions: { columns: [1, 2, 3] } },
          { extend: "pdf",   text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-pdf me-1"></i>Pdf</span>`,   className: "dropdown-item", exportOptions: { columns: [1, 2, 3] } },
          { extend: "copy",  text: `<i class="icon-base bx bx-copy me-1"></i>Copy`, className: "dropdown-item", exportOptions: { columns: [1, 2, 3] } }
        ]
      });
    }

    if (tienePermisoVista('registrar')) {
      botonesTop.push({
        text: '<i class="bx bx-plus"></i><span class="d-none d-sm-inline-block">Nuevo Modelo</span>',
        className: "add-new btn btn-primary",
        attr: {
          "data-bs-toggle": "modal",
          "data-bs-target": "#modalRegistrarModelo"
        }
      });
    }

  const tabla = new DataTable(dtModelos, {
    ajax: assetsPath + "modeloAjax/listar",
      columns: [
        { data: null },         // Numero oculto (control)
        { data: "nombre" },         // nombre rol
        { data: "anio" },         // nombre rol
        { data: "total_productos" },         // nombre rol
        { data: "activo" },         // estado (badge)
        { data: "id_modelo" }      // acciones con ID
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
          targets: 2,
          className: "text-center"
        },
        {
          targets: 3,
          className: "text-center"
        },
        {
          targets: 4, // ESTADO
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
          targets: 5, // ACCIONES
          className: "text-center",
          orderable: false,
          searchable: false,
          render: (id) => {
            let botones = '';

            // Ver
            /*if (tienePermisoVista('visualizar')) {
              botones += `
                <button class="btn btn-sm btn-icon btn-info" title="Ver" data-id="${id}">
                  <i class="bx bx-show-alt"></i>
                </button>`;
            }*/

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
      order: [[5, "asc"]],
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
        emptyTable: "No se encontraron modelos registrados",
        paginate: {
          next: '<i class="bx bx-chevron-right scaleX-n1-rtl icon-18px"></i>',
          previous: '<i class="bx bx-chevron-left scaleX-n1-rtl icon-18px"></i>'
        }
      },
      responsive: {
        details: {
          display: DataTable.Responsive.display.modal({
            header: row => 'Detalles de ' + row.data().nombre
          }),
          type: "column",
          renderer: DataTable.Responsive.renderer.listHiddenNodes()
        }
      }
  });

  /* Filtro de estado por defecto = Activo */
  tabla.column(4).search('^1$', true, false).draw();

  /* PARA FILTROS Y BUSQUEDAD*/
  $(document).on("input", "#modelo-filtro-valor", function () {
      const valor = $(this).val().trim();

      let colIndex = 1;

      //Limpiar filtro
      tabla.columns().search('');
      tabla.column(colIndex).search(valor).draw();
    });

    // Resetear Búsqueda
    $(document).on("click", "#btnResetmodelo", function () {
      $("#modelo-filtro-valor").val("");
      tabla.columns().search('');
      tabla.column(4).search('^1$', true, false).draw(); // Restaurar a activos
    });
 }




/* ---- INICIO: EVENTO REGISTRAR ---- */
$(function() {  
  let modeloDisponible = null;  // null = not checked

  // Verificar Modelo
  $('#verificarmodelo').on('click', function (e) {
    e.preventDefault();
    const modelo = $('#reg-nombre-modelo').val().trim();
    if (!modelo) return;

    fetch(`${assetsPath}modeloAjax/validar_modelo?nombre=${encodeURIComponent(modelo)}`)
      .then(res => res.json())
      .then(data => {
        modeloDisponible = !data.valid;
        Toastify({
          text: modeloDisponible ? "Modelo disponible ✅" : "Modelo ya registrado ❌",
          duration: 3000,
          style: { background: modeloDisponible ? "#28a745" : "#dc3545" }
        }).showToast();
      })
      .catch(err => {
        Toastify({
          text: "Error al verificar el modelo ⚠️",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
        console.error("Error en la verificación del modelo:", err);
      });
  });

  // Al abrir modal de registro: Cargar marcas activas y años
  $('#modalRegistrarModelo').on('show.bs.modal', function () {
    const selectMarca = $('#reg-id_marca');
    const selectAnio = $('#reg-anio-modelo');

    // Limpiar el select de marca
    selectMarca.empty().append('<option disabled selected> Cargando marcas... </option>');
    // Limpiar el select de años
    selectAnio.empty().append('<option disabled selected> Cargando años...</option>'); 

    // Cargar Marcas
    fetch(`${assetsPath}modeloAjax/listar_motos`)
      .then(res => res.json())
      .then(motos => {
        selectMarca.empty().append('<option value="" disabled selected>Seleccione una marca de moto</option>');
        motos.forEach(moto => {
          selectMarca.append(`<option value="${moto.id_moto}">${moto.nombre}</option>`);
        });
      })
      .catch(err => {
        console.error("Error al cargar marcas activas:", err);
        selectMarca.empty().append('<option disabled selected>Error al cargar</option>');
      });

    // Cargar Años desde el año actual hasta 1950
    const anioActual = new Date().getFullYear();
    const anioInicio = 1950;

    // Limpiar el select de año y agregar opción por defecto
    selectAnio.empty().append('<option value="" disabled selected>Seleccione un año</option>'); 
    
    // Agregar años desde el actual hacia atrás
    for (let anio = anioActual; anio >= anioInicio; anio--) {
      const option = document.createElement('option');
      option.value = anio;
      option.textContent = anio;
      selectAnio.append(option);
    }
  });

  // Enviar formulario
  $('#formRegistrarModelo').on('submit', function (e) {
    e.preventDefault();

    const form = this;
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    // Validar Modelo antes de enviar
    if (modeloDisponible === false) {
      Toastify({ text: 'Modelo ya registrado ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }

    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

    const formData = new FormData(form);

    fetch(`${assetsPath}ModeloAjax/registrar`, {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Toastify({ text: 'Modelo registrado correctamente ✅', style: { background: '#28a745' } }).showToast();
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalRegistrarModelo'));
          modal.hide();
          $('.datatables-category-list').DataTable().ajax.reload(null, false);
          form.reset();
          modeloDisponible = null;  // Restablecer el estado de la verificación
        } else {
          Toastify({ text: data.message || 'Error al registrar Modelo ❌', style: { background: '#dc3545' } }).showToast();
        }
      })
      .catch(() => {
        Toastify({ text: 'Error de red ❌', style: { background: '#dc3545' } }).showToast();
      })
      .finally(() => {
        submitBtn.prop('disabled', false).html('Registrar');
      });
  });
});
/* ---- FIN: EVENTO REGISTRAR ---- */


//FALTA ACOMODAR 
/* ---- INICIO: EVENTO EDITAR ---- */
$(function () {
  let modeloDisponible = null;
  let originalEditData = {};

  // Abre el modal y carga datos
  $(document).on("click", ".btn-warning[data-id]", function () {
    const idModelo = $(this).data("id");
    const selectMarca = $("#edit-id_moto");
    const selectAnio = $("#edit-anio-modelo");

    // Limpiar y mostrar carga
    selectMarca.empty().append('<option disabled selected> Cargando marcas... </option>');
    selectAnio.empty().append('<option disabled selected> Cargando años...</option>');

    // Cargar marcas
    fetch(`${assetsPath}modeloAjax/listar_motos`)
      .then(res => res.json())
      .then(motos => {
        selectMarca.empty().append('<option value="" disabled selected>Seleccione una marca</option>');
        motos.forEach(moto => {
          selectMarca.append(`<option value="${moto.id_moto}">${moto.nombre}</option>`);
        });
      })
      .catch(() => {
        selectMarca.empty().append('<option disabled selected>Error al cargar</option>');
      });

    // Cargar años
    const anioActual = new Date().getFullYear();
    selectAnio.empty().append('<option value="" disabled selected>Seleccione un año</option>');
    for (let anio = anioActual; anio >= 1950; anio--) {
      selectAnio.append(new Option(anio, anio));
    }

    // Obtener datos del modelo
    fetch(`${assetsPath}modeloAjax/obtener?id=${idModelo}`)
      .then(res => res.json())
      .then(data => {
        if (!data || !data.id_modelo) {
          alert("No se encontró el modelo.");
          return;
        }

        $("#edit-id_modelo").val(data.id_modelo);
        $("#edit-nombre-modelo").val(data.nombre);
        $("#edit-anio-modelo").val(data.anio);
        $("#edit-id_moto").val(data.id_moto);

        originalEditData = {
          id_modelo: data.id_modelo,
          nombre: data.nombre,
          anio: data.anio,
          id_moto: data.id_moto
        };
      })
      .catch(err => {
        console.error("Error al obtener datos del modelo:", err);
        alert("Error al cargar los datos del modelo.");
      });

    new bootstrap.Modal(document.getElementById("modalEditarModelo")).show();
  });

  // Verificación de modelo
  $("#editverificarmodelo").on("click", function (e) {
    e.preventDefault();
    const modelo = $("#edit-nombre-modelo").val().trim();
    const idModelo = $("#edit-id_modelo").val().trim();
    if (!modelo) return;

    fetch(`${assetsPath}modeloAjax/validar_modelo?nombre=${encodeURIComponent(modelo)}&id_modelo=${encodeURIComponent(idModelo)}`)
      .then(res => res.json())
      .then(data => {
        modeloDisponible = !data.valid;
        Toastify({
          text: modeloDisponible ? "Modelo disponible ✅" : "Modelo ya registrado ❌",
          duration: 3000,
          style: { background: modeloDisponible ? "#28a745" : "#dc3545" }
        }).showToast();
      });
  });

  // Detectar cambios
  function hasFormChanged() {
    return (
      $("#edit-nombre-modelo").val().trim() !== originalEditData.nombre ||
      $("#edit-anio-modelo").val().toString() !== originalEditData.anio.toString() ||
      $("#edit-id_moto").val().toString() !== originalEditData.id_moto.toString()
    );
  }

  // Enviar formulario
  $("#formEditarModelo").on("submit", function (e) {
    e.preventDefault();

    if (!this.checkValidity()) {
      this.reportValidity();
      return;
    }

    if (modeloDisponible === false) {
      Toastify({ text: "Modelo ya registrado ❌", style: { background: "#dc3545" } }).showToast();
      return;
    }

    if (!hasFormChanged()) {
      Toastify({ text: "No se realizaron cambios ⚠️", style: { background: "#ffc107" } }).showToast();
      return;
    }

    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

    const formData = new FormData(this);

    fetch(`${assetsPath}ModeloAjax/actualizar`, {
      method: "POST",
      body: formData,
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Toastify({ text: "Modelo actualizado correctamente ✅", style: { background: "#28a745" } }).showToast();
          bootstrap.Modal.getInstance(document.getElementById("modalEditarModelo")).hide();
          $(".datatables-category-list").DataTable().ajax.reload(null, false);
          this.reset();
          modeloDisponible = null;
        } else {
          Toastify({ text: data.message || "Error al actualizar Modelo ❌", style: { background: "#dc3545" } }).showToast();
        }
      })
      .catch(() => {
        Toastify({ text: "Error de red ❌", style: { background: "#dc3545" } }).showToast();
      })
      .finally(() => {
        submitBtn.prop("disabled", false).html("Actualizar");
      });
  });

  // Reset de estado al cerrar
  $("#modalEditarModelo").on("hidden.bs.modal", function () {
    modeloDisponible = null;
  });
});


/* ---- FIN: EVENTO EDITAR ---- */




/* ---- INICIO: EVENTO CAMBIAR ---- */
  let deleteModId = null;
  let deleteModName = '';
  let deleteModEstado = 0;

  // Primera Confirmación
  $(document).on('click', '.btn-danger[data-id]', function () {
    deleteModId = $(this).data('id');

    // Obtener nombre de usuario
    fetch(`${assetsPath}modeloAjax/obtener?id=${deleteModId}`)
      .then(res => res.json())
      .then(data => {
        if (!data || !data.id_modelo) {
          alert("No se encontró la información del usuario.");
          return;
        }
        deleteModName = data.nombre;
        deleteModEstado = data.activo;

        $('#modalConfirmDelete1Body').text(`¿Está seguro de cambiar el estado del usuario "${deleteModName}"?`);

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
    if (deleteModEstado === 1) {
      mensaje = "Al cambiar de estado el usuario no podrá acceder al sistema, ¿desea continuar?";
    } else if (deleteModEstado === 2) {
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

    fetch(`${assetsPath}modeloAjax/eliminar`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id_modelo: deleteModId })
    })
      .then(res => res.json())
      .then(resp => {
        if (resp.success) {
          Toastify({
            text: `Usuario "${deleteModName}" eliminado correctamente ✅`,
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