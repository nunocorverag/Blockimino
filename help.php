<?php
include("includes/header.php");

$query_comprobar_usuario_normal = mysqli_query($con, "SELECT * FROM usuarios WHERE (id_usuario='$id_usuario_loggeado' AND tipo='normal')");
if((mysqli_num_rows($query_comprobar_usuario_normal) == 0))
{
    header("Location: home.php");
}
?>

    <br>
    <div class="contenedor_boton_pedir_ayuda"> 
        <a href="help_request.php">
            <button>Pedir ayuda</button>
        </a> 
    </div>
    <br>
    <div class="contenedor_peticiones_de_ayuda">
        <h4>Peticiones de ayuda</h4>
        <br>
        <?php
        $query_seleccionar_peticiones = mysqli_query($con, "SELECT * FROM peticiones_de_ayuda WHERE id_usuario_peticion='$id_usuario_loggeado' ORDER BY resuelto='no' DESC, id_peticion_ayuda DESC");
        while($fila_peticiones = mysqli_fetch_array($query_seleccionar_peticiones))
        {
            $id_peticion_ayuda = $fila_peticiones['id_peticion_ayuda'];
            $checar_si_hay_comentarios_peticion = mysqli_query($con, "SELECT * FROM comentarios_peticiones_ayuda WHERE id_peticion_comentada='$id_peticion_ayuda'");
            $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios_peticion);
    
            if($numero_comentarios == 1)
            {
                $numero_comentarios = $numero_comentarios . " Comentario";
            }
            else
            {
                $numero_comentarios = $numero_comentarios . " Comentarios";
            }
            ?>
            <div class="displayAyda">
                <div class="razon_peticion_display">
                <p style="width:75%; font-weight: bold">Razón: <?php echo $fila_peticiones['razon_peticion_ayuda'] ?></p>
                    <?php
                    if($fila_peticiones['resuelto'] == "no")
                    {
                        ?>
                        <p style="width:25%; color:#e74c3c;">Petición NO resuelta</p>
                        <?php
                    }
                    else if($fila_peticiones['resuelto'] == "si")
                    {
                        ?>
                        <p style="width:25%; color:#2ecc71;">Peticion resuelta</p>
                        <?php
                    }

                    ?>
                </div>
                <div class="contenido_peticion_display">
                    <p>Peticion: <?php echo $fila_peticiones['peticion_ayuda'] ?></p>
                    <?php
                    $direccionImagen = $fila_peticiones['imagen_peticion_ayuda'];
                    if($direccionImagen != "")
                    {
                        $divImagen = "<div class='imagenPublicada'>
                                        <img src='$direccionImagen'>
                                    </div>";
                    }
                    else
                    {
                        $divImagen = "";
                    }
                    echo $divImagen;
                    ?>
                    <?php $fila_peticiones['imagen_peticion_ayuda'] ?>
                </div>
            </div>
            <div class="displayComentarioAyuda">
            <span class="mostrar_ocultar_comentarios_ayuda" onClick="javascript:toggle<?php echo $id_peticion_ayuda?>()">
                <i class='fa-solid fa-comment'></i>&nbsp;<?php echo $numero_comentarios?>
            </span>
            <div class="publicar_comentario_peticion" id="mostrarComentariosPeticion<?php echo $id_peticion_ayuda?>" style="display:none;">
                <iframe src="comment_request_frame.php?id_peticion_ayuda=<?php echo $id_peticion_ayuda?>" id="iframe_comentario_peticion" frameborder="0"></iframe>
            </div>

            <!-- Este bloque es para mostrar los comentarios -->
            <script>
                // + Esta seccion es para saber que comentario mostrar
                function toggle<?php echo $id_peticion_ayuda; ?>()
                {
                    // $ event.target -> Es donde la persona hizo click
                    // - target -> Guarda donde hizo click la persona
                    var target = $(event.target);
                    // + Si un link no es clickeado, entonces mostrara o oculatara el comentario
                    if (!target.is("")) {
                        var element = document.getElementById("mostrarComentariosPeticion<?php echo $id_peticion_ayuda ?>");
                        if(element.style.display == "block")
                        {
                            element.style.display = "none";
                        }
                        else
                        {
                            element.style.display = "block";
                        }
                    }
                }
            </script>

            </div>
            <br>
            <br>
            <br>

            <?php
        }
        ?>

    </div>

</div>