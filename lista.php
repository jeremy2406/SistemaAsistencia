<?php include 'componentes/header.php'; ?>
<?php include 'componentes/nav.php'; ?>

<div class="container mx-auto p-4">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Lista de Asistencia</h2>
    
    <!-- Controles de fecha y actualización -->
    <div class="flex items-center space-x-4">
      <input type="date" id="fechaSelector" value="<?= date('Y-m-d') ?>" 
             class="border border-gray-300 rounded px-3 py-2">
      <button onclick="cargarAsistencias()" 
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Actualizar
      </button>
    </div>
  </div>

  <!-- Estadísticas resumidas -->
  <div id="estadisticas" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <!-- Se llenarán con JavaScript -->
  </div>

  <!-- Loading spinner -->
  <div id="loading" class="text-center py-8 hidden">
    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
    <p class="mt-2">Cargando asistencias...</p>
  </div>

  <!-- Mensaje de error -->
  <div id="error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    <span id="errorMessage"></span>
  </div>

  <!-- Tabla de asistencias -->
  <div class="overflow-x-auto shadow rounded-lg">
    <table class="min-w-full divide-y divide-gray-200 bg-white">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Nombre
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Apellido
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Ocupación
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Hora de llegada
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Estatus
          </th>
        </tr>
      </thead>
      <tbody id="tablaAsistencia" class="bg-white divide-y divide-gray-200">
        <!-- Los datos se cargarán aquí -->
      </tbody>
    </table>
  </div>

  <!-- Mensaje cuando no hay datos -->
  <div id="noData" class="text-center py-8 hidden">
    <div class="text-gray-400 text-lg">
      📋 No hay registros de asistencia para la fecha seleccionada
    </div>
  </div>
</div>

<script>
// Variables globales
let asistenciasData = [];

// Función para mostrar/ocultar loading
function toggleLoading(show) {
  const loading = document.getElementById('loading');
  if (show) {
    loading.classList.remove('hidden');
    document.getElementById('error').classList.add('hidden');
    document.getElementById('noData').classList.add('hidden');
  } else {
    loading.classList.add('hidden');
  }
}

// Función para mostrar errores
function mostrarError(mensaje) {
  const errorDiv = document.getElementById('error');
  const errorMessage = document.getElementById('errorMessage');
  errorMessage.textContent = mensaje;
  errorDiv.classList.remove('hidden');
}

// Función para cargar estadísticas
async function cargarEstadisticas(fecha) {
  try {
    const response = await fetch(`./php/funciones_adicionales.php?accion=estadisticas&fecha=${fecha}`);
    const data = await response.json();
    
    if (data.error) {
      console.error('Error al cargar estadísticas:', data.error);
      return;
    }

    const estadisticasDiv = document.getElementById('estadisticas');
    estadisticasDiv.innerHTML = `
      <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-2xl font-bold text-blue-600">${data.total_usuarios}</div>
        <div class="text-sm text-gray-600">Total Usuarios</div>
      </div>
      <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-2xl font-bold text-green-600">${data.total_asistencias}</div>
        <div class="text-sm text-gray-600">Presentes</div>
      </div>
      <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-2xl font-bold text-red-600">${data.ausentes}</div>
        <div class="text-sm text-gray-600">Ausentes</div>
      </div>
      <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-2xl font-bold text-purple-600">${data.porcentaje_asistencia}%</div>
        <div class="text-sm text-gray-600">% Asistencia</div>
      </div>
    `;
  } catch (error) {
    console.error('Error al cargar estadísticas:', error);
  }
}

// Función para cargar asistencias
async function cargarAsistencias() {
  const fecha = document.getElementById('fechaSelector').value;
  toggleLoading(true);

  try {
    const response = await fetch(`./php/obtener_asistencias.php?fecha=${fecha}`);
    const data = await response.json();

    if (data.error) {
      mostrarError(data.error);
      return;
    }

    asistenciasData = data;
    renderizarTabla(data);
    await cargarEstadisticas(fecha);

  } catch (error) {
    console.error('Error al cargar asistencias:', error);
    mostrarError('Error de conexión al cargar los datos');
  } finally {
    toggleLoading(false);
  }
}

// Función para renderizar la tabla
function renderizarTabla(asistencias) {
  const tbody = document.getElementById('tablaAsistencia');
  const noDataDiv = document.getElementById('noData');

  if (!asistencias || asistencias.length === 0) {
    tbody.innerHTML = '';
    noDataDiv.classList.remove('hidden');
    return;
  }

  noDataDiv.classList.add('hidden');

  tbody.innerHTML = asistencias.map(item => {
    // Verificar que el objeto usuarios existe
    const usuario = item.usuarios || {};
    const nombre = usuario.nombre || 'N/A';
    const apellido = usuario.apellido || 'N/A';
    const ocupacion = usuario.ocupacion || 'N/A';
    
    // Formatear la hora
    const fecha = new Date(item.hora_llegada);
    const horaFormateada = fecha.toLocaleTimeString('es-ES', {
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit'
    });

    return `
      <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
          ${nombre}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
          ${apellido}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
          ${ocupacion}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
          ${horaFormateada}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 rounded-full ${
            item.estatus === 'Presente' 
              ? 'bg-green-100 text-green-800' 
              : 'bg-red-100 text-red-800'
          }">
            ${item.estatus}
          </span>
        </td>
      </tr>
    `;
  }).join('');
}

// Event listeners
document.getElementById('fechaSelector').addEventListener('change', cargarAsistencias);

// Auto-actualizar cada 30 segundos
setInterval(cargarAsistencias, 30000);

// Cargar datos al iniciar la página
document.addEventListener('DOMContentLoaded', () => {
  cargarAsistencias();
});
</script>