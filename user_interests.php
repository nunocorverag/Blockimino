<?php
include("includes/header.php");

if(isset($_POST['agregar_interes']))
{
    $interes = $_POST['buscar_interes'];
    $insercion_exitosa = 1;

    if($interes == "")
    {
        $mensaje_de_error = "La busqueda esta vacia!";
        $insercion_exitosa = 0;
    }
    $query_verificar_hashtag = mysqli_query($con, "SELECT * FROM hashtags WHERE hashtag = '$interes'");
    if(!(mysqli_num_rows($query_verificar_hashtag) > 0))
    {
        $mensaje_de_error = "El hashtag no existe!";
        $insercion_exitosa = 0;
    }

    if($insercion_exitosa)
    {
        $fila_hashtag = mysqli_fetch_array($query_verificar_hashtag);
        $id_hashtag = $fila_hashtag['id_hashtag'];
        $query_agregar_interes = mysqli_query($con, "INSERT INTO temas_interes VALUES ('', '$id_usuario_loggeado', '$id_hashtag', '1')");
        header("Location: user_interests.php");
    }
    else
    {
        echo "<div class='alert alert-danger' style='text-align:center;'>
                $mensaje_de_error
            </div>";
    }
}

$query_obtener_no_intereses_usuario = mysqli_query($con, "SELECT hashtags.hashtag
                                                            FROM temas_interes
                                                            INNER JOIN hashtags ON temas_interes.id_hashtag_interes = hashtags.id_hashtag 
                                                            WHERE id_hashtag_interes NOT IN (SELECT id_hashtag_interes FROM temas_interes WHERE id_usuario_interesado = $id_usuario_loggeado)
                                                            GROUP BY id_hashtag_interes");

$mostrar_opc_intereses = (mysqli_num_rows($query_obtener_no_intereses_usuario) > 0);

?>
<div class="contenedor_intereses">
    <h4>Agregar intereses</h4>
    <p>Agrega un interes a los temas que te interesan</p>
    <?php
    if($mostrar_opc_intereses)
    {
        ?>
        <form action="user_interests.php" method="POST">
            <input type="text" name="buscar_interes" id="buscar_interes" placeholder="Buscar interes">
            <div class="interes_resultados_container" id="interes_resultados_container">

            </div>
            <input type="submit" name="agregar_interes" id="agregar_interes" value="Agregar">
        </form>

        <!-- //+ En este script se buscaran los hashtags con los que coincida -->
        <script>
            $(document).ready(function() {
                $('#buscar_interes').on('input', function() {
                    var query = $(this).val();
                    $.ajax({
                        url: 'includes/handlers/ajax_search_interest.php?id_usuario=<?php echo $id_usuario_loggeado?>',
                        type: 'POST',
                        data: {query:query},
                        success: function(data) {
                            $('#interes_resultados_container').html(data);
                            $('.displayResultadoHashtag').click(function(){
                                var nombre_usuario = $(this).find('.hashtag_encontrado').text().trim();
                                $('#buscar_interes').val(nombre_usuario);
                                $('#interes_resultados_container').empty();
                            });
                        }
                    });
                });
            });
        </script>
        <?php
    }
    else
    {
        ?>
        <p>Ya cuentas con todos los intereses!</p>
        <?php
    }
    ?>

    <h4>Aquí puedes ver todos los temas en los que has mostrado interés</h4>
    <p>Puedes cambiar la cantidad de interes y esto hara que los temas marcados como mas interesantes tengan mas prioridad que los otros</p>
    <p>Puedes eliminar algún interes si es que ya no lo encuentras útil</p>
    <?php

    $query_seleccionar_intereses_usuario = mysqli_query($con, "SELECT temas_interes.id_interes, hashtags.hashtag, temas_interes.cantidad_interes 
                                                                FROM temas_interes 
                                                                INNER JOIN hashtags ON temas_interes.id_hashtag_interes = hashtags.id_hashtag 
                                                                WHERE temas_interes.id_usuario_interesado = $id_usuario_loggeado 
                                                                ORDER BY cantidad_interes DESC, id_hashtag_interes DESC");

    if(mysqli_num_rows($query_seleccionar_intereses_usuario) > 0)
    {
        ?>

        <table class="tabla_intereses table table-striped table-bordered" style="border-collapse: collapse;">
            <thead class="table-blue-light">
                <tr>
                    <th style="width: 15%; text-align: center">Hashtag</th>
                    <th style="width: 15%; text-align: center">Cantidad de Interés</th>
                    <th style="width: 15%; text-align: center">Acciones</th>
                </tr>
            </thead>


            <?php

            while($fila_info_hashtags_interes = mysqli_fetch_array($query_seleccionar_intereses_usuario)){
                $id_interes = $fila_info_hashtags_interes['id_interes'];
                $hashtag = $fila_info_hashtags_interes['hashtag'];
                $nombre_sin_hashtag = str_replace("#", "", $hashtag);

                $cantidad_interes = $fila_info_hashtags_interes['cantidad_interes'];

                
                $hashtag_corto = (strlen($hashtag) >= 12) ? substr($hashtag, 0, 12) . "..." : $hashtag;
                $hashtag_completo = htmlentities($hashtag); // Escapar caracteres especiales para mostrar correctamente el hashtag completo en el tooltip
                
                ?>
                
                <tr>
                    <td>
                        <a href="publication_hashtag.php?hashtag=<?php echo $nombre_sin_hashtag?>">
                            <span class="hashtag" data-tooltip="<?php echo $hashtag_completo ?>"><?php echo $hashtag_corto ?></span>
                        </a>
                    </td>
                    <td><?php echo $cantidad_interes ?></td>
                    <td>
                        <button class="editar_interes info" id="editar_interes<?php echo $id_interes?>">Editar</button>
                        <button class="eliminar_interes danger" id="eliminar_interes<?php echo $id_interes?>">Eliminar</button>
                    </td>
                </tr>


                <!-- // + Script para mostrar el hashtag completo -->
                <script>
                $(document).ready(function() {
                  $('.hashtag').mouseover(function() {
                    var tooltipText = $(this).data('tooltip');
                    $(this).attr('title', tooltipText);
                  });
                });
                </script>

                <script>
                    $(document).ready(function(){

                        $('#editar_interes<?php echo $id_interes ?>').on('click', function() {
                            bootbox.prompt("Introduce una nueva cantidad de interés: (Entre 1 y 500)", function(result) {
                                if(result != null)
                                {
                                    if(isNaN(result) || result < 1 || result > 500) {
                                        bootbox.alert("La cantidad de interés debe ser un número entre 1 y 500.");
                                    } else {
                                        $.post("includes/handlers/ajax_edit_interest.php?id_usuario=<?php echo $id_usuario_loggeado?>&id_interes=<?php echo $id_interes?>", {resultado:result});
                                        location.reload();
                                    }
                                }
                            });
                        });

                        $('#eliminar_interes<?php echo $id_interes ?>').on('click', function() {
                            bootbox.confirm("¿Estas seguro que quieres eliminar este interes", function(result) {
                                if(result == true)
                                {
                                    $.post("includes/handlers/ajax_delete_interest.php?id_usuario=<?php echo $id_usuario_loggeado?>&id_interes=<?php echo $id_interes?>", {resultado:result});
                                    location.reload();
                                }
                            });
                        });
                    });
                </script>

                <?php
            }
            ?>

        </table>


        <?php
    }
    else
    {
        ?>
        <div class='alert alert-secondary' style='text-align:center;'>
            <p>No tienes intereses, agrega intereses en la parte superior!</p>
        </div>
        <?php
    }
    ?>

</div>
