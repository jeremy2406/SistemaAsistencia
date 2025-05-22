// Sistema de Asistencia QR - Funciones Principales
$(document).ready(function() {
    console.log('Sistema de Asistencia QR iniciado');
    
    // Inicializar componentes
    initializeNavigation();
    initializeScanner();
    initializeUserManagement();
    initializeAdminPanel();
    
    // Cargar datos iniciales
    loadDashboardStats();
    loadRecentActivity();
});

// ========== NAVEGACIÓN ==========
function initializeNavigation() {
    // Manejar clics en navegación principal
    $('.nav-link').on('click', function(e) {
        e.preventDefault();
        const target = $(this).data('target');
        
        if (target) {
            showPage(target);
            
            // Actualizar navegación activa
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
        }
    });
    
    // Manejar clics en tabs del panel de administrador
    $('.admin-tab').on('click', function(e) {
        e.preventDefault();
        const tab = $(this).data('tab');
        
        if (tab) {
            showAdminTab(tab);
            
            // Actualizar tab activo
            $('.admin-tab').removeClass('active');
            $(this).addClass('active');
        }
    });
    
    // Botón de escaneo en hero
    $('.scan-btn').on('click', function(e) {
        e.preventDefault();
        const target = $(this).data('target');
        showPage(target);
        
        // Actualizar navegación
        $('.nav-link').removeClass('active');
        $('.nav-link[data-target="scan"]').addClass('active');
    });
}

function showPage(pageId) {
    // Ocultar todas las páginas
    $('.page').removeClass('active').addClass('hidden');
    
    // Mostrar página seleccionada
    $(`#${pageId}`).removeClass('hidden').addClass('active');
    
    // Ejecutar acciones específicas según la página
    switch(pageId) {
        case 'scan':
            initializeQRScanner();
            loadScanStats();
            break;
        case 'admin':
            loadDashboardStats();
            break;
        case 'people':
            loadAllUsers();
            break;
    }
}

function showAdminTab(tabId) {
    // Ocultar todas las tabs
    $('.admin-content').removeClass('active').addClass('hidden');
    
    // Mostrar tab seleccionada
    $(`#admin-${tabId}`).removeClass('hidden').addClass('active');
    
    // Cargar datos específicos
    switch(tabId) {
        case 'overview':
            loadDashboardStats();
            loadRecentActivity();
            break;
        case 'attendance-history':
            loadAttendanceHistory();
            break;
        case 'users':
            loadUsersForAdmin();
            break;
        case 'export':
            // No necesita carga inicial
            break;
        case 'settings':
            loadSystemConfig();
            break;
    }
}

// ========== ESCÁNER QR ==========
let qrScanner = null;
let scannerActive = false;

function initializeScanner() {
    $('#start-scan').on('click', startQRScanner);
    $('#stop-scan').on('click', stopQRScanner);
    $('#switch-camera').on('click', switchCamera);
    $('#manual-submit').on('click', processManualQR);
    
    // Enter en input manual
    $('#manual-qr').on('keypress', function(e) {
        if (e.which === 13) {
            processManualQR();
        }
    });
}

function initializeQRScanner() {
    // Solo inicializar si no está ya activo
    if (!scannerActive) {
        $('#start-scan').show();
        $('#stop-scan').hide();
        $('#switch-camera').hide();
    }
}

function startQRScanner() {
    console.log('Iniciando escáner QR...');
    
    // Verificar si Html5Qrcode está disponible
    if (typeof Html5Qrcode === 'undefined') {
        console.error('Html5Qrcode no está disponible');
        showNotification('Error: Librería de escáner no disponible', 'error');
        return;
    }
    
    try {
        qrScanner = new Html5Qrcode("qr-reader");
        
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };
        
        qrScanner.start(
            { facingMode: "environment" }, // Cámara trasera
            config,
            (decodedText, decodedResult) => {
                console.log('QR escaneado:', decodedText);
                processQRCode(decodedText);
            },
            (errorMessage) => {
                // Error de escaneo (normal cuando no hay QR visible)
            }
        ).then(() => {
            scannerActive = true;
            $('#start-scan').hide();
            $('#stop-scan').show();
            $('#switch-camera').show();
            showNotification('Escáner iniciado correctamente', 'success');
        }).catch((error) => {
            console.error('Error al iniciar escáner:', error);
            showNotification('Error al acceder a la cámara', 'error');
        });
        
    } catch (error) {
        console.error('Error al crear escáner:', error);
        showNotification('Error al inicializar el escáner', 'error');
    }
}

function stopQRScanner() {
    if (qrScanner && scannerActive) {
        qrScanner.stop().then(() => {
            scannerActive = false;
            $('#start-scan').show();
            $('#stop-scan').hide();
            $('#switch-camera').hide();
            showNotification('Escáner detenido', 'info');
        }).catch((error) => {
            console.error('Error al detener escáner:', error);
        });
    }
}

function switchCamera() {
    // Implementar cambio de cámara si es necesario
    showNotification('Función de cambio de cámara en desarrollo', 'info');
}

function processManualQR() {
    const qrCode = $('#manual-qr').val().trim();
    
    if (!qrCode) {
        showNotification('Por favor, ingrese un código QR', 'warning');
        return;
    }
    
    processQRCode(qrCode);
    $('#manual-qr').val('');
}

function processQRCode(qrCode) {
    console.log('Procesando código QR:', qrCode);
    
    // Mostrar loading
    showLoadingModal('Verificando código QR...');
    
    // Enviar a servidor para procesar
    $.ajax({
        url: 'Includes/Handler.php',
        method: 'POST',
        data: {
            action: 'scan_qr',
            qr_code: qrCode
        },
        dataType: 'json',
        success: function(response) {
            hideLoadingModal();
            
            if (response.success) {
                showScanResult(response.data);
                updateScanStats();
                loadRecentScans();
            } else {
                showNotification(response.message || 'Error al procesar código QR', 'error');
            }
        },
        error: function(xhr, status, error) {
            hideLoadingModal();
            console.error('Error AJAX:', error);
            showNotification('Error de conexión al servidor', 'error');
        }
    });
}

function showScanResult(data) {
    // Llenar modal con información del resultado
    $('#result-user-name').text(data.usuario?.nombre || 'Usuario desconocido');
    $('#result-user-email').text(data.usuario?.email || '');
    $('#result-user-role').text(data.usuario?.rol || '').removeClass().addClass('role-badge ' + (data.usuario?.rol || ''));
    
    // Configurar estado
    const statusConfig = {
        'presente': { icon: 'fa-check-circle', color: 'success', text: 'Asistencia Registrada' },
        'tardanza': { icon: 'fa-clock', color: 'warning', text: 'Tardanza Registrada' },
        'error': { icon: 'fa-times-circle', color: 'error', text: 'Error en Registro' }
    };
    
    const status = statusConfig[data.estado] || statusConfig['error'];
    
    $('#result-status-icon i').removeClass().addClass('fas ' + status.icon);
    $('#result-status-icon').removeClass().addClass('status-icon ' + status.color);
    $('#result-status-text').text(status.text);
    $('#result-status-time').text(data.hora || new Date().toLocaleTimeString());
    
    // Mostrar mensaje adicional si existe
    if (data.mensaje) {
        $('#result-message').text(data.mensaje).show();
    } else {
        $('#result-message').hide();
    }
    
    // Mostrar modal
    $('#scan-result-modal').show();
}

// ========== GESTIÓN DE USUARIOS ==========
function initializeUserManagement() {
    // Modal de agregar usuario
    $('#add-user-btn').on('click', function() {
        $('#user-modal').show();
        $('#user-form')[0].reset();
        $('#user-modal .modal-title').text('Agregar Usuario');
        $('#user-id').val('');
    });
    
    // Guardar usuario
    $('#save-user').on('click', saveUser);
    
    // Formulario de usuario
    $('#user-form').on('submit', function(e) {
        e.preventDefault();
        saveUser();
    });
    
    // Cerrar modales
    $('.close, .modal-close').on('click', function() {
        $(this).closest('.modal').hide();
    });
    
    // Continuar escaneando
    $('#continue-scanning').on('click', function() {
        $('#scan-result-modal').hide();
    });
}

function saveUser() {
    const userId = $('#user-id').val();
    const formData = {
        action: userId ? 'update_user' : 'add_user',
        name: $('#user-name').val().trim(),
        email: $('#user-email').val().trim(),
        role: $('#user-role').val()
    };
    
    if (userId) {
        formData.user_id = userId;
    }
    
    // Validación básica
    if (!formData.name || !formData.email) {
        showNotification('Nombre y correo son obligatorios', 'warning');
        return;
    }
    
    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(formData.email)) {
        showNotification('Por favor, ingrese un correo válido', 'warning');
        return;
    }
    
    showLoadingModal(userId ? 'Actualizando usuario...' : 'Creando usuario...');
    
    $.ajax({
        url: 'Includes/Handler.php',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            hideLoadingModal();
            
            if (response.success) {
                $('#user-modal').hide();
                showNotification(response.message, 'success');
                
                // Recargar listas de usuarios
                loadAllUsers();
                loadUsersForAdmin();
            } else {
                showNotification(response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            hideLoadingModal();
            console.error('Error al guardar usuario:', error);
            showNotification('Error de conexión al servidor', 'error');
        }
    });
}

function editUser(userId) {
    // Buscar datos del usuario
    $.ajax({
        url: 'Includes/Handler.php',
        method: 'POST',
        data: {
            action: 'get_users'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const user = response.data.find(u => u.id === userId);
                if (user) {
                    // Llenar formulario
                    $('#user-id').val(user.id);
                    $('#user-name').val(user.nombre);
                    $('#user-email').val(user.email);
                    $('#user-role').val(user.rol);
                    
                    // Mostrar modal
                    $('#user-modal .modal-title').text('Editar Usuario');
                    $('#user-modal').show();
                }
            }
        }
    });
}

function deleteUser(userId, userName) {
    if (!confirm(`¿Está seguro de eliminar al usuario "${userName}"?`)) {
        return;
    }
    
    showLoadingModal('Eliminando usuario...');
    
    $.ajax({
        url: 'Includes/Handler.php',
        method: 'POST',
        data: {
            action: 'delete_user',
            user_id: userId
        },
        dataType: 'json',
        success: function(response) {
            hideLoadingModal();
            
            if (response.success) {
                showNotification(response.message, 'success');
                loadAllUsers();
                loadUsersForAdmin();
            } else {
                showNotification(response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            hideLoadingModal();
            console.error('Error al eliminar usuario:', error);
            showNotification('Error de conexión al servidor', 'error');
        }
    });
}

function regenerateQR(userId, userName) {
    if (!confirm(`¿Regenerar código QR para "${userName}"?`)) {
        return;
    }
    
    showLoadingModal('Regenerando código QR...');
    
    $.ajax({
        url: 'Includes/Handler.php',
        method: 'POST',
        data: {
            action: 'regenerate_qr',
            user_id: userId
        },
        dataType: 'json',
        success: function(response) {
            hideLoadingModal();
            
            if (response.success) {
                showNotification('Código QR regenerado exitosamente', 'success');
                loadAllUsers();
                loadUsersForAdmin();
            } else {
                showNotification(response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            hideLoadingModal();
            console.error('Error al regenerar QR:', error);
            showNotification('Error de conexión al servidor', 'error');
        }
    });
}

// ========== CARGA DE DATOS ==========
function loadAllUsers() {
    $.ajax({
        url: 'Includes/Handler.php',
        method: 'POST',
        data: { action: 'get_users' },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayUsersGrid(response.data);
            } else {
                console.error('Error al cargar usuarios:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error AJAX al cargar usuarios:', error);
        }
    });
}

function loadUsersForAdmin() {
    $.ajax({
        url: 'Includes/Handler.php',
        method: 'POST',
        data: { action: 'get_users' },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayUsersTable(response.data);
            }
        }
    });
}

function loadDashboardStats() {
    $.ajax({
        url: 'Includes/Handler.php',
        method: 'POST',
        data: { action: 'get_stats' },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                updateStatsDisplay(response.data);
            }
        }
    });
}

function loadScanStats() {
    loadDashboardStats(); // Usar la misma función
}

function updateScanStats() {
    loadScanStats();
}

function loadRecentActivity() {
    $.ajax({
        url: 'Includes/Handler.php',
        method: 'POST',
        data: { 
            action: 'get_recent_activity',
            limit: 10
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayRecentActivity(response.data);
            }
        }
    });
}

function loadRecentScans() {
    loadRecentActivity(); // Usar la misma función
}

// ========== FUNCIONES DE VISUALIZACIÓN ==========
function displayUsersGrid(users) {
    const container = $('#users-grid');
    if (container.length === 0) return;
    
    container.empty();
    
    if (!users || users.length === 0) {
        container.html('<p class="no-data">No hay usuarios registrados</p>');
        return;
    }
    
    users.forEach(user => {
        const userCard = $(`
            <div class="user-card">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-info">
                    <h4>${user.nombre}</h4>
                    <p>${user.email}</p>
                    <span class="role-badge ${user.rol}">${user.rol}</span>
                </div>
                <div class="user-qr">
                    <div id="qr-${user.id}" class="qr-code"></div>
                    <p class="qr-text">${user.codigo_qr}</p>
                </div>
            </div>
        `);
        
        container.append(userCard);
        
        // Generar código QR
        if (user.codigo_qr && typeof QRCode !== 'undefined') {
            new QRCode(document.getElementById(`qr-${user.id}`), {
                text: user.codigo_qr,
                width: 100,
                height: 100
            });
        }
    });
}

function displayUsersTable(users) {
    const tbody = $('#users-table tbody');
    if (tbody.length === 0) return;
    
    tbody.empty();
    
    if (!users || users.length === 0) {
        tbody.html('<tr><td colspan="6" class="text-center">No hay usuarios registrados</td></tr>');
        return;
    }
    
    users.forEach(user => {
        const row = $(`
            <tr>
                <td>${user.nombre}</td>
                <td>${user.email}</td>
                <td><span class="role-badge ${user.rol}">${user.rol}</span></td>
                <td><code>${user.codigo_qr}</code></td>
                <td>
                    <span class="status-badge ${user.activo ? 'active' : 'inactive'}">
                        ${user.activo ? 'Activo' : 'Inactivo'}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="editUser('${user.id}')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-info" onclick="regenerateQR('${user.id}', '${user.nombre}')">
                        <i class="fas fa-qrcode"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteUser('${user.id}', '${user.nombre}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
        
        tbody.append(row);
    });
}

function updateStatsDisplay(stats) {
    // Actualizar estadísticas del dashboard
    $('#present-count').text(stats.presentes || 0);
    $('#late-count').text(stats.tardanzas || 0);
    $('#absent-count').text(stats.ausentes || 0);
    
    // También actualizar estadísticas del admin si existen
    $('#admin-present-count').text(stats.presentes || 0);
    $('#admin-late-count').text(stats.tardanzas || 0);
    $('#admin-absent-count').text(stats.ausentes || 0);
    $('#admin-total-count').text(stats.total || 0);
}

function displayRecentActivity(activities) {
    const container = $('#recent-scans-list');
    if (container.length === 0) return;
    
    container.empty();
    
    if (!activities || activities.length === 0) {
        container.html('<p class="no-data">No hay registros recientes</p>');
        return;
    }
    
    activities.forEach(activity => {
        const statusIcon = {
            'presente': 'fa-check-circle text-success',
            'tardanza': 'fa-clock text-warning',
            'ausente': 'fa-times-circle text-danger'
        }[activity.estado] || 'fa-question-circle';
        
        const item = $(`
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas ${statusIcon}"></i>
                </div>
                <div class="activity-info">
                    <h5>${activity.usuarios?.nombre || 'Usuario'}</h5>
                    <p>${activity.estado} - ${activity.hora || ''}</p>
                    <small>${activity.fecha || ''}</small>
                </div>
            </div>
        `);
        
        container.append(item);
    });
}

// ========== FUNCIONES DE UTILIDAD ==========
function showNotification(message, type = 'info') {
    // Crear notificación si no existe el sistema
    const notification = $(`
        <div class="notification ${type}">
            <span>${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `);
    
    // Agregar al body
    $('body').append(notification);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        notification.fadeOut(() => notification.remove());
    }, 5000);
    
    // Permitir cerrar manualmente
    notification.find('.notification-close').on('click', function() {
        notification.fadeOut(() => notification.remove());
    });
}

function showLoadingModal(message = 'Cargando...') {
    const modal = $(`
        <div id="loading-modal" class="modal">
            <div class="modal-content loading-content">
                <div class="loading-spinner"></div>
                <p>${message}</p>
            </div>
        </div>
    `);
    
    $('body').append(modal);
    modal.show();
}

function hideLoadingModal() {
    $('#loading-modal').remove();
}

// ========== PANEL DE ADMINISTRADOR ==========
function initializeAdminPanel() {
    // Inicializar filtros de historial
    $('#history-filter-btn').on('click', loadAttendanceHistory);
    
    // Inicializar exportación
    $('#export-btn').on('click', exportData);
    
    // Inicializar configuración
    $('#save-config').on('click', saveSystemConfig);
    
    // Marcar ausencias masivas
    $('#mark-absent-btn').on('click', markAllAbsent);
}

function loadAttendanceHistory() {
    const filters = {
        action: 'get_attendance_history',
        date: $('#filter-date').val(),
        status: $('#filter-status').val(),
        date_start: $('#filter-date-start').val(),
        date_end: $('#filter-date-end').val()
    };
    
    $.ajax({
        url: 'Includes/Handler.php',
        method: 'POST',
        data: filters,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayAttendanceHistory(response.data);
            }
        }
    });
}

function displayAttendanceHistory(records) {
    const tbody = $('#attendance-history-table tbody');
    tbody.empty();
    
    if (!records || records.length === 0) {
        tbody.html('<tr><td colspan="5" class="text-center">No hay registros</td></tr>');
        return;
    }
    
    records.forEach(record => {
        const statusClass = {
            'presente': 'success',
            'tardanza': 'warning',
            'ausente': 'danger'
        }[record.estado] || 'secondary';
        
        const row = $(`
            <tr>
                <td>${record.usuarios?.nombre || 'N/A'}</td>
                <td>${record.fecha}</td>
                <td>${record.hora || 'N/A'}</td>
                <td><span class="badge badge-${statusClass}">${record.estado}</span></td>
                <td>${record.registrado_en || 'N/A'}</td>
            </tr>
        `);
        
        tbody.append(row);
    });
}

function exportData() {
    const exportData = {
        action: 'export_data',
        format: $('#export-format').val(),
        start_date: $('#export-start-date').val(),
        end_date: $('#export-end-date').val(),
        type: $('#export-type').val()
    };
    
    if (!exportData.start_date || !exportData.end_date) {
        showNotification('Por favor, seleccione las fechas de inicio y fin', 'warning');
        return;
    }
    
    showLoadingModal('Generando archivo...');
    
    $.ajax({
        url: 'Includes/Handler.php',
        method: 'POST',
        data: exportData,
        dataType: 'json',
        success: function(response) {
            hideLoadingModal();
            
            if (response.success) {
                // Crear y descargar archivo
                if (response.format === 'csv') {
                    downloadCSV(response.data, response.filename);
                } else {
                    showNotification('Formato de exportación no implementado aún', 'info');
                }
            } else {
                showNotification(response.message, 'error');
            }
        },
        error: function() {
            hideLoadingModal();
            showNotification('Error al exportar datos', 'error');
        }
    });
}

function downloadCSV(csvData, filename) {
    const blob = new Blob([csvData], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
    
    showNotification('Archivo descargado exitosamente', 'success');
}

function loadSystemConfig() {
    $.ajax({
        url: 'Includes/Handler.php',
        method: 'POST',
        data: { action: 'get_config' },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.data) {
                const config = response.data;
                $('#org-name').val(config.nombre_organizacion || '');
                $('#time-limit').val(config.hora_limite ? config.hora_limite.substring(0, 5) : '09:00');
                $('#timezone').val(config.zona_horaria || 'America/Mexico_City');
                $('#email-notifications').val(config.notificaciones_email || 'none');
            }
        }
    });
}

function saveSystemConfig() {
    const configData = {
        action: 'update_config',
        nombre_organizacion: $('#org-name').val(),
        hora_limite: $('#time-limit').val(),
        zona_horaria: $('#timezone').val(),
        notificaciones_email: $('#email-notifications').val()
    };
    
    showLoadingModal('Guardando configuración...');
    
    $.ajax({
        url: 'Includes/Handler.php',
        method: 'POST',
        data: configData,
        dataType: 'json',
        success: function(response) {
            hideLoadingModal();
            
            if (response.success) {
                showNotification('Configuración guardada exitosamente', 'success');
            } else {
                showNotification(response.message, 'error');
            }
        },
        error: function() {
            hideLoadingModal();
            showNotification('Error al guardar configuración', 'error');
        }
    });
}

function markAllAbsent() {
    if (!confirm('¿Marcar como ausentes a todos los usuarios que no han registrado asistencia hoy?')) {
        return;
    }
    
    showLoadingModal('Marcando ausencias...');
    
    $.ajax({
        url: 'Includes/Handler.php',
        method: 'POST',
        data: { 
            action: 'mark_all_absent',
            date: new Date().toISOString().split('T')[0]
        },
        dataType: 'json',
        success: function(response) {
            hideLoadingModal();
            
            if (response.success) {
                showNotification(response.message, 'success');
                loadDashboardStats();
                loadRecentActivity();
            } else {
                showNotification(response.message, 'error');
            }
        },
        error: function() {
            hideLoadingModal();
            showNotification('Error al marcar ausencias', 'error');
        }
    });
}

// Exponer funciones globalmente para uso en HTML
window.editUser = editUser;
window.deleteUser = deleteUser;
window.regenerateQR = regenerateQR;