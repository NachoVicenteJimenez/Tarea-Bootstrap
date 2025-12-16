<?php

$destinatario = "correo-destino@ejemplo.com"; 
$tuCorreo = "tu-correo@ejemplo.com";         
$asuntoFijo = "Nuevo Mensaje de Portafolio Web";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = htmlspecialchars(trim($_POST['entradaNombre']));
    $correo = filter_var(trim($_POST['entradaCorreo']), FILTER_SANITIZE_EMAIL);
    $asunto = htmlspecialchars(trim($_POST['entradaAsunto']));
    $mensaje = htmlspecialchars(trim($_POST['entradaMensaje']));

    if (empty($nombre) || empty($correo) || empty($mensaje) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400); 
        echo "Por favor, completa todos los campos requeridos y asegúrate de que el correo sea válido.";
        exit;
    }

    $encabezados = "MIME-Version: 1.0" . "\r\n";
    $encabezados .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $encabezados .= "From: " . $nombre . " <" . $tuCorreo . ">" . "\r\n";
    $encabezados .= "Reply-To: " . $correo . "\r\n";
    
    $contenidoCorreo = '
    <html>
    <head>
      <title>' . $asuntoFijo . '</title>
    </head>
    <body>
      <h2>Detalles del Contacto</h2>
      <p><strong>Nombre:</strong> ' . $nombre . '</p>
      <p><strong>Correo Electrónico:</strong> ' . $correo . '</p>
      <p><strong>Asunto:</strong> ' . (empty($asunto) ? "Sin Asunto" : $asunto) . '</p>
      <h3>Mensaje:</h3>
      <p>' . nl2br($mensaje) . '</p>
    </body>
    </html>
    ';

    if (mail($destinatario, $asuntoFijo, $contenidoCorreo, $encabezados)) {
        header("Location: confirmacion.html");
        exit;
    } else {
        http_response_code(500); 
        echo "Hubo un error al intentar enviar tu mensaje. Por favor, inténtalo de nuevo más tarde.";
        exit;
    }

} else {
    http_response_code(403); 
    echo "Acceso Denegado.";
    exit;
}
?>