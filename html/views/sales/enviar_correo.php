<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

error_log("LLEGO A ENTRAR A ENVIAR CORREO, VEMOS SI ANDA O NO ");

// Obtener los datos enviados a través de la solicitud POST
$rutaPDF = $_POST['rutaPDF'];
$correoDestinatario = isset($_POST['correoDestinatario']) ? $_POST['correoDestinatario'] : "";
error_log($correoDestinatario);

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'appfoodsoporte@gmail.com';
$mail->Password = 'fabriziolucasgonzalo';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

// Configurar el remitente
$mail->setFrom('appfoodsoporte@gmail.com', 'AppFoodSoporte');

// Agregar el destinatario
$mail->addAddress($correoDestinatario);

// Configurar el asunto y el cuerpo del mensaje
$mail->Subject = 'Comprobante de venta';
$mail->Body = 'Hola, aquí te enviamos el comprobante de tu compra en formato PDF.';

// Adjuntar el archivo PDF
//$mail->addAttachment($rutaPDF); // Adjuntamos el archivo PDF generado

// Enviar el correo electrónico
if (!$mail->send()) {
  echo 'enviar_correo.php===> Error al enviar el correo electrónico: ' . $mail->ErrorInfo;
} else {
  echo 'enviar_correo.php===> Correo electrónico enviado correctamente.';
}
?>
