<!-- Página de Lista de Personas -->
<section id="attendees" class="page hidden">
    <h1>Lista de Personas</h1>
    
    <div class="attendees-controls">
        <div class="search-section">
            <div class="search-bar">
                <input type="text" id="search-users" placeholder="Buscar por nombre o correo..." class="search-input">
                <button id="search-btn" class="btn btn-primary">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
            <div class="filter-section">
                <select id="role-filter" class="form-select">
                    <option value="">Todos los roles</option>
                    <option value="estudiante">Estudiante</option>
                    <option value="profesor">Profesor</option>
                    <option value="personal">Personal</option>
                    <option value="administrador">Administrador</option>
                </select>
                <select id="status-filter" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="activo">Activos</option>
                    <option value="inactivo">Inactivos</option>
                </select>
            </div>
        </div>
        
        <div class="action-buttons">
            <button id="add-person-btn" class="btn btn-success">
                <i class="fas fa-plus"></i> Agregar Persona
            </button>
            <button id="refresh-list-btn" class="btn btn-info">
                <i class="fas fa-sync-alt"></i> Actualizar
            </button>
        </div>
    </div>
    
    <div class="attendees-table-container">
        <table class="attendees-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Código QR</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="attendees-list">
                <tr>
                    <td colspan="6" class="loading">Cargando personas...</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Modal para agregar/editar persona -->
    <div id="person-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="person-modal-title">Agregar Persona</h3>
                <span class="close" id="close-person-modal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="person-form">
                    <input type="hidden" id="person-id" value="">
                    
                    <div class="form-group">
                        <label for="person-name">Nombre Completo *</label>
                        <input type="text" id="person-name" name="name" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="person-email">Correo Electrónico *</label>
                        <input type="email" id="person-email" name="email" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="person-role">Rol *</label>
                        <select id="person-role" name="role" required class="form-select">
                            <option value="">Seleccionar rol</option>
                            <option value="estudiante">Estudiante</option>
                            <option value="profesor">Profesor</option>
                            <option value="personal">Personal</option>
                            <option value="administrador">Administrador</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="cancel-person" class="btn btn-secondary">Cancelar</button>
                <button type="submit" form="person-form" id="save-person" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
    
    <!-- Modal para mostrar código QR -->
    <div id="qr-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Código QR</h3>
                <span class="close" id="close-qr-modal">&times;</span>
            </div>
            <div class="modal-body">
                <div class="qr-display">
                    <div id="qr-code-display"></div>
                    <div class="qr-info">
                        <h4 id="qr-user-name"></h4>
                        <p id="qr-code-text"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="download-qr" class="btn btn-success">
                    <i class="fas fa-download"></i> Descargar QR
                </button>
                <button id="print-qr" class="btn btn-info">
                    <i class="fas fa-print"></i> Imprimir QR
                </button>
                <button id="regenerate-qr" class="btn btn-warning">
                    <i class="fas fa-sync-alt"></i> Regenerar QR
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modal de confirmación -->
    <div id="confirm-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmar Acción</h3>
                <span class="close" id="close-confirm-modal">&times;</span>
            </div>
            <div class="modal-body">
                <p id="confirm-message">¿Está seguro que desea realizar esta acción?</p>
            </div>
            <div class="modal-footer">
                <button id="cancel-action" class="btn btn-secondary">Cancelar</button>
                <button id="confirm-action" class="btn btn-danger">Confirmar</button>
            </div>
        </div>
    </div>
</section>