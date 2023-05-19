<!-- Sera la lista de amigos que tiene un usuario -->
<?php
include("includes/header.php");

if (isset($_GET['perfil_usuario']))
{
    $perfil_nombre_usuario = $_GET['perfil_usuario'];
    $query_obtener_amigos_usuario_perfil = mysqli_query($con, "SELECT id_usuario, lista_amigos FROM usuarios WHERE username='$perfil_nombre_usuario'");
    $fila = mysqli_fetch_array($query_obtener_amigos_usuario_perfil);
    $id_usuario_perfil = $fila['id_usuario'];
    $lista_amigos = $fila['lista_amigos'];
    $lista_amigos_explode = explode(",", $lista_amigos);
    // $ array_filter -> Elimina los elementos vacios dentro de un arreglo
    $lista_amigos_explode = array_filter($lista_amigos_explode);
    $total_amigos = count($lista_amigos_explode);
}

?>
    <div class="cuerpo_pagina_amigos">
    <?php

    // + Aqui definiremos las reglas de la pagina
    // + Los amigos por pagina seran 10
    $amigos_por_pagina = 10;

    // + Obtenemos la pagina actual
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

    $offset = ($pagina_actual - 1) * $amigos_por_pagina;

    // $ array_slice ->
    $amigos_pagina_actual = array_slice($lista_amigos_explode, $offset, $amigos_por_pagina);

    $cantidad_amigos_pagina_actual = count($amigos_pagina_actual);

    // $ceil ->
    $total_paginas = ceil($total_amigos / $amigos_por_pagina);

    $offset = 0;
    $contador = 0;

    $offset += $amigos_por_pagina;

    $usuario = new Usuario($con, $id_usuario_loggeado);

    if($cantidad_amigos_pagina_actual > 1)
    {
    ?>

    <table class="tabla_usuarios_encontrados">
        <tr>
            <td colspan="2" class="titulo_tabla_usuarios_encontrados">
                <span>
                    Lista de amigos
                </span>
            </td>
        </tr>
        <tr>
            <?php
            $i = 0;
            $iterar = 0;
            foreach($amigos_pagina_actual as $id_amigo)
            {

                if ($id_amigo != "")
                {
                    // + Esta variable separara las dos columnas de la tabla en diferentes clases
                    $iterar ++;
                    // + Este indice determinara cuantos amigos cargara
                    $query_info_grupo = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='$id_amigo'");
                    $fila_info_amigo = mysqli_fetch_array($query_info_grupo);
                    
                    if($id_usuario_loggeado != $id_amigo)
                    {
                        if($usuario->obtenerAmigosMutuos($id_amigo) == 1)
                        {
                            $amigos_mutuos = $usuario->obtenerAmigosMutuos($id_amigo) . " Amigo en comun";
                        }
                        else
                        {
                            $amigos_mutuos = $usuario->obtenerAmigosMutuos($id_amigo) . " Amigos en comun";
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
                                    <a href='../" . $fila_info_amigo['username'] . "' style='color: 000'>
                                        <img src='../". $fila_info_amigo['foto_perfil'] . "'>
                                    </a>
                                </div>
                                    
                                <div class='infoUsuario'>
                                    <span>
                                        <a href='../" . $fila_info_amigo['username'] . "' style='color: 000'>
                                            " . $fila_info_amigo['nombre'] . " " . $fila_info_amigo['apeP'] . " " . $fila_info_amigo['apeM'] . "
                                        </a>
                                    </span>
                                    <p style='margin: 0'>
                                        <a href='../" . $fila_info_amigo['username'] . "' style='color: 000'>"
                                            .$fila_info_amigo['username'] . 
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
    else if ($cantidad_amigos_pagina_actual == 1)
    {
        ?>
        <div class="titulo_tabla_usuarios_encontrados">
            <span>
                Lista de amigos
            </span>
        </div>

        <?php
        $columna = "td_derecha";

        $id_amigo = $amigos_pagina_actual[0];

        if($id_usuario_loggeado != $id_amigo)
        {
            if($usuario->obtenerAmigosMutuos($id_amigo) == 1)
            {
                $amigos_mutuos = $usuario->obtenerAmigosMutuos($id_amigo) . " Amigo en comun";
            }
            else
            {
                $amigos_mutuos = $usuario->obtenerAmigosMutuos($id_amigo) . " Amigos en comun";
            }
        }
        else
        {
            $amigos_mutuos = "";
        }

        
        $query_primer_amigo = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='$id_amigo'");
        $fila_info_amigo = mysqli_fetch_array($query_primer_amigo);
    
        
        echo "<div class='col-sm-4'>
                <div class='displayUsuario columna_un_usuario'>
                    <div class='fotoPerfilUsuario'>
                        <a href='../" . $fila_info_amigo['username'] . "' style='color: 000'>
                            <img src='../". $fila_info_amigo['foto_perfil'] . "'>
                        </a>
                    </div>
                        
                    <div class='infoUsuario'>
                        <span>
                            <a href='../" . $fila_info_amigo['username'] . "' style='color: 000'>
                                " . $fila_info_amigo['nombre'] . " " . $fila_info_amigo['apeP'] . " " . $fila_info_amigo['apeM'] . "
                            </a>
                        </span>
                            <p style='margin: 0'>
                                <a href='../" . $fila_info_amigo['username'] . "' style='color: 000'>"
                                    .$fila_info_amigo['username'] . 
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
        echo '<p style="text-align: center;">Este usuario no tiene amigos agregados.</p>';
    }
        
    ?>

    <div class="cuerpo_inferior_pag_amigos">
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
        function cargarAmigos(pagina)
        {
            $.ajax({
                url: 'friends.php',
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