<?php
include("includes/header.php");

// + Si el boton de publicar fue presionado entonces:
if (isset($_POST['publicar'])) {
    // + Refrescamos la pagina para que no nos pida confirmar reenvio de formulario
    header("Location: home.php");
}
?>

</div>


<div class="publicar_area">
    <form class="formulario_publicacion" action="home.php" method="POST" enctype="multipart/form-data">
        <div class="publicar_titulo_container">
            <textarea name="publicar_titulo" id="publicar_titulo" placeholder="Titulo publicacion" required></textarea>
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
        <div class="publicar_texto_container">
            <textarea name="publicar_texto" id="publicar_texto" placeholder="Cuerpo_publicacion" required></textarea>
        </div>
        <br>
        <input type="submit" name="publicar" id="boton_publicar" value="Publicar">
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


</body>

</html>