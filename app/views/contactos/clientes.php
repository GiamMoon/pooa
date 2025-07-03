<script>
  window.PERMISOS_USUARIO = <?= json_encode($_SESSION['permisos']) ?>;
</script>
<!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-6">
              <div class="col-12">
                <div class="card">
                  <div style="display: flex;align-items: center;justify-content: space-between;">
                    <h5 class="card-header">Clientes</h5>
                    <div style="margin-right: 24px;">
                      <button class="btn btn-primary" id="addCliente">Añadir nuevo cliente</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="app-ecommerce-category">
        
              <!-- Proveedor List Table-->
              <div class="card">
                <div class="card-datatable">
                  <table class="datatables-category-list table" id="tablaClientes">
                    <thead class="border-top">
                      <tr>                        
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Documento</th>
                        <th>Nombre/Razón Social</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $i = 1; foreach ($clientes as $c): ?>
                        <tr data-id="<?= $c['id_cliente'] ?>">
                          <td><?= $i++ ?></td>
                          <td><?= ucfirst(strtolower($c['tipo_cliente'])) ?></td>
                          <td><?= $c['numero_documento'] ?></td>
                          <td>
                            <?= $c['tipo_cliente'] === 'NATURAL' 
                              ? $c['nombre'] . ' ' . $c['apellido']
                              : $c['razon_social'] ?>
                          </td>
                          <td><?= $c['email'] ?? '-' ?></td>
                          <td><?= $c['telefono'] ?? '-' ?></td>
                          <td>
                          <div class="form-check form-switch">
                            <input 
                              class="form-check-input toggle-estado" 
                              type="checkbox" 
                              data-id="<?= $c['id_cliente'] ?>"
                              <?= $c['activo'] ? 'checked' : '' ?>>
                          </div>
                        </td>
                          <td>
                            <button class="btn btn-sm btn-primary editar-cliente" data-id="<?= $c['id_cliente'] ?>">
                              <i class="bx bx-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger eliminar-cliente" data-id="<?= $c['id_cliente'] ?>">
                              <i class="bx bx-trash"></i>
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>

           
                <!-- Modal -->
                <div class="modal fade" id="clienteModal" tabindex="-1"  >
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="titleCliente"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row g-6">
                          <div class="col-4 mb-6">
                            <label for="nameCliente" class="form-label">Tipo de identidad</label>
                            <select id="tipoDocumento"  class="form-select" data-allow-clear="true" tabindex="-1" >
                                <option value="1" selected>DNI</option>  
                                <option value="2">RUC</option>  
                            </select>
                          </div>
                          <div class="col-8 mb-6">
                            <label for="nameComercialCliente" class="form-label">Numero de identidad</label>
                            <div class="input-group ">
                                <input type="text" id="inputClienteNumber" class="form-control" placeholder="Buscar..." aria-label="Buscar..." aria-describedby="basic-addon-search31">
                                <button class="input-group-text btn btn-label-secondary" onclick="buscarClientePorDocumento()"><i class="icon-base bx bx-search"></i> Buscar</button>
                            </div>
                            <input type="hidden" id="clienteId">
                          </div>
                        </div>
                        <div class="row g-6" id="type_dni" style="display: flex;">
                          <div class="col mb-6">
                            <label for="nombreCliente" class="form-label">Nombre</label>
                            <input type="text" id="nombreCliente" class="form-control" placeholder="" />
                          </div>
                          <div class="col mb-6">
                            <label for="apellidoCliente" class="form-label">Apellidos</label>
                            <input type="text" id="apellidoCliente" class="form-control" placeholder="" />
                          </div>
                        </div>
                        <div class="row g-6" id="type_ruc" style="display: none;">
                          <div class="col mb-6">
                            <label for="razonSocialCliente" class="form-label">Razon social</label>
                            <input type="text" id="razonSocialCliente" class="form-control" placeholder="" />
                          </div>
                          <div class="col mb-6">
                            <label for="nombreRepresentanteCliente" class="form-label">Nombre representante</label>
                            <input type="text" id="nombreRepresentanteCliente" class="form-control" placeholder="" />
                          </div>
                        </div>
                        <div class="row g-6">
                          <div class="col mb-6">
                            <label for="ubigeoCliente" class="form-label">Ubigeo</label>
                            <input type="text" id="ubigeoCliente" class="form-control" placeholder="" />
                          </div>
                          <div class="col mb-6">
                            <label for="direccionCliente" class="form-label">Dirección</label>
                            <input type="text" id="direccionCliente" class="form-control" placeholder="" />
                          </div>
                        </div>
                        <div class="row g-6">
                          <div class="col mb-0">
                            <label for="emailCliente" class="form-label">Email</label>
                            <input type="email" id="emailCliente" class="form-control" placeholder="xxxx@xxx.xx" />
                          </div>
                          <div class="col mb-0">
                            <label for="telefonoCliente" class="form-label">Telefono</label>
                            <input type="text" id="telefonoCliente" class="form-control" />
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="guardarCliente()">Guardar</button>
                      </div>
                    </div>
                  </div>
                </div>

 
<div id="toas" class="bs-toast toast toast-ex animate__animated my-2 fade bg-primary animate__tada hide" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
    <div class="toast-header">
      <i class="icon-base bx bx-bell me-2"></i>
      <div class="me-auto fw-medium" id="title_toast">title</div>
      <small>Mensaje:</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body" id="mensaje_toast">mensaje</div>
  </div>


            </div>
          </div>
            <!-- / Content -->

<script>
document.querySelectorAll('.toggle-estado').forEach(toggle => {
  toggle.addEventListener('change', function () {
    const id = this.dataset.id;
    const nuevoEstado = this.checked ? 1 : 0;

    fetch('<?= BASE_URL ?>contactos/toggleEstado', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id_cliente: id, activo: nuevoEstado })
    })
    .then(res => res.json())
    .then(data => {
      if (data.status !== 'ok') {
        mostrarToast('Error','Error al actualizar', 'bg-danger');
        this.checked = !this.checked; // revertir cambio si falla
      }else{
        mostrarToast('Exito','Estado actualizado correctamente', 'bg-success');
      }
    })
    
    .catch(() => {
      alert('Error en el servidor');
      this.checked = !this.checked;
    });
  });
});


document.getElementById('addCliente').addEventListener('click', function(){

        document.getElementById('titleCliente').textContent = 'Nuevo cliente';
        document.getElementById('clienteId').value = '';
        document.getElementById('nombreCliente').value = '';
        document.getElementById('apellidoCliente').value =  '';
        document.getElementById('razonSocialCliente').value = '';
        document.getElementById('nombreRepresentanteCliente').value = '';
        document.getElementById('inputClienteNumber').value = '';
        document.getElementById('emailCliente').value = '';
        document.getElementById('telefonoCliente').value = '';
        document.getElementById('direccionCliente').value = '';
        document.getElementById('ubigeoCliente').value = '';

        actualizarCamposPorTipo('1');
        document.getElementById('tipoDocumento').value === '1';

        // mostrar modal
        new bootstrap.Modal(document.getElementById('clienteModal')).show();

})
/*
document.querySelectorAll('.editar-cliente').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.dataset.id;
    fetch(`<?= BASE_URL ?>contactos/clienteById/${id}`)
      .then(res => res.json())
      .then(data => {
         document.getElementById('titleCliente').textContent = 'Editar Cliente';
        document.getElementById('clienteId').value = data.id_cliente;
        document.getElementById('nombreCliente').value = data.nombre || '';
        document.getElementById('apellidoCliente').value = data.apellido || '';
        document.getElementById('razonSocialCliente').value = data.razon_social || '';
        document.getElementById('nombreRepresentanteCliente').value = data.nombre_representante || '';
        document.getElementById('inputClienteNumber').value = data.numero_documento;
        document.getElementById('emailCliente').value = data.email;
        document.getElementById('telefonoCliente').value = data.telefono;
        document.getElementById('direccionCliente').value = data.direccion;
        document.getElementById('ubigeoCliente').value = data.id_ubigeo;

        actualizarCamposPorTipo(String(data.tipo));
        document.getElementById('tipoDocumento').value = String(data.tipo);

         new bootstrap.Modal(document.getElementById('clienteModal')).show();
      });
  });
});

document.querySelectorAll('.eliminar-cliente').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.dataset.id;
    const fila = this.closest('tr');

    if (confirm('¿Estás seguro de eliminar este cliente?')) {
      fetch(`<?= BASE_URL ?>contactos/eliminarCliente`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'ok') {
          mostrarToast('Éxito', 'Cliente eliminado correctamente', 'bg-success');
          fila.remove();
        } else {
          mostrarToast('Error', 'No se pudo eliminar el cliente', 'bg-danger');
        }
      })
      .catch(err => {
        console.error(err);
        mostrarToast('Error', 'Error en la conexión con el servidor', 'bg-danger');
      });
    }
  });
});
*/
 
function buscarClientePorDocumento() {
  const tipo = document.getElementById('tipoDocumento').value;
  const numero = document.getElementById('inputClienteNumber').value.trim();
  if (!numero) {
    mostrarToast('Error', 'Debes ingresar un número de documento.', 'bg-danger');
    return;
  }
  if (tipo === '1' && numero.length !== 8) {
    mostrarToast('Error', 'El DNI debe tener 8 dígitos.', 'bg-danger');
    return;
  }

  if (tipo === '3' && numero.length !== 11) {
    mostrarToast('Error', 'El RUC debe tener 11 dígitos.', 'bg-danger');
    return;
  }
  const url =
    tipo === '1' ? `https://api.factiliza.com/v1/dni/info/${numero}` : `https://api.factiliza.com/v1/ruc/info/${numero}`;
    
  const options = {
    method: 'GET',
    headers: {
      Authorization: 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzOTAxNSIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6ImNvbnN1bHRvciJ9.40vpWF-S7M7h3Pw74xe86FXEn1VEqNXK3OT7ITcf7no'
    }
  };

  fetch(url,  options)
    .then(res => res.json())
    .then(res => {
      if (res.success && res.data) {
        if (tipo === '1') {
          llenarCamposDNI(res.data);
        } else {
          llenarCamposRUC(res.data);
        }
        mostrarToast('Éxito', 'Datos cargados correctamente.', 'bg-success');
      } else {
        mostrarToast('Error', 'No se encontraron datos para ese número.', 'bg-danger');
      }
    })
    .catch(err => {
      console.error(err);
      mostrarToast('Error', 'No se pudo conectar con la API.', 'bg-danger');
    });
}

function llenarCamposDNI(data) {
  // Mostrar campos de DNI y ocultar RUC (opcional si ya se hizo)
  actualizarCamposPorTipo('1');

  document.getElementById('nombreCliente').value = data.nombres ?? '';
  document.getElementById('apellidoCliente').value = `${data.apellido_paterno ?? ''} ${data.apellido_materno ?? ''}`.trim();
  document.getElementById('ubigeoCliente').value = data.ubigeo_reniec ?? data.ubigeo?.[2] ?? '';

  document.getElementById('direccionCliente').value = data.direccion_completa ?? data.direccion ?? '';


  // Limpiar campos de RUC por si quedaron datos
  document.getElementById('razonSocialCliente').value = '';
  document.getElementById('nombreRepresentanteCliente').value = '';
}

function llenarCamposRUC(data) {
  // Mostrar campos de RUC y ocultar DNI
  actualizarCamposPorTipo('2');

  document.getElementById('razonSocialCliente').value = data.nombre_o_razon_social ?? '';
  document.getElementById('nombreRepresentanteCliente').value = ''; // No viene en la API, lo dejas en blanco
  document.getElementById('direccionCliente').value = data.direccion_completa ?? data.direccion ?? '';
  document.getElementById('ubigeoCliente').value = data.ubigeo_sunat ?? data.ubigeo?.[2] ?? '';

  // Limpiar campos de DNI
  document.getElementById('nombreCliente').value = '';
  document.getElementById('apellidoCliente').value = '';
}



function actualizarCamposPorTipo(tipoValor) {
  const dniSection = document.getElementById('type_dni');
  const rucSection = document.getElementById('type_ruc');

  if (tipoValor === '1') { // DNI
    dniSection.style.display = 'flex';
    rucSection.style.display = 'none';
  } else if (tipoValor === '2') { // RUC
    dniSection.style.display = 'none';
    rucSection.style.display = 'flex';
  } else {
    // Ocultar ambos si es otro tipo
    dniSection.style.display = 'none';
    rucSection.style.display = 'none';
  }
}

document.getElementById('tipoDocumento').addEventListener('change', function () { actualizarCamposPorTipo(this.value); });



function guardarCliente() {
  const tipoCliente = document.getElementById('tipoDocumento').value === '1' ? 'NATURAL' : 'JURIDICO';

  const data = new FormData();
  data.append('id_cliente', document.getElementById('clienteId').value);
  data.append('tipo_cliente', tipoCliente);
  data.append('id_tipo_documento', document.getElementById('tipoDocumento').value);
  data.append('numero_documento', document.getElementById('inputClienteNumber').value);
  data.append('email', document.getElementById('emailCliente').value);
  data.append('telefono', document.getElementById('telefonoCliente').value);
  data.append('direccion', document.getElementById('direccionCliente').value);
  data.append('id_ubigeo', document.getElementById('ubigeoCliente').value);

  if (tipoCliente === 'NATURAL') {
    data.append('nombre', document.getElementById('nombreCliente').value);
    data.append('apellido', document.getElementById('apellidoCliente').value);
  } else {
    data.append('razon_social', document.getElementById('razonSocialCliente').value);
    data.append('nombre_representante', document.getElementById('nombreRepresentanteCliente').value);
  }

  fetch(`<?= BASE_URL ?>contactos/guardarCliente` , {
    method: 'POST',
    body: data
  })
  .then(res => res.json())
  .then(res => {console.log(res);
    if (res.success) {

      mostrarToast('Éxito', 'Guardado exitosamente', 'bg-success');

      if (!document.getElementById('clienteId').value) {
        insertarFilaCliente(res.cliente);
      } else {
        actualizarFilaCliente(res.cliente);
      }

      // Cierra el modal
      bootstrap.Modal.getInstance(document.getElementById('clienteModal')).hide();
  
    } else { 
      mostrarToast('Error', res.message, 'bg-danger');
    }
  });
}

function insertarFilaCliente(cliente) {
  const tabla = document.querySelector('#tablaClientes tbody');
  const fila = document.createElement('tr');
  fila.setAttribute('data-id', cliente.id_cliente);

  // Crear posición (#) dinámicamente
  const posicion = tabla.children.length + 1;

  fila.innerHTML = `
    <td>${posicion}</td>
    <td>${cliente.tipo_cliente}</td>
    <td>${cliente.numero_documento}</td>
    <td>${cliente.tipo_cliente === 'NATURAL' ? cliente.nombre + ' ' + cliente.apellido : cliente.razon_social}</td>
    <td>${cliente.email || '-'}</td>
    <td>${cliente.telefono || '-'}</td>
    <td>
      <div class="form-check form-switch">
        <input 
          class="form-check-input toggle-estado" 
          type="checkbox" 
          data-id="${cliente.id_cliente}" 
          ${cliente.activo == 1 ? 'checked' : ''}>
      </div>
    </td>
    <td>
      <button class="btn btn-sm btn-primary editar-cliente" data-id="${cliente.id_cliente}">
        <i class="bx bx-edit"></i>
      </button>
      <button class="btn btn-sm btn-danger eliminar-cliente" data-id="${cliente.id_cliente}">
        <i class="bx bx-trash"></i>
      </button>
    </td>
  `;

  tabla.prepend(fila);
 
}


function actualizarFilaCliente(cliente) {
  const fila = document.querySelector(`#tablaClientes tbody tr[data-id="${cliente.id_cliente}"]`);
  if (!fila) return;

  fila.innerHTML = `
    <td>${fila.rowIndex}</td>
    <td>${cliente.tipo_cliente}</td>
    <td>${cliente.numero_documento}</td>
    <td>${cliente.tipo_cliente === 'NATURAL' ? cliente.nombre + ' ' + cliente.apellido : cliente.razon_social}</td>
    <td>${cliente.email || '-'}</td>
    <td>${cliente.telefono || '-'}</td>
    <td>
      <div class="form-check form-switch">
        <input 
          class="form-check-input toggle-estado" 
          type="checkbox" 
          data-id="${cliente.id_cliente}" 
          ${cliente.activo == 1 ? 'checked' : ''}>
      </div>
    </td>
    <td>
      <button class="btn btn-sm btn-primary editar-cliente" data-id="${cliente.id_cliente}">
        <i class="bx bx-edit"></i>
      </button>
      <button class="btn btn-sm btn-danger eliminar-cliente" data-id="${cliente.id_cliente}">
        <i class="bx bx-trash"></i>
      </button>
    </td>
  `;
 
}

document.querySelector('#tablaClientes tbody').addEventListener('click', function (e) {
  const editBtn = e.target.closest('.editar-cliente');
  const deleteBtn = e.target.closest('.eliminar-cliente');

  if (editBtn) {
    const id = editBtn.dataset.id;
    fetch(`<?= BASE_URL ?>contactos/clienteById/${id}`)
      .then(res => res.json())
      .then(data => {
        document.getElementById('titleCliente').textContent = 'Editar Cliente';
        document.getElementById('clienteId').value = data.id_cliente;
        document.getElementById('nombreCliente').value = data.nombre || '';
        document.getElementById('apellidoCliente').value = data.apellido || '';
        document.getElementById('razonSocialCliente').value = data.razon_social || '';
        document.getElementById('nombreRepresentanteCliente').value = data.nombre_representante || '';
        document.getElementById('inputClienteNumber').value = data.numero_documento;
        document.getElementById('emailCliente').value = data.email;
        document.getElementById('telefonoCliente').value = data.telefono;
        document.getElementById('direccionCliente').value = data.direccion;
        document.getElementById('ubigeoCliente').value = data.codigo_ubigeo;
        actualizarCamposPorTipo(String(data.tipo));
        document.getElementById('tipoDocumento').value = String(data.tipo);
        new bootstrap.Modal(document.getElementById('clienteModal')).show();
      });
  }

  if (deleteBtn) {
    const id = deleteBtn.dataset.id;
    const fila = deleteBtn.closest('tr');

    if (confirm('¿Estás seguro de eliminar este cliente?')) {
      fetch(`<?= BASE_URL ?>contactos/eliminarCliente`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'ok') {
          mostrarToast('Éxito', 'Cliente eliminado correctamente', 'bg-success');
          fila.remove();
        } else {
          mostrarToast('Error', 'No se pudo eliminar el cliente', 'bg-danger');
        }
      })
      .catch(err => {
        console.error(err);
        mostrarToast('Error', 'Error en la conexión con el servidor', 'bg-danger');
      });
    }
  }
});


function mostrarToast(title, message, type = 'bg-success') {
  const toast = document.getElementById('toas');
  toast.classList.remove('bg-primary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-info', 'bg-secondary', 'bg-dark', 'bg-light');
  toast.classList.add(type);
  document.getElementById('title_toast').textContent = title;
  document.getElementById('mensaje_toast').textContent = message;
  const toastBootstrap = new bootstrap.Toast(toast);
  toastBootstrap.show();
}

 

</script>