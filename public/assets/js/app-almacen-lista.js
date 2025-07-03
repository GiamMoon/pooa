'use strict';

document.addEventListener("DOMContentLoaded", function () {
  const dtAlmacenes = document.querySelector(".datatables-category-list");
  const selects = $(".select2");
  // Inyectar permisos del usuario desde PHP
  const permisos = window.PERMISOS_USUARIO || [];

  // Helper para verificar si el usuario tiene un permiso específico en la vista actual
  const tienePermisoVista = (permisoEsperado) => {
    return permisos.some(p =>
      p.ruta === 'administracion/almacen' && p.permiso === permisoEsperado
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
  if (dtAlmacenes) {

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
          <i class="bx bx-reset"></i>
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
          { extend: "print", text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-printer me-1"></i>Print</span>`, className: "dropdown-item", exportOptions: { columns: [1,2,3,4], modifier: {selected: true},} },
          { extend: "csv",   text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-file me-1"></i>Csv</span>`,   className: "dropdown-item", exportOptions: { columns: [1,2,3,4], modifier: {selected: true}, } },
          { extend: "excel", text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-export me-1"></i>Excel</span>`, className: "dropdown-item", exportOptions: { columns: [1,2,3,4], modifier: {selected: true}, } },
          { extend: "pdf",   text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-pdf me-1"></i>Pdf</span>`,   className: "dropdown-item", exportOptions: { columns: [1,2,3,4], modifier: {selected: true}, } },
          { extend: "copy",  text: `<i class="icon-base bx bx-copy me-1"></i>Copy`, className: "dropdown-item", exportOptions: { columns: [1,2,3,4], modifier: {selected: true}, } }
        ]
      });
    }

    if (tienePermisoVista('registrar')) {
      botonesTop.push({
        text: '<i class="bx bx-plus"></i><span class="d-none d-sm-inline-block">Registrar</span>',
        className: "add-new btn btn-primary",
        attr: {
          "data-bs-toggle": "modal",
          "data-bs-target": "#modalRegistrarAlmacen"
        }
      });
    }

  const tabla = new DataTable(dtAlmacenes, {
    ajax: assetsPath + "AlmacenAjax/listar",
      columns: [
        { data: "id_almacen" },      //0
        { data: "nombre_comercial" },    //1
        { data: "direccion" },         //2
        { data: "telefono" },    //3
        { data: "activo" },         // 4
        { data: "id_almacen" }      // 5
      ],
      columnDefs: [
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
          className: "text-start"
        },
        {
          targets: 2,
          className: "text-start"
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

            let deshabilitarVer = !tienePermisoVista('visualizar') || parseInt(id) === 1 || parseInt(id) !== 1;
            botones += `<button class="btn btn-sm btn-icon ${deshabilitarVer ? 'btn-secondary' : 'btn-info'}" title="Ver" data-id="${id}" ${deshabilitarVer ? 'disabled' : ''}><i class="bx bx-show-alt"></i></button>`;

            let deshabilitarEditar = !tienePermisoVista('editar');
            botones += `<button class="btn btn-sm btn-icon ${deshabilitarEditar ? 'btn-secondary' : 'btn-warning'}" title="Editar" data-id="${id}" ${deshabilitarEditar ? 'disabled' : ''}><i class="bx bx-edit-alt"></i></button>`;

            let deshabilitarEliminar = !tienePermisoVista('eliminar');
            botones += `<button class="btn btn-sm btn-icon ${deshabilitarEliminar ? 'btn-secondary' : 'btn-danger'}" title="Eliminar" data-id="${id}" ${deshabilitarEliminar ? 'disabled' : ''}><i class="bx bx-git-commit"></i></button>`;

            return `<div class="d-flex justify-content-center gap-1">${botones}</div>`;
          }
        }
      ],
      select: {
        style: "multi",
        selector: "td:first-child input[type='checkbox']",
        selectAll: '<input type="checkbox" class="form-check-input select-all" id="selectAll">',
        blurable: true
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
            header: row => 'Detalles de ' + row.data().nombre_comercial
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

    const soloNumeros = (e) => {
        const key = e.key;
        if (!/^\d$/.test(key) && key !== 'Backspace' && key !== 'Delete' && key !== 'ArrowLeft' && key !== 'ArrowRight' && key !== 'Tab') {
        e.preventDefault();
        }
    };
    document.getElementById('reg-telefono').addEventListener('keydown', soloNumeros);
    //document.getElementById('edit-telefono').addEventListener('keydown', soloNumeros);

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
  let isSubmittingRegAlmacen = false;

  //Listar
  $('#modalRegistrarAlmacen').on('show.bs.modal', function () {
    
    fetch(`${assetsPath}usuarioAjax/listar_sucursales`)
      .then(res => res.json())              
      .then(sucursales => {
        console.log("Sucursales Obtenidas:", sucursales);
        const selectSucursal = $('#reg-sucursal');
        selectSucursal.empty().append('<option value="">Seleccione sucursal</option>');
        sucursales.forEach(sucursal => {          
          selectSucursal.append(`<option value="${sucursal.id_sucursal}">${sucursal.nombre_comercial}</option>`);
        });
      })
      .catch(err => {
        console.error("Error al cargar Sucursales:", err);
        Toastify({
          text: "Error al cargar Sucursales",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      }); 
        
    
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

  // Enviar formulario
  $('#formRegistrarAlmacen').on('submit', async function (e) {
    e.preventDefault();

    const form = this;
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }            

    enviarRegistroAlmacen(form);
  });

  //Función envío
  function enviarRegistroAlmacen(form){
    isSubmittingRegAlmacen = true;    
    const formData = new FormData(form);
    const submitBtn = $(form).find('button[type="submit"]');

    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

    fetch(`${assetsPath}almacenAjax/registrar`, {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Toastify({
            text: 'Almacén registrado correctamente ✅', 
            duration: 3000,
            style: { background: '#28a745' } 
          }).showToast();
          bootstrap.Modal.getInstance(document.getElementById('modalRegistrarAlmacen')).hide();
          $('.datatables-category-list').DataTable().ajax.reload(null, false);
          form.reset();

          // Resetear selects de ubicación
          $('#reg-departamento').val('').trigger('change');
          $('#reg-provincia').prop('disabled', true).val('').trigger('change');
          $('#reg-distrito').prop('disabled', true).val('').trigger('change');
        } else {
          Toastify({ 
            text: data.message || 'Error al registrar datos ⚠️', 
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
        isSubmittingRegAlmacen = false;
      });
  }

});
/* ---- FIN: EVENTO REEGISTRAR ---- */


/* ---- INICIO: EVENTO EDITAR ---- */  
  let originalEditData = {};
  let isSubmittingEditSucursal = false;  

  //Abrir Modal y Cargar Datos
  $(document).on('click', '.btn-warning[data-id]', function () {
    const sucursalID = $(this).data('id');

    // Asignar eventos dinámicos una sola vez
    $('#edit-departamento').on('change', function () {
        const dep = $(this).val();
        $('#edit-provincia').prop('disabled', !dep).empty().append('<option></option>');
        $('#edit-distrito').prop('disabled', true).empty().append('<option></option>');

        if (!dep) return;

        fetch(`${assetsPath}perfilAjax/listar_provincias?departamento=${encodeURIComponent(dep)}`)
        .then(res => res.json())
        .then(provincias => {
            provincias.forEach(prov => {
            $('#edit-provincia').append(`<option value="${prov.provincia}">${prov.provincia}</option>`);
            });
        });
    });

    $('#edit-provincia').on('change', function () {
        const dep = $('#edit-departamento').val();
        const prov = $(this).val();
        $('#edit-distrito').prop('disabled', !prov).empty().append('<option></option>');

        if (!prov) return;

        fetch(`${assetsPath}perfilAjax/listar_distritos?departamento=${encodeURIComponent(dep)}&provincia=${encodeURIComponent(prov)}`)
        .then(res => res.json())
        .then(distritos => {
            distritos.forEach(dist => {
            $('#edit-distrito').append(`<option value="${dist.distrito}">${dist.distrito}</option>`);
            });
        });
    });

    // Obtener datos
    fetch(`${assetsPath}sucursalAjax/obtener?id=${sucursalID}`)
      .then(res => res.json())
      .then(data => {
        if (!data || !data.id_almacen) {
          alert("No se encontró la información de la Sucursal.");
          return;
        }        
        
        fetch(`${assetsPath}perfilAjax/listar_departamentos`)
          .then(res => res.json())              
          .then(departamentos => {
            const selectDep = $('#edit-departamento');
            selectDep.empty().append('<option></option>');
            departamentos.forEach(dep => {                  
              const selected = dep.departamento == data.departamento ? 'selected' : '';
              selectDep.append(`<option value="${dep.departamento}" ${selected}>${dep.departamento}</option>`);
            });
                
            if (!data.departamento) return;
                
            fetch(`${assetsPath}perfilAjax/listar_provincias?departamento=${encodeURIComponent(data.departamento)}`)                 
              .then(res => res.json())
              .then(provincias => {
                const selectPro = $('#edit-provincia');
                selectPro.empty().append('<option></option>');
                provincias.forEach(prov => {
                  const selected = prov.provincia == data.provincia ? 'selected' : '';
                  selectPro.append(`<option value="${prov.provincia}" ${selected}>${prov.provincia}</option>`);
                });

                if (!data.provincia) return;

                fetch(`${assetsPath}perfilAjax/listar_distritos?departamento=${encodeURIComponent(data.departamento)}&provincia=${encodeURIComponent(data.provincia)}`)
                  .then(res => res.json())
                  .then(distritos => {
                    const selectDis = $('#edit-distrito');
                    selectDis.empty().append('<option></option>');
                    distritos.forEach(dist => {
                      const selected = dist.distrito == data.distrito ? 'selected' : '';
                      selectDis.append(`<option value="${dist.distrito}" ${selected}>${dist.distrito}</option>`);
                    });
                                
                    if (data.provincia) $('#edit-provincia').prop('disabled', false);
                    if (data.distrito) $('#edit-distrito').prop('disabled', false);
                                
                        // Insertar datos al fomrulario
                        $('#edit-id_almacen').val(data.id_almacen);
                        $('#edit-nombre_comercial').val(data.nombre_comercial);
                        $('#edit-direccion').val(data.direccion);                                                        

                        originalEditData = {
                          nombre: data.nombre,                        
                          direccion: data.direccion,
                          departamento: data.departamento,
                          provincia: data.provincia,
                          distrito: data.distrito                                                                  
                        };
                        
                        // Mostrar modal
                        const modal = new bootstrap.Modal(document.getElementById('modalEditarSucursal'));
                        modal.show();
                      });
                  });
              });            
      })
      .catch(err => {
        console.error("Error al obtener Sucursal:", err);
        alert("Error al obtener datos de la Sucursal.");
      });
  });

  //Detectar Cambios
  function hasFormChanged() {
    // Debug current vs original:
    return (
      $('#edit-nombre_comercial').val().trim() !== (originalEditData.nombre_comercial || '').trim() ||      
      $('#edit-direccion').val().trim() !== (originalEditData.direccion || '').trim() ||
      $('#departamento').val().trim() !== (originalEditData.departamento || '').trim() ||
      $('#provincia').val().trim() !== (originalEditData.provincia || '').trim() ||
      $('#distrito').val().trim() !== (originalEditData.distrito || '').trim()      
    );
  }

  //Enviar formulario
  $('#formEditarSucursal').on('submit', async function (e) {
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
    
    enviarEdicionSucursal();
  });

  //Función de envío
  function enviarEdicionSucursal() {
    isSubmittingEditSucursal = true;
    const form2 = document.getElementById('formEditarSucursal');
    const formData = new FormData(form2);

    const submitBtn = $(form2).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
    
    fetch(`${assetsPath}sucursalAjax/actualizar`, {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {          
          Toastify({
            text: "Sucursal y Almacen actualizados correctamente ✅",
            duration: 3000,
            style: { background: "#28a745" }
          }).showToast();

          bootstrap.Modal.getInstance(document.getElementById('modalEditarSucursal')).hide();          
          $('.datatables-category-list').DataTable().ajax.reload(null, false);
        } else {
          Toastify({
            text: "Error al actualizar ⚠️",
            duration: 3000,
            style: { background: "#dc3545" }
          }).showToast();
        }
      })
      .catch(err => {
        console.error("Error en actualización:", err);
        Toastify({
          text: "Error al actualizar Sucursal ❌",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      })
      .finally(() => {
        submitBtn.prop('disabled', false).html('Guardar cambios');
        isSubmittingEditSucursal = false;
      });
  }

  // Confirmación antes de cerrar modal si hay cambios
  $('#modalEditarSucursal').on('show.bs.modal', function () {
    $(this).off('hide.bs.modal').on('hide.bs.modal', function (e) {
      if (!isSubmittingEditSucursal && hasFormChanged()) {
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




/* ---- INICIO: EVENTO CAMBIAR ---- */
  let deleteMarId = null;
  let deleteSucName = '';
  let deleteSucEstado = 0;

  // Primera Confirmación
  $(document).on('click', '.btn-danger[data-id]', function () {
    deleteMarId = $(this).data('id');

    // Obtener nombre de usuario
    fetch(`${assetsPath}sucursalAjax/obtener?id=${deleteMarId}`)
      .then(res => res.json())
      .then(data => {
        if (!data || !data.id_almacen) {
          alert("No se encontró la información de la marca.");
          return;
        }
        deleteSucName = data.nombre_comercial;
        deleteSucEstado = data.activo;

        $('#modalConfirmDelete1Body').text(`¿Está seguro de cambiar el estado de la sucursal "${deleteSucName}"?`);

        // Mostrar modal 1
        const modal1 = new bootstrap.Modal(document.getElementById('modalConfirmDelete1'));
        modal1.show();
      })
      .catch(err => {
        console.error("Error al obtener Sucursal para cambiar:", err);
        alert("Error al obtener datos de la Sucursal.");
      });
  });

  // Confirmación modal 1 - pasa a modal 2
  $('#btnConfirmDelete1').on('click', function () {
    const modal1El = document.getElementById('modalConfirmDelete1');
    const modal1 = bootstrap.Modal.getInstance(modal1El);
    modal1.hide();

    let mensaje = "";
    if (deleteSucEstado === 1) {
      mensaje = "Al cambiar de estado la Sucursal, tanto los usuarios y almacenes que dependen de esta se desactivarán en el sistema, ¿desea continuar?";
    } else if (deleteSucEstado === 2) {
      mensaje = "Al cambiar de estado la Sucursal, tanto los usuarios y almacenes asociados a esta, estarán vigentes en el sistema nuevamente, ¿desea continuar?";
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

    fetch(`${assetsPath}sucursalAjax/eliminar`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id_almacen: deleteMarId })
    })
      .then(res => res.json())
      .then(resp => {
        if (resp.success) {
          Toastify({
            text: `Sucursal: "${deleteSucName}" Actualizado correctamente ✅`,
            duration: 3000,
            style: { background: "#28a745" }
          }).showToast();

          // Recargar tabla
          $('.datatables-category-list').DataTable().ajax.reload(null, false);

          modal2.hide();
        } else {
          Toastify({
            text: `Error al cambiar estado ⚠️`,
            duration: 3000,
            style: { background: "#dc3545" }
          }).showToast();
        }
      })
      .catch(err => {
        console.error("Error al cambiar estado:", err);
        Toastify({
          text: "Error al cambiar estado de la Sucursal ⚠️",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      });
  });
/* ---- FIN: EVENTO CAMBIAR ---- */




});