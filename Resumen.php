<div class="dashboard-content">
                    <!-- Tab de Resumen -->
                    <div id="overview" class="tab-content active">
                        <h2>Resumen de Asistencia</h2>
                        <p>Estadísticas de asistencia del día de hoy.</p>
                        
                        <div class="stats">
                            <div class="stat-card stat-present">
                                <div class="stat-icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 id="present-count">0</h3>
                                    <p>Presentes</p>
                                </div>
                            </div>
                            <div class="stat-card stat-absent">
                                <div class="stat-icon">
                                    <i class="fas fa-user-times"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 id="absent-count">0</h3>
                                    <p>Ausentes</p>
                                </div>
                            </div>
                            <div class="stat-card stat-total">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 id="total-count">0</h3>
                                    <p>Total</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h3>Actividad Reciente</h3>
                            </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Estado</th>
                                        <th>Hora</th>
                                    </tr>
                                </thead>
                                <tbody id="recent-activity">
                                    <!-- Se llenará dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>