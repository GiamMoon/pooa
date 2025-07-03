'use strict';

document.addEventListener("DOMContentLoaded", function () {
  const dtRoles = document.querySelector(".datatables-category-list");
  const selects = $(".select2");
  // Inyectar permisos del rol desde PHP
  const permisos = window.PERMISOS_USUARIO || [];

  // Helper para verificar si el usuario tiene un permiso específico en la vista actual
  const tienePermisoVista = (permisoEsperado) => {
    return permisos.some(p => 
      p.ruta === 'seguridad/rol' && p.permiso === permisoEsperado
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
  if (dtRoles) {

    const filtroCustomFeature = function(settings) {
    const container = document.createElement('div');
    container.classList.add('d-flex', 'align-items-center', 'gap-2', 'flex-wrap');

    container.innerHTML = `
    <div class="row gx-2 mx-3 mb-3 align-items-center">      
      <div class="col">
        <input type="text" id="rol-filtro-valor" class="form-control form-control-sm" placeholder="Buscar por Nombre...">
      </div>
      <div class="col-auto">
        <button id="btnResetRol" class="btn btn-sm btn-secondary">
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
        text: '<i class="bx bx-plus"></i><span class="d-none d-sm-inline-block">Nuevo Rol</span>',
        className: "add-new btn btn-primary",
        attr: {
          "data-bs-toggle": "modal",
          "data-bs-target": "#modalRegistrarRol"
        }
      });
    }

  const tabla = new DataTable(dtRoles, {
    ajax: assetsPath + "rolAjax/listar",
      columns: [
        { data: null },         // Numero oculto (control)
        { data: "nombre_rol" },         // nombre rol
        { data: "usuarios_asignados" },         // nombre rol
        { data: "usuarios_activos" },         // nombre rol
        { data: "activo" },         // estado (badge)
        { data: "id_rol" }      // acciones con ID
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
          className: "text-start"
        },
        {
          targets: 3,
          className: "text-start"
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

            if (tienePermisoVista('visualizar')) {
              botones += `<button class="btn btn-sm btn-icon btn-info" title="Ver" data-id="${id}"><i class="bx bx-show-alt"></i></button>`;
            }

            let deshabilitarEditar = !tienePermisoVista('editar');            
            botones += `<button class="btn btn-sm btn-icon ${deshabilitarEditar ? 'btn-secondary' : 'btn-warning'}" title="Editar" data-id="${id}" ${deshabilitarEditar ? 'disabled' : ''}><i class="bx bx-edit-alt"></i></button>`;            

            let deshabilitarEliminar = !tienePermisoVista('eliminar') || parseInt(id) === 1 || parseInt(id) === 3;            
            botones += `<button class="btn btn-sm btn-icon ${deshabilitarEliminar ? 'btn-secondary' : 'btn-danger'}" title="Eliminar" data-id="${id}" ${deshabilitarEliminar ? 'disabled' : ''}><i class="bx bx-git-commit"></i></button>`;            

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
        emptyTable: "No se encontraron roles registrados",
        paginate: {
          next: '<i class="bx bx-chevron-right scaleX-n1-rtl icon-18px"></i>',
          previous: '<i class="bx bx-chevron-left scaleX-n1-rtl icon-18px"></i>'
        }
      },
      responsive: {
        details: {
          display: DataTable.Responsive.display.modal({
            header: row => 'Detalles de ' + row.data().nombre_rol
          }),
          type: "column",
          renderer: DataTable.Responsive.renderer.listHiddenNodes()
        }
      }
  });

  /* Filtro de estado por defecto = Activo */
  tabla.column(4).search('^1$', true, false).draw();

  /* PARA FILTROS Y BUSQUEDAD*/
  $(document).on("input", "#rol-filtro-valor", function () {
      const valor = $(this).val().trim();

      let colIndex = 1;

      //Limpiar filtro
      tabla.columns().search('');
      tabla.column(colIndex).search(valor).draw();
    });

    // Resetear Búsqueda
    $(document).on("click", "#btnResetRol", function () {
      $("#rol-filtro-valor").val("");
      tabla.columns().search('');
      tabla.column(4).search('^1$', true, false).draw(); // Restaurar a activos
    });
 }

  //BLOQUEAR ESPACIOS
  const camposSinEspacios = ['reg-rol-nombre', 'edit-rol-nombre'];
  camposSinEspacios.forEach(id => {
  const input = document.getElementById(id);

  if (input) {
    input.addEventListener('input', function (e) {
      let valor = input.value;

      // Reemplaza espacios seguidos por uno solo
      valor = valor.replace(/\s{2,}/g, ' ');

      // Reemplaza un espacio entre palabras por "_"
      valor = valor.replace(/(\S)\s(\S)/g, '$1_$2');

      // Si termina en espacio y solo hay una palabra, remueve el espacio
      if (valor.trim().indexOf(' ') === -1 && valor.endsWith(' ')) {
        valor = valor.trim();
      }

      input.value = valor;
    });
  }
});


   /* ---- INICIO: EVENTO VISUALIZAR ---- */
  document.querySelector('.datatables-category-list').addEventListener('click', e => {
    const btn = e.target.closest('button[title="Ver"]');
    if (!btn) return;

    const idRol = btn.getAttribute('data-id');
    if (!idRol) return;

    const modal = new bootstrap.Modal(document.getElementById('modalVerRol'));
    modal.show();

    // Reset inputs
    $('#ver-rol-nombre').val('Cargando...');
    $('#ver-usuarios-asignados').val('');
    $('#ver-usuarios-activos').val('');
    $('#ver-estado').val('');
    $('#ver-estructura-permisos').html('<p>Cargando permisos...</p>');

    fetch(`${assetsPath}rolAjax/detalle?id_rol=${idRol}`)
      .then(res => res.json())
      .then(resp => {
        if (!resp.success) throw new Error(resp.message || 'Error al obtener detalles');

        const { rol, permisosAsignados, todosPermisos, todosRecursos } = resp.data;

        $('#ver-rol-nombre').val(rol.nombre_rol);
        $('#ver-usuarios-asignados').val(rol.usuarios_asignados);
        $('#ver-usuarios-activos').val(rol.usuarios_activos);
        $('#ver-estado').val(rol.activo === 1 ? 'Activo' : 'Inactivo');

        //Deshabilitar todos los elementos del formulario
        $('#modalVerRol').find('input').prop('disabled', true);

        renderMatrizPermisosDetalle(todosPermisos, todosRecursos, permisosAsignados);
      })
      .catch(err => {
        $('#ver-estructura-permisos').html(`<p class="text-danger">${err.message}</p>`);
      });
  });

  // Función para render matriz de permisos en solo lectura
  function renderMatrizPermisosDetalle(permisos, recursos, permisosAsignados) {
    const modulos = recursos.filter(r => r.tipo === 'MODULO');
    const secciones = recursos.filter(r => r.tipo === 'SECCION');
    const subsecciones = recursos.filter(r => r.tipo === 'SUBSECCION');

    const asignadosSet = new Set(permisosAsignados.map(pa => `${pa.id_recurso}-${pa.id_permiso}`));
    let html = '';

    modulos.forEach(mod => {
      const modId = `ver-modulo-${mod.id_recurso}`;
      html += `
        <div class="accordion-item border rounded mb-2">
          <h2 class="accordion-header" id="heading-${modId}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${modId}" aria-expanded="false">
              ${mod.nombre}
            </button>
          </h2>
          <div id="collapse-${modId}" class="accordion-collapse collapse" aria-labelledby="heading-${modId}">
            <div class="accordion-body table-responsive p-0">
              <table class="table table-sm table-bordered m-0">
                <thead class="table-light">
                  <tr>
                    <th style="width: 30%">Recurso</th>
                    ${permisos.map(p => `<th class="text-center">${p.nombre}</th>`).join('')}
                  </tr>
                </thead>
                <tbody>`;

      secciones.filter(sec => sec.recurso_padre == mod.id_recurso).forEach(sec => {
        html += `
          <tr>
            <td class="ps-4">${sec.nombre}</td>
            ${permisos.map(p => {
              const key = `${sec.id_recurso}-${p.id_permiso}`;
              const checked = asignadosSet.has(key) ? 'checked' : '';
              return `<td class="text-center"><input type="checkbox" disabled ${checked}></td>`;
            }).join('')}
          </tr>`;

        subsecciones.filter(sub => sub.recurso_padre == sec.id_recurso).forEach(sub => {
          html += `
          <tr>
            <td class="ps-5">${sub.nombre}</td>
            ${permisos.map(p => {
              const key = `${sub.id_recurso}-${p.id_permiso}`;
              const checked = asignadosSet.has(key) ? 'checked' : '';
              return `<td class="text-center"><input type="checkbox" disabled ${checked}></td>`;
            }).join('')}
          </tr>`;
        });
      });

      html += `
                </tbody>
              </table>
            </div>
          </div>
        </div>`;
    });

    $('#ver-estructura-permisos').html(html);
  }
  /* ---- FIN: EVENTO VISUALIZAR ---- */


  
  /* ---- INICIO: EVENTO REGISTRAR ---- */
$(function () {
  let rolDisponible = null;
  let isSubmittingRegRol = false;

  //Cargar Datos del formulario
  $('#modalRegistrarRol').on('show.bs.modal', function () {
    $('#reg-rol-nombre').val('');
    $('#estructura-permisos').html('<p class="text-muted">Cargando permisos y recursos...</p>');

    Promise.all([
      fetch(`${assetsPath}rolAjax/listar_permisos`).then(res => res.json()),
      fetch(`${assetsPath}rolAjax/listar_recursos`).then(res => res.json())
    ])
    .then(([permisos, recursos]) => {
      renderMatrizPermisos(permisos, recursos);
    })
    .catch(() => {
      $('#estructura-permisos').html('<p class="text-danger">Error al cargar permisos o recursos.</p>');
    });
  });
  function renderMatrizPermisos(permisos, recursos) {
    const modulos = recursos.filter(r => r.tipo === 'MODULO');
    const secciones = recursos.filter(r => r.tipo === 'SECCION');
    const subsecciones = recursos.filter(r => r.tipo === 'SUBSECCION');

    let html = ``;

    modulos.forEach((mod, index) => {
      const modId = `modulo-${mod.id_recurso}`;
      html += `
        <div class="accordion-item border rounded mb-2">
          <h2 class="accordion-header" id="heading-${modId}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${modId}" aria-expanded="false">
              ${mod.nombre}
            </button>
          </h2>
          <div id="collapse-${modId}" class="accordion-collapse collapse" aria-labelledby="heading-${modId}">
            <div class="accordion-body table-responsive p-0">
              <table class="table table-sm table-bordered m-0">
                <thead class="table-light">
                  <tr>
                    <th style="width: 30%">Recurso</th>
                    ${permisos.map(p => `<th class="text-center">${p.nombre}</th>`).join('')}
                  </tr>
                </thead>
                <tbody>`;

      secciones.filter(sec => sec.recurso_padre == mod.id_recurso).forEach(sec => {
        html += `
          <tr>
            <td class="ps-4">${sec.nombre}</td>
            ${permisos.map(p =>
              `<td class="text-center"><input type="checkbox" class="perm-check" data-id_permiso="${p.id_permiso}" data-id_recurso="${sec.id_recurso}"></td>`
            ).join('')}
          </tr>`;

        subsecciones.filter(sub => sub.recurso_padre == sec.id_recurso).forEach(sub => {
          html += `
          <tr>
            <td class="ps-5">${sub.nombre}</td>
            ${permisos.map(p =>
              `<td class="text-center"><input type="checkbox" class="perm-check" data-id_permiso="${p.id_permiso}" data-id_recurso="${sub.id_recurso}"></td>`
            ).join('')}
          </tr>`;
        });
      });

      html += `
                </tbody>
              </table>
            </div>
          </div>
        </div>`;
    });

    $('#estructura-permisos').html(html);
  }

  // Habilitar Botón Usuario
  $(document).ready(function () {
    // Deshabilitar inicialmente
    $('#verificarrol').prop('disabled', true);

    // Monitorear cambios
    $('#reg-rol-nombre').on('input', function () {
      const valor = $(this).val().trim();
      $('#verificarrol').prop('disabled', valor.length === 0);
    });
  });

  // Verificar Rol
  function verificarRolExistenteReg() {
    const nombre = $('#reg-rol-nombre').val().trim();

    if (!nombre) {
      Toastify({
        text: 'Ingrese un rol',
        duration: 3000,
        style: { background: '#ffc107', color: '#000' }
      }).showToast();
      return Promise.resolve(false);
    };

    return fetch(`${assetsPath}rolAjax/validar_rol?nombre=${encodeURIComponent(nombre)}`)
    .then (res => {
      if (!res.ok) throw new Error('Error de red en validad registros');
      return res.json();
    })
    .then (data => {      
      rolDisponible = !data.valid;
      Toastify({
        text: rolDisponible ? "Rol disponible ✅" : "Rol ya registrado ❌",
        duration: 3000,
        style: { background: rolDisponible ? "#28a745" : "#dc3545" }
      }).showToast();          
    })
    .catch(() => {
      Toastify({
        text: "Error en la verificación del rol",
        duration: 3000,
        style: { background: "#dc3545" }
      }).showToast();
    });  
  }
  //Botón de Verificar Usuario
  $('#verificarrol').on('click', function(e) {
    e.preventDefault();
    verificarRolExistenteReg();
  });

  //Envío del formulario
  $('#formRegistrarRol').on('submit', async function (e) {
    e.preventDefault();

    const form = this;
    const nombre = $('#reg-rol-nombre').val().trim();
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    // Validar rol
    await verificarRolExistenteReg();
    if (rolDisponible === false) {
      Toastify({ text: 'Rol ya registrado ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }

    //Obtener asignaciones marcadas
    const asignaciones = [];
    $('.perm-check:checked').each(function () {
      asignaciones.push({
        id_permiso: $(this).data('id_permiso'),
        id_recurso: $(this).data('id_recurso')
      });
    });

    enviarRegistroRol(form,nombre,asignaciones);    
  });

  //Función enviar
  function enviarRegistroRol(form,nombre,asignaciones){

    isSubmittingRegRol = true;

    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

    fetch(`${assetsPath}rolAjax/registrar`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        nombre: nombre,
        asignaciones: JSON.stringify(asignaciones)
      })
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Toastify({ text: 'Rol registrado correctamente ✅', style: { background: "#28a745" } }).showToast();
          $('#modalRegistrarRol').modal('hide');
          $('.datatables-category-list').DataTable().ajax.reload(null, false);
          form.reset();
          rolDisponible = null; // Reset estado para siguientes registros
        } else {
          Toastify({ text: data.message || 'Error al registrar rol ❌', style: { background: "#dc3545" } }).showToast();
        }
      })
      .catch(() => {
        Toastify({ text: 'Error de Red ❌', style: { background: "#dc3545" } }).showToast();
      })
      .finally(() => {
        submitBtn.prop('disabled', false).html('Registrar');
        isSubmittingRegRol = false;
      });
  }
});
  /* ---- FIN: EVENTO REGISTRAR ---- */



  /* ---- INICIO: EVENTO EDITAR ---- */
  let originalRolData = {};
  let isSubmittingRol = false;
  let rolEditDisponible = false;

  $(document).on('click', '.btn-warning[data-id]', function () {
    const idRol = $(this).data('id');
    if (!idRol) return;

    const modal = new bootstrap.Modal(document.getElementById('modalEditarRol'));
    modal.show();

    // Reset campos
    $('#rol-id').val(idRol);
    $('#edit-rol-nombre').val('');
    $('#editar-estructura-permisos').html('<p class="text-muted">Cargando permisos y recursos...</p>');

    // Obtener detalle del rol
    fetch(`${assetsPath}rolAjax/detalle?id_rol=${idRol}`)
      .then(res => res.json())
      .then(resp => {
        if (!resp.success) throw new Error('No se pudo cargar los datos del rol');

        const { rol, permisosAsignados, todosPermisos, todosRecursos } = resp.data;

        $('#edit-rol-nombre').val(rol.nombre_rol);

        // Render permisos (matriz editable)
        renderMatrizPermisosEditar(todosPermisos, todosRecursos, permisosAsignados);

        // Guardar data original para validar cambios
         setTimeout(() => {
          const asignaciones = [];
          $('.perm-check:checked').each(function () {
            asignaciones.push(`${$(this).data('id_recurso')}-${$(this).data('id_permiso')}`);
          });

          originalRolData = {
            nombre: rol.nombre_rol,
            asignaciones: asignaciones.sort()
          };
        }, 50);
      })
      .catch(() => {
        $('#editar-estructura-permisos').html('<p class="text-danger">Error al cargar permisos o recursos.</p>');
    });
  });

  function renderMatrizPermisosEditar(permisos, recursos, permisosAsignados) {
    const modulos = recursos.filter(r => r.tipo === 'MODULO');
    const secciones = recursos.filter(r => r.tipo === 'SECCION');
    const subsecciones = recursos.filter(r => r.tipo === 'SUBSECCION');

    const asignadosSet = new Set(permisosAsignados.map(pa => `${pa.id_recurso}-${pa.id_permiso}`));
    let html = '';

    modulos.forEach(mod => {
      const modId = `edit-modulo-${mod.id_recurso}`;
      html += `
        <div class="accordion-item border rounded mb-2">
          <h2 class="accordion-header" id="heading-${modId}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${modId}" aria-expanded="false">
              ${mod.nombre}
            </button>
          </h2>
          <div id="collapse-${modId}" class="accordion-collapse collapse" aria-labelledby="heading-${modId}">
            <div class="accordion-body table-responsive p-0">
              <table class="table table-sm table-bordered m-0">
                <thead class="table-light">
                  <tr>
                    <th style="width: 30%">Recurso</th>
                    ${permisos.map(p => `<th class="text-center">${p.nombre}</th>`).join('')}
                  </tr>
                </thead>
                <tbody>`;

      secciones.filter(sec => sec.recurso_padre == mod.id_recurso).forEach(sec => {
        html += `
          <tr>
            <td class="ps-4">${sec.nombre}</td>
            ${permisos.map(p => {
              const key = `${sec.id_recurso}-${p.id_permiso}`;
              const checked = asignadosSet.has(key) ? 'checked' : '';
              return `<td class="text-center"><input type="checkbox" class="perm-check" data-id_permiso="${p.id_permiso}" data-id_recurso="${sec.id_recurso}" ${checked}></td>`;
            }).join('')}
          </tr>`;

        subsecciones.filter(sub => sub.recurso_padre == sec.recurso_padre).forEach(sub => {
          html += `
          <tr>
            <td class="ps-5">${sub.nombre}</td>
            ${permisos.map(p => {
              const key = `${sub.id_recurso}-${p.id_permiso}`;
              const checked = asignadosSet.has(key) ? 'checked' : '';
              return `<td class="text-center"><input type="checkbox" class="perm-check" data-id_permiso="${p.id_permiso}" data-id_recurso="${sub.id_recurso}" ${checked}></td>`;
            }).join('')}
          </tr>`;
        });
      });

      html += `
                </tbody>
              </table>
            </div>
          </div>
        </div>`;
    });

    $('#editar-estructura-permisos').html(html);
  }

  // Habilitar Botón Usuario
  $(document).ready(function () {
    // Monitorear cambios
    $('#edit-rol-nombre').on('input', function () {
      const valor = $(this).val().trim();
      $('#verificareditrol').prop('disabled', valor.length === 0);
    });
  });

  // Verificar Rol
  function verificarRolExistente() {
    const nombre = $('#edit-rol-nombre').val().trim();
    const idRol = $('#rol-id').val();

    if (!nombre) {
      Toastify({
        text: 'Ingrese un rol',
        duration: 3000,
        style: { background: '#ffc107', color: '#000' }
      }).showToast();
      return Promise.resolve(false);
    };

    return fetch(`${assetsPath}rolAjax/validar_rol?nombre=${encodeURIComponent(nombre)}&id_rol=${encodeURIComponent(idRol)}`)
    .then (res => {
      if (!res.ok) throw new Error('Error de red en validad registros');
      return res.json();
    })
    .then (data => {      
      rolEditDisponible = !data.valid;
      Toastify({
        text: rolEditDisponible ? "Rol disponible ✅" : "Rol ya registrado ❌",
        duration: 3000,
        style: { background: rolEditDisponible ? "#28a745" : "#dc3545" }
      }).showToast();          
    })
    .catch(() => {
      Toastify({
        text: "Error en la verificación del rol",
        duration: 3000,
        style: { background: "#dc3545" }
      }).showToast();
    });  
  }
  //Botón de Verificar Usuario
  $('#verificareditrol').on('click', function(e) {
    e.preventDefault();
    verificarRolExistente();
  });

  function hasRolChanged() {
    const nombreActual = $('#edit-rol-nombre').val().trim();
    if (nombreActual !== (originalRolData.nombre || '').trim()) {
      return true;
    }

    const asignacionesActuales = [];
    $('.perm-check:checked').each(function () {
      asignacionesActuales.push(`${$(this).data('id_recurso')}-${$(this).data('id_permiso')}`);
    });

    const actualesSorted = asignacionesActuales.sort();
    const originalesSorted = (originalRolData.asignaciones || []).sort();

    if (actualesSorted.length !== originalesSorted.length) return true;
    
    for (let i = 0; i < actualesSorted.length; i++) {
      if (actualesSorted[i] !== originalesSorted[i]) return true;
    }

    return false;
  }

  // Envío del formulario de edición
  $('#formEditarRol').off('submit').on('submit', function (e) {
    e.preventDefault();

    if (!this.checkValidity()) {
      this.reportValidity();
      return;
    }

    if (!hasRolChanged()) {
      Toastify({
        text: "No se realizaron cambios ⚠️",
        duration: 3000,
        style: { background: "#ffc107", color: "#000" }
      }).showToast();
      return; // <-- Important: Stop sending here if no changes!
    }

    const idRol = $('#rol-id').val();
    const nombre = $('#edit-rol-nombre').val().trim();

    const asignaciones = [];
    $('.perm-check:checked').each(function () {
      asignaciones.push({
        id_permiso: $(this).data('id_permiso'),
        id_recurso: $(this).data('id_recurso')
      });
    });

    isSubmittingRol = true;

    fetch(`${assetsPath}rolAjax/actualizar`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        id_rol: idRol,
        nombre: nombre,        
        asignaciones: JSON.stringify(asignaciones)
      })
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Toastify({ text: 'Rol actualizado correctamente ✅', style: { background: "#28a745" } }).showToast();
          $('#modalEditarRol').modal('hide');
          $('.datatables-category-list').DataTable().ajax.reload(null, false);
        } else {
          Toastify({ text: data.message || 'Error al actualizar el rol ❌', style: { background: "#dc3545" } }).showToast();
        }
      })
      .catch(() => {
        Toastify({ text: 'Error de red ❌', style: { background: "#dc3545" } }).showToast();
      })
      .finally(() =>{
        isSubmittingRol = false;
      });
  });

  $('#modalEditarRol').on('show.bs.modal', function () {
    $(this).off('hide.bs.modal').on('hide.bs.modal', function (e) {
      if (!isSubmittingRol && hasRolChanged()) {
        e.preventDefault();
        const salir = confirm("Hay cambios sin guardar. ¿Deseas salir y perder los cambios?");
        if (salir) {
          $(this).off('hide.bs.modal');
          $(this).modal('hide');
        }
      }
    });
  });
  /* ---- FIN: EVENTO EDITAR  ---- */


/* --- INICIO: Evento ELIMINAR  */
let rolAEliminar = null;
let nombreRolEliminar = '';
let usuariosConRol = [];
const ID_ROL_INVITADO = 3; // rol invitado fijo

// Abrir modal confirmación inicial al hacer click en eliminar
$(document).on('click', '.btn-danger[data-id]', function () {
  rolAEliminar = $(this).data('id');
  nombreRolEliminar = $(this).closest('tr').find('td').eq(1).text() || 'este rol';

  $('#modalConfirmDeleteRol1Body').text(`¿Estás seguro de que deseas cambiar el estado del rol "${nombreRolEliminar}"?`);
  new bootstrap.Modal(document.getElementById('modalConfirmDeleteRol1')).show();
});

// Confirmar eliminación (modal 1)
$('#btnConfirmDeleteRol1').on('click', function () {
  if (!rolAEliminar) return;

  // Contar usuarios asignados a ese rol
  fetch(`${assetsPath}rolAjax/contar_usuarios_rol?id_rol=${rolAEliminar}`)
    .then(res => res.json())
    .then(data => {
      const total = data.total || 0;
      const activos = data.activos || 0;

      // Ocultar modal 1
      bootstrap.Modal.getInstance(document.getElementById('modalConfirmDeleteRol1')).hide();

      if (total > 0) {
        // Mostrar modal 2 con advertencia
        $('#modalConfirmDeleteRol2Body').html(`
          El rol "<strong>${nombreRolEliminar}</strong>" tiene asignados <strong>${total}</strong> usuarios, de los cuales <strong>${activos}</strong> están activos.<br/>
          ¿Estás seguro de cambiar su estado?
        `);
        new bootstrap.Modal(document.getElementById('modalConfirmDeleteRol2')).show();
      } else {
        // No hay usuarios, confirmar eliminación simple
        $('#modalConfirmDeleteRol2Body').html(`No hay usuarios asignados a este rol.<br/>¿Deseas Descativarlo?`);
        new bootstrap.Modal(document.getElementById('modalConfirmDeleteRol2')).show();
      }
    })
    .catch(() => {
      alert('Error al contar usuarios asignados al rol');
    });
});

// Confirmar en modal 2
$('#btnConfirmDeleteRol2').on('click', function () {
  // Cerrar modal 2
  bootstrap.Modal.getInstance(document.getElementById('modalConfirmDeleteRol2')).hide();

  // Si hay usuarios, abrir modal 3 para opciones, sino eliminar directo
  fetch(`${assetsPath}rolAjax/contar_usuarios_rol?id_rol=${rolAEliminar}`)
    .then(res => res.json())
    .then(data => {
      const total = data.total || 0;

      if (total > 0) {
        // Mostrar modal 3 con opciones de reasignación
        new bootstrap.Modal(document.getElementById('modalConfirmDeleteRol3')).show();
      } else {
        // Sin usuarios, eliminar directamente
        eliminarRolDirecto(rolAEliminar);
      }
    });
});

// Opción 1: reasignar todos al rol Nuevo
$('#optReasignarInvitado').on('click', function () {
  new bootstrap.Modal(document.getElementById('modalConfirmDeleteRol3')).hide();

  fetch(`${assetsPath}rolAjax/eliminar_rol_invitado`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id_rol: rolAEliminar })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('Rol desactivado y usuarios reasignados al rol Nuevo.');
      recargarTablaRoles();
    } else {
      alert('Error al desactivar rol');
    }
  });
});

// Opción 2: reasignar todos a otro rol existente
$('#optReasignarRolExistente').on('click', function () {
  // Cargar roles excepto invitado y actual para selección
  fetch(`${assetsPath}rolAjax/listar_roles_excepto?ex1=${ID_ROL_INVITADO}&ex2=${rolAEliminar}`)
    .then(res => res.json())
    .then(roles => {
      const select = $('#selectRolReasignar');
      select.empty();
      roles.forEach(rol => {
        select.append(`<option value="${rol.id_rol}">${rol.nombre}</option>`);
      });
      new bootstrap.Modal(document.getElementById('modalConfirmDeleteRol3')).hide();
      new bootstrap.Modal(document.getElementById('modalReasignarRolExistente')).show();
    });
});

$('#btnConfirmReasignarRolExistente').on('click', function () {
  const nuevoRol = $('#selectRolReasignar').val();
  if (!nuevoRol) {
    alert('Debe seleccionar un rol para reasignar');
    return;
  }

  fetch(`${assetsPath}rolAjax/eliminar_rol_reasignado`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id_rol: rolAEliminar, nuevo_rol: nuevoRol })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('Rol desactivado y usuarios reasignados correctamente.');
      new bootstrap.Modal(document.getElementById('modalReasignarRolExistente')).hide();
      recargarTablaRoles();
    } else {
      alert('Error al eliminar rol');
    }
  });
});

// Opción 3: reasignación personalizada por usuario
$('#optReasignarPorUsuario').on('click', function () {
  fetch(`${assetsPath}rolAjax/listar_usuarios_por_rol?id_rol=${rolAEliminar}`)
    .then(res => res.json())
    .then(usuarios => {
      fetch(`${assetsPath}rolAjax/listar_roles_excepto?ex1=0&ex2=${rolAEliminar}`)
        .then(res => res.json())
        .then(roles => {
          const tbody = $('#tablaReasignarUsuarios tbody');
          tbody.empty();

          usuarios.forEach(u => {
            let options = roles.map(r => `<option value="${r.id_rol}">${r.nombre}</option>`).join('');
            tbody.append(`
              <tr>
                <td>${u.nombre_usuario}</td>
                <td>
                  <select class="form-select rol-usuario-select" data-usuario-id="${u.id_usuario}">
                    <option value="">Seleccione rol</option>
                    ${options}
                  </select>
                </td>
              </tr>
            `);
          });

          new bootstrap.Modal.getInstance(document.getElementById('modalConfirmDeleteRol3')).hide();        
          new bootstrap.Modal.getOrCreateInstance(document.getElementById('modalReasignarPorUsuario')).show();
        });
    });
});

$('#btnConfirmReasignarPorUsuario').on('click', function () {
  const usuarios = [];

  $('.rol-usuario-select').each(function () {
    const idUsuario = $(this).data('usuario-id');
    const nuevoRol = $(this).val();

    if (!nuevoRol) {
      alert('Debe seleccionar un rol para todos los usuarios');
      usuarios.length = 0; // vaciar para no enviar
      return false; // break each
    }

    usuarios.push({ id_usuario: idUsuario, nuevo_rol: nuevoRol });
  });

  if (usuarios.length === 0) return;

  fetch(`${assetsPath}rolAjax/eliminar_rol_personalizado`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id_rol: rolAEliminar, usuarios })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('Rol desactivado y usuarios reasignados correctamente.');
      
      const modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalReasignarPorUsuario'));
      if (modalInstance) modalInstance.hide();
      
      recargarTablaRoles();
    } else {
      alert('Error al eliminar rol');
    }
  });
});

// Función para eliminar rol directamente sin usuarios
function eliminarRolDirecto(idRol) {
  fetch(`${assetsPath}rolAjax/eliminar_rol_invitado`, { // reutilizamos endpoint, pero sin reasignación real
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id_rol: idRol })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('Cambio de estado aplicado al Rol.');
      recargarTablaRoles();
    } else {
      alert('Error al eliminar rol');
    }
  });
}

// Refrescar tabla o lista de roles (debes ajustar a tu código)
function recargarTablaRoles() {
  $('.datatables-category-list').DataTable().ajax.reload(null, false);
}
/* --- FIN: Evento ELIMINAR */


  

});