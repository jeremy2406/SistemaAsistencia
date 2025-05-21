<?php
// Incluir configuración de Supabase
require_once 'config/supabase.php';

// Incluir componentes
include_once 'Componentes/Header.php';
include_once 'Componentes/Nav.php';
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

        <!-- Incluir las secciones -->
        <?php include_once 'Escaneo.php'; ?>
        <?php include_once 'Personas.php'; ?>
        
        <!-- Panel de Administrador -->
        <section id="admin" class="page hidden">
            <h1>Panel de Administrador</h1>
            
            <div class="dashboard">
                <div class="sidebar">
                    <ul class="sidebar-menu">
                        <li>
                            <a href="#" class="admin-tab active" data-tab="overview">
                                <i class="fas fa-home"></i> Resumen
                            </a>
                        </li>
                        <li>
                            <a href="#" class="admin-tab" data-tab="attendance-history">
                                <i class="fas fa-history"></i> Historial
                            </a>
                        </li>
                        <li>
                            <a href="#" class="admin-tab" data-tab="users">
                                <i class="fas fa-users"></i> Usuarios
                            </a>
                        </li>
                        <li>
                            <a href="#" class="admin-tab" data-tab="export">
                                <i class="fas fa-file-export"></i> Exportar Datos
                            </a>
                        </li>
                        <li>
                            <a href="#" class="admin-tab" data-tab="settings">
                                <i class="fas fa-cog"></i> Configuración
                            </a>
                        </li>
                    </ul>
                </div>
                
                <?php include_once 'Resumen.php'; ?>
                <?php include_once 'Historial.php'; ?>
                <?php include_once 'Usuarios.php'; ?>
                <?php include_once 'Exportar.php'; ?>
                <?php include_once 'Configuracion.php'; ?>
                
            </div>
        </section>
    </main>
    
<?php include_once 'Componentes/Footer.php'; ?>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>