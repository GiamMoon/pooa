'use strict';

document.addEventListener("DOMContentLoaded", function () {
  const dtUbicaciones = document.querySelector(".datatables-category-list");
  const selects = $(".select2");
  // Inyectar permisos del usuario desde PHP
  const permisos = window.PERMISOS_USUARIO || [];

  // Helper para verificar si el usuario tiene un permiso específico en la vista actual
  const tienePermisoVista = (permisoEsperado) => {
    return permisos.some(p =>
      p.ruta === 'inventario/ubicacion' && p.permiso === permisoEsperado
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
  if (dtUbicaciones) {

    const filtroCustomFeature = function(settings) {
    const container = document.createElement('div');
    container.classList.add('d-flex', 'align-items-center', 'gap-2', 'flex-wrap');

    container.innerHTML = `
    <div class="row gx-2 mx-3 mb-3 align-items-center">      
      <div class="col">
        <input type="text" id="ubicacion-filtro-valor" class="form-control form-control-sm" placeholder="Buscar por Dirección...">
      </div>
      <div class="col-auto">
        <button id="btnResetubicacion" class="btn btn-sm btn-secondary">
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
        text: '<i class="bx bx-plus"></i><span class="d-none d-sm-inline-block">Nueva Ubicación</span>',
        className: "add-new btn btn-primary",
        attr: {
          "data-bs-toggle": "modal",
          "data-bs-target": "#modalRegistrarUbicacion"
        }
      });
    }

  const tabla = new DataTable(dtUbicaciones, {
    ajax: assetsPath + "ubicacionAjax/listar",
      columns: [
        { data: null },         // Numero oculto (control)
        { data: "direccion" },         // nombre rol        
        { data: "telefono" },         // abre
        { data: "tipo_ubicacion" },         // nomb
        { data: "activo" },         // estado (badge)
        { data: "id_ubicacion" }      // acciones con ID
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
          className: "text-center",
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
        emptyTable: "No se encontraron tipos de ubicaciones registradas",
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
  $(document).on("input", "#ubicacion-filtro-valor", function () {
      const valor = $(this).val().trim();

      let colIndex = 1;

      //Limpiar filtro
      tabla.columns().search('');
      tabla.column(colIndex).search(valor).draw();
    });

    // Resetear Búsqueda
    $(document).on("click", "#btnResetubicacion", function () {
      $("#ubicacion-filtro-valor").val("");
      tabla.columns().search('');
      tabla.column(4).search('^1$', true, false).draw(); // Restaurar a activos
    });
 }

 // Sanitizar TELÉFONO - solo números y máx 9
$('#reg-telefono-ubicacion, #edit-telefono-ubicacion').on('input', function () {
  this.value = this.value.replace(/\D/g, '').slice(0, 9);
});


 // ========================= REGISTRAR =========================
//Verificar dirección
let direccionDisponible = null;
let map, geocoder, marker;
let autocomplete;

$('#reg-direccion-ubicacion').on('input', function () {
  direccionDisponible = null;
  $('#verificarUbicacion').prop('disabled', this.value.trim() === '');
});

$('#verificarUbicacion').on('click', function (e) {
  e.preventDefault();
  
  const direccion = $('#reg-direccion-ubicacion').val().trim();
  if (!direccion) return;

  fetch(`${assetsPath}ubicacionAjax/validar_ubicacion?direccion=${encodeURIComponent(direccion)}`)
    .then(res => res.json())
    .then(data => {
      direccionDisponible = !data.valid;
      Toastify({
        text: direccionDisponible ? "Dirección disponible ✅" : "Dirección ya registrada ❌",
        style: { background: direccionDisponible ? "#28a745" : "#dc3545" },
        duration: 3000
      }).showToast();
    });
});

// Cuando se hace clic en "Abrir" el mapa de Google
$('#seleccionarDireccion').on('click', function (e) {
  e.preventDefault();

  // Modal con el mapa de Google
  const modalContent = `    
        <div class="mb-3">
          <label for="direccion-busqueda" class="form-label">Buscar Dirección</label>
          <input id="direccion-busqueda" class="form-control" type="text" placeholder="Escribe una dirección...">
        </div>
        <div id="googleMap" style="width: 100%; height: 400px;"></div>    
  `;
  
  const modal = new bootstrap.Modal(document.getElementById('modalGoogleMap'));
  modal.show();
  
  $('#modalGoogleMap .modal-body').html(modalContent);
  
  // Solo inicializar el mapa si no está inicializado
  if (!window.map) {
    initMap(); // Inicializar el mapa de Google solo si no se ha inicializado previamente
  }
});

// Inicializar el mapa de Google
function initMap() {
  const initialPosition = { lat: -34.397, lng: 150.644 };  // Coordenadas predeterminadas
  map = new google.maps.Map(document.getElementById("googleMap"), {
    center: initialPosition,
    zoom: 15,
  });

  geocoder = new google.maps.Geocoder();
  marker = new google.maps.Marker({
    position: initialPosition,
    map: map,
    draggable: true,
  });

  // Al mover el marcador, actualizar la dirección en el campo
  google.maps.event.addListener(marker, 'dragend', function() {
    geocodePosition(marker.getPosition());
  });

  // Usar la geolocalización para centrar el mapa en la ubicación del usuario
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      const userLocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      map.setCenter(userLocation);
      marker.setPosition(userLocation);
      geocodePosition(userLocation);
    });
  }
}

// Obtener la dirección al mover el marcador
function geocodePosition(position) {
  geocoder.geocode({ latLng: position }, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      if (results[0]) {
        const address = results[0].formatted_address;
        $('#reg-direccion-ubicacion').val(address);  // Actualizar el input con la dirección
      }
    } else {
      alert("No se pudo obtener la dirección.");
    }
  });
}

// Cuando el usuario acepta la ubicación seleccionada
$('#acceptLocation').on('click', function() {
  const address = $('#reg-direccion-ubicacion').val();
  if (!address) {
    alert("Debe seleccionar una ubicación.");
    return;
  }
  // Cerrar el modal
  const modal = bootstrap.Modal.getInstance(document.getElementById('modalGoogleMap'));
  modal.hide();
});

// Script de Google Maps API
function loadGoogleMapsAPI() {
  const script = document.createElement('script');
  script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyCdqQ3hxyM2Xsb2S-5gRQYJsHPU41vGBME&callback=initMap";
  script.async = true;
  script.defer = true;
  document.head.appendChild(script);
}
window.onload = loadGoogleMapsAPI;

//envio con verificacion
$('#formRegistrarUbicacion').on('submit', async function (e) {
  e.preventDefault();
  const form = this;

  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }

  if (direccionDisponible === null) {
    await $('#verificarUbicacion').trigger('click');
    return;
  }

  if (!direccionDisponible) {
    Toastify({ text: 'Dirección ya registrada ❌', style: { background: '#dc3545' } }).showToast();
    return;
  }

  const submitBtn = $(form).find('button[type="submit"]');
  submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

  const formData = new FormData(form);
  fetch(`${assetsPath}ubicacionAjax/registrar`, {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        Toastify({ text: 'Ubicación registrada correctamente ✅', style: { background: '#28a745' } }).showToast();
        bootstrap.Modal.getInstance(document.getElementById('modalRegistrarUbicacion')).hide();
        $('.datatables-category-list').DataTable().ajax.reload(null, false);
        form.reset();
        direccionDisponible = null;
      } else {
        Toastify({ text: data.message || 'Error al registrar ❌', style: { background: '#dc3545' } }).showToast();
      }
    })
    .finally(() => {
      submitBtn.prop('disabled', false).html('Registrar');
    });
});




  // ========================= EDITAR =========================
let originalEditUbicacion = {};
let direccionDisponibleEdit = null;

$(document).on('click', '.btn-warning[data-id]', function () {
  const id = $(this).data('id');

  fetch(`${assetsPath}ubicacionAjax/obtener?id=${id}`)
    .then(res => res.json())
    .then(data => {
      $('#edit-id_ubicacion').val(data.id_ubicacion);
      $('#edit-direccion-ubicacion').val(data.direccion);
      $('#edit-telefono-ubicacion').val(data.telefono);
      $('#edit-tipo-ubicacion').val(data.tipo_ubicacion);

      originalEditUbicacion = { ...data };
      direccionDisponibleEdit = null;

      new bootstrap.Modal('#modalEditarUbicacion').show();
    });
});

$('#edit-direccion-ubicacion').on('input', () => direccionDisponibleEdit = null);

$('#edit-verificarUbicacion').on('click', function (e) {
  e.preventDefault();
  const direccion = $('#edit-direccion-ubicacion').val().trim();
  const id = $('#edit-id_ubicacion').val().trim();

  fetch(`${assetsPath}ubicacionAjax/validar_ubicacion?direccion=${encodeURIComponent(direccion)}&id_ubicacion=${encodeURIComponent(id)}`)
    .then(res => res.json())
    .then(data => {
      direccionDisponibleEdit = !data.valid;
      Toastify({
        text: direccionDisponibleEdit ? "Dirección disponible ✅" : "Ya registrada ❌",
        style: { background: direccionDisponibleEdit ? "#28a745" : "#dc3545" },
        duration: 3000
      }).showToast();
    });
});

function hasUbicacionChanged() {
  return (
    $('#edit-direccion-ubicacion').val().trim() !== originalEditUbicacion.direccion ||
    $('#edit-telefono-ubicacion').val().trim() !== originalEditUbicacion.telefono ||
    $('#edit-tipo-ubicacion').val() !== originalEditUbicacion.tipo_ubicacion
  );
}

$('#formEditarUbicacion').on('submit', function (e) {
  e.preventDefault();
  const form = this;

  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }

  if (!hasUbicacionChanged()) {
    Toastify({ text: "No se realizaron cambios ⚠️", style: { background: "#ffc107", color: "#000" } }).showToast();
    return;
  }

  if (direccionDisponibleEdit === null) {
    $('#edit-verificarUbicacion').trigger('click');
    return;
  }

  if (!direccionDisponibleEdit) {
    Toastify({ text: 'Dirección ya registrada ❌', style: { background: '#dc3545' } }).showToast();
    return;
  }

  const formData = new FormData(form);
  fetch(`${assetsPath}ubicacionAjax/actualizar`, {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        Toastify({ text: 'Ubicación actualizada ✅', style: { background: "#28a745" } }).showToast();
        bootstrap.Modal.getInstance(document.getElementById('modalEditarUbicacion')).hide();
        $('.datatables-category-list').DataTable().ajax.reload(null, false);
      } else {
        Toastify({ text: 'Error al actualizar ❌', style: { background: '#dc3545' } }).showToast();
      }
    });
});



/* CAMBIAR ESTADO */
let deleteUbicacionId = null;
let deleteUbicacionName = '';
let deleteUbicacionEstado = 1;

$(document).on('click', '.btn-danger[data-id]', function () {
  deleteUbicacionId = $(this).data('id');

  fetch(`${assetsPath}ubicacionAjax/obtener?id=${deleteUbicacionId}`)
    .then(res => res.json())
    .then(data => {
      deleteUbicacionName = data.direccion;
      deleteUbicacionEstado = data.activo;

      $('#modalConfirmDelete1Body').text(`¿Deseas cambiar el estado de "${deleteUbicacionName}"?`);
      new bootstrap.Modal('#modalConfirmDelete1').show();
    });
});

$('#btnConfirmDelete1').on('click', function () {
  const mensaje = deleteUbicacionEstado == 1
    ? "Se desactivará esta ubicación en el sistema. ¿Desea continuar?"
    : "Esta ubicación será habilitada nuevamente. ¿Desea continuar?";

  $('#modalConfirmDelete2Body').text(mensaje);
  new bootstrap.Modal('#modalConfirmDelete2').show();
});

$('#btnConfirmDelete2').on('click', function () {
  fetch(`${assetsPath}ubicacionAjax/eliminar`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ id_ubicacion: deleteUbicacionId })
  })
    .then(res => res.json())
    .then(resp => {
      if (resp.success) {
        Toastify({
          text: `Estado actualizado correctamente ✅`,
          duration: 3000,
          style: { background: "#28a745" }
        }).showToast();
        $('.datatables-category-list').DataTable().ajax.reload(null, false);
        bootstrap.Modal.getInstance(document.getElementById('modalConfirmDelete2')).hide();
      } else {
        Toastify({ text: 'Error al cambiar estado ❌', style: { background: '#dc3545' } }).showToast();
      }
    });
});






});