<?php
// config.php - Configuración de Supabase para producción y desarrollo

class SupabaseClient {
    private $url;
    private $key;
    private $headers;

    public function __construct() {
        // Usar variables de entorno en producción, valores por defecto en desarrollo
        $this->url = $this->getEnvVariable('SUPABASE_URL', 'https://ftrfqvqaaandvmvhdirk.supabase.co');
        $this->key = $this->getEnvVariable('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ0cmZxdnFhYWFuZHZtdmhkaXJrIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDgyMTE0ODIsImV4cCI6MjA2Mzc4NzQ4Mn0.92EsJxnyxOgzuQuZV_BiaybWUelpxbQCZ-vyLFLPs_c');
        
        $this->headers = [
            'Content-Type: application/json',
            'apikey: ' . $this->key,
            'Authorization: Bearer ' . $this->key,
            'Prefer: return=representation'
        ];
    }

    /**
     * Obtener variable de entorno con fallback
     */
    private function getEnvVariable($name, $default = null) {
        // Intentar getenv() primero
        $value = getenv($name);
        if ($value !== false) {
            return $value;
        }
        
        // Intentar $_ENV
        if (isset($_ENV[$name])) {
            return $_ENV[$name];
        }
        
        // Intentar $_SERVER
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }
        
        return $default;
    }

    public function select($table, $columns = '*', $conditions = null) {
        $url = $this->url . '/rest/v1/' . $table . '?select=' . $columns;
        
        if ($conditions) {
            $url .= '&' . $conditions;
        }

        return $this->makeRequest('GET', $url);
    }

    public function insert($table, $data) {
        $url = $this->url . '/rest/v1/' . $table;
        return $this->makeRequest('POST', $url, $data);
    }

    public function update($table, $data, $conditions) {
        $url = $this->url . '/rest/v1/' . $table . '?' . $conditions;
        return $this->makeRequest('PATCH', $url, $data);
    }

    public function delete($table, $conditions) {
        $url = $this->url . '/rest/v1/' . $table . '?' . $conditions;
        return $this->makeRequest('DELETE', $url);
    }

    private function makeRequest($method, $url, $data = null) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
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
        
        if ($httpCode >= 200 && $httpCode < 300) {
            $decoded = json_decode($response, true);
            return $decoded !== null ? $decoded : [];
        } else {
            $errorResponse = json_decode($response, true);
            $errorMessage = isset($errorResponse['message']) ? $errorResponse['message'] : $response;
            throw new Exception('Error HTTP ' . $httpCode . ': ' . $errorMessage);
        }
    }

    /**
     * Método para verificar la conexión
     */
    public function testConnection() {
        try {
            $this->select('usuarios', 'count');
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>