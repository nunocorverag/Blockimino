<!-- Esta clase trabajara con cosas que se requieran hacer con las publicaciones -->
<?php
class Publicacion {
    // - $con -> Guardara la conexion de la base de datos
    private $con;
    // - $n_usuario -> Guardara un objeto tipo usuario, el cual pertenecera a la clase Usuario
    private $objeto_usuario;

    public function __construct($con, $id_usuario)
    {
        $this->con = $con;
        // + Guardara en el objeto usuario, la conexion a la base de datos y el nombre de usuario
        $this->objeto_usuario = new Usuario($con, $id_usuario);
    }

    // + Se encargara de introducir la publicacion en la base de datos:
    // + $cuerpo -> Sera el cuerpo de la publicacion
    // + $enviado_a -> Si un usuario publico en el perfil de otro, esta variable se utilizara, de lo contrario, sera nula
    public function enviarPublicacion($titulo, $cuerpo, $publicado_para)
    {
        // $ strip_tags -> Retira las etiqueras HTML y PHP de un string
        $cuerpo = strip_tags($cuerpo);
        // $ mysqli_real_escape_string -> Escapa caracteres especiales para insertarlos en la base de datos
        $cuerpo = mysqli_real_escape_string($this->con, $cuerpo);
        
        // $ str_replace -> Reemplaza todas las occurrencias con otro string que querramos
        // +$ 1. String a reemplazar 2. String por el que se va a reemplazar 3. String completo en el que se va a reemplazar
        $cuerpo = str_replace('\r\n', '\n', $cuerpo);
        // $ nl2br -> Devuelve el string con un <br> en vez de una newline en codigo
        $cuerpo = nl2br($cuerpo);

        $titulo = strip_tags($titulo);
        $titulo = mysqli_real_escape_string($this->con, $titulo);

        // + Checa si hay espacios vacios y los reemplaza con un string vacio
        $checar_cuerpo_vacio = preg_replace('/\s+/', '', $cuerpo);
        $checar_titulo_vacio = preg_replace('/\s+/', '', $titulo);
        // + Revisa si el string completo no esta vacio para poderlo almacenar en la base de datos
        if ($checar_cuerpo_vacio != "" && $checar_titulo_vacio != "")
        {
            // - Guardamos en esta variable la fecha y hora actual para despues mostrar cuando se hizo la publicacion
            $fecha_publicado = date("Y-m-d H:i:s");

            // - Guardamos el id del usuario que lo publico
            $publicado_por = $this->objeto_usuario->obtenerIDUsuario();
            // + Si el usuario se encuentra en su perfil, entonces publicado_para sera nulo
            if ($publicado_para == $publicado_por)
            {
                $publicado_para = NULL;
            }

            // + Agregamos la publicacion a la base de datos si publicado_para es nulo
            if ($publicado_para == NULL)
            {
                $query_agrega_publicacion = mysqli_query($this->con, "INSERT INTO publicaciones VALUES('', '$titulo', '$cuerpo', '$publicado_por', NULL, '$fecha_publicado', 'no', '0')");
                // $ mysqli_insert_id -> devuelve el ID generado por una consulta en una tabla con una columna que tenga el atributo de AUTO INCREMENT, esto para almacenar en una variable el ID de la publicacion
                $id_regresado = mysqli_insert_id($this->con);
                // ! Faltan las notificaciones del post en el newsfeed

            }
            else if ($publicado_para != NULL)
            {
                $query_agrega_publicacion = mysqli_query($this->con, "INSERT INTO publicaciones VALUES('', '$titulo', '$cuerpo', '$publicado_por', $publicado_para, '$fecha_publicado', 'no', '0')");
                $id_regresado = mysqli_insert_id($this->con);
                $notificacion = new Notificacion($this->con, $publicado_por);
                $notificacion->insertarNotificacion($id_regresado, $publicado_para, "publicacion_perfil");
            }
            $id_regresado = mysqli_insert_id($this->con);

            // + Si el un amigo realizo una publicacion
            $lista_amigos = $this->objeto_usuario->obtenerListaAmigos();
            $lista_amigos_explode = explode(",", $lista_amigos);

            foreach($lista_amigos_explode as $amigo)
            {
                if($amigo != "")
                {
                    ?>
                    <script>
                        alert("<?php $amigo?>")
                    </script>
                    <?php
                    $query_obtener_id_amigo = mysqli_query($this->con, "SELECT id_usuario FROM usuarios WHERE username='$amigo'");
                    $fila = mysqli_fetch_array($query_obtener_id_amigo);
                    $id_amigo = $fila['id_usuario'];
                    $notificacion = new Notificacion($this->con, $publicado_por);
                    $notificacion->insertarNotificacion($id_regresado, $id_amigo, "amigo_publico");
                }
            }

            // + Si el un seguido realizo una publicacion
            $lista_seguidores = $this->objeto_usuario->obtenerListaSeguidores();
            $lista_seguidores_explode = explode(",", $lista_seguidores);

            foreach($lista_seguidores_explode as $seguidor)
            {
                if($seguidor != "")
                {
                    $query_obtener_id_seguidor = mysqli_query($this->con, "SELECT id_usuario FROM usuarios WHERE username='$seguidor'");
                    $fila = mysqli_fetch_array($query_obtener_id_seguidor);
                    $id_seguidor = $fila['id_usuario'];
                    $notificacion = new Notificacion($this->con, $publicado_por);
                    $notificacion->insertarNotificacion($id_regresado, $id_seguidor, "seguido_publico");
                }
            }

            // + Aumentar el conteo de publicaciones en el usuario
            $num_publicaciones = $this->objeto_usuario->obenerNumeroPublicaciones();
            // + Aumentamos por uno el numero de publicaciones
            $num_publicaciones++;
            $query_aumentar_publicaciones = mysqli_query($this->con, "UPDATE usuarios SET num_posts='$num_publicaciones' WHERE id_usuario='$publicado_por'");
        }
    }

    public function cargarPublicacionesAmigos ($info, $limite){
        // ! Esta seccion es del scroll infinito, checar como funciona
        // - Info es la variable $REQUEST mandaada a esta funcion
        // - Esta variable guardara la pagina actual
        // - $info['pagina'] accede a la variable pagina del request
        $pagina = $info['pagina'];
        $id_usuario_loggeado = $this->objeto_usuario->obtenerIDUsuario();

        if($pagina == 1)
        {
            // - Esta variable guardara el numero de post por el cual la pagina comenzara a cargar
            $comienzo = 0;
        }
        else
        {
            // + Por ejemplo si la pagina ha sido cargada 2 veces:
            // + 2 - 1 * 10 = 10 -> Empezara a cargar desde el elemento 10
            $comienzo = ($pagina - 1) * $limite;
        }
        // ! Esta seccion es del scroll infinito, checar como funciona


        // - Este string contendra todas las publicaciones
        $string_publicacion = "";
        // + Esta query va a obtener todas las publicaciones no borradas y las va a ordenar de forma descendente, es decir, las que se crearon primero, hasta abajo
        $query_info = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE borrado='no' ORDER BY id_publicacion DESC");

        // ! este if tambien es del scroll infinito
        // + Si existen publicaciones entonces:
        if(mysqli_num_rows($query_info) > 0)
        {
            // - Cuenta cuántas veces ha dado la vuelta el bucle
            $num_iteraciones = 0;
            // - Cuenta cuantos resultadoados hemos cargado
            $contador = 1;


            // + Mientras existan publicaciones en el arreglo, realizar el loop
            while($fila = mysqli_fetch_array($query_info))
            {
                // + Guardamos en variables, las variables de la fila de la base de datos
                $id_publicacion = $fila['id_publicacion'];
                $titulo = $fila['titulo'];
                $cuerpo = $fila['cuerpo'];
                // ! publicado por, me regresara un numero, necesito acceder al usuario de ese publicado por
                // ! SE PUEDE HACER CON UN INNER JOIN, PERO VOY A SACAR EL NOMBRE DE USUARIO CON SU ID Y METER EL NOMBRE DE USUARIO AL OBJET: objeto_publicado_por
                $id_publicado_por = $fila['publicado_por'];
                $query_publicado_por = mysqli_query($this->con, "SELECT username, tipo from usuarios WHERE id_usuario='$id_publicado_por'");
                $fila_publicado_por = mysqli_fetch_array($query_publicado_por);
                // - Asignamos nombre de usuario a publicado por
                $usuario_publicado_por = $fila_publicado_por['username'];
                $fecha_publicado = $fila['fecha_publicado'];
                $tipo_usuario_publicado_por = $fila_publicado_por['tipo'];

                #region publicado_para
                // + Si no una publicacion en un perfil de alguien, entonces el string de publicado_para estara vacio
                // ! RECORDAR QUE PUBLICADO PARA ES UN NUMERO DE FOREIGN KEY, REQUIERO SACAR EL NOMBRE DE USUARIO
                $id_publicado_para = $fila['publicado_para'];
                $query_publicado_para = mysqli_query($this->con, "SELECT id_usuario from usuarios WHERE id_usuario='$id_publicado_para'");
                // /$fila_publicado_para = mysqli_fetch_array($query_publicado_para);
                $checar_si = mysqli_num_rows($query_publicado_para);

                if($checar_si == 0)
                {
                    $publicado_para = "";
                }
                else
                {
                    // + De lo contrario, se creara un nuevo objeto usuario, con el nombre de usuario del perfil para el que se publico
                    $fila_publicado_para = mysqli_fetch_array($query_publicado_para);
                    $objeto_publicado_para = new Usuario($this->con, $fila_publicado_para['id_usuario']);
                    // + Se utilizaran dos funciones para obtener su nombre y su nombre de usuario
                    $publicado_para_nombre = $objeto_publicado_para->obtenerNombreCompleto();
                    $publicado_para_N_usuario = $objeto_publicado_para->obtenerNombreUsuario();
                    // + Se combinaran en un string para mostrar para quien se publico
                    $publicado_para = "para <a href='" . $publicado_para_N_usuario . "'>" . $publicado_para_nombre . "</a>";
                }
                #endregion

                #region verificar si la cuenta del usuario esta cerrada
                // + Creamos un nuevo usuario para el usuario que realizo la publicacion
                $objeto_publicado_por = new Usuario($this->con, $id_publicado_por);
                if($objeto_publicado_por->estaCerrado())
                {
                    // $ continue -> Detiene la iteracion actual y vuelve al principio del bucle para realizar otra iteracion
                    continue;
                }
                #endregion

                $objeto_usuario_loggeado = new Usuario($this->con, $id_usuario_loggeado);
                $tipo_usuario = $objeto_usuario_loggeado->obtenerTipoUsuario();
                // + Verifica si el usuario loggeado es amigo del que publico
                //! Aqui si necesito el nombre del que publico
                if($objeto_usuario_loggeado->esAmigo($usuario_publicado_por) || $objeto_usuario_loggeado->esSeguidor($usuario_publicado_por))
                {

                    //! Esta seccion tambien es del scroll infinito
                    // + Si no hemos alcanzado la posicion inicial, entonces regresaremos a realizar el loop
                    // + Esto iterara entre todos los posts que ya han sido cargados
                    if($num_iteraciones++ < $comienzo)
                    {
                        continue;
                    }

                    //! Este if tambien
                    // + Una vez que el limite de posts sea cargado, entonces, termina la iteracion
                    if($contador > $limite)
                    {
                        break;
                    }
                    else
                    {
                        $contador++;
                    }

                    if($id_usuario_loggeado == $id_publicado_por)
                    {
                        $boton_eliminar = "<button class='boton_eliminar btn btn-danger' data-es-propia='true' id='publicacion$id_publicacion'><i class='fa-solid fa-x'></i></button>";
                    }
                    else if ($tipo_usuario == "moderador" && $tipo_usuario_publicado_por == "normal" || $tipo_usuario == "administrador" && ($tipo_usuario_publicado_por == "normal" || $tipo_usuario_publicado_por == "moderador"))
                    {
                        $boton_eliminar = "<button class='boton_eliminar btn btn-danger' data-es-propia='false' id='publicacion$id_publicacion'><i class='fa-solid fa-x'></i></button>";
                    }
                    else
                    {
                        $boton_eliminar = "";
                    }

                    ?>
                    <?php

                    #region publicado_por
                    // + Query para seleccionar el nombre del usuario que publico y su foto de perfil
                    $query_detalles_usuario = mysqli_query($this->con, "SELECT nombre, apeP, apeM, foto_perfil FROM usuarios WHERE id_usuario='$id_publicado_por'");
                    // + Guardamos las variables en filas 
                    $fila_usuario = mysqli_fetch_array($query_detalles_usuario);
                    // + Guardamos en variables, las variables de la fila de la base de datos
                    $nombre = $fila_usuario['nombre'];
                    $apeP = $fila_usuario['apeP'];
                    $apeM = $fila_usuario['apeM'];
                    $foto_perfil = $fila_usuario['foto_perfil'];
                    #endregion

                    ?>
                    <!-- Este bloque es para mostrar los comentarios -->
                    <script>
                        // + Esta seccion es para saber que comentario mostrar
                        function toggle<?php echo $id_publicacion; ?>()
                        {
                            // $ event.target -> Es donde la persona hizo click
                            // - target -> Guarda donde hizo click la persona
                            var target = $(event.target);
                            // + Si un link no es clickeado, entonces mostrara o oculatara el comentario
                            if (!target.is("a")) {
                                var element = document.getElementById("mostrarComentarios<?php echo $id_publicacion ?>");
                                if(element.style.display == "block")
                                {
                                    element.style.display = "none";
                                }
                                else
                                {
                                    element.style.display = "block";
                                }
                            }
                        }
                    </script>
                    <?php

                    $checar_si_hay_comentarios = mysqli_query($this->con, "SELECT * FROM comentarios WHERE publicacion_comentada='$id_publicacion'");
                    $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios);

                    #region Periodo de tiempo de los posts
                    // - Guardamos la hora y fecha actuales
                    $tiempo_actual = date("Y-m-d H:i:s");
                    // - Guardamos la hora y fecha actuales en el que se realizo la publicacion
                    $fecha_comienzo = new DateTime($fecha_publicado);
                    // - Guardamos la hora y fecha actuales
                    $fecha_final = new DateTime($tiempo_actual);
                    // - Realizamos una diferencia de tiempos de la fecha inicial, con la actual para saber cuanto tiempo lleva la publicacion publicada
                    $intervalo = $fecha_comienzo->diff($fecha_final);
                    // + Si el intervalo es 1 o mas años
                    if($intervalo->y >= 1)
                    {
                        //Un año de antiguedad
                        if($intervalo->y == 1)
                        {
                            $mensaje_tiempo = $intervalo->y . " año atrás";
                        }
                        //Más de un año de antiguedad
                        else
                        {
                            $mensaje_tiempo = $intervalo->y . " años atrás";
                        }
                    }
                    // + Si el intervalo es 1 o mas de 1 mes atras, pero menos de un año
                    else if($intervalo->m >= 1)
                    {
                        // + Checamos los dias 
                        // 0 dias
                        if($intervalo->d == 0)
                        {
                            $dias = " atrás";
                        }
                        // 1 dia
                        else if($intervalo->d == 1)
                        {
                            $dias = $intervalo->d. "día atrás";
                        }
                        //Mas de 1 dia
                        else 
                        {
                            $dias = $intervalo->d . " días atrás";
                        }

                        //1 mes
                        if($intervalo-> m == 1)
                        {
                            $mensaje_tiempo = $intervalo->m . " mes" . $dias;
                        }
                        //Mas de 1 mes
                        else
                        {
                            $mensaje_tiempo = $intervalo->m . " meses" . $dias;
                        }
                    }
                    // + Si el intervalo es 1 o mas dias atras, pero menos que un mes
                    else if($intervalo->d >= 1)
                    {
                        //1 dia
                        if($intervalo->d == 1)
                        {
                            $mensaje_tiempo = "ayer";
                        }
                        //Mas de un dia
                        else 
                        {
                            $mensaje_tiempo = $intervalo->d . " días atrás";
                        }
                    }
                    // + Si el intervalo es 1 o mas horas atras, pero menos que un dia
                    else if($intervalo->h >= 1)
                    {
                        //1 hora atras
                        if($intervalo->h == 1)
                        {
                            $mensaje_tiempo = $intervalo->h . " hora atrás";
                        }
                        //Mas de una hora
                        else 
                        {
                            $mensaje_tiempo = $intervalo->h . " horas atrás";
                        }
                    }
                    // + Si el intervalo es de 1 minuto o mas atras, pero menos que una hora
                    else if($intervalo->i >= 1)
                    {
                        //1 minuto atras
                        if($intervalo->i == 1)
                        {
                            $mensaje_tiempo = $intervalo->i . " minuto atrás";
                        }
                        //Mas de un minuto
                        else 
                        {
                            $mensaje_tiempo = $intervalo->i . " minutos atrás";
                        }
                    }
                    // + Si el intervalo es de 1 segundo o mas atras, pero menos que un minuto
                    else
                    {
                        //Menos que 30 segundos
                        if($intervalo->s < 30)
                        {
                            $mensaje_tiempo = "Justo ahora";
                        }
                        //30 segundos o mas
                        else 
                        {
                            $mensaje_tiempo = "Hace unos segundos";
                        }
                    }
                    #endregion

                    // + En este string se guardara cada publciacion y cada que se ejecute la carga de una, se emitira un echo, para mostrarla al usuario
                    // + Tenemos divido por doto de perfil, un mensaje de cuanto tiempo ha pasado desde que se hizo la publicacion y el cuerpo de la publicacion
                    $string_publicacion .= 
                    // + onClick='javascript:toggle$id_publicacion' -> Cuando hagamos click, se ejecutara la funcion
                    // + <div class ='publicar_comentario'> -> lo ocultamos con display:none, este cambiara como ya lo explicamos antes entre block y none
                        "<div class='publicacion'>
                            <div class='foto_perfil_publicacion'>
                                <img src='$foto_perfil' width='50'>
                            </div>

                            <div class='publicado_por' style='color:#ACACAC;'>
                                <a href='$usuario_publicado_por'> $nombre $apeP $apeM </a> $publicado_para &nbsp;&nbsp;&nbsp;&nbsp;$mensaje_tiempo
                                $boton_eliminar
                            </div>
                            <div id='titulo_publicacion' style='font-style: bold;'>
                                $titulo
                                <hr>
                            </div>
                            <div id='cuerpo_publicacion'>
                                $cuerpo
                                <br>
                                <br>
                                <br>
                            </div>

                            <div class='OpcionesDePublicacion'>
                                &nbsp;
                                <span class='mostrar_ocultar_comentarios' onClick='javascript:toggle$id_publicacion()'>
                                    <i class='fa-solid fa-comment'></i>&nbsp;$numero_comentarios Comentarios
                                </span>
                                <iframe src='like.php?id_publicacion=$id_publicacion' scrolling='no' id='iframe_likes'></iframe>
                            </div>
                        </div>
                        <div class ='publicar_comentario' id='mostrarComentarios$id_publicacion' style='display:none;'>
                            <iframe src='comment_frame.php?id_publicacion=$id_publicacion' id='iframe_comentario' frameborder='0'></iframe>
                        </div>
                        <hr>";
                }
                ?>
                <script>
                    $(document).ready(function(){
                        $('#publicacion<?php echo $id_publicacion; ?>').on('click', function() {
                            // + Esta variable determinara si la publicacion que se quiere eliminar es propia o si es de algun otro usuario
                            var es_propia = $(this).data('es-propia');
                            // + Si la variable es true, entonces, solo eliminamos la publicacion
                            // + El triple signo de igualdad, hace una comparacion estricta de igualdad entre dos valores
                            // + Compara tanto el valor como el tipo de datos de los valores que se estan comparando
                            if (es_propia === true)
                            {
                                // - resultado -> Sera el resultadoado de lo que el usuario clickeo, si fue "si" o "no"
                                bootbox.confirm("¿Estas seguro que quieres eliminar esta publicacion?", function(result) {
                                // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                $.post("includes/form_handlers/delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>", {resultado:result});
                                    if(result == true)
                                    {
                                        location.reload();
                                    }
                                });
                            }
                            else 
                            {
                                bootbox.prompt({
                                title: "Por favor, escribe una razón para la eliminación:",
                                buttons: {
                                    confirm: {
                                    label: 'Aceptar',
                                    className: 'btn-danger'
                                    }
                                },
                                callback: function(result) {
                                    if (result !== true && result !== '') {
                                        $.post("includes/form_handlers/delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>", { resultado:result, razon:result});
                                        location.reload();
                                    } 
                                    else 
                                    {
                                        alert("Debes ingresar una razón para eliminar la publicación.");
                                    }
                                }
                                });
                            }

                        });
                    });
                </script>
                <?php
            } // * while($fila = mysqli_fetch_array($info))
            // ! esta parte tambien es del scroll infinito
            // + Si contador es mas grande que limite, significa que hay mas posts por cargar
            if($contador > $limite)
            {
                $string_publicacion .= "<input type='hidden' class='siguientePagina' value='" . ($pagina + 1) . "'>
							            <input type='hidden' class='noMasPublicaciones' value='false'>";
                            
            }
            // + Si contador NO es mas grande que limite, significa que ya NO hay mas posts por cargar
            else
            {
                $string_publicacion .= "<input type='hidden' class='noMasPublicaciones' value='true'><p style='text-align: center;'> No hay más publicaciones por mostrar! </p>";
            }
        }
        echo $string_publicacion;
    }


    public function cargarPublicacionesPerfil ($info, $limite){
        // ! Esta seccion es del scroll infinito, checar como funciona
        // - Info es la variable $REQUEST mandaada a esta funcion
        // - Esta variable guardara la pagina actual
        // - $info['pagina'] accede a la variable pagina del request
        $pagina = $info['pagina'];
        $id_usuario_perfil = $info['id_usuario_perfil'];
        $id_usuario_loggeado = $this->objeto_usuario->obtenerIDUsuario();

        if($pagina == 1)
        {
            // - Esta variable guardara el numero de post por el cual la pagina comenzara a cargar
            $comienzo = 0;
        }
        else
        {
            // + Por ejemplo si la pagina ha sido cargada 2 veces:
            // + 2 - 1 * 10 = 10 -> Empezara a cargar desde el elemento 10
            $comienzo = ($pagina - 1) * $limite;
        }
        // ! Esta seccion es del scroll infinito, checar como funciona


        // - Este string contendra todas las publicaciones
        $string_publicacion = "";
        // + Esta query va a obtener las publicaciones que ha hecho el usuario del perfil que visitemos o las publicaciones que le han hecho a este usuario en su perfil
        $query_info = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE borrado='no' AND ((publicado_por='$id_usuario_perfil' AND publicado_para IS null) OR publicado_para='$id_usuario_perfil') ORDER BY id_publicacion DESC");

        // ! este if tambien es del scroll infinito
        // + Si existen publicaciones entonces:
        if(mysqli_num_rows($query_info) > 0)
        {
            // - Cuenta cuántas veces ha dado la vuelta el bucle
            $num_iteraciones = 0;
            // - Cuenta cuantos resultadoados hemos cargado
            $contador = 1;


            // + Mientras existan publicaciones en el arreglo, realizar el loop
            while($fila = mysqli_fetch_array($query_info))
            {
                // + Guardamos en variables, las variables de la fila de la base de datos
                $id_publicacion = $fila['id_publicacion'];
                $titulo = $fila['titulo'];
                $cuerpo = $fila['cuerpo'];
                // ! publicado por, me regresara un numero, necesito acceder al usuario de ese publicado por
                // ! SE PUEDE HACER CON UN INNER JOIN, PERO VOY A SACAR EL NOMBRE DE USUARIO CON SU ID Y METER EL NOMBRE DE USUARIO AL OBJET: objeto_publicado_por
                $id_publicado_por = $fila['publicado_por'];
                $query_publicado_por = mysqli_query($this->con, "SELECT username, tipo from usuarios WHERE id_usuario='$id_publicado_por'");
                $fila_publicado_por = mysqli_fetch_array($query_publicado_por);
                // - Asignamos nombre de usuario a publicado por
                $usuario_publicado_por = $fila_publicado_por['username'];
                $fecha_publicado = $fila['fecha_publicado'];
                $tipo_usuario_publicado_por = $fila_publicado_por['tipo'];

                #region publicado_para
                // + Si no una publicacion en un perfil de alguien, entonces el string de publicado_para estara vacio
                // ! RECORDAR QUE PUBLICADO PARA ES UN NUMERO DE FOREIGN KEY, REQUIERO SACAR EL NOMBRE DE USUARIO
                $id_publicado_para = $fila['publicado_para'];
                $query_publicado_para = mysqli_query($this->con, "SELECT id_usuario from usuarios WHERE id_usuario='$id_publicado_para'");
                // /$fila_publicado_para = mysqli_fetch_array($query_publicado_para);
                $checar_si = mysqli_num_rows($query_publicado_para);
                #endregion

                $objeto_usuario_loggeado = new Usuario($this->con, $id_usuario_loggeado);
                $tipo_usuario = $objeto_usuario_loggeado->obtenerTipoUsuario();
                // + Verifica si el usuario loggeado es amigo del que publico


                    //! Esta seccion tambien es del scroll infinito
                    // + Si no hemos alcanzado la posicion inicial, entonces regresaremos a realizar el loop
                    // + Esto iterara entre todos los posts que ya han sido cargados
                    if($num_iteraciones++ < $comienzo)
                    {
                        continue;
                    }

                    //! Este if tambien
                    // + Una vez que el limite de posts sea cargado, entonces, termina la iteracion
                    if($contador > $limite)
                    {
                        break;
                    }
                    else
                    {
                        $contador++;
                    }

                    if($id_usuario_loggeado == $id_publicado_por && $tipo_usuario == "normal" || $tipo_usuario == "moderador" && ($tipo_usuario_publicado_por == "normal" || $id_usuario_loggeado == $id_publicado_por) || $tipo_usuario == "administrador" && ($tipo_usuario_publicado_por == "normal" || $tipo_usuario_publicado_por == "moderador" || $id_usuario_loggeado == $id_publicado_por))
                    {
                        $boton_eliminar = "<button class='boton_eliminar btn btn-danger' id='publicacion$id_publicacion'><i class='fa-solid fa-x'></i></button>";
                    }
                    else
                    {
                        $boton_eliminar = "";
                    }

                    ?>
                    <?php

                    #region publicado_por
                    // + Query para seleccionar el nombre del usuario que publico y su foto de perfil
                    $query_detalles_usuario = mysqli_query($this->con, "SELECT nombre, apeP, apeM, foto_perfil FROM usuarios WHERE id_usuario='$id_publicado_por'");
                    // + Guardamos las variables en filas 
                    $fila_usuario = mysqli_fetch_array($query_detalles_usuario);
                    // + Guardamos en variables, las variables de la fila de la base de datos
                    $nombre = $fila_usuario['nombre'];
                    $apeP = $fila_usuario['apeP'];
                    $apeM = $fila_usuario['apeM'];
                    $foto_perfil = $fila_usuario['foto_perfil'];
                    #endregion

                    ?>
                    <!-- Este bloque es para mostrar los comentarios -->
                    <script>
                        // + Esta seccion es para saber que comentario mostrar
                        function toggle<?php echo $id_publicacion; ?>()
                        {
                            // $ event.target -> Es donde la persona hizo click
                            // - target -> Guarda donde hizo click la persona
                            var target = $(event.target);
                            // + Si un link no es clickeado, entonces mostrara o oculatara el comentario
                            if (!target.is("a")) {
                                var element = document.getElementById("mostrarComentarios<?php echo $id_publicacion ?>");
                                if(element.style.display == "block")
                                {
                                    element.style.display = "none";
                                }
                                else
                                {
                                    element.style.display = "block";
                                }
                            }
                        }
                    </script>
                    <?php

                    $checar_si_hay_comentarios = mysqli_query($this->con, "SELECT * FROM comentarios WHERE publicacion_comentada='$id_publicacion'");
                    $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios);

                    #region Periodo de tiempo de los posts
                    // - Guardamos la hora y fecha actuales
                    $tiempo_actual = date("Y-m-d H:i:s");
                    // - Guardamos la hora y fecha actuales en el que se realizo la publicacion
                    $fecha_comienzo = new DateTime($fecha_publicado);
                    // - Guardamos la hora y fecha actuales
                    $fecha_final = new DateTime($tiempo_actual);
                    // - Realizamos una diferencia de tiempos de la fecha inicial, con la actual para saber cuanto tiempo lleva la publicacion publicada
                    $intervalo = $fecha_comienzo->diff($fecha_final);
                    // + Si el intervalo es 1 o mas años
                    if($intervalo->y >= 1)
                    {
                        //Un año de antiguedad
                        if($intervalo->y == 1)
                        {
                            $mensaje_tiempo = $intervalo->y . " año atrás";
                        }
                        //Más de un año de antiguedad
                        else
                        {
                            $mensaje_tiempo = $intervalo->y . " años atrás";
                        }
                    }
                    // + Si el intervalo es 1 o mas de 1 mes atras, pero menos de un año
                    else if($intervalo->m >= 1)
                    {
                        // + Checamos los dias 
                        // 0 dias
                        if($intervalo->d == 0)
                        {
                            $dias = " atrás";
                        }
                        // 1 dia
                        else if($intervalo->d == 1)
                        {
                            $dias = $intervalo->d. "día atrás";
                        }
                        //Mas de 1 dia
                        else 
                        {
                            $dias = $intervalo->d . " días atrás";
                        }

                        //1 mes
                        if($intervalo-> m == 1)
                        {
                            $mensaje_tiempo = $intervalo->m . " mes" . $dias;
                        }
                        //Mas de 1 mes
                        else
                        {
                            $mensaje_tiempo = $intervalo->m . " meses" . $dias;
                        }
                    }
                    // + Si el intervalo es 1 o mas dias atras, pero menos que un mes
                    else if($intervalo->d >= 1)
                    {
                        //1 dia
                        if($intervalo->d == 1)
                        {
                            $mensaje_tiempo = "ayer";
                        }
                        //Mas de un dia
                        else 
                        {
                            $mensaje_tiempo = $intervalo->d . " días atrás";
                        }
                    }
                    // + Si el intervalo es 1 o mas horas atras, pero menos que un dia
                    else if($intervalo->h >= 1)
                    {
                        //1 hora atras
                        if($intervalo->h == 1)
                        {
                            $mensaje_tiempo = $intervalo->h . " hora atrás";
                        }
                        //Mas de una hora
                        else 
                        {
                            $mensaje_tiempo = $intervalo->h . " horas atrás";
                        }
                    }
                    // + Si el intervalo es de 1 minuto o mas atras, pero menos que una hora
                    else if($intervalo->i >= 1)
                    {
                        //1 minuto atras
                        if($intervalo->i == 1)
                        {
                            $mensaje_tiempo = $intervalo->i . " minuto atrás";
                        }
                        //Mas de un minuto
                        else 
                        {
                            $mensaje_tiempo = $intervalo->i . " minutos atrás";
                        }
                    }
                    // + Si el intervalo es de 1 segundo o mas atras, pero menos que un minuto
                    else
                    {
                        //Menos que 30 segundos
                        if($intervalo->s < 30)
                        {
                            $mensaje_tiempo = "Justo ahora";
                        }
                        //30 segundos o mas
                        else 
                        {
                            $mensaje_tiempo = "Hace unos segundos";
                        }
                    }
                    #endregion

                    // + En este string se guardara cada publciacion y cada que se ejecute la carga de una, se emitira un echo, para mostrarla al usuario
                    // + Tenemos divido por doto de perfil, un mensaje de cuanto tiempo ha pasado desde que se hizo la publicacion y el cuerpo de la publicacion
                    $string_publicacion .= 
                    // + onClick='javascript:toggle$id_publicacion' -> Cuando hagamos click, se ejecutara la funcion
                    // + <div class ='publicar_comentario'> -> lo ocultamos con display:none, este cambiara como ya lo explicamos antes entre block y none
                        "<div class='publicacion'>
                            <div class='foto_perfil_publicacion'>
                                <img src='$foto_perfil' width='50'>
                            </div>

                            <div class='publicado_por' style='color:#ACACAC;'>
                                <a href='$usuario_publicado_por'> $nombre $apeP $apeM </a> &nbsp;&nbsp;&nbsp;&nbsp;$mensaje_tiempo
                                $boton_eliminar
                            </div>
                            <div id='titulo_publicacion' style='font-style: bold;'>
                                $titulo
                                <hr>
                            </div>
                            <div id='cuerpo_publicacion'>
                                $cuerpo
                                <br>
                                <br>
                                <br>
                            </div>

                            <div class='OpcionesDePublicacion'>
                                &nbsp;
                                <span class='mostrar_ocultar_comentarios' onClick='javascript:toggle$id_publicacion()'>
                                    <i class='fa-solid fa-comment'></i>&nbsp;$numero_comentarios Comentarios
                                </span>
                                <iframe src='like.php?id_publicacion=$id_publicacion' scrolling='no' id='iframe_likes'></iframe>
                            </div>
                        </div>
                        <div class ='publicar_comentario' id='mostrarComentarios$id_publicacion' style='display:none;'>
                            <iframe src='comment_frame.php?id_publicacion=$id_publicacion' id='iframe_comentario' frameborder='0'></iframe>
                        </div>
                        <hr>";
                ?>
                <script>
                    $(document).ready(function(){
                        $('#publicacion<?php echo $id_publicacion; ?>').on('click', function() {
                            // + Esta variable determinara si la publicacion que se quiere eliminar es propia o si es de algun otro usuario
                            var es_propia = $(this).data('es-propia');
                            // + Si la variable es true, entonces, solo eliminamos la publicacion
                            // + El triple signo de igualdad, hace una comparacion estricta de igualdad entre dos valores
                            // + Compara tanto el valor como el tipo de datos de los valores que se estan comparando
                            if (es_propia === true)
                            {
                                // - resultado -> Sera el resultadoado de lo que el usuario clickeo, si fue "si" o "no"
                                bootbox.confirm("¿Estas seguro que quieres eliminar esta publicacion?", function(resultado) {
                                // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                $.post("includes/form_handlers/delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>", {resultado:resultado});
                                    if(resultado == true)
                                    {
                                        location.reload();
                                    }
                                });
                            }
                            else
                            {
                                bootbox.prompt({
                                    title: "Mencione el motivo por el que se eliminara esta publicacion",
                                    inputType: "textarea",
                                    callback: function (resultado)
                                    {
                                        if (resultado !== null)
                                        {
                                            $.post("includes/form_handlers/delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>", {resultado:resultado});
                                            location.reload();
                                        }
                                    }
                                });
                            }

                        });
                    });
                </script>
                <?php
            } // * while($fila = mysqli_fetch_array($info))
            // ! esta parte tambien es del scroll infinito
            // + Si contador es mas grande que limite, significa que hay mas posts por cargar
            if($contador > $limite)
            {
                $string_publicacion .= "<input type='hidden' class='siguientePagina' value='" . ($pagina + 1) . "'>
							            <input type='hidden' class='noMasPublicaciones' value='false'>";
                            
            }
            // + Si contador NO es mas grande que limite, significa que ya NO hay mas posts por cargar
            else
            {
                $string_publicacion .= "<input type='hidden' class='noMasPublicaciones' value='true'><p style='text-align: center;'> No hay más publicaciones por mostrar! </p>";
            }
        }
        echo $string_publicacion;
    }

    public function obtenerPublicacionSolicitada($id_publicacion)
    {
        $id_usuario_loggeado = $this->objeto_usuario->obtenerIDUsuario();

        $query_notificacion_abierta = mysqli_query($this->con, "UPDATE notificaciones SET abierta='si' WHERE notificacion_para='$id_usuario_loggeado' AND link LIKE '%=$id_publicacion'");


        // - Este string contendra todas las publicaciones
        $string_publicacion = "";
        // + Esta query va a obtener todas las publicaciones no borradas y las va a ordenar de forma descendente, es decir, las que se crearon primero, hasta abajo
        $query_info = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE borrado='no' AND id_publicacion='$id_publicacion'");

        // ! este if tambien es del scroll infinito
        // + Si existen publicaciones entonces:
        if(mysqli_num_rows($query_info) > 0)
        {
            $fila = mysqli_fetch_array($query_info);
            // + Guardamos en variables, las variables de la fila de la base de datos
            $titulo = $fila['titulo'];
            $cuerpo = $fila['cuerpo'];
            // ! publicado por, me regresara un numero, necesito acceder al usuario de ese publicado por
            // ! SE PUEDE HACER CON UN INNER JOIN, PERO VOY A SACAR EL NOMBRE DE USUARIO CON SU ID Y METER EL NOMBRE DE USUARIO AL OBJET: objeto_publicado_por
            $id_publicado_por = $fila['publicado_por'];
            $query_publicado_por = mysqli_query($this->con, "SELECT username, tipo from usuarios WHERE id_usuario='$id_publicado_por'");
            $fila_publicado_por = mysqli_fetch_array($query_publicado_por);
            // - Asignamos nombre de usuario a publicado por
            $usuario_publicado_por = $fila_publicado_por['username'];
            $fecha_publicado = $fila['fecha_publicado'];
            $tipo_usuario_publicado_por = $fila_publicado_por['tipo'];

            #region publicado_para
            // + Si no una publicacion en un perfil de alguien, entonces el string de publicado_para estara vacio
            // ! RECORDAR QUE PUBLICADO PARA ES UN NUMERO DE FOREIGN KEY, REQUIERO SACAR EL NOMBRE DE USUARIO
            $id_publicado_para = $fila['publicado_para'];
            $query_publicado_para = mysqli_query($this->con, "SELECT id_usuario from usuarios WHERE id_usuario='$id_publicado_para'");
            // /$fila_publicado_para = mysqli_fetch_array($query_publicado_para);
            $checar_si = mysqli_num_rows($query_publicado_para);

            if($checar_si == 0)
            {
                $publicado_para = "";
            }
            else
            {
                // + De lo contrario, se creara un nuevo objeto usuario, con el nombre de usuario del perfil para el que se publico
                $fila_publicado_para = mysqli_fetch_array($query_publicado_para);
                $objeto_publicado_para = new Usuario($this->con, $fila_publicado_para['id_usuario']);
                // + Se utilizaran dos funciones para obtener su nombre y su nombre de usuario
                $publicado_para_nombre = $objeto_publicado_para->obtenerNombreCompleto();
                $publicado_para_N_usuario = $objeto_publicado_para->obtenerNombreUsuario();
                // + Se combinaran en un string para mostrar para quien se publico
                $publicado_para = "para <a href='" . $publicado_para_N_usuario . "'>" . $publicado_para_nombre . "</a>";
            }
            #endregion

            #region verificar si la cuenta del usuario esta cerrada
            // + Creamos un nuevo usuario para el usuario que realizo la publicacion
            $objeto_publicado_por = new Usuario($this->con, $id_publicado_por);
            if($objeto_publicado_por->estaCerrado())
            {
                // $ continue -> Detiene la iteracion actual y vuelve al principio del bucle para realizar otra iteracion
                return;
            }
            #endregion

            $objeto_usuario_loggeado = new Usuario($this->con, $id_usuario_loggeado);
            $tipo_usuario = $objeto_usuario_loggeado->obtenerTipoUsuario();
            // + Verifica si el usuario loggeado es amigo del que publico
            //! Aqui si necesito el nombre del que publico
            if($objeto_usuario_loggeado->esAmigo($usuario_publicado_por) || $objeto_usuario_loggeado->esSeguidor($usuario_publicado_por))
            {
                if($id_usuario_loggeado == $id_publicado_por)
                {
                    $boton_eliminar = "<button class='boton_eliminar btn btn-danger' data-es-propia='true' id='publicacion$id_publicacion'><i class='fa-solid fa-x'></i></button>";
                }
                else if ($tipo_usuario == "moderador" && $tipo_usuario_publicado_por == "normal" || $tipo_usuario == "administrador" && ($tipo_usuario_publicado_por == "normal" || $tipo_usuario_publicado_por == "moderador"))
                {
                    $boton_eliminar = "<button class='boton_eliminar btn btn-danger' data-es-propia='false' id='publicacion$id_publicacion'><i class='fa-solid fa-x'></i></button>";
                }
                else
                {
                    $boton_eliminar = "";
                }

                ?>
                <?php

                #region publicado_por
                // + Query para seleccionar el nombre del usuario que publico y su foto de perfil
                $query_detalles_usuario = mysqli_query($this->con, "SELECT nombre, apeP, apeM, foto_perfil FROM usuarios WHERE id_usuario='$id_publicado_por'");
                // + Guardamos las variables en filas 
                $fila_usuario = mysqli_fetch_array($query_detalles_usuario);
                // + Guardamos en variables, las variables de la fila de la base de datos
                $nombre = $fila_usuario['nombre'];
                $apeP = $fila_usuario['apeP'];
                $apeM = $fila_usuario['apeM'];
                $foto_perfil = $fila_usuario['foto_perfil'];
                #endregion

                ?>
                <!-- Este bloque es para mostrar los comentarios -->
                <script>
                    // + Esta seccion es para saber que comentario mostrar
                    function toggle<?php echo $id_publicacion; ?>()
                    {
                        // $ event.target -> Es donde la persona hizo click
                        // - target -> Guarda donde hizo click la persona
                        var target = $(event.target);
                        // + Si un link no es clickeado, entonces mostrara o oculatara el comentario
                        if (!target.is("a")) {
                            var element = document.getElementById("mostrarComentarios<?php echo $id_publicacion ?>");
                            if(element.style.display == "block")
                            {
                                element.style.display = "none";
                            }
                            else
                            {
                                element.style.display = "block";
                            }
                        }
                    }
                </script>
                <?php

                $checar_si_hay_comentarios = mysqli_query($this->con, "SELECT * FROM comentarios WHERE publicacion_comentada='$id_publicacion'");
                $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios);

                #region Periodo de tiempo de los posts
                // - Guardamos la hora y fecha actuales
                $tiempo_actual = date("Y-m-d H:i:s");
                // - Guardamos la hora y fecha actuales en el que se realizo la publicacion
                $fecha_comienzo = new DateTime($fecha_publicado);
                // - Guardamos la hora y fecha actuales
                $fecha_final = new DateTime($tiempo_actual);
                // - Realizamos una diferencia de tiempos de la fecha inicial, con la actual para saber cuanto tiempo lleva la publicacion publicada
                $intervalo = $fecha_comienzo->diff($fecha_final);
                // + Si el intervalo es 1 o mas años
                if($intervalo->y >= 1)
                {
                    //Un año de antiguedad
                    if($intervalo->y == 1)
                    {
                        $mensaje_tiempo = $intervalo->y . " año atrás";
                    }
                    //Más de un año de antiguedad
                    else
                    {
                        $mensaje_tiempo = $intervalo->y . " años atrás";
                    }
                }
                // + Si el intervalo es 1 o mas de 1 mes atras, pero menos de un año
                else if($intervalo->m >= 1)
                {
                    // + Checamos los dias 
                    // 0 dias
                    if($intervalo->d == 0)
                    {
                        $dias = " atrás";
                    }
                    // 1 dia
                    else if($intervalo->d == 1)
                    {
                        $dias = $intervalo->d. "día atrás";
                    }
                    //Mas de 1 dia
                    else 
                    {
                        $dias = $intervalo->d . " días atrás";
                    }

                    //1 mes
                    if($intervalo-> m == 1)
                    {
                        $mensaje_tiempo = $intervalo->m . " mes" . $dias;
                    }
                    //Mas de 1 mes
                    else
                    {
                        $mensaje_tiempo = $intervalo->m . " meses" . $dias;
                    }
                }
                // + Si el intervalo es 1 o mas dias atras, pero menos que un mes
                else if($intervalo->d >= 1)
                {
                    //1 dia
                    if($intervalo->d == 1)
                    {
                        $mensaje_tiempo = "ayer";
                    }
                    //Mas de un dia
                    else 
                    {
                        $mensaje_tiempo = $intervalo->d . " días atrás";
                    }
                }
                // + Si el intervalo es 1 o mas horas atras, pero menos que un dia
                else if($intervalo->h >= 1)
                {
                    //1 hora atras
                    if($intervalo->h == 1)
                    {
                        $mensaje_tiempo = $intervalo->h . " hora atrás";
                    }
                    //Mas de una hora
                    else 
                    {
                        $mensaje_tiempo = $intervalo->h . " horas atrás";
                    }
                }
                // + Si el intervalo es de 1 minuto o mas atras, pero menos que una hora
                else if($intervalo->i >= 1)
                {
                    //1 minuto atras
                    if($intervalo->i == 1)
                    {
                        $mensaje_tiempo = $intervalo->i . " minuto atrás";
                    }
                    //Mas de un minuto
                    else 
                    {
                        $mensaje_tiempo = $intervalo->i . " minutos atrás";
                    }
                }
                // + Si el intervalo es de 1 segundo o mas atras, pero menos que un minuto
                else
                {
                    //Menos que 30 segundos
                    if($intervalo->s < 30)
                    {
                        $mensaje_tiempo = "Justo ahora";
                    }
                    //30 segundos o mas
                    else 
                    {
                        $mensaje_tiempo = "Hace unos segundos";
                    }
                }
                #endregion

                // + En este string se guardara cada publciacion y cada que se ejecute la carga de una, se emitira un echo, para mostrarla al usuario
                // + Tenemos divido por doto de perfil, un mensaje de cuanto tiempo ha pasado desde que se hizo la publicacion y el cuerpo de la publicacion
                $string_publicacion .= 
                // + onClick='javascript:toggle$id_publicacion' -> Cuando hagamos click, se ejecutara la funcion
                // + <div class ='publicar_comentario'> -> lo ocultamos con display:none, este cambiara como ya lo explicamos antes entre block y none
                    "<div class='publicacion'>
                        <div class='foto_perfil_publicacion'>
                            <img src='$foto_perfil' width='50'>
                        </div>

                        <div class='publicado_por' style='color:#ACACAC;'>
                            <a href='$usuario_publicado_por'> $nombre $apeP $apeM </a> $publicado_para &nbsp;&nbsp;&nbsp;&nbsp;$mensaje_tiempo
                            $boton_eliminar
                        </div>
                        <div id='titulo_publicacion' style='font-style: bold;'>
                            $titulo
                            <hr>
                        </div>
                        <div id='cuerpo_publicacion'>
                            $cuerpo
                            <br>
                            <br>
                            <br>
                        </div>

                        <div class='OpcionesDePublicacion'>
                            &nbsp;
                            <span class='mostrar_ocultar_comentarios' onClick='javascript:toggle$id_publicacion()'>
                                <i class='fa-solid fa-comment'></i>&nbsp;$numero_comentarios Comentarios
                            </span>
                            <iframe src='like.php?id_publicacion=$id_publicacion' scrolling='no' id='iframe_likes'></iframe>
                        </div>
                    </div>
                    <div class ='publicar_comentario' id='mostrarComentarios$id_publicacion' style='display:none;'>
                        <iframe src='comment_frame.php?id_publicacion=$id_publicacion' id='iframe_comentario' frameborder='0'></iframe>
                    </div>
                    <hr>";

                ?>
                <script>
                    $(document).ready(function(){
                        $('#publicacion<?php echo $id_publicacion; ?>').on('click', function() {
                            // + Esta variable determinara si la publicacion que se quiere eliminar es propia o si es de algun otro usuario
                            var es_propia = $(this).data('es-propia');
                            // + Si la variable es true, entonces, solo eliminamos la publicacion
                            // + El triple signo de igualdad, hace una comparacion estricta de igualdad entre dos valores
                            // + Compara tanto el valor como el tipo de datos de los valores que se estan comparando
                            if (es_propia === true)
                            {
                                // - resultado -> Sera el resultadoado de lo que el usuario clickeo, si fue "si" o "no"
                                bootbox.confirm("¿Estas seguro que quieres eliminar esta publicacion?", function(result) {
                                // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                $.post("includes/form_handlers/delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>", {resultado:result});
                                    if(result == true)
                                    {
                                        location.reload();
                                    }
                                });
                            }
                            else 
                            {
                                bootbox.prompt({
                                title: "Por favor, escribe una razón para la eliminación:",
                                buttons: {
                                    confirm: {
                                    label: 'Aceptar',
                                    className: 'btn-danger'
                                    }
                                },
                                callback: function(result) {
                                    if (result !== true && result !== '') {
                                        $.post("includes/form_handlers/delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>", { resultado:result, razon:result});
                                        location.reload();
                                    } 
                                    else 
                                    {
                                        alert("Debes ingresar una razón para eliminar la publicación.");
                                    }
                                }
                                });
                            }

                        });
                    });
                </script>
                <?php
            }
            // + Si el usuario no es amigo o seguidor del usuario que publico
            else
            {
                echo "<p>No puedes ver este post a menos que sigas o seas amigo del usuario que lo publicó</p>";
                return;
            }
        }
        else
        {
            echo "<p>No se encontro la publicación, puede que haya sido eliminada o no exista!</p>";
            return;
        }
        echo $string_publicacion;
    }
}

?>