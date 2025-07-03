'use strict';

document.addEventListener("DOMContentLoaded", function () {
  const dtProveedores = document.querySelector(".datatables-category-list");
  const selects = $(".select2");
  // Inyectar permisos del usuario desde PHP
  const permisos = window.PERMISOS_USUARIO || [];

  // Helper para verificar si el usuario tiene un permiso específico en la vista actual
  const tienePermisoVista = (permisoEsperado) => {
    return permisos.some(p =>
      p.ruta === 'contactos/proveedor' && p.permiso === permisoEsperado
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
  if (dtProveedores) {

  const filtroCustomFeature = function(settings) {
    const container = document.createElement('div');
    container.classList.add('d-flex', 'align-items-center', 'gap-2', 'flex-wrap');

    container.innerHTML = `
    <div class="row gx-2 mx-3 mb-3 align-items-center">
      <div class="col-auto">
        <select id="proveedor-filtro-campo" class="form-select form-select-sm">
          <option value="razon_social">Razón Social</option>
          <option value="ruc">RUC</option>        
        </select>
      </div>
      <div class="col">
        <input type="text" id="proveedor-filtro-valor" class="form-control form-control-sm" placeholder="Buscar por filtro seleccionado...">
      </div>
      <div class="col-auto">
        <button id="btnResetProveedor" class="btn btn-sm btn-secondary">
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
              tabla.column(6).search('^1$', true, false).draw();
            }
          },
          {
            text: 'Inactivos',
            className: 'dropdown-item',
            action: function () {
              tabla.column(6).search('^2$', true, false).draw();
            }
          },
          {
            text: 'Todos',
            className: 'dropdown-item',
            action: function () {
              tabla.column(6).search('').draw();
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
            exportOptions: { columns: [0,1,2,3,4,5,6] }
          },
          {
            extend: "csv",
            text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-file me-1"></i>Csv</span>`,
            className: "dropdown-item",
            exportOptions: { columns: [0,1,2,3,4,5,6] }
          },
          {
            extend: "excel",
            text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-export me-1"></i>Excel</span>`,
            className: "dropdown-item",
            exportOptions: { columns: [0,1,2,3,4,5,6] }
          },
          {
            extend: "pdf",
            text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-pdf me-1"></i>Pdf</span>`,
            className: "dropdown-item",
            exportOptions: { columns: [0,1,2,3,4,5,6] }
          },
          {
            extend: "copy",
            text: `<i class="icon-base bx bx-copy me-1"></i>Copy`,
            className: "dropdown-item",
            exportOptions: { columns: [0,1,2,3,4,5,6] }
          }
        ]
      });
    }

    if (tienePermisoVista('registrar')) {
      botonesTop.push({
        text: '<i class="bx bx-plus"></i><span class="d-none d-sm-inline-block">Nuevo Proveedor</span>',
        className: "add-new btn btn-primary",
        attr: {
          "data-bs-toggle": "modal",
          "data-bs-target": "#modalRegistrarProveedor"
        }
      });
    }

    const tabla = new DataTable(dtProveedores, {
      ajax: assetsPath + "proveedorAjax/listar",
      columns: [
        { data: null },                 // Número oculto (control)
        { data: "razon_social"},       // razón social
        { data: "ruc" },               // correo
        { data: "Ubicacion" },          // rol
        { data: "estado_sunat" },       // estado (SUNAT)
        { data: "condicion_sunat" },    // condición (SUNAT)
        { data: "activo" },             // estado (badge)
        { data: "id_proveedor" }        // acciones con ID
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
        },
        {
          targets: 2,
          className: "text-start",          
        },
        {
          targets: 3,
          className: "text-start",          
        },
        {
          targets: 4, // ESTADO_SUNAT
          className: "text-center",
          render: (data, type) => {
            if ( type === 'display') {
              switch (data) {
                case 'ACTIVO':
                  return '<span class="badge bg-label-success">ACTIVO</span>';
                case 'SUSPENSION TEMPORAL':
                  return '<span class="badge bg-label-secondary">SUSPENSION TEMPORAL</span>';
                case 'BAJA PROVISIONAL':
                  return '<span class="badge bg-label-warning">BAJA PROVISIONAL</span>';
                case 'BAJA DEFINITIVA':
                  return '<span class="badge bg-label-danger">BAJA DEFINITIVA</span>';
                
                case 'BAJA PROVISIONAL DE OFICIO':
                  return '<span class="badge bg-label-info">BAJA PROVISIONAL DE OFICIO</span>';
                case 'BAJA DEFINITIVA DE OFICIO':
                  return '<span class="badge bg-label-dark">BAJA DEFINITIVA DE OFICIO</span>';
                default:
                  return `<span class="badge bg-label-secondary">${data}</span>`;
              }              
            }
            return data;
          }         
        },
        {
          targets: 5, // CONDICION_SUNAT
          className: "text-center",
          render: (data, type) => {
            if ( type === 'display') {
              switch (data) {
                case 'HABIDO':
                  return '<span class="badge bg-label-success">HABIDO</span>';              
                case 'NO HALLADO':
                  return '<span class="badge bg-label-warning">NO HALLADO</span>';
                case 'NO HABIDO':
                  return '<span class="badge bg-label-danger">NO HABIDO</span>';
                default:
                  return `<span class="badge bg-label-secondary">${data}</span>`;
              }              
            }
            return data;
          }
        },
        {
          targets: 6, // ESTADO
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
          targets: 7, // ACCIONES
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
        emptyTable: "No se encontraron proveedores registrados",
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

    /* Filtro de estado por defecto = Activo */
    tabla.column(6).search('^1$', true, false).draw();
    
    /* PARA FILTROS Y BUSQUEDAD*/
    $(document).on("input", "#proveedor-filtro-valor", function () {
      const campo = $("#proveedor-filtro-campo").val();
      const valor = $(this).val().trim();

      let colIndex = -1;
      switch (campo) {
        case "razon_social":
          colIndex = 1;
          break;
        case "ruc":
          colIndex = 2;
          break;        
      }

      if (colIndex >= 0) {
        tabla.columns().search('');
        tabla.column(colIndex).search(valor).draw();
      }
    });

    // Resetear filtro personalizado
    $(document).on("click", "#btnResetProveedor", function () {
      $("#proveedor-filtro-valor").val("");
      tabla.columns().search('');
      tabla.column(6).search('^1$', true, false).draw(); // Restaurar a activos
    });
  }

   /* ==== Validaciones ==== */
  const soloNumeros = (e) => {
    const key = e.key;
    if (!/^\d$/.test(key) && key !== 'Backspace' && key !== 'Delete' && key !== 'ArrowLeft' && key !== 'ArrowRight' && key !== 'Tab') {
      e.preventDefault();
    }
  };

  // $('#reg-dni, #reg-telefono, #edit-dni, #edit-telefono').on('keydown', soloNumeros);  
  document.getElementById('prov-ruc').addEventListener('keydown', soloNumeros);
  document.getElementById('prov-telefono').addEventListener('keydown', soloNumeros);

  // Bloquear la tecla espacio
  $('#prov-correo').on('input', function () {
    const input = $(this);
    let valor = input.val();

    // Eliminar espacios
    valor = valor.replace(/\s/g, '');
    input.val(valor);

    // Validar formato correo (si no está vacío)
    const esValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor);
    $('#verificarcorreo').prop('disabled', !esValido && valor !== '');
  });

  $(document).ready(function () {
  $('#buscarProveedor').prop('disabled', true);
  $('#verificarcorreo').prop('disabled', true);

  //prov-edit-correo

  $('#prov-ruc').on('input', function () {
    const rucValido = /^\d{11}$/.test($(this).val().trim());
    $('#buscarProveedor').prop('disabled', !rucValido);
  });

  $('#prov-correo').on('input', function () {
    const correo = $(this).val().trim();
    const correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo);
    $('#verificarcorreo').prop('disabled', !correoValido);
  });
});



/* ---- INICIO: EVENTO REGISTRAR ---- */
$(function() {
  let proveedorDisponible = null;  // null = not checked
  let correoDisponible = null;
  let isSubmittingRegProveedor = false;

  // HABILITAR botón buscar si RUC inválido
  $('#buscarProveedor').prop('disabled', true);
  $('#prov-ruc').on('input', function () {
    const rucReg = $(this).val().trim();
    const rucRegValido = /^\d{11}$/.test(rucReg);
    $('#buscarProveedor').prop('disabled', !rucRegValido);
  });

    // Verificar DNI
  function verificarRUCExistenteReg() {
    const ruc = $('#prov-ruc').val().trim();

    if (!ruc) {
      Toastify({
        text: 'Ingrese un número de RUC',
        duration: 3000,
        style: { background: '#ffc107', color: '#000' }
      }).showToast();
      return Promise.resolve(false);
    };

    //Verificar existencia en la BD
    return fetch(`${assetsPath}proveedorAjax/validar_proveedor?ruc=${encodeURIComponent(ruc)}`)
      .then(res => {
        if (!res.ok) throw new Error('Error de red en validar registros');
        return res.json();
      })
      .then(dataDB => {
        if (dataDB.valid) {
          proveedorDisponible = false;
          Toastify({
            text: 'Empresa (RUC) ya registrada ❌',
            duration: 3000,
            style: { background: '#dc3545' }
          }).showToast();
          return false; // Detener aquí   
        }

        //Consultar RUC
        return fetch(`${assetsPath}consultaAjax/buscar_proveedor?ruc=${ruc}`)
          .then(res => {
            if (!res.ok) throw new Error('Error de red en consultar RUC');
            return res.json();
          })
          .then(data => {
		        if (data && data.razonSocial) {              
              //document.getElementById('reg-nombre').value = data.nombres;
              $('#prov-razon').val(data.razonSocial || '');
              $('#prov-direccion').val(data.direccion || '');
              $('#prov-departamento').val(data.departamento || '');
              $('#prov-provincia').val(data.provincia || '');
              $('#prov-distrito').val(data.distrito || '');
              $('#prov-ubigeo').val(data.ubigeo || '');
              $('#prov-agente-retencion').val(data.EsAgenteRetencion ? '1' : '0');
              $('#prov-estado-sunat').val(data.estado || '');
              $('#prov-condicion-sunat').val(data.condicion || '');

              Toastify({
                text: "Datos cargados correctamente ✅",
                duration: 3000,
                style: { background: "#28a745" }
              }).showToast();
            } else {
              Toastify({
                text: "No se encontraron datos del RUC ❌",
                duration: 3000,
                style: { background: "#dc3545" }
              }).showToast();
            }
            proveedorDisponible = data.success && data.valid;
            return proveedorDisponible;
          });        
      })
      .catch(err => {
        console.error('Error durante la validación del RUC:', err);
        proveedorDisponible = false;
        Toastify({
          text: "Error validando RUC ❌",
          duration: 3000,
          style: { background: '#dc3545' }
        }).showToast();
        return false;
      });
  }
  // Verificar correo
  function verificarCorreoExistenteReg() {
    const correo = $('#prov-correo').val().trim();

    if (!correo) {
      correoDisponible = true; // Campo vacío está permitido
      return Promise.resolve(true);
    };

    //Verificar existencia en la BD
    return fetch(`${assetsPath}proveedorAjax/validar_correo_proveedor?correo=${encodeURIComponent(correo)}`)
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

    //Botón de Verificar RUC
  $('#buscarProveedor').on('click', function(e) {
    e.preventDefault();
    verificarRUCExistenteReg();
  });
  //Botón de Verificar Correo
  $('#verificarcorreo').on('click', function(e) {
    e.preventDefault();
    verificarCorreoExistenteReg();
  });

  // Enviar formulario
  $('#formRegistrarProveedor').on('submit', async function (e) {
    e.preventDefault();

    const form = this;
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    // Validar proveedor y correo
    if (proveedorDisponible === false) {
      Toastify({ text: 'Proveedor inválido ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }
    if ($('#prov-correo').val().trim() !== '' && correoDisponible === false) {
      const correoValido = await verificarCorreoExistenteReg();
      if (!correoValido) return;
    }

    enviarRegistroProveedor(form);
  });

  //FUnción envío
  function enviarRegistroProveedor(form) {
    isSubmittingRegProveedor = true;

    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

    const formData = new FormData(form);

    fetch(`${assetsPath}proveedorAjax/registrar`, {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Toastify({ text: 'Proveedor registrado correctamente ✅', style: { background: '#28a745' } }).showToast();
          bootstrap.Modal.getInstance(document.getElementById('modalRegistrarProveedor')).hide();
          $('.datatables-category-list').DataTable().ajax.reload(null, false);
          form.reset();          
        } else {
          Toastify({ 
            text: data.message || 'Error al registrar proveedor ⚠️', 
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
        isSubmittingRegProveedor = false;
      });


  }
});
/* ---- FIN: EVENTO REEGISTRAR ---- */



/* ---- INICIO: EVENTO VISUALIZAR ---- */
$(document).on('click', '.btn-info[data-id]', function () {
  const id = $(this).data('id');

  fetch(`${assetsPath}proveedorAjax/obtener?id=${id}`)
    .then(res => res.json())
    .then(data => {
      if (data && data.id_proveedor) {
        $('#detalle-prov-ruc').val(data.ruc);
        $('#detalle-prov-razon').val(data.razon_social);
        $('#detalle-prov-direccion').val(data.direccion);
        $('#detalle-prov-departamento').val(data.departamento);
        $('#detalle-prov-provincia').val(data.provincia);
        $('#detalle-prov-distrito').val(data.distrito);
        $('#detalle-prov-ubigeo').val(data.ubigeo);
        $('#detalle-prov-telefono').val(data.telefono);
        $('#detalle-prov-correo').val(data.correo);
        $('#detalle-prov-contacto').val(data.contacto);
        $('#detalle-prov-estado-sunat').val(data.estado_sunat);
        $('#detalle-prov-condicion-sunat').val(data.condicion_sunat);
        $('#detalle-prov-agente-retencion').val(data.es_agente_retencion ? 'Sí' : 'No');
        $('#detalle-prov-estado').val(data.activo ===1 ? 'Activo' : 'Inactivo');

        //Deshabilitar todos los elementos del formulario
         $('#modalDetalleProveedor').find('input, select, textarea').prop('disabled', true);

        const modal = new bootstrap.Modal(document.getElementById('modalDetalleProveedor'));
        modal.show();
      } else {
        alert("No se encontró el detalle del proveedor.");
      }
    })
    .catch(err => {
      console.error("Error al obtener detalle del proveedor:", err);
    });
});
/* ---- FIN: EVENTO VISUALIZAR ---- */




/* ---- INICIO: EVENTO CAMBIA ---- */
  let deleteProvId = null;
  let deleteRazScc = '';
  let deleteProvEstado = 0;

  // Primera Confirmación
  $(document).on('click', '.btn-danger[data-id]', function () {
    deleteProvId = $(this).data('id');

    // Obtener razon social del Proveedor
    fetch(`${assetsPath}proveedorAjax/obtener?id=${deleteProvId}`)
      .then(res => res.json())
      .then(data => {
        if (!data || !data.id_proveedor) {
          alert("No se encontró la información del Proveedor.");
          return;
        }
        deleteRazScc = data.razon_social;
        deleteProvEstado = data.activo;

        $('#modalConfirmDelete1Body').text(`¿Está seguro de cambiar el estado del Proveedor "${deleteRazScc}"?`);

        // Mostrar modal 1
        const modal1 = new bootstrap.Modal(document.getElementById('modalConfirmDelete1'));
        modal1.show();
      })
      .catch(err => {
        console.error("Error al obtener proveedor para cambiar:", err);
        alert("Error al obtener datos del proveedor.");
      });
  });

  // Confirmación modal 1 - pasa a modal 2
  $('#btnConfirmDelete1').on('click', function () {
    const modal1El = document.getElementById('modalConfirmDelete1');
    const modal1 = bootstrap.Modal.getInstance(modal1El);
    modal1.hide();

    let mensaje = "";
    if (deleteProvEstado === 1) {
      mensaje = "Al cambiar de estado el proveedor no se podrá realizar compras en el sistema a este provedor, ¿desea continuar?";
    } else if (deleteProvEstado === 2) {
      mensaje = "Al cambiar de estado el proveedor se podrá realizar compras en el sistema nuevamente con este proveedor, ¿desea continuar?";
    } else {
      mensaje = "¿Desea continuar con el cambio de estado?";
    }
  $('#modalConfirmDelete2Body').text(mensaje);

    // Mostrar modal 2
    const modal2 = new bootstrap.Modal(document.getElementById('modalConfirmDelete2'));
    modal2.show();
  });

  // Confirmación modal 2 - proceder a eliminar proveedor
  $('#btnConfirmDelete2').on('click', function () {
    const modal2El = document.getElementById('modalConfirmDelete2');
    const modal2 = bootstrap.Modal.getInstance(modal2El);

    fetch(`${assetsPath}proveedorAjax/eliminar`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id_proveedor: deleteProvId })
    })
      .then(res => res.json())
      .then(resp => {
        if (resp.success) {
          Toastify({
            text: `Proveedor "${deleteRazScc}" eliminado correctamente ✅`,
            duration: 3000,
            style: { background: "#28a745" }
          }).showToast();

          // Recargar tabla
          $('.datatables-category-list').DataTable().ajax.reload(null, false);

          modal2.hide();
        } else {
          Toastify({
            text: `Error al eliminar proveedor ⚠️`,
            duration: 3000,
            style: { background: "#dc3545" }
          }).showToast();
        }
      })
      .catch(err => {
        console.error("Error al eliminar proveedor:", err);
        Toastify({
          text: "Error al eliminar proveedor ⚠️",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      });
  });
/* ---- FIN: EVENTO CAMBIAR ---- */





/* ---- INICIO: EVENTO EDITAR PROVEEDOR ---- */
  let originalPrvData = {};
  let isSubmittingProveedor = false;
  let correoEditDisponible = null;

  // Abrir modal y cargar datos
  $(document).on('click', '.btn-warning[data-id]', function () {
    const id = $(this).data('id');
    //console.log("Editar proveedor ID:", idProveedor);

    // Obtener datos
    fetch(`${assetsPath}proveedorAjax/obtener?id=${id}`)
      .then(res => res.json())
      .then(data => {
        if (!data || !data.id_proveedor) {
          alert("No se encontró la información del proveedor.");
          return;
        }
        // Insertar datos al formulario editar
        $('#prov-edit-id_proveedor').val(data.id_proveedor);
        $('#prov-edit-ruc').val(data.ruc);
        $('#prov-edit-razon').val(data.razon_social);
        $('#prov-edit-direccion').val(data.direccion);
        $('#prov-edit-departamento').val(data.departamento);
        $('#prov-edit-provincia').val(data.provincia);
        $('#prov-edit-distrito').val(data.distrito);
        $('#prov-edit-ubigeo').val(data.ubigeo);
        $('#prov-edit-telefono').val(data.telefono || '');
        $('#prov-edit-correo').val(data.correo);
        $('#prov-edit-contacto').val(data.contacto);
        $('#prov-edit-estado-sunat').val(data.estado_sunat);
        $('#prov-edit-condicion-sunat').val(data.condicion_sunat);
        $('#prov-edit-agente-retencion').val(data.es_agente_retencion ? 1 : 0);

        originalPrvData = { ...data};

        // Mostrar modal editar proveedor      
        const modal = new bootstrap.Modal(document.getElementById('modalEditarProveedor'));
        modal.show();
      })
      .catch(err => {
        console.error("Error al obtener proveedor:", err);
        alert("Error al cargar datos.");
      });
  });

  // Verificar correo al editar proveedor
  function verificarCorreoExistenteEdit() {
    const correo = $('#prov-edit-correo').val().trim();
    const idProveedor = $('#prov-edit-id_proveedor').val();

    if (!correo) {
      correoEditDisponible = true; // válido porque es opcional
      return Promise.resolve(true);
    };

    //Verificar existencia en la BD
    return fetch(`${assetsPath}proveedorAjax/validar_correo_proveedor?correo=${encodeURIComponent(correo)}&id_proveedor=${encodeURIComponent(idProveedor)}`)
      .then(res => {
        if (!res.ok) throw new Error('Error de red en validar_correo');
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
            if (!res.ok) throw new Error('Error de red en validar_correo_real1');
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
  //Botón de Verificar Correo
  $('#verificarEditcorreo').on('click', function(e) {
    e.preventDefault();
    verificarCorreoExistenteEdit();
  });


  // Detectar cambios
  function hasProveedorChanged() {
    return (
      $('#prov-edit-ruc').val().trim() !== (originalPrvData.ruc || '').trim() ||
      $('#prov-edit-razon').val().trim() !== (originalPrvData.razon_social || '').trim() ||
      $('#prov-edit-direccion').val().trim() !== (originalPrvData.direccion || '').trim() ||
      $('#prov-edit-departamento').val().trim() !== (originalPrvData.departamento || '').trim() ||
      $('#prov-edit-provincia').val().trim() !== (originalPrvData.provincia || '').trim() ||
      $('#prov-edit-distrito').val().trim() !== (originalPrvData.distrito || '').trim() ||
      $('#prov-edit-ubigeo').val().trim() !== (originalPrvData.ubigeo || '').trim() ||
      $('#prov-edit-telefono').val().trim() !== (originalPrvData.telefono || '').trim() ||
      $('#prov-edit-correo').val().trim() !== (originalPrvData.correo || '').trim() ||
      $('#prov-edit-contacto').val().trim() !== (originalPrvData.contacto || '').trim() ||
      $('#prov-edit-estado-sunat').val() !== originalPrvData.estado_sunat ||
      $('#prov-edit-condicion-sunat').val() !== originalPrvData.condicion_sunat ||
      $('#prov-edit-agente-retencion').val().toString() !== (originalPrvData.es_agente_retencion).toString()
    );
  }

  // Enviar formulario con validación
  $('#formEditarProveedor').on('submit', async  function(e) {
    e.preventDefault();

    if (!this.checkValidity()) {
      this.reportValidity();
      return;
    }

    if (!hasProveedorChanged()) {
      Toastify({
        text: "No se realizaron cambios ⚠️",
        duration: 3000,
        style: { background: "#ffc107", color: "#000" }
      }).showToast();
      return;
    }

    if ($('#prov-edit-correo').val().trim() !== '' && correoEditDisponible === false) {
      Toastify({ text: 'Correo no válido ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }

    enviarEdicionProveedor();
  });

  //Función de envío
  function enviarEdicionProveedor() {
    isSubmittingProveedor = true;
    const formData = new FormData(document.getElementById('formEditarProveedor'));

    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
    //const formData = new FormData(this);

    fetch(`${assetsPath}proveedorAjax/actualizar`, {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Toastify({ 
            text: 'Proveedor actualizado correctamente ✅',
            duration: 3000,
            style: { background: '#28a745' } 
          }).showToast();
          bootstrap.Modal.getInstance(document.getElementById('modalEditarProveedor')).hide();
          $('.datatables-category-list').DataTable().ajax.reload(null, false);
        } else {
          Toastify({ 
            text: 'Error al actualizar proveedor ⚠️', 
            duration: 3000,
            style: { background: '#dc3545' } 
          }).showToast();
        }
      })
      .catch(err => {
        console.error("Error en actualización:", err);
        Toastify({ 
          text: 'Error de red ❌', 
          duration: 3000,
          style: { background: '#dc3545'} 
        }).showToast();
      })
      .finally(() => {
        isSubmittingProveedor = false
        submitBtn.prop('disabled', false).html('Guardar Cambios');
      });
  }

  // Advertencia al cerrar sin guardar
  $('#modalEditarProveedor').on('show.bs.modal', function () {
    $(this).off('hide.bs.modal').on('hide.bs.modal', function (e) {
      if (!isSubmittingProveedor && hasProveedorChanged()) {
        e.preventDefault(); // prevenir cierre
        const salir = confirm("Hay cambios sin guardar. ¿Seguro que deseas salir y perder los cambios?");
        if (salir) {
          $(this).off('hide.bs.modal'); // desengancha momentáneamente para permitir cerrar
          $(this).modal('hide');        // cierra realmente
        }
      }
    });
  });
/* ---- FIN: EVENTO EDITAR PROVEEDOR ---- */



});