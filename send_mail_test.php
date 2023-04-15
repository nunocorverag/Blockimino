<?php
include("includes/header.php");

if(isset($_POST['enviar'])) 
{
    //Datos del remitente
    $header = "From: uStore <uStore@gmail.com>";

    // Información del correo electrónico del administrador
    $email_administrador = "gnuno2003@gmail.com";
    $asunto = "Clave de registro";
    $contenido = "Hola";
 
    // Enviar el correo electrónico utilizando la función mail()
    mail($email_administrador, $asunto, $contenido, $header);

  echo "Correo electrónico enviado.";
}

?>
<form action="send_mail_test.php" method="POST">
    <input type="submit" name="enviar">
</form>
