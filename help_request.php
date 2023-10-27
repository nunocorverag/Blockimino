<?php
include("includes/header.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
require "/home4/blockimi/public_html/PHPMailer/src/Exception.php";
require "/home4/blockimi/public_html/PHPMailer/src/PHPMailer.php";
require "/home4/blockimi/public_html/PHPMailer/src/SMTP.php";

$query_comprobar_usuario_normal = mysqli_query($con, "SELECT * FROM usuarios WHERE (id_usuario='$id_usuario_loggeado' AND (tipo='normal' OR tipo='moderador'))");
if((mysqli_num_rows($query_comprobar_usuario_normal) == 0))
{
    header("Location: home.php");
}

if(isset($_POST['enviar_peticion']))
{
    $razon_peticion = $_POST['razon_peticion'];
    $contenido_peticion = $_POST['contenido_peticion'];

    $subida_exitosa = 1;
    $nombre_imagen = $_FILES['imagenASubir']['name'];
    $mensaje_de_error = "";

    if($nombre_imagen != "")
    {
        $directorio_destino = "assets/images/help_images/";
        // $uniqid -> Genera un id unico por si dos personas suben el archivo con el mismo nombre
        // $basename -> Va a ser la extension de la imagen .jpg, .png
        $nombre_imagen = $directorio_destino . uniqid() . "_" . basename($nombre_imagen);
        $tipoArchivoImagen = pathinfo($nombre_imagen, PATHINFO_EXTENSION);

        // + Checamos el tamaño en bytes, el maximo sera 
        if($_FILES['imagenASubir']['size'] > 10000000)
        {
            $mensaje_de_error = "Tu archivo es demasiado pesado, no se pudo completar la publicación";
            $subida_exitosa = 0;
        }

        if(strtolower($tipoArchivoImagen) != "jpeg" && strtolower($tipoArchivoImagen) != "png" && strtolower($tipoArchivoImagen) != "jpg")
        {
            $mensaje_de_error = "Solo se permiten archivos de tipo: jpeg, jpg o png!";
            $subida_exitosa = 0;
        }

        if($subida_exitosa == 1)
        {
            if(move_uploaded_file($_FILES['imagenASubir']['tmp_name'], $nombre_imagen))
            {
                // + La imagen se subio con exito!
            }
            else
            {
                $subida_exitosa = 0;
            }
        }
    }

    if($subida_exitosa == 1)
    {
        $query_insertar_peticion_ayuda = mysqli_query($con, "INSERT INTO peticiones_de_ayuda VALUES ('', '$id_usuario_loggeado', '$razon_peticion', '$contenido_peticion', '$nombre_imagen','no')");
        
        $query_obtemer_correo_usuario_loggeado = mysqli_query($con, "SELECT email, username FROM usuarios WHERE id_usuario='$id_usuario_loggeado'");
        $fila_correo_usuario_loggeado = mysqli_fetch_array($query_obtemer_correo_usuario_loggeado);
        $correo_usuario_loggeado = $fila_correo_usuario_loggeado['email'];
        $usuario_mail = $fila_correo_usuario_loggeado['username'];
    
        $query_obtener_correos_usuarios_especiales = mysqli_query($con, "SELECT email FROM usuarios WHERE tipo='administrador' OR tipo='moderador'");

        // + Configuracion de mail en mi dominio
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

        $mail->setFrom("noreply@blockimino.com");

        $subject = "El usuario $usuario_mail ha realizado un nuevo comentario de ayuda en el sistema!";
        $subject .= "Razon: ".$razon_peticion;
        $message .= "Comentario: ".$contenido_peticion;

        while($fila_correo_usuario_especial = mysqli_fetch_array($query_obtener_correos_usuarios_especiales))
        {
            $correo_usuario_especial = $fila_correo_usuario_especial['email'];
            // Envía el correo
            $mail->addAddress($correo_usuario_especial);
            $mail->Subject = $subject;
            $mail->Body = $message;
            if($nombre_imagen != "")
            {
                $mail->addAttachment($nombre_imagen);
            }
            $mail->send();
            $mail->clearAddresses();
        }
        header("Location: help.php");
    }
    else
    {
        echo "<div class='alert alert-danger' style='text-align:center;'>
                $mensaje_de_error
            </div>";
    }}

?>

    <div class="cabecera_ayuda">
    <h4>Ayuda</h4>
    Escriba un comentario informando acerca de un problema o experiencia o una propuesta para ayudarnos a mejorar
    </div>
    <br>
    <br>

    <div class="cuerpo_ayuda">
        <div class="area_ayuda">
            <form class="formulario_ayuda" action="help_request.php" method="POST" enctype="multipart/form-data">
                <div class="razon_peticion_container">
                    <select name="razon_peticion" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="problema">Problema</option>
                        <option value="experienia">Experiencia</option>
                        <option value="propuesta">Propuesta</option>
                    </select>
                    <div class="icono_imagen_container">
                        <input type="file" name="imagenASubir" id="imagenASubir" style="display:none">
                        <i class="fa-regular fa-image" id="icono_imagen">
                            <span class="tooltip" id="tooltip"></span>
                        </i>
                        <span class="palomita">
                            <i class="fa-solid fa-check"></i>
                        </span>
                    </div>
                </div>
                <br>
                <div class="contenido_peticion_container">
                    <textarea name="contenido_peticion" id="contenido_peticion" placeholder="Petición" required></textarea>
                </div>
                <input type="submit" name="enviar_peticion" id="boton_enviar_comentario" value="Enviar petición">
            </form>
        </div>

        <script>
            // ! explicar este script
            const inputFile = document.getElementById("imagenASubir");
            const iconoImagen = document.getElementById("icono_imagen");
            const palomita = document.querySelector(".palomita");
            const tooltip = iconoImagen.querySelector(".tooltip");

            iconoImagen.onclick = function() {
                inputFile.click();
            }

            inputFile.onchange = function() {
                if (inputFile.files && inputFile.files[0]) {
                    palomita.classList.add("activo");
                    tooltip.innerHTML = inputFile.files[0].name;
                    tooltip.style.visibility = "visible";
                    tooltip.style.opacity = 1; // Set opacity to 1
                } else {
                    palomita.classList.remove("activo");
                    tooltip.style.visibility = "hidden";
                    tooltip.style.opacity = 0; // Set opacity to 0
                }
            }
        </script>    
    </div>
</div>