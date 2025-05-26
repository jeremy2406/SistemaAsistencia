<!-- Footer -->
    <footer id="contact" class="bg-warm-brown text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex justify-center items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                       <img src="./assets/Logo.png" class="text-white text-xl font-bold" alt="Logo Chibi's House">
                    </div>
                    <h3 class="text-3xl font-bold">Chibi's House</h3>
                </div>
                
                <div class="mb-6">
                    <p class="text-xl mb-2">📞 849-234-3465</p>
                    <p class="text-lg">📍 Bella Vista, Santiago de los Caballeros</p>
                </div>
                
                <div class="border-t border-amber-700 pt-6 mt-6">
                    <p class="text-amber-200">&copy; 2025 Chibi's House. Todos los derechos reservados por JEM. Best for Your Pet.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    mobileMenu.classList.add('hidden');
                }
            });
        });
        
        // Add scroll effect to navigation
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 100) {
                nav.classList.add('backdrop-blur-sm', 'bg-white/95');
            } else {
                nav.classList.remove('backdrop-blur-sm', 'bg-white/95');
            }
        });
    </script>
</body>
</html>