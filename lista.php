<?php include 'componentes/header.php'; ?>
<?php include 'componentes/nav.php'; ?>
<div class="container mx-auto p-4">
  <h2 class="text-2xl font-bold mb-4">Lista de Asistencia</h2>
  <div class="overflow-x-auto shadow rounded-lg">
    <table class="min-w-full divide-y divide-gray-200 bg-white">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apellido</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ocupación</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora de llegada</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estatus</th>
        </tr>
      </thead>
      <tbody id="tablaAsistencia" class="bg-white divide-y divide-gray-200"></tbody>
    </table>
  </div>
</div>
<script>
fetch('/php/obtener_asistencias.php')
  .then(res => res.json())
  .then(data => {
    const tbody = document.querySelector('#tablaAsistencia');
    data.forEach(item => {
      const row = `<tr>
        <td class="px-6 py-4 whitespace-nowrap">${item.usuarios.nombre}</td>
        <td class="px-6 py-4 whitespace-nowrap">${item.usuarios.apellido}</td>
        <td class="px-6 py-4 whitespace-nowrap">${item.usuarios.ocupacion}</td>
        <td class="px-6 py-4 whitespace-nowrap">${new Date(item.hora_llegada).toLocaleTimeString()}</td>
        <td class="px-6 py-4 whitespace-nowrap">
          <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full ${item.estatus === 'Presente' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
            ${item.estatus}
          </span>
        </td>
      </tr>`;
      tbody.innerHTML += row;
    });
  });
</script>
<?php include 'componentes/footer.php'; ?>
