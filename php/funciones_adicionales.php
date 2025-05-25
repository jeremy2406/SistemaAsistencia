<?php
// Funciones adicionales para el sistema

require_once 'config.php';

// Función para obtener todos los usuarios
function obtenerUsuarios() {
    $supabase = new SupabaseClient();
    try {
        return $supabase->select('usuarios', '*', 'order=nombre.asc');
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

// Función para crear un nuevo usuario
function crearUsuario($nombre, $apellido, $ocupacion, $codigo_qr) {
    $supabase = new SupabaseClient();
    try {
        // Verificar que el código QR no exista
        $existente = $supabase->select('usuarios', 'id', 'codigo_qr=eq.' . $codigo_qr);
        if (!empty($existente)) {
            throw new Exception('El código QR ya existe');
        }

        $datos = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'ocupacion' => $ocupacion,
            'codigo_qr' => $codigo_qr
        ];

        return $supabase->insert('usuarios', $datos);
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

// Función para obtener estadísticas de asistencia
function obtenerEstadisticas($fecha = null) {
    $supabase = new SupabaseClient();
    try {
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
        return ['error' => $e->getMessage()];
    }
}

// Función para obtener historial de asistencia de un usuario
function obtenerHistorialUsuario($usuario_id, $limite = 10) {
    $supabase = new SupabaseClient();
    try {
        return $supabase->select(
            'asistencias',
            'fecha,hora_llegada,estatus',
            'usuario_id=eq.' . $usuario_id . '&order=fecha.desc&limit=' . $limite
        );
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

// Función para generar reporte de asistencia por rango de fechas
function generarReporte($fecha_inicio, $fecha_fin) {
    $supabase = new SupabaseClient();
    try {
        $asistencias = $supabase->select(
            'asistencias',
            'fecha,hora_llegada,estatus,usuarios(nombre,apellido,ocupacion)',
            'fecha=gte.' . $fecha_inicio . '&fecha=lte.' . $fecha_fin . '&order=fecha.desc,hora_llegada.desc'
        );
        
        return $asistencias;
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

// Endpoints para las funciones adicionales

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $accion = $_GET['accion'] ?? '';
    
    switch ($accion) {
        case 'usuarios':
            header('Content-Type: application/json');
            echo json_encode(obtenerUsuarios());
            break;
            
        case 'estadisticas':
            header('Content-Type: application/json');
            $fecha = $_GET['fecha'] ?? null;
            echo json_encode(obtenerEstadisticas($fecha));
            break;
            
        case 'historial':
            header('Content-Type: application/json');
            $usuario_id = $_GET['usuario_id'] ?? '';
            $limite = $_GET['limite'] ?? 10;
            if (empty($usuario_id)) {
                echo json_encode(['error' => 'ID de usuario requerido']);
            } else {
                echo json_encode(obtenerHistorialUsuario($usuario_id, $limite));
            }
            break;
            
        case 'reporte':
            header('Content-Type: application/json');
            $fecha_inicio = $_GET['fecha_inicio'] ?? '';
            $fecha_fin = $_GET['fecha_fin'] ?? '';
            if (empty($fecha_inicio) || empty($fecha_fin)) {
                echo json_encode(['error' => 'Fechas de inicio y fin requeridas']);
            } else {
                echo json_encode(generarReporte($fecha_inicio, $fecha_fin));
            }
            break;
            
        default:
            echo json_encode(['error' => 'Acción no válida']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $accion = $input['accion'] ?? '';
    
    if ($accion === 'crear_usuario') {
        header('Content-Type: application/json');
        $nombre = $input['nombre'] ?? '';
        $apellido = $input['apellido'] ?? '';
        $ocupacion = $input['ocupacion'] ?? '';
        $codigo_qr = $input['codigo_qr'] ?? '';
        
        if (empty($nombre) || empty($apellido) || empty($ocupacion) || empty($codigo_qr)) {
            echo json_encode(['error' => 'Todos los campos son requeridos']);
        } else {
            echo json_encode(crearUsuario($nombre, $apellido, $ocupacion, $codigo_qr));
        }
    } else {
        echo json_encode(['error' => 'Acción no válida']);
    }
}
?>