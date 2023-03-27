<?php
include("includes/header.php");

// + Si el boton de publicar fue presionado entonces:
if (isset($_POST['crear_grupo'])) {
    // + Refrescamos la pagina para que no nos pida confirmar reenvio de formulario
    header("Location: group_index.php");
}
?>

</div>


<div class="area_crear_grupo">
    <form class="formulario_crear_grupo" action="group_index.php" method="POST" enctype="multipart/form-data">
        <div class="nombre_grupo_container">
            <textarea name="nombre_grupo" id="nombre_grupo" placeholder="Nombre de grupo" required></textarea>
            <div class="icono_imagen_container">
                <!-- //TODO FALTA PONER QUE EL USUARIO COMPLETE EL CAMPO SI NO SUBE IMAGEN DE GRUPO, O MAS BIEN, QUE PONGA UNA DEFAULT -->
                <input type="file" name="imagenGrupo" id="imagenGrupo" style="display:none" required>
                <i class="fa-regular fa-image" id="icono_imagen">
                    <span class="tooltip" id="tooltip"></span>
                </i>
                <span class="palomita">
                    <i class="fa-solid fa-check"></i>
                </span>
            </div>
        </div>
        <br>
        <div class="descripcion_grupo_container">
            <textarea name="desripcion_grupo" id="desripcion_grupo" placeholder="Descripción del grupo (opcional)"></textarea>
        </div>
        <br>
        <input type="submit" name="crear_grupo" id="boton_crear_grupo" value="Crear grupo">
    </form>
</div>

<script>
    // ! explicar este script
    const inputFile = document.getElementById("imagenGrupo");
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