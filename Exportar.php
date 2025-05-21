 <!-- Tab de Exportar Datos -->
                    <div id="export" class="tab-content">
                        <h2>Exportar Datos</h2>
                        <p>Exporta los datos de asistencia en diferentes formatos para su análisis o archivo.</p>
                        
                        <div class="card">
                            <div class="card-header">
                                <h3>Opciones de Exportación</h3>
                            </div>
                            <div class="form-group">
                                <label for="export-date-range">Rango de Fechas</label>
                                <div style="display: flex; gap: 10px;">
                                    <input type="date" id="export-date-start" style="width: 50%;">
                                    <input type="date" id="export-date-end" style="width: 50%;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="export-type">Tipo de Datos</label>
                                <select id="export-type">
                                    <option value="all">Todos los registros</option>
                                    <option value="present">Solo presentes</option>
                                    <option value="absent">Solo ausentes</option>
                                    <option value="late">Solo tardanzas</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="export-format">Formato</label>
                                <select id="export-format">
                                    <option value="csv">CSV</option>
                                    <option value="excel">Excel</option>
                                    <option value="pdf">PDF</option>
                                </select>
                            </div>
                            <div class="actions">
                                <button id="export-btn" class="btn btn-primary">
                                    <i class="fas fa-file-export"></i> Exportar Datos
                                </button>
                            </div>
                        </div>
                    </div>