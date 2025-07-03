'use strict';

document.addEventListener("DOMContentLoaded", function () {
  const dtUsuarios = document.querySelector(".datatables-category-list");
  const selects = $(".select2");
  // Inyectar permisos del usuario desde PHP
  const permisos = window.PERMISOS_USUARIO || [];

  //Verificar ROL ADMIN
  const esAdmin = parseInt(window.USER_ROLE) === 1;

  // Helper para verificar si el usuario tiene un permiso específico en la vista actual
  const tienePermisoVista = (permisoEsperado) => {
    return permisos.some(p =>
      p.ruta === 'seguridad/usuario' && p.permiso === permisoEsperado
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
  if (dtUsuarios) {

    const filtroCustomFeature = function(settings) {
      const container = document.createElement('div');
      container.classList.add('d-flex', 'align-items-center', 'gap-2', 'flex-wrap');

      container.innerHTML = `
      <div class="row gx-2 mx-3 mb-3 align-items-center">
        <div class="col-auto">
          <select id="usuario-filtro-campo" class="form-select form-select-sm">
            <option value="nombre_usuario">Usuario</option>
            <option value="correo">Correo</option>
            <option value="rol">Rol</option>
          </select>
        </div>
        <div class="col">
          <input type="text" id="usuario-filtro-valor" class="form-control form-control-sm" placeholder="Buscar por filtro seleccionado...">
        </div>
        <div class="col-auto">
          <button id="btnResetUsuario" class="btn btn-sm btn-secondary">
            <i class="bx bx-reset"></i>
          </button>
        </div>
      </div>
      `;

      return container;
    };


    const botonesTop = [];

    // Cargar sucursales antes de construir la tabla
    if (esAdmin) {
      botonesTop.push({
        extend: 'collection',
        className: 'btn btn-label-info dropdown-toggle me-2',
        text: `<span class="d-flex align-items-center gap-2">
                <i class="bx bx-building icon-xs"></i>
                <span class="d-none d-sm-inline-block">Sucursales</span>
              </span>`,
        autoClose: true,
        buttons: function () {
          const buttons = [{
            text: 'Todos',
            className: 'dropdown-item',
            action: function () {
              tabla.column(4).search('').draw();
            }
          }];

          $.ajax({
            url: `${assetsPath}usuarioAjax/listar_sucursales`,
            method: 'GET',
            async: false, // Bloquea hasta cargar las sucursales (porque el array debe ser inmediato)
            success: function (res) {
              let sucursales = [];

              // Si viene como string JSON, parsear
              if (typeof res === 'string') {
                try {
                  sucursales = JSON.parse(res);
                } catch (err) {
                  console.error('Error al parsear respuesta de sucursales:', err);
                  return;
                }
              } else if (Array.isArray(res)) {
                sucursales = res;
              } else if (res && res.data) {
                // En caso venga como { data: [...] }
                sucursales = res.data;
              }

              sucursales.forEach(sucursal => {
                buttons.push({
                  text: 'Sucursal '+sucursal.id_sucursal,
                  className: 'dropdown-item',
                  action: function () {
                    tabla.column(4).search(sucursal.nombre_comercial, true, false).draw();
                  }
                });
              });
            },
            error: function () {
              console.error("Error al cargar sucursales dinámicamente");
            }
          });

          return buttons;
        }()
      });
    }
        

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
            className: 'dropdown-item ',
            action: function () {
              tabla.column(esAdmin ? 5 : 4).search('^1$', true, false).draw();            
            }
          },
          {
            text: 'Inactivos',
            className: 'dropdown-item ',
            action: function () {
              tabla.column(esAdmin ? 5 : 4).search('^2$', true, false).draw();
            }
          },
          {
            text: 'Todos',
            className: 'dropdown-item ',
            action: function () {
              tabla.column(esAdmin ? 5 : 4).search('').draw();
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
            exportOptions: { 
              columns: esAdmin ? [1,2,3,4,5] : [1,2,3,4],
              modifier: {
                selected: true // Solo exportar seleccionados
              },
              format: {
                body: function (data, row, column, node) {
                  if (column === 1) {
                    const match = data.match(/<h6[^>]*>(.*?)<\/h6>/);
                    return match ? match[1].trim() : data;
                  }
                  if (column === esAdmin ? 5 : 4) {
                    const div = document.createElement('div');
                    div.innerHTML = data;
                    return div.textContent || div.innerText || '';
                  }
                  return data;
                }
              }
            }
          },
          {
            extend: "csv",
            text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-file me-1"></i>Csv</span>`,
            className: "dropdown-item",
            exportOptions: { 
              columns: esAdmin ? [1,2,3,4,5] : [1,2,3,4],
              modifier: {
                selected: true // Solo exportar seleccionados
              },
              format: {
                body: function (data, row, column, node) {
                  if (column === 1) {
                    const match = data.match(/<h6[^>]*>(.*?)<\/h6>/);
                    return match ? match[1].trim() : data;
                  }
                  if (column === esAdmin ? 5 : 4) {
                    const div = document.createElement('div');
                    div.innerHTML = data;
                    return div.textContent || div.innerText || '';
                  }
                  return data;
                }
              }
            }
          },
          {
            extend: "excel",
            text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-export me-1"></i>Excel</span>`,
            className: "dropdown-item",
            exportOptions: { 
              columns: esAdmin ? [1,2,3,4,5] : [1,2,3,4],
              modifier: {
                selected: true // Solo exportar seleccionados
              },
              format: {
                body: function (data, row, column, node) {
                  if (column === 1) {
                    const match = data.match(/<h6[^>]*>(.*?)<\/h6>/);
                    return match ? match[1].trim() : data;
                  }
                  if (column === esAdmin ? 5 : 4) {
                    const div = document.createElement('div');
                    div.innerHTML = data;
                    return div.textContent || div.innerText || '';
                  }
                  return data;
                }
              }
            }
          },
          {
            extend: "pdf",
            text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-pdf me-1"></i>Pdf</span>`,
            className: "dropdown-item",
            exportOptions: { 
              columns: esAdmin ? [1,2,3,4,5] : [1,2,3,4],
              modifier: {
                selected: true // Solo exportar seleccionados
              },
              format: {
                body: function (data, row, column, node) {
                  if (column === 1) {
                    const match = data.match(/<h6[^>]*>(.*?)<\/h6>/);
                    return match ? match[1].trim() : data;
                  }
                  if (column === esAdmin ? 5 : 4) {
                    const div = document.createElement('div');
                    div.innerHTML = data;
                    return div.textContent || div.innerText || '';
                  }
                  return data;
                }
              }
            }
          },
          {
            extend: "copy",
            text: `<i class="icon-base bx bx-copy me-1"></i>Copy`,
            className: "dropdown-item",
            exportOptions: { 
              columns: esAdmin ? [1,2,3,4,5] : [1,2,3,4],
              modifier: {
                selected: true // Solo exportar seleccionados
              },
              format: {
                body: function (data, row, column, node) {
                  if (column === 1) {
                    const match = data.match(/<h6[^>]*>(.*?)<\/h6>/);
                    return match ? match[1].trim() : data;
                  }
                  if (column === esAdmin ? 5 : 4) {
                    const div = document.createElement('div');
                    div.innerHTML = data;
                    return div.textContent || div.innerText || '';
                  }
                  return data;
                }
              }
            }
          }
        ]
      });
    }

    if (tienePermisoVista('registrar')) {
      botonesTop.push({
        text: '<i class="bx bx-plus"></i><span class="d-none d-sm-inline-block">Registrar</span>',
        className: "add-new btn btn-primary",
        attr: {
          "data-bs-toggle": "modal",//offcanvas
          "data-bs-target": "#modalRegistrarUsuario"//#offcanvasEcommerceCategoryList
        }
      });
    }

    //Manejo de llamado
    const columnas = [
      { data: "id_usuario" }, // 0 - ID para checkbox
      {
        data: {
          "nombre_usuario": "nombre_usuario",
          "ini_nombre": "ini_nombre",
          "activo": "activo"
        }
      }, // 1 - Usuario
      { data: "correo" }, // 2 - Correo
      { data: "rol" }     // 3 - Rol
    ];
    // Insertar "Sucursal" si es admin
    if (esAdmin) {
      columnas.push({ data: "sucursal" }); // 4
    }
    columnas.push(
      { data: "activo" },       // Estado 5 o 4
      { data: "id_usuario" }    // Acciones 6 o 5
    );

    //Manejo de columnas llamadas
    const columnDefs = [
      {
        targets: 0,
        className: "text-center",
        orderable: false,
        searchable: false,
        checkboxes: {
          selectRow: true,
          selectAllRender: '<input type="checkbox" class="form-check-input select-all" id="selectAll">'
        },
        render: () => '<input type="checkbox" class="dt-checkboxes form-check-input">'        
      },
      {
        targets: 1,
        className: "text-center",
        render: (data) => {
          const estado = data.activo === 1 ? 'avatar-online' : 'avatar-busy';
          const color = data.activo === 1 ? 'bg-label-success' : 'bg-label-danger';
          return `
            <div class="d-flex justify-content-start align-items-center text-nowrap">
              <div class="avatar-wrapper">
                <div class="avatar me-2 ${estado}">
                  <span class="avatar-initial rounded-circle ${color}">${data.ini_nombre}</span>
                </div>
              </div>
              <div class="d-flex flex-column">
                <h6 class="text-body mb-0">${data.nombre_usuario}</h6>
              </div>
            </div>`;
        }
      },
      {
        targets: 2,
        className: "text-start",
        orderable: false        
      },
      {
        targets: 3,
        className: "text-start",
        orderable: false        
      }
    ];
    // Si es admin, target 4 es "Sucursal", 5 es "Estado", 6 es "Acciones"
    if (esAdmin) {
      columnDefs.push({
        targets: 4,
        className: "text-center",
        orderable: false  
      });

      

      columnDefs.push({
        targets: 5,
        className: "text-center",
        render: (data, type) => {
          return type === 'display'
            ? (data == 1
                ? '<span class="badge bg-label-success">Activo</span>'
                : '<span class="badge bg-label-danger">Inactivo</span>')
            : data;
        }
      });

      columnDefs.push({
        targets: 6,
        className: "text-center",
        orderable: false,
        searchable: false,
        render: (id) => renderAcciones(id)
      });

    } else {
      // Si NO es admin, target 4 es "Estado", 5 es "Acciones"
      columnDefs.push({
        targets: 4,
        className: "text-center",
        render: (data, type) => {
          return type === 'display'
            ? (data == 1
                ? '<span class="badge bg-label-success">Activo</span>'
                : '<span class="badge bg-label-danger">Inactivo</span>')
            : data;
        }
      });

      columnDefs.push({
        targets: 5,
        className: "text-center",
        orderable: false,
        searchable: false,
        render: (id) => renderAcciones(id)
      });
    }

    // Función para renderizar botones de acción
    function renderAcciones(id) {
      const idActual = window.USUARIO_ACTUAL_ID || 0;
      let botones = '';

      if (tienePermisoVista('visualizar')) {
        botones += `<button class="btn btn-sm btn-icon btn-info" title="Ver" data-id="${id}"><i class="bx bx-show-alt"></i></button>`;
      }

      let deshabilitarEditar = !tienePermisoVista('editar') || parseInt(id) === parseInt(idActual) || parseInt(id) === 1;
      botones += `<button class="btn btn-sm btn-icon ${deshabilitarEditar ? 'btn-secondary' : 'btn-warning'}" title="Editar" data-id="${id}" ${deshabilitarEditar ? 'disabled' : ''}><i class="bx bx-edit-alt"></i></button>`;
      

      let deshabilitarEliminar = !tienePermisoVista('eliminar') || parseInt(id) === parseInt(idActual) || parseInt(id) === 1;
      botones += `<button class="btn btn-sm btn-icon ${deshabilitarEliminar ? 'btn-secondary' : 'btn-danger'}" title="Eliminar" data-id="${id}" ${deshabilitarEliminar ? 'disabled' : ''}><i class="bx bx-git-commit"></i></button>`;

      return `<div class="d-flex justify-content-center gap-1">${botones}</div>`;
    }

    const tabla = new DataTable(dtUsuarios, {
      ajax: assetsPath + "usuarioAjax/listar",
      columns: columnas,
      columnDefs: columnDefs,
      select: {
        style: "multi",
        selector: "td:first-child input[type='checkbox']",
        selectAll: '<input type="checkbox" class="form-check-input select-all" id="selectAll">',
        blurable: true
      },
      order: [[esAdmin ? 6 : 5, "asc"]],
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
        emptyTable: "No se encontraron usuarios registrados",
        paginate: {
          next: '<i class="bx bx-chevron-right scaleX-n1-rtl icon-18px"></i>',
          previous: '<i class="bx bx-chevron-left scaleX-n1-rtl icon-18px"></i>'
        }
      },
      responsive: {
        details: {
          display: DataTable.Responsive.display.modal({
            header: row => 'Detalles de ' + row.data().nombre_usuario
          }),
          type: "column",
          renderer: DataTable.Responsive.renderer.listHiddenNodes()
        }
      }
    });

    // Manejar el evento de selección/deselección de todos
    $('#selectAll').on('click', function() {
      const isChecked = this.checked;
      tabla.rows().nodes().to$().find('input[type="checkbox"]').prop('checked', isChecked);
      
      if (isChecked) {
          tabla.rows().select();
      } else {
          tabla.rows().deselect();
      }
    });
  // Actualizar el estado del checkbox se seleccionan/deseleccionan elementos individualmente
  tabla.on('select deselect', function() {
      const allSelected = tabla.rows({ selected: true }).count() === tabla.rows().count();
      $('#selectAll').prop('checked', allSelected);
  });

    /* Filtro de estado por defecto = Activo */
    tabla.column(esAdmin ? 5 : 4).search('^1$', true, false).draw();
    
    /* PARA FILTROS Y BUSQUEDAD*/
    $(document).on("input", "#usuario-filtro-valor", function () {
      const campo = $("#usuario-filtro-campo").val();
      const valor = $(this).val().trim();

      let colIndex = -1;
      switch (campo) {
        case "nombre_usuario":
          colIndex = 1;
          break;
        case "correo":
          colIndex = 2;
          break;
        case "rol":
          colIndex = 3;
          break;
      }

      if (colIndex >= 0) {
        tabla.columns().search('');
        tabla.column(colIndex).search(valor).draw();
      }
    });

    //Filtro

    // Resetear filtro personalizado
    $(document).on("click", "#btnResetUsuario", function () {
      $("#usuario-filtro-valor").val("");
      tabla.columns().search('');
      tabla.column(esAdmin ? 5 : 4).search('^1$', true, false).draw();
    });
  }
  // End DataTable


/*INICIOR: Faltpickr (SETEAR FECHA Y HORA)*/
  function initFlatpickr(selector, wrapperSelector) {
    const now = new Date();
    now.setSeconds(0, 0); // sin milisegundos

    flatpickr(selector, {
      enableTime: true,
      dateFormat: "Y-m-d H:i",
      time_24hr: true,
      minuteIncrement: 1,  
      minDate: now,
      defaultDate: null,
      onOpen: function (selectedDates, dateStr, instance) {
        if (!instance.input.value) {
          instance.setDate(new Date(), true); // solo si aún está vacío
        }
      },
      onReady: function(selectedDates, dateStr, instance) {
        instance._input.readOnly = true; // evita entrada manual
      }
    });
  }
  $('#reg-fecha-limite, #edit-fecha-limite').on('keydown', function (e) {
    e.preventDefault(); // bloquear escritura
  });
/* FIN: Faltpickr (SETEAR FECHA Y HORA)*/

/* ==== INICIO: VALIDARCIONES CAMPOS ==== */
  const soloNumeros = (e) => {
    const key = e.key;
    if (!/^\d$/.test(key) && key !== 'Backspace' && key !== 'Delete' && key !== 'ArrowLeft' && key !== 'ArrowRight' && key !== 'Tab') {
      e.preventDefault();
    }
  };

  // Bloquear la tecla espacio
  $('#reg-usuario, #edit-usuario, #reg-correo, #edit-correo').on('keydown', function (e) {
    if (e.key === ' ') {
      e.preventDefault();
    }
  });

  // $('#reg-dni, #reg-telefono, #edit-dni, #edit-telefono').on('keydown', soloNumeros);  
  document.getElementById('reg-dni').addEventListener('keydown', soloNumeros);
  document.getElementById('reg-telefono').addEventListener('keydown', soloNumeros);
  document.getElementById('edit-dni').addEventListener('keydown', soloNumeros);
  document.getElementById('edit-telefono').addEventListener('keydown', soloNumeros);
/* ==== FIN: VALIDARCIONES CAMPOS ==== */



/* ---- INICIO:  EVENTO VISUALIZAR ---- */
  $(document).on('click', '.btn-info[data-id]', function () {
    const userId = $(this).data('id');    

    fetch(`${assetsPath}usuarioAjax/obtener?id=${userId}`)
      .then(res => res.json())
      .then(data => {
        if (data && data.id_usuario) {
          //Rellenar Campos
          $('#detalle-nombre').val(data.nombre);
          $('#detalle-apellido').val(data.apellido);
          $('#detalle-dni').val(data.numero_dni);
          $('#detalle-telefono').val(data.telefono);
          $('#detalle-direccion').val(data.direccion);          
          $('#detalle-correo').val(data.correo);
          $('#detalle-usuario').val(data.nombre_usuario);
          $('#detalle-rol').val(data.nombre_rol);
          $('#detalle-sucursal').val(data.nombre_sucursal);
          $('#detalle-estado').val(data.activo === 1 ? 'Activo' : 'Inactivo');
          
          $('#detalle-departamento').val(data.departamento);
          $('#detalle-provincia').val(data.provincia);
          $('#detalle-distrito').val(data.distrito);
          if (data.fecha_limite) {
                  const partes = data.fecha_limite.split(' ');
                  const fecha = partes[0]; // 'YYYY-MM-DD'
                  const hora = partes[1].slice(0, 5); // 'HH:mm'
                  const fechaFormatted = `${fecha}T${hora}`;
                  $('#detalle-fecha-limite').val(fechaFormatted);
                } else {
                  $('#detalle-fecha-limite').val('');
                  $('#detalle-fecha-limite').attr('placeholder','Tiempo indefinido');
                }                  
          
          //Deshabilitar todos los elementos del formulario
          $('#modalDetalleUsuario').find('input, select, textarea').prop('disabled', true);

          const modal = new bootstrap.Modal(document.getElementById('modalDetalleUsuario'));
          modal.show();
        } else {
          alert('No se pudo obtener la información del usuario.');
        }
      })
      .catch(err => {
        console.error('Error al obtener detalle de usuario:', err);
      });
  });
/* ---- FIN: EVENTO VISUALIZAR ---- */



/* ---- INICIO: EVENTO EDITAR ---- */
  let correoEditDisponible = null;
  let usuarioEditDisponible = null;
  let originalEditData = {};
  let isSubmittingEditUsuario = false;

  const contenedoreditSucursal = $('#edit-container-sucursal');
  const selecteditSucursal = $('#edit-sucursal');

  //Abrir Modal y Cargar Datos
  $(document).on('click', '.btn-warning[data-id]', function () {
    const userId = $(this).data('id');

    // Establecer min con hora local actual
    initFlatpickr('#edit-fecha-limite');

    // Asignar eventos dinámicos una sola vez
    $('#departamento').on('change', function () {
        const dep = $(this).val();
        $('#provincia').prop('disabled', !dep).empty().append('<option></option>');
        $('#distrito').prop('disabled', true).empty().append('<option></option>');

        if (!dep) return;

        fetch(`${assetsPath}perfilAjax/listar_provincias?departamento=${encodeURIComponent(dep)}`)
        .then(res => res.json())
        .then(provincias => {
            provincias.forEach(prov => {
            $('#provincia').append(`<option value="${prov.provincia}">${prov.provincia}</option>`);
            });
        });
    });

    $('#provincia').on('change', function () {
        const dep = $('#departamento').val();
        const prov = $(this).val();
        $('#distrito').prop('disabled', !prov).empty().append('<option></option>');

        if (!prov) return;

        fetch(`${assetsPath}perfilAjax/listar_distritos?departamento=${encodeURIComponent(dep)}&provincia=${encodeURIComponent(prov)}`)
        .then(res => res.json())
        .then(distritos => {
            distritos.forEach(dist => {
            $('#distrito').append(`<option value="${dist.distrito}">${dist.distrito}</option>`);
            });
        });
    });

    // Obtener datos
    fetch(`${assetsPath}usuarioAjax/obtener?id=${userId}`)
      .then(res => res.json())
      .then(data => {
        if (!data || !data.id_usuario) {
          alert("No se encontró la información del usuario.");
          return;
        }

        // Obtener informacion de los roles
        fetch(`${assetsPath}usuarioAjax/listar_roles`)
          .then(res => res.json())
          .then(roles => {
            console.log("Roles obtenidos:", roles);
            const selectRol = $('#edit-rol');
            selectRol.empty();
            roles.forEach(rol => {
              const selected = rol.id_rol == data.id_rol ? 'selected' : '';
              selectRol.append(`<option value="${rol.id_rol}" ${selected}>${rol.nombre_rol}</option>`);
            });

            //Sucursal desplegable según rol
            selectRol.val(data.id_rol).trigger('change');
            const idRolEdit = parseInt($('#edit-rol').val());
            const idSucursalEdit = $('#edit-sucursal').val();

            $('#edit-rol').on('change', function () {
              const idRolEdit = parseInt(this.value);

              if (!isNaN(idRolEdit) && idRolEdit !== 1 && idRolEdit !== 2) {
                contenedoreditSucursal.show();
              } else {
                contenedoreditSucursal.hide();
                selecteditSucursal.val('').trigger('change'); // reset valor select2
              }
            });

            fetch(`${assetsPath}usuarioAjax/listar_sucursales`)
              .then(res => res.json())
              .then(sucursales => {
                console.log("Sucursales Obtenidas:", sucursales);
                const selectSucursal = $('#edit-sucursal');
                selectSucursal.empty();
                sucursales.forEach(sucursal => {
                  const select2 = sucursal.id_sucursal == data.id_sucursal ? 'selected' : '';
                  selectSucursal.append(`<option value="${sucursal.id_sucursal}" ${select2}>${sucursal.nombre_comercial}</option>`);
                });

                fetch(`${assetsPath}perfilAjax/listar_departamentos`)
                  .then(res => res.json())
                  .then(departamentos => {
                    const selectDep = $('#departamento');
                    selectDep.empty().append('<option></option>');
                    departamentos.forEach(dep => {
                        const selected = dep.departamento == data.departamento ? 'selected' : '';
                        selectDep.append(`<option value="${dep.departamento}" ${selected}>${dep.departamento}</option>`);
                    });

                    if (!data.departamento) return;

                    fetch(`${assetsPath}perfilAjax/listar_provincias?departamento=${encodeURIComponent(data.departamento)}`)
                        .then(res => res.json())
                        .then(provincias => {
                        const selectPro = $('#provincia');
                        selectPro.empty().append('<option></option>');
                        provincias.forEach(prov => {
                            const selected = prov.provincia == data.provincia ? 'selected' : '';
                            selectPro.append(`<option value="${prov.provincia}" ${selected}>${prov.provincia}</option>`);
                        });

                        if (!data.provincia) return;

                        fetch(`${assetsPath}perfilAjax/listar_distritos?departamento=${encodeURIComponent(data.departamento)}&provincia=${encodeURIComponent(data.provincia)}`)
                            .then(res => res.json())
                            .then(distritos => {
                                const selectDis = $('#distrito');
                                selectDis.empty().append('<option></option>');
                                distritos.forEach(dist => {
                                    const selected = dist.distrito == data.distrito ? 'selected' : '';
                                    selectDis.append(`<option value="${dist.distrito}" ${selected}>${dist.distrito}</option>`);
                                });
                                
                                if (data.provincia) $('#provincia').prop('disabled', false);
                                if (data.distrito) $('#distrito').prop('disabled', false);
                                
                                // Insertar datos al fomrulario
                                $('#edit-id_usuario').val(data.id_usuario);
                                $('#edit-nombre').val(data.nombre);
                                $('#edit-apellido').val(data.apellido);
                                $('#edit-dni').val(data.numero_dni);
                                $('#edit-telefono').val(data.telefono);
                                $('#edit-direccion').val(data.direccion);
                                $('#edit-correo').val(data.correo);
                                $('#edit-usuario').val(data.nombre_usuario);            
                                
                                //$('#edit-sucursal').val(data.nombre_sucursal);

                                if (data.fecha_limite) {
                                  const partes = data.fecha_limite.split(' ');
                                  const fecha = partes[0]; // 'YYYY-MM-DD'
                                  const hora = partes[1].slice(0, 5); // 'HH:mm'
                                  const fechaFormatted = `${fecha}T${hora}`;
                                  $('#edit-fecha-limite').val(fechaFormatted);
                                } else {
                                  $('#edit-fecha-limite').val('');
                                  $('#edit-fecha-limite').attr('placeholder','Tiempo indefinido');
                                }

                                originalEditData = {
                                  nombre: data.nombre,
                                  apellido: data.apellido,
                                  dni: data.numero_dni,
                                  telefono: data.telefono,
                                  direccion: data.direccion,
                                  departamento: data.departamento,
                                  provincia: data.provincia,
                                  distrito: data.distrito, 
                                  correo: data.correo,
                                  usuario: data.nombre_usuario,
                                  id_rol: String(data.id_rol),
                                  id_sucursal: String(data.id_sucursal),
                                  fecha_limite: data.fecha_limite
                                };
                                                /*
                                // Verificar si el botón de correo debe estar deshabilitado
                                const correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.correo);
                                $('#verificarEditcorreo').prop('disabled', !correoValido);
                                */
                                //Deshabilitar Sucursal
                                //$('#edit-sucursal').prop('disabled', true);

                                // Mostrar modal
                                const modal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
                                modal.show();

                            });
                        });
                    });


              })                
              .catch(err => {
                console.error("Error al obtener sucursales:", err);
                alert("No se pudieron cargar las sucursales para edición.");
              });                                
          })
          .catch(err => {
            console.error("Error al obtener roles:", err);
            alert("No se pudieron cargar los roles para edición.");
          });
      })
      .catch(err => {
        console.error("Error al obtener usuario:", err);
        alert("Error al obtener datos del usuario.");
      });
  });

  // Verificar usuario
  function verificarUsuarioExistenteEdit() {
    const usuario = $('#edit-usuario').val().trim();
    const idUsuario = $('#edit-id_usuario').val();

    if (!usuario) {
      Toastify({
        text: 'Ingrese un usuario',
        duration: 3000,
        style: { background: '#ffc107', color: '#000' }
      }).showToast();
      return Promise.resolve(false);
    };

    return fetch(`${assetsPath}usuarioAjax/validar_usuario?usuario=${encodeURIComponent(usuario)}&id_usuario=${encodeURIComponent(idUsuario)}`)
    .then (res => {
      if (!res.ok) throw new Error('Error de red en validad registros');
      return res.json ();
    })
    .then (dataDB => {      
      usuarioEditDisponible = !dataDB.valid;
      Toastify({
        text: usuarioEditDisponible ? "Usuario disponible ✅" : "Usuario ya registrado ❌",
        duration: 3000,
        style: { background: usuarioEditDisponible ? "#28a745" : "#dc3545" }
      }).showToast();          
    })
    .catch(() => {
      Toastify({
        text: "Error en la verificación del usuario",
        duration: 3000,
        style: { background: "#dc3545" }
      }).showToast();
    });  
  }
  //Verificar correo
  function verificarCorreoExistenteEdit() {
    const correo = $('#edit-correo').val().trim();
    const idUsuario = $('#edit-id_usuario').val();

    if (!correo) {
      Toastify({
        text: 'Ingrese un correo',
        duration: 3000,
        style: { background: '#ffc107', color: '#000' }
      }).showToast();
      return Promise.resolve(false);
    };

    //Verificar existencia en la BD
    return fetch(`${assetsPath}usuarioAjax/validar_correo1?correo=${encodeURIComponent(correo)}&id_usuario=${encodeURIComponent(idUsuario)}`)
      .then(res => {
        if (!res.ok) throw new Error('Error de red en validar registros');
        return res.json();
      })
      .then(dataDB => {
        if (dataDB.valid) {
          correoEditDisponible = false;
          Toastify({
            text: 'Correo ya registrado ❌',
            duration: 3000,
            style: { background: '#dc3545' }
          }).showToast();
          return false; // Detener aquí   
        }

        //Consultar Correo real
        return fetch(`${assetsPath}consultaAjax/validar_correo_real1?correo=${encodeURIComponent(correo)}`)
          .then(res => {
            if (!res.ok) throw new Error('Error de red en validar existencia de correo');
            return res.json();
          })
          .then(data => {
            Toastify({
              text: data.message,
              duration: 3000,
              style: { background: data.success && data.valid ? '#28a745' : '#dc3545' }
            }).showToast();

            correoEditDisponible = data.success && data.valid;
            return correoEditDisponible;
          });        
      })
      .catch(err => {
        console.error('Error durante la validación del correo:', err);
        correoEditDisponible = false;
        Toastify({
          text: "Error validando correo ❌",
          duration: 3000,
          style: { background: '#dc3545' }
        }).showToast();
        return false;
      });
  }

  // habilitar botón Correo
  $('#edit-correo').on('input', function () {
    const correo = $(this).val().trim();
    const correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo);
    $('#verificarEditcorreo').prop('disabled', !correoValido);
  });
  // habilitar botón usuario
  $('#edit-usuario').on('input', function () {
    const usuario = $(this).val().trim();
    const usuarioValido = usuario.length >= 4;
    $('#verificarEditusuario').prop('disabled', !usuarioValido);
  });

  //Botón de Verificar Usuario
  $('#verificarEditusuario').on('click', function(e) {
    e.preventDefault();
    verificarUsuarioExistenteEdit();
  });
  //Botón de Verificar Correo
  $('#verificarEditcorreo').on('click', function(e) {
    e.preventDefault();
    verificarCorreoExistenteEdit();
  });

  //Detectar Cambios
  function hasFormChanged() {
    // Debug current vs original:
    return (
      $('#edit-nombre').val().trim() !== (originalEditData.nombre || '').trim() ||
      $('#edit-apellido').val().trim() !== (originalEditData.apellido || '').trim() ||
      $('#edit-dni').val().trim() !== (originalEditData.dni || '').trim() ||
      $('#edit-telefono').val().trim() !== (originalEditData.telefono || '').trim() ||
      $('#edit-direccion').val().trim() !== (originalEditData.direccion || '').trim() ||
      $('#departamento').val() !== (originalEditData.departamento || '').trim() ||
      $('#provincia').val() !== (originalEditData.provincia || '').trim() ||
      $('#distrito').val() !== (originalEditData.distrito || '').trim() ||
      $('#edit-correo').val().trim() !== (originalEditData.correo || '').trim() ||
      $('#edit-usuario').val().trim() !== (originalEditData.usuario || '').trim() ||
      $('#edit-rol').val().toString() !== (originalEditData.id_rol) ||
      $('#edit-sucursal').val().toString() !== (originalEditData.id_sucursal) ||
      $('#edit-fecha-limite').val().trim() !== (originalEditData.fecha_limite ? new Date(originalEditData.fecha_limite).toISOString().slice(0, 16) : '')
    );
  }

  //Enviar formulario
  $('#formEditarUsuario').on('submit', async function (e) {
    e.preventDefault();

    if (!this.checkValidity()) {
      this.reportValidity();
      return;
    }

    if (!hasFormChanged()) {
      Toastify({
        text: "No se realizaron cambios ⚠️",
        duration: 3000,
        style: { background: "#ffc107", color: "#000" }
      }).showToast();
      return; // No enviar si no hay cambios
    }

    if (correoEditDisponible === false) {
      Toastify({ text: 'Correo no válido ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }
    if (usuarioEditDisponible === false) {
      Toastify({ text: 'Usuario no válido ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }
    
    enviarEdicionUsuario();
  });

  //Función de envío
  function enviarEdicionUsuario() {
    isSubmittingEditUsuario = true;
    const form2 = document.getElementById('formEditarUsuario');
    const formData = new FormData(form2);

    const submitBtn = $(form2).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
    
    fetch(`${assetsPath}usuarioAjax/actualizar`, {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {          
          Toastify({
            text: "Usuario actualizado correctamente ✅",
            duration: 3000,
            style: { background: "#28a745" }
          }).showToast();

          bootstrap.Modal.getInstance(document.getElementById('modalEditarUsuario')).hide();          
          $('.datatables-category-list').DataTable().ajax.reload(null, false);
        } else {
          Toastify({
            text: "Error al actualizar usuario ⚠️",
            duration: 3000,
            style: { background: "#dc3545" }
          }).showToast();
        }
      })
      .catch(err => {
        console.error("Error en actualización:", err);
        Toastify({
          text: "Error al actualizar usuario ❌",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      })
      .finally(() => {
        submitBtn.prop('disabled', false).html('Guardar cambios');
        isSubmittingEditUsuario = false;
      });
  }

  // Confirmación antes de cerrar modal si hay cambios
  $('#modalEditarUsuario').on('show.bs.modal', function () {
    $(this).off('hide.bs.modal').on('hide.bs.modal', function (e) {
      if (!isSubmittingEditUsuario && hasFormChanged()) {
        e.preventDefault(); // detiene cierre
        const salir = confirm("Hay cambios sin guardar. ¿Seguro que deseas salir y perder los cambios?");

        if (salir) {
          $(this).off('hide.bs.modal'); // desengancha momentáneamente para permitir cerrar
          $(this).modal('hide');        // cierra realmente
        }
      }
    });
  });
/* ---- FIN: EVENTO EDITAR ---- */



/* ---- INICIO: EVENTO REGISTRAR ---- */
$(function() {  
  let dniDisponible = null;
  let usuarioDisponible = null;
  let correoDisponible = null;
  let isSubmittingRegUsuario = false;

  const contenedorSucursal = $('#container-sucursal');
  const selectSucursal = $('#reg-sucursal');

  //Listar roles
  $('#modalRegistrarUsuario').on('show.bs.modal', function () {
    usuarioDisponible = null;
    correoDisponible = null;

    // establecer mínimo de fecha actual al abrir el modal    
    initFlatpickr('#reg-fecha-limite');

    // Cargar departamentos
    fetch(`${assetsPath}perfilAjax/listar_departamentos`)
      .then(res => res.json())
      .then(departamentos => {
        const selectDep = $('#reg-departamento');
        selectDep.empty().append('<option></option>');
        departamentos.forEach(dep => {
          selectDep.append(`<option value="${dep.departamento}">${dep.departamento}</option>`);
        });
      })
      .catch(err => {
        console.error("Error al cargar departamentos:", err);
        Toastify({
          text: "Error al cargar departamentos",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      });

    // Cargar roles
    const selectRol = $('#reg-rol');
    selectRol.empty();
    selectRol.append('<option value="">Seleccione un rol</option>');

    fetch(`${assetsPath}usuarioAjax/listar_roles`)
      .then(res => res.json())
      .then(roles => {
        roles.forEach(rol => {
          selectRol.append(`<option value="${rol.id_rol}">${rol.nombre_rol}</option>`);
        });
      })
      .catch(() => {
        Toastify({
          text: "Error al cargar los roles para registro ⚠️",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      });
  });

  // Eventos para el ubigeo
  $('#reg-departamento').on('change', function () {
    const dep = $(this).val();
    $('#reg-provincia').prop('disabled', !dep).empty().append('<option></option>');
    $('#reg-distrito').prop('disabled', true).empty().append('<option></option>');

    if (!dep) return;

    fetch(`${assetsPath}perfilAjax/listar_provincias?departamento=${encodeURIComponent(dep)}`)
      .then(res => res.json())
      .then(provincias => {
        provincias.forEach(prov => {
          $('#reg-provincia').append(`<option value="${prov.provincia}">${prov.provincia}</option>`);
        });
      });
  });

  $('#reg-provincia').on('change', function () {
    const dep = $('#reg-departamento').val();
    const prov = $(this).val();
    $('#reg-distrito').prop('disabled', !prov).empty().append('<option></option>');

    if (!prov) return;

    fetch(`${assetsPath}perfilAjax/listar_distritos?departamento=${encodeURIComponent(dep)}&provincia=${encodeURIComponent(prov)}`)
      .then(res => res.json())
      .then(distritos => {
        distritos.forEach(dist => {
          $('#reg-distrito').append(`<option value="${dist.distrito}">${dist.distrito}</option>`);
        });
      });
  });


  //Sucursal desplegable según rol
  const idRol = parseInt($('#reg-rol').val());
  const idSucursal = $('#reg-sucursal').val();

  $('#reg-rol').on('change', function () {
    const idRol = parseInt(this.value);

    if (!isNaN(idRol) && idRol !== 1 && idRol !== 2) {
      contenedorSucursal.show();
    } else {
      contenedorSucursal.hide();
      selectSucursal.val('').trigger('change'); // reset valor select2
    }
  });

  // Habilitar Botón DNI
  $(document).ready(function () {
    $('#buscar').prop('disabled', true);
  });
  $('#reg-dni').on('input', function () {
    const dni = $(this).val().trim();
    const dniValido = /^\d{8}$/.test(dni);
    $('#buscar').prop('disabled', !dniValido);
  });
  // Habilitar Botón Usuario
  $(document).ready(function () {
    $('#verificarcusuario').prop('disabled', true);
  });
  $('#reg-usuario').on('input', function () {
    const usuarioReg = $(this).val().trim();
    const usuarioRegValido = usuarioReg.length >= 4;
    $('#verificarcusuario').prop('disabled', !usuarioRegValido);
  });
  // Habilitar Botón correo
  $(document).ready(function () {
    $('#verificarcorreo').prop('disabled', true);
  });
  $('#reg-correo').on('input', function () {
    const correoReg = $(this).val().trim();
    const correoRegValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correoReg);
    $('#verificarcorreo').prop('disabled', !correoRegValido);
  });
  
  // Verificar DNI
  function verificarDNIExistenteReg() {
    const dni = $('#reg-dni').val().trim();

    if (!dni) {
      Toastify({
        text: 'Ingrese un número de DNI',
        duration: 3000,
        style: { background: '#ffc107', color: '#000' }
      }).showToast();
      return Promise.resolve(false);
    };

    //Verificar existencia en la BD
    return fetch(`${assetsPath}usuarioAjax/validar_registrodni?dni=${encodeURIComponent(dni)}`)
      .then(res => {
        if (!res.ok) throw new Error('Error de red en validar registros');
        return res.json();
      })
      .then(dataDB => {
        if (dataDB.valid) {
          dniDisponible = false;
          Toastify({
            text: 'Persona (DNI) ya registrada ❌',
            duration: 3000,
            style: { background: '#dc3545' }
          }).showToast();
          return false; // Detener aquí   
        }

        //Consultar DNI
        return fetch(`${assetsPath}consultaAjax/buscar_dni?dni=${dni}`)
          .then(res => {
            if (!res.ok) throw new Error('Error de red en consultar DNI');
            return res.json();
          })
          .then(data => {
		if (data && data.nombres && data.apellidoPaterno && data.apellidoMaterno) {
          document.getElementById('reg-nombre').value = data.nombres;
          document.getElementById('reg-apellido').value = `${data.apellidoPaterno} ${data.apellidoMaterno}`;
          Toastify({
            text: "Datos cargados correctamente ✅",
            duration: 3000,
            style: { background: "#28a745" }
          }).showToast();
        } else {
          Toastify({
            text: "No se encontraron datos del DNI ❌",
            duration: 3000,
            style: { background: "#dc3545" }
          }).showToast();
        }
            dniDisponible = data.success && data.valid;
            return dniDisponible;
          });        
      })
      .catch(err => {
        console.error('Error durante la validación del DNI:', err);
        dniDisponible = false;
        Toastify({
          text: "Error validando DNI ❌",
          duration: 3000,
          style: { background: '#dc3545' }
        }).showToast();
        return false;
      });
  }  
  // Verificar correo
  function verificarCorreoExistenteReg() {
    const correo = $('#reg-correo').val().trim();

    if (!correo) {
      Toastify({
        text: 'Ingrese un correo',
        duration: 3000,
        style: { background: '#ffc107', color: '#000' }
      }).showToast();
      return Promise.resolve(false);
    };

    //Verificar existencia en la BD
    return fetch(`${assetsPath}usuarioAjax/validar_correo1?correo=${encodeURIComponent(correo)}`)
      .then(res => {
        if (!res.ok) throw new Error('Error de red en validar registros');
        return res.json();
      })
      .then(dataDB => {
        if (dataDB.valid) {
          correoDisponible = false;
          Toastify({
            text: 'Correo ya registrado ❌',
            duration: 3000,
            style: { background: '#dc3545' }
          }).showToast();
          return false; // Detener aquí   
        }

        //Consultar Correo real
        return fetch(`${assetsPath}consultaAjax/validar_correo_real1?correo=${encodeURIComponent(correo)}`)
          .then(res => {
            if (!res.ok) throw new Error('Error de red en validar existencia del correo');
            return res.json();
          })
          .then(data => {
            Toastify({
              text: data.message,
              duration: 3000,
              style: { background: data.success && data.valid ? '#28a745' : '#dc3545' }
            }).showToast();

            correoDisponible = data.success && data.valid;
            return correoDisponible;
          });        
      })
      .catch(err => {
        console.error('Error durante la validación del correo:', err);
        correoDisponible = false;
        Toastify({
          text: "Error validando correo ❌",
          duration: 3000,
          style: { background: '#dc3545' }
        }).showToast();
        return false;
      });
  }
  // Verificar usuario
  function verificarUsuarioExistenteReg() {
    const usuario = $('#reg-usuario').val().trim();

    if (!usuario) {
      Toastify({
        text: 'Ingrese un usuario',
        duration: 3000,
        style: { background: '#ffc107', color: '#000' }
      }).showToast();
      return Promise.resolve(false);
    };

    return fetch(`${assetsPath}usuarioAjax/validar_usuario?usuario=${encodeURIComponent(usuario)}`)
    .then (res => {
      if (!res.ok) throw new Error('Error de red en validad registros');
      return res.json();
    })
    .then (data => {      
      usuarioDisponible = !data.valid;
      Toastify({
        text: usuarioDisponible ? "Usuario disponible ✅" : "Usuario ya registrado ❌",
        duration: 3000,
        style: { background: usuarioDisponible ? "#28a745" : "#dc3545" }
      }).showToast();          
    })
    .catch(() => {
      Toastify({
        text: "Error en la verificación del usuario",
        duration: 3000,
        style: { background: "#dc3545" }
      }).showToast();
    });  
  }


  //Botón de Verificar DNI
  $('#buscar').on('click', function(e) {
    e.preventDefault();
    verificarDNIExistenteReg();
  });
  //Botón de Verificar Correo
  $('#verificarcorreo').on('click', function(e) {
    e.preventDefault();
    verificarCorreoExistenteReg();
  });
  //Botón de Verificar Usuario
  $('#verificarcusuario').on('click', function(e) {
    e.preventDefault();
    verificarUsuarioExistenteReg();
  });

  // Enviar formulario
  $('#formRegistrarUsuario').on('submit', async function (e) {
    e.preventDefault();

    const form = this;
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }    

    // Validar usuario, correo, dni y fecha
    if (usuarioDisponible === false) {
      Toastify({ text: 'Usuario ya registrado ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }
    if (correoDisponible === false) {
      Toastify({ text: 'Correo ya registrado ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }
    if (dniDisponible === false) {
      Toastify({ text: 'Persona (DNI) ya registrada ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }
        

    enviarRegistroUsuario(form);
  });

  //Función envío
  function enviarRegistroUsuario(form){
    isSubmittingRegUsuario = true;
    
    const formData = new FormData(form);

    const fechaLimite = formData.get('fecha_limite');
    if (fechaLimite && isNaN(Date.parse(fechaLimite))) {
      Toastify({ text: 'Fecha límite inválida ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }

    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

    fetch(`${assetsPath}usuarioAjax/registrar`, {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Toastify({ 
            text: 'Usuario registrado correctamente ✅', 
            duration: 3000,
            style: { background: '#28a745' } 
          }).showToast();
          bootstrap.Modal.getInstance(document.getElementById('modalRegistrarUsuario')).hide();
          $('.datatables-category-list').DataTable().ajax.reload(null, false);
          form.reset();

          // Resetear selects de ubicación
          $('#reg-departamento').val('').trigger('change');
          $('#reg-provincia').prop('disabled', true).val('').trigger('change');
          $('#reg-distrito').prop('disabled', true).val('').trigger('change');
        } else {
          Toastify({ 
            text: data.message || 'Error al registrar usuario ⚠️', 
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
        submitBtn.prop('disabled', false).html('Registrar');
        isSubmittingRegUsuario = false;
      });
  }

});
/* ---- FIN: EVENTO REEGISTRAR ---- */



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
            text: `Usuario "${deleteUserName}" actualizado correctamente ✅`,
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