<div class="admin-content" id="attendance-history">
    <div class="history-header">
        <h2>Historial de Asistencia</h2>
    </div>
    
    <!-- Filtros -->
    <div class="history-filters">
        <div class="filter-row">
            <div class="filter-group">
                <label for="history-date">Fecha Específica:</label>
                <input type="date" id="history-date" class="form-control">
            </div>
            
            <div class="filter-group">
                <label for="history-status">Estado:</label>
                <select id="history-status" class="form-select">
                    <option value="all">Todos</option>
                    <option value="presente">Presente</option>
                    <option value="tardanza">Tardanza</option>
                    <option value="ausente">Ausente</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="history-role">Rol:</label>
                <select id="history-role" class="form-select">
                    <option value="all">Todos</option>
                    <option value="estudiante">Estudiante</option>
                    <option value="profesor">Profesor</option>
                    <option value="personal">Personal</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>
        </div>
        
        <div class="filter-row">
            <div class="filter-group">
                <label for="history-date-start">Desde:</label>
                <input type="date" id="history-date-start" class="form-control">
            </div>
            
            <div class="filter-group">
                <label for="history-date-end">Hasta:</label>
                <input type="date" id="history-date-end" class="form-control">
            </div>
            
            <div class="filter-actions">
                <button id="apply-history-filters" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Aplicar Filtros
                </button>
                <button id="clear-history-filters" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Limpiar
                </button>
            </div>
        </div>
    </div>
    
    <!-- Estadísticas del Período -->
    <div class="period-stats" id="period-stats" style="display: none;">
        <h3>Estadísticas del Período</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-label">Total Registros:</span>
                <span class="stat-value" id="period-total">0</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Presentes:</span>
                <span class="stat-value present" id="period-present">0</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Tardanzas:</span>
                <span class="stat-value late" id="period-late">0</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Ausentes:</span>
                <span class="stat-value absent" id="period-absent">0</span>
            </div>
        </div>
    </div>
    
    <!-- Tabla de Historial -->
    <div class="history-table-container">
        <div class="table-actions">
            <div class="table-info">
                <span id="history-count">Total: 0 registros</span>
            </div>
            <div class="table-controls">
                <button id="refresh-history" class="btn btn-info">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
                <button id="export-history" class="btn btn-success">
                    <i class="fas fa-file-export"></i> Exportar
                </button>
            </div>
        </div>
        
        <table class="history-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="history-list">
                <tr>
                    <td colspan="7" class="loading">Seleccione los filtros y haga clic en "Aplicar Filtros" para cargar los datos</td>
                </tr>
            </tbody>
        </table>
        
        <!-- Paginación -->
        <div class="pagination-container" id="history-pagination" style="display: none;">
            <div class="pagination-info">
                <span id="pagination-info">Mostrando 0 - 0 de 0 registros</span>
            </div>
            <div class="pagination-controls">
                <button id="prev-page" class="btn btn-sm btn-secondary" disabled>
                    <i class="fas fa-arrow-left"></i> Anterior
                </button>
                <span id="current-page-info">Página 1 de 1</span>
                <button id="next-page" class="btn btn-sm btn-secondary" disabled>
                    Siguiente <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modal para editar asistencia -->
    <div id="edit-attendance-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Editar Asistencia</h3>
                <span class="close" id="close-edit-attendance">&times;</span>
            </div>
            <div class="modal-body">
                <form id="edit-attendance-form">
                    <input type="hidden" id="edit-attendance-id">
                    
                    <div class="form-group">
                        <label>Usuario:</label>
                        <span id="edit-user-name" class="form-text"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-attendance-date">Fecha:</label>
                        <input type="date" id="edit-attendance-date" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-attendance-time">Hora:</label>
                        <input type="time" id="edit-attendance-time" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-attendance-status">Estado:</label>
                        <select id="edit-attendance-status" class="form-select" required>
                            <option value="presente">Presente</option>
                            <option value="tardanza">Tardanza</option>
                            <option value="ausente">Ausente</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="cancel-edit-attendance" class="btn btn-secondary">Cancelar</button>
                <button type="submit" form="edit-attendance-form" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>