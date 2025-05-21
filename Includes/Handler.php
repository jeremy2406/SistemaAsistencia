<?php
/**
 * Manejador de solicitudes AJAX
 * 
 * Este archivo procesa todas las solicitudes AJAX del front-end
 */

// Incluir la configuración de Supabase
require_once 'config.php';

// Verificar que la solicitud sea de tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Obtener la acción solicitada
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Procesar según la acción
switch ($action) {
    // Usuario
    case 'get_users':
        $users = get_users();
        echo json_encode(['success' => true, 'data' => $users]);
        break;
        
    case 'add_user':
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $role = isset($_POST['role']) ? $_POST['role'] : 'student';
        
        if (empty($name) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Nombre y correo son obligatorios']);
            break;
        }
        
        // Generar código QR único
        $qr_code = generate_unique_qr_code();
        
        $user_data = [
            'nombre' => $name,
            'email' => $email,
            'rol' => $role,
            'qr_code' => $qr_code,
            'creado_en' => date('Y-m-d H:i:s')
        ];
        
        $result = create_user($user_data);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Usuario agregado correctamente', 'data' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al agregar usuario']);
        }
        break;
        
    case 'delete_user':
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
        
        if (empty($user_id)) {
            echo json_encode(['success' => false, 'message' => 'ID de usuario es obligatorio']);
            break;
        }
        
        $result = delete_user($user_id);
        
        if ($result !== false) {
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar usuario']);
        }
        break;
    
    // Escaneo QR y asistencia
    case 'scan_qr':
        $qr_code = isset($_POST['qr_code']) ? $_POST['qr_code'] : '';
        
        if (empty($qr_code)) {
            echo json_encode(['success' => false, 'message' => 'Código QR inválido']);
            break;
        }
        
        // Buscar usuario por código QR
        $user = get_user_by_qr_code($qr_code);
        
        if (!$user || empty($user)) {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
            break;
        }
        
        $user = $user[0]; // Obtener el primer resultado
        
        // Verificar si ya se registró asistencia hoy
        $today = date('Y-m-d');
        $attendance = get_attendance_records([
            'fecha' => $today,
            'usuario_id' => $user['id']
        ]);
        
        if ($attendance && !empty($attendance)) {
            echo json_encode([
                'success' => false, 
                'message' => 'Ya se registró asistencia para este usuario hoy',
                'user' => $user['nombre']
            ]);
            break;
        }
        
        // Obtener la hora límite de la configuración (por defecto 09:00)
        $time_limit = '09:00:00';
        $current_time = date('H:i:s');
        
        // Determinar estado (presente o tardanza)
        $status = ($current_time <= $time_limit) ? 'presente' : 'tardanza';
        
        // Registrar asistencia
        $attendance_data = [
            'usuario_id' => $user['id'],
            'fecha' => $today,
            'hora' => $current_time,
            'estado' => $status
        ];
        
        $result = record_attendance($attendance_data);
        
        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => 'Asistencia registrada correctamente',
                'user' => $user['nombre'],
                'status' => $status,
                'time' => $current_time
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar asistencia']);
        }
        break;
        
    // Estadísticas y reportes
    case 'get_stats':
        $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
        $stats = get_attendance_stats($date);
        echo json_encode(['success' => true, 'data' => $stats]);
        break;
        
    case 'get_attendance_history':
        $filters = [];
        
        if (isset($_POST['date']) && !empty($_POST['date'])) {
            $filters['fecha'] = $_POST['date'];
        }
        
        if (isset($_POST['status']) && !empty($_POST['status'])) {
            $filters['estado'] = $_POST['status'];
        }
        
        $attendance = get_attendance_records($filters);
        echo json_encode(['success' => true, 'data' => $attendance]);
        break;
        
    case 'get_recent_activity':
        $today = date('Y-m-d');
        $activity = get_attendance_records(['fecha' => $today]);
        echo json_encode(['success' => true, 'data' => $activity]);
        break;
        
    // Exportación de datos
    case 'export_data':
        $format = isset($_POST['format']) ? $_POST['format'] : 'csv';
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
        $type = isset($_POST['type']) ? $_POST['type'] : 'all';
        
        // Construir filtros
        $filters = [];
        
        if (!empty($start_date) && !empty($end_date)) {
            // Para filtrar por rango de fechas, necesitaríamos modificar la función de solicitud a Supabase
            // Por ahora, usaremos la fecha de inicio como filtro simple
            $filters['fecha'] = $start_date;
        }
        
        if ($type !== 'all') {
            $filters['estado'] = $type;
        }
        
        $data = get_attendance_records($filters);
        
        // Responder con los datos para que el front-end los procese según el formato
        echo json_encode(['success' => true, 'data' => $data, 'format' => $format]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no reconocida']);
        break;
}