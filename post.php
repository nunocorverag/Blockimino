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
        <!-- Sera la publicacion nueva que el usuario podra publicar -->
        <form class="formulario_publicacion" action="home.php" method="POST">
            
            <!-- Sera el area de texto para el titulo de la publicacion -->
            <div class="publicar_titulo">
                <textarea name="publicar_titulo" id="publicar_titulo" placeholder="Titulo publicacion" required></textarea>
                <i class="fa-regular fa-image"></i>
            </div>
            <br>
            <!-- Sera el area de texto para el cuerpo de la publicacion -->
            <div class="publicar_texto">
                <textarea name="publicar_texto" id="publicar_texto" placeholder="Cuerpo_publicacion" required></textarea>
            </div>
            <br>
            <!-- Boton para publicar -->
            <input type="submit" name="publicar" id="boton_publicar" value="Publicar">
        </form>
    </div>


</body>

</html>