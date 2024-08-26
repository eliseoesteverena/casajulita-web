<?php

header('Content-Type: application/json');


// Obtén el contenido JSON de la solicitud
$json = file_get_contents('php://input');

$data = json_decode($json, true);

// Asegúrate de que no haya errores al decodificar el JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid JSON input'));
    exit;
}
error_log("Datos decodificados: " . print_r($data, true));

echo sendMail($data);
function sendMail($data) {

    $asunto = "Nuevo mensaje desde tu sitio web";

    $nombre = $data['data_message']['name'];
    $correo = $data['data_message']['email'];
    $mensaje = $data['data_message']['message'];
    $destinatario = "grafica@casajulita.com.ar"; # aquí la persona que recibirá los mensajes
    $encabezados = 'MIME-Version: 1.0' . "\r\n";
    $encabezados .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $encabezados .= "Sender: web@casajulita.com.ar\r\n"; # El remitente, debe ser un correo de tu dominio de servidor
    $encabezados .= "From: $nombre <" . $correo . ">\r\n";
    $encabezados .= "Reply-To: $nombre <$correo>\r\n";
    $resultado = mail($destinatario, $asunto, $mensaje, $encabezados);
    if ($resultado) {
        $exito = "Mensaje enviado, Te responderemos a la brevedad.";
        echo json_encode($exito, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode("Tu mensaje no se ha enviado. Intenta de nuevo.", JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT);
    }
}
?>