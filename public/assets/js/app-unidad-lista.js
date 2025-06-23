'use strict';

document.addEventListener("DOMContentLoaded", function () {
  const dtUnidades = document.querySelector(".datatables-category-list");
  const selects = $(".select2");
  // Inyectar permisos del usuario desde PHP
  const permisos = window.PERMISOS_USUARIO || [];

  // Helper para verificar si el usuario tiene un permiso específico en la vista actual
  const tienePermisoVista = (permisoEsperado) => {
    return permisos.some(p =>
      p.ruta === 'inventario/unidad' && p.permiso === permisoEsperado
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
  if (dtUnidades) {

    const filtroCustomFeature = function(settings) {
    const container = document.createElement('div');
    container.classList.add('d-flex', 'align-items-center', 'gap-2', 'flex-wrap');

    container.innerHTML = `
    <div class="row gx-2 mx-3 mb-3 align-items-center">      
      <div class="col">
        <input type="text" id="unidad-filtro-valor" class="form-control form-control-sm" placeholder="Buscar por Nombre...">
      </div>
      <div class="col-auto">
        <button id="btnResetunidad" class="btn btn-sm btn-secondary">
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
        text: '<i class="bx bx-plus"></i><span class="d-none d-sm-inline-block">Nueva Unidad</span>',
        className: "add-new btn btn-primary",
        attr: {
          "data-bs-toggle": "modal",
          "data-bs-target": "#modalRegistrarUnidad"
        }
      });
    }

  const tabla = new DataTable(dtUnidades, {
    ajax: assetsPath + "unidadAjax/listar",
      columns: [
        { data: null },         // Numero oculto (control)
        { data: "nombre" },         // nombre rol        
        { data: "abreviatura" },         // abre
        { data: "descripcion" },         // nomb
        { data: "activo" },         // estado (badge)
        { data: "id_tipo_unidad" }      // acciones con ID
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
          className: "text-start",
          orderable: true
        },
        {
          targets: 2,
          className: "text-center",
          orderable: true
        },
        {
          targets: 3,
          className: "text-start",
          orderable: false
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
        emptyTable: "No se encontraron tipos de unidades registradas",
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
  $(document).on("input", "#unidad-filtro-valor", function () {
      const valor = $(this).val().trim();

      let colIndex = 1;

      //Limpiar filtro
      tabla.columns().search('');
      tabla.column(colIndex).search(valor).draw();
    });

    // Resetear Búsqueda
    $(document).on("click", "#btnResetunidad", function () {
      $("#unidad-filtro-valor").val("");
      tabla.columns().search('');
      tabla.column(4).search('^1$', true, false).draw(); // Restaurar a activos
    });
 }


 
/* ---- INICIO: EVENTO REGISTRAR ---- */
$(function () {
  let unidadDisponible = null;

  $('#reg-nombre-unidad').on('input', function () {
    unidadDisponible = null; // Invalida cache cuando cambia
  });

  $('#generarAbreviatura').on('click', function () {
    const nombre = $('#reg-nombre-unidad').val().trim();
    /*if (nombre.length < 3) {
      Toastify({ text: 'El nombre debe tener al menos 3 letras', style: { background: '#ffc107' } }).showToast();
      return;
    }*/
    const abreviatura = (nombre.substring(0, 2) + nombre.slice(-1));
    $('#reg-abreviatura-unidad').val(abreviatura);
  });

  $('#verificarunidad').on('click', function (e) {
    e.preventDefault();
    verificarUnidad();
  });  

  function verificarUnidad() {
    const unidad = $('#reg-nombre-unidad').val().trim();
    if (!unidad) return;

    return fetch(`${assetsPath}unidadAjax/validad_unidad?nombre=${encodeURIComponent(unidad)}`)
      .then(res => res.json())
      .then(data => {
        unidadDisponible = !data.valid;
        Toastify({
          text: unidadDisponible ? "Unidad disponible ✅" : "Unidad ya registrada ❌",
          duration: 3000,
          style: { background: unidadDisponible ? "#28a745" : "#dc3545" }
        }).showToast();
        return unidadDisponible;
      })
      .catch(() => {
        unidadDisponible = false;
        Toastify({ text: 'Error validando unidad ❌', style: { background: '#dc3545' } }).showToast();
        return false;
      });
  }

  $('#formRegistrarUnidad').on('submit', async function (e) {
    e.preventDefault();
    const form = this;

    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    // Validar si ya se verificó. Si no, hacer verificación ahora
    if (unidadDisponible === null) {
      const valid = await verificarUnidad();
      if (!valid) return;
    }

    if (unidadDisponible === false) {
      Toastify({ text: 'Unidad ya registrada ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }

    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

    const formData = new FormData(form);

    fetch(`${assetsPath}unidadAjax/registrar`, {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Toastify({ text: 'Unidad registrada correctamente ✅', style: { background: '#28a745' } }).showToast();
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalRegistrarUnidad'));
          modal.hide();
          $('.datatables-category-list').DataTable().ajax.reload(null, false);
          form.reset();
          unidadDisponible = null;
        } else {
          Toastify({ text: data.message || 'Error al registrar unidad ❌', style: { background: '#dc3545' } }).showToast();
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


/* ---- INICIO: EVENTO EDITAR ---- */
let originalUnidadData = {};
let isSubmittingUnidad = false;
let unidadEditDisponible = null;

// Abrir modal y cargar datos
$(document).on('click', '.btn-warning[data-id]', function () {
  const id = $(this).data('id');

  fetch(`${assetsPath}unidadAjax/obtener?id=${id}`)
    .then(res => res.json())
    .then(data => {
      if (!data || !data.id_tipo_unidad) {
        alert("No se encontró la unidad.");
        return;
      }

      $('#edit-id_tipo_unidad').val(data.id_tipo_unidad);
      $('#edit-nombre-unidad').val(data.nombre);
      $('#edit-abreviatura-unidad').val(data.abreviatura);
      $('#edit-codigo-sunat-unidad').val(data.codigo_sunat);
      $('#edit-descripcion-unidad').val(data.descripcion);

      originalUnidadData = { ...data };
      unidadEditDisponible = null;
      new bootstrap.Modal('#modalEditarUnidad').show();
    })
    .catch(err => {
      console.error("Error al obtener unidad:", err);
      alert("Error al cargar datos.");
    });
});

// Detectar cambios en nombre para resetear unidadDisponible
$('#edit-nombre-unidad').on('input', () => {
  unidadEditDisponible = null;
});

// Generar abreviatura en edición
$('#edit-generarAbreviatura').on('click', function () {
  const nombre = $('#edit-nombre-unidad').val().trim();
  const abreviatura = (nombre.substring(0, 2) + nombre.slice(-1)).toUpperCase();
  $('#edit-abreviatura-unidad').val(abreviatura);
});

// Validar si existe unidad
function verificarUnidadExistenteEdit() {
  const nombre = $('#edit-nombre-unidad').val().trim();
  const id = $('#edit-id_tipo_unidad').val().trim();

  if (!nombre) return false;

  return fetch(`${assetsPath}unidadAjax/validad_unidad?nombre=${encodeURIComponent(nombre)}&id_tipo_unidad=${encodeURIComponent(id)}`)
    .then(res => res.json())
    .then(data => {
      unidadEditDisponible = !data.valid;
      Toastify({
        text: unidadEditDisponible ? "Unidad disponible ✅" : "Unidad ya registrada ❌",
        duration: 3000,
        style: { background: unidadEditDisponible ? "#28a745" : "#dc3545" }
      }).showToast();
      return unidadEditDisponible;
    })
    .catch(() => {
      unidadEditDisponible = false;
      Toastify({ text: 'Error validando unidad ❌', style: { background: '#dc3545' } }).showToast();
      return false;
    });
}
// Botón verificar
$('#edit-verificarunidad').on('click', function (e) {
  e.preventDefault();
  verificarUnidadExistenteEdit();
});

// Detectar cambios
function hasUnidadChanged() {
  return (
    $('#edit-nombre-unidad').val().trim() !== originalUnidadData.nombre ||
    $('#edit-abreviatura-unidad').val().trim() !== originalUnidadData.abreviatura ||
    $('#edit-codigo-sunat-unidad').val().trim() !== originalUnidadData.codigo_sunat ||
    $('#edit-descripcion-unidad').val().trim() !== originalUnidadData.descripcion
  );
}

// Enviar formulario con validación
$('#formEditarUnidad').on('submit', async function (e) {
  e.preventDefault();

  if (!this.checkValidity()) {
    this.reportValidity();
    return;
  }

  if (!hasUnidadChanged()) {
    Toastify({
      text: "No se realizaron cambios ⚠️",
      style: { background: "#ffc107", color: "#000" }
    }).showToast();
    return;
  }
  
  if (unidadEditDisponible === null) {
    const valid = await verificarUnidadExistenteEdit();
    if (!valid) return;
  }
  if (!unidadEditDisponible) {
    Toastify({ text: 'Unidad ya registrada ❌', style: { background: '#dc3545' } }).showToast();
    return;
  }

  const codigoSunatNuevo = $('#edit-codigo-sunat-unidad').val().trim();
  const codigoSunatOriginal = originalUnidadData.codigo_sunat;

  if (codigoSunatNuevo !== codigoSunatOriginal) {
    $('#modalAdvertenciaSunat').modal('show');

    $('#sunatCancelar').off().on('click', function () {
      $('#edit-codigo-sunat-unidad').val(codigoSunatOriginal);
      $('#modalAdvertenciaSunat').modal('hide');
    });

    $('#sunatConfirmar').off().on('click', function () {
      $('#modalAdvertenciaSunat').modal('hide');
      enviarEdicionUnidad();
    });

    return;
  }

  enviarEdicionUnidad();
});

// Función de envío
function enviarEdicionUnidad() {
  isSubmittingUnidad = true;
  const formData = new FormData(document.getElementById('formEditarUnidad'));

  fetch(`${assetsPath}unidadAjax/actualizar`, {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        Toastify({ text: 'Unidad actualizada correctamente ✅', style: { background: '#28a745' } }).showToast();
        bootstrap.Modal.getInstance(document.getElementById('modalEditarUnidad')).hide();
        $('.datatables-category-list').DataTable().ajax.reload(null, false);
      } else {
        Toastify({ text: 'Error al actualizar unidad ❌', style: { background: '#dc3545' } }).showToast();
      }
    })
    .catch(() => {
      Toastify({ text: 'Error de red ❌', style: { background: '#dc3545' } }).showToast();
    })
    .finally(() => {
      isSubmittingUnidad = false;
    });
}

// Advertencia al cerrar sin guardar
$('#modalEditarUnidad').on('hide.bs.modal', function (e) {
  if (!isSubmittingUnidad && hasUnidadChanged()) {
    e.preventDefault();
    const salir = confirm("Hay cambios sin guardar. ¿Seguro que deseas salir?");
    if (salir) {
      $('#modalEditarUnidad').off('hide.bs.modal');
      $('#modalEditarUnidad').modal('hide');
    }
  }
});
/* ---- FIN: EVENTO EDITAR ---- */



/* ---- INICIO: EVENTO CAMBIAR ---- */
let deleteUnId = null;
let deleteUnName = '';
let deleteUnEstado = 0;

// Primera Confirmación
$(document).on('click', '.btn-danger[data-id]', function () {
deleteUnId = $(this).data('id');

// Obtener nombre de usuario
fetch(`${assetsPath}unidadAjax/obtener?id=${deleteUnId}`)
    .then(res => res.json())
    .then(data => {
        if (!data || !data.id_tipo_unidad) {
          alert("No se encontró la información de la unidad.");
          return;
        }
        deleteUnName = data.nombre;
        deleteUnEstado = data.activo;

        $('#modalConfirmDelete1Body').text(`¿Está seguro de cambiar el estado de la unidad "${deleteUnName}"?`);

        // Mostrar modal 1
        const modal1 = new bootstrap.Modal(document.getElementById('modalConfirmDelete1'));
        modal1.show();
      })
      .catch(err => {
        console.error("Error al obtener unidad para cambiar:", err);
        alert("Error al obtener datos de la unidad.");
      });
});

  // Confirmación modal 1 - pasa a modal 2
  $('#btnConfirmDelete1').on('click', function () {
    const modal1El = document.getElementById('modalConfirmDelete1');
    const modal1 = bootstrap.Modal.getInstance(modal1El);
    modal1.hide();

    let mensaje = "";
    if (deleteUnEstado === 1) {
      mensaje = "Al cambiar de estado la unidad y sus productos que engloban se desactivarán en el sistema, ¿desea continuar?";
    } else if (deleteUnEstado === 2) {
      mensaje = "Al cambiar de estado la unidad con sus productos asociados estarán vigentes en el sistema nuevamente, ¿desea continuar?";
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

    fetch(`${assetsPath}unidadAjax/eliminar`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id_tipo_unidad: deleteUnId })
    })
      .then(res => res.json())
      .then(resp => {
        if (resp.success) {
          Toastify({
            text: `Usuario "${deleteUnName}" desactivado correctamente ✅`,
            duration: 3000,
            style: { background: "#28a745" }
          }).showToast();

          // Recargar tabla
          $('.datatables-category-list').DataTable().ajax.reload(null, false);

          modal2.hide();
        } else {
          Toastify({
            text: `Error al desactivar unidad ⚠️`,
            duration: 3000,
            style: { background: "#dc3545" }
          }).showToast();
        }
      })
      .catch(err => {
        console.error("Error al desactivar unidad:", err);
        Toastify({
          text: "Error al eliminar unidad ⚠️",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      });
  });
/* ---- FIN: EVENTO CAMBIAR ---- */


});
