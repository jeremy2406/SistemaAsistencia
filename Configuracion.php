 <!-- Tab de Configuración -->
                    <div id="settings" class="tab-content">
                        <h2>Configuración del Sistema</h2>
                        
                        <div class="card">
                            <div class="card-header">
                                <h3>Configuración General</h3>
                            </div>
                            <form id="settings-form">
                                <div class="form-group">
                                    <label for="setting-name">Nombre de la Organización</label>
                                    <input type="text" id="setting-name" value="Mi Organización">
                                </div>
                                <div class="form-group">
                                    <label for="setting-time-limit">Hora límite para marcar tardanza (HH:MM)</label>
                                    <input type="time" id="setting-time-limit" value="09:00">
                                </div>
                                <div class="form-group">
                                    <label for="setting-timezone">Zona Horaria</label>
                                    <select id="setting-timezone">
                                        <option value="America/Mexico_City">Ciudad de México (GMT-6)</option>
                                        <option value="America/Bogota">Bogotá (GMT-5)</option>
                                        <option value="America/Santiago">Santiago (GMT-4)</option>
                                        <option value="America/Buenos_Aires">Buenos Aires (GMT-3)</option>
                                        <option value="Europe/Madrid">Madrid (GMT+1)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="setting-notifications">Notificaciones por Correo</label>
                                    <select id="setting-notifications">
                                        <option value="all">Todas las asistencias</option>
                                        <option value="absence">Solo ausencias</option>
                                        <option value="none">Desactivadas</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Configuración
                                </button>
                            </form>
                        </div>
                    </div>