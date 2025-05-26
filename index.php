<?php include 'Componentes/Header.php'; ?>
<?php include 'Componentes/Nav.php'; ?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-orange-400 via-orange-300 to-yellow-200 text-white py-20">
  <div class="container mx-auto text-center px-4">
    <!-- Logo -->
    <div class="mb-8">
      <img src="logo-chibis.png" alt="Chibi's House Logo" class="mx-auto w-48 h-48 object-contain">
    </div>
    <h1 class="text-4xl md:text-6xl font-bold mb-4 text-orange-900">Chibi's House</h1>
    <p class="text-xl md:text-2xl mb-2 text-orange-800 font-medium">Best for You Pet</p>
    <p class="text-lg md:text-xl mb-8 text-orange-700">Sistema de Asistencia Digital</p>
    <a href="escanear.php" class="inline-block bg-orange-600 text-white px-8 py-4 rounded-full font-semibold hover:bg-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
      🔍 Registrar Asistencia
    </a>
  </div>
</section>




<!-- Misión, Visión y Valores -->
<section class="bg-gradient-to-r from-orange-50 to-yellow-50 py-16">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-12 text-orange-800">Nuestra Identidad</h2>
    
    <div class="grid md:grid-cols-3 gap-8 mb-12">
      <!-- Misión -->
      <div class="bg-white rounded-2xl p-8 shadow-lg border border-orange-100">
        <div class="text-center mb-6">
          <div class="w-16 h-16 mx-auto bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-2xl mb-4">🎯</div>
          <h3 class="text-2xl font-bold text-orange-800">Misión</h3>
        </div>
        <p class="text-gray-700 leading-relaxed">
          Brindar atención personalizada, profesional y amorosa a las mascotas directamente en la comodidad de su hogar, 
          asegurando su bienestar, felicidad y tranquilidad para sus dueños, mediante un servicio confiable, rápido y adaptado a cada necesidad.
        </p>
      </div>

      <!-- Visión -->
      <div class="bg-white rounded-2xl p-8 shadow-lg border border-orange-100">
        <div class="text-center mb-6">
          <div class="w-16 h-16 mx-auto bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl mb-4">🌟</div>
          <h3 class="text-2xl font-bold text-orange-800">Visión</h3>
        </div>
        <p class="text-gray-700 leading-relaxed">
          Ser la empresa líder en servicios de cuidado de mascotas a domicilio en el país, reconocida por la calidad, 
          calidez y compromiso con el bienestar animal, innovando continuamente para ofrecer experiencias excepcionales tanto a las mascotas como a sus familias.
        </p>
      </div>

      <!-- Quiénes Somos -->
      <div class="bg-white rounded-2xl p-8 shadow-lg border border-orange-100">
        <div class="text-center mb-6">
          <div class="w-16 h-16 mx-auto bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-2xl mb-4">🏠</div>
          <h3 class="text-2xl font-bold text-orange-800">Quiénes Somos</h3>
        </div>
        <p class="text-gray-700 leading-relaxed">
          Chibi's House es más que una tienda de mascotas, es una comunidad dedicada a ofrecer lo mejor para tus animales. 
          Inspirados en lo "chibi" (pequeño y adorable), priorizamos calidad, cuidado y alegría. Ofrecemos asesoramiento personalizado y celebramos la individualidad de cada animal.
        </p>
      </div>
    </div>

    <!-- Valores -->
    <div class="bg-white rounded-2xl p-8 shadow-lg border border-orange-100">
      <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-2xl mb-4">💎</div>
        <h3 class="text-2xl font-bold text-orange-800">Nuestros Valores</h3>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="text-center p-4 bg-orange-50 rounded-lg">
          <div class="text-2xl mb-2">❤️</div>
          <span class="text-sm font-semibold text-orange-800">Amor por los animales</span>
        </div>
        <div class="text-center p-4 bg-blue-50 rounded-lg">
          <div class="text-2xl mb-2">🤝</div>
          <span class="text-sm font-semibold text-orange-800">Confianza</span>
        </div>
        <div class="text-center p-4 bg-green-50 rounded-lg">
          <div class="text-2xl mb-2">💪</div>
          <span class="text-sm font-semibold text-orange-800">Compromiso</span>
        </div>
        <div class="text-center p-4 bg-yellow-50 rounded-lg">
          <div class="text-2xl mb-2">🤗</div>
          <span class="text-sm font-semibold text-orange-800">Empatía</span>
        </div>
        <div class="text-center p-4 bg-purple-50 rounded-lg">
          <div class="text-2xl mb-2">💡</div>
          <span class="text-sm font-semibold text-orange-800">Innovación</span>
        </div>
        <div class="text-center p-4 bg-indigo-50 rounded-lg">
          <div class="text-2xl mb-2">🔍</div>
          <span class="text-sm font-semibold text-orange-800">Transparencia</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Organigrama -->
<section class="py-16">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-12 text-orange-800">Estructura Organizacional</h2>
    
    <div class="bg-white rounded-2xl p-8 shadow-lg border border-orange-100 overflow-x-auto">
      <!-- Director Ejecutivo -->
      <div class="text-center mb-8">
        <div class="inline-block bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold">
          Director Ejecutivo
        </div>
      </div>

      <!-- Primera línea de departamentos -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="text-center">
          <div class="bg-orange-300 text-orange-900 px-4 py-2 rounded-lg font-medium mb-2">
            Directora de Recursos Humanos
          </div>
          <div class="bg-orange-100 text-orange-800 px-3 py-1 rounded text-sm">
            Auxiliar de Recursos Humanos
          </div>
        </div>
        
        <div class="text-center">
          <div class="bg-orange-300 text-orange-900 px-4 py-2 rounded-lg font-medium mb-2">
            Departamento Financiero
          </div>
          <div class="space-y-1">
            <div class="bg-orange-100 text-orange-800 px-3 py-1 rounded text-sm">
              Dept. de Contabilidad
            </div>
            <div class="bg-orange-100 text-orange-800 px-3 py-1 rounded text-sm">
              Dept. de Tesorería
            </div>
            <div class="bg-orange-100 text-orange-800 px-3 py-1 rounded text-sm">
              Dept. de Compras
            </div>
          </div>
        </div>
        
        <div class="text-center">
          <div class="bg-orange-300 text-orange-900 px-4 py-2 rounded-lg font-medium mb-2">
            Gerente de Administración
          </div>
          <div class="space-y-1">
            <div class="bg-orange-100 text-orange-800 px-3 py-1 rounded text-sm">
              Encargada de Gestión Documental
            </div>
            <div class="bg-orange-100 text-orange-800 px-3 py-1 rounded text-sm">
              Dept. de Conserjería
            </div>
          </div>
        </div>
        
        <div class="text-center">
          <div class="bg-orange-300 text-orange-900 px-4 py-2 rounded-lg font-medium mb-2">
            Departamento de Servicio al Cliente
          </div>
          <div class="space-y-1">
            <div class="bg-orange-100 text-orange-800 px-3 py-1 rounded text-sm">
              Dept. de Ventas
            </div>
            <div class="bg-orange-100 text-orange-800 px-3 py-1 rounded text-sm">
              Dept. de Marketing
            </div>
            <div class="bg-orange-100 text-orange-800 px-3 py-1 rounded text-sm">
              Dept. de Caja
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



<?php include 'Componentes/Footer.php'; ?>