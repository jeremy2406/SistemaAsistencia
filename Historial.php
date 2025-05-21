  <!-- Tab de Historial -->
                    <div id="attendance-history" class="tab-content">
                        <h2>Historial de Asistencia</h2>
                        
                        <div class="filters">
                            <div class="filter-group">
                                <label for="date-filter">Fecha:</label>
                                <input type="date" id="date-filter">
                            </div>
                            <div class="filter-group">
                                <label for="status-filter">Estado:</label>
                                <select id="status-filter">
                                    <option value="all">Todos</option>
                                    <option value="present">Presente</option>
                                    <option value="absent">Ausente</option>
                                    <option value="late">Tardanza</option>
                                </select>
                            </div>
                            <button class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                        
                        <div class="attendance-list">
                            <table id="history-table">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Hora de Llegada</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Se llenará dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    