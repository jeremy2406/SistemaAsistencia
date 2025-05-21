<?php
// Configuración de la conexión a Supabase
class SupabaseConfig {
    // Constantes de configuración
    private static $SUPABASE_URL = 'https://llzrabdkomezsjyihkhz.supabase.co';
    private static $SUPABASE_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImxsenJhYmRrb21lenNqeWloa2h6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDc4NDM0NzcsImV4cCI6MjA2MzQxOTQ3N30.0J9YBaRBO63YMHKKCP_6WHwjPP-_cmL5tA1TBnyk4OM';
    
    // Obtener URL de Supabase
    public static function getUrl() {
        return self::$SUPABASE_URL;
    }
    
    // Obtener clave de API de Supabase
    public static function getKey() {
        return self::$SUPABASE_KEY;
    }
    
    // Función para realizar peticiones a la API de Supabase
    public static function request($endpoint, $method = 'GET', $data = null) {
        $url = self::$SUPABASE_URL . $endpoint;
        
        $headers = [
            'Content-Type: application/json',
            'apikey: ' . self::$SUPABASE_KEY,
            'Authorization: Bearer ' . self::$SUPABASE_KEY
        ];
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ]);
        
        if ($data && ($method === 'POST' || $method === 'PATCH' || $method === 'PUT')) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($curl);
        $error = curl_error($curl);
        
        curl_close($curl);
        
        if ($error) {
            return ['error' => $error];
        }
        
        return json_decode($response, true);
    }
}
?>