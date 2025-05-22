<div class="admin-content active" id="overview">
    <div class="overview-header">
        <h2>Resumen General</h2>
        <div class="date-selector">
            <input type="date" id="overview-date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
            <button id="refresh-overview" class="btn btn-primary">
                <i class="fas fa-sync-alt"></i> Actualizar
            </button>
        </div>
    </div>
    
    <!-- Estadísticas Principales -->
    <div class="stats-overview">
        <div class="stat-card total">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-details">
                <h3 id="total-users">0</h3>
                <p>Total Usuarios</p>
            </div>
        </div>
        
        <div class="stat-card present">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-details">
                <h3 id="overview-present">0</h3>
                <p>Presentes Hoy</p>
                <small id="present-percentage">0%</small>
            </div>
        </div>
        
        <div class="stat-card late">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-details">
                <h3 id="overview-late">0</h3>
                <p>Tardanzas Hoy</p>
                <small id="late-percentage">0%</small>
            </div>
        </div>
        
        <div class="stat-card absent">
            <div class="stat-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-details">
                <h3 id="overview-absent">0</h3>
                <p>Ausentes Hoy</p>
                <small id="absent-percentage">0%</small>
            </div>
        </div>
    </div>
    
    <div class="overview-content">
        <!-- Gráfico de Asistencia -->
        <div class="chart-section">
            <div class="chart-card">
                <h3>Asistencia de Hoy</h3>
                <div class="chart-container">
                    <canvas id="attendance-chart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Actividad Reciente -->
        <div class="activity-section">
            <div class="activity-card">
                <div class="activity-header">
                    <h3>Actividad Reciente</h3>
                    <button id="refresh-activity" class="btn btn-sm btn-outline">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="activity-list" id="recent-activity">
                    <div class="loading-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        Cargando actividad reciente...
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Resumen por Roles -->
    <div class="role-summary">
        <h3>Resumen por Roles</h3>
        <div class="role-stats" id="role-stats">
            <div class="role-card">
                <div class="role-info">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Estudiantes</span>
                </div>
                <div class="role-numbers">
                    <span id="students-present">0</span> / <span id="students-total">0</span>
                </div>
            </div>
            
            <div class="role-card">
                <div class="role-info">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Profesores</span>
                </div>
                <div class="role-numbers">
                    <span id="teachers-present">0</span> / <span id="teachers-total">0</span>
                </div>
            </div>
            
            <div class="role-card">
                <div class="role-info">
                    <i class="fas fa-user-tie"></i>
                    <span>Personal</span>
                </div>
                <div class="role-numbers">
                    <span id="staff-present">0</span> / <span id="staff-total">0</span>
                </div>
            </div>
            
            <div class="role-card">
                <div class="role-info">
                    <i class="fas fa-user-shield"></i>
                    <span>Administradores</span>
                </div>
                <div class="role-numbers">
                    <span id="admins-present">0</span> / <span id="admins-total">0</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Acciones Rápidas -->
    <div class="quick-actions">
        <h3>Acciones Rápidas</h3>
        <div class="action-buttons">
            <button id="mark-all-absent" class="btn btn-warning">
                <i class="fas fa-user-times"></i>
                Marcar Ausentes del Día
            </button>
            <button id="export-today" class="btn btn-success">
                <i class="fas fa-file-export"></i>
                Exportar Asistencia de Hoy
            </button>
            <button id="view-attendance" class="btn btn-info">
                <i class="fas fa-list"></i>
                Ver Historial de Hoy
            </button>
        </div>
    </div>
</div>