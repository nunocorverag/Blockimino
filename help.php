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
        $subject = "Nuevo comentario de ayuda en el sistema! por el usuario $correo_usuario_loggeado.";
        $subject .= " Razón: ".$_POST['razon_comentario'];
        $message .= "Comentario: ".$_POST['enviar_comentario'];
        $header = "From: $correo_usuario_loggeado";

        mail("gnuno2003@gmail.com", $subject, $message, $headers);
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