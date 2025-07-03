'use strict';

document.addEventListener("DOMContentLoaded", function () {
  const dtEmpresa = document.querySelector(".datatables-category-list");
  const selects = $(".select2");
  // Inyectar permisos del usuario desde PHP
  const permisos = window.PERMISOS_USUARIO || [];

  // Helper para verificar si el usuario tiene un permiso específico en la vista actual
  const tienePermisoVista = (permisoEsperado) => {
    return permisos.some(p =>
      p.ruta === 'administracion/empresa' && p.permiso === permisoEsperado
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
  if (dtEmpresa) {

    const botonesTop =[];    

    if (tienePermisoVista('exportar')) {
      botonesTop.push({
        extend: "collection",
        className: "btn btn-label-secondary dropdown-toggle me-4",
        text: `<span class="d-flex align-items-center gap-2">
                 <i class="icon-base bx bx-export icon-xs"></i>
                 <span class="d-none d-sm-inline-block">Exportar</span>
               </span>`,
        buttons: [
          { extend: "print", text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-printer me-1"></i>Print</span>`, className: "dropdown-item", exportOptions: { columns: [1, 2, 3], modifier: {selected: true},} },
          { extend: "csv",   text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-file me-1"></i>Csv</span>`,   className: "dropdown-item", exportOptions: { columns: [1, 2, 3], modifier: {selected: true}, } },
          { extend: "excel", text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-export me-1"></i>Excel</span>`, className: "dropdown-item", exportOptions: { columns: [1, 2, 3], modifier: {selected: true}, } },
          { extend: "pdf",   text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-pdf me-1"></i>Pdf</span>`,   className: "dropdown-item", exportOptions: { columns: [1, 2, 3], modifier: {selected: true}, } },
          { extend: "copy",  text: `<i class="icon-base bx bx-copy me-1"></i>Copy`, className: "dropdown-item", exportOptions: { columns: [1, 2, 3], modifier: {selected: true}, } }
        ]
      });
    }

  const tabla = new DataTable(dtEmpresa, {
    ajax: assetsPath + "empresaAjax/listar",
      columns: [
        { data: "id_empresa" }, // 0 - ID para checkbox
        { data: "razon_social" },         // razón social 1        
        { data: "ruc" },         // ruc 2
        { data: "direccion" },         // direccion 3        
        { data: "id_empresa" }      // acciones con ID 4
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
          className: "text-start",
          orderable: false
        },
        {
          targets: 2,
          className: "text-center",
          orderable: false
        },
        {
          targets: 3,
          className: "text-start",
          orderable: false
        },        
        {
          targets: 4, // ACCIONES
          className: "text-center",
          orderable: false,
          searchable: false,
          render: (id) => {
            let botones = '';

            let deshabilitarVer = !tienePermisoVista('visualizar') || parseInt(id) === 1;
            botones += `<button class="btn btn-sm btn-icon ${deshabilitarVer ? 'btn-secondary' : 'btn-info'}" title="Ver" data-id="${id}" ${deshabilitarVer ? 'disabled' : ''}><i class="bx bx-show-alt"></i></button>`;
            
            let deshabilitarEditar = !tienePermisoVista('editar');
            botones += `<button class="btn btn-sm btn-icon ${deshabilitarEditar ? 'btn-secondary' : 'btn-warning'}" title="Editar" data-id="${id}" ${deshabilitarEditar ? 'disabled' : ''}><i class="bx bx-edit-alt"></i></button>`;

            let deshabilitarEliminar = !tienePermisoVista('eliminar') || parseInt(id) === 1;
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
      order: [[4, "asc"]],
      layout: {
        topStart: {
          rowClass: "row m-3 my-0 justify-content-between",
          features: []
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
        emptyTable: "No se encontraron tipos de ubicaciones registradas",
        paginate: {
          next: '<i class="bx bx-chevron-right scaleX-n1-rtl icon-18px"></i>',
          previous: '<i class="bx bx-chevron-left scaleX-n1-rtl icon-18px"></i>'
        }
      },
      responsive: {
        details: {
          display: DataTable.Responsive.display.modal({
            header: row => 'Detalles de ' + row.data().razon_social
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
 }
  const soloNumeros = (e) => {
    const key = e.key;
    if (!/^\d$/.test(key) && key !== 'Backspace' && key !== 'Delete' && key !== 'ArrowLeft' && key !== 'ArrowRight' && key !== 'Tab') {
      e.preventDefault();
    }
  };
  document.getElementById('ruc').addEventListener('keydown', soloNumeros);


/*========================= EDITAR =========================*/
  let originalEditData = {};
  let isSubmittingEditEmpresa = false;

  //Abrir Modal y Cargar Datos
  $(document).on('click', '.btn-warning[data-id]', function () {
    const idEmpresa = $(this).data('id');

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
    fetch(`${assetsPath}empresaAjax/obtener?id=${idEmpresa}`)
      .then(res => res.json())
      .then(data => {
        if (!data || !data.id_empresa) {
          alert("No se encontró la información de la empresa.");
          return;
        }
        
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
                    $('#id_empresa').val(data.id_empresa);
                    $('#razon_social').val(data.razon_social);                                
                    $('#ruc').val(data.ruc);                                
                    $('#direccion').val(data.direccion);                                                    

                    originalEditData = {
                      razon_social: data.razon_social,                      
                      ruc: data.ruc,                                  
                      direccion: data.direccion,
                      departamento: data.departamento,
                      provincia: data.provincia,
                      distrito: data.distrito                                  
                    };
                    // Mostrar modal
                    const modal = new bootstrap.Modal(document.getElementById('modalEditarEmpresa'));
                    modal.show();
                  });
                });
          });
      })
      .catch(err => {
        console.error("Error al obtener Empresa:", err);
        alert("Error al obtener datos de la empresa.");
      });
  });

  //Detectar Cambios
  function hasFormChanged() {
    return (
      $('#razon_social').val().trim() !== (originalEditData.razon_social || '').trim() ||      
      $('#ruc').val().trim() !== (originalEditData.ruc || '').trim() ||      
      $('#direccion').val().trim() !== (originalEditData.direccion || '').trim() ||
      $('#departamento').val() !== (originalEditData.departamento || '').trim() ||
      $('#provincia').val() !== (originalEditData.provincia || '').trim() ||
      $('#distrito').val() !== (originalEditData.distrito || '').trim()
    );
  }

    //Enviar formulario
  $('#formEditarEmpresa').on('submit', async function (e) {
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
    
    enviarEdicionEmpresa();
  });

  //Función de envío
  function enviarEdicionEmpresa() {
    isSubmittingEditEmpresa = true;
    const form2 = document.getElementById('formEditarEmpresa');
    const formData = new FormData(form2);

    const submitBtn = $(form2).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
    
    fetch(`${assetsPath}empresaAjax/actualizar`, {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {          
          Toastify({
            text: "Datos de la Empresa actualizados correctamente ✅",
            duration: 3000,
            style: { background: "#28a745" }
          }).showToast();

          bootstrap.Modal.getInstance(document.getElementById('modalEditarEmpresa')).hide();          
          $('.datatables-category-list').DataTable().ajax.reload(null, false);
        } else {
          Toastify({
            text: "Error al actualizar Datos de Empresa ⚠️",
            duration: 3000,
            style: { background: "#dc3545" }
          }).showToast();
        }
      })
      .catch(err => {
        console.error("Error en actualización:", err);
        Toastify({
          text: "Error al actualizar Empresa ❌",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      })
      .finally(() => {
        submitBtn.prop('disabled', false).html('Guardar cambios');
        isSubmittingEditEmpresa = false;
      });
  }

  // Confirmación antes de cerrar modal si hay cambios
  $('#modalEditarEmpresa').on('show.bs.modal', function () {
    $(this).off('hide.bs.modal').on('hide.bs.modal', function (e) {
      if (!isSubmittingEditEmpresa && hasFormChanged()) {
        e.preventDefault(); // detiene cierre
        const salir = confirm("Hay cambios sin guardar. ¿Seguro que deseas salir y perder los cambios?");

        if (salir) {
          $(this).off('hide.bs.modal'); // desengancha momentáneamente para permitir cerrar
          $(this).modal('hide');        // cierra realmente
        }
      }
    });
  });



});