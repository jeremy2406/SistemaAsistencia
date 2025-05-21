<?php
require_once 'Config.php';

class Asistencia {
    
    /**
     * Registra una asistencia escaneando un código QR
     * 
     * @param string $codigoQR El código QR del usuario
     * @return array Resultado del registro de asistencia
     */
    public static function registrarAsistencia($codigoQR) {
        $endpoint = '/rest/v1/rpc/marcar_asistencia';
        $data = ['codigo' => $codigoQR];
        
        $result = SupabaseConfig::request($endpoint, 'POST', $data);
        
        return $result;
    }
    
    /**
     * Obtiene las asistencias de un día específico
     * 
     * @param string $fecha Fecha en formato YYYY-MM-DD
     * @param string $estado Filtro por estado (presente, ausente, tardanza, all)
     * @return array Lista de asistencias
     */
    public static function obtenerAsistenciasPorFecha($fecha, $estado = 'all') {
        $endpoint = '/rest/v1/asistencias?select=*,usuarios(nombre,email,rol)&fecha=eq.' . $fecha;
        
        if ($estado !== 'all') {
            $endpoint .= '&estado=eq.' . $estado;
        }
        
        return SupabaseConfig::request($endpoint);
    }
    
    /**
     * Obtiene las asistencias recientes
     * 
     * @param int $limite Número de registros a obtener
     * @return array Lista de asistencias recientes
     */
    public static function obtenerAsistenciasRecientes($limite = 10) {
        $endpoint = '/rest/v1/asistencias?select=*,usuarios(nombre)&order=registrado_en.desc&limit=' . $limite;
        
        return SupabaseConfig::request($endpoint);
    }
    
    /**
     * Obtiene estadísticas de asistencia para el día actual
     * 
     * @return array Estadísticas de asistencia
     */
    public static function obtenerEstadisticasHoy() {
        $fecha = date('Y-m-d');
        
        // Obtener conteo de presentes
        $endpointPresentes = '/rest/v1/asistencias?select=count&fecha=eq.' . $fecha . '&estado=eq.presente';
        $presentes = SupabaseConfig::request($endpointPresentes);
        
        // Obtener conteo de tardanzas
        $endpointTardanzas = '/rest/v1/asistencias?select=count&fecha=eq.' . $fecha . '&estado=eq.tardanza';
        $tardanzas = SupabaseConfig::request($endpointTardanzas);
        
        // Obtener conteo total de usuarios
        $endpointTotal = '/rest/v1/usuarios?select=count';
        $total = SupabaseConfig::request($endpointTotal);
        
        // Calcular ausentes
        $ausentes = $total[0]['count'] - ($presentes[0]['count'] + $tardanzas[0]['count']);
        
        return [
            'presentes' => $presentes[0]['count'],
            'tardanzas' => $tardanzas[0]['count'],
            'ausentes' => $ausentes < 0 ? 0 : $ausentes,
            'total' => $total[0]['count']
        ];
    }
    
    /**
     * Exporta los datos de asistencia según los filtros
     * 
     * @param string $fechaInicio Fecha inicial en formato YYYY-MM-DD
     * @param string $fechaFin Fecha final en formato YYYY-MM-DD
     * @param string $tipo Tipo de datos a exportar (all, present, absent, late)
     * @return array Datos de asistencia para exportación
     */
    public static function exportarDatos($fechaInicio, $fechaFin, $tipo = 'all') {
        $endpoint = '/rest/v1/asistencias?select=*,usuarios(nombre,email,rol)&fecha=gte.' . $fechaInicio . '&fecha=lte.' . $fechaFin;
        
        // Aplicar filtro por tipo
        switch ($tipo) {
            case 'present':
                $endpoint .= '&estado=eq.presente';
                break;
            case 'absent':
                $endpoint .= '&estado=eq.ausente';
                break;
            case 'late':
                $endpoint .= '&estado=eq.tardanza';
                break;
        }
        
        return SupabaseConfig::request($endpoint);
    }
    
    /**
     * Marca ausencias automáticamente para usuarios que no registraron asistencia
     * 
     * @param string $fecha Fecha en formato YYYY-MM-DD
     * @return array Resultado de la operación
     */
    public static function marcarAusencias($fecha = null) {
        if ($fecha === null) {
            $fecha = date('Y-m-d');
        }
        
        // Obtener todos los usuarios activos
        $usuarios = SupabaseConfig::request('/rest/v1/usuarios?select=id&activo=eq.true');
        
        // Obtener usuarios que ya tienen asistencia registrada hoy
        $asistencias = SupabaseConfig::request('/rest/v1/asistencias?select=usuario_id&fecha=eq.' . $fecha);
        
        $usuariosConAsistencia = array_map(function($asistencia) {
            return $asistencia['usuario_id'];
        }, $asistencias);
        
        // Identificar usuarios sin asistencia
        $usuariosSinAsistencia = array_filter($usuarios, function($usuario) use ($usuariosConAsistencia) {
            return !in_array($usuario['id'], $usuariosConAsistencia);
        });
        
        // Registrar ausencias
        $registrados = 0;
        foreach ($usuariosSinAsistencia as $usuario) {
            $datos = [
                'usuario_id' => $usuario['id'],
                'fecha' => $fecha,
                'estado' => 'ausente',
                'hora_llegada' => null
            ];
            
            SupabaseConfig::request('/rest/v1/asistencias', 'POST', $datos);
            $registrados++;
        }
        
        return [
            'success' => true,
            'message' => 'Se marcaron ' . $registrados . ' ausencias',
            'count' => $registrados
        ];
    }
}
?>