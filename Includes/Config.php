<?php
/**
 * Configuración de Supabase
 * 
 * Este archivo contiene las credenciales y funciones para conectarse a Supabase
 */

// Credenciales de Supabase (reemplazar con tus propias credenciales)
define('SUPABASE_URL', 'https://llzrabdkomezsjyihkhz.supabase.co');
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImxsenJhYmRrb21lenNqeWloa2h6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDc4NDM0NzcsImV4cCI6MjA2MzQxOTQ3N30.0J9YBaRBO63YMHKKCP_6WHwjPP-_cmL5tA1TBnyk4OM');

class SupabaseConfig {
    
    /**
     * Función para realizar solicitudes a la API de Supabase
     */
    public static function request($endpoint, $method = 'GET', $data = null) {
        $url = SUPABASE_URL . $endpoint;
        
        $headers = [
            'Content-Type: application/json',
            'apikey: ' . SUPABASE_KEY,
            'Authorization: Bearer ' . SUPABASE_KEY,
            'Prefer: return=representation'
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($method === 'POST' || $method === 'PATCH' || $method === 'PUT') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        if ($method !== 'GET' && $method !== 'POST') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            error_log("Error cURL: $error");
            return false;
        }
        
        if ($http_code >= 200 && $http_code < 300) {
            return json_decode($response, true);
        } else {
            error_log("Error Supabase ($http_code): $response");
            return false;
        }
    }
}

// Función para realizar solicitudes a la API de Supabase (retrocompatibilidad)
function supabase_request($endpoint, $method = 'GET', $data = null) {
    return SupabaseConfig::request('/rest/v1/' . $endpoint, $method, $data);
}

// Funciones específicas para cada tabla

// Usuarios
function get_users() {
    return supabase_request('usuarios?select=*&order=nombre.asc');
}

function get_user($id) {
    $result = supabase_request("usuarios?id=eq.$id");
    return !empty($result) ? $result[0] : null;
}

function get_user_by_qr_code($qr_code) {
    $result = supabase_request("usuarios?codigo_qr=eq." . urlencode($qr_code) . "&activo=eq.true");
    return !empty($result) ? $result[0] : null;
}

function create_user($data) {
    // Generar código QR único si no se proporciona
    if (!isset($data['codigo_qr'])) {
        $data['codigo_qr'] = generate_unique_qr_code();
    }
    
    // Establecer valores por defecto
    if (!isset($data['activo'])) {
        $data['activo'] = true;
    }
    
    if (!isset($data['creado_en'])) {
        $data['creado_en'] = date('Y-m-d H:i:s');
    }
    
    return supabase_request('usuarios', 'POST', $data);
}

function update_user($id, $data) {
    $data['actualizado_en'] = date('Y-m-d H:i:s');
    return supabase_request("usuarios?id=eq.$id", 'PATCH', $data);
}

function delete_user($id) {
    // Eliminación lógica (desactivar usuario)
    return supabase_request("usuarios?id=eq.$id", 'PATCH', ['activo' => false]);
}

// Asistencias
function get_attendance_records($filters = []) {
    $query = 'asistencias?select=*,usuarios(nombre,email,rol)&order=fecha.desc,hora.desc';
    
    // Aplicar filtros si existen
    if (!empty($filters)) {
        foreach ($filters as $key => $value) {
            if ($key === 'fecha' && !empty($value)) {
                $query .= "&fecha=eq.$value";
            }
            if ($key === 'estado' && !empty($value) && $value !== 'all') {
                $query .= "&estado=eq.$value";
            }
            if ($key === 'usuario_id' && !empty($value)) {
                $query .= "&usuario_id=eq.$value";
            }
            if ($key === 'fecha_inicio' && !empty($value)) {
                $query .= "&fecha=gte.$value";
            }
            if ($key === 'fecha_fin' && !empty($value)) {
                $query .= "&fecha=lte.$value";
            }
        }
    }
    
    return supabase_request($query);
}

function record_attendance($data) {
    // Verificar si ya existe asistencia para este usuario hoy
    $existing = supabase_request("asistencias?usuario_id=eq.{$data['usuario_id']}&fecha=eq.{$data['fecha']}");
    
    if (!empty($existing)) {
        return [
            'error' => 'Ya existe registro de asistencia para este usuario en esta fecha'
        ];
    }
    
    if (!isset($data['registrado_en'])) {
        $data['registrado_en'] = date('Y-m-d H:i:s');
    }
    
    return supabase_request('asistencias', 'POST', $data);
}

function update_attendance($id, $data) {
    return supabase_request("asistencias?id=eq.$id", 'PATCH', $data);
}

// Obtener estadísticas
function get_attendance_stats($date = null) {
    // Si no se proporciona fecha, usar la fecha actual
    if ($date === null) {
        $date = date('Y-m-d');
    }
    
    $present = supabase_request("asistencias?fecha=eq.$date&estado=eq.presente&select=count");
    $absent = supabase_request("asistencias?fecha=eq.$date&estado=eq.ausente&select=count");
    $late = supabase_request("asistencias?fecha=eq.$date&estado=eq.tardanza&select=count");
    $total = supabase_request("usuarios?activo=eq.true&select=count");
    
    $presentCount = isset($present[0]['count']) ? $present[0]['count'] : 0;
    $absentCount = isset($absent[0]['count']) ? $absent[0]['count'] : 0;
    $lateCount = isset($late[0]['count']) ? $late[0]['count'] : 0;
    $totalCount = isset($total[0]['count']) ? $total[0]['count'] : 0;
    
    // Calcular ausentes reales (total - presentes - tardanzas)
    $realAbsent = $totalCount - ($presentCount + $lateCount);
    
    return [
        'present' => $presentCount,
        'absent' => $realAbsent > 0 ? $realAbsent : 0,
        'late' => $lateCount,
        'total' => $totalCount
    ];
}

// Función para generar un código QR único
function generate_unique_qr_code() {
    do {
        $prefix = 'QR';
        $randomPart = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        $qr_code = $prefix . $randomPart;
        
        // Verificar que no exista ya
        $existing = supabase_request("usuarios?codigo_qr=eq.$qr_code");
    } while (!empty($existing));
    
    return $qr_code;
}

// Función para obtener la configuración del sistema
function get_system_config() {
    $config = supabase_request("configuracion?select=*&limit=1");
    return !empty($config) ? $config[0] : [
        'nombre_organizacion' => 'Mi Organización',
        'hora_limite' => '09:00:00',
        'zona_horaria' => 'America/Mexico_City',
        'notificaciones_email' => 'none'
    ];
}

// Función para determinar el estado de asistencia según la hora
function determine_attendance_status($arrival_time, $limit_time = '09:00:00') {
    return $arrival_time <= $limit_time ? 'presente' : 'tardanza';
}
?>