<?php
require_once 'Config.php';

class Configuracion {
    
    /**
     * Obtiene la configuración actual del sistema
     * 
     * @return array Configuración del sistema
     */
    public static function obtenerConfiguracion() {
        $endpoint = '/rest/v1/configuracion?select=*&limit=1';
        $resultado = SupabaseConfig::request($endpoint);
        
        return !empty($resultado) ? $resultado[0] : null;
    }
    
    /**
     * Actualiza la configuración del sistema
     * 
     * @param array $datos Datos de configuración a actualizar
     * @return array Resultado de la operación
     */
    public static function actualizarConfiguracion($datos) {
        // Validar datos
        if (empty($datos)) {
            return [
                'success' => false,
                'message' => 'No hay datos para actualizar'
            ];
        }
        
        // Obtener el ID de la configuración
        $config = self::obtenerConfiguracion();
        if (!$config) {
            return [
                'success' => false,
                'message' => 'No se encontró la configuración del sistema'
            ];
        }
        
        // Añadir timestamp de actualización
        $datos['actualizado_en'] = date('Y-m-d H:i:s');
        
        // Actualizar configuración
        $endpoint = '/rest/v1/configuracion?id=eq.' . $config['id'];
        $resultado = SupabaseConfig::request($endpoint, 'PATCH', $datos);
        
        if (isset($resultado['error'])) {
            return [
                'success' => false,
                'message' => 'Error al actualizar la configuración: ' . $resultado['error']
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Configuración actualizada exitosamente'
        ];
    }
    
    /**
     * Obtiene la hora límite para marcar tardanza
     * 
     * @return string Hora límite en formato HH:MM:SS
     */
    public static function obtenerHoraLimite() {
        $config = self::obtenerConfiguracion();
        return $config ? $config['hora_limite'] : '09:00:00';
    }
    
    /**
     * Obtiene la zona horaria configurada
     * 
     * @return string Zona horaria
     */
    public static function obtenerZonaHoraria() {
        $config = self::obtenerConfiguracion();
        return $config ? $config['zona_horaria'] : 'America/Mexico_City';
    }
    
    /**
     * Obtiene el nombre de la organización
     * 
     * @return string Nombre de la organización
     */
    public static function obtenerNombreOrganizacion() {
        $config = self::obtenerConfiguracion();
        return $config ? $config['nombre_organizacion'] : 'Mi Organización';
    }
    
    /**
     * Verifica si es tardanza según la hora actual y la configuración
     * 
     * @param string $hora Hora en formato HH:MM:SS (opcional, usa la hora actual por defecto)
     * @return bool True si es tardanza, false si no
     */
    public static function esTardanza($hora = null) {
        if ($hora === null) {
            $hora = date('H:i:s');
        }
        
        $horaLimite = self::obtenerHoraLimite();
        
        return $hora > $horaLimite;
    }
}
?>