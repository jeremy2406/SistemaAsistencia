   <!-- Tab de Usuarios -->
                    <div id="users" class="tab-content">
                        <h2>Gestión de Usuarios</h2>
                        
                        <div class="card">
                            <div class="card-header">
                                <h3>Agregar Nuevo Usuario</h3>
                            </div>
                            <form id="add-user-form">
                                <div class="form-group">
                                    <label for="user-name">Nombre Completo</label>
                                    <input type="text" id="user-name" required>
                                </div>
                                <div class="form-group">
                                    <label for="user-email">Correo Electrónico</label>
                                    <input type="email" id="user-email" required>
                                </div>
                                <div class="form-group">
                                    <label for="user-role">Rol</label>
                                    <select id="user-role">
                                        <option value="student">Estudiante</option>
                                        <option value="teacher">Profesor</option>
                                        <option value="staff">Personal</option>
                                        <option value="admin">Administrador</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Agregar Usuario
                                </button>
                            </form>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h3>Lista de Usuarios</h3>
                                <div class="search-bar">
                                    <input type="text" id="user-search" placeholder="Buscar usuario...">
                                    <button class="btn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <table id="users-table">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Rol</th>
                                        <th>Código QR</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Se llenará dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>