<?php
// config.php - Configuración mejorada de Supabase
class SupabaseClient {
    private $url;
    private $key;
    private $headers;

    public function __construct() {
        // Configuración mejorada con validación
        $this->url = $this->getEnvVariable('SUPABASE_URL', 'https://ftrfqvqaaandvmvhdirk.supabase.co');
        $this->key = $this->getEnvVariable('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ0cmZxdnFhYWFuZHZtdmhkaXJrIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDgyMTE0ODIsImV4cCI6MjA2Mzc4NzQ4Mn0.92EsJxnyxOgzuQuZV_BiaybWUelpxbQCZ-vyLFLPs_c');
        
        if (empty($this->url) || empty($this->key)) {
            throw new Exception('Configuración de Supabase incompleta');
        }
        
        $this->headers = [
            'Content-Type: application/json',
            'apikey: ' . $this->key,
            'Authorization: Bearer ' . $this->key,
            'Prefer: return=representation'
        ];
    }

    private function getEnvVariable($name, $default = null) {
        $value = getenv($name);
        if ($value !== false && !empty($value)) {
            return $value;
        }
        
        if (isset($_ENV[$name]) && !empty($_ENV[$name])) {
            return $_ENV[$name];
        }
        
        if (isset($_SERVER[$name]) && !empty($_SERVER[$name])) {
            return $_SERVER[$name];
        }
        
        return $default;
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
            $this->select('usuarios', 'count');
            return true;
        } catch (Exception $e) {
            error_log('Error de conexión Supabase: ' . $e->getMessage());
            return false;
        }
    }
}

// registrar_asistencia.php - Versión mejorada
function registrarAsistencia() {
    // Headers de respuesta
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    // Manejar preflight request
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    try {
        // Verificar método
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception('Método no permitido');
        }

        // Obtener y validar datos
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
            echo json_encode([
                'success' => false,
                'mensaje' => 'Código QR no encontrado'
            ]);
            exit;
        }

        $usuario = $usuarios[0];
        $fecha_hoy = date('Y-m-d');
        $zona_horaria = new DateTimeZone('America/Mexico_City'); // Ajusta según tu zona
        $fecha_hora = new DateTime('now', $zona_horaria);

        // Verificar asistencia existente
        $asistencia_existente = $supabase->select(
            'asistencias', 
            '*', 
            'usuario_id=eq.' . $usuario['id'] . '&fecha=eq.' . $fecha_hoy
        );

        if (!empty($asistencia_existente)) {
            $hora_existente = new DateTime($asistencia_existente[0]['hora_llegada']);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Ya registraste tu asistencia hoy a las ' . $hora_existente->format('H:i:s')
            ]);
            exit;
        }

        // Registrar asistencia
        $nueva_asistencia = [
            'usuario_id' => $usuario['id'],
            'estatus' => 'Presente',
            'fecha' => $fecha_hoy,
            'hora_llegada' => $fecha_hora->format('Y-m-d H:i:s')
        ];

        $resultado = $supabase->insert('asistencias', $nueva_asistencia);

        echo json_encode([
            'success' => true,
            'mensaje' => '¡Asistencia registrada exitosamente!',
            'datos' => [
                'usuario' => $usuario['nombre'] . ' ' . $usuario['apellido'],
                'ocupacion' => $usuario['ocupacion'],
                'hora' => $fecha_hora->format('H:i:s'),
                'fecha' => $fecha_hoy
            ]
        ]);

    } catch (Exception $e) {
        error_log('Error en registrar_asistencia: ' . $e->getMessage());
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error interno del servidor'
        ]);
    }
}

// obtener_asistencias.php - Versión mejorada
function obtenerAsistencias() {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Content-Type');

    try {
        $supabase = new SupabaseClient();
        
        // Validar fecha
        $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
        
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            throw new Exception('Formato de fecha no válido');
        }

        // Obtener asistencias con join
        $asistencias = $supabase->select(
            'asistencias', 
            'id,hora_llegada,estatus,fecha,usuarios(nombre,apellido,ocupacion)',
            'fecha=eq.' . $fecha . '&order=hora_llegada.desc'
        );

        if (empty($asistencias)) {
            echo json_encode([]);
            exit;
        }

        // Formatear datos
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

        echo json_encode($resultado);

    } catch (Exception $e) {
        error_log('Error en obtener_asistencias: ' . $e->getMessage());
        echo json_encode([
            'error' => 'Error al obtener datos'
        ]);
    }
}

// test.php - Versión mejorada con diagnósticos
function testConnection() {
    header('Content-Type: application/json');

    try {
        // Verificar extensiones PHP
        $required_extensions = ['curl', 'json'];
        $missing_extensions = [];
        
        foreach ($required_extensions as $ext) {
            if (!extension_loaded($ext)) {
                $missing_extensions[] = $ext;
            }
        }
        
        if (!empty($missing_extensions)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Extensiones PHP faltantes: ' . implode(', ', $missing_extensions)
            ]);
            exit;
        }

        $supabase = new SupabaseClient();
        
        // Test básico de conexión
        if (!$supabase->testConnection()) {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se pudo conectar a Supabase'
            ]);
            exit;
        }

        // Obtener estadísticas
        $usuarios = $supabase->select('usuarios', '*');
        $asistencias = $supabase->select('asistencias', '*');
        
        echo json_encode([
            'status' => 'success',
            'message' => '✅ Conexión exitosa',
            'data' => [
                'total_usuarios' => count($usuarios),
                'total_asistencias' => count($asistencias),
                'php_version' => PHP_VERSION,
                'extensions' => $required_extensions,
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ]);
        
    } catch (Exception $e) {
        error_log('Error en test: ' . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

// Router principal
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
$script_name = basename($_SERVER['SCRIPT_NAME'] ?? '');

// Determinar qué función ejecutar basado en el archivo solicitado
switch ($script_name) {
    case 'registrar_asistencia.php':
        registrarAsistencia();
        break;
    case 'obtener_asistencias.php':
        obtenerAsistencias();
        break;
    case 'test.php':
        testConnection();
        break;
    default:
        // Para desarrollo local
        if (strpos($request_uri, 'registrar_asistencia') !== false) {
            registrarAsistencia();
        } elseif (strpos($request_uri, 'obtener_asistencias') !== false) {
            obtenerAsistencias();
        } elseif (strpos($request_uri, 'test') !== false) {
            testConnection();
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Endpoint no encontrado']);
        }
}