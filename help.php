<?php
include("includes/header.php");

$query_comprobar_usuario_normal = mysqli_query($con, "SELECT * FROM usuarios WHERE (id_usuario='$id_usuario_loggeado' AND tipo='normal')");
if((mysqli_num_rows($query_comprobar_usuario_normal) == 0))
{
    header("Location: home.php");
}

if(isset($_POST['enviar_comentario']))
{
    $query_obtemer_correo_usuario_loggeado = mysqli_query($con, "SELECT email FROM usuarios WHERE id_usuario='$id_usuario_loggeado'");
    $fila_correo_usuario_loggeado = mysqli_fetch_array($query_obtemer_correo_usuario_loggeado);
    $correo_usuario_loggeado = $fila_correo_usuario_loggeado['email'];

    $query_obtener_correos_usuarios_especiales = mysqli_query($con, "SELECT email FROM usuarios WHERE tipo='usuario_especial'");
    while($fila_correo_usuario_especial = mysqli_fetch_array($query_obtener_correos_usuarios_especiales))
    {
        $correo_usuario_especial = $fila_correo_usuario_especial['email'];
        
        // Compose email message
        $subject = "Nuevo comentario en el sistema";
        $message = "Un nuevo comentario ha sido enviado al sistema por el usuario $correo_usuario_loggeado.\n\n";
        $message .= "Razón del comentario: ".$_POST['razon_comentario']."\n\n";
        $message .= "Comentario: ".$_POST['enviar_comentario']."\n\n";
        
        // Optional attachment
        if(isset($_FILES['archivoASubir']) && $_FILES['archivoASubir']['error'] == UPLOAD_ERR_OK) {
            $file = $_FILES['archivoASubir']['tmp_name'];
            $filename = $_FILES['archivoASubir']['name'];
            $filetype = $_FILES['archivoASubir']['type'];
            $filecontent = file_get_contents($file);
            $attachment = chunk_split(base64_encode($filecontent));
            
            $boundary = md5(time());
            $headers = "From: $correo_usuario_loggeado\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
            
            $message = "--$boundary\r\n";
            $message .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
            $message .= "Content-Transfer-Encoding: 7bit\r\n";
            $message .= "\n$message\n";
            $message .= "--$boundary\r\n";
            $message .= "Content-Type: $filetype; name=\"$filename\"\r\n";
            $message .= "Content-Disposition: attachment; filename=\"$filename\"\r\n";
            $message .= "Content-Transfer-Encoding: base64\r\n";
            $message .= "\n$attachment\n";
            $message .= "--$boundary--";
            
            mail($correo_usuario_especial, $subject, $message, $headers);
        } else {
            mail($correo_usuario_especial, $subject, $message, "From: $correo_usuario_loggeado");
        }
    }

}

?>
    <div class="cabecera_ayuda">
    <h4>Ayuda</h4>
    Escriba un comentario informando acerca de un problema o experiencia o una propuesta para ayudarnos a mejorar
    </div>
</div>

<div class="cuerpo_ayuda">
    <div class="area_ayuda">
        <form class="formulario_ayuda" action="help.php" method="POST" enctype="multipart/form-data">
            <div class="razon_comentario_container">
                <textarea name="razon_comentario" id="razon_comentario" placeholder="Razón del comentario" required></textarea>
                <div class="icono_imagen_container">
                    <input type="file" name="archivoASubir" id="archivoASubir" style="display:none">
                    <i class="fa-regular fa-image" id="icono_imagen">
                        <span class="tooltip" id="tooltip"></span>
                    </i>
                    <span class="palomita">
                        <i class="fa-solid fa-check"></i>
                    </span>
                </div>
            </div>
            <br>
            <div class="enviar_comentario_container">
                <textarea name="enviar_comentario" id="enviar_comentario" placeholder="Comentario" required></textarea>
            </div>
            <br>
            <input type="submit" name="enviar_comentario" id="boton_enviar_comentario" value="Enviar comentario">
        </form>
    </div>

    <script>
        // ! explicar este script
        const inputFile = document.getElementById("archivoASubir");
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