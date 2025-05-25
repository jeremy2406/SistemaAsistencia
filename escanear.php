<?php include 'componentes/header.php'; ?>
<?php include 'componentes/nav.php'; ?>
<div class="container mx-auto px-4 py-12 text-center">
  <h2 class="text-3xl font-bold mb-4">Escanea tu código QR</h2>
  <p class="mb-6">Utiliza la cámara para escanear tu código QR único y registrar tu asistencia.</p>
  <div id="reader" class="mx-auto w-full max-w-md h-80 border border-gray-300 rounded-md shadow-md"></div>
  <p id="resultado" class="mt-4 text-lg font-semibold text-green-600"></p>
</div>
<script src="https://unpkg.com/html5-qrcode@2.3.7/html5-qrcode.min.js"></script>
<script>
  const qrReader = new Html5Qrcode("reader");
  qrReader.start({ facingMode: "environment" }, {
    fps: 10, qrbox: 250
  }, qrCodeMessage => {
    fetch('/php/registrar_asistencia.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ codigo_qr: qrCodeMessage })
    }).then(res => res.json()).then(data => {
      document.getElementById("resultado").innerText = data.mensaje || "Asistencia registrada.";
    });
    qrReader.stop();
  });
</script>
<?php include 'componentes/footer.php'; ?>
