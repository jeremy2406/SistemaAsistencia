<?php
// config.php - Configuración corregida con mejor manejo de errores y headers
// Limpiar buffer de salida completamente
while (ob_get_level()) {
    ob_end_clean();
}

// Iniciar buffer limpio
ob_start();

// Configuración de errores para producción
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

class SupabaseClient {
    private $url;
    private $key;
    private $headers;

    public function __construct() {
        $this->url = 'https://ftrfqvqaaandvmvhdirk.supabase.co';
        $this->key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ0cmZxdnFhYWFuZHZtdmhkaXJrIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDgyMTE0ODIsImV4cCI6MjA2Mzc4NzQ4Mn0.92EsJxnyxOgzuQuZV_BiaybWUelpxbQCZ-vyLFLPs_c';
        
        $this->headers = [
            'Content-Type: application/json',
            'apikey: ' . $this->key,
            'Authorization: Bearer ' . $this->key,
            'Prefer: return=representation'
        ];
    }

    public function select($table, $columns = '*', $conditions = null) {
        $url = $this->url . '/rest/v1/' . $table . '?select=' . urlencode($columns);
        
        if ($conditions) {
            $url .= '&' . $conditions;
        }

        return $this->makeRequest('GET', $url);
    }

    public function insert($table, $data) {
        $url = $this->url . '/rest/v1/' . $table;
        return $this->makeRequest('POST', $url, $data);
    }

    private function makeRequest($method, $url, $data = null) {
        $ch = curl_init();
        
        if (!$ch) {
            throw new Exception('Error al inicializar cURL');
        }
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SistemaAsistenciaQR/1.0');
        
        if ($data && ($method === 'POST' || $method === 'PATCH')) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception('Error de conexión: ' . $error);
        }
        
        if ($response === false) {
            throw new Exception('Error: No se recibió respuesta del servidor');
        }
        
        if ($httpCode >= 200 && $httpCode < 300) {
            $decoded = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
            }
            return $decoded !== null ? $decoded : [];
        } else {
            $errorResponse = json_decode($response, true);
            $errorMessage = 'Error HTTP ' . $httpCode;
            if ($errorResponse && isset($errorResponse['message'])) {
                $errorMessage .= ': ' . $errorResponse['message'];
            }
            throw new Exception($errorMessage);
        }
    }

    public function testConnection() {
        try {
            $result = $this->select('usuarios', 'count');
            return true;
        } catch (Exception $e) {
            error_log('Error de conexión Supabase: ' . $e->getMessage());
            return false;
        }
    }
}

// Función para establecer headers CORS
function setCorsHeaders() {
    // Limpiar buffer antes de enviar headers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Max-Age: 86400');
    header('Content-Type: application/json; charset=utf-8');
    
    // Manejar preflight OPTIONS
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

// Función para enviar respuesta JSON limpia
function sendJsonResponse($data) {
    // Limpiar cualquier salida previa
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Establecer headers
    setCorsHeaders();
    
    // Verificar que los datos se puedan serializar
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $json = json_encode([
            'error' => 'Error al codificar JSON: ' . json_last_error_msg()
        ]);
    }
    
    echo $json;
    exit();
}

// Función para crear usuario
function crearUsuario() {
    try {
        $input = file_get_contents('php://input');
        if (empty($input)) {
            throw new Exception('No se recibieron datos');
        }

        $data = json_decode($input, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON no válido: ' . json_last_error_msg());
        }

        $nombre = trim($data['nombre'] ?? '');
        $apellido = trim($data['apellido'] ?? '');
        $ocupacion = trim($data['ocupacion'] ?? '');
        $codigo_qr = trim($data['codigo_qr'] ?? '');

        if (empty($nombre) || empty($apellido) || empty($ocupacion) || empty($codigo_qr)) {
            throw new Exception('Todos los campos son requeridos');
        }

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

        sendJsonResponse([
            'success' => true,
            'message' => 'Usuario creado exitosamente',
            'data' => $resultado
        ]);

    } catch (Exception $e) {
        error_log('Error en crearUsuario: ' . $e->getMessage());
        sendJsonResponse([
            'error' => $e->getMessage()
        ]);
    }
}

// registrar_asistencia.php
function registrarAsistencia() {
    try {
        $input = file_get_contents('php://input');
        if (empty($input)) {
            throw new Exception('No se recibieron datos');
        }

        $data = json_decode($input, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON no válido: ' . json_last_error_msg());
        }

        if (!isset($data['codigo_qr']) || empty(trim($data['codigo_qr']))) {
            throw new Exception('Código QR requerido');
        }

        $codigo_qr = trim($data['codigo_qr']);
        $supabase = new SupabaseClient();

        // Buscar usuario
        $usuarios = $supabase->select('usuarios', '*', 'codigo_qr=eq.' . urlencode($codigo_qr));
        
        if (empty($usuarios)) {
            sendJsonResponse([
                'success' => false,
                'mensaje' => 'Código QR no encontrado'
            ]);
        }

        $usuario = $usuarios[0];
        $fecha_hoy = date('Y-m-d');
        $fecha_hora_actual = date('Y-m-d H:i:s');

        // Verificar asistencia existente
        $asistencia_existente = $supabase->select(
            'asistencias', 
            '*', 
            'usuario_id=eq.' . $usuario['id'] . '&fecha=eq.' . $fecha_hoy
        );

        if (!empty($asistencia_existente)) {
            $hora_existente = date('H:i:s', strtotime($asistencia_existente[0]['hora_llegada']));
            sendJsonResponse([
                'success' => false,
                'mensaje' => 'Ya registraste tu asistencia hoy a las ' . $hora_existente
            ]);
        }

        // Registrar asistencia
        $nueva_asistencia = [
            'usuario_id' => $usuario['id'],
            'estatus' => 'Presente',
            'fecha' => $fecha_hoy,
            'hora_llegada' => $fecha_hora_actual
        ];

        $resultado = $supabase->insert('asistencias', $nueva_asistencia);

        sendJsonResponse([
            'success' => true,
            'mensaje' => '¡Asistencia registrada exitosamente!',
            'datos' => [
                'usuario' => $usuario['nombre'] . ' ' . $usuario['apellido'],
                'ocupacion' => $usuario['ocupacion'],
                'hora' => date('H:i:s'),
                'fecha' => $fecha_hoy
            ]
        ]);

    } catch (Exception $e) {
        error_log('Error en registrar_asistencia: ' . $e->getMessage());
        sendJsonResponse([
            'success' => false,
            'mensaje' => 'Error interno: ' . $e->getMessage()
        ]);
    }
}

// obtener_asistencias.php
function obtenerAsistencias() {
    try {
        $supabase = new SupabaseClient();
        
        $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
        
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            throw new Exception('Formato de fecha no válido');
        }

        $asistencias = $supabase->select(
            'asistencias', 
            'id,hora_llegada,estatus,fecha,usuarios(nombre,apellido,ocupacion)',
            'fecha=eq.' . $fecha . '&order=hora_llegada.desc'
        );

        if (empty($asistencias)) {
            sendJsonResponse([]);
            return;
        }

        $resultado = array_map(function($asistencia) {
            return [
                'id' => $asistencia['id'],
                'hora_llegada' => $asistencia['hora_llegada'],
                'estatus' => $asistencia['estatus'],
                'fecha' => $asistencia['fecha'],
                'usuarios' => $asistencia['usuarios'] ?? [
                    'nombre' => 'N/A',
                    'apellido' => 'N/A',
                    'ocupacion' => 'N/A'
                ]
            ];
        }, $asistencias);

        sendJsonResponse($resultado);

    } catch (Exception $e) {
        error_log('Error en obtener_asistencias: ' . $e->getMessage());
        sendJsonResponse([
            'error' => 'Error al obtener datos: ' . $e->getMessage()
        ]);
    }
}

// obtener_usuarios.php
function obtenerUsuarios() {
    try {
        $supabase = new SupabaseClient();
        
        $usuarios = $supabase->select(
            'usuarios', 
            'id,nombre,apellido,ocupacion,codigo_qr',
            'order=nombre.asc'
        );

        sendJsonResponse($usuarios);

    } catch (Exception $e) {
        error_log('Error en obtener_usuarios: ' . $e->getMessage());
        sendJsonResponse([
            'error' => 'Error al obtener usuarios: ' . $e->getMessage()
        ]);
    }
}

// test.php
function testConnection() {
    try {
        $required_extensions = ['curl', 'json'];
        $missing_extensions = [];
        
        foreach ($required_extensions as $ext) {
            if (!extension_loaded($ext)) {
                $missing_extensions[] = $ext;
            }
        }
        
        if (!empty($missing_extensions)) {
            sendJsonResponse([
                'status' => 'error',
                'message' => 'Extensiones PHP faltantes: ' . implode(', ', $missing_extensions)
            ]);
        }

        $supabase = new SupabaseClient();
        
        if (!$supabase->testConnection()) {
            sendJsonResponse([
                'status' => 'error',
                'message' => 'No se pudo conectar a Supabase'
            ]);
        }

        $usuarios = $supabase->select('usuarios', '*');
        $asistencias = $supabase->select('asistencias', '*');
        
        sendJsonResponse([
            'status' => 'success',
            'message' => '✅ Conexión exitosa',
            'data' => [
                'total_usuarios' => count($usuarios),
                'total_asistencias' => count($asistencias),
                'php_version' => PHP_VERSION,
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ]);
        
    } catch (Exception $e) {
        error_log('Error en test: ' . $e->getMessage());
        sendJsonResponse([
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

// Router principal con headers CORS establecidos desde el inicio
setCorsHeaders();

try {
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    $path_info = parse_url($request_uri, PHP_URL_PATH);
    $query_string = parse_url($request_uri, PHP_URL_QUERY);
    parse_str($query_string ?? '', $query_params);

    // Obtener acción de diferentes fuentes
    $action = '';
    
    // 1. Desde query parameters
    if (isset($query_params['action'])) {
        $action = $query_params['action'];
    }
    // 2. Desde POST data
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = file_get_contents('php://input');
        if (!empty($input)) {
            $post_data = json_decode($input, true);
            if (isset($post_data['accion'])) {
                $action = $post_data['accion'];
            }
        }
    }
    // 3. Desde la URL path
    elseif (strpos($path_info, 'registrar_asistencia') !== false) {
        $action = 'registrar_asistencia';
    }
    elseif (strpos($path_info, 'obtener_asistencias') !== false) {
        $action = 'obtener_asistencias';
    }
    elseif (strpos($path_info, 'obtener_usuarios') !== false) {
        $action = 'obtener_usuarios';
    }
    elseif (strpos($path_info, 'test') !== false) {
        $action = 'test';
    }

    // Ejecutar función según la acción
    switch ($action) {
        case 'crear_usuario':
            crearUsuario();
            break;
            
        case 'registrar_asistencia':
            registrarAsistencia();
            break;
            
        case 'obtener_asistencias':
            obtenerAsistencias();
            break;
            
        case 'obtener_usuarios':
            obtenerUsuarios();
            break;
            
        case 'test':
            testConnection();
            break;
            
        default:
            sendJsonResponse([
                'error' => 'Endpoint no encontrado',
                'action_received' => $action,
                'request_method' => $_SERVER['REQUEST_METHOD'],
                'request_uri' => $request_uri,
                'available_actions' => [
                    'test' => 'Para probar la conexión',
                    'crear_usuario' => 'Para crear usuario (POST)',
                    'registrar_asistencia' => 'Para registrar asistencia (POST)',
                    'obtener_asistencias' => 'Para obtener asistencias (GET)',
                    'obtener_usuarios' => 'Para obtener usuarios (GET)'
                ]
            ]);
    }
    
} catch (Exception $e) {
    error_log('Error en router principal: ' . $e->getMessage());
    sendJsonResponse([
        'error' => 'Error interno del servidor',
        'message' => $e->getMessage()
    ]);
}
?>