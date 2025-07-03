<?php
class ReportesModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $fecha_inicio La fecha de inicio del reporte.
     * @param string $fecha_fin La fecha de fin del reporte.
     * @return array Lista de movimientos para el reporte.
     */
    public function generarReporteMovimientos($fecha_inicio, $fecha_fin)
    {
        try {
            $stmt = $this->db->prepare("CALL SP_REPORTE_MOVIMIENTOS(?, ?)");
            $stmt->execute([$fecha_inicio, $fecha_fin]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('ReportesModel::generarReporteMovimientos -> ' . $e->getMessage());
            return [];
        }
    }
}