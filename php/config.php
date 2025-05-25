<?php
// config.php - Configuración de Supabase

class SupabaseClient {
    private $url;
    private $key;
    private $headers;

    public function __construct() {
        // Reemplaza estos valores con tus credenciales de Supabase
        $this->url = 'https://ftrfqvqaaandvmvhdirk.supabase.co'; // Tu URL de Supabase
        $this->key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ0cmZxdnFhYWFuZHZtdmhkaXJrIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDgyMTE0ODIsImV4cCI6MjA2Mzc4NzQ4Mn0.92EsJxnyxOgzuQuZV_BiaybWUelpxbQCZ-vyLFLPs_c'; // Tu clave anónima de Supabase
        
        $this->headers = [
            'Content-Type: application/json',
            'apikey: ' . $this->key,
            'Authorization: Bearer ' . $this->key
        ];
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
        
        if ($data && ($method === 'POST' || $method === 'PATCH')) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true);
        } else {
            throw new Exception('Error en la petición: ' . $response);
        }
    }
}
?>