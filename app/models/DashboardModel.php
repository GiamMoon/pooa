<?php

class DashboardModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene los datos para los KPIs principales del dashboard.
     *
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @return array
     */
    public function getKpis(string $fecha_inicio, string $fecha_fin): array
    {
        $kpis = [];
        
        // Total de Ventas
        $sqlVentas = "CALL SP_DASHBOARD_TOTAL_VENTAS(?, ?)";
        $stmtVentas = $this->db->prepare($sqlVentas);
        $stmtVentas->execute([$fecha_inicio, $fecha_fin]);
        $kpis['total_ventas'] = $stmtVentas->fetch(PDO::FETCH_ASSOC)['total_ventas'] ?? 0;
        $stmtVentas->closeCursor();

        // Total de Compras
        $sqlCompras = "CALL SP_DASHBOARD_TOTAL_COMPRAS(?, ?)";
        $stmtCompras = $this->db->prepare($sqlCompras);
        $stmtCompras->execute([$fecha_inicio, $fecha_fin]);
        $kpis['total_compras'] = $stmtCompras->fetch(PDO::FETCH_ASSOC)['total_compras'] ?? 0;
        $stmtCompras->closeCursor();

        // Total de Clientes
        $sqlClientes = "CALL SP_DASHBOARD_TOTAL_CLIENTES()";
        $stmtClientes = $this->db->prepare($sqlClientes);
        $stmtClientes->execute();
        $kpis['total_clientes'] = $stmtClientes->fetch(PDO::FETCH_ASSOC)['total_clientes'] ?? 0;
        $stmtClientes->closeCursor();
        
        // Productos Críticos
        $sqlCriticos = "CALL SP_DASHBOARD_PRODUCTOS_CRITICOS()";
        $stmtCriticos = $this->db->prepare($sqlCriticos);
        $stmtCriticos->execute();
        $kpis['total_productos_criticos'] = $stmtCriticos->fetch(PDO::FETCH_ASSOC)['total_productos_criticos'] ?? 0;
        $stmtCriticos->closeCursor();

        return $kpis;
    }

    /**
     * Obtiene los datos de ventas por período para el gráfico.
     *
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @return array
     */
    public function getVentasPorPeriodo(string $fecha_inicio, string $fecha_fin): array
    {
        $sql = "CALL SP_DASHBOARD_VENTAS_POR_PERIODO(?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fecha_inicio, $fecha_fin]);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultado;
    }

    /**
     * Obtiene el top 5 de productos más vendidos.
     *
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @return array
     */
    public function getTopProductosVendidos(string $fecha_inicio, string $fecha_fin): array
    {
        $sql = "CALL SP_DASHBOARD_TOP_PRODUCTOS_VENDIDOS(?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fecha_inicio, $fecha_fin]);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultado;
    }

    /**
     * Obtiene las ventas agrupadas por sucursal.
     *
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @return array
     */
    public function getVentasPorSucursal(string $fecha_inicio, string $fecha_fin): array
    {
        $sql = "CALL SP_DASHBOARD_VENTAS_POR_SUCURSAL(?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fecha_inicio, $fecha_fin]);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultado;
    }

    /**
     * Obtiene las compras agrupadas por proveedor.
     *
     * @param string $fecha_inicio
     * @param string $fecha_fin
     * @return array
     */
    public function getComprasPorProveedor(string $fecha_inicio, string $fecha_fin): array
    {
        $sql = "CALL sp_get_top_proveedores_por_fecha(?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fecha_inicio, $fecha_fin]);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultado;
    }

    /**
     * NUEVA FUNCIÓN
     * Obtiene la lista de productos que están en estado crítico.
     *
     * @return array
     */
    public function getListaProductosCriticos(): array
    {
        $sql = "CALL sp_get_lista_productos_criticos()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultado;
    }
}
