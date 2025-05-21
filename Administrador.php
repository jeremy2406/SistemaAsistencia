<?php
include 'componentes/Header.php';
include 'componentes/Nav.php';
?>
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
    </div>
</section>
<?php
include 'componentes/Footer.php';
?>