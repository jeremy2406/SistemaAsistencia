<?php include 'componentes/header.php'; ?>
<?php include 'componentes/nav.php'; ?>
<section class="bg-blue-600 text-white py-20">
  <div class="container mx-auto text-center">
    <h1 class="text-4xl md:text-5xl font-bold mb-4">Sistema de Asistencia por Código QR</h1>
    <p class="text-lg md:text-xl mb-6">Escanea tu código QR para registrar tu asistencia de forma rápida y sencilla.</p>
    <a href="escanear.php" class="inline-block bg-white text-blue-600 px-6 py-3 rounded-md font-semibold hover:bg-gray-100 transition">Comenzar</a>
  </div>
</section>
<section class="container mx-auto py-16 px-4 grid md:grid-cols-3 gap-8 text-center">
  <div>
    <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-2xl font-bold">🔍</div>
    <h3 class="text-xl font-semibold mb-2">Escaneo instantáneo</h3>
    <p>Registra asistencia al instante desde dispositivos móviles o PC.</p>
  </div>
  <div>
    <div class="w-16 h-16 mx-auto mb-4 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl font-bold">📊</div>
    <h3 class="text-xl font-semibold mb-2">Datos en tiempo real</h3>
    <p>Consulta la lista de asistencia con hora exacta y estatus actualizado.</p>
  </div>
  <div>
    <div class="w-16 h-16 mx-auto mb-4 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-2xl font-bold">👌</div>
    <h3 class="text-xl font-semibold mb-2">Fácil de usar</h3>
    <p>Interfaz intuitiva y responsive, sin necesidad de instalación.</p>
  </div>
</section>
<?php include 'componentes/footer.php'; ?>
