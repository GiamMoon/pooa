'use strict';

document.addEventListener('DOMContentLoaded', function() {

    const dateRangeInput = document.getElementById('dashboard-date-range');
    if (!dateRangeInput) {
        console.error('Elemento #dashboard-date-range no encontrado.');
        return;
    }

    if (typeof site_url === 'undefined') {
        console.error('CRÍTICO: La variable global "site_url" no está definida. Por favor, asegúrate de que se defina en tu layout principal (ej. head.php).');
        return;
    }

    let dateRangePicker;
    const kpiTotalVentasEl = document.getElementById('kpi-total-ventas');
    const kpiTotalComprasEl = document.getElementById('kpi-total-compras');
    const kpiTotalClientesEl = document.getElementById('kpi-total-clientes');
    const kpiProductosCriticosEl = document.getElementById('kpi-productos-criticos');
    const filterButtons = document.querySelectorAll('.btn-group[role="group"] button');

    const cardStockCritico = document.getElementById('card-stock-critico');
    const criticalStockTableBody = document.getElementById('criticalStockTableBody');
    const criticalStockModal = new bootstrap.Modal(document.getElementById('criticalStockModal'));

    let salesByPeriodChart, topProductsChart, salesByBranchChart, purchasesBySupplierChart;

    const chartColors = {
        bar: '#28a745',
        line: '#007bff',
        donut: ['#696cff', '#71dd37', '#ffab00', '#ff3e1d', '#03c3ec']
    };

    function initializeChart(elementId, options) {
        const element = document.getElementById(elementId);
        if (element && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(element, options);
            chart.render();
            return chart;
        }
        return null;
    }

    function showNoDataMessage(chartInstance, message = 'No hay datos para el período') {
        if (chartInstance) {
            chartInstance.updateOptions({
                series: [],
                xaxis: { categories: [] },
                labels: [],
                noData: { text: message, align: 'center', verticalAlign: 'middle', style: { color: '#6c757d', fontSize: '14px' } }
            });
        }
    }

    function formatCurrency(value) {
        const numberValue = parseFloat(value);
        if (isNaN(numberValue)) return 'S/ 0.00';
        return `S/ ${numberValue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`;
    }

    function updateKpiCards(kpis) {
        if (!kpis) return;
        kpiTotalVentasEl.textContent = formatCurrency(kpis.total_ventas);
        kpiTotalComprasEl.textContent = formatCurrency(kpis.total_compras);
        kpiTotalClientesEl.textContent = kpis.total_clientes || 0;
        kpiProductosCriticosEl.textContent = kpis.total_productos_criticos || 0;
    }

    function updateActiveButton(activeButton) {
        filterButtons.forEach(button => {
            button.classList.remove('btn-primary');
            button.classList.add('btn-outline-primary');
        });
        if (activeButton) {
            activeButton.classList.remove('btn-outline-primary');
            activeButton.classList.add('btn-primary');
        }
    }

    function fetchDashboardData(startDate, endDate) {
        console.log(`Iniciando fetch para: ${startDate} a ${endDate}`);

        showNoDataMessage(salesByPeriodChart, 'Cargando...');
        showNoDataMessage(topProductsChart, 'Cargando...');
        showNoDataMessage(salesByBranchChart, 'Cargando...');
        showNoDataMessage(purchasesBySupplierChart, 'Cargando...');

        const formData = new FormData();
        formData.append('fecha_inicio', startDate);
        formData.append('fecha_fin', endDate);
        const url = `${site_url}DashboardAjax/obtenerDatosDashboard`;

        fetch(url, { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                console.log("Datos recibidos:", data);
                updateKpiCards(data.kpis);

                if (salesByPeriodChart) {
                    if (data.ventasPeriodo && data.ventasPeriodo.length > 0) {
                        salesByPeriodChart.updateOptions({ xaxis: { categories: data.ventasPeriodo.map(item => item.fecha) }, series: [{ name: 'Total Ventas', data: data.ventasPeriodo.map(item => parseFloat(item.total_dia)) }], noData: { text: '' } });
                    } else { showNoDataMessage(salesByPeriodChart, 'No hay datos de ventas'); }
                }

                if (topProductsChart) {
                    if (data.topProductos && data.topProductos.length > 0) {
                        topProductsChart.updateOptions({ labels: data.topProductos.map(item => item.producto), noData: { text: '' } });
                        topProductsChart.updateSeries(data.topProductos.map(item => parseInt(item.cantidad_vendida, 10)));
                    } else {
                        topProductsChart.updateSeries([]);
                        showNoDataMessage(topProductsChart, 'No hay productos vendidos');
                    }
                }

                if (salesByBranchChart) {
                    if (data.ventasSucursal && data.ventasSucursal.length > 0) {
                        salesByBranchChart.updateOptions({ xaxis: { categories: data.ventasSucursal.map(item => item.sucursal) }, series: [{ name: 'Total Vendido', data: data.ventasSucursal.map(item => parseFloat(item.total_vendido)) }], noData: { text: '' } });
                    } else { showNoDataMessage(salesByBranchChart, 'No hay ventas por sucursal'); }
                }

                if (purchasesBySupplierChart) {
                     if (data.comprasProveedor && data.comprasProveedor.length > 0) {
                        purchasesBySupplierChart.updateOptions({ xaxis: { categories: data.comprasProveedor.map(item => item.proveedor) }, series: [{ name: 'Total Comprado', data: data.comprasProveedor.map(item => parseFloat(item.total_comprado)) }], noData: { text: '' } });
                    } else { showNoDataMessage(purchasesBySupplierChart, 'No hay compras por proveedor'); }
                }

                criticalStockTableBody.innerHTML = '';
                if (data.listaProductosCriticos && data.listaProductosCriticos.length > 0) {
                    data.listaProductosCriticos.forEach(item => {
                        const row = `<tr>
                            <td>${item.producto}</td>
                            <td><span class="badge bg-danger">${item.stock}</span></td>
                            <td>${item.stock_minimo}</td>
                        </tr>`;
                        criticalStockTableBody.innerHTML += row;
                    });
                    cardStockCritico.onclick = () => criticalStockModal.show();
                    cardStockCritico.style.cursor = 'pointer';
                } else {
                    criticalStockTableBody.innerHTML = '<tr><td colspan="3" class="text-center">¡Felicidades! No hay productos en estado crítico.</td></tr>';
                    cardStockCritico.onclick = null;
                    cardStockCritico.style.cursor = 'default';
                }

                setTimeout(() => window.dispatchEvent(new Event('resize')), 100);

            })
            .catch(error => console.error('Error en fetchDashboardData:', error));
    }

    salesByPeriodChart = initializeChart('salesByPeriodChart', { chart: { type: 'line', height: 350, toolbar: { show: true } }, series: [], xaxis: { type: 'datetime' }, yaxis: { title: { text: 'Monto (S/)' } }, stroke: { curve: 'smooth' }, colors: [chartColors.line], noData: { text: 'Seleccione un período' } });
    topProductsChart = initializeChart('topProductsChart', { chart: { type: 'donut', height: 350 }, series: [], labels: [], colors: chartColors.donut, legend: { position: 'bottom' }, noData: { text: 'Seleccione un período' } });
    salesByBranchChart = initializeChart('salesByBranchChart', { chart: { type: 'bar', height: 300 }, series: [], xaxis: { categories: [] }, plotOptions: { bar: { horizontal: true } }, colors: [chartColors.bar], noData: { text: 'Seleccione un período' } });
    purchasesBySupplierChart = initializeChart('purchasesBySupplierChart', { chart: { type: 'bar', height: 300 }, series: [], xaxis: { categories: [] }, plotOptions: { bar: { borderRadius: 4, horizontal: true, } }, colors: [chartColors.donut[1]], noData: { text: 'Seleccione un período' } });

    dateRangePicker = flatpickr(dateRangeInput, {
        mode: 'range',
        dateFormat: 'Y-m-d',
        onClose: (selectedDates) => {
            if (selectedDates.length === 2) {
                updateActiveButton(null);
                fetchDashboardData(selectedDates[0].toISOString().slice(0, 10), selectedDates[1].toISOString().slice(0, 10));
            }
        }
    });

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            updateActiveButton(this);
            const range = this.getAttribute('data-range');
            const now = new Date();
            let startDate, endDate;
            switch (range) {
                case 'today': startDate = endDate = new Date(); break;
                case 'this_month': startDate = new Date(now.getFullYear(), now.getMonth(), 1); endDate = new Date(now.getFullYear(), now.getMonth() + 1, 0); break;
                case 'last_month': startDate = new Date(now.getFullYear(), now.getMonth() - 1, 1); endDate = new Date(now.getFullYear(), now.getMonth(), 0); break;
                case 'this_year': startDate = new Date(now.getFullYear(), 0, 1); endDate = new Date(now.getFullYear(), 11, 31); break;
            }
            if (startDate && endDate) {
                dateRangePicker.setDate([startDate, endDate], false);
                fetchDashboardData(startDate.toISOString().slice(0, 10), endDate.toISOString().slice(0, 10));
            }
        });
    });

    document.querySelector('button[data-range="this_month"]').click();
});
