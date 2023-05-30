<?php
include("includes/header.php");

if(isset($_GET['perfil_usuario']))
{
    $perfil_nombre_usuario = $_GET['perfil_usuario'];
    $query_obtener_id_nombre_usuario = mysqli_query($con, "SELECT * FROM usuarios WHERE username='$perfil_nombre_usuario'");
    $fila_info_usuario = mysqli_fetch_array($query_obtener_id_nombre_usuario);
    $id_usuario_perfil = $fila_info_usuario['id_usuario'];

    if($usuario_loggeado == $perfil_nombre_usuario)
    {
        $perfil_es_propio = true;
    }
    else
    {
        $perfil_es_propio = false;
    }
}
else
{
    header("Location: home.php");
}

?>

<div class="contenedor_proyectos_usuario">
    <?php
    if($perfil_es_propio)
    {
        ?>
        <h4>Mis proyectos</h4>
        <br>
        <div class="contenedor_proyectos">
            <?php
                $query_seleccionar_proyectos_usuario = mysqli_query($con, "SELECT * FROM proyectos WHERE id_usuario_proyecto='$id_usuario_perfil'");
                if(mysqli_num_rows($query_seleccionar_proyectos_usuario) > 0)
                {
                    while($fila = mysqli_fetch_array($query_seleccionar_proyectos_usuario))
                    {
                        $id_proyecto = $fila['id_proyecto'];
                        $nombre_proyecto = $fila['nombre_proyecto'];
                        $visibilidad = $fila['visibilidad'];
                        $link_proyecto = $fila['link_proyecto'];
                        ?>
                        <div class="contenedor_proyecto">
                            <div class="nombre_proyecto_container">
                                <p><?php echo $nombre_proyecto ?></p>
                            </div>
                            <div class="imagen_fondo">
                                <img src="..\assets\images\icons\blockimino.png">
                            </div>
                            <div class="visibilidad_proyecto">
                                <button id="publico-btn-<?php echo $nombre_proyecto ?>" class="visibilidad-btn" type="button">Público</button>
                                <button id="privado-btn-<?php echo $nombre_proyecto ?>" class="visibilidad-btn" type="button">Privado</button>
                                <input type="hidden" name="visibilidad_proyecto_<?php echo $nombre_proyecto ?>" id="visibilidad_proyecto_<?php echo $id_proyecto ?>" value="<?php echo $visibilidad ?>">
                            </div>
                            <br>
                            <div class="contenedor_botones_accion_proyecto">
                                <button class="boton_editar_proyecto btn btn-info" id=editar<?php echo $nombre_proyecto?> >Editar</button>
                                <button class="boton_eliminar_proyecto btn btn-danger" id=eliminar<?php echo $nombre_proyecto?> >Eliminar</button>
                            </div>
                        </div>

                        <script>
                            $(document).ready(function(){
                                // + Obtener el valor de la visibilidad del proyecto
                                var visibilidad = '<?php echo $visibilidad; ?>';

                                // + Verificar si la visibilidad es pública o privada
                                if (visibilidad == 1) {
                                    // + Asignar la clase 'selected' al botón público
                                    $('#publico-btn-<?php echo $nombre_proyecto ?>').addClass('selected');
                                } else {
                                    // + Asignar la clase 'selected' al botón privado
                                    $('#privado-btn-<?php echo $nombre_proyecto ?>').addClass('selected');
                                }
                            });
                        </script>

                        <script>
                            // + Script de actualizar proyecto
                            $(document).ready(function(){
                                $('#publico-btn-<?php echo $nombre_proyecto ?>').on('click', function() {
                                    id_proyecto = <?php echo $id_proyecto?>;
                                    // - resultado -> Sera el resultadoado de lo que el usuario clickeo, si fue "si" o "no"
                                    // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                    $.post("../includes/handlers/ajax_update_project_visibility.php", {id_proyecto:id_proyecto, visibilidad:1});
                                    location.reload();
                                });

                                $('#privado-btn-<?php echo $nombre_proyecto ?>').on('click', function() {
                                    id_proyecto = <?php echo $id_proyecto?>;
                                    // - resultado -> Sera el resultadoado de lo que el usuario clickeo, si fue "si" o "no"
                                    // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                    $.post("../includes/handlers/ajax_update_project_visibility.php", {id_proyecto:id_proyecto, visibilidad:0});
                                    location.reload();
                                });
                            });
                        </script>

                        <script>
                            // + Script de borrar proyecto
                            $(document).ready(function(){
                                $('#eliminar<?php echo $nombre_proyecto; ?>').on('click', function() {
                                    // - resultado -> Sera el resultadoado de lo que el usuario clickeo, si fue "si" o "no"
                                    bootbox.confirm("¿Estas seguro que quieres eliminar este proyecto?", function(result) {
                                        if(result == true)
                                        {
                                            // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                            $.post("../includes/form_handlers/handlers/ajax_delete_project.php?id_proyecto=<?php echo $id_proyecto; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", {resultado:result});
                                            location.reload();
                                        }
                                    });
                                });
                            });
                        </script>

                        <!-- // + ESTE SCRIPT CARGARA EL PROYECTO EN ESPECIFICO -->
                        <script>
                            $(document).ready(function(){
                                
                                $('#editar<?php echo $nombre_proyecto; ?>').on('click', function() {
                                    window.open('../block_arena.php?project=<?php echo $nombre_proyecto?>');
                                    
                                });
                            });
                        </script>

                        <?php
                    }
                }
                else
                {
                    ?>
                    <div class="contenedor_no_proyectos">
                        <p>No tienes proyectos</p>
                    </div>
                    <?php
                }
            ?>
        </div>
        <?php
    }
    else
    {
        ?>
        <h4>Proyectos de <?php echo $perfil_nombre_usuario?></h4>
        <br>
        <div class="contenedor_proyectos">
            <?php
                $query_seleccionar_proyectos_visibles_usuario = mysqli_query($con, "SELECT * FROM proyectos WHERE id_usuario_proyecto='$id_usuario_perfil' AND visibilidad='1'");
                if(mysqli_num_rows($query_seleccionar_proyectos_visibles_usuario) > 0)
                {
                    while($fila = mysqli_fetch_array($query_seleccionar_proyectos_visibles_usuario))
                    {
                        $id_proyecto = $fila['id_proyecto'];
                        $nombre_proyecto = $fila['nombre_proyecto'];
                        $visibilidad = $fila['visibilidad'];
                        $link_proyecto = $fila['link_proyecto'];
                        ?>
                        <div class="contenedor_proyecto">
                            <div class="nombre_proyecto_container">
                                <p><?php echo $nombre_proyecto ?></p>
                            </div>
                            <div class="imagen_fondo">
                                <img src="..\assets\images\icons\blockimino.png">
                            </div>
                            <br>
                            <br>
                            <div class="contenedor_botones_accion_proyecto">
                                <button class="boton_copiar_proyecto btn btn-info" id=copiar_proyecto<?php echo $nombre_proyecto?> >Copiar_Proyecto</button>
                            </div>
                        </div>

                        <!-- // TODO ESTE SCRIPT CARGARA EL PROYECTO EN ESPECIFICO EN ESTE SCRIPT AL DARLE CLICK EN EL PROYECTO DE ALGUIEN MAS, SE ABRIRA EL PROYECTO Y PUES YA DECIDIRA EL USUARIO SI GUARDARLO O NO-->
                        <script>
                            $(document).ready(function(){
                                $('#copiar_proyecto<?php echo $nombre_proyecto; ?>').on('click', function() {
                                    bootbox.prompt("Introduce un nombre para el proyecto", function(result) {
                                        if(result != null)
                                        {
                                            $.ajax({
                                                url: '../includes/handlers/ajax_copy_project.php?id_usuario=<?php echo $id_usuario_loggeado?>&link_proyecto=<?php echo $link_proyecto?>',
                                                type: 'POST',
                                                data: {resultado:result},
                                                success: function(data) {
                                                    alert(data);
                                                }
                                            });
                                        }
                                    });
                                });
                            });
                        </script>

                        <?php
                    }
                }
                else
                {
                    ?>
                    <div class="contenedor_no_proyectos">
                        <p>El usuario no tiene proyectos!</p>
                    </div>
                    <?php
                }
            ?>
        </div>
        <?php
    }
    ?>
</div>

