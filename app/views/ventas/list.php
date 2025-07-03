<script>
  window.PERMISOS_USUARIO = <?= json_encode($_SESSION['permisos']) ?>;
</script>

          <!-- Content -->
           <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Product List Widget -->
              <div class="card mb-6">
                <div class="card-widget-separator-wrapper">
                  <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                      <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                          <div>
                            <p class="mb-1">Ventas totales</p>                            
                            <p class="mb-0">
                                <span class="me-2">(<?= $totalVentas ?>)</span>
                                <span class="badge bg-label-success"><?= $crecimientoHoy ?>%</span>
                            </p>
                          </div>
                          <span class="avatar me-sm-6">
                          <span class="avatar-initial rounded w-px-44 h-px-44">
                          <i class="icon-base bx bx-store-alt icon-lg text-heading"></i>
                          </span>
                          </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-6" />
                      </div>
                      <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                          <div>
                            <p class="mb-1">Ventas del día</p>                            
                            <p class="mb-0">
                                  <span class="me-2">(<?= $ventasHoy ?>)</span>
  <span class="badge bg-label-success"><?= $crecimientoHoy ?>%</span>
                            </p>
                          </div>
                          <span class="avatar p-2 me-lg-6">
                          <span class="avatar-initial rounded w-px-44 h-px-44">
                          <i class="icon-base bx bx-archive icon-lg text-heading"></i>
                          </span>
                          </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none" />
                      </div>
                      <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                          <div>
                            <p class="mb-1">Ventas activas</p>                            
<p class="mb-0">(<?= $ventasActivas ?>)</p>
                          </div>
                          <span class="avatar p-2 me-sm-6">
                          <span class="avatar-initial rounded w-px-44 h-px-44">
                          <i class="icon-base bx bx-check-circle icon-lg text-heading"></i>
                          </span>
                          </span>
                        </div>
                      </div>
                      <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start">
                          <div>
                            <p class="mb-1">Total recaudado</p>                            
                            <p class="mb-0"><span class="me-2">S/ <?= number_format($totalRecaudado, 2) ?></span></p>
                          </div>
                          <span class="avatar p-2">
                          <span class="avatar-initial rounded w-px-44 h-px-44">
                          <i class="icon-base bx bx-dollar-circle icon-lg text-heading"></i>
                          </span>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="app-ecommerce-category">
                
              <!-- Usuarios List Table -->
                <div class="card">
                  <div class="card-datatable">
                     
                    <table class="datatables-category-list table">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Comprobante</th>
            <th>Cliente</th>
             <th>Tipo</th>
            <th>Modo de Pago</th>
            <th>Total</th>
            <th>Por Cobrar</th>
            <th>SUNAT</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php //print_r($ventas); 
        foreach ($ventas as $venta): ?>
            <tr>
                <td><?= $venta['fecha_emision'] ?></td>
                <td><?= $venta['comprobante'] ?></td>
                <td><?= $venta['cliente'] ?> <br> <?= $venta['doc_cliente'] ?></td>
                 <td><?= $venta['tipo_comprobante'] ?></td>
                <td><?= $venta['modo_pago'] ?></td>
                <td>S/ <?= number_format($venta['monto_total'], 2) ?></td>
                <td>S/ 0</td>
                <td>Aceptado<?php //$venta['estado_sunat'] ?></td>
                <td>
                    <button class="btn btn-sm btn-info" data-id="<?= $venta['id_venta'] ?>" onclick="verDetalle(this)" data-bs-toggle="modal" data-bs-target="#detalle">
                        Ver Detalle
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


                  </div>
                </div>
                
                <!-- Modal -->
                <div class="modal fade" id="detalle" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                      <div class="modal-header" style="display: flex;justify-content: space-between;">
                        <h5 class="modal-title" id="tituloVenta"></h5>
                        <div class="mb-3 d-flex flex-wrap gap-2">
                          <button id="btnDescargarA4" class="btn btn-label-primary abrir-pdf" target="_blank" type="button">
                            <i class="bx bx-download"></i> A4
                          </button>
                          <button id="btnDescargarTicket" class="btn btn-label-secondary abrir-pdf" target="_blank" type="button" >
                            <i class="bx bx-receipt"></i> Ticket
                          </button>
                          <a id="btnWhatsapp" class="btn btn-label-success" target="_blank" type="button">
                            <i class="bx bxl-whatsapp"></i> WhatsApp
                          </a>
                          <a id="btnCorreo" class="btn btn-label-danger" target="_blank" type="button">
                            <i class="bx bx-envelope"></i> Gmail
                          </a>
                        </div> 
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row g-6">
                          <div class="col-lg-8 col-12 ">
                            <iframe id="pdf" src="" width="100%" height="500px" frameborder="0"></iframe>
                          </div>
                          <div class="col-md-6 col-md-4 col-lg-4">
                            
                          <div class="card mb-6">
        <div class="card-body pt-5">
           
          <div class="d-flex justify-content-around flex-wrap mb-6 gap-0 gap-md-3">
            <div class="d-flex align-items-center gap-4 me-5">
              <div class="avatar">
                <div class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-cart icon-lg"></i></div>
              </div>
              <div>
                <h5 class="mb-0" id="submonto">S/ 00.00</h5>
                <span>SubTotal</span>
              </div>
            </div>
            <div class="d-flex align-items-center gap-4">
              <div class="avatar">
                <div class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bx-dollar icon-lg"></i></div>
              </div>
              <div>
                <h5 class="mb-0" id="totalVenta">S/ 00.00</h5>
                <span>Total</span>
              </div>
            </div>
          </div>

          <div class="info-container">
             <ul class="list-unstyled mb-6">
              <li class="mb-2">
                <span class="h6 me-1">Cliente:</span>
                <span id="cliente"></span>
              </li>
              <li class="mb-2">
                <span class="h6 me-1" id="tipo_comprobante"></span>
                <span id="serie"></span>
              </li>
              <li class="mb-2">
                <span class="h6 me-1">Status:</span>
                <span class="badge bg-label-success">Active</span>
              </li>
              <li class="mb-2">
                <span class="h6 me-1">Contact:</span>
                <span>(123) 456-7890</span>
              </li>

              <li class="mb-2">
                <span class="h6 me-1">Country:</span>
                <span>USA</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
                          </div>
                        </div>
                      </div><!--
                      <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                      </div>-->
                    </div>
                  </div>
                </div>


              </div>
            </div>

<script>
function verDetalle(btn) {
  const id = btn.getAttribute('data-id');
  fetch(`<?= BASE_URL ?>/ventas/resumen/${id}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        mostrarDetalleVenta(data);
      } else {
        alert('No se pudo cargar el resumen');
      }
    });
}

function mostrarDetalleVenta(data) { console.log(data);
  const r = data.resumen;
  //const pagos = data.pagos;

  document.getElementById('pdf').src = '<?= BASE_URL ?>ventas/pdf/' + data.pdf;

  // Enlaces para descarga y envío 
  document.getElementById('btnDescargarA4').setAttribute('data-url', '<?= BASE_URL ?>ventas/pdf/' + data.pdf + '?type=A4');
  document.getElementById('btnDescargarTicket').setAttribute('data-url', '<?= BASE_URL ?>ventas/pdf/' + data.pdf + '?type=ticket');


  const whatsappMsg = encodeURIComponent(`Hola, aquí está tu comprobante de venta: <?= BASE_URL ?>ventas/pdf/${data.pdf}`);
  document.getElementById('btnWhatsapp').href = 'https://wa.me/?text=' + whatsappMsg;

  const mailSubject = encodeURIComponent('Comprobante de Venta');
  const mailBody = encodeURIComponent('Estimado cliente,\n\nAdjunto encontrará el comprobante de su compra:\n<?= BASE_URL ?>ventas/pdf/' + data.pdf);
  document.getElementById('btnCorreo').href = 'mailto:?subject=' + mailSubject + '&body=' + mailBody;


  document.getElementById('tituloVenta').textContent = `Venta ${r.numero}`;
  document.getElementById('totalVenta').textContent = `S/ ${parseFloat(r.monto_total).toFixed(2)}`;
  document.getElementById('submonto').textContent = `S/ ${parseFloat(r.submonto).toFixed(2)}`;

  //document.getElementById('cobrado').textContent = `S/ ${parseFloat(r.monto_cobrado).toFixed(2)}`;
  //document.getElementById('porCobrar').textContent = `S/ ${parseFloat(r.por_cobrar).toFixed(2)}`;

  document.getElementById('cliente').textContent = r.cliente + ' - '+r.ruc_dni;
  document.getElementById('tipo_comprobante').textContent = r.tipo_comprobante+':';
  document.getElementById('serie').textContent = r.serie;
  //document.getElementById('fechaVenta').textContent = r.fecha;

}

document.querySelectorAll('.abrir-pdf').forEach(btn => {
  btn.addEventListener('click', function () {
    const url = this.dataset.url;
    const width = 800;
    const height = 500;
    const left = (window.screen.width / 2) - (width / 2);
    const top = (window.screen.height / 2) - (height / 2);
    window.open(url,'_blank',`toolbar=no,location=no,menubar=no,scrollbars=yes,resizable=yes,width=${width},height=${height},top=${top},left=${left}`);
  });
});



</script>


