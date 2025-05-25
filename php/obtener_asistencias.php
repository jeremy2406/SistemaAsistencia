<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php';

try {
    $supabase = new SupabaseClient();
    
    // Obtener fecha actual o fecha específica si se proporciona
    $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
    
    // Obtener asistencias con datos de usuario usando una consulta con join
    $asistencias = $supabase->select(
        'asistencias', 
        'id,hora_llegada,estatus,fecha,usuarios(nombre,apellido,ocupacion)',
        'fecha=eq.' . $fecha . '&order=hora_llegada.desc'
    );

    // Formatear los datos para la respuesta
    $resultado = array_map(function($asistencia) {
        return [
            'id' => $asistencia['id'],
            'hora_llegada' => $asistencia['hora_llegada'],
            'estatus' => $asistencia['estatus'],
            'fecha' => $asistencia['fecha'],
            'usuarios' => $asistencia['usuarios']
        ];
    }, $asistencias);

    echo json_encode($resultado);

} catch (Exception $e) {
    echo json_encode([
        'error' => 'Error al obtener asistencias: ' . $e->getMessage()
    ]);
}
?>