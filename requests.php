<?php
include("includes/header.php");
?>

    <div class="solicitudes_de_amistad">
        <h4>Solicitudes de amistad</h4>
        <?php
            $query_soliitudes_de_amistad = mysqli_query($con, "SELECT *FROM solicitudes_de_amistad WHERE usuario_solicitado='$id_usuario_loggeado'");
            if(mysqli_num_rows($query_soliitudes_de_amistad) == 0)
            {
                echo "No tienes solicitudes de amistad pendientes!";
            }
            else
            {
                while($fila = mysqli_fetch_array($query_soliitudes_de_amistad))
                {
                    $id_usuario_que_envio_solicitud = $fila['usuario_que_solicito'];  
                    $objeto_usuario_loggeado = new Usuario($con, $id_usuario_loggeado);              
                    $objeto_usuario_que_solicito = new Usuario($con, $id_usuario_que_envio_solicitud);

                    $username_usuario_que_envio_solicitud = $objeto_usuario_que_solicito->obtenerNombreUsuario();

                    echo $objeto_usuario_que_solicito->obtenerNombreCompleto() . " Te ha enviado una solicitud de amistad!";
                    $lista_amigos_usuario_que_solicito = $objeto_usuario_que_solicito->obtenerListaAmigos();

                    if(isset($_POST['aceptar_solicitud' . $username_usuario_que_envio_solicitud]))
                    {
                        $query_agregar_amigo = mysqli_query($con, "UPDATE usuarios SET lista_amigos=CONCAT(lista_amigos, '$username_usuario_que_envio_solicitud,') WHERE id_usuario='$id_usuario_loggeado'");
                        $query_agregar_amigo = mysqli_query($con, "UPDATE usuarios SET lista_amigos=CONCAT(lista_amigos, '$usuario_loggeado,') WHERE id_usuario='$id_usuario_que_envio_solicitud'");

                        $query_eliminar_solicitud = mysqli_query($con, "DELETE FROM solicitudes_de_amistad WHERE usuario_solicitado='$id_usuario_loggeado' AND usuario_que_solicito='$id_usuario_que_envio_solicitud'");
                        echo "Ahora eres amigo de: " . $username_usuario_que_envio_solicitud . "!";

                        $lista_seguidos_usuario_loggeado = $objeto_usuario_loggeado->obtenerListaSeguidos();
                        $lista_seguidores_usuario_loggeado = $objeto_usuario_loggeado->obtenerListaSeguidores();

                        $lista_seguidos_usuario_que_solicito = $objeto_usuario_que_solicito->obtenerListaSeguidos();
                        $lista_seguidores_usuario_que_solicito = $objeto_usuario_que_solicito->obtenerListaSeguidores();
                
                        // + Remover si es que el usuario loggeado, seguia al usuario que envio la solicitud
                        $nueva_lista_seguidos_usuario_loggeado = str_replace($username_usuario_que_envio_solicitud . ",", "", $lista_seguidos_usuario_loggeado);
                        $query_eliminar_seguido_usuario_loggeado = mysqli_query($con, "UPDATE usuarios SET lista_seguidos='$nueva_lista_seguidos_usuario_loggeado' WHERE id_usuario='$id_usuario_loggeado'");

                        // + Remover si es que el usuario loggeado, estaba seguido por el usuario que envio la solicitud
                        $nueva_lista_seguidores_usuario_loggeado = str_replace($username_usuario_que_envio_solicitud . ",", "", $lista_seguidores_usuario_loggeado);
                        $query_eliminar_seguido_usuario_loggeado = mysqli_query($con, "UPDATE usuarios SET lista_seguidores='$nueva_lista_seguidores_usuario_loggeado' WHERE id_usuario='$id_usuario_loggeado'");

                        // + Remover si es que el que envio la solicitud, segui al usuario loggeado
                        $nueva_lista_seguidos_usuario_que_solicito = str_replace($usuario_loggeado . ",", "", $lista_seguidos_usuario_que_solicito);
                        $query_eliminar_seguido_usuario_que_solicito = mysqli_query($con, "UPDATE usuarios SET lista_seguidos='$nueva_lista_seguidos_usuario_que_solicito' WHERE id_usuario='$id_usuario_que_envio_solicitud'");

                        // + Remover si es que el usuario que envio la solicitud, estaba seguido por el usuario loggeado
                        $nueva_lista_seguidores_usuario_que_solicito = str_replace($usuario_loggeado . ",", "", $lista_seguidores_usuario_que_solicito);
                        $query_eliminar_seguido_usuario_que_solicito = mysqli_query($con, "UPDATE usuarios SET lista_seguidores='$nueva_lista_seguidores_usuario_que_solicito' WHERE id_usuario='$id_usuario_que_envio_solicitud'");


                        header("Location: requests.php");
                    }

                    if(isset($_POST['ignorar_solicitud' . $username_usuario_que_envio_solicitud]))
                    {    
                        $query_eliminar_solicitud = mysqli_query($con, "DELETE FROM solicitudes_de_amistad WHERE usuario_solicitado='$id_usuario_loggeado' AND usuario_que_solicito='$id_usuario_que_envio_solicitud'");
                        echo "Solicitud ignorada con exito!";
                        header("Location: requests.php");
                    }
                    ?>

                    <form action="requests.php" method="POST">
                        <input type="submit" name="aceptar_solicitud<?php echo $username_usuario_que_envio_solicitud; ?>" id="boton_aceptar" value="Aceptar">
                        <input type="submit" name="ignorar_solicitud<?php echo $username_usuario_que_envio_solicitud; ?>" id="boton_ignorar" value="Ignorar">
                    </form>
                    <?php

                }
            }
        ?>
    </div>
</div>