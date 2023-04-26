<?php
include("../../config/config.php");
include("../../includes/classes/Usuario.php");

$query = $_POST['query'];

// + Separamos los elementos de la busqueda
$hashtag = explode(" ", $query);

if(count($hashtag) > 1)
{
    $hashtagsRetornadosQuery = "";
}
if(count($hashtag) == 1)
{
    $hashtagsRetornadosQuery = mysqli_query($con, "SELECT * FROM hashtags WHERE 
                                                                            (hashtag LIKE '%#$hashtag[0]%') 
                                                                            OR 
                                                                            (hashtag LIKE '%$hashtag[0]%')
                                                                            LIMIT 8");
}

if($query != "" && $hashtagsRetornadosQuery != "")
{
    while($fila = mysqli_fetch_array($hashtagsRetornadosQuery))
    {
        echo "<div class='displayResultadoHashtag'>
                <div class='hashtag_encontrado'>
                    " . $fila['hashtag']. "
                </div>
            </div>";
    }
}
?>