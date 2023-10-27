<?php
class Grupo {
    private $con;
    // - $con -> Guardara la conexion de la base de datos
    private $objeto_usuario;
    // - $n_usuario -> Guardara un objeto tipo usuario, el cual pertenecera a la clase Usuario

    public function __construct($con, $id_usuario)
    {
        $this->con = $con;
        // + Guardara en el objeto usuario, la conexion a la base de datos y el nombre de usuario
        $this->objeto_usuario = new Usuario($con, $id_usuario);
    }

    public function crearGrupo($nombre_grupo, $descripcion_grupo, $imagen_grupo)
    {
        $id_creador_grupo = $this->objeto_usuario->obtenerIDUsuario();
        $crear_grupo = mysqli_query($this->con, "INSERT INTO grupos VALUES ('', '$nombre_grupo', '$id_creador_grupo', '$imagen_grupo', '$descripcion_grupo', ',$id_creador_grupo,', 'no')");
        $id_regresado = mysqli_insert_id($this->con);
        $agregar_grupo_al_usuario = mysqli_query($this->con, "UPDATE usuarios SET lista_grupos = CONCAT(lista_grupos, '$id_regresado,') WHERE id_usuario = '$id_creador_grupo'");
        echo "Grupo creado con éxito!";
    }

    public function displayGrupos()
    {
        $id_usuario = $this->objeto_usuario->obtenerIDUsuario();
        $grupos_usuario = $this->objeto_usuario->obtenerListaGrupos();
        $grupos_usuario_explode = explode(",", $grupos_usuario);
        $grupos_usuario_explode = array_filter($grupos_usuario_explode);
        $return_string = "";

        foreach($grupos_usuario_explode as $grupo)
        {
            $query_info_grupo = mysqli_query($this->con, "SELECT * FROM grupos WHERE (id_grupo='$grupo' AND grupo_eliminado='no')");
            $fila = mysqli_fetch_array($query_info_grupo);
            $return_string .= "<div class='displayResultadoGrupo'>
                                    <a href='groups/"  . $fila['nombre_grupo'] ."'> 
                                        <div class='fotoPerfilGrupo'>
                                            <img src='" . $fila['imagen_grupo'] . "'>
                                        </div>
                                        <div class='infoGrupo'>
                                            ".$fila['nombre_grupo'] . "
                                        </div>
                                    </a>
                                </div>";
        }
        echo $return_string;
    }

    public function ObtenerInfoGrupo($id_grupo)
    {
        if($id_grupo == 0)
        {
            echo "Este grupo no existe!";
            return;
        }
        // + Si el usuario pertenece al grupo
        $pertenece_grupo = $this->UsuarioPerteneceAlGrupo($id_grupo);
        $query_info_grupo = mysqli_query($this->con, "SELECT * FROM grupos WHERE id_grupo='$id_grupo'");
        $fila = mysqli_fetch_array($query_info_grupo);
        $lista_miembros = $fila['miembros_grupo'];
        $lista_miembros_explode = explode(",", $lista_miembros);
        $lista_miembros_explode = array_filter($lista_miembros_explode);
        $total_miembros = count($lista_miembros_explode);

        if($pertenece_grupo == true)
        {
            // + Boton para administrar grupo
            $boton_administar = "";
            if($this->EsUsuarioPropietario($id_grupo))
            {
                $boton_administar = "<a href='". $fila['nombre_grupo'] . "/manage'>
                                        <button class='info'>Administrar grupo</button>
                                    </a>";
            }
            $boton_eliminar_salir = "";
            if($this->EsUsuarioPropietario($id_grupo))
            {
                $boton_eliminar_salir = "<button type='submit' class='danger' id='boton_eliminar_grupo'>Eliminar grupo</button>";
            }
            else if ($this->UsuarioPerteneceAlGrupo($id_grupo))
            {
                $boton_eliminar_salir = "<button type='submit' class='danger' id='boton_salir_grupo'>Salir del grupo</button>";
            }

            $return_string = "  <div class='imagenPaginaGrupo'>
                                    <img src='../" . $fila['imagen_grupo'] . "'>
                                </div>
                                <div class='detalles_grupo'>
                                    <div class='titulo_grupo'>
                                        <h4>" . $fila['nombre_grupo'] . "</h4> 
                                    </div>
                                    <div class='descripcion_grupo'>
                                        <p> <span> Descripción del grupo:  </span>
                                        " . $fila['descripcion_grupo'] . "</p> 
                                    </div>
                                    <div class='miembros'>
                                        <a href='". $fila['nombre_grupo'] . "/members'>
                                            <button class='info'>
                                                <p> Miembros: " . $total_miembros . "</p>
                                            </button>
                                        </a>
                                    </div>
                                    <br>
                                    <div class='botones'>
                                        <input type='submit' class='deep_blue' data-toggle='modal' data-target='#formulario_publicacion' value='Publicar algo'>
                                        " . $boton_administar . "
                                        " . $boton_eliminar_salir . "
                                    </div>
                                </div>
                                <br>
                                <br>";

            echo $return_string;
            // + Este script sera para la confirmacion de eliminar o salir del grupo
            ?>
            <script>
                // + Script deeliminar grupo
                $(document).ready(function(){
                    $('#boton_eliminar_grupo').on('click', function() {
                        var id_usuario_propietario = <?php echo $this->ObtenerIdUsuarioPropietario($id_grupo) ?>;
                        // - resultado -> Sera el resultadoado de lo que el usuario clickeo, si fue "si" o "no"
                        bootbox.confirm("¿Estas seguro que quieres eliminar este grupo?<br> No se podrá deshacer esta acción<br> Todas las publicaciones y comentarios se eliminaran", function(result) {
                            if(result == true)
                            {
                                $.post("../includes/handlers/ajax_delete_group.php?id_grupo=<?php echo $id_grupo; ?>&id_usuario_propietario=" + id_usuario_propietario, {resultado:result});
                                window.location.href = '../groups.php';
                            }
                        });
                    });
                    $('#boton_salir_grupo').on('click', function() {
                        var id_usuario_loggeado = <?php echo $this->objeto_usuario->obtenerIDUsuario() ?>;
                        // - resultado -> Sera el resultadoado de lo que el usuario clickeo, si fue "si" o "no"
                        bootbox.confirm("¿Estas seguro que quieres salir de este grupo?", function(result) {
                            if(result == true)
                            {
                                $.post("../includes/handlers/ajax_delete_member.php?id_grupo=<?php echo $id_grupo; ?>&id_miembro=" + id_usuario_loggeado, {resultado:result});
                                window.location.href = '../groups.php';
                            }
                        });
                    });
                });
            </script>
            <?php
            return;
        }
        else
        {
        $id_usuario = $this->objeto_usuario->obtenerIDUsuario();
        $info = "";
        // + Checar si el usuario ya solicito unirse
        $boton = "";
        if($total_miembros != 20)
        {
            $query_checar_si_hay_solicitud = mysqli_query($this->con, "SELECT * FROM solicitudes_de_grupo WHERE grupo_solicitado='$id_grupo' AND usuario_que_solicito_unirse='$id_usuario'");
            if(mysqli_num_rows($query_checar_si_hay_solicitud) > 0)
            
            {
                $boton_solicitar_unirse = "<input type='submit' name='' value='Solicitud Enviada' class='default boton_solicitar_unirse'>";
            }
            else
            {
                $boton_solicitar_unirse = "<input type='submit' name='solicitar_unirse_grupo' value='Solicitar Unirse' class='success boton_solicitar_unirse'>";
            }
        }
        else
        {
            $boton_solicitar_unirse = '<input type="button" name="" class="default boton_solicitar_unirse" value="Grupo lleno!">';

        }   

        if(isset($_POST['solicitar_unirse_grupo']))
        {
            $query_solicitud_grupo = mysqli_query($this->con, "INSERT INTO solicitudes_de_grupo VALUES ('', '$id_grupo', '$id_usuario')");
            header("Location: " . $fila['nombre_grupo']);
        }

        $return_string = "  <div class='imagenPaginaGrupo'>
                                <img src='../" . $fila['imagen_grupo'] . "'>
                            </div>
                            <div class='detalles_grupo'>
                                <div class='titulo_grupo'>
                                    <h4>" . $fila['nombre_grupo'] . "</h4> 
                                </div>
                                <div class='descripcion_grupo'>
                                    <p> <span> Descripción del grupo:  </span>
                                    " . $fila['descripcion_grupo'] . "</p> 
                                </div>
                                <div class='miembros'>
                                    <a href='". $fila['nombre_grupo'] . "/members'>
                                        <p> Miembros: " . $total_miembros . "</p>
                                    </a>
                                </div>
                                <div class='contenedorSolicitudGrupo'>
                                Usted no pertenece a este grupo, solicite unirse aqui:
                                <form action='" . $fila['nombre_grupo'] . "' method='POST'>
                                    " .$boton_solicitar_unirse . "
                                </form>
                            </div>
                            </div>";

            echo $return_string;
            return;
        }
    }

    public function UsuarioPerteneceAlGrupo($grupo_solicitado)
    {
        $id_usuario = $this->objeto_usuario->obtenerIDUsuario();
        $lista_grupos_usuario = $this->objeto_usuario->obtenerListaGrupos();
        $lista_grupos_explode = explode(",", $lista_grupos_usuario);
        foreach($lista_grupos_explode as $grupo)
        {
            if($grupo == $grupo_solicitado)
            {
                return true;
            }
        }
        return false;
    }

    public function EsUsuarioPropietario($id_grupo) {
        $id_usuario = $this->objeto_usuario->obtenerIDUsuario();
        $query_id_creador_grupo = mysqli_query($this->con, "SELECT id_creador_grupo FROM grupos WHERE id_grupo='$id_grupo'");
        $fila = mysqli_fetch_array($query_id_creador_grupo);
        $id_creador_grupo = $fila['id_creador_grupo'];
        if($id_creador_grupo == $id_usuario)
        {
            return true;
        }
        else
        {
            return false;
        }
        
    }

    public function ObtenerIdUsuarioPropietario($id_grupo) {
        $query_id_creador_grupo = mysqli_query($this->con, "SELECT id_creador_grupo FROM grupos WHERE id_grupo='$id_grupo'");
        $fila = mysqli_fetch_array($query_id_creador_grupo);
        $id_creador_grupo = $fila['id_creador_grupo'];
        return $id_creador_grupo;
    }

    public function DisplayMiembros($id_grupo)
    {
        $query_info_grupo = mysqli_query($this->con, "SELECT * FROM grupos WHERE id_grupo='$id_grupo'");
        $fila = mysqli_fetch_array($query_info_grupo);
        $lista_miembros = $fila['miembros_grupo'];
        $lista_miembros_explode = explode(",", $lista_miembros);
        $lista_miembros_explode = array_filter($lista_miembros_explode);
        // + Display de la info del miembro
        $string_miembros = "";

        $usuario_es_propietario = $this->EsUsuarioPropietario($id_grupo);
        $iterar = 0;
        foreach($lista_miembros_explode as $miembro)
        {
            $iterar ++;
            if($usuario_es_propietario && $this->objeto_usuario->obtenerIDUsuario() != $miembro)
            {
                $boton_eliminar_miembro = "<button class='boton_eliminar_miembro btn btn-danger' id='miembro$miembro'><i class='fa-solid fa-x'></i></button>";
            }
            else
            {
                $boton_eliminar_miembro = "";
            }

            // + Obtener los amigos mutuos

            if($this->objeto_usuario->obtenerIDUsuario() != $miembro)
            {
                if($this->objeto_usuario->obtenerAmigosMutuos($miembro) == 1)
                {
                    $amigos_mutuos = $this->objeto_usuario->obtenerAmigosMutuos($miembro) . " Amigo en comun";
                }
                else
                {
                    $amigos_mutuos = $this->objeto_usuario->obtenerAmigosMutuos($miembro) . " Amigos en comun";
                }
            }
            else
            {
                $amigos_mutuos = "";
            }

            // + Verificara cuantas veces ha iterado para ver si es derecha o izquierda
            if($iterar == 1)
            {
                $columna = "col_derecha_miembros";
            }
            else
            {
                $columna = "col_izquierda_miembros";
                $iterar = 0;
            }

            $query_info_miembro = mysqli_query($this->con, "SELECT * FROM usuarios WHERE id_usuario='$miembro'");
            $fila_info_miembro = mysqli_fetch_array($query_info_miembro);

            $string_miembros .= "<div class='displayMiembroGrupo " . $columna . "'>
                                    <div class='displayFotoPerfilMiembro'>
                                        <a href='../../" . $fila_info_miembro['username'] . "'>
                                            <img src='../../" . $fila_info_miembro['foto_perfil'] . "'>
                                        </a>
                                    </div>
                                    <div class ='displayInfoMiembro'>
                                        <a href='../../" . $fila_info_miembro['username'] . "' style='color: 000'>
                                            " . $fila_info_miembro['nombre'] . " " . $fila_info_miembro['apeP'] . " " . $fila_info_miembro['apeM'] . "
                                        </a>
                                    <p style='margin: 0'>
                                        <a href='../../" . $fila_info_miembro['username'] . "' style='color: 000'>"
                                            .$fila_info_miembro['username'] . 
                                        "</a>
                                    </p>
                                    <p id='gris'> ". $amigos_mutuos . "</p>
                                    </div>
                                    <div class='contenedor_boton_eliminar_miembro'>
                                        " . $boton_eliminar_miembro . "
                                    </div>
                                </div>";
                                ?>

                                <script>
                                    // + Script de eliminar miembro
                                    $(document).ready(function(){
                                        $('#miembro<?php echo $miembro; ?>').on('click', function() {
                                            bootbox.confirm("¿Estas seguro que quieres eliminar del grupo a este usuario (<?php echo $fila_info_miembro['username']; ?>)?", function(result) {
                                                if(result == true)
                                                {
                                                    $.post("../../includes/handlers/ajax_delete_member.php?id_miembro=<?php echo $miembro; ?>&id_grupo=<?php echo $id_grupo; ?>", {resultado:result});
                                                    location.reload();
                                                }
                                            });
                                        });
                                    });
                                </script>

                                <?php
        } // *foreach($lista_miembros_explode as $miembro)
        echo $string_miembros;
    }

    public function checarInvitacionGrupoEnviada($id_grupo, $id_usuario_invitado)
    {
        // - Aqui se guardara el usuario que hizo la solicitud de amistad
        $id_usuario_que_invito = $this->objeto_usuario->obtenerIDUsuario();
        $query_checar_invitacion_grupo = mysqli_query($this->con, "SELECT * FROM invitaciones_de_grupo WHERE id_usuario_que_invito='$id_usuario_que_invito' AND id_usuario_invitado='$id_usuario_invitado' AND id_grupo_invitado='$id_grupo'");
        if((mysqli_num_rows($query_checar_invitacion_grupo)) > 0)
        {
            return true;
        }
        else 
        {
            return false;
        } 
    }

    // public function InvitarUsuarioGrupo($id_grupo, $id_usuario_invitado)
    // {
    //     $id_usuario_que_invito = $this->objeto_usuario->obtenerIDUsuario();
    //     $query_enviar_invitacion = mysqli_query($this->con, "INSERT INTO invitaciones_de_grupo VALUES ('', '$id_usuario_que_invito', '$id_usuario_invitado', '$id_grupo')");
    // }

    // public function salirDelGrupo($id_grupo){
    //         $id_miembro = $this->objeto_usuario->obtenerIDUsuario();

    //         //+ Obtenemos la lista de miembros del grupo
    //         $query_detalles_grupo = mysqli_query($this->con, "SELECT miembros_grupo FROM grupos WHERE id_grupo='$id_grupo'");
    //         $fila_detalles_grupo = mysqli_fetch_array($query_detalles_grupo);
    //         $lista_miembros_grupo = $fila_detalles_grupo['miembros_grupo'];

    //         //+ Obtenemos la lista de grupos del usuario
    //         $query_detalles_usuario_a_remover = mysqli_query($this->con, "SELECT lista_grupos FROM usuarios WHERE id_usuario='$id_miembro'");
    //         $fila_detalles_usuario_a_remover = mysqli_fetch_array($query_detalles_usuario_a_remover);
    //         $lista_grupos_usuario = $fila_detalles_usuario_a_remover['lista_grupos'];

    //         // + Removemos al usuario del grupo
    //         $nueva_lista_de_miembros_grupo = str_replace("," . $id_miembro . ",", ",", $lista_miembros_grupo);
    //         $query_eliminar_miembro = mysqli_query($this->con, "UPDATE grupos SET miembros_grupo='$nueva_lista_de_miembros_grupo' WHERE id_grupo='$id_grupo'");
    
    //         // + Removemos el grupo de la lista de grupos del usuario
    //         $nueva_lista_de_grupos_usuario = str_replace("," . $id_grupo . ",", ",", $lista_grupos_usuario);
    //         $query_eliminar_grupo_lista_grupos = mysqli_query($this->con, "UPDATE usuarios SET lista_grupos='$nueva_lista_de_grupos_usuario' WHERE id_usuario='$id_miembro'");
    // }
}

?>