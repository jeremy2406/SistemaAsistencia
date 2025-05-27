 <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white shadow-lg z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and Brand -->
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center">
                       <img src="./assets/Logo.png" class="text-white text-xl font-bold" alt="Logo Chibi's House">
                    </div>
                   <a href="./index.php"><span class="text-2xl font-bold text-warm-brown">Chibi's House</span></a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8">
                    <a href="./index.php" class="text-gray-700 hover:text-warm-brown transition-colors duration-300 font-medium">Inicio</a>
                    <a href="./escanear.php" class="text-gray-700 hover:text-warm-brown transition-colors duration-300 font-medium">Escanear</a>
                    <a href="./lista.php" class="text-gray-700 hover:text-warm-brown transition-colors duration-300 font-medium">Lista</a>
                    <a href="./generador_qr.php" class="text-gray-700 hover:text-warm-brown transition-colors duration-300 font-medium">Generar</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-gray-700 hover:text-warm-brown">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden bg-white border-t">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="./index.php" class="block px-3 py-2 text-gray-700 hover:text-warm-brown font-medium">Inicio</a>
                    <a href="./escanear.php" class="block px-3 py-2 text-gray-700 hover:text-warm-brown font-medium">Escanear</a>
                    <a href="./lista.php" class="block px-3 py-2 text-gray-700 hover:text-warm-brown font-medium">Lista</a>
                    <a href="./generador_qr.php" class="block px-3 py-2 text-gray-700 hover:text-warm-brown font-medium">Generar</a>
                </div>
            </div>
        </div>
    </nav>