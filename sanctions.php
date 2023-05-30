<?php
include("includes/header.php");

$query_comprobar_usuario_moderador_o_administrador = mysqli_query($con, "SELECT * FROM usuarios WHERE (id_usuario='$id_usuario_loggeado' AND (tipo='moderador' OR tipo='administrador'))");
if((mysqli_num_rows($query_comprobar_usuario_moderador_o_administrador) == 0))
{
    header("Location: home.php");
}

?>
    <br>
    <div class="contenedor_boton_nueva_sancion"> 
        <a href="new_sanction.php">
            <button>Nueva Sanción</button>
        </a> 
    </div>
    <br>
    <div class="contenedor_sanciones">
    <h4>Sanciones</h4>
        <?php
        $query_seleccionar_sancionse = mysqli_query($con, "SELECT * FROM sanciones WHERE sancion_eliminada='no' ORDER BY id_sancion DESC");
        while($fila = mysqli_fetch_array($query_seleccionar_sancionse))
        {
            //+ Display la sancion
            $id_usuario_sancionado = $fila['id_usuario_sancionado'];
            $objeto_usuario_sancionado = new Usuario($con, $id_usuario_sancionado);
            $nombre_usuario_sancionado = $objeto_usuario_sancionado->obtenerNombreUsuario();
            ?>
            <div class="displaySancion">
                <button class='boton_eliminar_sancion btn btn-danger' id='sancion<?php echo $fila['id_sancion'];?>'><i class='fa-solid fa-x'></i></button>
                <div class="div_superior_sancion_display">
                    <div class="div_usuario_a_sancionar">
                        <p>Usuario sancionado: 
                            <a href="<?php echo $nombre_usuario_sancionado?>">
                                <?php echo $nombre_usuario_sancionado ?>
                            </a>
                        </p>
                    </div>
                    <div class="div_tipo_sancion">
                        <p>Tipo de sanción: <?php echo $fila['tipo_sancion'] ?></p>
                    </div>
                </div>
                <div class="div_central_sancion_display">
                    <p>Razón de la sanción: <?php echo $fila['razon_sancion']?></p>
                </div>
                    <?php
                    if($fila['id_publicacion_sancion'] != NULL)
                    {
                        ?>
                        <div class="div_mostrar_publicacion_o_comentario_sancionado">
                        <p>
                            ID de la publicación sancionada 
                            <a href="deleted_publication.php?id=<?php echo $fila['id_publicacion_sancion']?>">
                                <?php echo $fila['id_publicacion_sancion'] ?>
                            </a>
                        </p>
                        </div>
                        <?php
                    }
                    else if($fila['id_comentario_sancion'] != NULL)
                    {
                        ?>
                        <div class="div_mostrar_publicacion_o_comentario_sancionado">
                        <p>
                            ID del comentario sancionado 
                            <a href="deleted_comment.php?id=<?php echo $fila['id_comentario_sancion']?>">
                                <?php echo $fila['id_comentario_sancion'] ?>
                            </a>
                        </p>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    if($fila['tipo_sancion'] == "temporal")
                    {
                        ?>
                        <div class="div_inferior_sancion_display">
                        <?php
                        // + fecha y hora actual
                        $tiempo_actual = date("Y-m-d H:i:s");

                        $tiempo_actual_num =  strtotime($tiempo_actual);
                        $tiempo_actual = new DateTime($tiempo_actual);
    
                        // + fecha y hora restante, con el formato para que acepte operaciones de datetime
                        $fecha_sancion = $fila['fecha_sancion'];

                        $tiempo_sancion_num = strtotime($fecha_sancion);
                        $fecha_sancion = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_sancion);

                        $tiempo_restante_num = $tiempo_sancion_num - $tiempo_actual_num;

                        if($tiempo_restante_num <= 0)
                        {
                            $query_eliminar_sanciones_temporales = mysqli_query($con, "UPDATE sanciones SET sancion_eliminada='si' WHERE id_usuario_sancionado='$id_usuario_sancionado' AND tipo_sancion='temporal'");
                        }
                        // + calcular la diferencia entre las dos fechas
                        $tiempo_restante = $tiempo_actual->diff($fecha_sancion);
    
                        // guardar la diferencia en variables separadas
                        $dias = $tiempo_restante->days;
                        $horas = $tiempo_restante->h;
                        $minutos = $tiempo_restante->i;
                        $segundos = $tiempo_restante->s;
                        ?>
                        <p>
                        Tiempo restante:
                        Dias: <?php echo $dias ?>
                        Horas: <?php echo $horas ?>
                        Minutos: <?php echo $minutos ?>
                        Segundos: <?php echo $segundos ?>
                        </p>
                    </div>
                    <?php
                    }
                    ?>
                        <script>
                        // + Script para borrar sanciones
                        $(document).ready(function(){
                            $('#sancion<?php echo $fila['id_sancion'];?>').on('click', function() {
                                bootbox.confirm("¿Estás seguro que quieres eliminar esta sanción? Si no quedan más sanciones por terminar, el usuario podrá volver a utilizar Blockimino.", function(result) {
                                    if(result == true) {
                                            $.post("includes/handlers/ajax_delete_sanction.php?id_sancion=<?php echo $fila['id_sancion'];?>", {resultado:result}, function(data){
                                            location.reload();
                                        });
                                    }
                                });
                            });
                        });
                    </script>
            </div>
            <br><br>
            <?php
        }
        ?>
    </div>
</div>  <!-- Cierre de div cuerpo_principal -->
</body>
</html>