<!-- Sera la lista de amigos que tiene un usuario -->
<?php
//Incluimos el archivo de header para backslash
include("includes/header.php");

if (isset($_GET['perfil_usuario']))
{
    $perfil_nombre_usuario = $_GET['perfil_usuario'];
    $query_obtener_seguidos_usuario_perfil = mysqli_query($con, "SELECT id_usuario, lista_seguidos FROM usuarios WHERE username='$perfil_nombre_usuario'");
    $fila = mysqli_fetch_array($query_obtener_seguidos_usuario_perfil);
    $id_usuario_perfil = $fila['id_usuario'];
    $lista_seguidos = $fila['lista_seguidos'];
    $lista_seguidos_explode = explode(",", $lista_seguidos);
    // $ array_filter -> Elimina los elementos vacios dentro de un arreglo
    $lista_seguidos_explode = array_filter($lista_seguidos_explode);
    $total_seguidos = count($lista_seguidos_explode);
}

?>
    <div class="cuerpo_pagina_seguidos">
    <?php

    // + Aqui definiremos las reglas de la pagina
    // + Los amigos por pagina seran 10
    $seguidos_por_pagina = 10;

    // + Obtenemos la pagina actual
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

    $offset = ($pagina_actual - 1) * $seguidos_por_pagina;

    // $ array_slice ->
    $seguidos_pagina_actual = array_slice($lista_seguidos_explode, $offset, $seguidos_por_pagina);

    $cantidad_seguidos_pagina_actual = count($seguidos_pagina_actual);

    // $ceil ->
    $total_paginas = ceil($total_seguidos / $seguidos_por_pagina);

    $offset = 0;
    $contador = 0;

    $offset += $seguidos_por_pagina;

    $usuario = new Usuario($con, $id_usuario_loggeado);

    if($cantidad_seguidos_pagina_actual > 1)
    {
    ?>

    <table class="tabla_usuarios_encontrados">
        <tr>
            <td colspan="2" class="titulo_tabla_usuarios_encontrados">
                <span>
                    Lista de seguidos
                </span>
            </td>
        </tr>
        <tr>
            <?php
            $i = 0;
            $iterar = 0;
            foreach($seguidos_pagina_actual as $id_seguido)
            {

                if ($id_seguido != "")
                {
                    // + Esta variable separara las dos columnas de la tabla en diferentes clases
                    $iterar ++;
                    // + Este indice determinara cuantos amigos cargara
                    $query_info_seguido = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='$id_seguido'");
                    $fila_info_seguido = mysqli_fetch_array($query_info_seguido);
                    
                    if($id_usuario_loggeado != $id_seguido)
                    {
                        if($usuario->obtenerAmigosMutuos($id_seguido) == 1)
                        {
                            $amigos_mutuos = $usuario->obtenerAmigosMutuos($id_seguido) . " Amigo en comun";
                        }
                        else
                        {
                            $amigos_mutuos = $usuario->obtenerAmigosMutuos($id_seguido) . " Amigos en comun";
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
                                    <a href='../" . $fila_info_seguido['username'] . "' style='color: 000'>
                                        <img src='../". $fila_info_seguido['foto_perfil'] . "'>
                                    </a>
                                </div>
                                    
                                <div class='infoUsuario'>
                                    <span>
                                        <a href='../" . $fila_info_seguido['username'] . "' style='color: 000'>
                                            " . $fila_info_seguido['nombre'] . " " . $fila_info_seguido['apeP'] . " " . $fila_info_seguido['apeM'] . "
                                        </a>
                                    </span>
                                        <p style='margin: 0'>
                                            <a href='../" . $fila_info_seguido['username'] . "' style='color: 000'>"
                                                .$fila_info_seguido['username'] . 
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
    else if ($cantidad_seguidos_pagina_actual == 1)
    {
        ?>
        <div class="titulo_tabla_usuarios_encontrados">
            <span>
                Lista de seguidos
            </span>
        </div>

        <?php
        $columna = "td_derecha";

        $id_seguido = $seguidos_pagina_actual[0];

        if($id_usuario_loggeado != $id_seguido)
        {
            if($usuario->obtenerAmigosMutuos($id_seguido) == 1)
            {
                $amigos_mutuos = $usuario->obtenerAmigosMutuos($id_seguido) . " Amigo en comun";
            }
            else
            {
                $amigos_mutuos = $usuario->obtenerAmigosMutuos($id_seguido) . " Amigos en comun";
            }
        }
        else
        {
            $amigos_mutuos = "";
        }

        
        $query_primer_amigo = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='$id_seguido'");
        $fila_info_seguido = mysqli_fetch_array($query_primer_amigo);
    
        
        echo "<div class='col-sm-4'>
                <div class='displayUsuario columna_un_usuario'>
                    <div class='fotoPerfilUsuario'>
                        <a href='../" . $fila_info_seguido['username'] . "' style='color: 000'>
                            <img src='../". $fila_info_seguido['foto_perfil'] . "'>
                        </a>
                    </div>
                        
                    <div class='infoUsuario'>
                        <span>
                            <a href='../" . $fila_info_seguido['username'] . "' style='color: 000'>
                                " . $fila_info_seguido['nombre'] . " " . $fila_info_seguido['apeP'] . " " . $fila_info_seguido['apeM'] . "
                            </a>
                        </span>
                            <p style='margin: 0'>
                                <a href='../" . $fila_info_seguido['username'] . "' style='color: 000'>"
                                    .$fila_info_seguido['username'] . 
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
        echo '<p style="text-align: center;">Este usuario no sigue a ningún usuario.</p>';
    }
        
    ?>

    <div class="cuerpo_inferior_pag_seguidos">
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
        function cargarSeguidos(pagina)
        {
            $.ajax({
                url: 'followed.php',
                type: 'GET',
                data: pagina,
                success: function(resultado)
                {
                    $('$lista-amigos').html(resultado);
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