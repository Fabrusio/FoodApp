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

$file = fopen("../../config/Config.txt", "r") or die("No se pudo abrir el archivo.");
while(!feof($file)){
  $line = fgets($file);
  $config = explode("|", $line);
  $appEmail = $config[0];
  $password = $config[1];

}
fclose($file);

function validarEmail($email) {
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return false;
  }
  // Verificar si el dominio del correo electrónico tiene registros MX
  list($username, $domain) = explode('@', $email);
  return checkdnsrr($domain, 'MX');
}

if (isset($_POST['email']) && isset($_FILES['pdf'])) {
  $emailCliente = trim($_POST['email']);

  if (!validarEmail($emailCliente)) {
    echo json_encode(['success' => false, 'message' => 'El correo electrónico ingresado no es válido.']);
    exit; // Salir del script si el correo electrónico no es válido
  }


  $archivoAdjunto = $_FILES['pdf']['tmp_name']; 
  $nombreArchivo = $_FILES['pdf']['name'];

  $mail = new PHPMailer(true);

      $mail->SMTPDebug = 0;                      // Enable verbose debug output (remove for production)
      $mail->isSMTP();                                            // Send using SMTP
      $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
      $mail->Username   = $appEmail;                     // SMTP username
      $mail->Password   = $password;                               // SMTP password
      $mail->SMTPSecure = 'tls';            // Enable implicit TLS encryption
      $mail->Port       = 587;                                    // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

      // Recipients
      $mail->setFrom($appEmail, 'AppFoodSoporte');
      $mail->addAddress($emailCliente);     // Add a recipient

      // Attachments
      $mail->addAttachment($archivoAdjunto, $nombreArchivo);         // Add the uploaded PDF

      // Content
      $mail->isHTML(true);
      $mail->Subject = 'Comprobante de venta';
      $mail->Body    = 'Hola, aquí te enviamos el comprobante de tu compra en formato PDF.';

      try {
        $mail->send();
        echo json_encode("Correo electronico enviado correctamente. Por favor, vuelva hacia atras para acceder a la APP.");
    } catch (Exception $e) {
      echo json_encode(['Hubo un error al enviar el correo electronico: ' . $mail->ErrorInfo . ' Por favor, vuelva hacia atras para acceder a la APP.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos en la solicitud']);
}
?>
