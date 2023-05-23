<?php
include("includes/header.php");

?>

<div class="cuerpo_buscar_grupos">
    <h4>Buscar proyectos</h4>
    <div class="busqueda_proyetosr">
        <form action="search_project.php" method="GET" name="formulario_busqueda_proyecto">
            <input type="text" onkeyup="obtenerLiveSearchBuscarProyectos(this.value, '<?php echo $id_usuario_loggeado?>')" name="query" placeholder="Buscar proyectos" autocomplete="off" id="input_busqueda_texto">
            <div class="contenedor_boton_busqueda_proyecto">
                <button type="submit" class="search-button" style="background: none; border: none;">
                    <i class="fas fa-search" style="color: #000;"></i>
                </button>                       
            </div>
        </form>

        <!-- <div class="resultados_busqueda_buscar_proyectos">
            
        </div> -->

        <!-- // ! Hay que explicar este -->
        <!-- <div class="resultados_busqueda_buscar_proyectos_pie_pagina_vacios">

        </div> -->
    </div>
    <br><br><br>

        <?php 
        if(isset($_GET['query']))
        {
            $query = $_GET['query'];

            if($query != "")
            {
                // + Separamos los elementos de la busqueda
                $nombres = explode(" ", $query);

                // + Contara el numero de nombres en el arreglo
                if(count($nombres) >= 4)
                {
                    $proyectosRetornadosQuery = "";
                }

                // + Esta busqueda incluye el nombre y los apellidos
                else if(count($nombres) == 3)
                {
                    $proyectosRetornadosQuery = mysqli_query($con, "SELECT p.*, u.*
                                                                        FROM proyectos p
                                                                        JOIN usuarios u ON p.id_usuario_proyecto = u.id_usuario
                                                                        WHERE (nombre LIKE '%$nombres[0]%' AND apeP LIKE '%$nombres[1]%' AND apeM LIKE '%$nombres[2]%')
                                                                        AND u.usuario_cerrado = 'no'
                                                                        AND p.visibilidad = '1'
                                                                        AND u.id_usuario <> $id_usuario_loggeado");
                }

                // + Esta busqueda incluye el nombre y un apellido
                else if(count($nombres) == 2)
                {
                    $proyectosRetornadosQuery = mysqli_query($con, "SELECT p.*, u.*
                                                                        FROM proyectos p
                                                                        JOIN usuarios u ON p.id_usuario_proyecto = u.id_usuario                                                                                        
                                                                        WHERE 
                                                                            (u.nombre LIKE '%$nombres[0]%' AND u.apeP LIKE '%$nombres[1]%') 
                                                                            OR 
                                                                            (u.nombre LIKE '%$nombres[0]%' AND u.apeM LIKE '%$nombres[1]%')
                                                                            AND u.usuario_cerrado = 'no'
                                                                            AND p.visibilidad = '1'
                                                                            AND u.id_usuario <> $id_usuario_loggeado");
                }
                // + Esta busqueda es para los usuarios
                else if(count($nombres) == 1)
                {
                    $proyectosRetornadosQuery = mysqli_query($con, "SELECT p.*, u.*
                                                                        FROM proyectos p
                                                                        JOIN usuarios u ON p.id_usuario_proyecto = u.id_usuario  
                                                                        WHERE
                                                                            (u.nombre LIKE '%$nombres[0]%' 
                                                                            OR u.apeP LIKE '%$nombres[0]%'
                                                                            OR u.apeM LIKE '%$nombres[0]%'
                                                                            OR u.username LIKE '$nombres[0]%'
                                                                            OR p.nombre_proyecto LIKE '%$nombres[0]%')
                                                                            AND p.visibilidad = '1'
                                                                            AND u.id_usuario <> $id_usuario_loggeado");
                }
            }
            else
            {
                $proyectosRetornadosQuery = "";
            }


            if($proyectosRetornadosQuery != "")
            {
                ?>
                <div class="contenedor_proyectos">
                <?php
                    while($fila = mysqli_fetch_array($proyectosRetornadosQuery))
                    {
                        $nombre_proyecto = $fila['nombre_proyecto'];
        
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
                                <img src="assets\images\icons\blockimino.png">
                            </div>
                            <br>
                            <br>
                            <div class="contenedor_botones_accion_proyecto">
                                <button class="boton_copiar_proyecto btn btn-info" id=copiar_proyecto<?php echo $nombre_proyecto?> >Copiar Proyecto</button>
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
                                                url: 'includes/handlers/ajax_copy_project.php?id_usuario=<?php echo $id_usuario_loggeado?>&link_proyecto=<?php echo $link_proyecto?>',
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
                    ?>
                </div>
                <?php
            }

        }
    ?>
</div>
