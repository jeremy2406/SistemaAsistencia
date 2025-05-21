<?php 
include 'componentes/Header.php'; 
include 'componentes/Nav.php'; 
?>


    <main class="container">
        <!-- Página de Inicio -->
        <section id="home" class="page active">
            <div class="hero">
                <div class="hero-content">
                    <h1>Sistema de Asistencia por Código QR</h1>
                    <p>Registra la asistencia de manera rápida y sencilla mediante códigos QR. Olvídate de las listas de papel y los registros manuales.</p>
                    <a href="#" class="btn btn-success scan-btn" data-target="scan">
                        <i class="fas fa-qrcode"></i> Empezar a Escanear
                    </a>
                </div>
                <div class="hero-image">
                    <img src="/api/placeholder/500/300" alt="Sistema de Asistencia QR" />
                </div>
            </div>

            <h2>¿Cómo funciona?</h2>
            <div class="features">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h3>Registro Único</h3>
                    <p>Cada usuario recibe un código QR único vinculado a su perfil.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <h3>Escaneo Rápido</h3>
                    <p>Simplemente escanea el código QR con cualquier dispositivo con cámara.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Registro Automático</h3>
                    <p>El sistema registra automáticamente la hora de llegada y marca la asistencia.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Estadísticas en Tiempo Real</h3>
                    <p>Visualiza las estadísticas de asistencia en tiempo real desde el panel de administración.</p>
                </div>
            </div>
        </section>

     
<?php
 include 'componentes/Footer.php'; 
 ?>
       
        
                
                
                    
                  
                 