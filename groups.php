<?php
include("includes/header.php");

?>
<div class="crear_grupo">
<a href="create_group.php">
        <button>
            <p>Crear nuevo grupo</p>
        </button>
    </a>
</div>

<div class="mostar_grupos">
    <h4>Grupos a los que perteneces</h4>
    <?php
    $objeto_grupo = new Grupo($con, $id_usuario_loggeado);
    ?>
    <div class="contenedor_grupos">
        <?php
        $objeto_grupo->displayGrupos();
        ?>
    </div>
</div>
<br>
<br>
<div class="contenedor_invitaciones_a_grupos">
    <h4>Invitaciones a grupos</h4>
    <?php
    $objeto_grupo = new Grupo($con, $id_usuario_loggeado);
    ?>
    <div class="contenedor_invitaciones">
        <?php
        $query_obtener_invitaciones_grupo = mysqli_query($con, "SELECT * FROM invitaciones_de_grupo WHERE id_usuario_invitado='$id_usuario_loggeado'"); 
        if(mysqli_num_rows($query_obtener_invitaciones_grupo) > 0)
        {
            while ($fila = mysqli_fetch_array($query_obtener_invitaciones_grupo))
            {
                $id_usuario_invitado = $fila['id_usuario_invitado'];
                $id_usuario_que_invito = $fila['id_usuario_que_invito'];
                $id_grupo_invitado = $fila['id_grupo_invitado'];

                $objeto_usuario_invitado = new Usuario($con, $id_usuario_invitado);
                $objeto_usuario_que_invito = new Usuario($con, $id_usuario_que_invito);

                $query_obtener_detalles_grupo = mysqli_query($con, "SELECT * FROM grupos WHERE id_grupo='$id_grupo_invitado'");
                $fila_detalles_grupo = mysqli_fetch_array($query_obtener_detalles_grupo);

                $nombre_grupo_invitado = $fila_detalles_grupo['nombre_grupo'];
                $lista_miembros = $fila_detalles_grupo['miembros_grupo'];
                $lista_miembros_explode = explode(",", $lista_miembros);
                $lista_miembros_explode = array_filter($lista_miembros_explode);
                $total_miembros = count($lista_miembros_explode);

                // + Si el usuario acepto
                if(isset($_POST['aceptar_invitacion' . $nombre_grupo_invitado]))
                {
                    $query_agregar_usuario_al_grupo = mysqli_query($con, "UPDATE grupos SET miembros_grupo=CONCAT(miembros_grupo, '$id_usuario_invitado,') WHERE id_grupo='$id_grupo_invitado'");
                    $query_agregar_grupo_al_usuario = mysqli_query($con, "UPDATE usuarios SET lista_grupos=CONCAT(lista_grupos, '$id_grupo_invitado,') WHERE id_usuario='$id_usuario_invitado'");
                    
                    $query_eliminar_invitacion = mysqli_query($con, "DELETE FROM invitaciones_de_grupo WHERE (id_usuario_invitado='$id_usuario_invitado' AND id_usuario_que_invito='$id_usuario_que_invito' AND id_grupo_invitado='$id_grupo_invitado')");

                    //+ Hay que revisar si hay solicitudes pendientes, para eliminarlas
                    $query_checar_solicitud = mysqli_query($con, "SELECT * FROM solicitudes_de_grupo WHERE (grupo_solicitado='$id_grupo_invitado' AND usuario_que_solicito_unirse='$id_usuario_invitado')");

                    if(mysqli_num_rows($query_checar_solicitud) > 0)
                    {
                        $query_eliminar_solicitud = mysqli_query($con, "DELETE FROM solicitudes_de_grupo WHERE (grupo_solicitado='$id_grupo_invitado' AND usuario_que_solicito_unirse='$id_usuario_invitado')");
                    }

                    if($total_miembros + 1 == 20)
                    {
                        // + Eliminamos las invitaciones porque no puede haber mas de 20 miembros
                        $query_eliminar_invitaciones = mysqli_query($con, "DELETE FROM invitaciones_de_grupo WHERE id_grupo_invitado='$id_grupo_invitado'");
                        $query_eliminar_solicitudes = mysqli_query($con, "DELETE FROM solicitudes_de_grupo WHERE grupo_solicitado='$id_grupo_invitado'");
                    }

                    header("Location: groups.php");
                }

                // + Si el usuario no acepto
                if(isset($_POST['ignorar_invitacion' . $nombre_grupo_invitado]))
                {    
                    $query_eliminar_invitacion = mysqli_query($con, "DELETE FROM invitaciones_de_grupo WHERE (id_usuario_invitado='$id_usuario_invitado' AND id_usuario_que_invito='$id_usuario_que_invito' AND id_grupo_invitado='$id_grupo_invitado')");
                    header("Location: groups.php");
                }
    
                $string_invitaciones = "<div class='displayInvitacion'>
                                            <div class='textoInvitacion'>
                                                <a href='" . $objeto_usuario_que_invito->obtenerNombreUsuario() . "'>

                                                    " . $objeto_usuario_que_invito->obtenerNombreCompleto() . "
                                                </a>
                                                Te a invitado al siguiente grupo: 
                                            </div>
                                            <br>
                                            <div class='contenedorInvitacion'>
                                                <div class='detallesGrupoInvitado'>
                                                    <div class='fotoPerfilGrupoInvitado'>
                                                        <a href='groups/"  . $fila_detalles_grupo['nombre_grupo'] ."'> 
                                                            <img src='" . $fila_detalles_grupo['imagen_grupo'] . "'>
                                                        </a>
                                                    </div>
                                                    <div class='infoGrupoInvitado'>
                                                    <a href='groups/"  . $fila_detalles_grupo['nombre_grupo'] ."'> 
                                                        ".$fila_detalles_grupo['nombre_grupo'] . "
                                                    </a>
                                                    </div>
                                                </div>
                                                <div class='botonesInvitacion'>
                                                <form action='groups.php' method='POST'>
                                                    <input type='submit' class='success boton_aceptar' name='aceptar_invitacion" . $nombre_grupo_invitado . "' id='boton_unirse' value='Unirse'>
                                                    <input type='submit' class='danger boton_declinar' name='ignorar_invitacion" . $nombre_grupo_invitado . "' id='boton_ignorar_invitacion' value='Ignorar'>
                                                </form>
                                            </div>
                                        </div>";
                echo $string_invitaciones;
            }
        }
        else
        {
            echo "No tienes invitaciones pendientes";
        }  
        ?>
    </div>
</div
