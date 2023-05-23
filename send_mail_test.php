<?php
require "includes/header.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
require "/home4/blockimi/public_html/PHPMailer/src/Exception.php";
require "/home4/blockimi/public_html/PHPMailer/src/PHPMailer.php";
require "/home4/blockimi/public_html/PHPMailer/src/SMTP.php";

if (isset($_POST["enviar"])) {
    // + Crear una nueva instancia de PHPMailer y configuracion de SMTP
    $mail = new PHPMailer(true);

    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Mailer = "mail";
    $mail->SMTPSecure = "ssl";  
    $mail->Timeout = 10; // Timeout de 10 segundos
    $mail->Host = "mail.blockimino.com";  // STMP server 
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = "noreply@blockimino.com";
    $mail->Password = "Rq#7pW&fX9";


    // Establecer el remitente y el destinatario
    $mail->setFrom("noreply@blockimino.com");
    $mail->addAddress("gnuno2003@gmail.com");

    // Establecer el asunto y el contenido del correo electrónico
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = "Clave de registro";
    $mail->Body = "Hola";

    // Establecer un tiempo de espera (timeout) para el envío del correo electrónico
    $mail->Timeout = 10;  // Timeout en segundos (ejemplo: 30 segundos)

    // Enviar el correo electrónico
    if ($mail->send()) {
        echo "Correo electrónico enviado.";
    } else {
        echo "Error al enviar el correo electrónico: " . $mail->ErrorInfo;
    }
}
?>
<form action="send_mail_test.php" method="POST">
    <input type="submit" name="enviar">
</form>