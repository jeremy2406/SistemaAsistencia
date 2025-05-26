<?php include 'componentes/header.php'; ?>
<?php include 'componentes/nav.php'; ?>

<div class="container mx-auto p-4">
  <div class="max-w-4xl mx-auto">
    <h2 class="text-3xl font-bold mb-6 text-center">Generador de Códigos QR</h2>
    
    <!-- Tabs -->
    <div class="flex mb-6 border-b">
      <button id="tabGenerar" class="px-4 py-2 font-semibold text-blue-600 border-b-2 border-blue-600">
        Generar QR Individual
      </button>
      <button id="tabMasivos" class="px-4 py-2 font-semibold text-gray-500 hover:text-blue-600">
        Generar QR Masivos
      </button>
      <button id="tabUsuarios" class="px-4 py-2 font-semibold text-gray-500 hover:text-blue-600">
        Ver Usuarios
      </button>
    </div>

    <!-- Tab: Generar QR Individual -->
    <div id="contentGenerar" class="tab-content">
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-semibold mb-4">Crear Usuario y Generar QR</h3>
        
        <form id="formUsuario" class="grid md:grid-cols-2 gap-4 mb-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
            <input type="text" id="nombre" class="w-full border border-gray-300 rounded px-3 py-2" required>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Apellido</label>
            <input type="text" id="apellido" class="w-full border border-gray-300 rounded px-3 py-2" required>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Ocupación</label>
            <input type="text" id="ocupacion" class="w-full border border-gray-300 rounded px-3 py-2" required>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Código QR (opcional)</label>
            <input type="text" id="codigoQR" class="w-full border border-gray-300 rounded px-3 py-2" 
                   placeholder="Se generará automáticamente si está vacío">
          </div>
          
          <div class="md:col-span-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
              Crear Usuario y Generar QR
            </button>
          </div>
        </form>

        <!-- Resultado del QR generado -->
        <div id="resultadoQR" class="hidden">
          <div class="border-t pt-6">
            <h4 class="text-lg font-semibold mb-4">QR Generado</h4>
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <div id="qrContainer" class="text-center"></div>
                <button id="descargarQR" class="mt-4 w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                  Descargar QR
                </button>
              </div>
              <div>
                <h5 class="font-semibold mb-2">Información del Usuario:</h5>
                <div id="infoUsuario" class="bg-gray-50 p-4 rounded text-sm"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab: Generar QR Masivos -->
    <div id="contentMasivos" class="tab-content hidden">
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-semibold mb-4">Generar QRs Masivos</h3>
        
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Subir archivo CSV (nombre,apellido,ocupacion)
          </label>
          <input type="file" id="archivoCSV" accept=".csv" 
                 class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        
        <div class="mb-4">
          <button id="procesarCSV" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            Procesar CSV y Generar QRs
          </button>
        </div>

        <!-- Resultado de QRs masivos -->
        <div id="resultadoMasivo" class="hidden">
          <div class="border-t pt-6">
            <div class="flex justify-between items-center mb-4">
              <h4 class="text-lg font-semibold">QRs Generados</h4>
              <button id="descargarTodos" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Descargar Todos (ZIP)
              </button>
            </div>
            <div id="qrsMasivos" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab: Ver Usuarios -->
    <div id="contentUsuarios" class="tab-content hidden">
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold">Usuarios Registrados</h3>
          <button id="actualizarUsuarios" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Actualizar Lista
          </button>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apellido</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ocupación</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código QR</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
              </tr>
            </thead>
            <tbody id="tablaUsuarios" class="divide-y divide-gray-200">
              <!-- Se llenará con JavaScript -->
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div id="loading" class="hidden text-center py-8">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      <p class="mt-2">Procesando...</p>
    </div>

    <!-- Mensajes -->
    <div id="mensaje" class="hidden mt-4 p-4 rounded"></div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>

<script>
// Variables globales
let usuariosData = [];
let qrLibraryLoaded = false;

// Verificar si la librería QRCode está cargada
function checkQRLibrary() {
  return new Promise((resolve) => {
    if (typeof QRCode !== 'undefined') {
      qrLibraryLoaded = true;
      resolve(true);
    } else {
      // Intentar cargar la librería manualmente si no está disponible
      const script = document.createElement('script');
      script.src = 'https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js';
      script.onload = () => {
        qrLibraryLoaded = true;
        resolve(true);
      };
      script.onerror = () => resolve(false);
      document.head.appendChild(script);
    }
  });
}

// Funciones de UI
function mostrarTab(tabName) {
  // Ocultar todos los contenidos
  document.querySelectorAll('.tab-content').forEach(content => {
    content.classList.add('hidden');
  });
  
  // Quitar estilos activos de todos los tabs
  document.querySelectorAll('[id^="tab"]').forEach(tab => {
    tab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
    tab.classList.add('text-gray-500');
  });
  
  // Mostrar contenido activo
  document.getElementById('content' + tabName).classList.remove('hidden');
  
  // Activar tab
  const activeTab = document.getElementById('tab' + tabName);
  activeTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
  activeTab.classList.remove('text-gray-500');
}

function mostrarMensaje(texto, tipo = 'info') {
  const mensaje = document.getElementById('mensaje');
  mensaje.className = `mt-4 p-4 rounded ${
    tipo === 'success' ? 'bg-green-100 text-green-800' :
    tipo === 'error' ? 'bg-red-100 text-red-800' :
    'bg-blue-100 text-blue-800'
  }`;
  mensaje.textContent = texto;
  mensaje.classList.remove('hidden');
  
  // Auto-ocultar después de 5 segundos
  setTimeout(() => {
    mensaje.classList.add('hidden');
  }, 5000);
}

function toggleLoading(show) {
  const loading = document.getElementById('loading');
  if (show) {
    loading.classList.remove('hidden');
  } else {
    loading.classList.add('hidden');
  }
}

// Función para generar código QR único
function generarCodigoUnico() {
  const timestamp = Date.now();
  const random = Math.random().toString(36).substring(2);
  return `QR_${timestamp}_${random}`;
}

// Función CORREGIDA para generar QR visual
async function generarQRVisual(codigo, contenedor) {
  try {
    // Verificar que la librería esté disponible
    const libraryReady = await checkQRLibrary();
    if (!libraryReady) {
      throw new Error('No se pudo cargar la librería QRCode');
    }

    // Limpiar contenedor
    contenedor.innerHTML = '';
    
    // Crear canvas
    const canvas = document.createElement('canvas');
    
    // Generar QR en el canvas
    await QRCode.toCanvas(canvas, codigo, {
      width: 200,
      margin: 2,
      color: {
        dark: '#000000',
        light: '#FFFFFF'
      }
    });
    
    // Agregar canvas al contenedor
    contenedor.appendChild(canvas);
    
    // Crear un wrapper con la información para facilitar la descarga
    canvas.setAttribute('data-qr-code', codigo);
    canvas.setAttribute('data-generated', 'true');
    
    return canvas;
  } catch (error) {
    console.error('Error al generar QR con canvas:', error);
    
    // Fallback: usar API externa
    try {
      const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(codigo)}`;
      
      // Crear imagen y esperar a que cargue
      const img = await new Promise((resolve, reject) => {
        const imagen = new Image();
        imagen.crossOrigin = 'anonymous'; // Importante para poder descargar
        imagen.onload = () => resolve(imagen);
        imagen.onerror = () => reject(new Error('Error al cargar imagen QR'));
        imagen.src = qrUrl;
      });
      
      // Crear canvas desde la imagen para poder descargar
      const canvas = document.createElement('canvas');
      const ctx = canvas.getContext('2d');
      canvas.width = 200;
      canvas.height = 200;
      ctx.drawImage(img, 0, 0, 200, 200);
      
      // Limpiar contenedor y agregar canvas
      contenedor.innerHTML = '';
      contenedor.appendChild(canvas);
      
      canvas.setAttribute('data-qr-code', codigo);
      canvas.setAttribute('data-generated', 'true');
      
      return canvas;
    } catch (fallbackError) {
      console.error('Error en fallback:', fallbackError);
      throw new Error('No se pudo generar el código QR');
    }
  }
}

// Función CORREGIDA para descargar QR
function descargarElementoQR(elemento, nombreArchivo) {
  try {
    if (!elemento) {
      throw new Error('Elemento no encontrado');
    }
    
    let canvas;
    
    if (elemento.tagName === 'CANVAS') {
      canvas = elemento;
    } else if (elemento.tagName === 'IMG') {
      // Convertir imagen a canvas
      canvas = document.createElement('canvas');
      const ctx = canvas.getContext('2d');
      canvas.width = elemento.naturalWidth || 200;
      canvas.height = elemento.naturalHeight || 200;
      ctx.drawImage(elemento, 0, 0);
    } else {
      throw new Error('Tipo de elemento no soportado');
    }
    
    // Verificar que el canvas tenga contenido
    if (canvas.width === 0 || canvas.height === 0) {
      throw new Error('Canvas vacío');
    }
    
    // Generar URL de descarga
    canvas.toBlob((blob) => {
      if (!blob) {
        mostrarMensaje('Error al generar archivo de descarga', 'error');
        return;
      }
      
      const url = URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.download = nombreArchivo;
      link.href = url;
      
      // Simular click y limpiar
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      URL.revokeObjectURL(url);
      
      mostrarMensaje('QR descargado exitosamente', 'success');
    }, 'image/png');
    
  } catch (error) {
    console.error('Error al descargar:', error);
    mostrarMensaje('Error al descargar el QR: ' + error.message, 'error');
  }
}

// Función para crear usuario
async function crearUsuario(nombre, apellido, ocupacion, codigoQR) {
  try {
    const response = await fetch('./php/funciones_adicionales.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        accion: 'crear_usuario',
        nombre: nombre,
        apellido: apellido,
        ocupacion: ocupacion,
        codigo_qr: codigoQR
      })
    });
    
    const data = await response.json();
    
    if (data.error) {
      throw new Error(data.error);
    }
    
    return data;
  } catch (error) {
    console.error('Error al crear usuario:', error);
    throw error;
  }
}

// Función para obtener usuarios
async function obtenerUsuarios() {
  try {
    const response = await fetch('./php/funciones_adicionales.php?accion=usuarios');
    const data = await response.json();
    
    if (data.error) {
      throw new Error(data.error);
    }
    
    return data;
  } catch (error) {
    console.error('Error al obtener usuarios:', error);
    throw error;
  }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
  // Tabs
  document.getElementById('tabGenerar').addEventListener('click', () => mostrarTab('Generar'));
  document.getElementById('tabMasivos').addEventListener('click', () => mostrarTab('Masivos'));
  document.getElementById('tabUsuarios').addEventListener('click', () => {
    mostrarTab('Usuarios');
    cargarUsuarios();
  });

  // Formulario de usuario individual
  document.getElementById('formUsuario').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const nombre = document.getElementById('nombre').value.trim();
    const apellido = document.getElementById('apellido').value.trim();
    const ocupacion = document.getElementById('ocupacion').value.trim();
    let codigoQR = document.getElementById('codigoQR').value.trim();
    
    if (!codigoQR) {
      codigoQR = generarCodigoUnico();
    }
    
    toggleLoading(true);
    
    try {
      // Crear usuario en la base de datos
      await crearUsuario(nombre, apellido, ocupacion, codigoQR);
      
      // Generar QR visual
      const qrContainer = document.getElementById('qrContainer');
      const canvas = await generarQRVisual(codigoQR, qrContainer);
      
      // Mostrar información del usuario
      document.getElementById('infoUsuario').innerHTML = `
        <p><strong>Nombre:</strong> ${nombre} ${apellido}</p>
        <p><strong>Ocupación:</strong> ${ocupacion}</p>
        <p><strong>Código QR:</strong> ${codigoQR}</p>
        <p><strong>Fecha:</strong> ${new Date().toLocaleDateString()}</p>
      `;
      
      // Configurar botón de descarga
      const descargarBtn = document.getElementById('descargarQR');
      descargarBtn.onclick = () => {
        descargarElementoQR(canvas, `QR_${nombre}_${apellido}.png`);
      };
      
      // Mostrar resultado
      document.getElementById('resultadoQR').classList.remove('hidden');
      
      // Limpiar formulario
      document.getElementById('formUsuario').reset();
      
      mostrarMensaje('Usuario creado y QR generado exitosamente', 'success');
      
    } catch (error) {
      mostrarMensaje('Error: ' + error.message, 'error');
    } finally {
      toggleLoading(false);
    }
  });

  // Procesar CSV
  document.getElementById('procesarCSV').addEventListener('click', async () => {
    const archivo = document.getElementById('archivoCSV').files[0];
    
    if (!archivo) {
      mostrarMensaje('Por favor selecciona un archivo CSV', 'error');
      return;
    }
    
    toggleLoading(true);
    
    try {
      const texto = await archivo.text();
      const lineas = texto.split('\n').filter(linea => linea.trim());
      const qrsMasivos = document.getElementById('qrsMasivos');
      qrsMasivos.innerHTML = '';
      
      // Verificar que JSZip esté disponible
      if (typeof JSZip === 'undefined') {
        throw new Error('JSZip no está disponible');
      }
      
      const zip = new JSZip();
      const qrElements = []; // Almacenar elementos para descarga
      
      for (let i = 1; i < lineas.length; i++) { // Saltar header
        const [nombre, apellido, ocupacion] = lineas[i].split(',').map(s => s.trim());
        
        if (nombre && apellido && ocupacion) {
          const codigoQR = generarCodigoUnico();
          
          try {
            // Crear usuario
            await crearUsuario(nombre, apellido, ocupacion, codigoQR);
            
            // Crear contenedor para QR
            const qrDiv = document.createElement('div');
            qrDiv.className = 'text-center p-4 border rounded';
            qrDiv.innerHTML = `<p class="text-sm font-semibold mb-2">${nombre} ${apellido}</p>`;
            
            // Generar QR
            const canvas = await generarQRVisual(codigoQR, qrDiv);
            
            // Almacenar para descarga
            qrElements.push({
              canvas: canvas,
              nombre: `QR_${nombre}_${apellido}.png`
            });
            
            qrsMasivos.appendChild(qrDiv);
            
          } catch (error) {
            console.error(`Error procesando ${nombre} ${apellido}:`, error);
          }
        }
      }
      
      // Configurar descarga masiva
      document.getElementById('descargarTodos').onclick = async () => {
        try {
          // Agregar cada QR al ZIP
          for (const qrElement of qrElements) {
            await new Promise((resolve) => {
              qrElement.canvas.toBlob((blob) => {
                if (blob) {
                  zip.file(qrElement.nombre, blob);
                }
                resolve();
              }, 'image/png');
            });
          }
          
          // Generar y descargar ZIP
          const zipBlob = await zip.generateAsync({type: 'blob'});
          const link = document.createElement('a');
          link.href = URL.createObjectURL(zipBlob);
          link.download = 'codigos_qr.zip';
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
          URL.revokeObjectURL(link.href);
          
          mostrarMensaje('ZIP descargado exitosamente', 'success');
        } catch (error) {
          mostrarMensaje('Error al crear ZIP: ' + error.message, 'error');
        }
      };
      
      document.getElementById('resultadoMasivo').classList.remove('hidden');
      mostrarMensaje('QRs generados exitosamente', 'success');
      
    } catch (error) {
      mostrarMensaje('Error al procesar archivo: ' + error.message, 'error');
    } finally {
      toggleLoading(false);
    }
  });

  // Actualizar usuarios
  document.getElementById('actualizarUsuarios').addEventListener('click', cargarUsuarios);

  // Inicializar
  mostrarTab('Generar');
  checkQRLibrary();
});

// Cargar usuarios
async function cargarUsuarios() {
  toggleLoading(true);
  
  try {
    usuariosData = await obtenerUsuarios();
    renderizarTablaUsuarios();
  } catch (error) {
    mostrarMensaje('Error al cargar usuarios: ' + error.message, 'error');
  } finally {
    toggleLoading(false);
  }
}

function renderizarTablaUsuarios() {
  const tbody = document.getElementById('tablaUsuarios');
  
  if (!usuariosData || usuariosData.length === 0) {
    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4">No hay usuarios registrados</td></tr>';
    return;
  }
  
  tbody.innerHTML = usuariosData.map(usuario => `
    <tr>
      <td class="px-6 py-4 text-sm">${usuario.nombre}</td>
      <td class="px-6 py-4 text-sm">${usuario.apellido}</td>
      <td class="px-6 py-4 text-sm">${usuario.ocupacion}</td>
      <td class="px-6 py-4 text-sm font-mono text-xs">${usuario.codigo_qr}</td>
      <td class="px-6 py-4 text-sm">
        <button onclick="generarQRExistente('${usuario.codigo_qr}', '${usuario.nombre}', '${usuario.apellido}')" 
                class="text-blue-600 hover:text-blue-800 text-sm">
          Ver QR
        </button>
      </td>
    </tr>
  `).join('');
}

// Función CORREGIDA para generar QR existente
async function generarQRExistente(codigoQR, nombre, apellido) {
  try {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
      <div class="bg-white p-6 rounded-lg max-w-md w-full m-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">QR para ${nombre} ${apellido}</h3>
          <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700 text-xl font-bold">✕</button>
        </div>
        <div id="qrModal" class="text-center mb-4">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
          <p class="mt-2 text-sm text-gray-600">Generando QR...</p>
        </div>
        <button id="descargarModal" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" disabled>
          Descargar QR
        </button>
      </div>
    `;
    
    document.body.appendChild(modal);
    
    try {
      const qrElement = await generarQRVisual(codigoQR, document.getElementById('qrModal'));
      
      const descargarBtn = document.getElementById('descargarModal');
      descargarBtn.disabled = false;
      descargarBtn.onclick = () => {
        descargarElementoQR(qrElement, `QR_${nombre}_${apellido}.png`);
      };
    } catch (qrError) {
      document.getElementById('qrModal').innerHTML = `
        <div class="text-red-600">
          <p>Error al generar QR</p>
          <p class="text-sm">${qrError.message}</p>
        </div>
      `;
    }
    
  } catch (error) {
    console.error('Error al crear modal:', error);
    mostrarMensaje('Error al mostrar QR: ' + error.message, 'error');
  }
}

// Hacer la función disponible globalmente
window.generarQRExistente = generarQRExistente;
</script>

<?php include 'componentes/footer.php'; ?>