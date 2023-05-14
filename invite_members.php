<?php
include("includes/header.php");

if(isset($_GET['nombre_grupo']))
{
    $nombre_grupo = $_GET['nombre_grupo'];
    $query_info_grupo = mysqli_query($con, "SELECT * FROM grupos WHERE nombre_grupo='$nombre_grupo'");
    $fila = mysqli_fetch_array($query_info_grupo);
    $id_grupo = $fila['id_grupo'];
    $objeto_grupo_usuario_loggeado = new Grupo($con, $id_usuario_loggeado);
    $lista_miembros = $fila['miembros_grupo'];
    $lista_miembros_explode = explode(",", $lista_miembros);
    $lista_miembros_explode = array_filter($lista_miembros_explode);
    $total_miembros = count($lista_miembros_explode);

}
?>

<div class="cuerpo_invitar_miembros">
    <?php
    if ($objeto_grupo_usuario_loggeado->EsUsuarioPropietario($id_grupo))
    {
            ?>
            <h4>Invitar Miembros al Grupo</h4>
            <?php
                if($total_miembros == 20)
                {
                    echo "El grupo tiene 20 miembros, no puedes invitar mas usuarios al grupo!";
                }
            ?>
            <br>
            <div class="busqueda_usuarios_invitar">
                <form action="invite" method="GET" name="formulario_busqueda_invitacion">
                    <input type="text" onkeyup="obtenerLiveSearchInvitarUsuarios(this.value, '<?php echo $id_usuario_loggeado?>', '<?php echo $id_grupo?>')" name="query" placeholder="Buscar usuarios para invitar" autocomplete="off" id="input_busqueda_texto">
                    <div class="contenedor_boton_busqueda_invitacion">
                        <i class="fa-solid fa-magnifying-glass"></i>        
                    </div>
                </form>
    
                <div class="resultados_busqueda_invitar_miembros">
                    
                </div>
    
                <!-- // ! Hay que explicar este -->
                <div class="resultados_busqueda_invitar_miembros_pie_pagina_vacios">
        
                </div>
            </div>
            <br><br><br>
    
            <?php 
            if(isset($_GET['query']))
            {
                $query = $_GET['query'];
                // !NOTA HAY QUE TENER CUIDADO CON EL REDIRECCIONAMIENTO ABSOLUTO EN EL HOST
                $src_pagina = 'http://localhost/blockimino/';

                // + Separamos los elementos de la busqueda
                $nombres = explode(" ", $query);

                // + Contara el numero de nombres en el arreglo
                if(count($nombres) >= 4)
                {
                    $usuariosRetornadosQuery = "";
                }

                // + Esta busqueda incluye el nombre y los apellidos
                else if(count($nombres) == 3)
                {
                    $usuariosRetornadosQuery = mysqli_query($con, "SELECT * FROM usuarios WHERE (nombre LIKE '%$nombres[0]%' AND apeP LIKE '%$nombres[1]%' AND apeM LIKE '%$nombres[2]%')
                                                                                            AND usuario_cerrado='no' LIMIT 8");
                }

                // + Esta busqueda incluye el nombre y un apellido
                else if(count($nombres) == 2)
                {
                    $usuariosRetornadosQuery = mysqli_query($con, "SELECT * FROM usuarios WHERE 
                                                                                            (nombre LIKE '%$nombres[0]%' AND apeP LIKE '%$nombres[1]%') 
                                                                                            OR 
                                                                                            (nombre LIKE '%$nombres[0]%' AND apeM LIKE '%$nombres[1]%')
                                                                                            AND usuario_cerrado='no' LIMIT 8");
                }
                // + Esta busqueda es para los usuarios
                else if(count($nombres) == 1)
                {
                    $usuariosRetornadosQuery = mysqli_query($con, "SELECT * FROM usuarios WHERE 
                                                                                            (nombre LIKE '%$nombres[0]%') 
                                                                                            OR 
                                                                                            (apeP LIKE '%$nombres[0]%')
                                                                                            OR
                                                                                            (apeM LIKE '%$nombres[0]%')
                                                                                            OR
                                                                                            (username LIKE '$nombres[0]%')
                                                                                            AND usuario_cerrado='no' LIMIT 8");
                }

                while($fila = mysqli_fetch_array($usuariosRetornadosQuery))
                {
                    $id_usuario_retornado = $fila['id_usuario'];
                    $objeto_grupo_usuario_buscado = new Grupo($con, $id_usuario_retornado);

                    // + Esto es para que no muestre los usuarios que ya pertenecen al grupo
                    if (!($objeto_grupo_usuario_buscado->UsuarioPerteneceAlGrupo($id_grupo))) 
                    {
                        $boton = "";
                        if($total_miembros != 20)
                        {
                            if ($objeto_grupo_usuario_loggeado->checarInvitacionGrupoEnviada($id_grupo, $id_usuario_retornado))
                            {
                                $boton = '<input type="button" name="" class="default" value="Invitación Enviada">';
                            }
                            else
                            {
                                $boton = '<input type="button" name="invitar_usuario_grupo" class="success" value="Invitar" onclick="invitarUsuario(' . $id_usuario_loggeado . ', ' . $id_usuario_retornado . ', ' .$id_grupo . ')">';
                            }
                        }       
                        else
                        {
                            $boton = '<input type="button" name="" class="default" value="Grupo lleno!">';
                        }             
                        
                        $objeto_usuario_loggeado = new Usuario($con, $id_usuario_loggeado);
                        // + Esto para evitar que aoarezca el usuario loggaedo
                        if($fila['id_usuario'] != $id_usuario_loggeado)
                        {
                            $amigos_mutuos = $objeto_usuario_loggeado->obtenerAmigosMutuos($fila['id_usuario']);
                            if($amigos_mutuos != 1)
                            {
                                $amigos_mutuos .= " amigos en común";
                            }
                            else
                            {
                                $amigos_mutuos .= " amigo en común";
                            }
                        }
                        else
                        {
                            $amigos_mutuos = "";
                        }
                        if ($fila['id_usuario'] != $id_usuario_loggeado)
                        {
                            echo "<div class='displayResultadoInvitarMiembro'>
                                    <div class='invitarLiveSearchFotoPerfil'>
                                        <a href='" . $src_pagina . $fila['username'] . "' style='color: #1485BD'>
                                            <img src='" . $src_pagina . $fila['foto_perfil'] . "'>
                                        </a>
                                    </div>
                                        <div class='invitarLiveSearchTexto'>
                                        <a href='" . $src_pagina . $fila['username'] . "' style='color: #1485BD'>
                                            " . $fila['nombre'] . " " . $fila['apeP'] . " " . $fila['apeM'] . "
                                        </a>
                                            <p style='margin: 0'> 
                                                <a href='" . $src_pagina . $fila['username'] . "' style='color: #1485BD'>
                                                    " .$fila['username'] . "
                                                </a>
                                            </p>
                                            <p id='gris'> ". $amigos_mutuos . "</p>
                                    </div>
                                    <div class='boton_invitar'>
                                        " . $boton . "
                                    </div>
                
                                </div>";
                        }
                    }
                }
            }
        }
    else if($objeto_grupo_usuario_loggeado->UsuarioPerteneceAlGrupo($id_grupo))
    {
        echo "No tiene los permisos necesarios para ivnitar en este grupo!";
    }
    else
    {
        echo "No pertenece a este grupo!";
    }

    ?>
</div>
