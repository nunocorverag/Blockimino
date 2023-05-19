<?php
include("../../config/config.php");
include("../../includes/classes/Usuario.php");

$id_usuario_loggeado = $_GET['id_usuario'];
$query = $_POST['query'];

// + Separamos los elementos de la busqueda
$interes = explode(" ", $query);

if(count($interes) > 1)
{
    $hashtagsRetornadosQuery = "";
}
if(count($interes) == 1)
{
    $query_obtener_no_intereses_usuario = mysqli_query($con, "SELECT hashtags.hashtag, temas_interes.id_hashtag_interes
                                                                FROM temas_interes
                                                                INNER JOIN hashtags ON temas_interes.id_hashtag_interes = hashtags.id_hashtag
                                                                WHERE temas_interes.id_hashtag_interes NOT IN (
                                                                SELECT id_hashtag_interes
                                                                FROM temas_interes
                                                                WHERE id_usuario_interesado = $id_usuario_loggeado
                                                                )
                                                                AND hashtags.hashtag LIKE '%$query%'
                                                                GROUP BY hashtags.hashtag, temas_interes.id_hashtag_interes");
}

if($query != "" && $query_obtener_no_intereses_usuario != "")
{
    while($fila = mysqli_fetch_array($query_obtener_no_intereses_usuario))
    {
        echo "<div class='displayResultadoHashtag'>
                <div class='hashtag_encontrado'>
                    " . $fila['hashtag']. "
                </div>
            </div>";
    }
}
?>