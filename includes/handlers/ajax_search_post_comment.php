<?php
include("../../config/config.php");
include("../../includes/classes/Usuario.php");

$query = $_POST['query'];
$nombre_usuario = $_POST['nombre_usuario'];
$query_obtener_id_usuario = mysqli_query($con, "SELECT id_usuario FROM usuarios WHERE username='$nombre_usuario'");
if(mysqli_num_rows($query_obtener_id_usuario) > 0)
{
    $fila_id_usuario = mysqli_fetch_array($query_obtener_id_usuario);
    $id_usuario = $fila_id_usuario['id_usuario'];
    $tipo_busqueda = $_POST['tipo_busqueda'];
    if($tipo_busqueda == 'publicacion')
    {
        $publicacionesRetornadasQuery = mysqli_query($con, "SELECT * FROM publicaciones WHERE (titulo LIKE '%$query%' OR cuerpo LIKE '%$query%') AND publicado_por='$id_usuario' LIMIT 8");
        if(mysqli_num_rows($publicacionesRetornadasQuery) == 0)
        {
            $publicacionesRetornadasQuery = "";
        }

        if($query != "" && $publicacionesRetornadasQuery != "")
        {
            while($fila = mysqli_fetch_array($publicacionesRetornadasQuery))
            {
                $titulo_publicacion = $fila['titulo'];
                $titulo_publicacion_puntos = strlen($titulo_publicacion) >= 30 ? "..." : "";
                $titulo_publicacion_recortado = str_split($titulo_publicacion, 30);
                $titulo_publicacion_recortado = $titulo_publicacion_recortado[0];

                $cuerpo_publicacion = $fila['cuerpo'];
                $cuerpo_publicacion_puntos = strlen($cuerpo_publicacion) >= 30 ? "..." : "";
                $cuerpo_publicacion_recortado = str_split($cuerpo_publicacion, 30);
                $cuerpo_publicacion_recortado = $cuerpo_publicacion_recortado[0];

                $id_publicacion = $fila['id_publicacion'];

                echo "<div class='displayResultadoObjeto'>
                        <div class='liveSearchPublicacion'>
                            Titulo: " . $titulo_publicacion_recortado . $titulo_publicacion_puntos . "
                            <br>
                            Cuerpo: " . $cuerpo_publicacion_recortado . $cuerpo_publicacion_puntos . "
                        </div>
                        <div class='liveSearchUsuarioComentario'>
                            Comentado por: <span class='nombre_usuario_objeto' id='nombre_usuario_objeto'>" . $nombre_usuario . "</span>
                            ID publicacion: <span class='id_objeto_sancion' id='id_objeto_sancion'> " . $id_publicacion . "</span>
                        </div>
                    </div>";
            }
        }
    }
    else if($tipo_busqueda == 'comentario')
    {
        $comentariosRetornadosQuery = mysqli_query($con, "SELECT * FROM comentarios WHERE (cuerpo_comentario LIKE '%$query%' AND comentado_por='$id_usuario') LIMIT 8");
        if(mysqli_num_rows($comentariosRetornadosQuery) == 0)
        {
            $comentariosRetornadosQuery = "";
        }

        if($query != "" && $comentariosRetornadosQuery != "")
        {
            while($fila = mysqli_fetch_array($comentariosRetornadosQuery))
            {
                $comentario = $fila['cuerpo_comentario'];
                $comentario_puntos = strlen($comentario) >= 30 ? "..." : "";
                $comentario_recortado = str_split($comentario, 30);
                $comentario_recortado = $comentario_recortado[0];
                $id_comentario = $fila['id_comentario'];
                $id_publicacion_comentada = $fila['publicacion_comentada'];
        
        
                echo "<div class='displayResultadoObjeto'>
                        <div class='liveSearchComentario'>
                            Comentario: " . $comentario_recortado . $comentario_puntos . "
                        </div>
                        <div class='liveSearchUsuarioComentario'>
                            Comentado por: <span class='nombre_usuario_objeto' id='nombre_usuario_objeto'>" . $nombre_usuario . "</span>
                            <br>
                            ID comentario: <span class='id_objeto_sancion' id='id_objeto_sancion'> " . $id_comentario . "</span>
                            ID publicacion comentada: <span class='id_publicacion_comentario_sancion' id='id_publicacion_comentario_sancion'> " . $id_publicacion_comentada . "</span>
                        </div>
                    </div>";
            }
        }
    }
}

?>