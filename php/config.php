<?php
// config.php - Versión corregida y simplificada
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Limpiar buffer de salida completamente
while (ob_get_level()) {
    ob_end_clean();
}

class SupabaseClient {
    private $url;
    private $key;
    private $headers;

    public function __construct() {
        $this->url = 'https://nlpfavavqxpwdodyonmi.supabase.co';
        $this->key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im5scGZhdmF2cXhwd2RvZHlvbm1pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDg0MDIyODksImV4cCI6MjA2Mzk3ODI4OX0.aCDdbgJ-2gXyTro5-kVf-YkiLqWHYLOXuB1k9Ss4CBI';
        
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
    // Establecer headers
    setCorsHeaders();
    
    // Verificar que los datos se puedan serializar
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

// SOLO procesar si es una llamada directa a este archivo
if (basename($_SERVER['PHP_SELF']) === 'config.php') {
    setCorsHeaders();
    
    try {
        // Obtener acción de diferentes fuentes
        $action = '';
        
        // Desde query parameters
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        }
        // Desde POST data
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            if (!empty($input)) {
                $post_data = json_decode($input, true);
                if (isset($post_data['accion'])) {
                    $action = $post_data['accion'];
                }
            }
        }

        // Ejecutar función según la acción
        switch ($action) {
            case 'test':
                try {
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
                break;
                
            case 'registrar_asistencia':
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
                break;
                
            case 'obtener_asistencias':
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
                break;
                
            case 'obtener_usuarios':
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
                break;
                
            default:
                sendJsonResponse([
                    'error' => 'Endpoint no encontrado',
                    'action_received' => $action,
                    'request_method' => $_SERVER['REQUEST_METHOD'],
                    'available_actions' => [
                        'test' => 'Para probar la conexión',
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
}
?>