'use strict';

document.addEventListener("DOMContentLoaded", function () {
  const dtCategorias = document.querySelector(".datatables-category-list");
  const selects = $(".select2");
  // Inyectar permisos del usuario desde PHP
  const permisos = window.PERMISOS_USUARIO || [];

  // Helper para verificar si el usuario tiene un permiso específico en la vista actual
  const tienePermisoVista = (permisoEsperado) => {
    return permisos.some(p =>
      p.ruta === 'productos/categoria' && p.permiso === permisoEsperado
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
  if (dtCategorias) {

    const filtroCustomFeature = function(settings) {
    const container = document.createElement('div');
    container.classList.add('d-flex', 'align-items-center', 'gap-2', 'flex-wrap');

    container.innerHTML = `
    <div class="row gx-2 mx-3 mb-3 align-items-center">      
      <div class="col">
        <input type="text" id="categoria-filtro-valor" class="form-control form-control-sm" placeholder="Buscar por Nombre...">
      </div>
      <div class="col-auto">
        <button id="btnResetcategoria" class="btn btn-sm btn-secondary">
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
        text: '<i class="bx bx-plus"></i><span class="d-none d-sm-inline-block">Nueva Categoria</span>',
        className: "add-new btn btn-primary",
        attr: {
          "data-bs-toggle": "modal",
          "data-bs-target": "#modalRegistrarCategoria"
        }
      });
    }

  const tabla = new DataTable(dtCategorias, {
    ajax: assetsPath + "categoriaAjax/listar",
      columns: [
        { data: null },         // Numero oculto (control)
        { data: "nombre" },         // nombre rol
        { data: "descripcion" },         // nombre rol
        { data: "total_productos" },         // nombre rol
        { data: "activo" },         // estado (badge)
        { data: "id_categoria" }      // acciones con ID
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
        emptyTable: "No se encontraron categorias registrados",
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
  $(document).on("input", "#categoria-filtro-valor", function () {
      const valor = $(this).val().trim();

      let colIndex = 1;

      //Limpiar filtro
      tabla.columns().search('');
      tabla.column(colIndex).search(valor).draw();
    });

    // Resetear Búsqueda
    $(document).on("click", "#btnResetcategoria", function () {
      $("#categoria-filtro-valor").val("");
      tabla.columns().search('');
      tabla.column(4).search('^1$', true, false).draw(); // Restaurar a activos
    });
 }


 /* ---- INICIO: EVENTO REGISTRAR ---- */
$(function() {  
  let categoriaDisponible = null;  // null = not checked

  // Verificar categoria
  $('#verificarcategoria').on('click', function (e) {
    e.preventDefault();
    const categoria = $('#reg-nombre-categoria').val().trim();
   if (!categoria) return;

    fetch(`${assetsPath}categoriaAjax/validar_categoria?nombre=${encodeURIComponent(categoria)}`)
      .then(res => res.json())
      .then(data => {
        categoriaDisponible = !data.valid;
        Toastify({
          text: categoriaDisponible ? "Categoria disponible ✅" : "Categoria ya registrado ❌",
          duration: 3000,
          style: { background: categoriaDisponible ? "#28a745" : "#dc3545" }
        }).showToast();
      });
  });
  
  // Enviar formulario
  $('#formRegistrarCategoria').on('submit', function (e) {
    e.preventDefault();

    const form = this;
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    // Validar categoria y correo en vivo antes de enviar
    if (categoriaDisponible === false) {
      Toastify({ text: 'Categoria ya registrado ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }

    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

    const formData = new FormData(form);

    fetch(`${assetsPath}categoriaAjax/registrar`, {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Toastify({ text: 'Categoria registrado correctamente ✅', style: { background: '#28a745' } }).showToast();
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalRegistrarCategoria'));
          modal.hide();
          $('.datatables-category-list').DataTable().ajax.reload(null, false);
          form.reset();
          categoriaDisponible = null;          
        } else {
          Toastify({ text: data.message || 'Error al registrar categoria ❌', style: { background: '#dc3545' } }).showToast();
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
  let deleteCatId = null;
  let deleteCatName = '';
  let deleteCatEstado = 0;

  // Primera Confirmación
  $(document).on('click', '.btn-danger[data-id]', function () {
    deleteCatId = $(this).data('id');

    // Obtener nombre de usuario
    fetch(`${assetsPath}categoriaAjax/obtener?id=${deleteCatId}`)
      .then(res => res.json())
      .then(data => {
        if (!data || !data.id_categoria) {
          alert("No se encontró la información de la categoría.");
          return;
        }
        deleteCatName = data.nombre;
        deleteCatEstado = data.activo;

        $('#modalConfirmDelete1Body').text(`¿Está seguro de cambiar el estado de la categoría "${deleteCatName}"?`);

        // Mostrar modal 1
        const modal1 = new bootstrap.Modal(document.getElementById('modalConfirmDelete1'));
        modal1.show();
      })
      .catch(err => {
        console.error("Error al obtener categoría para cambiar:", err);
        alert("Error al obtener datos de la categoría.");
      });
  });

  // Confirmación modal 1 - pasa a modal 2
  $('#btnConfirmDelete1').on('click', function () {
    const modal1El = document.getElementById('modalConfirmDelete1');
    const modal1 = bootstrap.Modal.getInstance(modal1El);
    modal1.hide();

    let mensaje = "";
    if (deleteCatEstado === 1) {
      mensaje = "Al cambiar de estado la categoría y sus productos que engloban se desactivarán en el sistema, ¿desea continuar?";
    } else if (deleteCatEstado === 2) {
      mensaje = "Al cambiar de estado la categoría con sus productos asociados estarán vigentes en el sistema nuevamente, ¿desea continuar?";
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

    fetch(`${assetsPath}categoriaAjax/eliminar`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id_categoria: deleteCatId })
    })
      .then(res => res.json())
      .then(resp => {
        if (resp.success) {
          Toastify({
            text: `Usuario "${deleteCatName}" desactivado correctamente ✅`,
            duration: 3000,
            style: { background: "#28a745" }
          }).showToast();

          // Recargar tabla
          $('.datatables-category-list').DataTable().ajax.reload(null, false);

          modal2.hide();
        } else {
          Toastify({
            text: `Error al desactivar categoría ⚠️`,
            duration: 3000,
            style: { background: "#dc3545" }
          }).showToast();
        }
      })
      .catch(err => {
        console.error("Error al desactivar categoría:", err);
        Toastify({
          text: "Error al eliminar categoría ⚠️",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      });
  });
  /* ---- FIN: EVENTO CAMBIAR ---- */


/* ---- INICIO: EVENTO EDITAR ---- */
// Verificar correo al editar
  $('#modalEditarCategoria').on('click', '#editverificarcategoria', function (e) {
    e.preventDefault();
    const categoria = $('#edit-nombre-categoria').val().trim();
    const idCategoria = $('#edit-id_categoria').val().trim();
   if (!categoria) return;

    fetch(`${assetsPath}categoriaAjax/validar_categoria?nombre=${encodeURIComponent(categoria)}&id_categoria=${encodeURIComponent(idCategoria)}`)
      .then(res => res.json())
      .then(data => {
        const categoriaDisponible = !data.valid;
        Toastify({
          text: categoriaDisponible ? "Categoria disponible ✅" : "Categoria ya registrado ❌",
          duration: 3000,
          style: { background: categoriaDisponible ? "#28a745" : "#dc3545" }
        }).showToast();
      })
      .catch(() => {
      Toastify({
        text: "Error en la verificación del usuario",
        duration: 3000,
        style: { background: "#dc3545" }
      }).showToast();
    });
  });

let originalEditCategoriaData = {};

// Evento para abrir modal y cargar datos
$(document).on('click', '.btn-warning[data-id]', function () {
  const categoriaId = $(this).data('id');

  fetch(`${assetsPath}categoriaAjax/obtener?id=${categoriaId}`)
    .then(res => res.json())
    .then(data => {
      if (!data || !data.id_categoria) {
        alert("No se encontró la categoría.");
        return;
      }

      $('#edit-id_categoria').val(data.id_categoria);
      $('#edit-nombre-categoria').val(data.nombre);
      $('#edit-descripcion-categoria').val(data.descripcion);

      originalEditCategoriaData = {
        nombre: data.nombre,
        descripcion: data.descripcion
      };

      const modal = new bootstrap.Modal(document.getElementById('modalEditarCategoria'));
      modal.show();
    })
    .catch(err => {
      console.error("Error al obtener categoría:", err);
      alert("Error al obtener datos de la categoría.");
    });
});

// Función para detectar cambios en el formulario
function hasCategoriaFormChanged() {
  return (
    $('#edit-nombre-categoria').val().trim() !== (originalEditCategoriaData.nombre || '').trim() ||
    $('#edit-descripcion-categoria').val().trim() !== (originalEditCategoriaData.descripcion || '').trim()
  );
}

// Enviar actualización
let isSubmittingCategoria = false;
$('#formEditarCategoria').on('submit', function (e) {
  e.preventDefault();

  if (!this.checkValidity()) {
    this.reportValidity();
    return;
  }

  if (!hasCategoriaFormChanged()) {
    Toastify({
      text: "No se realizaron cambios ⚠️",
      duration: 3000,
      style: { background: "#ffc107", color: "#000" }
    }).showToast();
    return;
  }

  isSubmittingCategoria = true;

  const formData = new FormData(this);

  fetch(`${assetsPath}categoriaAjax/actualizar`, {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarCategoria'));
        modal.hide();
        $('.datatables-category-list').DataTable().ajax.reload(null, false);
        Toastify({
          text: "Categoría actualizada correctamente ✅",
          duration: 3000,
          style: { background: "#28a745" }
        }).showToast();
      } else {
        Toastify({
          text: "Error al actualizar categoría ⚠️",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      }
    })
    .catch(err => {
      console.error("Error al actualizar categoría:", err);
      Toastify({
        text: "Error al actualizar categoría ⚠️",
        duration: 3000,
        style: { background: "#dc3545" }
      }).showToast();
    })
    .finally(() => {
      isSubmittingCategoria = false;
    });
});

// Confirmar antes de cerrar si hay cambios
$('#modalEditarCategoria').on('hide.bs.modal', function (e) {
  if (!isSubmittingCategoria && hasCategoriaFormChanged()) {
    e.preventDefault();

    const salir = confirm("Hay cambios sin guardar. ¿Seguro que deseas salir?");
    if (salir) {
      $('#modalEditarCategoria').off('hide.bs.modal');
      $('#modalEditarCategoria').modal('hide');
    }
  }
});

/* ---- FIN: EVENTO EDITAR ---- */




});