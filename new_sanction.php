<?php
include("includes/header.php");

$query_comprobar_usuario_moderador_o_administrador = mysqli_query($con, "SELECT * FROM usuarios WHERE (id_usuario='$id_usuario_loggeado' AND (tipo='moderador' OR tipo='administrador'))");
if((mysqli_num_rows($query_comprobar_usuario_moderador_o_administrador) == 0))
{
    header("Location: home.php");
}

// + Si el boton de aplicar sancion fue presionado
if(isset($_POST['boton_aplicar_sancion']))
{
    $sancion_exitosa = 1;
    $mensaje_de_error = "";
    $id_usuario_a_sancionar = "";
    $id_usuario_que_sanciono = $id_usuario_loggeado;
    $objeto_usuario_que_sanciono = new Usuario($con, $id_usuario_que_sanciono);
    $tipo_usuario_que_sanciono = $objeto_usuario_que_sanciono->obtenerTipoUsuario();

    $razon = $_POST['razon_sancion'];
    $tipo_sancion = $_POST['tipo_sancion'];
    $tipo_busqueda = "";

    $usuario_a_sancionar = $_POST['usuario_a_sancionar'];

    $query_id_usuario_a_sancionar = mysqli_query($con, "SELECT id_usuario FROM usuarios WHERE username='$usuario_a_sancionar'");
    if(mysqli_num_rows($query_id_usuario_a_sancionar) == 0)
    {
        $sancion_exitosa = 0;
        $mensaje_de_error .= "Error, el usuario no se encontro!<br>";
    }
    else
    {
        $fila_id_usuario = mysqli_fetch_array($query_id_usuario_a_sancionar);
        $id_usuario_a_sancionar = $fila_id_usuario['id_usuario'];
        $objeto_usuario_a_sancionar = new Usuario($con, $id_usuario_a_sancionar);
        $tipo_usuario_a_sancionar = $objeto_usuario_a_sancionar->obtenerTipoUsuario();
    
        if(isset($_POST['tipo_busqueda']) && isset($_POST['objeto_a_buscar']))
        {
            $tipo_busqueda = $_POST['tipo_busqueda'];
            $id_objeto_a_buscar = $_POST['objeto_a_buscar'];
            if($tipo_busqueda == "publicacion")
            {
                $query_comprobar_que_la_publicacion_sea_del_usuario = mysqli_query($con, "SELECT * FROM publicaciones WHERE (id_publicacion='$id_objeto_a_buscar' AND publicado_por='$id_usuario_a_sancionar')");
                if(mysqli_num_rows($query_comprobar_que_la_publicacion_sea_del_usuario) == 0)
                {
                    $mensaje_de_error .= "Error, la publicacion seleccionada no pertenece a este usuario!<br>";
                    $sancion_exitosa = 0;
                }
            }
            else if($tipo_busqueda == "comentario")
            {
                $query_comprobar_que_el_comentario_sea_del_usuario = mysqli_query($con, "SELECT * FROM comentarios WHERE (id_comentario='$id_objeto_a_buscar' AND comentado_por='$id_usuario_a_sancionar')");
                if(mysqli_num_rows($query_comprobar_que_el_comentario_sea_del_usuario) == 0)
                {
                    $mensaje_de_error .= "Error, el comentario seleccionado no pertenece a este usuario!<br>";
                    $sancion_exitosa = 0;
                }
            }
        }

        // + Verificar que el usuario pueda sancionar al usuario
        if($tipo_usuario_a_sancionar == "administrador")
        {
            $mensaje_de_error .= "Error, el usuario a sancionar no puede ser un administrador!<br>";
            $sancion_exitosa = 0;
        }
        else if($tipo_usuario_que_sanciono == "administrador" && $tipo_usuario_a_sancionar == "moderador")
        {
            $query_quitar_moderador = mysqli_query($con, "UPDATE usuarios SET tipo='normal' WHERE id_usuario='$id_usuario_a_sancionar'");
        }
        else if($tipo_usuario_que_sanciono == "moderador" && $tipo_usuario_a_sancionar == "moderador")
        {
            $mensaje_de_error .= "Error, usted no puede sancionar a un moderador!<br>";
            $sancion_exitosa = 0;
        }
    }

    $fecha_sancion = "";
    $query_verificar_si_hay_una_sancion_permanente = mysqli_query($con, "SELECT * FROM sanciones WHERE id_usuario_sancionado='$id_usuario_a_sancionar' AND tipo_sancion='permanente'");
    if(mysqli_num_rows($query_verificar_si_hay_una_sancion_permanente) == 0)
    {
        if($tipo_sancion == "permanente")
        {
            $fecha_sancion = NULL;
            $query_eliminar_sanciones_temporales = mysqli_query($con, "DELETE FROM sanciones WHERE id_usuario_sancionado='$id_usuario_a_sancionar' AND tipo_sancion='temporal'");
        }
        else if($tipo_sancion == "temporal")
        {
            $dias_sancion = $_POST['dias_sancion'];
            $horas_sancion = $_POST['horas_sancion'];
            $minutos_sancion = $_POST['minutos_sancion'];
            $query_verificar_que_usuario_sancionado_exista = mysqli_query($con, "SELECT * FROM sanciones WHERE id_usuario_sancionado='$id_usuario_a_sancionar'");

            if(($dias_sancion + $horas_sancion + $minutos_sancion) == 0)
            {
                $mensaje_de_error .= "Error, si la sanción es temporal, debe introducir tiempo!<br>";
                $sancion_exitosa = 0;
            }
            else if(mysqli_num_rows($query_verificar_que_usuario_sancionado_exista) > 0)
            {
                $query_seleccionar_ultima_sancion_usuario = mysqli_query($con, "SELECT * FROM sanciones WHERE id_usuario_sancionado='$id_usuario_a_sancionar' AND id_sancion = (SELECT MAX(id_sancion) FROM sanciones WHERE id_usuario_sancionado ='$id_usuario_a_sancionar')");
                $fila_sancion_existente = mysqli_fetch_array($query_seleccionar_ultima_sancion_usuario);
                $tiempo_restante_sancion_existente = strtotime($fila_sancion_existente['fecha_sancion']) - strtotime(date('Y-m-d H:i:s'));
                
                $dias_nueva_sancion = $_POST['dias_sancion'];
                $horas_nueva_sancion = $_POST['horas_sancion'];
                $minutos_nueva_sancion = $_POST['minutos_sancion'];
                
                $total_minutos_nueva_sancion = $dias_nueva_sancion * 1440 + $horas_nueva_sancion * 60 + $minutos_nueva_sancion;
                $total_minutos_sancion_existente = round($tiempo_restante_sancion_existente / 60);
                
                $total_minutos_nueva_sancion += $total_minutos_sancion_existente;
                $dias_nueva_sancion = floor($total_minutos_nueva_sancion / 1440);
                $horas_nueva_sancion = floor(($total_minutos_nueva_sancion - $dias_nueva_sancion * 1440) / 60);
                $minutos_nueva_sancion = $total_minutos_nueva_sancion % 60;
                
                $fecha_actual = date('Y-m-d H:i:s');
                $fecha_sancion = date('Y-m-d H:i:s', strtotime($fecha_actual ."+"."$dias_nueva_sancion"."days" ."+"."$horas_nueva_sancion"."hours"."+".$minutos_nueva_sancion."minutes"));
            }
            else
            {
                $fecha_actual = date('Y-m-d H:i:s');
                $fecha_sancion = date('Y-m-d H:i:s', strtotime($fecha_actual ."+"."$dias_sancion"."days" ."+"."$horas_sancion"."hours"."+".$minutos_sancion."minutes"));
            }    
        }
    }
    else
    {
        $mensaje_de_error .= "Error, ya existe una sanción permanente para este usuario!<br>";
        $sancion_exitosa = 0; 
    }

    
    if($sancion_exitosa == 1)
    {

        if($tipo_busqueda == "publicacion")
        {
            // + Verificar si la publicacion fue eliminada
            $query_verificar_si_la_publicacion_fue_eliminada = mysqli_query($con, "SELECT * FROM publicaciones WHERE (id_publicacion='$id_objeto_a_buscar' AND borrado='no')");
            if(mysqli_num_rows($query_verificar_si_la_publicacion_fue_eliminada) > 0)
            {
                // + AJAX para eliminar el comentario sancionado
                ?>
                <script>
                    result_eliminacion = true;
                    motivo = "Eliminada por aplicación de sanción";
                    $.post("includes/form_handlers/delete_post.php?id_publicacion=<?php echo $id_objeto_a_buscar; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", { resultado:result_eliminacion, razon:motivo});
                </script>
                <?php
            }
            $query_aplicar_sancion = mysqli_query($con, "INSERT INTO sanciones VALUES ('', '$razon', '$tipo_sancion', '$fecha_sancion', '$id_usuario_a_sancionar', '$id_usuario_que_sanciono', '$id_objeto_a_buscar', NULL)");
            header("Location: sanctions.php");
        }
        else if($tipo_busqueda == "comentario")
        {
            $query_verificar_si_el_comentario_fue_eliminado = mysqli_query($con, "SELECT * FROM comentarios WHERE (id_comentario='$id_objeto_a_buscar' AND eliminado='no')");
            if(mysqli_num_rows($query_verificar_si_el_comentario_fue_eliminado) > 0)
            {
                $query_obtener_id_publicacion_comentario = mysqli_query($con, "SELECT publicacion_comentada FROM comentarios WHERE id_comentario='$id_objeto_a_buscar'");
                $fila_id_publicacion_comentario = mysqli_fetch_array($query_obtener_id_publicacion_comentario);
                $id_publicacion_comentario = $fila_id_publicacion_comentario['publicacion_comentada'];
                // + AJAX para eliminar el comentario sancionado
                ?>
                <script>
                    result_eliminacion = true;
                    motivo = "Eliminado por aplicación de sanción";
                    $.post(
                        "includes/form_handlers/delete_comment.php?id_comentario=<?php echo $id_objeto_a_buscar ?>&id_usuario=<?php echo $id_usuario_loggeado ?>&id_publicacion=<?php echo $id_publicacion_comentario ?>",
                        { resultado:result_eliminacion, razon:motivo }
                    );                
                </script>
                <?php
            }
            $query_aplicar_sancion = mysqli_query($con, "INSERT INTO sanciones VALUES ('', '$razon', '$tipo_sancion', '$fecha_sancion', '$id_usuario_a_sancionar', '$id_usuario_que_sanciono', NULL, '$id_objeto_a_buscar')");
            // header("Location: sanctions.php");
        }
        else if($tipo_busqueda == "")
        {
            $query_aplicar_sancion = mysqli_query($con, "INSERT INTO sanciones VALUES ('', '$razon', '$tipo_sancion', '$fecha_sancion', '$id_usuario_a_sancionar', '$id_usuario_que_sanciono', NULL, NULL)");
            // header("Location: sanctions.php");
        }
    }
    else
    {
        echo "<div class='alert alert-danger' style='text-align:center;'>
                $mensaje_de_error
            </div>";
    }
}

?>
    <div class="contenedor_nueva_sancion">
        <h4>Nueva Sanción</h4>
        <form action="new_sanction.php" method="POST">
            <div class="div_superior_sancion">
                <div class="div_usuario_a_sancionar">
                    <div class="contenedor_busqueda_y_usuario">
                        <input type="text" class="usuario_a_sancionar" name="usuario_a_sancionar" id="usuario_a_sancionar" placeholder="Buscar usuario" 
                        value="<?php
                        echo isset($_POST['usuario_a_sancionar']) ? $_POST['usuario_a_sancionar'] : '';
                        ?>"required>
                    </div>
                    <div class="resultado_busqueda_usuarios_a_sancionar" id="resultado_busqueda_usuarios_a_sancionar"></div>
                </div>
                <div class="div_tipo_sancion">
                    Tipo de sanción:
                    <select name="tipo_sancion" id="tipo_sancion" required>
                    <option value="">Seleccione un tipo</option>
                        <option value="temporal">Temporal</option>
                        <option value="permanente">Permanente</option>
                    </select>
                </div>
            </div>
            <div class="div_central_sancion">
                <textarea name="razon_sancion" id="razon_sancion" placeholder="Razón de la sanción" required><?php echo isset($_POST['razon_sancion']) ? $_POST['razon_sancion'] : ''; ?></textarea>
            </div>
            <div class="div_seleccionar_publicacion_o_comentario">
                <label for="tipo_busqueda">Buscar:</label>
                <select name="tipo_busqueda" id="tipo_busqueda">
                    <option value="">Seleccione una opción</option>
                    <option value="publicacion">Publicación</option>
                    <option value="comentario">Comentario</option>
                </select>
                <input type="text" class="objeto_a_buscar" name="objeto_a_buscar" id="objeto_a_buscar" placeholder="Buscar publicacion / comentario" disabled>
            </div>
            <div class="resultado_busqueda_objeto_a_buscar" id="resultado_busqueda_objeto_a_buscar"></div>
            <div class="div_inferior_sancion">
                <label for="dias_sancion">Días:</label>
                <input type="number" id="dias_sancion" name="dias_sancion" min="0" max="364" value="<?php
                    echo isset($_POST['dias_sancion']) ? $_POST['dias_sancion'] : '0';
                ?>" required>
                <label for="horas_sancion">Horas:</label>
                <input type="number" id="horas_sancion" name="horas_sancion" min="0" max="23" value="<?php
                    echo isset($_POST['horas_sancion']) ? $_POST['horas_sancion'] : '0';
                ?>" required>
                <label for="minutos_sancion">Minutos:</label>
                <input type="number" id="minutos_sancion" name="minutos_sancion" min="0" max="59" value="<?php
                    echo isset($_POST['minutos_sancion']) ? $_POST['minutos_sancion'] : '0';
                ?>" required>
                <input type="submit" class="danger" name="boton_aplicar_sancion" id="boton_aplicar_sancion" value="Aplicar">
            </div>
        </form>

        <!-- //+ Este script mandara a buscar a los usuarios que coincidan con la query y al darle click, pondra el usuario en el campo -->
        <script>
            $(document).ready(function() {
                $('#usuario_a_sancionar').on('input', function() {
                    var query = $(this).val();
                    var id_usuario_loggeado = '<?php echo $id_usuario_loggeado ?>';
                    $.ajax({
                        url: 'includes/handlers/ajax_search_user_to_sanction.php',
                        type: 'POST',
                        data: {query:query, id_usuario_loggeado:id_usuario_loggeado},
                        success: function(data) {
                            $('#resultado_busqueda_usuarios_a_sancionar').html(data);
                            $('.displayResultado').click(function(){
                                var nombre_usuario = $(this).find('.username_to_sanction').text().trim();
                                $('#usuario_a_sancionar').val(nombre_usuario);
                                $('#resultado_busqueda_usuarios_a_sancionar').empty();
                            });
                        }
                    });
                });
            });
        </script>

        <!-- //+ Este script mandara a buscar a las publicaciones o comentarios que coincidan con el usuario que se busco-->
        <script>
            $(document).ready(function() {
                $('#objeto_a_buscar').on('input', function() {
                    var tipo_busqueda = $('#tipo_busqueda').val();
                    var nombre_usuario = $('#usuario_a_sancionar').val();
                    if(tipo_busqueda != "" && nombre_usuario != "")
                    {
                        var query = $(this).val();
                        $.ajax({
                            url: 'includes/handlers/ajax_search_post_comment.php',
                            type: 'POST',
                            data: {query:query, tipo_busqueda:tipo_busqueda, nombre_usuario:nombre_usuario},
                            success: function(data) {
                                $('#resultado_busqueda_objeto_a_buscar').html(data);
                                $('.displayResultadoObjeto').click(function(){
                                    var objeto_seleccionado = $(this).find('.id_objeto_sancion').text().trim();
                                    $('#objeto_a_buscar').val(objeto_seleccionado)
                                    $('#resultado_busqueda_objeto_a_buscar').empty();
                                });
                            }
                        });
                    }
                    else
                    {
                        alert("Debe introducir un nombre de usuario antes de buscar")
                    }
                });
            });
        </script>

        <!-- //+ Este script deshabilidara el campo de tiempo de sancion si la sancion es permanente -->
        <script>
            $(document).ready(function() {
                $('#tipo_sancion').change(function() {
                    if ($(this).val() == 'permanente') {
                        $('#dias_sancion, #horas_sancion, #minutos_sancion').prop('disabled', true).val(0);
                    } else {
                        $('#dias_sancion, #horas_sancion, #minutos_sancion').prop('disabled', false);
                    }
                });
            });
        </script>

        <!-- // + Este script se encargara de deshabilitar buscar un comentario o publiacion si no se eligio nada.  -->
        <!--   // + Tambien reseteara el buscador si se cambia de comentario a publicacion o viceversa -->
        <script>
            $(document).ready(function() {
                $('#tipo_busqueda, #usuario_a_sancionar').on('change', function() {
                    var tipo_busqueda = $('#tipo_busqueda').val();
                    var usuario_a_sancionar = $('#usuario_a_sancionar').val();
                    if(usuario_a_sancionar != "")
                    {
                        if (tipo_busqueda === 'publicacion') {
                        $('#objeto_a_buscar').prop('disabled', false).val('');
                        $('#objeto_a_buscar').attr('required', true);
                        $('#resultado_busqueda_objeto_a_buscar').empty();
                        } else if (tipo_busqueda === 'comentario') {
                            $('#objeto_a_buscar').prop('disabled', false).val('');
                            $('#objeto_a_buscar').attr('required', true);
                            $('#resultado_busqueda_objeto_a_buscar').empty();
                        } else {
                            $('#objeto_a_buscar').prop('disabled', true).val('');
                            $('#objeto_a_buscar').attr('required', false);
                            $('#resultado_busqueda_objeto_a_buscar').empty();

                        }
                    }
                });
            });


            $('#usuario_a_sancionar').on('input', function() {
                    var tipo_busqueda = $('#tipo_busqueda').val();
                    var usuario_a_sancionar = $('#usuario_a_sancionar').val();
                    if(tipo_busqueda != "" && usuario_a_sancionar != "")
                    {
                        $('#objeto_a_buscar').prop('disabled', false).val('');
                        $('#objeto_a_buscar').attr('required', true);
                        $('#resultado_busqueda_objeto_a_buscar').empty();
                    }
                    else
                    {
                        $('#objeto_a_buscar').prop('disabled', true).val('');
                        $('#objeto_a_buscar').attr('required', false);
                        $('#resultado_busqueda_objeto_a_buscar').empty();
                    }
                });
        </script>

    </div>
</div>  <!-- Cierre de div cuerpo_principal -->
</body>
</html>