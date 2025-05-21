/**
 * Sistema de Asistencia QR
 * Script principal
 */

document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    const html5QrCode = new Html5Qrcode("reader");
    let isScanning = false;
    
    // Inicializar componentes
    initNavigation();
    initAdminTabs();
    initQRScanner();
    initUsersTable();
    initAttendanceHistory();
    initExportForm();
    loadDashboardStats();
    
    /**
     * Navegación principal
     */
    function initNavigation() {
        // Navegación principal
        document.querySelectorAll('.nav-link, .scan-btn').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('data-target');
                
                // Ocultar todas las secciones
                document.querySelectorAll('.page').forEach(page => {
                    page.classList.add('hidden');
                });
                
                // Mostrar la sección seleccionada
                document.getElementById(target).classList.remove('hidden');
                
                // Si se selecciona la sección de administrador, cargar datos
                if (target === 'admin') {
                    loadDashboardStats();
                    loadRecentActivity();
                }
                
                // Si se selecciona la sección de asistentes, cargar lista
                if (target === 'attendees') {
                    loadAttendeesList();
                }
            });
        });
        
        // Menú móvil
        document.querySelector('.mobile-menu-icon').addEventListener('click', function() {
            document.querySelector('.nav-links').classList.toggle('active');
        });
    }
    
    /**
     * Pestañas del panel de administrador
     */
    function initAdminTabs() {
        document.querySelectorAll('.admin-tab').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('data-tab');
                
                // Quitar clase activa de todas las pestañas
                document.querySelectorAll('.admin-tab').forEach(t => {
                    t.classList.remove('active');
                });
                
                // Ocultar todos los contenidos de pestañas
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                // Activar la pestaña seleccionada
                this.classList.add('active');
                document.getElementById(target).classList.add('active');
                
                // Cargar datos específicos según la pestaña
                if (target === 'overview') {
                    loadDashboardStats();
                    loadRecentActivity();
                } else if (target === 'attendance-history') {
                    loadAttendanceHistory();
                } else if (target === 'users') {
                    loadUsersTable();
                }
            });
        });
    }
    
    /**
     * Escáner de códigos QR
     */
    function initQRScanner() {
        const startScannerBtn = document.getElementById('start-scanner');
        const stopScannerBtn = document.getElementById('stop-scanner');
        const scanResult = document.getElementById('scan-result');
        const scanMessages = document.getElementById('scan-messages');
        
        startScannerBtn.addEventListener('click', function() {
            if (!isScanning) {
                // Configuración del escáner
                const config = { fps: 10, qrbox: 250 };
                
                // Iniciar escáner
                html5QrCode.start(
                    { facingMode: "environment" }, 
                    config,
                    onScanSuccess,
                    onScanFailure
                )
                .then(() => {
                    isScanning = true;
                    startScannerBtn.classList.add('hidden');
                    stopScannerBtn.classList.remove('hidden');
                })
                .catch(err => {
                    showScanMessage('error', 'Error al iniciar la cámara: ' + err);
                });
            }
        });
        
        stopScannerBtn.addEventListener('click', function() {
            if (isScanning) {
                html5QrCode.stop()
                .then(() => {
                    isScanning = false;
                    stopScannerBtn.classList.add('hidden');
                    startScannerBtn.classList.remove('hidden');
                })
                .catch(err => {
                    console.error('Error al detener el escáner:', err);
                });
            }
        });
        
        // Función que se ejecuta cuando se escanea un código QR
        function onScanSuccess(qrCodeMessage) {
            // Detener el escáner después de un escaneo exitoso
            html5QrCode.stop().catch(err => console.error(err));
            isScanning = false;
            stopScannerBtn.classList.add('hidden');
            startScannerBtn.classList.remove('hidden');
            
            // Enviar el código QR al servidor
            processScan(qrCodeMessage);
        }
        
        function onScanFailure(error) {
            // No hacer nada en caso de error (es normal durante el escaneo)
        }
        
        function processScan(qrCode) {
            // Mostrar mensaje de carga
            showScanMessage('info', 'Procesando código QR...');
            
            // Enviar solicitud al servidor
            fetch('ajax/handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'action': 'scan_qr',
                    'qr_code': qrCode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    const statusText = data.status === 'presente' ? 'Presente' : 'Tardanza';
                    const statusClass = data.status === 'presente' ? 'success' : 'warning';
                    
                    showScanMessage(
                        statusClass, 
                        `<strong>${data.user}</strong> - Asistencia registrada: <span class="status status-${data.status}">${statusText}</span> a las ${data.time}`
                    );
                    
                    // Actualizar dashboard si está visible
                    if (!document.getElementById('admin').classList.contains('hidden')) {
                        loadDashboardStats();
                        loadRecentActivity();
                    }
                    
                    // Actualizar lista de asistentes si está visible
                    if (!document.getElementById('attendees').classList.contains('hidden')) {
                        loadAttendeesList();
                    }
                } else {
                    // Mostrar mensaje de error
                    showScanMessage('error', data.message);
                }
            })
            .catch(error => {
                showScanMessage('error', 'Error de conexión: ' + error);
            });
        }
        
        function showScanMessage(type, message) {
            // Crear elemento de mensaje
            const messageElement = document.createElement('div');
            messageElement.className = `message message-${type}`;
            messageElement.innerHTML = message;
            
            // Limpiar mensajes anteriores
            scanMessages.innerHTML = '';
            
            // Agregar nuevo mensaje
            scanMessages.appendChild(messageElement);
            
            // Desplazarse hacia el mensaje
            messageElement.scrollIntoView({ behavior: 'smooth' });
        }
    }
    
    /**
     * Gestión de usuarios
     */
    function initUsersTable() {
        const addUserForm = document.getElementById('add-user-form');
        const userSearchInput = document.getElementById('user-search');
        
        // Cargar la tabla de usuarios
        loadUsersTable();
        
        // Manejar el formulario de agregar usuario
        if (addUserForm) {
            addUserForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const name = document.getElementById('user-name').value;
                const email = document.getElementById('user-email').value;
                const role = document.getElementById('user-role').value;
                
                // Validar campos
                if (!name || !email) {
                    alert('Por favor, complete todos los campos obligatorios.');
                    return;
                }
                
                // Enviar datos al servidor
                fetch('ajax/handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        'action': 'add_user',
                        'name': name,
                        'email': email,
                        'role': role
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Limpiar formulario
                        addUserForm.reset();
                        
                        // Actualizar tabla
                        loadUsersTable();
                        
                        // Mostrar mensaje
                        alert('Usuario agregado correctamente.');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error de conexión: ' + error);
                });
            });
        }
        
        // Filtrar usuarios por búsqueda
        if (userSearchInput) {
            userSearchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const userRows = document.querySelectorAll('#users-table tbody tr');
                
                userRows.forEach(row => {
                    const name = row.querySelector('td:first-child').textContent.toLowerCase();
                    const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    
                    if (name.includes(searchTerm) || email.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    }
    
    function loadUsersTable() {
        const usersTable = document.querySelector('#users-table tbody');
        
        if (!usersTable) return;
        
        // Mostrar indicador de carga
        usersTable.innerHTML = '<tr><td colspan="5">Cargando usuarios...