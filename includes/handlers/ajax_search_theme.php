<?php
include("../../config/config.php");

$query = $_POST['query'];

// + Primero se buscara por tema

$temasRetornadosQuery = mysqli_query($con, "SELECT * FROM temas_pagina WHERE 
                                                                        (nombre_tema LIKE '%$query%') 
                                                                        LIMIT 8");
                                                                        
if(mysqli_num_rows($temasRetornadosQuery) < 8)
{
    // + bp = bloques_pagina
    // + tp = temas_pagina
    $bloquesRetornadosQuery = mysqli_query($con, "SELECT bp.nombre_bloque, bp.id_bloque, tp.nombre_tema, tp.id_tema
                                                    FROM bloques_pagina AS bp
                                                    LEFT JOIN temas_pagina AS tp ON tp.id_tema = bp.id_tema_bloque
                                                    WHERE bp.nombre_bloque LIKE '%$query%'
                                                    LIMIT 8");


    if((mysqli_num_rows($temasRetornadosQuery) + mysqli_num_rows($bloquesRetornadosQuery)) < 8)
    {
        $bloquesDescripcionRetornadosQuery = mysqli_query($con, "SELECT bp.nombre_bloque, bp.id_bloque, tp.nombre_tema, tp.id_tema
                                                        FROM bloques_pagina AS bp
                                                        LEFT JOIN temas_pagina AS tp ON tp.id_tema = bp.id_tema_bloque
                                                        WHERE bp.descripcion_bloque LIKE '%$query%'
                                                        LIMIT 8");
    }
}

if($query != "" && ($temasRetornadosQuery != "" || $bloquesRetornadosQuery != ""))
{
    $resultados_mostrados = 0;
    if(mysqli_num_rows($temasRetornadosQuery) > 0)
    {
        while(($fila = mysqli_fetch_array($temasRetornadosQuery)) && $resultados_mostrados < 8)
        {
            $nombre_tema = $fila['nombre_tema'];
            $id_tema = $fila['id_tema'];
            echo "<div class='displayResultadoTema'>
                    <div class='tema_encontrado' onclick='seleccionarTema(\"$id_tema\")'>
                        Tema encontrado: " . $fila['nombre_tema']. "
                    </div>
                </div>";
            $resultados_mostrados ++;
        }
    }
    if($resultados_mostrados < 8)
    {
        // Crear un arreglo para almacenar los IDs de los bloques mostrados
        $bloquesMostrados = [];
        if(mysqli_num_rows($bloquesRetornadosQuery) > 0)
        {
            while(($fila = mysqli_fetch_array($bloquesRetornadosQuery)) && $resultados_mostrados < 8)
            {
                $id_tema = $fila['id_tema'];
                $id_bloque = $fila['id_bloque'];

                echo "<div class='displayResultadoBloque'>
                        <div class='bloque_encontrado' onclick='seleccionarBloque(\"$id_tema\", \"$id_bloque\")'>
                            Elemento encontrado: " . $fila['nombre_bloque']. " 
                            <br>
                            Tema al que pertenece: " . $fila['nombre_tema'] . " 
                        </div>
                    </div>";
                $resultados_mostrados ++;
                $bloquesMostrados[] = $id_bloque;
            }
        }
    }
    if($resultados_mostrados < 8)
    {
        if(mysqli_num_rows($bloquesDescripcionRetornadosQuery) > 0)
        {
            while(($fila = mysqli_fetch_array($bloquesDescripcionRetornadosQuery)) && $resultados_mostrados < 8)
            {
                $id_tema = $fila['id_tema'];
                $id_bloque = $fila['id_bloque'];
                if (!in_array($id_bloque, $bloquesMostrados)) {
                    echo "<div class='displayResultadoBloque'>
                            <div class='bloque_encontrado' onclick='seleccionarBloque(\"$id_tema\", \"$id_bloque\")'>
                                Elemento encontrado: " . $fila['nombre_bloque']. "
                                <br>
                                Tema al que pertenece: " . $fila['nombre_tema'] . " 
                            </div>
                        </div>";
                    $resultados_mostrados ++;
                }
            }
        }
    }
}
?>