<?php
/**
 * Configuración de Supabase
 * 
 * Este archivo contiene las credenciales y funciones para conectarse a Supabase
 */

// Credenciales de Supabase (reemplazar con tus propias credenciales)
define('SUPABASE_URL', 'https://llzrabdkomezsjyihkhz.supabase.co');
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImxsenJhYmRrb21lenNqeWloa2h6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDc4NDM0NzcsImV4cCI6MjA2MzQxOTQ3N30.0J9YBaRBO63YMHKKCP_6WHwjPP-_cmL5tA1TBnyk4OM');

// Función para realizar solicitudes a la API de Supabase
function supabase_request($endpoint, $method = 'GET', $data = null) {
    $url = SUPABASE_URL . '/rest/v1/' . $endpoint;
    
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
    curl_close($ch);
    
    if ($http_code >= 200 && $http_code < 300) {
        return json_decode($response, true);
    } else {
        error_log("Error Supabase ($http_code): $response");
        return false;
    }
}

// Funciones específicas para cada tabla
// Estas funciones se utilizarán para interactuar con las tablas de Supabase

// Usuarios
function get_users() {
    return supabase_request('usuarios?select=*');
}

function get_user($id) {
    return supabase_request("usuarios?id=eq.$id");
}

function get_user_by_qr_code($qr_code) {
    return supabase_request("usuarios?qr_code=eq.$qr_code");
}

function create_user($data) {
    return supabase_request('usuarios', 'POST', $data);
}

function update_user($id, $data) {
    return supabase_request("usuarios?id=eq.$id", 'PATCH', $data);
}

function delete_user($id) {
    return supabase_request("usuarios?id=eq.$id", 'DELETE');
}

// Asistencias
function get_attendance_records($filters = []) {
    $query = 'asistencias?select=*,usuarios(nombre,email,rol)';
    
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
        }
    }
    
    return supabase_request($query);
}

function record_attendance($data) {
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
    $total = supabase_request("usuarios?select=count");
    
    return [
        'present' => isset($present[0]['count']) ? $present[0]['count'] : 0,
        'absent' => isset($absent[0]['count']) ? $absent[0]['count'] : 0,
        'late' => isset($late[0]['count']) ? $late[0]['count'] : 0,
        'total' => isset($total[0]['count']) ? $total[0]['count'] : 0
    ];
}

// Función para generar un código QR único
function generate_unique_qr_code() {
    $prefix = 'QR';
    $randomPart = substr(str_shuffle(MD5(microtime())), 0, 10);
    return $prefix . $randomPart;
}