'use strict';

document.addEventListener("DOMContentLoaded", function () {
  const dtMotos = document.querySelector(".datatables-category-list");
  const selects = $(".select2");
  // Inyectar permisos del usuario desde PHP
  const permisos = window.PERMISOS_USUARIO || [];

  // Helper para verificar si el usuario tiene un permiso específico en la vista actual
  const tienePermisoVista = (permisoEsperado) => {
    return permisos.some(p =>
      p.ruta === 'inventario/moto' && p.permiso === permisoEsperado
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
  if (dtMotos) {

    const filtroCustomFeature = function(settings) {
    const container = document.createElement('div');
    container.classList.add('d-flex', 'align-items-center', 'gap-2', 'flex-wrap');

    container.innerHTML = `
    <div class="row gx-2 mx-3 mb-3 align-items-center">      
      <div class="col">
        <input type="text" id="moto-filtro-valor" class="form-control form-control-sm" placeholder="Buscar por Nombre...">
      </div>
      <div class="col-auto">
        <button id="btnResetmoto" class="btn btn-sm btn-secondary">
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
          { extend: "print", text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-printer me-1"></i>Print</span>`, className: "dropdown-item", exportOptions: { columns: [0,1,2,3,4] } },
          { extend: "csv",   text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-file me-1"></i>Csv</span>`,   className: "dropdown-item", exportOptions: { columns: [0,1,2,3,4] } },
          { extend: "excel", text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-export me-1"></i>Excel</span>`, className: "dropdown-item", exportOptions: { columns: [0,1,2,3,4] } },
          { extend: "pdf",   text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-pdf me-1"></i>Pdf</span>`,   className: "dropdown-item", exportOptions: { columns: [0,1,2,3,4] } },
          { extend: "copy",  text: `<i class="icon-base bx bx-copy me-1"></i>Copy`, className: "dropdown-item", exportOptions: { columns: [0,1,2,3,4] } }
        ]
      });
    }

    if (tienePermisoVista('registrar')) {
      botonesTop.push({
        text: '<i class="bx bx-plus"></i><span class="d-none d-sm-inline-block">Nueva Marca de Moto</span>',
        className: "add-new btn btn-primary",
        attr: {
          "data-bs-toggle": "modal",
          "data-bs-target": "#modalRegistrarMoto"
        }
      });
    }

  const tabla = new DataTable(dtMotos, {
    ajax: assetsPath + "motoAjax/listar",
      columns: [
        { data: null },         // Numero oculto (control)
        { data: "nombre_marca" },         // nombre rol        
        { data: "total_modelos" },         // nombre rol
        { data: "modelos_activos" },         // nombre rol
        { data: "activo" },         // estado (badge)
        { data: "id_moto" }      // acciones con ID
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
          className: "text-start"
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
        emptyTable: "No se encontraron marcas registrados",
        paginate: {
          next: '<i class="bx bx-chevron-right scaleX-n1-rtl icon-18px"></i>',
          previous: '<i class="bx bx-chevron-left scaleX-n1-rtl icon-18px"></i>'
        }
      },
      responsive: {
        details: {
          display: DataTable.Responsive.display.modal({
            header: row => 'Detalles de ' + row.data().nombre_marca
          }),
          type: "column",
          renderer: DataTable.Responsive.renderer.listHiddenNodes()
        }
      }
  });

  /* Filtro de estado por defecto = Activo */
  tabla.column(4).search('^1$', true, false).draw();

  /* PARA FILTROS Y BUSQUEDAD*/
  $(document).on("input", "#moto-filtro-valor", function () {
      const valor = $(this).val().trim();

      let colIndex = 1;

      //Limpiar filtro
      tabla.columns().search('');
      tabla.column(colIndex).search(valor).draw();
    });

    // Resetear Búsqueda
    $(document).on("click", "#btnResetmoto", function () {
      $("#moto-filtro-valor").val("");
      tabla.columns().search('');
      tabla.column(4).search('^1$', true, false).draw(); // Restaurar a activos
    });
 }


 /* ---- INICIO: EVENTO REGISTRAR ---- */
$(function() {  
  let motoDisponible = null;  // null = not checked

  // Verificar marca
  $('#verificarmarca').on('click', function (e) {
    e.preventDefault();
    const moto = $('#reg-nombre-marca').val().trim();
   if (!moto) return;

    fetch(`${assetsPath}motoAjax/validar_moto?nombre=${encodeURIComponent(moto)}`)
      .then(res => res.json())
      .then(data => {
        motoDisponible = !data.valid;
        Toastify({
          text: motoDisponible ? "Marca de moto disponible ✅" : "Marca de moto ya registrada ❌",
          duration: 3000,
          style: { background: motoDisponible ? "#28a745" : "#dc3545" }
        }).showToast();
      });
  });
  
  // Enviar formulario
  $('#formRegistrarMarca').on('submit', function (e) {
    e.preventDefault();

    const form = this;
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    // Validar marca y correo en vivo antes de enviar
    if (motoDisponible === false) {
      Toastify({ text: 'Marca de moto ya registrada ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }

    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

    const formData = new FormData(form);

    fetch(`${assetsPath}motoAjax/registrar`, {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Toastify({ text: 'Marca registrada correctamente ✅', style: { background: '#28a745' } }).showToast();
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalRegistrarMoto'));
          modal.hide();
          $('.datatables-category-list').DataTable().ajax.reload(null, false);
          form.reset();
          motoDisponible = null;          
        } else {
          Toastify({ text: data.message || 'Error al registrar Marca ❌', style: { background: '#dc3545' } }).showToast();
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
/* ---- FIN: EVENTO REEGISTRAR ---- */


/* ---- INICIO: EVENTO CAMBIAR ---- */
  let deleteMarId = null;
  let deleteMarName = '';
  let deleteMarEstado = 0;

  // Primera Confirmación
  $(document).on('click', '.btn-danger[data-id]', function () {
    deleteMarId = $(this).data('id');

    // Obtener nombre de usuario
    fetch(`${assetsPath}motoAjax/obtener?id=${deleteMarId}`)
      .then(res => res.json())
      .then(data => {
        if (!data || !data.id_moto) {
          alert("No se encontró la información de la marca.");
          return;
        }
        deleteMarName = data.nombre_marca;
        deleteMarEstado = data.activo;

        $('#modalConfirmDelete1Body').text(`¿Está seguro de cambiar el estado de la marca "${deleteMarName}"?`);

        // Mostrar modal 1
        const modal1 = new bootstrap.Modal(document.getElementById('modalConfirmDelete1'));
        modal1.show();
      })
      .catch(err => {
        console.error("Error al obtener marca para cambiar:", err);
        alert("Error al obtener datos de la marca.");
      });
  });

  // Confirmación modal 1 - pasa a modal 2
  $('#btnConfirmDelete1').on('click', function () {
    const modal1El = document.getElementById('modalConfirmDelete1');
    const modal1 = bootstrap.Modal.getInstance(modal1El);
    modal1.hide();

    let mensaje = "";
    if (deleteMarEstado === 1) {
      mensaje = "Al cambiar de estado la marca y sus modelos que engloban se desactivarán en el sistema, ¿desea continuar?";
    } else if (deleteMarEstado === 2) {
      mensaje = "Al cambiar de estado la marca con sus modelos asociados estarán vigentes en el sistema nuevamente, ¿desea continuar?";
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

    fetch(`${assetsPath}motoAjax/eliminar`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id_moto: deleteMarId })
    })
      .then(res => res.json())
      .then(resp => {
        if (resp.success) {
          Toastify({
            text: `Marca de moto "${deleteMarName}" desactivado correctamente ✅`,
            duration: 3000,
            style: { background: "#28a745" }
          }).showToast();

          // Recargar tabla
          $('.datatables-category-list').DataTable().ajax.reload(null, false);

          modal2.hide();
        } else {
          Toastify({
            text: `Error al desactivar marca ⚠️`,
            duration: 3000,
            style: { background: "#dc3545" }
          }).showToast();
        }
      })
      .catch(err => {
        console.error("Error al desactivar marca:", err);
        Toastify({
          text: "Error al eliminar marca ⚠️",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      });
  });
/* ---- FIN: EVENTO CAMBIAR ---- */


/* ---- INICIO: EVENTO EDITAR  ---- */
  // Verificar MARCA
  $('#modalEditarMoto').on('click', '#editverificarmarca', function (e) {
    e.preventDefault();
    const moto = $('#edit-nombre-marca').val().trim();
    const idmoto = $('#edit-id_moto').val().trim();
    
    if (!moto) {
      Toastify({
        text: "Debe ingresar un nombre de marca",
        duration: 3000,
        style: { background: '#ffc107', color: '#000' }
      }).showToast();
      return;
    }

    fetch(`${assetsPath}motoAjax/validar_moto?nombre=${encodeURIComponent(moto)}&id_moto=${encodeURIComponent(idmoto)}`)
      .then(res => res.json())
      .then(data => {
        const motoDisponible = !data.valid;
        Toastify({
          text: motoDisponible ? "Marca de moto disponible ✅" : "Marca ya registrada ❌",
          duration: 3000,
          style: { background: motoDisponible ? "#28a745" : "#dc3545" }
        }).showToast();
      })
      .catch(() => {
        Toastify({
          text: "Error al verificar la marca",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      });
  });

  let originalEditmotoData = {};

  // Evento para abrir modal y cargar datos
  $(document).on('click', '.btn-warning[data-id]', function () {
    const motoId = $(this).data('id');

    fetch(`${assetsPath}motoAjax/obtener?id=${motoId}`)
      .then(res => res.json())
      .then(data => {
        if (!data || !data.id_moto) {
          alert("No se encontró la marca.");
          return;
        }

        $('#edit-id_moto').val(data.id_moto);
        $('#edit-nombre-marca').val(data.nombre_marca);

        originalEditmotoData = {
          nombre: data.nombre_marca
        };

        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('modalEditarMoto'));
        modal.show();
      })
      .catch(err => {
        console.error("Error al obtener marca:", err);
        alert("Error al obtener datos de la marca.");
      });
  });

  // Función para detectar cambios en el formulario
  function hasmarcaFormChanged() {
    return $('#edit-nombre-marca').val().trim() !== (originalEditmotoData.nombre || '').trim();
  }

  // Enviar actualización
  let isSubmittingmoto = false;
  $('#formEditarMarca').on('submit', function (e) {
    e.preventDefault();

    // Validar formulario
    if (!this.checkValidity()) {
      this.reportValidity();
      return;
    }

    // Verificar si hubo cambios
    if (!hasmarcaFormChanged()) {
      Toastify({
        text: "No se realizaron cambios ⚠️",
        duration: 3000,
        style: { background: "#ffc107", color: "#000" }
      }).showToast();
      return;
    }

    isSubmittingmoto = true;

    const formData = new FormData(this);

    fetch(`${assetsPath}motoAjax/actualizar`, {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarMoto'));
          modal.hide();
          $('.datatables-category-list').DataTable().ajax.reload(null, false);
          Toastify({
            text: "Marca actualizada correctamente ✅",
            duration: 3000,
            style: { background: "#28a745" }
          }).showToast();
        } else {
          Toastify({
            text: "Error al actualizar marca ⚠️",
            duration: 3000,
            style: { background: "#dc3545" }
          }).showToast();
        }
      })
      .catch(err => {
        console.error("Error al actualizar marca:", err);
        Toastify({
          text: "Error al actualizar marca ⚠️",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      })
      .finally(() => {
        isSubmittingmoto = false;
      });
  });

  // Confirmar antes de cerrar si hay cambios
  $('#modalEditarMoto').on('hide.bs.modal', function (e) {
    if (!isSubmittingmoto && hasmarcaFormChanged()) {
      e.preventDefault(); // detiene cierre
      const salir = confirm("Hay cambios sin guardar. ¿Seguro que deseas salir?");
      if (salir) {
        $('#modalEditarMoto').off('hide.bs.modal');
        $('#modalEditarMoto').modal('hide');
      }
    }
  });
/* ---- FIN: EVENTO EDITAR  ---- */




});