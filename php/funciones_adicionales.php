<?php
// funciones_adicionales.php - Versión completamente corregida
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Limpiar buffer de salida
while (ob_get_level()) {
    ob_end_clean();
}

// Establecer headers CORS inmediatamente
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=utf-8');

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Incluir config sin ejecutar el router
define('CONFIG_INCLUDED', true);
require_once 'config.php';

// Función para enviar respuesta JSON limpia
function sendCleanJsonResponse($data) {
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $json = json_encode([
            'error' => 'Error al codificar JSON: ' . json_last_error_msg()
        ]);
        http_response_code(500);
    }
    
    echo $json;
    exit();
}

// Función para obtener todos los usuarios
function obtenerUsuarios() {
    try {
        $supabase = new SupabaseClient();
        $usuarios = $supabase->select('usuarios', 'id,nombre,apellido,ocupacion,codigo_qr', 'order=nombre.asc');
        return $usuarios;
    } catch (Exception $e) {
        error_log('Error en obtenerUsuarios: ' . $e->getMessage());
        return ['error' => $e->getMessage()];
    }
}

// Función para crear un nuevo usuario
function crearUsuario($nombre, $apellido, $ocupacion, $codigo_qr) {
    try {
        $supabase = new SupabaseClient();
        
        // Verificar que el código QR no exista
        $existente = $supabase->select('usuarios', 'id', 'codigo_qr=eq.' . urlencode($codigo_qr));
        if (!empty($existente)) {
            throw new Exception('El código QR ya existe');
        }

        $datos = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'ocupacion' => $ocupacion,
            'codigo_qr' => $codigo_qr
        ];

        $resultado = $supabase->insert('usuarios', $datos);
        return [
            'success' => true,
            'message' => 'Usuario creado exitosamente',
            'data' => $resultado
        ];
    } catch (Exception $e) {
        error_log('Error en crearUsuario: ' . $e->getMessage());
        return ['error' => $e->getMessage()];
    }
}

// Función para obtener estadísticas de asistencia
function obtenerEstadisticas($fecha = null) {
    try {
        $supabase = new SupabaseClient();
        $fecha = $fecha ?: date('Y-m-d');
        
        // Total de usuarios registrados
        $totalUsuarios = $supabase->select('usuarios', 'id');
        $cantidadUsuarios = count($totalUsuarios);
        
        // Asistencias del día
        $asistenciasHoy = $supabase->select('asistencias', 'id', 'fecha=eq.' . $fecha);
        $cantidadAsistencias = count($asistenciasHoy);
        
        // Porcentaje de asistencia
        $porcentaje = $cantidadUsuarios > 0 ? ($cantidadAsistencias / $cantidadUsuarios) * 100 : 0;
        
        return [
            'fecha' => $fecha,
            'total_usuarios' => $cantidadUsuarios,
            'total_asistencias' => $cantidadAsistencias,
            'porcentaje_asistencia' => round($porcentaje, 2),
            'ausentes' => $cantidadUsuarios - $cantidadAsistencias
        ];
    } catch (Exception $e) {
        error_log('Error en obtenerEstadisticas: ' . $e->getMessage());
        return ['error' => $e->getMessage()];
    }
}

// Función para obtener historial de asistencia de un usuario
function obtenerHistorialUsuario($usuario_id, $limite = 10) {
    try {
        $supabase = new SupabaseClient();
        return $supabase->select(
            'asistencias',
            'fecha,hora_llegada,estatus',
            'usuario_id=eq.' . $usuario_id . '&order=fecha.desc&limit=' . $limite
        );
    } catch (Exception $e) {
        error_log('Error en obtenerHistorialUsuario: ' . $e->getMessage());
        return ['error' => $e->getMessage()];
    }
}

// Función para generar reporte de asistencia por rango de fechas
function generarReporte($fecha_inicio, $fecha_fin) {
    try {
        $supabase = new SupabaseClient();
        $asistencias = $supabase->select(
            'asistencias',
            'fecha,hora_llegada,estatus,usuarios(nombre,apellido,ocupacion)',
            'fecha=gte.' . $fecha_inicio . '&fecha=lte.' . $fecha_fin . '&order=fecha.desc,hora_llegada.desc'
        );
        
        return $asistencias;
    } catch (Exception $e) {
        error_log('Error en generarReporte: ' . $e->getMessage());
        return ['error' => $e->getMessage()];
    }
}

// Procesar requests GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $accion = $_GET['accion'] ?? '';
        
        switch ($accion) {
            case 'usuarios':
                $resultado = obtenerUsuarios();
                sendCleanJsonResponse($resultado);
                break;
                
            case 'estadisticas':
                $fecha = $_GET['fecha'] ?? null;
                $resultado = obtenerEstadisticas($fecha);
                sendCleanJsonResponse($resultado);
                break;
                
            case 'historial':
                $usuario_id = $_GET['usuario_id'] ?? '';
                $limite = $_GET['limite'] ?? 10;
                if (empty($usuario_id)) {
                    sendCleanJsonResponse(['error' => 'ID de usuario requerido']);
                } else {
                    $resultado = obtenerHistorialUsuario($usuario_id, $limite);
                    sendCleanJsonResponse($resultado);
                }
                break;
                
            case 'reporte':
                $fecha_inicio = $_GET['fecha_inicio'] ?? '';
                $fecha_fin = $_GET['fecha_fin'] ?? '';
                if (empty($fecha_inicio) || empty($fecha_fin)) {
                    sendCleanJsonResponse(['error' => 'Fechas de inicio y fin requeridas']);
                } else {
                    $resultado = generarReporte($fecha_inicio, $fecha_fin);
                    sendCleanJsonResponse($resultado);
                }
                break;
                
            default:
                sendCleanJsonResponse([
                    'error' => 'Acción no válida',
                    'accion_recibida' => $accion,
                    'acciones_disponibles' => [
                        'usuarios' => 'Obtener lista de usuarios',
                        'estadisticas' => 'Obtener estadísticas de asistencia',
                        'historial' => 'Obtener historial de usuario (requiere usuario_id)',
                        'reporte' => 'Generar reporte (requiere fecha_inicio y fecha_fin)'
                    ]
                ]);
        }
        
    } catch (Exception $e) {
        error_log('Error en GET request: ' . $e->getMessage());
        sendCleanJsonResponse(['error' => 'Error interno: ' . $e->getMessage()]);
    }
}

// Procesar requests POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $input = file_get_contents('php://input');
        
        if (empty($input)) {
            sendCleanJsonResponse(['error' => 'No se recibieron datos']);
        }
        
        $data = json_decode($input, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            sendCleanJsonResponse(['error' => 'JSON no válido: ' . json_last_error_msg()]);
        }
        
        $accion = $data['accion'] ?? '';
        
        if ($accion === 'crear_usuario') {
            $nombre = trim($data['nombre'] ?? '');
            $apellido = trim($data['apellido'] ?? '');
            $ocupacion = trim($data['ocupacion'] ?? '');
            $codigo_qr = trim($data['codigo_qr'] ?? '');
            
            if (empty($nombre) || empty($apellido) || empty($ocupacion) || empty($codigo_qr)) {
                sendCleanJsonResponse(['error' => 'Todos los campos son requeridos']);
            } else {
                $resultado = crearUsuario($nombre, $apellido, $ocupacion, $codigo_qr);
                sendCleanJsonResponse($resultado);
            }
        } else {
            sendCleanJsonResponse([
                'error' => 'Acción POST no válida',
                'accion_recibida' => $accion,
                'acciones_post_disponibles' => [
                    'crear_usuario' => 'Crear nuevo usuario (requiere nombre, apellido, ocupacion, codigo_qr)'
                ]
            ]);
        }
        
    } catch (Exception $e) {
        error_log('Error en POST request: ' . $e->getMessage());
        sendCleanJsonResponse(['error' => 'Error interno: ' . $e->getMessage()]);
    }
}

// Si no es GET ni POST
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    sendCleanJsonResponse([
        'error' => 'Método no permitido',
        'metodo_recibido' => $_SERVER['REQUEST_METHOD'],
        'metodos_permitidos' => ['GET', 'POST']
    ]);
}
?>