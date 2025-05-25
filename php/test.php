<?php
// test_connection.php - Script para probar la conexión con Supabase

require_once 'config.php';

header('Content-Type: application/json');

try {
    $supabase = new SupabaseClient();
    
    echo json_encode([
        'status' => 'testing',
        'message' => 'Probando conexión con Supabase...'
    ]);
    
    // Probar conexión básica
    $usuarios = $supabase->select('usuarios', 'count');
    
    if ($usuarios !== null) {
        $totalUsuarios = count($supabase->select('usuarios', '*'));
        $totalAsistencias = count($supabase->select('asistencias', '*'));
        
        echo json_encode([
            'status' => 'success',
            'message' => '✅ Conexión exitosa con Supabase',
            'data' => [
                'total_usuarios' => $totalUsuarios,
                'total_asistencias' => $totalAsistencias,
                'tablas_detectadas' => ['usuarios', 'asistencias']
            ]
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ Error: No se pudieron obtener datos de las tablas'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => '❌ Error de conexión: ' . $e->getMessage(),
        'suggestions' => [
            'Verifica que las credenciales de Supabase sean correctas',
            'Asegúrate de que las tablas estén creadas',
            'Verifica que las políticas RLS estén configuradas correctamente'
        ]
    ]);
}
?>