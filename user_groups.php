<!-- Sera la lista de amigos que tiene un usuario -->
<?php
//Incluimos el archivo de header para backslash
include("includes/header.php");

if (isset($_GET['perfil_usuario']))
{
    $perfil_nombre_usuario = $_GET['perfil_usuario'];
    $query_obtener_grupos_usuario_perfil = mysqli_query($con, "SELECT id_usuario, lista_grupos FROM usuarios WHERE username='$perfil_nombre_usuario'");
    $fila = mysqli_fetch_array($query_obtener_grupos_usuario_perfil);
    $id_usuario_perfil = $fila['id_usuario'];
    $lista_grupos = $fila['lista_grupos'];
    $lista_grupos_explode = explode(",", $lista_grupos);
    // $ array_filter -> Elimina los elementos vacios dentro de un arreglo
    $lista_grupos_explode = array_filter($lista_grupos_explode);
    $total_grupos = count($lista_grupos_explode);
}

?>
    <div class="cuerpo_pagina_amigos">
    <?php

    // + Aqui definiremos las reglas de la pagina
    // + Los amigos por pagina seran 10
    $grupos_por_pagina = 6;

    // + Obtenemos la pagina actual
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

    $offset = ($pagina_actual - 1) * $grupos_por_pagina;

    // $ array_slice ->
    $grupos_pagina_actual = array_slice($lista_grupos_explode, $offset, $grupos_por_pagina);

    $cantidad_grupos_pagina_actual = count($grupos_pagina_actual);

    // $ceil ->
    $total_paginas = ceil($total_grupos / $grupos_por_pagina);

    $offset = 0;
    $contador = 0;

    $offset += $grupos_por_pagina;

    $usuario = new Usuario($con, $id_usuario_loggeado);

    if($cantidad_grupos_pagina_actual > 1)
    {
    ?>

    <table class="tabla_usuarios_encontrados">
        <tr>
            <td colspan="2" class="titulo_tabla_usuarios_encontrados">
                <span>
                    Lista de grupos
                </span>
            </td>
        </tr>
        <tr>
            <?php
            $i = 0;
            $iterar = 0;
            foreach($grupos_pagina_actual as $id_grupo)
            {

                if ($id_grupo != "")
                {
                    // + Esta variable separara las dos columnas de la tabla en diferentes clases
                    $iterar ++;
                    // + Este indice determinara cuantos amigos cargara
                    $query_info_grupo = mysqli_query($con, "SELECT * FROM grupos WHERE id_grupo='$id_grupo'");
                    $fila_info_grupo = mysqli_fetch_array($query_info_grupo);
                    
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
                            <div class='displayGrupo " . $columna . "'>
                                <div class='contenedorImagenGrupo'>
                                    <a href='../groups/" . $fila_info_grupo['nombre_grupo'] . "' style='color: 000'>
                                        <img src='../". $fila_info_grupo['imagen_grupo'] . "'>
                                    </a>
                                </div>
                                    
                                <div class='contenedorInfoGrupo'>
                                    <p>
                                        <a href='../groups/" . $fila_info_grupo['nombre_grupo'] . "' style='color: 000'>"
                                            .$fila_info_grupo['nombre_grupo'] . 
                                        "</a>
                                    </p>
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
    else if ($cantidad_grupos_pagina_actual == 1)
    {
        ?>
        <div class="titulo_tabla_usuarios_encontrados">
            <span>
                Lista de grupos
            </span>
        </div>

        <?php
        $columna = "td_derecha";

        $id_grupo = $grupos_pagina_actual[0];
        
        $query_primer_grupo = mysqli_query($con, "SELECT * FROM grupos WHERE id_grupo='$id_grupo'");
        $fila_info_grupo = mysqli_fetch_array($query_primer_grupo);
    
        
        echo "<div class='col-sm-4'>
                <div class='displayGrupo columna_un_usuario'>
                    <div class='contenedorImagenGrupo'>
                        <a href='../groups" . $fila_info_grupo['nombre_grupo'] . "' style='color: 000'>
                            <img src='../". $fila_info_grupo['imagen_grupo'] . "'>
                        </a>
                    </div>
                        
                    <div class='contenedorInfoGrupo'>
                            <p>
                                <a href='../groups" . $fila_info_grupo['nombre_grupo'] . "' style='color: 000'>"
                                    .$fila_info_grupo['nombre_grupo'] . 
                                "</a>
                            </p>
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