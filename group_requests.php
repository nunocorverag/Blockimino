<?php
include("includes/header.php");

if(isset($_GET['nombre_grupo']))
{
    $nombre_grupo = $_GET['nombre_grupo'];
    $query_info_grupo = mysqli_query($con, "SELECT * FROM grupos WHERE nombre_grupo='$nombre_grupo'");
    $fila = mysqli_fetch_array($query_info_grupo);
    $id_grupo = $fila['id_grupo'];
    $objeto_grupo_usuario_loggeado = new Grupo($con, $id_usuario_loggeado);

}

?>

<div class="solicitudes_grupo">
    <?php
    if ($objeto_grupo_usuario_loggeado->EsUsuarioPropietario($id_grupo))
    {
            ?>
            <h4>Solicitudes para Unirse al Grupo</h4>
            <br>
            <div class="contenedor_solicitudes_grupo">
                <?php
                $query_obtener_solicitudes_grupo = mysqli_query($con, "SELECT * FROM solicitudes_de_grupo WHERE grupo_solicitado='$id_grupo'"); 
                if(mysqli_num_rows($query_obtener_solicitudes_grupo) > 0)
                {
                    while ($fila = mysqli_fetch_array($query_obtener_solicitudes_grupo))
                    {
                        $id_usuario_que_solicito = $fila['usuario_que_solicito_unirse'];
                        $id_grupo_solicitado = $fila['grupo_solicitado'];

                        $objeto_usuario_que_solicito = new Usuario($con, $id_usuario_que_solicito);

                        $query_obtener_detalles_usuario = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='$id_usuario_que_solicito'");
                        $fila_detalles_usuario = mysqli_fetch_array($query_obtener_detalles_usuario);
                        $nombre_usuario_que_solicito = $fila_detalles_usuario['username'];

                        // + Si el usuario acepto
                        if(isset($_POST['aceptar_invitacion' . $nombre_usuario_que_solicito]))
                        {
                            $query_agregar_usuario_al_grupo = mysqli_query($con, "UPDATE grupos SET miembros_grupo=CONCAT(miembros_grupo, '$id_usuario_que_solicito,') WHERE id_grupo='$id_grupo_solicitado'");
                            $query_agregar_grupo_al_usuario = mysqli_query($con, "UPDATE usuarios SET lista_grupos=CONCAT(lista_grupos, '$id_grupo_solicitado,') WHERE id_usuario='$id_usuario_que_solicito'");
                            
                            $query_eliminar_solicitud = mysqli_query($con, "DELETE FROM solicitudes_de_grupo WHERE (grupo_solicitado='$id_grupo_solicitado' AND usuario_que_solicito_unirse='$id_usuario_que_solicito')");

                            //+ Hay que revisar si hay invitaciones pendientes, para eliminarlas
                            $query_checar_invitacion = mysqli_query($con, "SELECT FROM invitaciones_de_grupo WHERE (id_usuario_invitado='$id_usuario_que_solicito' AND id_grupo_invitado='$id_grupo_solicitado')");

                            if(mysqli_num_rows($query_checar_invitacion) > 0)
                            {
                                $query_eliminar_invitacion = mysqli_query($con, "DELETE FROM invitaciones_de_grupo WHERE (id_usuario_invitado='$id_usuario_que_solicito' AND id_grupo_invitado='$id_grupo_solicitado')");
                            }

                            header("Location: requests");
                        }

                        // + Si el usuario no acepto
                        if(isset($_POST['ignorar_invitacion' . $nombre_usuario_que_solicito]))
                        {    
                            $query_eliminar_solicitud = mysqli_query($con, "DELETE FROM solicitudes_de_grupo WHERE (grupo_solicitado='$id_grupo_solicitado' AND usuario_que_solicito_unirse='$id_usuario_que_solicito')");
                            
                            header("Location: requests");
                        }

                        $string_invitaciones = "<div class='displaySolicitud'>
                                                    <div class='textoSolicitud'>
                                                        <a href='../../" . $objeto_usuario_que_solicito->obtenerNombreUsuario() . "'>

                                                            " . $objeto_usuario_que_solicito->obtenerNombreCompleto() . "
                                                        </a>
                                                        Ha solicitado unirse al grupo
                                                    </div>
                                                    <div class='contenedorSolicitud'>
                                                        <div class='botonesSolicitud'>
                                                        <form action='requests' method='POST'>
                                                            <input type='submit' class='success boton_aceptar' name='aceptar_invitacion" . $nombre_usuario_que_solicito . "' id='boton_unirse' value='Aceptar'>
                                                            <input type='submit' class='danger boton_declinar' name='ignorar_invitacion" . $nombre_usuario_que_solicito . "' id='boton_ignorar_invitacion' value='Denegar'>
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
    
            <?php 
        }
    else if($objeto_grupo_usuario_loggeado->UsuarioPerteneceAlGrupo($id_grupo))
    {
        echo "No tiene los permisos necesarios para administrar las solicitudes en este grupo!";
    }
    else
    {
        echo "No pertenece a este grupo!";
    }

    ?>
</div>
