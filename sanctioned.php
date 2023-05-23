<?php
require 'config/config.php';
?>

<link rel="stylesheet" href="assets/css/sanctioned_style.css">

<?php

if(isset($_GET['username']))
{
    $nombre_usuario = $_GET['username'];
    $query_obtener_usuario = mysqli_query($con, "SELECT id_usuario FROM usuarios WHERE username='$nombre_usuario'");

    if(mysqli_num_rows($query_obtener_usuario) == 1)
    {
        $fila_id_usuario = mysqli_fetch_array($query_obtener_usuario);
        $id_usuario = $fila_id_usuario['id_usuario'];
    
        $query_verificar_sanciones_usuario = mysqli_query($con, "SELECT * FROM sanciones WHERE id_usuario_sancionado='$id_usuario'");
        if(mysqli_num_rows($query_verificar_sanciones_usuario) > 0)
        {
            ?>
            <div class="cuerpo_principal">
                <div class="contenedor_display_sanciones_a_usuario">
                    <h1>Usted ha sido sancionado por los siguientes motivos:</h1>
                    <div class="contenedor_bloque_sancion">
                        <?php
                            $tipo = "";
                                while($fila = mysqli_fetch_array($query_verificar_sanciones_usuario))
                                {
                                    ?>
                                    <div class="contenedor_motivo_sancion">
                                        <?php
                                        $tipo = $fila['tipo_sancion'];
                                        $razon = $fila['razon_sancion'];
                                        $id_publicacion_sancion = $fila['id_publicacion_sancion'];
                                        $id_comentario_sancion = $fila['id_comentario_sancion'];
                                        echo "<div class='displaySancionAUsuario'>
                                                <p>Motivo: " . $fila['razon_sancion'] . "</p>";

                                        if($id_publicacion_sancion != NULL)
                                        {
                                            $query_seleccionar_publicacion_sancionada = mysqli_query($con, "SELECT * FROM publicaciones WHERE id_publicacion='$id_publicacion_sancion'");
                                            $fila_info_publicacion = mysqli_fetch_array($query_seleccionar_publicacion_sancionada);
                                            $titulo_publicacion = $fila_info_publicacion['titulo'];
                                            $cuerpo_publicacion = $fila_info_publicacion['cuerpo'];

                                            echo "<div class='displayPublicacionSancion'>
                                                    <p>Contenido publicacion: </p>
                                                    <p>Titulo: $titulo_publicacion </p>
                                                    <p>Cuerpo: $cuerpo_publicacion </p>
                                                </div>";


                                        }
                                        else if($id_comentario_sancion != NULL)
                                        {
                                            $query_seleccionar_comentario_sancionado = mysqli_query($con, "SELECT * FROM comentarios WHERE id_comentario='$id_comentario_sancion'");
                                            $fila_info_comentario = mysqli_fetch_array($query_seleccionar_comentario_sancionado);
                                            $cuerpo_comentario = $fila_info_comentario['cuerpo_comentario'];

                                            echo "<div class='displayComentario'>
                                                    <p>Comentario: $cuerpo_comentario </p>
                                                </div>";
                                        }
                                        echo "</div>";
                                    ?>
                                    </div>
                                    <?php

                                    
                                }
                        ?>
                    </div>
                    <br><br>
                    <div class='divInferiorDisplaySancion'>
                            <div class="tipoSancionUsuario">
                                <p>Tipo de sancion: <?php echo $tipo ?></p>
                            </div>
                        <?php
                            if($tipo == "temporal")
                            {
                                $query_seleccionar_ultima_sancion_usuario = mysqli_query($con, "SELECT fecha_sancion FROM sanciones WHERE id_usuario_sancionado='$id_usuario' AND id_sancion = (SELECT MAX(id_sancion) FROM sanciones WHERE id_usuario_sancionado ='$id_usuario')");
                                $fila_ultima_sancion_usuario = mysqli_fetch_array($query_seleccionar_ultima_sancion_usuario);
                                ?>
                                <div class="divTiempoSancionUsuario">
                                    <?php
                                    // + fecha y hora actual
                                    $tiempo_actual = date("Y-m-d H:i:s");
                                    $tiempo_actual = new DateTime($tiempo_actual);
                
                                    // + fecha y hora restante, con el formato para que acepte operaciones de datetime
                                    $fecha_sancion = DateTime::createFromFormat('Y-m-d H:i:s', $fila_ultima_sancion_usuario['fecha_sancion']);
                
                                    // + calcular la diferencia entre las dos fechas
                                    $tiempo_restante = $tiempo_actual->diff($fecha_sancion);
                
                                    // guardar la diferencia en variables separadas
                                    $dias = $tiempo_restante->days;
                                    $horas = $tiempo_restante->h;
                                    $minutos = $tiempo_restante->i;
                                    ?>
                                        <p>
                                        Tiempo restante:
                                        Dias: <?php echo $dias ?>
                                        Horas: <?php echo $horas ?>
                                        Minutos: <?php echo $minutos ?>
                                        </p>
                                </div>
                                <?php
                            }
                            ?>
                    </div>
                </div>
            </div>
            <?php
        }
        else
        {
            header("Location: index.php");
        }
    }
    else
    {
        header("Location: index.php");
    }
}

?>
