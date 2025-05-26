<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico del Sistema - Asistencia QR</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .status-ok { color: #10b981; }
        .status-error { color: #ef4444; }
        .status-warning { color: #f59e0b; }
    </style>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-center mb-8 text-blue-600">
                🔧 Diagnóstico del Sistema QR
            </h1>
            
            <div id="loading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2">Ejecutando diagnósticos...</p>
            </div>

            <div id="results" class="hidden space-y-6">
                <!-- Los resultados se mostrarán aquí -->
            </div>

            <div class="mt-8 text-center">
                <button onclick="ejecutarDiagnosticos()" 
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    🔄 Ejecutar Diagnósticos Nuevamente
                </button>
            </div>
        </div>
    </div>

    <script>
        async function ejecutarDiagnosticos() {
            const loading = document.getElementById('loading');
            const results = document.getElementById('results');
            
            loading.classList.remove('hidden');
            results.classList.add('hidden');
            results.innerHTML = '';

            const diagnosticos = [
                {
                    nombre: 'Información del Servidor',
                    funcion: diagnosticarServidor
                },
                {
                    nombre: 'Conexión con Supabase',
                    funcion: diagnosticarSupabase
                },
                {
                    nombre: 'Archivos del Sistema',
                    funcion: diagnosticarArchivos
                },
                {
                    nombre: 'Permisos y Configuración',
                    funcion: diagnosticarPermisos
                }
            ];

            for (const diagnostico of diagnosticos) {
                try {
                    const resultado = await diagnostico.funcion();
                    mostrarResultado(diagnostico.nombre, resultado);
                } catch (error) {
                    mostrarResultado(diagnostico.nombre, {
                        status: 'error',
                        message: 'Error: ' + error.message
                    });
                }
            }

            loading.classList.add('hidden');
            results.classList.remove('hidden');
        }

        async function diagnosticarServidor() {
            try {
                const response = await fetch('?action=server_info');
                if (!response.ok) throw new Error('Error HTTP ' + response.status);
                
                return {
                    status: 'success',
                    message: 'Información del servidor obtenida',
                    details: await response.json()
                };
            } catch (error) {
                return {
                    status: 'error',
                    message: 'Error al obtener información del servidor: ' + error.message
                };
            }
        }

        async function diagnosticarSupabase() {
            try {
                const response = await fetch('./php/test.php');
                if (!response.ok) throw new Error('Error HTTP ' + response.status);
                
                const data = await response.json();
                return data;
            } catch (error) {
                return {
                    status: 'error',
                    message: 'Error de conexión con Supabase: ' + error.message
                };
            }
        }

        async function diagnosticarArchivos() {
            const archivos = [
                './php/config.php',
                './php/registrar_asistencia.php',
                './php/obtener_asistencias.php',
                './php/test.php'
            ];

            const resultados = [];
            
            for (const archivo of archivos) {
                try {
                    const response = await fetch(archivo, { method: 'HEAD' });
                    resultados.push({
                        archivo: archivo,
                        status: response.ok ? 'ok' : 'error',
                        codigo: response.status
                    });
                } catch (error) {
                    resultados.push({
                        archivo: archivo,
                        status: 'error',
                        error: error.message
                    });
                }
            }

            return {
                status: 'info',
                message: 'Verificación de archivos completada',
                details: resultados
            };
        }

        async function diagnosticarPermisos() {
            try {
                // Intentar hacer una petición POST para probar permisos
                const response = await fetch('./php/registrar_asistencia.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ codigo_qr: 'TEST' })
                });

                const contentType = response.headers.get('content-type');
                
                return {
                    status: response.ok ? 'success' : 'warning',
                    message: `Respuesta HTTP ${response.status}`,
                    details: {
                        status: response.status,
                        contentType: contentType,
                        canMakePOST: true
                    }
                };
            } catch (error) {
                return {
                    status: 'error',
                    message: 'Error en permisos: ' + error.message
                };
            }
        }

        function mostrarResultado(nombre, resultado) {
            const results = document.getElementById('results');
            
            const statusClass = resultado.status === 'success' ? 'status-ok' : 
                               resultado.status === 'error' ? 'status-error' : 'status-warning';
            
            const icon = resultado.status === 'success' ? '✅' : 
                        resultado.status === 'error' ? '❌' : '⚠️';

            const html = `
                <div class="border rounded-lg p-4 ${resultado.status === 'error' ? 'bg-red-50 border-red-200' : 
                                                   resultado.status === 'success' ? 'bg-green-50 border-green-200' : 
                                                   'bg-yellow-50 border-yellow-200'}">
                    <h3 class="text-lg font-semibold mb-2 ${statusClass}">
                        ${icon} ${nombre}
                    </h3>
                    <p class="text-gray-700">${resultado.message}</p>
                    ${resultado.details ? `
                        <details class="mt-2">
                            <summary class="cursor-pointer text-blue-600 hover:underline">Ver detalles</summary>
                            <pre class="mt-2 p-2 bg-gray-100 rounded text-sm overflow-x-auto">${JSON.stringify(resultado.details, null, 2)}</pre>
                        </details>
                    ` : ''}
                </div>
            `;
            
            results.innerHTML += html;
        }

        // Ejecutar diagnósticos al cargar la página
        document.addEventListener('DOMContentLoaded', ejecutarDiagnosticos);
    </script>

    <?php
    // Backend PHP para diagnósticos adicionales
    if (isset($_GET['action']) && $_GET['action'] === 'server_info') {
        header('Content-Type: application/json');
        
        $info = [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'No definido',
            'script_name' => $_SERVER['SCRIPT_NAME'] ?? 'No definido',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'No definido',
            'extensions' => [
                'curl' => extension_loaded('curl'),
                'json' => extension_loaded('json'),
                'openssl' => extension_loaded('openssl')
            ],
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'error_reporting' => error_reporting(),
            'display_errors' => ini_get('display_errors'),
            'log_errors' => ini_get('log_errors'),
            'timezone' => date_default_timezone_get(),
            'current_time' => date('Y-m-d H:i:s')
        ];
        
        echo json_encode($info);
        exit;
    }
    ?>
</body>
</html>