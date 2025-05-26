<?php include 'Componentes/Header.php'; ?>
<?php include 'Componentes/Nav.php'; ?>

<!-- Hero Section -->
<section class="pt-16 bg-gradient-to-br from-cream to-soft-brown min-h-screen flex items-center">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center">
      <div class="mb-8">
        <div class="inline-flex items-center space-x-4 mb-6">
          <div class="w-16 h-16 bg-orange-400 rounded-full flex items-center justify-center">
            <span class="text-2xl">🐕</span>
          </div>
          <div class="w-16 h-16 bg-gray-400 rounded-full flex items-center justify-center">
            <span class="text-2xl">🐱</span>
          </div>
          <div class="w-16 h-16 bg-amber-400 rounded-full flex items-center justify-center">
            <span class="text-2xl">🐩</span>
          </div>
        </div>
      </div>
      <h1 class="text-5xl md:text-7xl font-bold text-warm-brown mb-4">Chibi's House</h1>
      <p class="text-xl md:text-2xl text-gray-700 mb-8 font-medium">BEST FOR YOUR PET</p>
      <p class="text-lg text-gray-600 mb-8">📞 849-234-3465</p>
      <p class="text-base text-gray-600">BELLA VISTA, SANTIAGO DE LOS CABALLEROS</p>
    </div>
  </div>
</section>

<!-- Gallery Section -->
<section id="gallery" class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-warm-brown mb-4">Galería de Perritos</h2>
      <p class="text-xl text-gray-600">Nuestros adorables compañeros de cuatro patas</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Gallery Items -->
      <div class="group cursor-pointer">
        <div
          class="bg-gradient-to-br from-orange-200 to-orange-300 rounded-2xl p-8 h-64 flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300">
          <div class="text-6xl">🐕</div>
        </div>
        <p class="text-center mt-4 font-semibold text-gray-700">Golden Retriever</p>
      </div>

      <div class="group cursor-pointer">
        <div
          class="bg-gradient-to-br from-amber-200 to-amber-300 rounded-2xl p-8 h-64 flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300">
          <div class="text-6xl">🐩</div>
        </div>
        <p class="text-center mt-4 font-semibold text-gray-700">Poodle</p>
      </div>

      <div class="group cursor-pointer">
        <div
          class="bg-gradient-to-br from-yellow-200 to-yellow-300 rounded-2xl p-8 h-64 flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300">
          <div class="text-6xl">🐶</div>
        </div>
        <p class="text-center mt-4 font-semibold text-gray-700">Labrador</p>
      </div>

      <div class="group cursor-pointer">
        <div
          class="bg-gradient-to-br from-red-200 to-red-300 rounded-2xl p-8 h-64 flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300">
          <div class="text-6xl">🐕‍🦺</div>
        </div>
        <p class="text-center mt-4 font-semibold text-gray-700">Pastor Alemán</p>
      </div>

      <div class="group cursor-pointer">
        <div
          class="bg-gradient-to-br from-purple-200 to-purple-300 rounded-2xl p-8 h-64 flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300">
          <div class="text-6xl">🐕</div>
        </div>
        <p class="text-center mt-4 font-semibold text-gray-700">Bulldog</p>
      </div>

      <div class="group cursor-pointer">
        <div
          class="bg-gradient-to-br from-green-200 to-green-300 rounded-2xl p-8 h-64 flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300">
          <div class="text-6xl">🐶</div>
        </div>
        <p class="text-center mt-4 font-semibold text-gray-700">Chihuahua</p>
      </div>
    </div>
  </div>
</section>

<!-- Company Section -->
<section id="company" class="py-20 bg-cream">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-warm-brown mb-4">Nuestra Empresa</h2>
      <p class="text-xl text-gray-600">Conoce más sobre Chibi's House</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Misión -->
      <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-6">
          <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
              </path>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-warm-brown">Misión</h3>
        </div>
        <p class="text-gray-600 leading-relaxed">Brindar atención personalizada,
          profesional y amorosa a las
          mascotas directamente en la
          comodidad
          de
          su
          hogar,
          asegurando su bienestar, felicidad y
          tranquilidad para sus dueños,
          mediante un servicio confiable,
          rápido y adaptado a cada necesidad.</p>
      </div>

      <!-- Visión -->
      <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-6">
          <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
              </path>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-warm-brown">Visión</h3>
        </div>
        <p class="text-gray-600 leading-relaxed">Ser la empresa líder en servicios de
          cuidado de mascotas a domicilio en
          el país, reconocida por la calidad,
          calidez y compromiso con el
          bienestar
          animal,
          continuamente
          para
          innovando
          ofrecer
          experiencias excepcionales tanto a
          las mascotas como a sus familias.</p>
      </div>

      <!-- Valores -->
      <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-6">
          <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
              </path>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-warm-brown">Valores</h3>
        </div>
        <p class="text-gray-600 leading-relaxed"> Amor por los animales,
          confianza,
          compromiso,
          empatía,
          innovación y
          transparencia.</p>
      </div>

      <!-- Sobre nosotros -->
      <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-6">
          <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
              </path>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-warm-brown">Sobre Nosotros</h3>
        </div>
        <p class="text-gray-600 leading-relaxed">Chibi's House es más que una
          tienda de mascotas, es una
          comunidad dedicada a ofrecer lo
          mejor para tus animales. Inspirados
          en lo "chibi" (pequeño y adorable),
          priorizamos calidad, cuidado y
          alegría. Ofrecemos asesoramiento
          personalizado y celebramos la
          individualidad de cada animal.</p>
      </div>
    </div>
  </div>
</section>

<!-- Company Structure Section -->
<section id="structure" class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-warm-brown mb-4">Estructura de la Empresa</h2>
      <p class="text-xl text-gray-600">Organización y jerarquía de Chibi's House</p>
    </div>

    <!-- Organization Chart -->
    <div class="max-w-6xl mx-auto">
      <!-- Director Ejecutivo -->
      <div class="flex justify-center mb-8">
        <div class="bg-gradient-to-br from-warm-brown to-amber-800 text-white rounded-xl p-6 shadow-lg">
          <h3 class="text-xl font-bold text-center">Director Ejecutivo</h3>
        </div>
      </div>

      <!-- Level 1 -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-soft-brown text-gray-800 rounded-xl p-4 shadow-lg text-center">
          <h4 class="font-semibold">Directora de Recursos Humanos</h4>
        </div>
        <div class="bg-soft-brown text-gray-800 rounded-xl p-4 shadow-lg text-center">
          <h4 class="font-semibold">Departamento Financiero</h4>
        </div>
        <div class="bg-soft-brown text-gray-800 rounded-xl p-4 shadow-lg text-center">
          <h4 class="font-semibold">Gerente de Administración</h4>
        </div>
        <div class="bg-soft-brown text-gray-800 rounded-xl p-4 shadow-lg text-center">
          <h4 class="font-semibold">Departamento de Servicio al Cliente</h4>
        </div>
      </div>

      <!-- Level 2 -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-light-brown text-gray-800 rounded-lg p-3 shadow text-center">
          <h5 class="text-sm font-medium">Auxiliar de Recursos Humanos</h5>
        </div>
        <div class="bg-light-brown text-gray-800 rounded-lg p-3 shadow text-center">
          <h5 class="text-sm font-medium">Departamento de Contabilidad</h5>
        </div>
        <div class="bg-light-brown text-gray-800 rounded-lg p-3 shadow text-center">
          <h5 class="text-sm font-medium">Encargada de Gestión Documental</h5>
        </div>
        <div class="bg-light-brown text-gray-800 rounded-lg p-3 shadow text-center">
          <h5 class="text-sm font-medium">Departamento de Ventas</h5>
        </div>
      </div>

      <!-- Level 3 -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <h5 class="text-sm font-medium"></h5>

        <div class="bg-amber-100 text-gray-800 rounded-lg p-3 shadow text-center">
          <h5 class="text-sm font-medium">Departamento de Tesorería</h5>
        </div>
        <div class="bg-amber-100 text-gray-800 rounded-lg p-3 shadow text-center">
          <h5 class="text-sm font-medium">Departamento de Consejería</h5>
        </div>
        <div class="bg-amber-100 text-gray-800 rounded-lg p-3 shadow text-center">
          <h5 class="text-sm font-medium">Departamento de Marketing</h5>
        </div>
      </div>
      <!-- Level 4 -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <h5 class="text-sm font-medium"></h5>

        <div class="bg-amber-100 text-gray-800 rounded-lg p-3 shadow text-center">
          <h5 class="text-sm font-medium">Departamento de Compras</h5>
        </div>

        <h5 class="text-sm font-medium"></h5>

        <div class="bg-amber-100 text-gray-800 rounded-lg p-3 shadow text-center">
          <h5 class="text-sm font-medium">Departamento de Caja</h5>
        </div>
      </div>

    </div>
  </div>
</section>


<?php include 'Componentes/Footer.php'; ?>