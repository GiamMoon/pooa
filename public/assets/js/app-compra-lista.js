'use strict';

document.addEventListener("DOMContentLoaded", function () {
    const modalRegistrarCompraEl = document.getElementById('modalRegistrarCompra');
    const modalRegistrarCompra = modalRegistrarCompraEl ? new bootstrap.Modal(modalRegistrarCompraEl) : null;

    const modalEditarCompraEl = document.getElementById('modalEditarCompra');
    const modalEditarCompra = modalEditarCompraEl ? new bootstrap.Modal(modalEditarCompraEl) : null;
    
    const modalRecepcionarCompraEl = document.getElementById('modalRecepcionarCompra');
    const modalRecepcionarCompra = modalRecepcionarCompraEl ? new bootstrap.Modal(modalRecepcionarCompraEl) : null;

    const dtComprasEl = document.querySelector(".datatables-compra-list");
    const selects = $(".select2");
    const permisos = window.PERMISOS_USUARIO || [];

    $(document).on('input', 'input[type="number"]', function () {
        if (parseFloat($(this).val()) < 0) {
            $(this).val('');
        }
    });

    const tienePermisoVista = (permisoEsperado) => {
        return permisos.some(p =>
            p.ruta === 'compra/compra' && p.permiso === permisoEsperado
        );
    };

    if (selects.length) {
        selects.each(function () {
            const $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                dropdownParent: $this.parent(),
                placeholder: $this.data("placeholder")
            });
        });
    }

    if (dtComprasEl) {
        const filtroCustomFeature = function (settings) {
            const container = document.createElement('div');
            container.classList.add('d-flex', 'align-items-center', 'gap-2', 'flex-wrap');
            container.innerHTML = `
                <div class="row gx-2 mx-3 mb-3 align-items-center">
                    <div class="col-auto">
                        <select id="compra-filtro-campo" class="form-select form-select-sm">
                            <option value="codigo_compra">Código</option>
                            <option value="nombre_proveedor">Proveedor</option>
                        </select>
                    </div>
                    <div class="col">
                        <input type="text" id="compra-filtro-valor" class="form-control form-control-sm" placeholder="Buscar por filtro seleccionado...">
                    </div>
                    <div class="col-auto">
                        <button id="btnResetCompra" class="btn btn-sm btn-secondary"><i class="bx bx-reset"></i> Limpiar</button>
                    </div>
                </div>`;
            return container;
        };

        const botonesTop = [];
        if (tienePermisoVista('visualizar')) {
            botonesTop.push({
                extend: 'collection',
                className: 'btn btn-label-info dropdown-toggle me-2',
                text: `<span class="d-flex align-items-center gap-2"><i class="bx bx-filter-alt icon-xs"></i><span class="d-none d-sm-inline-block">Estado</span></span>`,
                autoClose: true,
                buttons: [
                    { text: 'Pendiente', className: 'dropdown-item', action: function (e, dt, node, config) { dt.column(5).search('^7$', true, false).draw(); } },
                    { text: 'Pendiente a Entrega', className: 'dropdown-item', action: function (e, dt, node, config) { dt.column(5).search('^8$', true, false).draw(); } },
                    { text: 'Recibida Parcialmente', className: 'dropdown-item', action: function (e, dt, node, config) { dt.column(5).search('^5$', true, false).draw(); } },
                    { text: 'Completada', className: 'dropdown-item', action: function (e, dt, node, config) { dt.column(5).search('^4$', true, false).draw(); } },
                    { text: 'Anulada', className: 'dropdown-item', action: function (e, dt, node, config) { dt.column(5).search('^6$', true, false).draw(); } },
                    { text: 'Todas', className: 'dropdown-item', action: function (e, dt, node, config) { dt.column(5).search('').draw(); } }
                ]
            });
        }
        if (tienePermisoVista('exportar')) {
            botonesTop.push({
                extend: "collection",
                className: "btn btn-label-secondary dropdown-toggle me-4",
                text: `<span class="d-flex align-items-center gap-2"><i class="icon-base bx bx-export icon-xs"></i><span class="d-none d-sm-inline-block">Exportar</span></span>`,
                buttons: [
                    { extend: "print", text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-printer me-1"></i>Print</span>`, className: "dropdown-item", exportOptions: { columns: [1, 2, 3, 4, 5] } },
                    { extend: "csv", text: `<span class="d-flex align-items-center"><i class="icon-base bx bx-file me-1"></i>Csv</span>`, className: "dropdown-item", exportOptions: { columns: [1, 2, 3, 4, 5] } },
                    { extend: "excel", text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-export me-1"></i>Excel</span>`, className: "dropdown-item", exportOptions: { columns: [1, 2, 3, 4, 5] } },
                    { extend: "pdf", text: `<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-pdf me-1"></i>Pdf</span>`, className: "dropdown-item", exportOptions: { columns: [1, 2, 3, 4, 5] } },
                    { extend: "copy", text: `<i class="icon-base bx bx-copy me-1"></i>Copy`, className: "dropdown-item", exportOptions: { columns: [1, 2, 3, 4, 5] } }
                ]
            });
        }
        if (tienePermisoVista('registrar')) {
            botonesTop.push({
                text: '<i class="bx bx-plus"></i><span class="d-none d-sm-inline-block">Nueva Compra</span>',
                className: "add-new btn btn-primary",
                attr: { "data-bs-toggle": "modal", "data-bs-target": "#modalRegistrarCompra" }
            });
        }

        const tabla = new DataTable(dtComprasEl, {
            ajax: `${site_url}/compraAjax/listar`,
            columns: [
                { data: "id_compra" }, { data: "codigo_compra" }, { data: "nombre_proveedor" },
                { data: "fecha_registro" }, { data: "total" }, { data: "activo" },
                { data: "id_compra" }
            ],
            columnDefs: [
                { targets: 0, className: "text-center", orderable: false, searchable: false, 
                    checkboxes: {
                        selectRow: true,
                        selectAllRender: '<input type="checkbox" class="form-check-input select-all" id="selectAll">'
                    },
                    render: () => '<input type="checkbox" class="dt-checkboxes form-check-input">' 
                },
                { targets: 1, className: "text-center" }, { targets: 2, className: "text-start" },
                { targets: 3, className: "text-center" }, { targets: 4, className: "text-center" },
                {
                    targets: 5, className: "text-center",
                    render: (data) => {
                        const estados = {
                            4: { title: 'Completada', class: 'bg-label-success' },
                            5: { title: 'Recibida Parcialmente', class: 'bg-label-info' },
                            6: { title: 'Anulada', class: 'bg-label-danger' },
                            7: { title: 'Pendiente', class: 'bg-label-primary' },
                            8: { title: 'Pendiente a Entrega', class: 'bg-label-dark' }
                        };
                        const estado = estados[data] || { title: 'Desconocido', class: 'bg-label-secondary' };
                        return `<span class="badge ${estado.class}">${estado.title}</span>`;
                    }
                },
                {
                    targets: 6, className: "text-center", orderable: false, searchable: false,
                    render: (id_compra, type, row) => {
                        let botones = '';
                        const estadoCompra = parseInt(row.activo);
                        
                        if (tienePermisoVista('visualizar')) {
                            botones += `<button class="btn btn-sm btn-icon btn-info" title="Ver" data-id="${id_compra}"><i class="bx bx-show-alt"></i></button>`;
                        }
                        //let deshabilitarVer = !tienePermisoVista('visualizar');
                        //botones += `<button class="btn btn-sm btn-icon ${deshabilitarVer ? 'btn-secondary' : 'btn-info'}" title="Ver" data-id="${id}" ${deshabilitarVer ? 'disabled' : ''}><i class="bx bx-show-alt"></i></button>`;

                        if (tienePermisoVista('editar') && estadoCompra === 7) {
                            botones += `<button class="btn btn-sm btn-icon btn-primary" title="Editar" data-id="${id_compra}"><i class="bx bx-pencil"></i></button>`;
                        }
                        //let deshabilitarEditar = !tienePermisoVista('editar')  && estadoCompra === 7;
                        //botones += `<button class="btn btn-sm btn-icon ${deshabilitarEditar ? 'btn-secondary' : 'btn-warning'}" title="Editar" data-id="${id}" ${deshabilitarEditar ? 'disabled' : ''}><i class="bx bx-edit-alt"></i></button>`;

                        if (tienePermisoVista('eliminar') && estadoCompra === 7) {
                            botones += `<button class="btn btn-sm btn-icon btn-danger" title="Anular Compra" data-id="${id_compra}"><i class="bx bx-git-commit"></i></button>`;
                        }
                        //let deshabilitarEliminar = !tienePermisoVista('eliminar') && estadoCompra === 7;
                        //botones += `<button class="btn btn-sm btn-icon ${deshabilitarEliminar ? 'btn-secondary' : 'btn-danger'}" title="Eliminar" data-id="${id}" ${deshabilitarEliminar ? 'disabled' : ''}><i class="bx bx-git-commit"></i></button>`;

                        return `<div class="d-flex justify-content-center gap-1">${botones}</div>`;
                    }
                }
            ],
            order: [[0, "desc"]],
            layout: {
                topStart: { rowClass: "row m-3 my-0 justify-content-between", features: [filtroCustomFeature] },
                topEnd: { rowClass: "row m-3 my-0 justify-content-between", features: { pageLength: { menu: [5, 10, 15, 20], text: "_MENU_" }, buttons: botonesTop } },
                bottomStart: { rowClass: "row mx-3 justify-content-between", features: ["info"] },
                bottomEnd: { paging: { firstLast: false } }
            },
            language: {
                emptyTable: "No se encontraron Compras registrados",
                paginate: { next: '<i class="bx bx-chevron-right scaleX-n1-rtl icon-18px"></i>', previous: '<i class="bx bx-chevron-left scaleX-n1-rtl icon-18px"></i>' }
            },
            responsive: {
                details: {
                    display: DataTable.Responsive.display.modal({ header: row => 'Detalles de ' + row.data().codigo_compra }),
                    type: "column",
                    renderer: DataTable.Responsive.renderer.listHiddenNodes()
                }
            }
        });
    }

    let todosLosDestinos = [];
    let todosLosProductos = [];

    function cargarYFiltrarDestinos(idSucursal, tipoDestino, selectDestino, idDestinoSeleccionado = null) {
        selectDestino.empty().append('<option value="" disabled selected>Cargando...</option>').prop('disabled', true);
        fetch(`${site_url}/compraAjax/listarDestinosPorSucursal/${idSucursal}`)
            .then(res => res.json())
            .then(data => {
                todosLosDestinos = data;
                filtrarDestinos(tipoDestino, selectDestino, idDestinoSeleccionado);
            })
            .catch(() => {
                selectDestino.empty().append('<option value="" disabled selected>Error al cargar</option>');
            });
    }

    function filtrarDestinos(tipoSeleccionado, selectDestino, idDestinoSeleccionado = null) {
        selectDestino.empty().append('<option value="" disabled selected>Seleccione un destino</option>').prop('disabled', true);
        if (!tipoSeleccionado) return;
        const destinosFiltrados = todosLosDestinos.filter(d => d.tipo_ubicacion === tipoSeleccionado);
        if (destinosFiltrados.length > 0) {
            destinosFiltrados.forEach(u => {
                const esSeleccionado = u.id_ubicacion == idDestinoSeleccionado;
                selectDestino.append(new Option(u.direccion, u.id_ubicacion, false, esSeleccionado));
            });
            if (idDestinoSeleccionado) selectDestino.val(idDestinoSeleccionado);
            selectDestino.prop('disabled', false);
        } else {
            selectDestino.append(`<option value="" disabled>No hay destinos de este tipo</option>`);
        }
        selectDestino.trigger('change.select2');
    }

    let productosCompra = [];

    $('#modalRegistrarCompra').on('show.bs.modal', function () {
        $('#formRegistrarCompra')[0].reset();
        $('#reg-proveedor, #producto, #id_sucursal, #reg-tipo-ubicacion-destino, #reg-ubicacion-destino').val(null).trigger('change');
        productosCompra = [];
        renderTablaProductos();
        actualizarTotalCompra();
        cargarProveedores();
        cargarProductos();
    });

    $('#id_sucursal, #reg-tipo-ubicacion-destino').on('change', function () {
        const idSucursal = $('#id_sucursal').val();
        const tipoDestino = $('#reg-tipo-ubicacion-destino').val();
        if (idSucursal && tipoDestino) {
            cargarYFiltrarDestinos(idSucursal, tipoDestino, $('#reg-ubicacion-destino'));
        }
    });

    function cargarProveedores() {
        const select = $('#reg-proveedor');
        select.empty().append('<option disabled selected value="">Seleccione un proveedor</option>');
        fetch(`${site_url}/compraAjax/listar_proveedores`).then(res => res.json()).then(data => data.forEach(p => select.append(`<option value="${p.id_proveedor}">${p.nombre_proveedor}</option>`)));
    }

    function cargarProductos() {
        const select = $('#producto');
        select.empty().append('<option disabled selected value="">Seleccione un producto</option>');
        fetch(`${site_url}/compraAjax/listar_productos`)
            .then(res => res.json())
            .then(data => {
                todosLosProductos = data;
                data.forEach(p => select.append(`<option value="${p.id_producto}">${p.nombre_producto}</option>`));
            });
    }

    $('#cantidad, #precio').on('input', function () {
        const cantidad = parseFloat($('#cantidad').val()) || 0;
        const precio = parseFloat($('#precio').val()) || 0;
        $('#subtotal').val((cantidad * precio).toFixed(2));
    });

    $('#btnAgregarProducto').on('click', function () {
        const idProducto = $('#producto').val();
        if (!idProducto) { Swal.fire('Atención', 'Seleccione un producto.', 'warning'); return; }
        const cantidad = parseInt($('#cantidad').val());
        if (!(cantidad > 0)) { Swal.fire('Atención', 'Ingrese una cantidad válida.', 'warning'); return; }
        const precio = parseFloat($('#precio').val());
        if (!(precio > 0)) { Swal.fire('Atención', 'Ingrese un precio válido.', 'warning'); return; }

        const productoInfo = todosLosProductos.find(p => p.id_producto == idProducto);
        const abreviatura = productoInfo ? productoInfo.abreviatura : '';

        productosCompra.push({
            id_producto: idProducto,
            nombre: $('#producto option:selected').text(),
            cantidad,
            precio,
            subtotal: cantidad * precio,
            abreviatura: abreviatura
        });
        renderTablaProductos();
        limpiarInputsProducto();
    });

    function renderTablaProductos() {
        const tbody = $('#tablaProductosCompra tbody');
        tbody.empty();
        productosCompra.forEach((prod, index) => {
            tbody.append(`
                <tr>
                    <td>${prod.nombre}</td>
                    <td>${prod.cantidad}</td>
                    <td>${prod.abreviatura}</td>
                    <td>S/ ${prod.precio.toFixed(2)}</td>
                    <td>S/ ${prod.subtotal.toFixed(2)}</td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarProducto(${index})"><i class="bx bx-trash"></i></button></td>
                </tr>`);
        });
        actualizarTotalCompra();
    }

    window.eliminarProducto = function (index) {
        productosCompra.splice(index, 1);
        renderTablaProductos();
    };

    function limpiarInputsProducto() {
        $('#producto').val(null).trigger('change');
        $('#cantidad, #precio, #subtotal').val('');
    }

    function actualizarTotalCompra() {
        const total = productosCompra.reduce((sum, prod) => sum + prod.subtotal, 0);
        $('#totalCompra').text(`S/ ${total.toFixed(2)}`);
    }

    $('#formRegistrarCompra').on('submit', function (e) {
        e.preventDefault();
        if (!$('#reg-proveedor').val() || productosCompra.length === 0 || !$('#reg-ubicacion-destino').val()) {
            Swal.fire('Error', 'Debe seleccionar un proveedor, un destino y agregar al menos un producto.', 'error');
            return;
        }
        const formData = new FormData(this);
        formData.append('detalle_compra', JSON.stringify(productosCompra));
        fetch(`${site_url}/compraAjax/registrar`, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('¡Éxito!', data.message, 'success');
                    if (modalRegistrarCompra) modalRegistrarCompra.hide();
                    $('.datatables-compra-list').DataTable().ajax.reload();
                } else {
                    Swal.fire('Error', data.message || 'Error al registrar la compra.', 'error');
                }
            }).catch(err => Swal.fire('Error', 'Problema de comunicación: ' + err.message, 'error'));
    });

    let productosCompraEdit = [];
    let sucursalActualParaEdicion = null;

    $('.datatables-compra-list tbody').on('click', 'button[title="Editar"]', function () {
        const idCompra = $(this).data('id');
        fetch(`${site_url}/compraAjax/obtener?id=${idCompra}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    Swal.fire('Error', 'No se pudo cargar la información de la compra.', 'error');
                    return;
                }
                const compra = data.data;
                sucursalActualParaEdicion = compra.id_sucursal;
                $('#edit-id-compra').val(compra.id_compra);
                cargarProveedoresParaEdicion(compra.id_proveedor);
                cargarProductosParaEdicion();
                $('#edit-tipo-ubicacion-destino').val(compra.tipo_ubicacion_destino);
                cargarYFiltrarDestinos(sucursalActualParaEdicion, compra.tipo_ubicacion_destino, $('#edit-ubicacion-destino'), compra.id_ubicacion_destino);

                productosCompraEdit = compra.productos.map(p => ({
                    id_producto: p.id_producto,
                    nombre: p.nombre_producto,
                    cantidad: p.cantidad,
                    precio: p.precio_compra,
                    subtotal: p.subtotal,
                    abreviatura: p.abreviatura
                }));

                renderTablaProductosEdit();
                if (modalEditarCompra) modalEditarCompra.show();
            });
    });

    $('#edit-tipo-ubicacion-destino').on('change', function () {
        const tipoDestino = $(this).val();
        if (sucursalActualParaEdicion && tipoDestino) {
            filtrarDestinos(tipoDestino, $('#edit-ubicacion-destino'));
        }
    });

    function cargarProveedoresParaEdicion(idProveedorSeleccionado) {
        const select = $('#edit-proveedor');
        select.empty();
        fetch(`${site_url}/compraAjax/listar_proveedores`)
            .then(res => res.json())
            .then(data => {
                data.forEach(p => select.append(new Option(p.nombre_proveedor, p.id_proveedor, false, p.id_proveedor == idProveedorSeleccionado)));
                select.trigger('change');
            });
    }

    function cargarProductosParaEdicion() {
        const select = $('#edit-producto');
        select.empty().append('<option disabled selected value="">Cargando...</option>');
        if (todosLosProductos.length === 0) {
            fetch(`${site_url}/compraAjax/listar_productos`)
                .then(res => res.json())
                .then(data => {
                    todosLosProductos = data;
                    select.empty().append('<option disabled selected value="">Seleccione un producto</option>');
                    data.forEach(p => select.append(`<option value="${p.id_producto}">${p.nombre_producto}</option>`));
                })
                .catch(() => select.empty().append('<option disabled selected value="">Error al cargar</option>'));
        } else {
            select.empty().append('<option disabled selected value="">Seleccione un producto</option>');
            todosLosProductos.forEach(p => select.append(`<option value="${p.id_producto}">${p.nombre_producto}</option>`));
        }
    }

    $('#edit-cantidad, #edit-precio').on('input', function () {
        const cantidad = parseFloat($('#edit-cantidad').val()) || 0;
        const precio = parseFloat($('#edit-precio').val()) || 0;
        $('#edit-subtotal').val((cantidad * precio).toFixed(2));
    });

    $('#btnAgregarProductoEdit').on('click', function () {
        const idProducto = $('#edit-producto').val();
        if (!idProducto) { Swal.fire('Atención', 'Seleccione un producto.', 'warning'); return; }
        const cantidad = parseInt($('#edit-cantidad').val());
        if (!(cantidad > 0)) { Swal.fire('Atención', 'Ingrese una cantidad válida.', 'warning'); return; }
        const precio = parseFloat($('#edit-precio').val());
        if (!(precio > 0)) { Swal.fire('Atención', 'Ingrese un precio válido.', 'warning'); return; }

        const productoInfo = todosLosProductos.find(p => p.id_producto == idProducto);
        const abreviatura = productoInfo ? productoInfo.abreviatura : '';

        productosCompraEdit.push({
            id_producto: idProducto,
            nombre: $('#edit-producto option:selected').text(),
            cantidad,
            precio,
            subtotal: cantidad * precio,
            abreviatura: abreviatura
        });

        renderTablaProductosEdit();
        limpiarInputsProductoEdit();
    });

    function limpiarInputsProductoEdit() {
        $('#edit-producto').val(null).trigger('change');
        $('#edit-cantidad, #edit-precio, #edit-subtotal').val('');
    }

    function renderTablaProductosEdit() {
        const tbody = $('#tablaProductosCompraEdit tbody');
        tbody.empty();
        productosCompraEdit.forEach((prod, index) => {
            tbody.append(`<tr>
                <td>${prod.nombre}</td>
                <td>${prod.cantidad}</td>
                <td>${prod.abreviatura}</td>
                <td>S/ ${prod.precio.toFixed(2)}</td>
                <td>S/ ${prod.subtotal.toFixed(2)}</td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarProductoEdit(${index})"><i class="bx bx-trash"></i></button></td>
            </tr>`);
        });
        actualizarTotalCompraEdit();
    }

    window.eliminarProductoEdit = function (index) {
        productosCompraEdit.splice(index, 1);
        renderTablaProductosEdit();
    };

    function actualizarTotalCompraEdit() {
        const total = productosCompraEdit.reduce((sum, prod) => sum + prod.subtotal, 0);
        $('#totalCompraEdit').text(`S/ ${total.toFixed(2)}`);
    }

    $('#formEditarCompra').on('submit', function (e) {
        e.preventDefault();
        if (!$('#edit-proveedor').val() || productosCompraEdit.length === 0 || !$('#edit-ubicacion-destino').val()) {
            Swal.fire('Error', 'Debe seleccionar un proveedor, un destino y agregar al menos un producto.', 'error');
            return;
        }
        const formData = new FormData(this);
        const detalleParaEnviar = productosCompraEdit.map(p => ({ id_producto: p.id_producto, cantidad: p.cantidad, precio: p.precio }));
        formData.append('detalle_compra', JSON.stringify(detalleParaEnviar));
        fetch(`${site_url}/compra/actualizar`, {
            method: 'POST', body: formData
        }).then(res => res.json()).then(data => {
            if (data.success) {
                Swal.fire('¡Éxito!', data.message, 'success');
                if (modalEditarCompra) modalEditarCompra.hide();
                $('.datatables-compra-list').DataTable().ajax.reload();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    });

    $('.datatables-compra-list tbody').on('click', 'button[title="Ver"]', function (e) {
        e.stopPropagation();

        const idCompra = $(this).data('id');
        const modalDetalleEl = document.getElementById('modalDetalleCompra');
        if (!modalDetalleEl) return;

        const existingModal = bootstrap.Modal.getInstance(modalDetalleEl);
        if (existingModal) {
            existingModal.dispose();
        }
        
        const modalDetalleInstance = new bootstrap.Modal(modalDetalleEl);

        fetch(`${site_url}/compraAjax/obtener?id=${idCompra}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    Swal.fire('Error', data.error || 'No se pudo obtener el detalle.', 'error');
                    return;
                }
                const compra = data.data;
                $('#detalle-codigo-compra').val(compra.codigo_compra);
                $('#detalle-proveedor').val(compra.proveedor);
                $('#detalle-fecha-compra').val(compra.fecha_compra);
                const tbody = $('#tablaDetalleProductos tbody');
                tbody.empty();
                let total = 0;
                if (compra.productos && Array.isArray(compra.productos)) {
                    compra.productos.forEach(p => {
                        total += parseFloat(p.subtotal);
                        tbody.append(`<tr>
                            <td>${p.nombre_producto}</td>
                            <td>${p.cantidad}</td>
                            <td>${p.abreviatura}</td>
                            <td>S/ ${parseFloat(p.precio_compra).toFixed(2)}</td>
                            <td>S/ ${parseFloat(p.subtotal).toFixed(2)}</td>
                        </tr>`);
                    });
                }
                $('#detalle-total-compra').text(`S/ ${total.toFixed(2)}`);
                const btnGenerarOC = $('#btn-generar-oc');
                btnGenerarOC.data('id', idCompra);
                const estado = parseInt(compra.activo);
                if (estado === 7) {
                    btnGenerarOC.text('Generar Orden de Compra').show();
                } else if (estado !== 6) {
                    btnGenerarOC.text('Descargar Orden de Compra').show();
                } else {
                    btnGenerarOC.hide();
                }
                
                modalDetalleInstance.show();
            })
            .catch(error => {
                console.error("Error al obtener detalle de compra:", error);
                Swal.fire('Error', 'Problema de comunicación al obtener detalles.', 'error');
            });
    });

    $('#btn-generar-oc').on('click', function () {
        const idCompra = $(this).data('id');
        if (!idCompra) return;
        const urlDescarga = `${site_url}/compra/generarOrdenDeCompra/${idCompra}`;
        window.open(urlDescarga, '_blank');
        if ($(this).text().includes('Generar')) {
            const modalDetalleEl = document.getElementById('modalDetalleCompra');
            const modalDetalleInstance = bootstrap.Modal.getInstance(modalDetalleEl);
            if (modalDetalleInstance) {
                 modalDetalleInstance.hide();
            }

            Swal.fire({
                title: '¡Éxito!',
                text: 'La Orden de Compra se ha generado y el estado ha sido actualizado.',
                icon: 'success',
                timer: 2500,
                showConfirmButton: false
            });
            setTimeout(() => $('.datatables-compra-list').DataTable().ajax.reload(), 500);
        }
    });

    $('.datatables-compra-list tbody').on('click', 'button[title="Recepcionar"]', function () {
        const idCompra = $(this).data('id');
        fetch(`${site_url}/compraAjax/obtener?id=${idCompra}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) { return; }
                const compra = data.data;
                $('#formRecepcionarCompra').data('id-compra', idCompra);
                $('#codigo-compra-recep').val(compra.codigo_compra);
                $('#proveedor-recep').val(compra.proveedor);

                const destinoTexto = `${compra.tipo_ubicacion_destino || 'N/A'}: ${compra.direccion_destino || 'No especificada'}`;
                $('#destino-de-la-compra').val(destinoTexto);

                const tbody = $('#tablaLotesRecepcion tbody');
                tbody.empty();
                if (compra.productos && Array.isArray(compra.productos)) {
                    compra.productos.forEach(p => {
                        tbody.append(`
                            <tr data-id-producto="${p.id_producto}" data-cantidad-esperada="${p.cantidad}">
                                <td>${p.nombre_producto}</td>
                                <td><span class="badge bg-secondary">${p.cantidad} ${p.abreviatura}</span></td>
                                <td><input type="number" class="form-control cantidad-recibida" value="${p.cantidad}" min="0" max="${p.cantidad}"></td>
                                <td><input type="text" class="form-control observacion-producto" placeholder="Opcional..."></td>
                            </tr>`);
                    });
                }
                $('#campo-evidencia').hide();
                $('#evidencia-recepcion').val('').prop('required', false);
                if (modalRecepcionarCompra) modalRecepcionarCompra.show();
            });
    });

    $(document).on('input', '.cantidad-recibida', function () {
        let esParcial = false;
        $('#tablaLotesRecepcion tbody tr').each(function () {
            const fila = $(this);
            const cantidadRecibida = parseInt(fila.find('.cantidad-recibida').val()) || 0;
            const cantidadEsperada = parseInt(fila.data('cantidad-esperada')) || 0;
            if (cantidadRecibida < cantidadEsperada) {
                esParcial = true;
            }
        });

        if (esParcial) {
            $('#campo-evidencia').slideDown();
            $('#evidencia-recepcion').prop('required', true);
        } else {
            $('#campo-evidencia').slideUp();
            $('#evidencia-recepcion').prop('required', false);
        }
    });

    $('#formRecepcionarCompra').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('id_compra', $(this).data('id-compra'));

        let productosRecibidos = [];
        $('#tablaLotesRecepcion tbody tr').each(function () {
            const fila = $(this);
            productosRecibidos.push({
                id_producto: fila.data('id-producto'),
                cantidad_recibida: fila.find('.cantidad-recibida').val(),
                cantidad_esperada: fila.data('cantidad-esperada')
            });
        });
        formData.append('productos_recibidos', JSON.stringify(productosRecibidos));

        fetch(`${site_url}/compraAjax/procesarRecepcion`, {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    Swal.fire('¡Éxito!', response.message, 'success');
                    if (modalRecepcionarCompra) modalRecepcionarCompra.hide();
                    $('.datatables-compra-list').DataTable().ajax.reload();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            })
            .catch(() => Swal.fire('Error', 'Ocurrió un problema de comunicación con el servidor.', 'error'));
    });
});