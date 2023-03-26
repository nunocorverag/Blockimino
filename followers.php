<!-- Sera la lista de amigos que tiene un usuario -->
<?php
//Incluimos el archivo de header para backslash
include("includes/header_for_one_backslash.php");

if (isset($_GET['perfil_usuario']))
{
    $perfil_nombre_usuario = $_GET['perfil_usuario'];
    $query_obtener_amigos_usuario_perfil = mysqli_query($con, "SELECT id_usuario, lista_seguidores FROM usuarios WHERE username='$perfil_nombre_usuario'");
    $fila = mysqli_fetch_array($query_obtener_amigos_usuario_perfil);
    $id_usuario_perfil = $fila['id_usuario'];
    $lista_seguidores = $fila['lista_seguidores'];
    $lista_seguidores_explode = explode(",", $lista_seguidores);
    // $ array_filter -> Elimina los elementos vacios dentro de un arreglo
    $lista_seguidores_explode = array_filter($lista_seguidores_explode);
    $total_seguidores = count($lista_seguidores_explode);
}

?>
    <div class="cuerpo_pagina_seguidores">
    <?php




    // + Aqui definiremos las reglas de la pagina
    // + Los amigos por pagina seran 10
    $seguidores_por_pagina = 10;

    // + Obtenemos la pagina actual
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

    $offset = ($pagina_actual - 1) * $seguidores_por_pagina;

    // $ array_slice ->
    $seguidores_pagina_actual = array_slice($lista_seguidores_explode, $offset, $seguidores_por_pagina);

    $cantidad_seguidores_pagina_actual = count($seguidores_pagina_actual);

    // $ceil ->
    $total_paginas = ceil($total_seguidores / $seguidores_por_pagina);

    $offset = 0;
    $contador = 0;

    $offset += $seguidores_por_pagina;

    $usuario = new Usuario($con, $id_usuario_loggeado);

    if($cantidad_seguidores_pagina_actual > 1)
    {
    ?>

    <table class="tabla_usuarios_encontrados">
        <tr>
            <td colspan="2" class="titulo_tabla_usuarios_encontrados">
                <span>
                    Lista de seguidores
                </span>
            </td>
        </tr>
        <tr>
            <?php
            $i = 0;
            $iterar = 0;
            foreach($seguidores_pagina_actual as $id_seguidor)
            {

                if ($id_seguidor != "")
                {
                    // + Esta variable separara las dos columnas de la tabla en diferentes clases
                    $iterar ++;
                    // + Este indice determinara cuantos amigos cargara
                    $query_info_seguidor = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='$id_seguidor'");
                    $fila_info_seguidor = mysqli_fetch_array($query_info_seguidor);
                    
                    if($id_usuario_loggeado != $id_seguidor)
                    {
                        if($usuario->obtenerAmigosMutuos($id_seguidor) == 1)
                        {
                            $amigos_mutuos = $usuario->obtenerAmigosMutuos($id_seguidor) . " Amigo en comun";
                        }
                        else
                        {
                            $amigos_mutuos = $usuario->obtenerAmigosMutuos($id_seguidor) . " Amigos en comun";
                        }
                    }
                    else
                    {
                        $amigos_mutuos = "";
                    }
                    if ($i % 2 == 0 && $i != 0) {
                        echo "</tr><tr>";
                    }

                    // + Verificara cuantas veces ha iterado para ver si es derecha o izquierda
                    if($iterar == 1)
                    {
                        $columna = "td_derecha";
                    }
                    else
                    {
                        $columna = "td_izquierda";
                        $iterar = 0;
                    }
                    

                    echo "<td class='diplay_td'>
                            <div class='displayUsuario " . $columna . "'>
                                <div class='fotoPerfilUsuario'>
                                    <a href='../" . $fila_info_seguidor['username'] . "' style='color: 000'>
                                        <img src='../". $fila_info_seguidor['foto_perfil'] . "'>
                                    </a>
                                </div>
                                    
                                <div class='infoUsuario'>
                                    <span>
                                        <a href='../" . $fila_info_seguidor['username'] . "' style='color: 000'>
                                            " . $fila_info_seguidor['nombre'] . " " . $fila_info_seguidor['apeP'] . " " . $fila_info_seguidor['apeM'] . "
                                        </a>
                                    </span>
                                        <p style='margin: 0'>
                                            <a href='../" . $fila_info_seguidor['username'] . "' style='color: 000'>"
                                                .$fila_info_seguidor['username'] . 
                                            "</a>
                                        </p>
                                        <p id='gris'> ". $amigos_mutuos . "</p>
                                </div>
                            </div>
                        </td>";  
                    $i++;
                }
            }
            ?>
        </tr>
    </table>
                <?php
    }
    else if ($cantidad_seguidores_pagina_actual == 1)
    {
        ?>
        <div class="titulo_tabla_usuarios_encontrados">
            <span>
                Lista de seguidores
            </span>
        </div>

        <?php
        $columna = "td_derecha";

        $id_seguidor = $seguidores_pagina_actual[0];

        if($id_usuario_loggeado != $id_seguidor)
        {
            if($usuario->obtenerAmigosMutuos($id_seguidor) == 1)
            {
                $amigos_mutuos = $usuario->obtenerAmigosMutuos($id_seguidor) . " Amigo en comun";
            }
            else
            {
                $amigos_mutuos = $usuario->obtenerAmigosMutuos($id_seguidor) . " Amigos en comun";
            }
        }
        else
        {
            $amigos_mutuos = "";
        }

        
        $query_primer_seguidor = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='$id_seguidor'");
        $fila_info_seguidor = mysqli_fetch_array($query_primer_seguidor);
    
        
        echo "<div class='col-sm-4'>
                <div class='displayUsuario columna_un_usuario'>
                    <div class='fotoPerfilUsuario'>
                        <a href='../" . $fila_info_seguidor['username'] . "' style='color: 000'>
                            <img src='../". $fila_info_seguidor['foto_perfil'] . "'>
                        </a>
                    </div>
                        
                    <div class='infoUsuario'>
                        <span>
                            <a href='../" . $fila_info_seguidor['username'] . "' style='color: 000'>
                                " . $fila_info_seguidor['nombre'] . " " . $fila_info_seguidor['apeP'] . " " . $fila_info_seguidor['apeM'] . "
                            </a>
                        </span>
                            <p style='margin: 0'>
                                <a href='../" . $fila_info_seguidor['username'] . "' style='color: 000'>"
                                    .$fila_info_seguidor['username'] . 
                                "</a>
                            </p>
                            <p id='gris'> ". $amigos_mutuos . "</p>
                    </div>
                </div>
            </div>";
            ?>
            <?php

    }
    else 
    {
        echo '<p style="text-align: center;">Este usuario es seguido por ningún usuario.</p>';
    }
        
    ?>

    <div class="cuerpo_inferior_pag_seguidores">
        <div class="paginacion">
        <?php
            // Mostrar los botones de anterior y siguiente
            // ! Es necesario explicar este algoritmo
            if ($total_paginas > 1) {
                echo '<br>';
                if ($pagina_actual > 1) {
                    echo '<a href="?pagina=' . ($pagina_actual - 1) . '"><i class="fa-regular fa-chevron-left"></i></a> ';
                }
                if ($pagina_actual < $total_paginas) {
                    echo '<a href="?pagina=' . ($pagina_actual + 1) . '"><i class="fa-regular fa-chevron-right"></i></a>';
                }
            }
        ?>
        </div>
    </div>

    <script>
        function cargarSeguidores(pagina)
        {
            $.ajax({
                url: 'followers.php',
                type: 'GET',
                data: pagina,
                success: function(resultado)
                {
                    $('$lista-seguidores').html(resultado);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }
    </script>

</div> 
</body>
</html>