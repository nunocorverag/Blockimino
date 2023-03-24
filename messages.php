<?php
include("includes/header.php");


$objeto_mensaje = new Mensaje($con, $id_usuario_loggeado);

if(isset($_GET['u']))
{
    $mensaje_para = $_GET['u'];
    if($_GET['u'] != "nuevo")
    {
        $query_obtener_id_mensaje_para = mysqli_query($con, "SELECT id_usuario FROM usuarios WHERE username='$mensaje_para'");
        $fila = mysqli_fetch_array($query_obtener_id_mensaje_para);
        $mensaje_para = $fila['id_usuario']; 
    }
}
else
{
    // + Esta funcion va a retornar el usuario con el que el usuario loggeado tuvo la interaccion mas reciente
    $mensaje_para = $objeto_mensaje->obtenerUsuarioMasReciente();
    // + Si no encuentra nadie, entonces significa que no has empezado una converzacion con nadie
    if($mensaje_para == false)
    {
        // + Si esto es nuevo, entonces estamos mandando un mensaje nuevo
        $mensaje_para = "nuevo";
    }
}

if($mensaje_para != "nuevo")
{
    $objeto_mensaje_para = new Usuario($con, $mensaje_para);
}

if(isset($_POST['publicar_mensaje']))
{
    if (isset($_POST['cuerpo_mensaje']))
    {
        $cuerpo_mensaje = mysqli_real_escape_string($con, $_POST['cuerpo_mensaje']);
        $fecha_mensaje = date("Y-m-d H:i:s");
        $objeto_mensaje->enviarMensaje($mensaje_para, $cuerpo_mensaje, $fecha_mensaje);

    }
}
?>

<div class="columna_principal" id="columna_principal">
    <?php
    $esNuevo = "";
    // ! Aqui puede generar un error entonces podriamos checar el null pero tendre que ver ya que queden los mensajes
        if($mensaje_para != 'nuevo')
        {
            $nombre_usuario_mensaje_para = $objeto_mensaje_para->obtenerNombreUsuario();
            echo "<h4>Tu y <a href='$nombre_usuario_mensaje_para'>" . $objeto_mensaje_para->obtenerNombreCompleto() . "</a></h4><hr><br>";
            echo "<div class='mensajes_cargados' id='mensajes_desplazamiento'>";
                echo $objeto_mensaje->obtenerMensajes($mensaje_para);
            echo "</div>";
        }
        else
        {
            $esNuevo = true;
            echo "<h4>Nuevo Mensaje</h4>";
        }
    ?>

    <div class="publicar_mensaje">
        <form action="" method="POST">
            <?php
                if($mensaje_para == "nuevo")
                {
                    echo "Selecciona el amigo con el que quieres conversar <br><br>";
                    ?>
                    <p>Buscar: <input type='text' onkeyup='obtenerUsuarios(this.value, "<?php echo $id_usuario_loggeado; ?>")' name='q' placeholder='Nombre' autocomplete='off' id='search_text_input'></p>
                    <br>
                    <?php
                    echo "<div class='resultados'></div>";
                    ?>
                    <p>Contactos:</p> 
                    <?php
                    #region cargar amigos
                    // ! Aqui podemos agregar una funcion posteriormente para que solo seleccione los amigos
                    $usuariosRetornados = mysqli_query($con, "SELECT * FROM usuarios ORDER BY nombre" );
                    if ($usuariosRetornados != null)
                    {
                        while($fila = mysqli_fetch_array($usuariosRetornados))
                        {
                            $usuario = new Usuario($con, $id_usuario_loggeado);
                            if($fila['id_usuario'] != $id_usuario_loggeado)
                            {
                                if($usuario->obtenerAmigosMutuos($fila['id_usuario']) == 1)
                                {
                                    $amigos_mutuos = $usuario->obtenerAmigosMutuos($fila['id_usuario']) . " Amigo en comun";
                                }
                                else
                                {
                                    $amigos_mutuos = $usuario->obtenerAmigosMutuos($fila['id_usuario']) . " Amigos en comun";
                                }
                            }
                            else
                            {
                                $amigos_mutuos = "";
                            }
                    
                            if($usuario->esAmigo($fila['id_usuario']) && $fila['id_usuario'] != $id_usuario_loggeado)
                            {
                                echo "  <div class='displayResultado'>
                                            <a href='messages.php?u=" . $fila['username'] . "' style='color: 000'>
                                                <div class='liveSearchFotoPerfil'>
                                                    <img src='". $fila['foto_perfil'] . "'>
                                                </div>
                                                
                                                <div class='liveSearchTexto'>
                                                    " . $fila['nombre'] . " " . $fila['apeP'] . " " . $fila['apeM'] . "
                                                    <p style='margin: 0'> " .$fila['username'] . "</p>
                                                    <p id='gris'> ". $amigos_mutuos . "</p>
                                                </div>
                                                </a>
                                        </div>";    
                            }
                        }
                    }
                    #endregion
                                    }
                else
                {
                    echo "<textarea name='cuerpo_mensaje' id='textarea_mensaje' placeholder='Escribe aqui tu mensaje'></textarea>";
                    echo "<input type='submit' name='publicar_mensaje' class='info' id='enviar_mensaje' value='Enviar'>";
                }
            ?>
        </form>
        
    </div>

    <script>
        var div = document.getElementById("mensajes_desplazamiento");

        if(div != null)
        {
            div.scrollTop = div.scrollHeight;
        }

    </script>
</div>

<?php
if($esNuevo != true)
{
?>
<div class="conversaciones" id="conversaciones">
        <h4>Conversaciones</h4>
        <div class="conversaciones_cargadas">
            <?php echo $objeto_mensaje->obtenerConversaciones(); ?>
        </div>
        <br>
        <a href="messages.php?u=nuevo">Nuevo Mensaje</a>
</div>
<?php
}
?>