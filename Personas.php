 <!-- Lista de Personas -->
        <section id="attendees" class="page hidden">
            <h1>Lista de Personas</h1>
            <p>Aquí puedes ver el estado de asistencia de todos los usuarios registrados en el sistema.</p>
            
            <div class="search-bar">
                <input type="text" id="attendee-search" placeholder="Buscar por nombre...">
                <button class="btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            <div class="attendance-list">
                <table id="attendees-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Hora de Llegada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Se llenará dinámicamente -->
                    </tbody>
                </table>
            </div>
        </section>
