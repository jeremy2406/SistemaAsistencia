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
            fetch('Componentes/Handler.php', {
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
                fetch('Componentes/Handler.php', {
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
        usersTable.innerHTML = '<tr><td colspan="5">Cargando usuarios...</td></tr>';

        fetch('Componentes/Handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'action': 'get_users'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && Array.isArray(data.users)) {
                usersTable.innerHTML = '';
                data.users.forEach(user => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.role}</td>
                        <td>
                            <img src="Componentes/qr.php?code=${encodeURIComponent(user.qr_code)}" alt="QR" width="40">
                        </td>
                        <td>
                            <button class="btn btn-danger btn-sm delete-user" data-id="${user.id}"><i class="fas fa-trash"></i></button>
                        </td>
                    `;
                    usersTable.appendChild(row);
                });

                // Botón eliminar usuario
                document.querySelectorAll('.delete-user').forEach(btn => {
                    btn.addEventListener('click', function() {
                        if (confirm('¿Seguro que deseas eliminar este usuario?')) {
                            const userId = this.getAttribute('data-id');
                            fetch('Componentes/Handler.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: new URLSearchParams({
                                    'action': 'delete_user',
                                    'user_id': userId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    loadUsersTable();
                                } else {
                                    alert('Error: ' + data.message);
                                }
                            })
                            .catch(error => {
                                alert('Error de conexión: ' + error);
                            });
                        }
                    });
                });

            } else {
                usersTable.innerHTML = '<tr><td colspan="5">No se encontraron usuarios.</td></tr>';
            }
        })
        .catch(error => {
            usersTable.innerHTML = '<tr><td colspan="5">Error al cargar usuarios.</td></tr>';
        });
    }

    /**
     * Historial de asistencia
     */
    function initAttendanceHistory() {
        loadAttendanceHistory();
    }

    function loadAttendanceHistory() {
        const historyTable = document.querySelector('#attendance-history-table tbody');
        if (!historyTable) return;

        historyTable.innerHTML = '<tr><td colspan="4">Cargando historial...</td></tr>';

        fetch('Componentes/Handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'action': 'get_attendance_history'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && Array.isArray(data.history)) {
                historyTable.innerHTML = '';
                data.history.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.user}</td>
                        <td>${item.date}</td>
                        <td>${item.time}</td>
                        <td>${item.status}</td>
                    `;
                    historyTable.appendChild(row);
                });
            } else {
                historyTable.innerHTML = '<tr><td colspan="4">No hay registros.</td></tr>';
            }
        })
        .catch(error => {
            historyTable.innerHTML = '<tr><td colspan="4">Error al cargar historial.</td></tr>';
        });
    }

    /**
     * Exportar historial
     */
    function initExportForm() {
        const exportForm = document.getElementById('export-form');
        if (!exportForm) return;

        exportForm.addEventListener('submit', function(e) {
            e.preventDefault();
            exportForm.submit(); // Puede ser reemplazado por lógica AJAX si lo deseas
        });
    }

    /**
     * Dashboard
     */
    function loadDashboardStats() {
        const statsContainer = document.getElementById('dashboard-stats');
        if (!statsContainer) return;

        statsContainer.innerHTML = 'Cargando estadísticas...';

        fetch('Componentes/Handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'action': 'get_dashboard_stats'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statsContainer.innerHTML = `
                    <div class="stat"><strong>${data.total_users}</strong><span>Usuarios</span></div>
                    <div class="stat"><strong>${data.present_today}</strong><span>Presentes hoy</span></div>
                    <div class="stat"><strong>${data.late_today}</strong><span>Tardanzas hoy</span></div>
                `;
            } else {
                statsContainer.innerHTML = 'No se pudieron cargar las estadísticas.';
            }
        })
        .catch(error => {
            statsContainer.innerHTML = 'Error al cargar estadísticas.';
        });
    }

    function loadRecentActivity() {
        const activityContainer = document.getElementById('recent-activity');
        if (!activityContainer) return;

        activityContainer.innerHTML = 'Cargando actividad...';

        fetch('Componentes/Handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'action': 'get_recent_activity'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && Array.isArray(data.activity)) {
                activityContainer.innerHTML = '';
                data.activity.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'activity-item';
                    div.innerHTML = `<strong>${item.user}</strong> - ${item.status} a las ${item.time}`;
                    activityContainer.appendChild(div);
                });
            } else {
                activityContainer.innerHTML = 'No hay actividad reciente.';
            }
        })
        .catch(error => {
            activityContainer.innerHTML = 'Error al cargar actividad.';
        });
    }

    function loadAttendeesList() {
        const attendeesTable = document.querySelector('#attendees-table tbody');
        if (!attendeesTable) return;

        attendeesTable.innerHTML = '<tr><td colspan="3">Cargando asistentes...</td></tr>';

        fetch('Componentes/Handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'action': 'get_attendees'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && Array.isArray(data.attendees)) {
                attendeesTable.innerHTML = '';
                data.attendees.forEach(att => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${att.name}</td>
                        <td>${att.email}</td>
                        <td>${att.status}</td>
                    `;
                    attendeesTable.appendChild(row);
                });
            } else {
                attendeesTable.innerHTML = '<tr><td colspan="3">No hay asistentes.</td></tr>';
            }
        })
        .catch(error => {
            attendeesTable.innerHTML = '<tr><td colspan="3">Error al cargar asistentes.</td></tr>';
        });
    }

});
