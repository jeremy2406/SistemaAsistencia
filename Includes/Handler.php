<?php
/**
 * Manejador de solicitudes AJAX
 * 
 * Este archivo procesa todas las solicitudes AJAX del front-end
 */

// Incluir las clases necesarias
require_once 'Config.php';
require_once 'Usuarios.php';
require_once 'Asistencia.php';
require_once 'Configuracion.php';

// Configurar headers para JSON
header('Content-Type: application/json');

// Verificar que la solicitud sea de tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener la acción solicitada
$action = isset($_POST['action']) ? $_POST['action'] : '';

try {
    // Procesar según la acción
    switch ($action) {
        // ========== USUARIOS ==========
        case 'get_users':
            $users = Usuario::obtenerUsuarios();
            echo json_encode(['success' => true, 'data' => $users]);
            break;
            
        case 'add_user':
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $role = isset($_POST['role']) ? $_POST['role'] : 'estudiante';
            
            if (empty($name) || empty($email)) {
                echo json_encode(['success' => false, 'message' => 'Nombre y correo son obligatorios']);
                break;
            }
            
            $result = Usuario::crearUsuario($name, $email, $role);
            echo json_encode($result);
            break;
            
        case 'update_user':
            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
            $data = [];
            
            if (isset($_POST['name']) && !empty($_POST['name'])) {
                $data['nombre'] = trim($_POST['name']);
            }
            if (isset($_POST['email']) && !empty($_POST['email'])) {
                $data['email'] = trim($_POST['email']);
            }
            if (isset($_POST['role']) && !empty($_POST['role'])) {
                $data['rol'] = $_POST['role'];
            }
            
            if (empty($user_id)) {
                echo json_encode(['success' => false, 'message' => 'ID de usuario es obligatorio']);
                break;
            }
            
            $result = Usuario::actualizarUsuario($user_id, $data);
            echo json_encode($result);
            break;
            
        case 'delete_user':
            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
            
            if (empty($user_id)) {
                echo json_encode(['success' => false, 'message' => 'ID de usuario es obligatorio']);
                break;
            }
            
            $result = Usuario::eliminarUsuario($user_id);
            echo json_encode($result);
            break;
            
        case 'regenerate_qr':
            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
            
            if (empty($user_id)) {
                echo json_encode(['success' => false, 'message' => 'ID de usuario es obligatorio']);
                break;
            }
            
            $result = Usuario::regenerarCodigoQR($user_id);
            echo json_encode($result);
            break;
        
        // ========== ESCANEO QR Y ASISTENCIA ==========
        case 'scan_qr':
            $qr_code = isset($_POST['qr_code']) ? trim($_POST['qr_code']) : '';
            
            if (empty($qr_code)) {
                echo json_encode(['success' => false, 'message' => 'Código QR inválido']);
                break;
            }
            
            $result = Asistencia::registrarAsistencia($qr_code);
            echo json_encode($result);
            break;
            
        case 'mark_attendance':
            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
            $status = isset($_POST['status']) ? $_POST['status'] : 'presente';
            $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
            $time = isset($_POST['time']) ? $_POST['time'] : date('H:i:s');
            
            if (empty($user_id)) {
                echo json_encode(['success' => false, 'message' => 'ID de usuario es obligatorio']);
                break;
            }
            
            // Verificar si ya existe asistencia
            $existing = get_attendance_records([
                'fecha' => $date,
                'usuario_id' => $user_id
            ]);
            
            if (!empty($existing)) {
                echo json_encode(['success' => false, 'message' => 'Ya existe registro de asistencia para este usuario']);
                break;
            }
            
            $attendance_data = [
                'usuario_id' => $user_id,
                'fecha' => $date,
                'hora' => $time,
                'estado' => $status
            ];
            
            $result = record_attendance($attendance_data);
            
            if ($result && !isset($result['error'])) {
                echo json_encode(['success' => true, 'message' => 'Asistencia registrada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => $result['error'] ?? 'Error al registrar asistencia']);
            }
            break;
            
        // ========== ESTADÍSTICAS Y REPORTES ==========
        case 'get_stats':
            $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
            $stats = Asistencia::obtenerEstadisticasHoy();
            echo json_encode(['success' => true, 'data' => $stats]);
            break;
            
        case 'get_attendance_history':
            $filters = [];
            
            if (isset($_POST['date']) && !empty($_POST['date'])) {
                $filters['fecha'] = $_POST['date'];
            }
            
            if (isset($_POST['status']) && !empty($_POST['status']) && $_POST['status'] !== 'all') {
                $filters['estado'] = $_POST['status'];
            }
            
            if (isset($_POST['date_start']) && !empty($_POST['date_start'])) {
                $filters['fecha_inicio'] = $_POST['date_start'];
            }
            
            if (isset($_POST['date_end']) && !empty($_POST['date_end'])) {
                $filters['fecha_fin'] = $_POST['date_end'];
            }
            
            $attendance = get_attendance_records($filters);
            echo json_encode(['success' => true, 'data' => $attendance]);
            break;
            
        case 'get_recent_activity':
            $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 10;
            $activity = Asistencia::obtenerAsistenciasRecientes($limit);
            echo json_encode(['success' => true, 'data' => $activity]);
            break;
            
        // ========== EXPORTACIÓN DE DATOS ==========
        case 'export_data':
            $format = isset($_POST['format']) ? $_POST['format'] : 'csv';
            $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
            $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
            $type = isset($_POST['type']) ? $_POST['type'] : 'all';
            
            if (empty($start_date) || empty($end_date)) {
                echo json_encode(['success' => false, 'message' => 'Las fechas de inicio y fin son obligatorias']);
                break;
            }
            
            $data = Asistencia::exportarDatos($start_date, $end_date, $type);
            
            if ($data === false) {
                echo json_encode(['success' => false, 'message' => 'Error al obtener los datos']);
                break;
            }
            
            // Procesar datos según el formato
            switch ($format) {
                case 'csv':
                    $csv_data = generate_csv($data);
                    echo json_encode([
                        'success' => true, 
                        'data' => $csv_data, 
                        'format' => 'csv',
                        'filename' => 'asistencia_' . $start_date . '_' . $end_date . '.csv'
                    ]);
                    break;
                    
                case 'excel':
                case 'pdf':
                    // Para Excel y PDF, devolver los datos para procesamiento en el frontend
                    echo json_encode([
                        'success' => true, 
                        'data' => $data, 
                        'format' => $format,
                        'filename' => 'asistencia_' . $start_date . '_' . $end_date
                    ]);
                    break;
                    
                default:
                    echo json_encode(['success' => false, 'message' => 'Formato no soportado']);
            }
            break;
            
        // ========== CONFIGURACIÓN ==========
        case 'get_config':
            $config = Configuracion::obtenerConfiguracion();
            echo json_encode(['success' => true, 'data' => $config]);
            break;
            
        case 'update_config':
            $config_data = [];
            
            if (isset($_POST['nombre_organizacion'])) {
                $config_data['nombre_organizacion'] = trim($_POST['nombre_organizacion']);
            }
            if (isset($_POST['hora_limite'])) {
                $config_data['hora_limite'] = $_POST['hora_limite'] . ':00';
            }
            if (isset($_POST['zona_horaria'])) {
                $config_data['zona_horaria'] = $_POST['zona_horaria'];
            }
            if (isset($_POST['notificaciones_email'])) {
                $config_data['notificaciones_email'] = $_POST['notificaciones_email'];
            }
            
            $result = Configuracion::actualizarConfiguracion($config_data);
            echo json_encode($result);
            break;
            
        // ========== ACCIONES ADMINISTRATIVAS ==========
        case 'mark_all_absent':
            $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
            $result = Asistencia::marcarAusencias($date);
            echo json_encode($result);
            break;
            
        case 'search_users':
            $search = isset($_POST['search']) ? trim($_POST['search']) : '';
            $users = Usuario::obtenerUsuarios($search);
            echo json_encode(['success' => true, 'data' => $users]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no reconocida: ' . $action]);
            break;
    }
    
} catch (Exception $e) {
    error_log("Error en handler: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
}

// ========== FUNCIONES AUXILIARES ==========

/**
 * Genera contenido CSV a partir de los datos de asistencia
 */
function generate_csv($data) {
    if (empty($data)) {
        return '';
    }
    
    $csv = "Nombre,Email,Rol,Fecha,Estado,Hora\n";
    
    foreach ($data as $record) {
        $nombre = isset($record['usuarios']['nombre']) ? $record['usuarios']['nombre'] : 'N/A';
        $email = isset($record['usuarios']['email']) ? $record['usuarios']['email'] : 'N/A';
        $rol = isset($record['usuarios']['rol']) ? $record['usuarios']['rol'] : 'N/A';
        $fecha = $record['fecha'] ?? 'N/A';
        $estado = $record['estado'] ?? 'N/A';
        $hora = $record['hora'] ?? 'N/A';
        
        $csv .= "\"$nombre\",\"$email\",\"$rol\",\"$fecha\",\"$estado\",\"$hora\"\n";
    }
    
    return $csv;
}
?>