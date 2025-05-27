<?php include 'Componentes/Header.php'; ?>
<?php include 'Componentes/Nav.php'; ?>
<div class="container mx-auto px-4 py-20 text-center">
  <h2 class="text-3xl font-bold mb-4">Escanea tu código QR</h2>
  <p class="mb-6">Utiliza la cámara para escanear tu código QR único y registrar tu asistencia.</p>
  
  <!-- Área del escáner -->
  <div id="reader" class="mx-auto w-full max-w-md h-80 border border-gray-300 rounded-md shadow-md mb-4"></div>
  
  <!-- Botones de control -->
  <div class="mb-4 space-x-4">
    <button id="startBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50">
      Iniciar Cámara
    </button>
    <button id="stopBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 disabled:opacity-50" disabled>
      Detener Cámara
    </button>
  </div>
  
  <!-- Área de resultados -->
  <div id="resultado" class="mt-4 p-4 rounded-md hidden"></div>
  
  <!-- Loading spinner -->
  <div id="loading" class="hidden">
    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
    <p class="mt-2">Procesando...</p>
  </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.7/html5-qrcode.min.js"></script>
<script>
let qrReader = null;
let isScanning = false;

const startBtn = document.getElementById('startBtn');
const stopBtn = document.getElementById('stopBtn');
const resultadoDiv = document.getElementById('resultado');
const loadingDiv = document.getElementById('loading');

// Función para mostrar mensajes
function mostrarMensaje(mensaje, tipo = 'info') {
  resultadoDiv.className = `mt-4 p-4 rounded-md ${tipo === 'success' ? 'bg-green-100 text-green-800' : 
                                                 tipo === 'error' ? 'bg-red-100 text-red-800' : 
                                                 'bg-blue-100 text-blue-800'}`;
  resultadoDiv.textContent = mensaje;
  resultadoDiv.classList.remove('hidden');
}

// Función para mostrar/ocultar loading
function toggleLoading(show) {
  if (show) {
    loadingDiv.classList.remove('hidden');
  } else {
    loadingDiv.classList.add('hidden');
  }
}

// Función para iniciar el escáner
async function iniciarEscaner() {
  try {
    if (!qrReader) {
      qrReader = new Html5Qrcode("reader");
    }
    
    const config = {
      fps: 10,
      qrbox: { width: 250, height: 250 },
      aspectRatio: 1.0
    };
    
    await qrReader.start(
      { facingMode: "environment" },
      config,
      async (qrCodeMessage) => {
        // Detener el escáner inmediatamente para evitar múltiples lecturas
        await detenerEscaner();
        
        // Procesar el código QR
        await procesarCodigoQR(qrCodeMessage);
      },
      (errorMessage) => {
        // Manejar errores de escaneo (opcional)
        console.log(`Error de escaneo: ${errorMessage}`);
      }
    );
    
    isScanning = true;
    startBtn.disabled = true;
    stopBtn.disabled = false;
    mostrarMensaje('Cámara iniciada. Apunta al código QR para escanearlo.', 'info');
    
  } catch (error) {
    console.error('Error al iniciar el escáner:', error);
    mostrarMensaje('Error al acceder a la cámara. Verifica los permisos.', 'error');
  }
}

// Función para detener el escáner
async function detenerEscaner() {
  try {
    if (qrReader && isScanning) {
      await qrReader.stop();
      isScanning = false;
      startBtn.disabled = false;
      stopBtn.disabled = true;
    }
  } catch (error) {
    console.error('Error al detener el escáner:', error);
  }
}

// Función para procesar el código QR escaneado
async function procesarCodigoQR(codigoQR) {
  toggleLoading(true);
  
  try {
    const response = await fetch('./php/registrar_asistencia.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ codigo_qr: codigoQR })
    });
    
    const data = await response.json();
    
    if (data.success) {
      mostrarMensaje(`✅ ${data.mensaje}`, 'success');
      
      // Mostrar información adicional si está disponible
      if (data.datos) {
        setTimeout(() => {
          mostrarMensaje(
            `Bienvenido/a ${data.datos.usuario} (${data.datos.ocupacion})\nHora de registro: ${data.datos.hora}`,
            'success'
          );
        }, 2000);
      }
    } else {
      mostrarMensaje(`❌ ${data.mensaje}`, 'error');
    }
    
  } catch (error) {
    console.error('Error al procesar código QR:', error);
    mostrarMensaje('Error de conexión. Intenta nuevamente.', 'error');
  } finally {
    toggleLoading(false);
  }
}

// Event listeners
startBtn.addEventListener('click', iniciarEscaner);
stopBtn.addEventListener('click', detenerEscaner);

// Limpiar al salir de la página
window.addEventListener('beforeunload', async () => {
  if (qrReader && isScanning) {
    await detenerEscaner();
  }
});

// Auto-iniciar el escáner cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
  // Dar un pequeño delay para que se cargue completamente la página
  setTimeout(() => {
    iniciarEscaner();
  }, 500);
});
</script>

<?php include 'Componentes/Footer.php'; ?>