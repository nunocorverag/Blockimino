<?php
include("includes/header.php");

$query_comprobar_usuario_moderador_o_administrador = mysqli_query($con, "SELECT * FROM usuarios WHERE (id_usuario='$id_usuario_loggeado' AND (tipo='moderador' OR tipo='administrador'))");
if((mysqli_num_rows($query_comprobar_usuario_moderador_o_administrador) == 0))
{
    header("Location: home.php");
}
?>
    <div class="contenedor_peticiones_de_ayuda">
        <br>
        <h4>Peticiones de ayuda</h4>
        <br>
        <?php
        $query_seleccionar_peticiones = mysqli_query($con, "SELECT * FROM peticiones_de_ayuda WHERE resuelto='no' ORDER BY id_peticion_ayuda DESC");
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
                <button class='boton_resolver_ayuda btn btn-success' id='peticion<?php echo $id_peticion_ayuda ?>'><i class='fa-solid fa-check'></i></button>
                <div class="razon_peticion_display_usuario_especial">
                    <p style="width:70%; font-weight: bold">Razón: <?php echo $fila_peticiones['razon_peticion_ayuda'] ?></p>
                    <?php
                    $id_usuario_peticion = $fila_peticiones['id_usuario_peticion'];
                    $query_seleccionar_usuario_peticion = mysqli_query($con, "SELECT username FROM usuarios WHERE id_usuario='$id_usuario_peticion'");
                    $fila_usuario_peticion = mysqli_fetch_array($query_seleccionar_usuario_peticion);
                    $usuario_peticion = $fila_usuario_peticion['username'];
                    ?>
                    <p style="width:30%;">Usuario: 
                        <a href="<?php echo $usuario_peticion ?>">
                            <?php echo $usuario_peticion ?>
                        </a>
                    </p>
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

            <script>
                // + Script de resolver peticion
                $(document).ready(function(){
                    $('#peticion<?php echo $id_peticion_ayuda; ?>').on('click', function() {
                        // - resultado -> Sera el resultadoado de lo que el usuario clickeo, si fue "si" o "no"
                        bootbox.confirm("¿Estas seguro que quieres marcar esta peticion como resuelta?", function(result) {
                            // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                            if(result == true)
                            {
                                $.post("includes/form_handlers/solve_request.php?id_peticion_ayuda=<?php echo $id_peticion_ayuda; ?>", {resultado:result});
                                location.reload();
                            }
                        }).find('.btn-danger').removeClass('btn-danger').addClass('btn-success');
                    });
                });
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