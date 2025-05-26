<footer class="bg-gradient-to-r from-orange-800 to-orange-700 text-white py-12 mt-16">
  <div class="container mx-auto px-4">
    <!-- Logo y descripción -->
    <div class="text-center mb-8">
      <div class="flex items-center justify-center space-x-3 mb-4">
        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
          <span class="text-orange-600 font-bold text-lg">🐕</span>
        </div>
        <div>
          <h3 class="text-2xl font-bold">Chibi's House</h3>
          <p class="text-orange-200 text-sm">Best for You Pet</p>
        </div>
      </div>
      <p class="text-orange-200 max-w-2xl mx-auto">
        Más que una tienda de mascotas, somos una comunidad dedicada a ofrecer lo mejor para tus animales con amor, calidad y cuidado profesional.
      </p>
    </div>
    
    <!-- Enlaces e información -->
    <div class="grid md:grid-cols-3 gap-8 text-center md:text-left mb-8">
      <div>
        <h4 class="font-semibold text-orange-200 mb-4 text-lg">🏠 Sobre el Sistema</h4>
        <p class="text-sm text-orange-100 leading-relaxed">
          Sistema digital de asistencia mediante códigos QR, diseñado para facilitar el registro y control de asistencia del personal de Chibi's House.
        </p>
      </div>
      
      <div>
        <h4 class="font-semibold text-orange-200 mb-4 text-lg">🔗 Enlaces Útiles</h4>
        <ul class="text-sm text-orange-100 space-y-2">
          <li>
            <a href="./index.php" class="hover:text-white transition-colors duration-300 flex items-center justify-center md:justify-start">
              <span class="mr-2">🏠</span> Inicio
            </a>
          </li>
          <li>
            <a href="./escanear.php" class="hover:text-white transition-colors duration-300 flex items-center justify-center md:justify-start">
              <span class="mr-2">📱</span> Escanear QR
            </a>
          </li>
          <li>
            <a href="./lista.php" class="hover:text-white transition-colors duration-300 flex items-center justify-center md:justify-start">
              <span class="mr-2">📋</span> Lista de Asistencia
            </a>
          </li>
          <li>
            <a href="./generador_qr.php" class="hover:text-white transition-colors duration-300 flex items-center justify-center md:justify-start">
              <span class="mr-2">⚡</span> Generar Códigos QR
            </a>
          </li>
        </ul>
      </div>
      
      <div>
        <h4 class="font-semibold text-orange-200 mb-4 text-lg">📞 Contacto</h4>
        <div class="space-y-2 text-sm text-orange-100">
          <p class="flex items-center justify-center md:justify-start">
            <span class="mr-2">📍</span> Bella Vista, Santiago de los Caballeros
          </p>
          <p class="flex items-center justify-center md:justify-start">
            <span class="mr-2">📞</span> 849-234-3465
          </p>
          <p class="flex items-center justify-center md:justify-start">
            <span class="mr-2">✉️</span> info@chibishouse.com
          </p>
        </div>
      </div>
    </div>
    
    <!-- Valores -->
    <div class="border-t border-orange-600 pt-6 mb-6">
      <h4 class="text-center font-semibold text-orange-200 mb-4">💎 Nuestros Valores</h4>
      <div class="flex flex-wrap justify-center gap-4 text-sm">
        <span class="bg-orange-600 px-3 py-1 rounded-full">❤️ Amor por los animales</span>
        <span class="bg-orange-600 px-3 py-1 rounded-full">🤝 Confianza</span>
        <span class="bg-orange-600 px-3 py-1 rounded-full">💪 Compromiso</span>
        <span class="bg-orange-600 px-3 py-1 rounded-full">🤗 Empatía</span>
        <span class="bg-orange-600 px-3 py-1 rounded-full">💡 Innovación</span>
        <span class="bg-orange-600 px-3 py-1 rounded-full">🔍 Transparencia</span>
      </div>
    </div>
    
    <!-- Copyright -->
    <div class="text-center text-orange-200 border-t border-orange-600 pt-6">
      <p class="text-sm">
        &copy; <?= date("Y") ?> Chibi's House - Sistema de Asistencia Digital. 
        <br class="md:hidden">
        Todos los derechos reservados. Desarrollado con ❤️ para el mejor cuidado de las mascotas.
      </p>
    </div>
  </div>
</footer>
</body>
</html>