   <!-- Sección de Escaneo -->
        <section id="scan" class="page hidden">
            <h1>Registrar Asistencia</h1>
            <p>Coloca el código QR frente a la cámara para registrar automáticamente la asistencia.</p>
            
            <div class="qr-scanner">
                <div id="reader"></div>
                <div id="scan-result" class="hidden"></div>
                <button id="start-scanner" class="btn btn-primary">
                    <i class="fas fa-camera"></i> Iniciar Escáner
                </button>
                <button id="stop-scanner" class="btn btn-danger hidden">
                    <i class="fas fa-stop"></i> Detener Escáner
                </button>
            </div>
            
            <div id="scan-messages"></div>
        </section>