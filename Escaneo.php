<!-- Página de Escaneo de QR -->
<section id="scan" class="page hidden">
    <h1>Registrar Asistencia</h1>
    
    <div class="scan-container">
        <div class="scan-section">
            <div class="scan-header">
                <h2>Escanear Código QR</h2>
                <p>Apunta la cámara hacia el código QR para registrar la asistencia</p>
            </div>
            
            <div class="scanner-container">
                <div id="qr-reader" class="qr-scanner"></div>
                <div id="qr-reader-results" class="scan-results"></div>
            </div>
            
            <div class="scanner-controls">
                <button id="start-scan" class="btn btn-success">
                    <i class="fas fa-camera"></i> Iniciar Escaneo
                </button>
                <button id="stop-scan" class="btn btn-danger" style="display: none;">
                    <i class="fas fa-stop"></i> Detener Escaneo
                </button>
                <button id="switch-camera" class="btn btn-info" style="display: none;">
                    <i class="fas fa-sync-alt"></i> Cambiar Cámara
                </button>
            </div>
            
            <div class="manual-entry">
                <h3>Entrada Manual</h3>
                <div class="input-group">
                    <input type="text" id="manual-qr" placeholder="Ingrese el código QR manualmente" class="form-control">
                    <button id="manual-submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Verificar
                    </button>
                </div>
            </div>
        </div>
        
        <div class="scan-info">
            <div class="scan-stats">
                <h3>Estadísticas de Hoy</h3>
                <div class="stats-grid">
                    <div class="stat-card present">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h4 id="present-count">0</h4>
                            <p>Presentes</p>
                        </div>
                    </div>
                    <div class="stat-card late">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <h4 id="late-count">0</h4>
                            <p>Tardanzas</p>
                        </div>
                    </div>
                    <div class="stat-card absent">
                        <div class="stat-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h4 id="absent-count">0</h4>
                            <p>Ausentes</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="recent-scans">
                <h3>Registros Recientes</h3>
                <div id="recent-scans-list" class="recent-list">
                    <p class="no-data">No hay registros recientes</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Resultado -->
    <div id="scan-result-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="result-title">Resultado del Escaneo</h3>
                <span class="close" id="close-result-modal">&times;</span>
            </div>
            <div class="modal-body">
                <div id="result-content">
                    <div class="result-user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-details">
                            <h4 id="result-user-name">---</h4>
                            <p id="result-user-email">---</p>
                            <span id="result-user-role" class="role-badge">---</span>
                        </div>
                    </div>
                    <div class="result-status">
                        <div id="result-status-icon" class="status-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="status-details">
                            <h5 id="result-status-text">Asistencia Registrada</h5>
                            <p id="result-status-time">---</p>
                        </div>
                    </div>
                </div>
                <div id="result-message" class="result-message"></div>
            </div>
            <div class="modal-footer">
                <button id="continue-scanning" class="btn btn-primary">Continuar Escaneando</button>
            </div>
        </div>
    </div>
</section>