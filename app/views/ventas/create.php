<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-6">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Nueva venta</h5>
                <div class="card-body">
                    <div class="row g-6" style="display: flex;align-items: flex-end;">
                        <div class="col-md-2">
                            <label for="select2Basic" class="form-label">Tipo de documento</label>
                            <div class="position-relative">
                                <select class="form-select" data-allow-clear="true" tabindex="-1" aria-hidden="true" id="comprobante">
                                  <option value="1">Boleta</option>
                                  <option value="2">Factura</option>
                                  <option value="3">Nota de venta</option>
                                </select>
                            </div>
                        </div>
<style>
  .css_clientes {
    position: absolute;
    background: white;
    padding: 5px;
    width: 100%;
    margin-top: 5px;
    border-radius: 10px;
    box-shadow: 0 0.1875rem 0.5rem 0 rgba(34, 48, 62, 0.1);
    max-height: 200px;
    overflow-y: auto;
    z-index: 10;
    display: none;
    overflow-y: auto;
        flex-direction: column;
  }

  .css_clientes div {
    border-radius: 5px;
    padding: 5px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    width: 100%;
  }

  .css_clientes div:hover {
    background: #e4e4e459;
  }
</style>

<div class="col-md-3">
  <label class="form-label" style="margin-bottom: 0.7rem;">Cliente</label>
  <button type="button" class="btn btn-xs rounded-pill btn-label-primary" onclick="abrirModalCliente('nuevo')" >Nuevo</button>
  <div class="position-relative">
    <div>
      <input type="text" id="inputCliente" class="form-control" value="Cliente Varios" data-nombre="Cliente" data-apellido="varios" data-documento="9999999" data-nombre-comercial="" data-email="" data-telefono="" data-id="1" data-razon="" data-direccion="" data-ubigeo="" data-tipe="1" data-razon-social="" data-representante="" readonly>
      <div id="btn_clnts" style="position: absolute; top: 7px; right: 5px;">
        <button type="button" class="btn btn-xs rounded-pill btn-outline-primary" onclick="abrirModalCliente('editar')" style="margin-right: 5px;">Editar</button>
        <button type="button" class="btn btn-xs rounded-pill btn-outline-danger" onclick="quitarCliente()">Quitar</button>
      </div>
    </div>
    <div id="listaClientes" class="css_clientes"> </div>
    <input type="hidden" name="id_cliente" id="id_cliente" value="1">
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
<script>

function quitarCliente() {
  const input = document.getElementById('inputCliente');
  //input.removeAttribute('readonly');
  input.value = '';
  input.setAttribute('data-id', '');
  input.setAttribute('data-nombre', '');
  input.setAttribute('data-apellido', '');
  input.setAttribute('data-documento', '');
  input.setAttribute('data-nombre-comercial', '');
  input.setAttribute('data-razon', '');
  input.setAttribute('data-direccion', '');
  input.setAttribute('data-ubigeo', '');
  input.setAttribute('data-email', '');
  input.setAttribute('data-telefono', '');
  input.setAttribute('data-tipe', ''); 

  document.getElementById('id_cliente').value = '';
  document.getElementById('listaClientes').style.display = 'none';
  document.getElementById('btn_clnts').style.display = 'none';
  document.getElementById('inputCliente').removeAttribute('readonly');
}

document.getElementById('inputCliente').addEventListener('input', function () {
  const query = this.value;
  if (query.length >= 2) {
    
    fetch('<?= BASE_URL ?>contactos/buscar', {
      method: 'POST',
      body: new URLSearchParams({ query }),
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(res => res.json())
    .then(data => {
      const lista = document.getElementById('listaClientes');
      lista.innerHTML = '';
      lista.style.display = 'flex';

data.forEach(cliente => {
  const div = document.createElement('div');

  const esNatural = cliente.tipo_cliente === 'NATURAL';

  const nombreCompleto = esNatural 
    ? `${cliente.nombre_natural ?? ''} ${cliente.apellido_natural ?? ''}`.trim() 
    : cliente.razon_social;

  div.setAttribute('data-id', cliente.id_cliente);
  div.setAttribute('data-documento', cliente.numero_documento);
  div.setAttribute('data-nombre', cliente.nombre_natural ?? '');
  div.setAttribute('data-apellido', cliente.apellido_natural ?? '');
  div.setAttribute('data-razon-social', cliente.razon_social ?? '');
  div.setAttribute('data-representante', cliente.nombre_representante ?? '');
  div.setAttribute('data-email', cliente.email ?? '');
  div.setAttribute('data-telefono', cliente.telefono ?? '');
  div.setAttribute('data-direccion', cliente.direccion ?? '');
  div.setAttribute('data-ubigeo', cliente.codigo_ubigeo ?? '');
  div.setAttribute('data-tipe', cliente.tipo_cliente === 'NATURAL' ? '1' : '2');

  div.innerHTML = `
    <span>${nombreCompleto}</span>
    <span>${cliente.numero_documento}</span>
  `;

  div.onclick = () => {
    const input = document.getElementById('inputCliente');
    input.value = nombreCompleto.slice(0, 12) + '...';;

    input.setAttribute('data-id', cliente.id_cliente);
    input.setAttribute('data-documento', cliente.numero_documento);
    input.setAttribute('data-nombre', cliente.nombre_natural ?? '');
    input.setAttribute('data-apellido', cliente.apellido_natural ?? '');
    input.setAttribute('data-razon-social', cliente.razon_social ?? '');
    input.setAttribute('data-representante', cliente.nombre_representante ?? '');
    input.setAttribute('data-email', cliente.email ?? '');
    input.setAttribute('data-telefono', cliente.telefono ?? '');
    input.setAttribute('data-direccion', cliente.direccion ?? '');
    input.setAttribute('data-ubigeo', cliente.codigo_ubigeo ?? '');
    input.setAttribute('data-tipe', cliente.tipo_cliente === 'NATURAL' ? '1' : '2');

    document.getElementById('id_cliente').value = cliente.id_cliente;
    input.setAttribute('readonly', true);
    document.getElementById('btn_clnts').style.display = 'flex';
    document.getElementById('listaClientes').style.display = 'none';
  };

  lista.appendChild(div);
});


    });
  }
});

function abrirModalCliente(modo) {
  const modal = new bootstrap.Modal(document.getElementById('clienteModal'));
  const title = document.getElementById('titleCliente');

  if (modo === 'nuevo') {
    title.textContent = 'Nuevo Cliente';
    limpiarCamposCliente();
    actualizarCamposPorTipo('1');
        document.getElementById('tipoDocumento').value === '1';
  } else if (modo === 'editar') {
    const input = document.getElementById('inputCliente');
    title.textContent = input.getAttribute('data-nombre') || 'Editar Cliente';

    // Asignar valores existentes desde atributos del input
document.getElementById('inputClienteNumber').value = input.getAttribute('data-documento') || '';
document.getElementById('nombreCliente').value = input.getAttribute('data-nombre') || '';
document.getElementById('apellidoCliente').value = input.getAttribute('data-apellido') || '';
document.getElementById('razonSocialCliente').value = input.getAttribute('data-razon-social') || '';
document.getElementById('nombreRepresentanteCliente').value = input.getAttribute('data-representante') || '';
document.getElementById('ubigeoCliente').value = input.getAttribute('data-ubigeo') || '';
document.getElementById('direccionCliente').value = input.getAttribute('data-direccion') || '';
document.getElementById('emailCliente').value = input.getAttribute('data-email') || '';
document.getElementById('telefonoCliente').value = input.getAttribute('data-telefono') || '';
document.getElementById('tipoDocumento').value = input.getAttribute('data-tipe') || '1';

 
    actualizarCamposPorTipo(input.getAttribute('data-tipe'));
        document.getElementById('tipoDocumento').value = input.getAttribute('data-tipe');
  }

  modal.show();
}

function limpiarCamposCliente() {
  document.getElementById('inputClienteNumber').value = '';
  document.getElementById('nombreCliente').value = '';
  document.getElementById('apellidoCliente').value = '';
  document.getElementById('razonSocialCliente').value = '';
  document.getElementById('nombreRepresentanteCliente').value = '';
  document.getElementById('ubigeoCliente').value = '';
  document.getElementById('direccionCliente').value = '';
  document.getElementById('emailCliente').value = '';
  document.getElementById('telefonoCliente').value = '';
}




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
  data.append('id_cliente', document.getElementById('id_cliente').value);
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
  .then(res => {
    if (res.success) {
      const modal = bootstrap.Modal.getInstance(document.getElementById('clienteModal'));
      modal.hide(); 
      mostrarToast('Éxito', 'Guardado exitosamente', 'bg-success');
    } else { 
      mostrarToast('Error', res.message, 'bg-danger');
    }
  });
}

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
                        <div class="col-md-2">
                            <label for="select2Multiple" class="form-label">Fecha</label>
                            <div class="position-relative">
                              <?php $hoy = date('Y-m-d');$minFecha = date('Y-m-d', strtotime('-7 days'));$maxFecha = date('Y-m-d', strtotime('+7 days')); ?>
                              <input class="form-control" type="date" id="fecha"value="<?php echo $hoy; ?>"min="<?php echo $minFecha; ?>"max="<?php echo $maxFecha; ?>">
                            </div>
                        </div>

                        <div class="col-md-1">
                            <label for="select2Multiple" class="form-label">Moneda</label>
                            <div class="position-relative">
                                <select class="form-select" tabindex="-1" aria-hidden="true" id="moneda">
                                    <option value="pen">S/.</option>
                                    <option value="usd">$</option> 
                                </select> 
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="select2Multiple" class="form-label">Tipo de operación</label>
                            <div class="position-relative">
                                <select class="form-select" tabindex="-1" aria-hidden="true" id="tipo_operacion">
                                    <option value="1">Venta interna</option>
                                    <option value="2">Exportación de Bienes</option> 
                                </select> 
                            </div>
                        </div>

                        <div class="col-md-1">
                            <button type="button" class="btn btn-label-secondary">
                                <span class="icon-base bx bx-box icon-sm me-2"></span> Stock
                            </button>
                        </div>

                    </div>
                    <hr>
                    <div style="cursor: pointer; user-select: none;" onclick="const opciones = document.getElementById('vermasopciones');const spans = this.querySelectorAll('span');if (opciones.style.display === 'none' || opciones.style.display === '') {opciones.style.display = 'flex';spans[0].style.display = 'none';  spans[1].style.display = 'inline'; } else {opciones.style.display = 'none';spans[0].style.display = 'inline';spans[1].style.display = 'none';}">
                        <span><i class="ifs if-arrow-down-to-dotted-line "></i> Ver más</span>
                        <span style="display: none;"><i class="ifs if-arrow-up-from-dotted-line "></i> Ocultar</span>
                    </div> 
                    <hr>
                    <div class="row g-6" id="vermasopciones" style="display: none;">
                        <div class="col-md-2">
                            <label for="tipocambio" class="form-label">Tipo de cambio</label>
                            <div class="position-relative">
                                <input class="form-control" type="number" placeholder="0" value="3.57" id="valor_cambio">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="select2Multiple" class="form-label">Sucursal</label>
                            <div class="position-relative">
                                <select class="form-select" tabindex="-1" aria-hidden="true" id="sucursal">
                                    <option value="1">Oficina principal</option> 
                                </select> 
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="select2Multiple" class="form-label">Almacen</label>
                            <div class="position-relative">
                                <select class="form-select" tabindex="-1" aria-hidden="true" id="almacen" >
                                    <option value="1">Almacén - Oficina principal</option>
                                </select> 
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="select2Multiple" class="form-label">Vendedor</label>
                            <div class="position-relative">
                                <select class="form-select" tabindex="-1" aria-hidden="true" id="vendedor">
                                    <option value="0">Seleccionar vendedor</option>
                                    <option value="1" selected>Administrador</option>
                                    <option value="2">Vendedor</option>  
                                </select> 
                            </div>
                        </div>
 
 
                    </div>

                </div>
            </div>
            
        </div>
<style>
.img-fluid{
    filter: brightness(1.1); mix-blend-mode:multiply;
  }
.items-productos{
    background: white;
    box-shadow: 0 0.1875rem 0.5rem 0 rgba(34, 48, 62, 0.1);
    max-height: 200px;
    overflow-y: auto;
    border-radius: 10px;
    padding: 10px;
    width: 98%;
    position: absolute;
    margin-top: 10px;
        flex-direction: column;
}
.item-producto{
  background: #f7f7f7;
    display: flex;
    justify-content: space-between;
    padding: 5px 20px;
    border-radius: 10px ;
    align-items: center;
        margin-bottom: 8px;
}

.item-producto:hover{
      background: var(--bs-primary);
    color: #fff;
}

.item-detalle{
  display: flex;
  flex-direction: column;
}

.codbar{
    padding: 1px 5px;
    font-size: 10px; 
    border-radius: 50rem; 
        max-width: 150px;
    background-color:color-mix(in sRGB, var(--bs-paper-bg) var(--bs-bg-label-tint-amount), var(--bs-info)) ;
    color: var(--bs-info);
}

.item-producto:hover .item-detalle .codbar{
  background:white;
  color: var(--bs-primary);
}
.item-name{font-size: 13px}
.item-price{font-size: 12px;}

.ipretotal div{
  display: flex;
  justify-content: space-between;
  margin-bottom: 5px;
}
.ipretotal div .valor{
font-weight: bold;
}
.ipretotal div select{
width: 70px;
}
.ipretotal div input{
width: 70px;
font-weight: bold;
}

.totaltext, .totalnumber{
  font-size: 20px;
  font-weight: bold;
  margin-top: 5px;
}
.itm-title{
  margin: 0;
    font-size: 0.775rem;
    line-height: 1.57;
    font-weight: 400;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    display: inline-block;
    max-width: 200px;
}
</style>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-6">
                        <!-- Basic -->
                        <div class="col-md-12">
                          <div class="table-responsive text-nowrap">
                            <table class="table table-hover">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>DESCRIPCIÓN</th>
                                  <th>CANTIDAD</th>
                                  <th>PRECIO</th>
                                  <th>DESCUENTO</th>
                                  <th>IMPUESTO</th>
                                  <th>TOTAL</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody class="table-border-bottom-0"></tbody>
                            </table>
                          </div>
                        </div>
                        <!-- search -->
                        <div class="row g-6" style="display: flex;align-items: flex-end;">
                            <div class="col-md-11" style="position: relative;">
                                <div>
                                  <label for="defaultFormControlInput" class="form-label" style="margin-bottom: 0.7rem;">Producto/Servicio</label>
                                  <button type="button" class="btn btn-xs rounded-pill btn-label-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarProducto" >Nuevo</button>
                                  <input type="text" class="form-control" id="search-product" placeholder="Buscar producto/servicio">
                                </div>
                                <div class="items-productos" id="items-list-productos-result" style="display: none;"></div>
                            </div>
                            <div class="col-md-1" style="display: flex;">
                                <button type="button" class="btn btn-icon btn-label-secondary active" id="btn-buscar-nombre" style="margin-right:5px;">
                                    <i class="ifs if-search "></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-label-secondary" id="btn-buscar-codigo">
                                    <i class="ifs if-barcode "></i>
                                </button>
                            </div> 
                        </div> 
                        <div class="col-md-12" style="display: flex; justify-content: flex-end;">
                          <div class="col-md-3 ipretotal">
                            <div>
                              <span>Subtotal: </span><span class="valor" id="subtotalfinal">S/ 00.00</span></div>
                            <div>
                              <span>Impuesto (18%):</span><span class="valor" id="inpuestofinal">S/ 00.00</span></div>
                            <!--<div>
                              <span>Descuento</span>
                              <div>
                                <select class="form-select form-select-sm" id="tipo_descuento_general" aria-label="tipo de descuento">
                                    <option value="monetario" selected="">S/</option>
                                    <option value="porcentual">%</option> 
                                </select>
                                <input class="form-control form-control-sm" id="descuento_general" type="number" value="0">
                              </div>
                            </div>-->
                            <div>
                              <span class="totaltext">TOTAL:</span><span class="totalnumber" id="totalfinal">S/. 00.00</span>
                            </div>
                            <div>
                              <span></span>
                              <button type="button" class="btn btn-info" id="btnfinalcobro">Cobrar S/. 00.00</button>
                            </div>
                            
                          </div>
                        </div>
                         
                        <!-- Custom Template 
                        <div class="col-md-6">

                        </div>
                         
                        <div class="col-md-6">

                        </div>-->
                    </div>
                </div>
            </div>
        </div>
            
    </div>
</div>

<div class="modal fade" id="ventaExitosaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content text-center">
      <div class="modal-header">
        <h5 class="modal-title">¡Venta registrada correctamente!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p class="mb-3">Puedes visualizar el comprobante:</p>
        <div class="d-flex justify-content-center gap-2">
          <button id="btnDescargarA4" class="btn btn-label-primary abrir-pdf" data-url="">
            <i class="bx bx-download"></i> A4
          </button>
          <button id="btnDescargarTicket" class="btn btn-label-secondary abrir-pdf" data-url="">
            <i class="bx bx-receipt"></i> Ticket
          </button>
        </div>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


                <!-- Modal Registrar Producto -->
                <div class="modal fade" id="modalRegistrarProducto" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <form id="formRegistrarProducto" class="modal-content" enctype="multipart/form-data">
                      <div class="modal-header">
                        <h5 class="modal-title">Registrar Nuevo Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row g-3">
                          <div class="col-md-6">
                            <label for="reg-nombre-producto" class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control text-uppercase" id="reg-nombre-producto" name="nombre" maxlength="150">
                          </div>

                          <div class="col-md-6">
                            <label for="reg-categoria" class="form-label">Categoría</label>
                            <select class="form-select select2" id="reg-categoria" name="id_categoria" data-placeholder="Seleccione una categoría"></select>
                          </div>

                          <div class="col-md-6">
                            <label for="reg-marca" class="form-label">Marca del Producto</label>
                            <select class="form-select select2" id="reg-marca" name="id_marca" data-placeholder="Seleccione una marca"></select>
                          </div>

                          <div class="col-md-6">
                            <label for="reg-tipo-unidad" class="form-label">Tipo de Unidad</label>
                            <select class="form-select select2" id="reg-tipo-unidad" name="id_tipo_unidad" data-placeholder="Seleccione tipo de unidad"></select>
                          </div>

                          <div class="col-md-6">
                            <label for="reg-cantidad-min" class="form-label">Cantidad Mínima</label>
                            <input type="number" class="form-control" id="reg-cantidad-min" name="cantidad_min" min="15">
                          </div>

                          <div class="col-md-6">
                            <label for="reg-cantidad-max" class="form-label">Cantidad Máxima</label>
                            <input type="number" class="form-control" id="reg-cantidad-max" name="cantidad_max" min="16">
                          </div>

                          <div class="col-md-12">
                            <label for="reg-descripcion" class="form-label">Descripción (opcional)</label>
                            <textarea class="form-control" id="reg-descripcion" name="descripcion" rows="2" maxlength="250"></textarea>
                          </div>

                          <div class="col-md-6">
                            <label for="reg-precio-venta" class="form-label">Precio de Venta (opcional)</label>
                            <input type="number" step="0.01" class="form-control" id="reg-precio-venta" name="precio_venta" min="0.00">
                          </div>

                          <div class="col-md-6">
                            <label for="reg-url-imagen" class="form-label">Imagen (opcional)</label>
                            <input type="file" class="form-control" id="reg-url-imagen" name="url_imagen" accept="image/png, image/jpeg, image/webp">
                          </div>

                          <div class="col-md-12">
                            <label for="reg-marcas-moto" class="form-label">Marcas de Moto</label>
                            <select id="reg-marcas-moto" class="form-select select2-multiple" multiple="multiple" data-placeholder="Seleccione una o más marcas"></select>
                          </div>

                          <div class="col-md-12 mt-3" id="contenedor-marcas-modelos">
                            <!-- Aquí se renderizarán dinámicamente los modelos agrupados por marca -->
                          </div>

                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Registrar Producto</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                      </div>
                    </form>
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



<script>
const input = document.getElementById('search-product');
const contenedor = document.getElementById('items-list-productos-result');

input.addEventListener('input', function () {
  const query = this.value;
  const idSucursal = document.getElementById('sucursal').value;

  if (query.length === 0) {
    contenedor.style.display = 'none';
    contenedor.innerHTML = '';
    return;
  }

  if (query.length >= 2) {
    fetch('<?= BASE_URL ?>ventas/buscar', {
      method: 'POST',
      body: new URLSearchParams({ query, id_sucursal: idSucursal }),
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(res => res.json())
    .then(productos => {
      contenedor.innerHTML = '';
      contenedor.style.display = 'flex';

      productos.forEach(p => {
        const div = document.createElement('div');
        div.className = 'item-producto';
        div.innerHTML = `
          <div class="item-detalle">
            <span class="codbar">${p.codigo_producto}</span>
            <span class="item-name"><strong>${p.nombre}</strong></span>
            <span class="item-price">Precio: S/. ${parseFloat(p.precio_venta).toFixed(2)}</span>
          </div>
          <div class="item-detalle">
            <span>Stock:</span><span>${p.stock}</span>
          </div>
        `;
        div.onclick = () => agregarProductoATabla(p);
        contenedor.appendChild(div);
      });
    });
  }
});

// Ocultar si se hace clic fuera del input o lista
document.addEventListener('click', function (e) {
  if (!input.contains(e.target) && !contenedor.contains(e.target)) {
    contenedor.style.display = 'none';
  }
});

// Mostrar de nuevo si tiene texto y se enfoca el input
input.addEventListener('focus', function () {
  if (this.value.length >= 0 && contenedor.children.length > 0) {
    contenedor.style.display = 'flex';
  }
});


let contador = 1;

function agregarProductoATabla(producto) {
  const tbody = document.querySelector('tbody.table-border-bottom-0');
  const filas = tbody.querySelectorAll('tr.producto-fila');

  // Verificar si el producto ya está en la tabla
  for (let fila of filas) {
    if (fila.getAttribute('data-id') === producto.id_producto.toString()) {
      const cantidadInput = fila.querySelector('.cantidad');
      cantidadInput.value = parseInt(cantidadInput.value) + 1;
      actualizarTotales();
      
      // limpiar búsqueda
      document.getElementById('search-product').value = "";
      const contenedor = document.getElementById('items-list-productos-result');
      contenedor.innerHTML = '';
      contenedor.style.display = 'none';

      return; // ya está, no lo volvemos a agregar
    }
  }


  // No existe, crear nueva fila
  const fila = document.createElement('tr'); 
  fila.setAttribute('data-id', producto.id_producto);
  fila.classList.add('producto-fila');
  fila.innerHTML = `
    <td>${contador++}</td>
    <td>
      <img class="img-fluid" src="../uploads/products/${producto.url_imagen}" alt="${producto.nombre}" width="50">
      <span class="itm-title">${producto.nombre}</span>
    </td>
    <td><input class="form-control form-control-sm cantidad" type="number" value="1" min="1"></td>
    <td><input class="form-control form-control-sm precio" type="number" value="${producto.precio_venta}"></td>
    <td style="display: flex; padding: 1.5rem;">
      <select class="form-select form-select-sm tipo-descuento">
        <option value="monetario">S/</option>
        <option value="porcentual">%</option>
      </select>
      <input class="form-control form-control-sm descuento" type="number" value="0">
    </td>
    <td>
      <select class="form-select form-select-sm impuesto">
        <option value="1" selected>Gravado</option>
        <option value="2">Exonerado</option>
        <option value="3">Gratuito</option>
        <option value="4">Inafecto</option>
      </select>
    </td>
    <td class="subtotal">S/ ${producto.precio_venta}</td>
    <td><a class="dropdown-item text-danger" onclick="this.closest('tr').remove(); actualizarTotales();"><i class="icon-base bx bx-trash"></i></a></td>
  `;

  fila.querySelectorAll('input, select').forEach(el => {
    el.addEventListener('input', actualizarTotales);
  });

  tbody.appendChild(fila);
  actualizarTotales();

  // limpiar búsqueda
  document.getElementById('search-product').value = "";
  const contenedor = document.getElementById('items-list-productos-result'); 
  contenedor.innerHTML = '';
  contenedor.style.display = 'none';
}


function actualizarTotales() {
  let subtotal = 0;
  const filas = document.querySelectorAll('tbody tr');

  filas.forEach(tr => {
    const cantidad = parseFloat(tr.querySelector('.cantidad').value || 0);
    const precio = parseFloat(tr.querySelector('.precio').value || 0);
    const tipo_desc = tr.querySelector('.tipo-descuento').value;
    const desc = parseFloat(tr.querySelector('.descuento').value || 0);

    let total = cantidad * precio;

    // Validar y aplicar descuento individual
    //if (tipo_desc === 'porcentual') {
      //const porcentaje = Math.min(desc, 100); // máximo 100%
      //total -= total * (porcentaje / 100);
    //} else {
      //const maxDesc = total;
      //total -= Math.min(desc, maxDesc); // evita que el total sea negativo
    //}

// Aplicar descuento con tope máximo del 10% del valor total del producto
const descuentoInput = tr.querySelector('.descuento');
 
const descuentoMaximo = cantidad * precio * 0.10;
let montoDescuento = 0;

if (tipo_desc === 'porcentual') {
  const porcentaje = parseFloat(desc);
  montoDescuento = (cantidad * precio) * (porcentaje / 100);

  if (montoDescuento > descuentoMaximo) {
    alert(`El descuento máximo permitido es 10% = S/ ${descuentoMaximo.toFixed(2)}.`);
    montoDescuento = descuentoMaximo;

    // Actualizar el input con el % equivalente al máximo permitido
    const maxPorcentaje = (descuentoMaximo / (cantidad * precio)) * 100;
    descuentoInput.value = maxPorcentaje.toFixed(2);
  }
} else {
  montoDescuento = parseFloat(desc);

  if (montoDescuento > descuentoMaximo) {
    alert(`El descuento máximo permitido es 10% = S/ ${descuentoMaximo.toFixed(2)}.`);
    montoDescuento = descuentoMaximo;

    // Actualizar el input con el valor máximo
    descuentoInput.value = descuentoMaximo.toFixed(2);
  }
}

total -= montoDescuento;


    if (total < 0) total = 0;

    subtotal += total;
    tr.querySelector('.subtotal').textContent = 'S/ ' + total.toFixed(2);
  });

  // Calcular IGV
  const igv = subtotal * 0.18;

  //const tipoDescuentoGeneral = document.getElementById('tipo_descuento_general').value;
  //let descuentoGeneral = parseFloat(document.getElementById('descuento_general').value || 0);
  let totalFinal = subtotal + igv;

  // Validar y aplicar descuento general
  /*if (tipoDescuentoGeneral === 'porcentual') {
    if (descuentoGeneral > 100) descuentoGeneral = 100;
    totalFinal -= totalFinal * (descuentoGeneral / 100);
  } else {
    const maxDescGen = totalFinal;
    totalFinal -= Math.min(descuentoGeneral, maxDescGen);
  } */

  if (totalFinal < 0) totalFinal = 0;

  // Mostrar resultados
  document.getElementById('subtotalfinal').textContent = 'S/ ' + subtotal.toFixed(2);
  document.getElementById('inpuestofinal').textContent = 'S/ ' + igv.toFixed(2);
  document.getElementById('totalfinal').textContent = 'S/ ' + totalFinal.toFixed(2);
  document.getElementById('btnfinalcobro').textContent = 'Cobrar S/ ' + totalFinal.toFixed(2);
}

//document.getElementById('tipo_descuento_general').addEventListener('change', actualizarTotales);
//document.getElementById('descuento_general').addEventListener('input', actualizarTotales);



document.getElementById('btnfinalcobro').addEventListener('click', () => {
  // Validación del descuento general
  //const tipoDescuentoGeneral = document.getElementById('tipo_descuento_general').value;
  //const valorDescuentoGeneral = parseFloat(document.getElementById('descuento_general').value) || 0;

  //const descuentoGeneral = valorDescuentoGeneral > 0 ? { tipo: tipoDescuentoGeneral, valor: valorDescuentoGeneral } : 'none';

  const venta = {
    tipo_comprobante: document.getElementById('comprobante').value,
    id_cliente: document.getElementById('id_cliente').value,
    fecha: document.getElementById('fecha').value,
    moneda: document.getElementById('moneda').value,
    tipo_operacion: document.getElementById('tipo_operacion').value,
    valor_cambio: document.getElementById('valor_cambio').value,
    sucursal: document.getElementById('sucursal').value,
    almacen: document.getElementById('almacen').value,
    vendedor: document.getElementById('vendedor').value,
    //descuento_general: descuentoGeneral,
    productos: []
  };

  console.log('clickcomprar');

  document.querySelectorAll('tbody.table-border-bottom-0 tr.producto-fila').forEach(tr => {
    const idProducto = tr.getAttribute('data-id');
    const cantidad = parseFloat(tr.querySelector('.cantidad')?.value) || 0;
    const precio = parseFloat(tr.querySelector('.precio')?.value) || 0;

    const tipoDescuento = tr.querySelector('.tipo-descuento')?.value || '';
    const valorDescuento = parseFloat(tr.querySelector('.descuento')?.value) || 0;

    const descuento = valorDescuento > 0
      ? { tipo: tipoDescuento, valor: valorDescuento }
      : 'none';

    const impuesto = tr.querySelector('.impuesto')?.value || '';
    const subtotalText = tr.querySelector('.subtotal')?.textContent || 'S/ 0.00';
    const subtotal = parseFloat(subtotalText.replace('S/', '').trim()) || 0;

    venta.productos.push({
      id_producto: idProducto,
      cantidad: cantidad,
      precio: precio,
      descuento: descuento,
      impuesto: impuesto,
      total: subtotal
    });
  });

  fetch('<?= BASE_URL ?>ventas/addventa', {
    method: 'POST',
    body: JSON.stringify(venta),
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(res => res.json())
  .then(res => {
    if (res.status === 'ok') {
      const idVenta = res.id_venta;

      // Actualizar los botones del modal
      document.getElementById('btnDescargarA4').setAttribute('data-url', '<?= BASE_URL ?>ventas/pdf/' + idVenta + '?type=A4');
      document.getElementById('btnDescargarTicket').setAttribute('data-url', '<?= BASE_URL ?>ventas/pdf/' + idVenta + '?type=ticket');

      // Mostrar el modal
      const modal = new bootstrap.Modal(document.getElementById('ventaExitosaModal'));
      modal.show();

      document.getElementById('subtotalfinal').textContent = 'S/ 00.00';
      document.getElementById('inpuestofinal').textContent = 'S/ 00.00';
      document.getElementById('totalfinal').textContent = 'S/ 00.00';
      document.getElementById('btnfinalcobro').textContent = 'Cobrar S/ 00.00';
      document.querySelector('tbody.table-border-bottom-0').innerHTML = '';

    } else {
      alert('Error al registrar la venta: ' + res.message);
    }
  })
  .catch(err => {
    console.error('Error en la solicitud:', err);
    alert('Error al enviar los datos');
  });



});

document.querySelectorAll('.abrir-pdf').forEach(btn => {
  btn.addEventListener('click', function () {
    const url = this.dataset.url;
    if (!url) return;
    const width = 800;
    const height = 500;
    const left = (window.screen.width / 2) - (width / 2);
    const top = (window.screen.height / 2) - (height / 2);
    window.open(url, '_blank', `toolbar=no,location=no,menubar=no,scrollbars=yes,resizable=yes,width=${width},height=${height},top=${top},left=${left}`);
  });
});

document.getElementById('btn-buscar-nombre').addEventListener('click', function () {
  const input = document.getElementById('search-product');
  input.focus();
  input.placeholder = 'Buscar producto por nombre o código';

  this.classList.add('active');
  document.getElementById('btn-buscar-codigo').classList.remove('active');
});
document.getElementById('btn-buscar-codigo').addEventListener('click', function () {
  const input = document.getElementById('search-product');
  input.focus();
  input.placeholder = 'Escanea o escribe código de barras';

  this.classList.add('active');
  document.getElementById('btn-buscar-nombre').classList.remove('active');
});


</script>

