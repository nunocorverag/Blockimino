<?php
class Publicacion {
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

    // + Se encargara de introducir la publicacion en la base de datos:
    // + $cuerpo -> Sera el cuerpo de la publicacion
    // + $enviado_a -> Si un usuario publico en el perfil de otro, esta variable se utilizara, de lo contrario, sera nula
    public function enviarPublicacion($titulo, $cuerpo, $publicado_para, $nombre_imagenes, $nombre_archivos, $id_proyecto, $tipo_pagina, $hashtags)
    {
        // $ strip_tags -> Retira las etiqueras HTML y PHP de un string
        $cuerpo = strip_tags($cuerpo);
        // $ mysqli_real_escape_string -> Escapa caracteres especiales para insertarlos en la base de datos
        $cuerpo = mysqli_real_escape_string($this->con, $cuerpo);

        $cuerpo_sin_formato = $cuerpo;
        
        // $ str_replace -> Reemplaza todas las occurrencias con otro string que querramos
        // +$ 1. String a reemplazar 2. String por el que se va a reemplazar 3. String completo en el que se va a reemplazar

        $cuerpo = str_replace("\\r\\n", "\\r\\n<separar>", $cuerpo);
        $cuerpo = str_replace("\\r", "\\r<separar>", $cuerpo);
        $cuerpo = str_replace("\\n", "\\n<separar>", $cuerpo);

        $cuerpo = str_replace(" ", "&nbsp;<separar>", $cuerpo);

        $titulo = strip_tags($titulo);
        $titulo = mysqli_real_escape_string($this->con, $titulo);

        // + Checa si hay espacios vacios y los reemplaza con un string vacio
        $checar_cuerpo_vacio = preg_replace('/\s+/', '', $cuerpo);
        $checar_titulo_vacio = preg_replace('/\s+/', '', $titulo);
        // + Revisa si el string completo no esta vacio para poderlo almacenar en la base de datos
        if ($checar_cuerpo_vacio != "" && $checar_titulo_vacio != "")
        {
            #reg Esta parte es para ver los videos con links de youtube
            $arreglo_cuerpo = preg_split("/<separar>/", $cuerpo);

            // Aplicar nl2br para convertir los saltos de línea en <br>
            $arreglo_cuerpo = array_map('nl2br', $arreglo_cuerpo);            
            // + Separamos y metemos en un arreglo para ver si hay links en la publicacion
            // + key mantendra el indice en el que se encuentra valor
            // + valor sera el elemento dentro del arreglo
            foreach($arreglo_cuerpo as $key => $valor)
            {
                // Verificar si el valor contiene "www" pero no contiene "https://"
                if(strpos($valor, "www") !== false && strpos($valor, "https://") === false)
                {
                    // Agregar "https://" al inicio del enlace
                    $valor = "https://" . $valor;
                }

                // ! ANALIZAR BIEN EL TEMA DE !==
                if (strpos($valor, "https://") === 0 && strpos($valor, "https://www.youtube.com/watch?v=") !== false)
                {
                    $link = preg_split("!&!", $valor);
                    // * embed es para que aparezca el video de youtube
                    // $ limiters -> ! LO QUE VAYA DENTRO ! 
                    $valor = preg_replace("!watch\?v=!", "embed/", $link[0]);
                    $valor = "<br><iframe width=\'420\' height=\'315\' src=\'" . $valor . "\'></iframe><br>";
                    // Reemplazar el salto de línea después del link de Youtube con un espacio en blanco
                    $arreglo_cuerpo[$key] = preg_replace("/www\.youtube\.com\/watch\?v=.+\n?/", $valor . " ", $valor . "\n");
                }
            }

            //+ Ahora guardamos el valor nuevo del link en cuerpo
            $cuerpo = implode("", $arreglo_cuerpo);

            // - Guardamos en esta variable la fecha y hora actual para despues mostrar cuando se hizo la publicacion
            $fecha_publicado = date("Y-m-d H:i:s");

            // - Guardamos el id del usuario que lo publico
            $publicado_por = $this->objeto_usuario->obtenerIDUsuario();
            // + Si el usuario se encuentra en su perfil, entonces publicado_para sera nulo
            if ($publicado_para == $publicado_por)
            {
                $publicado_para = NULL;
            }

            $id_regresado = 0;
            // + Agregamos la publicacion a la base de datos si publicado_para es nulo
            if ($publicado_para == NULL && $tipo_pagina == "pagina")
            {
                if($id_proyecto == NULL)
                {
                    $query_agrega_publicacion = mysqli_query($this->con, "INSERT INTO publicaciones VALUES('', '$titulo', '$cuerpo', '$publicado_por', NULL, '$nombre_imagenes', '$nombre_archivos', NULL, '$fecha_publicado', 'no', '0', NULL, ',')");
                }
                else
                {
                    $query_agrega_publicacion = mysqli_query($this->con, "INSERT INTO publicaciones VALUES('', '$titulo', '$cuerpo', '$publicado_por', NULL, '$nombre_imagenes', '$nombre_archivos', '$id_proyecto', '$fecha_publicado', 'no', '0', NULL, ',')");
                }
                // $ mysqli_insert_id -> devuelve el ID generado por una consulta en una tabla con una columna que tenga el atributo de AUTO INCREMENT, esto para almacenar en una variable el ID de la publicacion
                $id_regresado = mysqli_insert_id($this->con);
                $hashtags = explode(",", $_POST['hashtags']);
                foreach ($hashtags as $hashtag) {
                    if($hashtag != "")
                    {
                        // + Verificar si el hashtag ya existe en la base de datos
                        $query_verificar_si_hashtag_existe = mysqli_query($this->con, "SELECT id_hashtag, hashtag, publicaciones_con_este_hashtag FROM hashtags WHERE hashtag='$hashtag'");
                        if(mysqli_num_rows($query_verificar_si_hashtag_existe) > 0)
                        {
                            $fila = mysqli_fetch_array($query_verificar_si_hashtag_existe);
                            $id_hashtag = $fila['id_hashtag'];
                            $query_actualizar_hashtag = mysqli_query($this->con, "UPDATE hashtags SET publicaciones_con_este_hashtag=CONCAT(',', '$id_regresado', publicaciones_con_este_hashtag) WHERE id_hashtag='$id_hashtag'");
                            $query_agregar_hashtag_publicacion = mysqli_query($this->con, "UPDATE publicaciones SET hashtags_publicacion=CONCAT(hashtags_publicacion, '$id_hashtag,') WHERE id_publicacion='$id_regresado'");

                        }
                        else
                        {
                            $query_insertar_hashtag = mysqli_query($this->con, "INSERT INTO hashtags VALUES ('', '$hashtag', ',$id_regresado,')");
                            $id_hashtag = mysqli_insert_id($this->con);
                            $query_agregar_hashtag_publicacion = mysqli_query($this->con, "UPDATE publicaciones SET hashtags_publicacion=CONCAT(hashtags_publicacion, '$id_hashtag,') WHERE id_publicacion='$id_regresado'");
                        }
                        // + query insertar interes a la tabla de intereses 
                        $query_verificar_interes = mysqli_query($this->con, "SELECT * FROM temas_interes WHERE id_hashtag_interes='$id_hashtag' AND id_usuario_interesado='$publicado_por'");
                        if(mysqli_num_rows($query_verificar_interes) > 0)
                        {
                            $fila_info_interes = mysqli_fetch_array($query_verificar_interes);
                            $cantidad_interes = $fila_info_interes['cantidad_interes'];
                            if(!($cantidad_interes > 500))
                            {
                                $query_agregar_cantidad_interes = mysqli_query($this->con, "UPDATE temas_interes SET cantidad_interes=cantidad_interes+1 WHERE id_hashtag_interes='$hashtag' AND id_usuario_interesado='$publicado_por'");
                            }                        
                        }
                        else
                        {
                            $query_insertar_interes = mysqli_query($this->con, "INSERT INTO temas_interes VALUES ('', '$publicado_por', '$id_hashtag', '1')");

                        }
                    }

                }
            }
            else if ($publicado_para != NULL && $tipo_pagina == "pagina")
            {
                if($id_proyecto == NULL)
                {
                    $query_agrega_publicacion = mysqli_query($this->con, "INSERT INTO publicaciones VALUES('', '$titulo', '$cuerpo', '$publicado_por', $publicado_para, '$nombre_imagenes', '$nombre_archivos', NULL, '$fecha_publicado', 'no', '0', NULL, ',')");
                }
                else
                {
                    $query_agrega_publicacion = mysqli_query($this->con, "INSERT INTO publicaciones VALUES('', '$titulo', '$cuerpo', '$publicado_por', $publicado_para, '$nombre_imagenes', '$nombre_archivos', '$id_proyecto', '$fecha_publicado', 'no', '0', NULL, ',')");

                }
                $id_regresado = mysqli_insert_id($this->con);
                $notificacion = new Notificacion($this->con, $publicado_por);
                $notificacion->insertarNotificacion($id_regresado, $publicado_para, "publicacion_perfil");
            }
            else
            {
                if($id_proyecto == NULL)
                {
                    $query_agrega_publicacion = mysqli_query($this->con, "INSERT INTO publicaciones VALUES('', '$titulo', '$cuerpo', '$publicado_por', NULL, '$nombre_imagenes', '$nombre_archivos', NULL, '$fecha_publicado', 'no', '0', '$tipo_pagina', ',')");
                }
                else
                {
                    $query_agrega_publicacion = mysqli_query($this->con, "INSERT INTO publicaciones VALUES('', '$titulo', '$cuerpo', '$publicado_por', NULL, '$nombre_imagenes', '$nombre_archivos', '$id_proyecto', '$fecha_publicado', 'no', '0', '$tipo_pagina', ',')"); 
                }
            }

            // + No mandaremos la notificacion si se realizo en un grupo
            if($tipo_pagina == "pagina")
            {
                // + Si el un amigo realizo una publicacion
                $lista_amigos = $this->objeto_usuario->obtenerListaAmigos();
                $lista_amigos_explode = explode(",", $lista_amigos);

                foreach($lista_amigos_explode as $amigo)
                {
                    if($amigo != "")
                    {
                        // + El usuario para el que se publico ya fue notificado
                        if ($amigo != $publicado_para)
                        {
                            $notificacion = new Notificacion($this->con, $publicado_por);
                            $notificacion->insertarNotificacion($id_regresado, $amigo, "amigo_publico");
                        }
                    }
                }

                // + Si el un seguido realizo una publicacion
                $lista_seguidores = $this->objeto_usuario->obtenerListaSeguidores();
                $lista_seguidores_explode = explode(",", $lista_seguidores);

                foreach($lista_seguidores_explode as $seguidor)
                {
                    if($seguidor != "")
                    {
                        // + El usuario para el que se publico ya fue notificado
                        if ($seguidor != $publicado_para)
                        {
                            $notificacion = new Notificacion($this->con, $publicado_por);
                            $notificacion->insertarNotificacion($id_regresado, $seguidor, "seguido_publico");
                        }
                    }
                }   
            }

            // + Aumentar el conteo de publicaciones en el usuario
            $num_publicaciones = $this->objeto_usuario->obenerNumeroPublicaciones();
            // + Aumentamos por uno el numero de publicaciones
            $num_publicaciones++;
            $query_aumentar_publicaciones = mysqli_query($this->con, "UPDATE usuarios SET num_posts='$num_publicaciones' WHERE id_usuario='$publicado_por'");

            if($tipo_pagina == "pagina")
            {
                // + Las stopwords o palabras vacias son los terminos que los buscadores omiten para posicionar los resultados de busqueda.
                // + Ya que este tipo de palabras son muy comunes y se usan en casi todas las frases
                $stop_words = "a aca ahi ajena ajeno ajenas ajenos al algo algun 
                alguna alguno algunas algunos alla alli ambos ante antes aquel 
                aquella aquello aquellas aquellos aqui arriba asi atras aun aunque 
                bajo bastante bien cabe cada casi cierto cierta ciertos ciertas 
                como con conmigo conseguimos conseguir consigo consigue consiguen 
                consigues contigo contra cual cuales cualquier cualquiera 
                cualesquiera cuan cuando cuanto cuanta cuantos cuantas de dejar 
                del demas demasiada demasiado demasiadas demasiados dentro desde 
                donde dos e el ella ello ellas ellos empleais emplean emplear empleas 
                empleo en encima entonces entre era eras eramos eran eres es esa 
                ese eso esas esos esta estas estaba estado estais estamos estan 
                estar este esto estos estoy etc fin fue fueron fui fuimos gueno 
                ha hace haces haceis hacemoshacen hacer hacia hago hasta i incluso 
                intenta intentas intentais intentamos intentan intentar intento ir 
                jamas junto juntos la lo las los largo mas me menos mi mis mia mias 
                mientras mio mios misma mismo mismas mismos modo mucha muchas 
                muchisima muchisimo muchisimas muchisimos mucho muchos muy nada ni 
                ningun ninguna ninguno ningunas ningunos no nos nosotras nosotros 
                nuestra nuestro nuestras nuestros nunca o os otra otro otras otros 
                para parecer pero poca poco pocas pocos podeis podemos poder podria 
                podrias podriais podriamos podrian por porque primero puede pueden 
                puedo pues que querer quien quienes quienesquiera quienquiera quiza 
                quizas sabe sabes saben sabeis sabemos saber se segun ser si siempre 
                siendo sin sino so sobre sois solamente solo somos soy sr sra sres 
                sta su sus suya suyo suyas suyos tal tales tambien tampoco tan tanta 
                tanto tantas tantos te teneis tenemos tener tengo ti tiempo tiene 
                tienen toda todo todas todos tomar trabaja trabajo trabajais trabajamos 
                trabajan trabajar trabajas tras tu tus tuya tuyo tuyos u ultimo un una 
                uno unas unos usa usas usais usamos usan usar uso usted ustedes va van 
                vais valor vamos varias varios vaya verdadera vosotras vosotros voy 
                vuestra vuestro vuestras vuestros y ya yo";

                // + Esta cadena separa tanto espacios como enters
                $stop_words = preg_split("/[\s,]+/", $stop_words);
                // + Reemplazara todo lo que no sea una letra
                // ! FALTA ELIMINAR LOS ACENTOS DE LAS LETRAS
                // $iconv -> Convierte los caracteres a un ofrmato especificado

                // + Trends en el titulo
                $titulo_a_analizar_trend = $titulo;
                $titulo_a_analizar_trend = str_replace("\\r\\n", "", $titulo_a_analizar_trend);
                $titulo_a_analizar_trend = str_replace("\\r", "", $titulo_a_analizar_trend);
                $titulo_a_analizar_trend = str_replace("\\n", "", $titulo_a_analizar_trend);

                $acentos = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú');
                $sin_acentos = array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U');
                $no_acentos_titulo = str_replace($acentos, $sin_acentos, $titulo_a_analizar_trend);

                $no_puntuacion_acentos_titulo = preg_replace("/[^a-zA-Z 0-9]+/", "", $no_acentos_titulo);
                // + Si un usuario ha posteado un link:
                if(strpos($no_puntuacion_acentos_titulo, "height") === false && strpos($no_puntuacion_acentos_titulo, "width") === false && strpos($no_puntuacion_acentos_titulo, "http") === false)
                {
                    $no_puntuacion_acentos_titulo = preg_split("/[\s,]+/", $no_puntuacion_acentos_titulo);

                    // + Removemos los stop_words del arreglo
                    foreach($stop_words as $valor)
                    {
                        foreach($no_puntuacion_acentos_titulo as $key => $valor2)
                        {
                            // + Si encuentra alguna palabra de $stop_words
                            if(strtolower($valor) == strtolower($valor2))
                            {
                                $no_puntuacion_acentos_titulo[$key] = "";
                            }
                        }
                    }

                    // - Este arreglo permitira no tener trends duplicados al realizar una publicacion
                    $terminos_calculados = array(); // Arreglo para almacenar los términos ya calculados

                    // + Esto calcula el trend
                    foreach ($no_puntuacion_acentos_titulo as $valor)
                    {
                        $termino = ucfirst($valor);
                    
                        // Verificar si el término ya fue calculado
                        if (!in_array($termino, $terminos_calculados))
                        {
                            $this->calcularTrend($termino);
                    
                            // Agregar el término al arreglo de términos calculados
                            $terminos_calculados[] = $termino;
                        }
                    }
                    
                }

                // + Trends en el cuerpo
                $cuerpo_a_analizar_trend = $cuerpo_sin_formato;
                $cuerpo_a_analizar_trend = str_replace("\\r\\n", "", $cuerpo_a_analizar_trend);
                $cuerpo_a_analizar_trend = str_replace("\\r", "", $cuerpo_a_analizar_trend);
                $cuerpo_a_analizar_trend = str_replace("\\n", "", $cuerpo_a_analizar_trend);

                $no_acentos_cuerpo = str_replace($acentos, $sin_acentos, $cuerpo_a_analizar_trend);
                $no_puntuacion_acentos_cuerpo = preg_replace("/[^a-zA-Z 0-9]+/", "", $no_acentos_cuerpo);

                // + Si un usuario ha posteado un link:
                if(strpos($no_puntuacion_acentos_cuerpo, "height") === false && strpos($no_puntuacion_acentos_cuerpo, "width") === false && strpos($no_puntuacion_acentos_cuerpo, "http") === false)
                {
                    $no_puntuacion_acentos_cuerpo = preg_split("/[\s,]+/", $no_puntuacion_acentos_cuerpo);

                    // + Removemos los stop_words del arreglo
                    foreach($stop_words as $valor)
                    {
                        foreach($no_puntuacion_acentos_cuerpo as $key => $valor2)
                        {
                            // + Si encuentra alguna palabra de $stop_words
                            if(strtolower($valor) == strtolower($valor2))
                            {
                                $no_puntuacion_acentos_cuerpo[$key] = "";
                            }
                        }
                    }

                    // + Esto calcula el trend
                    foreach ($no_puntuacion_acentos_cuerpo as $valor)
                    {
                        $termino = ucfirst($valor);
                    
                        // Verificar si el término ya fue calculado
                        if (!in_array($termino, $terminos_calculados))
                        {
                            $this->calcularTrend($termino);
                    
                            // Agregar el término al arreglo de términos calculados
                            $terminos_calculados[] = $termino;
                        }
                    }
                }
            }
        }
    }

    public function calcularTrend($termino_a_calcular)
    {
        if($termino_a_calcular != "")
        {
            $query_checar_trend = mysqli_query($this->con, "SELECT * FROM trends WHERE trend='$termino_a_calcular'");

            // + Si no existe el trend, lo insertamos a la tabla
            if(mysqli_num_rows($query_checar_trend) == 0)
            {
                $insertar_trend_query = mysqli_query($this->con, "INSERT INTO trends VALUES('', '$termino_a_calcular', '1')");
            }
            // + Si si encontro el trend
            else
            {
                $insertar_trend_query = mysqli_query($this->con, "UPDATE trends SET hits=hits+1 WHERE trend='$termino_a_calcular'");
            }
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
        $query_info = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE borrado='no' AND id_grupo_publicacion IS NULL ORDER BY id_publicacion DESC");

        // ! este if tambien es del scroll infinito
        // + Si existen publicaciones entonces:
        if(mysqli_num_rows($query_info) > 0)
        {
            // - Cuenta cuántas veces ha dado la vuelta el bucle
            $num_iteraciones = 0;

            // - Cuenta cuantos resultadoados hemos cargado
            $contador = 1;

            $conteo_recomendaciones_usuario = 0;

            #region_verificar_si_el_usuario_tiene_publicaciones
            {
                $lista_amigos_usuario_loggeado = $this->objeto_usuario->obtenerListaAmigos();
                $lista_seguidos_usuario_loggeado = $this->objeto_usuario->obtenerListaSeguidos();
                $lista_id_usuarios = $lista_amigos_usuario_loggeado . $lista_seguidos_usuario_loggeado . "," . $id_usuario_loggeado;

                $lista_id_usuarios_explode = explode(",", $lista_id_usuarios);
                $lista_id_usuarios_explode = array_filter($lista_id_usuarios_explode);

                $lista_id_usuarios_formated = implode(",", $lista_id_usuarios_explode);

                $query_verificar_que_usuario_tenga_publicaciones = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE publicado_por IN ($lista_id_usuarios_formated)");

                if(mysqli_num_rows($query_verificar_que_usuario_tenga_publicaciones) == 0)
                {
                    $usuario_tiene_publicaciones = false;
                    $query_obtener_trend_mas_popular = mysqli_query($this->con, "SELECT trend FROM trends WHERE hits = (SELECT MAX(hits) FROM trends)");
                    if(mysqli_num_rows($query_obtener_trend_mas_popular) > 0)
                    {
                        $fila_trend_mas_popular = mysqli_fetch_array($query_obtener_trend_mas_popular);
                        $trend = $fila_trend_mas_popular['trend'];
                        $limite = 10;
                        $info['trend'] = $trend;
                        $this->cargarPublicacionesTrend($info, $limite);
                    }

                }
                else
                {
                    $usuario_tiene_publicaciones = true;
                }

            }
            #endregion

            if($usuario_tiene_publicaciones)
            {
                // + Mientras existan publicaciones en el arreglo, realizar el loop
                while($fila = mysqli_fetch_array($query_info))
                {
                    $objeto_usuario_loggeado = new Usuario($this->con, $id_usuario_loggeado);

                    // + Guardamos en variables, las variables de la fila de la base de datos
                    $id_publicacion = $fila['id_publicacion'];
                    $titulo = $fila['titulo'];
                    $cuerpo = $fila['cuerpo'];
                    $cuerpo = nl2br($cuerpo);

                    // ! publicado por, me regresara un numero, necesito acceder al usuario de ese publicado por
                    // ! SE PUEDE HACER CON UN INNER JOIN, PERO VOY A SACAR EL NOMBRE DE USUARIO CON SU ID Y METER EL NOMBRE DE USUARIO AL OBJET: objeto_publicado_por
                    $id_publicado_por = $fila['publicado_por'];
                    $query_publicado_por = mysqli_query($this->con, "SELECT username, tipo, mostrar_proyectos from usuarios WHERE id_usuario='$id_publicado_por'");
                    $fila_publicado_por = mysqli_fetch_array($query_publicado_por);
                    // - Asignamos nombre de usuario a publicado por
                    $usuario_publicado_por = $fila_publicado_por['username'];
                    $fecha_publicado = $fila['fecha_publicado'];
                    $tipo_usuario_publicado_por = $fila_publicado_por['tipo'];

                    $visibilidad_proyecto = $fila_publicado_por['mostrar_proyectos'];

                    $direccionImagen= $fila['imagen'];
                    $direccionArchivo = $fila['archivo'];
                    $id_proyecto = $fila['proyecto'];

                    #region publicado_para
                    // + Si no una publicacion en un perfil de alguien, entonces el string de publicado_para estara vacio
                    // ! RECORDAR QUE PUBLICADO PARA ES UN NUMERO DE FOREIGN KEY, REQUIERO SACAR EL NOMBRE DE USUARIO
                    $id_publicado_para = $fila['publicado_para'];
                    $query_publicado_para = mysqli_query($this->con, "SELECT id_usuario, username from usuarios WHERE id_usuario='$id_publicado_para'");
                    $checar_si = mysqli_num_rows($query_publicado_para);

                    if($checar_si == 0)
                    {
                        $usuario_publicado_para = "";
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
                        $usuario_publicado_para = "para <a href='" . $publicado_para_N_usuario . "'>" . $publicado_para_nombre . "</a>";
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

                    $tipo_usuario = $objeto_usuario_loggeado->obtenerTipoUsuario();
                    // + Verifica si el usuario loggeado es amigo del que publico
                    if($objeto_usuario_loggeado->esAmigo($id_publicado_por) || $objeto_usuario_loggeado->esSeguidor($id_publicado_por))
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
                                if (!target.is("")) {
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

                        $checar_si_hay_comentarios = mysqli_query($this->con, "SELECT * FROM comentarios WHERE (eliminado='no' AND publicacion_comentada='$id_publicacion')");
                        $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios);

                        if($numero_comentarios == 1)
                        {
                            $numero_comentarios = $numero_comentarios . " Comentario";
                        }
                        else
                        {
                            $numero_comentarios = $numero_comentarios . " Comentarios";
                        }

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
                                $dias = $intervalo->d. " día atrás";
                            }
                            //Mas de 1 dia
                            else 
                            {
                                $dias = $intervalo->d . " días atrás";
                            }

                            //1 mes
                            if($intervalo-> m == 1)
                            {
                                $mensaje_tiempo = $intervalo->m . " mes " . $dias;
                            }
                            //Mas de 1 mes
                            else
                            {
                                $mensaje_tiempo = $intervalo->m . " meses " . $dias;
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

                        // + Procesar si hay una imagen
                        if($direccionImagen != "")
                        {
                            $lista_imagenes_explode = explode("|", $direccionImagen);
                            $lista_imagenes_explode = array_filter($lista_imagenes_explode);

                            $divImagen = "<div class='contenedorImagenesPublicadas'>";
                            
                            foreach($lista_imagenes_explode as $imagen)
                            {
                                $divImagen .= "<div class='imagenPublicada'>
                                                    <img src='$imagen'>
                                                </div>";                                                
                            }

                            $divImagen .= "</div>";
                        }
                        else
                        {
                            $divImagen = "";
                        }

                        if($direccionArchivo != "")
                        {
                            $lista_archivos_explode = explode("|", $direccionArchivo);
                            $lista_archivos_explode = array_filter($lista_archivos_explode);

                            $divArchivo = "<div class='contenedorArchivosPublicados'>
                                                <h4>Archivos</h4>";

                            foreach($lista_archivos_explode as $direccion_archivo)
                            {
                                $link_archivo = substr($direccion_archivo, strpos($direccion_archivo, "_") + 1);
                                $divArchivo .= "<div class='archivoPublicado'>
                                                    <a href='" . $direccion_archivo . "' download='" . $link_archivo . "'><i class='fa-solid fa-file'></i> " . $link_archivo . "</a>
                                                </div>";
                            }

                            $divArchivo .= "</div>";

                        }
                        else
                        {
                            $divArchivo = "";
                        }

                        $hashtags = $fila['hashtags_publicacion'];
                        $lista_hashtags_explode = explode(",", $hashtags);
                        $lista_hashtags_explode = array_filter($lista_hashtags_explode);

                        if($hashtags != ",")
                        {
                            $div_hashtags = "<div class='contenedor_hashtags'>
                                                <h4>Hashtags</h4>";

                            foreach($lista_hashtags_explode as $hashtag)
                            {
                                $query_info_hashtag = mysqli_query($this->con, "SELECT hashtag FROM hashtags WHERE id_hashtag='$hashtag'");
                                $fila_info_hashtag = mysqli_fetch_array($query_info_hashtag);
                                $nombre_hashtag =  $fila_info_hashtag['hashtag'];
                                $nombre_sin_hashtag = str_replace("#", "", $fila_info_hashtag['hashtag']);

                                $div_hashtags .= "<a href='publication_hashtag.php?hashtag=". $nombre_sin_hashtag . "'>
                                                    <div class='displayHashtag'>
                                                        " . $nombre_hashtag . "
                                                    </div>
                                                </a>";
                            }
                            $div_hashtags .= "</div>";
                        }
                        else
                        {
                            $div_hashtags = "";
                        }

                        // + Mostrar si se publico un proyecto
                        if($id_proyecto != NULL)
                        {
                            $query_obtener_detalles_proyecto = mysqli_query($this->con, "SELECT * FROM proyectos WHERE id_proyecto='$id_proyecto'");
                            $fila_detalles_proyecto = mysqli_fetch_array($query_obtener_detalles_proyecto);
                            $nombre_proyecto = $fila_detalles_proyecto['nombre_proyecto'];
                            $link_proyecto = $fila_detalles_proyecto['link_proyecto'];
                            if($visibilidad_proyecto)
                            {
                                $mostrar_proyecto = true;
                            }
                            else
                            {
                                if($objeto_usuario_loggeado->esAmigo($id_publicado_por) && $objeto_usuario_loggeado->obtenerIDUsuario() == $id_publicado_por)
                                {
                                    $mostrar_proyecto = true;
                                }
                                else
                                {
                                    $mostrar_proyecto = false;
                                }
                            }
                        }
                        else
                        {
                            $mostrar_proyecto = false;
                        }

                        if($mostrar_proyecto)
                        {
                            if($id_usuario_loggeado == $id_publicado_por)
                            {
                                $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                                    <h4>Proyecto incluido</h4>
                                                    <div class='mostrarProyecto'>
                                                        <div class='nombre_proyecto_container'>
                                                            <p> " . $nombre_proyecto . "</p>
                                                        </div>
                                                        <div class='imagen_fondo_mostrar_proyecto'>
                                                            <img src='assets\images\icons\blockimino.png'>
                                                        </div>
                                                        <div class='contenedor_boton_ver_proyecto'>
                                                            <button class='boton_ver_proyecto btn btn-success' id='editar" . $nombre_proyecto . "'>Editar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    $(document).ready(function(){
                                                        $('#editar$nombre_proyecto').on('click', function() {
                                                            window.open('block_arena.php?project=$nombre_proyecto');
                                                            
                                                        });
                                                    });
                                                </script>";

                            }
                            else
                            {
                                $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                                    <h4>Proyecto incluido</h4>
                                                    <div class='mostrarProyecto'>
                                                        <div class='nombre_proyecto_container'>
                                                            <p> " . $nombre_proyecto . "</p>
                                                        </div>
                                                        <div class='imagen_fondo_mostrar_proyecto'>
                                                            <img src='assets\images\icons\blockimino.png'>
                                                        </div>
                                                        <div class='contenedor_boton_copiar_proyecto'>
                                                            <button class='boton_copiar_proyecto btn btn-info' id='copiar_proyecto" . $nombre_proyecto . "'>Copiar proyecto</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                $(document).ready(function(){
                                                    $('#copiar_proyecto$nombre_proyecto').on('click', function() {
                                                        bootbox.prompt('Introduce un nombre para el proyecto', function(result) {
                                                            if(result != null)
                                                            {
                                                                $.ajax({
                                                                    url: 'includes/handlers/ajax_copy_project.php?id_usuario=$id_usuario_loggeado&link_proyecto=$link_proyecto',
                                                                    type: 'POST',
                                                                    data: {resultado:result},
                                                                    success: function(data) {
                                                                        alert(data);
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    });
                                                });
                                            </script>";
                            }                                        
                        }
                        else
                        {
                            $divProyecto = "";
                        }
                        
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
                                    <a href='$usuario_publicado_por'> $nombre $apeP $apeM </a> $usuario_publicado_para &nbsp;&nbsp;&nbsp;&nbsp;$mensaje_tiempo
                                    $boton_eliminar
                                </div>
                                <div id='titulo_publicacion' style='font-style: bold;'>
                                    $titulo
                                    <hr>
                                </div>
                                <div id='cuerpo_publicacion'>
                                    $cuerpo
                                    <br>
                                    $divImagen
                                    <br>
                                    <br>
                                </div>
                                $div_hashtags

                                $divProyecto

                                $divArchivo
                                <div class='OpcionesDePublicacion'>
                                    &nbsp;
                                    <span class='mostrar_ocultar_comentarios' onClick='javascript:toggle$id_publicacion()'>
                                        <i class='fa-solid fa-comment'></i>&nbsp;$numero_comentarios
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
                        // + Script de borrar publicacion
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
                                        if(result == true)
                                        {
                                            // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado 
                                            $.post("includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", {resultado:result});
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
                                            $.post("includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", { resultado:result, razon:result});
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
                    
                    // + Recomendar en funcion de los temas a los que el usuario ha mostrado interes
                    // + Si se realizaron 10 iteraciones recomentar una publicacion en relacion a los temas del usuario interesado
                    
                    if($num_iteraciones % 10 == 0 && $num_iteraciones != 0)
                    {
                        $publicacion_puede_ser_recomendada = false;
                        $publicacion_probable_puede_ser_recomendada = false;
                        $publicacion_amigo_puede_ser_recomendada = false;
                        $publicacion_seguido_puede_ser_recomendada = false;

                        #region recomendar intereses usuario

                        $query_seleccionar_intereses_usuario = mysqli_query($this->con, "SELECT * FROM temas_interes WHERE id_usuario_interesado='$id_usuario_loggeado'");

                        // + Comprobar si el usuario tiene intereses
                        if(mysqli_num_rows($query_seleccionar_intereses_usuario) > 0)
                        {
                            $cantidad_intereses = mysqli_num_rows($query_seleccionar_intereses_usuario);
                            $publicacion_puede_ser_recomendada = false;
        
                            // + Query para seleccionar el ultimo valor de la tabla de temas de interes del usuario
                            $query_ultimo_interes = mysqli_query($this->con, "SELECT *
                                                                                FROM temas_interes
                                                                                WHERE id_usuario_interesado ='$id_usuario_loggeado'
                                                                                AND cantidad_interes = (SELECT MIN(cantidad_interes) FROM temas_interes WHERE id_usuario_interesado='$id_usuario_loggeado')
                                                                                ORDER BY id_interes ASC
                                                                                LIMIT 1");
        
                            $fila_ultimo_interes = mysqli_fetch_array($query_ultimo_interes);
                            $id_hashtag_utlimo_interes = $fila_ultimo_interes['id_hashtag_interes'];
        
                            $query_obtener_hashtag_ultimo_interes = mysqli_query($this->con, "SELECT * FROM hashtags WHERE id_hashtag = '$id_hashtag_utlimo_interes'");
        
                            $fila_hashtag_ultimo_interes = mysqli_fetch_array($query_obtener_hashtag_ultimo_interes);
                            $publicaciones_ultimo_interes_con_este_hashtag = $fila_hashtag_ultimo_interes['publicaciones_con_este_hashtag'];
        
                            $lista_publicaciones_ultimo_interes_explode = explode(",", $publicaciones_ultimo_interes_con_este_hashtag);
                            $lista_publicaciones_ultimo_interes_explode = array_filter($lista_publicaciones_ultimo_interes_explode);
                            $id_publicacion_ultimo_interes = end($lista_publicaciones_ultimo_interes_explode);
                            
                            $limite_intereses = $pagina;
        
                            do
                            {
                                $publicacion_puede_ser_recomendada = false;
                                $publicacion_probable_puede_ser_recomendada = false;
        
                                $contador_repeticiones = 1;
                                if($limite_intereses > $cantidad_intereses)
                                {
                                    while($limite_intereses > $cantidad_intereses)
                                    {
                                        $limite_intereses = $limite_intereses - $cantidad_intereses;
                                        $contador_repeticiones ++;
                                    }
                                }
                                // + Seccion para obtener publicaciones recomendadas:
                                // + Esta query obtiene el interes cada vez que se carga la funcion y obtiene del mayor al menor segun la pagina
                                $query_obtener_interes = mysqli_query($this->con, "SELECT *
                                                                                        FROM (
                                                                                            SELECT *,
                                                                                                @row_num := @row_num + 1 AS row_num
                                                                                            FROM temas_interes
                                                                                            CROSS JOIN (SELECT @row_num := 0) AS vars
                                                                                            WHERE id_usuario_interesado = '$id_usuario_loggeado'
                                                                                            ORDER BY cantidad_interes DESC, id_interes DESC
                                                                                        ) AS subquery
                                                                                        WHERE row_num = '$limite_intereses'");
                                                                            
                                $fila_interes = mysqli_fetch_array($query_obtener_interes);
                                $id_hashtag_interes = $fila_interes['id_hashtag_interes'];
            
                                $query_obtener_hashtag_interes = mysqli_query($this->con, "SELECT * FROM hashtags WHERE id_hashtag = '$id_hashtag_interes'");
                                $fila_hashtag_interes = mysqli_fetch_array($query_obtener_hashtag_interes);
                                $publicaciones_con_este_hashtag = $fila_hashtag_interes['publicaciones_con_este_hashtag'];
                                $nombre_hashtag_interes = $fila_hashtag_interes['hashtag'];
                                $nombre_sin_hashtag_interes = substr($nombre_hashtag_interes, 1);
            
                                $lista_publicaciones_explode = explode(",", $publicaciones_con_este_hashtag);
                                $lista_publicaciones_explode = array_filter($lista_publicaciones_explode);
            
                                $actual = 0;
        
                                $id_publicacion_recomendada = $lista_publicaciones_explode[1];
        
                                for ($i = 0; $i < count($lista_publicaciones_explode); $i++) {
                                    $query_verificar_publicacion_recomendada = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE id_publicacion='$id_publicacion_recomendada' AND publicado_por='$id_usuario_loggeado'");
                                    if(mysqli_num_rows($query_verificar_publicacion_recomendada) > 0)
                                    {
                                        if(isset($lista_publicaciones_explode[$i]))
                                        {
                                            $id_publicacion_recomendada = $lista_publicaciones_explode[$i];
                                        }
                                    }
                                    else
                                    {
                                        $actual++;
                                        if($actual == $contador_repeticiones)
                                        {
                                            $publicacion_puede_ser_recomendada = true;
                                            break;
                                        }
                                    }
                                    $publicacion_puede_ser_recomendada = false;
                                }
        
        
                                // + Recomendarle algo parecido
                                // + Seccion para obtener publicaciones recomendadas:
                                // + Esta query obtiene el interes cada vez que se carga la funcion y obtiene del mayor al menor segun la pagina
        
                                $query_obtener_hashtag_que_puede_interesar = mysqli_query($this->con, "SELECT * FROM hashtags WHERE hashtag LIKE '%$nombre_hashtag_interes%' AND hashtag <> '#$nombre_hashtag_interes'");
                                if(mysqli_num_rows($query_obtener_hashtag_que_puede_interesar) > 0)
                                {
                                    while($fila_hashtag_que_puede_interesar = mysqli_fetch_array($query_obtener_hashtag_que_puede_interesar))
                                    {
                                        $nombre_hashtag_que_puede_interesar = $fila_hashtag_que_puede_interesar['hashtag'];
                                        
                                        $publicaciones_probables_con_este_hashtag = $fila_hashtag_que_puede_interesar['publicaciones_con_este_hashtag'];
                                        $lista_publicaciones_probables_explode = explode(",", $publicaciones_probables_con_este_hashtag);
                                        $lista_publicaciones_probables_explode = array_filter($lista_publicaciones_probables_explode);
        
                                        $id_publicacion_probable_recomendada = $lista_publicaciones_probables_explode[1];
        
                                        $actual = 0;
        
                                        for ($i = 0; $i < count($lista_publicaciones_probables_explode); $i++) {
                                            $query_verificar_publicacion_probable_recomendada = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE id_publicacion='$id_publicacion_probable_recomendada' AND publicado_por='$id_usuario_loggeado'");
                                            if(mysqli_num_rows($query_verificar_publicacion_probable_recomendada) > 0)
                                            {
                                                if(isset($lista_publicaciones_probables_explode[$i]))
                                                {
                                                    $id_publicacion_probable_recomendada = $lista_publicaciones_probables_explode[$i];
                                                }
                                            }
                                            else
                                            {
                                                $actual++;
                                                if($actual == $contador_repeticiones)
                                                {
                                                    $publicacion_probable_puede_ser_recomendada = true;
                                                    break;
                                                }
                                            }
                                            $publicacion_probable_puede_ser_recomendada = false;
                                        }
                                    }
                                }
        
                                if($publicacion_puede_ser_recomendada || $publicacion_probable_puede_ser_recomendada)
                                {
                                    break;
                                }
                                else
                                {
                                    $limite_intereses++;
                                }
        
                            } while($id_publicacion_ultimo_interes != $id_publicacion_recomendada && $cantidad_intereses != $contador);
                        }
                        #endregion

                        #region recomendar intereses amigos
                        // + Recomendar publicaciones de amigos
                        $query_obtener_amigos_seguidos_usuario = mysqli_query($this->con, "SELECT * FROM usuarios WHERE id_usuario='$id_usuario_loggeado'");
                        $fila_amigos_seguidos_usuario = mysqli_fetch_array($query_obtener_amigos_seguidos_usuario);

                        $lista_amigos_usuario = $fila_amigos_seguidos_usuario['lista_amigos'];

                        if($lista_amigos_usuario != ",")
                        {
                            $lista_amigos_explode = explode(",", $lista_amigos_usuario);
                            $lista_amigos_explode = array_filter($lista_amigos_explode);
        
                            // + Quitamos la primera y ultima coma para poder hacer la query
                            $lista_amigos_formatted = implode(",", $lista_amigos_explode);
        
        
                            $query_obtener_cantidad_intereses_amigos = mysqli_query($this->con, "SELECT id_hashtag_interes, SUM(cantidad_interes) AS suma_interes
                                                                                                FROM temas_interes
                                                                                                WHERE id_usuario_interesado IN ($lista_amigos_formatted) AND id_hashtag_interes NOT IN (SELECT id_hashtag_interes FROM temas_interes WHERE id_usuario_interesado = $id_usuario_loggeado)
                                                                                                GROUP BY id_hashtag_interes");
        
                            // + Comprobar si los amigos tienen intereses
                            if(mysqli_num_rows($query_obtener_cantidad_intereses_amigos) > 0)
                            {
                                // + Query para seleccionar el ultimo valor de la tabla de temas de interes del usuario
                                $query_ultimo_interes_amigo = mysqli_query($this->con, "SELECT id_hashtag_interes, SUM(cantidad_interes) AS suma_interes
                                                                                        FROM temas_interes
                                                                                        WHERE id_usuario_interesado IN ($lista_amigos_formatted) AND id_hashtag_interes NOT IN (SELECT id_hashtag_interes FROM temas_interes WHERE id_usuario_interesado = $id_usuario_loggeado)
                                                                                        GROUP BY id_hashtag_interes
                                                                                        ORDER BY suma_interes ASC, id_hashtag_interes ASC
                                                                                        LIMIT 1");
            
                                $fila_ultimo_interes_amigo = mysqli_fetch_array($query_ultimo_interes_amigo);
                                $id_hashtag_utlimo_interes_amigo = $fila_ultimo_interes_amigo['id_hashtag_interes'];
            
                                $query_obtener_hashtag_ultimo_interes_amigo = mysqli_query($this->con, "SELECT * FROM hashtags WHERE id_hashtag = '$id_hashtag_utlimo_interes_amigo'");
            
                                $fila_hashtag_ultimo_interes_amigo = mysqli_fetch_array($query_obtener_hashtag_ultimo_interes_amigo);
                                $publicaciones_ultimo_interes_con_este_hashtag_amigo = $fila_hashtag_ultimo_interes_amigo['publicaciones_con_este_hashtag'];
            
                                $lista_publicaciones_ultimo_interes_amigo_explode = explode(",", $publicaciones_ultimo_interes_con_este_hashtag_amigo);
                                $lista_publicaciones_ultimo_interes_amigo_explode = array_filter($lista_publicaciones_ultimo_interes_amigo_explode);
            
                                $id_publicacion_ultimo_interes_amigo = end($lista_publicaciones_ultimo_interes_amigo_explode);
            
                                $cantidad_intereses_amigos = mysqli_num_rows($query_obtener_cantidad_intereses_amigos);
                                $limite_intereses_amigos = $pagina;
            
                                do
                                {
                                    $publicacion_amigo_puede_ser_recomendada = false;
            
                                    $contador_repeticiones_amigo = 1;
                                    if($limite_intereses_amigos > $cantidad_intereses_amigos)
                                    {
                                        while($limite_intereses_amigos > $cantidad_intereses_amigos)
                                        {
                                            $limite_intereses_amigos = $limite_intereses_amigos - $cantidad_intereses_amigos;
                                            $contador_repeticiones_amigo ++;
                                        }
                                    }
                                    // + Seccion para obtener publicaciones recomendadas:
                                    // + Esta query obtiene el interes cada vez que se carga la funcion y obtiene del mayor al menor segun la pagina
                                    $query_seleccionar_intereses_amigo = mysqli_query($this->con, "SELECT *
                                                                                                    FROM (
                                                                                                        SELECT id_hashtag_interes, SUM(cantidad_interes) AS suma_interes,
                                                                                                            @row_num := @row_num + 1 AS row_num
                                                                                                        FROM temas_interes
                                                                                                        CROSS JOIN (SELECT @row_num := 0) AS vars
                                                                                                        WHERE id_usuario_interesado IN ($lista_amigos_formatted) AND id_hashtag_interes NOT IN (SELECT id_hashtag_interes FROM temas_interes WHERE id_usuario_interesado = $id_usuario_loggeado)
                                                                                                        GROUP BY id_hashtag_interes
                                                                                                        ORDER BY SUM(cantidad_interes) DESC
                                                                                                    ) AS subquery
                                                                                                    WHERE row_num = $limite_intereses_amigos");
                                                                                
                                    $fila_interes_amigo = mysqli_fetch_array($query_seleccionar_intereses_amigo);
                                    $id_hashtag_interes_amigo = $fila_interes_amigo['id_hashtag_interes'];
                
                                    $query_obtener_hashtag_interes_amigo = mysqli_query($this->con, "SELECT * FROM hashtags WHERE id_hashtag = '$id_hashtag_interes_amigo'");
                                    $fila_hashtag_interes_amigo = mysqli_fetch_array($query_obtener_hashtag_interes_amigo);
                                    $publicaciones_amigo_con_este_hashtag = $fila_hashtag_interes_amigo['publicaciones_con_este_hashtag'];
                                    $nombre_hashtag_interes_amigo = $fila_hashtag_interes_amigo['hashtag'];
                                    $nombre_sin_hashtag_interes_amigo = substr($nombre_hashtag_interes_amigo, 1);
                
                                    $lista_publicaciones_amigo_explode = explode(",", $publicaciones_amigo_con_este_hashtag);
                                    $lista_publicaciones_amigo_explode = array_filter($lista_publicaciones_amigo_explode);
                
                                    $actual_amigo = 0;
            
                                    $id_publicacion_amigo_recomendada = $lista_publicaciones_amigo_explode[1];
            
                                    for ($i = 0; $i < count($lista_publicaciones_amigo_explode); $i++) {
                                        $query_verificar_publicacion_amigo_recomendada = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE id_publicacion='$id_publicacion_amigo_recomendada' AND publicado_por='$id_usuario_loggeado'");
                                        if(mysqli_num_rows($query_verificar_publicacion_amigo_recomendada) > 0)
                                        {
                                            if(isset($lista_publicaciones_amigo_explode[$i]))
                                            {
                                                $id_publicacion_amigo_recomendada = $lista_publicaciones_amigo_explode[$i];
                                            }
                                        }
                                        else
                                        {
                                            $actual_amigo++;
                                            if($actual_amigo == $contador_repeticiones_amigo)
                                            {
                                                $publicacion_amigo_puede_ser_recomendada = true;
                                                break;
                                            }
                                        }
                                        $publicacion_amigo_puede_ser_recomendada = false;
                                    }
            
                                    if($publicacion_amigo_puede_ser_recomendada)
                                    {
                                        break;
                                    }
                                    else
                                    {
                                        $limite_intereses_amigos++;
                                    }
            
                                } while($id_publicacion_ultimo_interes_amigo != $id_publicacion_amigo_recomendada && $cantidad_intereses_amigos != $contador_repeticiones_amigo);

                            }
                        }

                    
                        #endregion


                        #region recomendar intereses seguidos
                        // + Recomendar publicaciones de seguidos si es que el usuario sigue a personas
                        $lista_seguidos_usuario = $fila_amigos_seguidos_usuario['lista_seguidos'];

                        if($lista_seguidos_usuario != ",")
                        {
                            $lista_seguidos_explode = explode(",", $lista_seguidos_usuario);
                            $lista_seguidos_explode = array_filter($lista_seguidos_explode);
        
                            // + Quitamos la primera y ultima coma para poder hacer la query
                            $lista_seguidos_formatted = implode(",", $lista_seguidos_explode);
        
        
                            $query_obtener_cantidad_intereses_seguidos = mysqli_query($this->con, "SELECT id_hashtag_interes, SUM(cantidad_interes) AS suma_interes
                                                                                                FROM temas_interes
                                                                                                WHERE id_usuario_interesado IN ($lista_seguidos_formatted) AND id_hashtag_interes NOT IN (SELECT id_hashtag_interes FROM temas_interes WHERE id_usuario_interesado = $id_usuario_loggeado)
                                                                                                GROUP BY id_hashtag_interes");
                                                                                            
                            // + Comprobar si los usuarios seguidos tienen intereses
                            if(mysqli_num_rows($query_obtener_cantidad_intereses_seguidos) > 0)
                            {
                                // + Query para seleccionar el ultimo valor de la tabla de temas de interes del usuario
                                $query_ultimo_interes_seguido = mysqli_query($this->con, "SELECT id_hashtag_interes, SUM(cantidad_interes) AS suma_interes
                                                                                        FROM temas_interes
                                                                                        WHERE id_usuario_interesado IN ($lista_seguidos_formatted) AND id_hashtag_interes NOT IN (SELECT id_hashtag_interes FROM temas_interes WHERE id_usuario_interesado = $id_usuario_loggeado)
                                                                                        GROUP BY id_hashtag_interes
                                                                                        ORDER BY suma_interes ASC, id_hashtag_interes ASC
                                                                                        LIMIT 1");
        
                                $fila_ultimo_interes_seguido = mysqli_fetch_array($query_ultimo_interes_seguido);
                                $id_hashtag_utlimo_interes_seguido = $fila_ultimo_interes_seguido['id_hashtag_interes'];
        
                                $query_obtener_hashtag_ultimo_interes_seguido = mysqli_query($this->con, "SELECT * FROM hashtags WHERE id_hashtag = '$id_hashtag_utlimo_interes_seguido'");
        
                                $fila_hashtag_ultimo_interes_seguido = mysqli_fetch_array($query_obtener_hashtag_ultimo_interes_seguido);
                                $publicaciones_ultimo_interes_con_este_hashtag_seguido = $fila_hashtag_ultimo_interes_seguido['publicaciones_con_este_hashtag'];
        
                                $lista_publicaciones_ultimo_interes_seguido_explode = explode(",", $publicaciones_ultimo_interes_con_este_hashtag_seguido);
                                $lista_publicaciones_ultimo_interes_seguido_explode = array_filter($lista_publicaciones_ultimo_interes_seguido_explode);
        
                                $id_publicacion_ultimo_interes_seguido = end($lista_publicaciones_ultimo_interes_seguido_explode);
        
                                $cantidad_intereses_seguidos = mysqli_num_rows($query_obtener_cantidad_intereses_seguidos);
                                $limite_intereses_seguidos = $pagina;
        
                                do
                                {
                                    $publicacion_seguido_puede_ser_recomendada = false;
        
                                    $contador_repeticiones_seguido = 1;
                                    if($limite_intereses_seguidos > $cantidad_intereses_seguidos)
                                    {
                                        while($limite_intereses_seguidos > $cantidad_intereses_seguidos)
                                        {
                                            $limite_intereses_seguidos = $limite_intereses_seguidos - $cantidad_intereses_seguidos;
                                            $contador_repeticiones_seguido ++;
                                        }
                                    }
                                    // + Seccion para obtener publicaciones recomendadas:
                                    // + Esta query obtiene el interes cada vez que se carga la funcion y obtiene del mayor al menor segun la pagina
                                    $query_seleccionar_intereses_seguido = mysqli_query($this->con, "SELECT *
                                                                                                    FROM (
                                                                                                        SELECT id_hashtag_interes, SUM(cantidad_interes) AS suma_interes,
                                                                                                            @row_num := @row_num + 1 AS row_num
                                                                                                        FROM temas_interes
                                                                                                        CROSS JOIN (SELECT @row_num := 0) AS vars
                                                                                                        WHERE id_usuario_interesado IN ($lista_seguidos_formatted) AND id_hashtag_interes NOT IN (SELECT id_hashtag_interes FROM temas_interes WHERE id_usuario_interesado = $id_usuario_loggeado)
                                                                                                        GROUP BY id_hashtag_interes
                                                                                                        ORDER BY SUM(cantidad_interes) DESC
                                                                                                    ) AS subquery
                                                                                                    WHERE row_num = $limite_intereses_seguidos");
                                                                                
                                    $fila_interes_seguido = mysqli_fetch_array($query_seleccionar_intereses_seguido);
                                    $id_hashtag_interes_seguido = $fila_interes_seguido['id_hashtag_interes'];
                
                                    $query_obtener_hashtag_interes_seguido = mysqli_query($this->con, "SELECT * FROM hashtags WHERE id_hashtag = '$id_hashtag_interes_seguido'");
                                    $fila_hashtag_interes_seguido = mysqli_fetch_array($query_obtener_hashtag_interes_seguido);
                                    $publicaciones_seguido_con_este_hashtag = $fila_hashtag_interes_seguido['publicaciones_con_este_hashtag'];
                                    $nombre_hashtag_interes_seguido = $fila_hashtag_interes_seguido['hashtag'];
                                    $nombre_sin_hashtag_interes_seguido = substr($nombre_hashtag_interes_seguido, 1);
                
                                    $lista_publicaciones_seguido_explode = explode(",", $publicaciones_seguido_con_este_hashtag);
                                    $lista_publicaciones_seguido_explode = array_filter($lista_publicaciones_seguido_explode);
                
                                    $actual_seguido = 0;
        
                                    $id_publicacion_seguido_recomendada = $lista_publicaciones_seguido_explode[1];
        
                                    for ($i = 0; $i < count($lista_publicaciones_seguido_explode); $i++) {
                                        $query_verificar_publicacion_seguido_recomendada = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE id_publicacion='$id_publicacion_seguido_recomendada' AND publicado_por='$id_usuario_loggeado'");
                                        if(mysqli_num_rows($query_verificar_publicacion_seguido_recomendada) > 0)
                                        {
                                            if(isset($lista_publicaciones_seguido_explode[$i]))
                                            {
                                                $id_publicacion_seguido_recomendada = $lista_publicaciones_seguido_explode[$i];
                                            }
                                        }
                                        else
                                        {
                                            $actual_seguido++;
                                            if($actual_seguido == $contador_repeticiones_seguido)
                                            {
                                                $publicacion_seguido_puede_ser_recomendada = true;
                                                break;
                                            }
                                        }
                                        $publicacion_seguido_puede_ser_recomendada = false;
                                    }
        
                                    if($publicacion_seguido_puede_ser_recomendada)
                                    {
                                        break;
                                    }
                                    else
                                    {
                                        $limite_intereses_seguidos++;
                                    }
        
                                } while($id_publicacion_ultimo_interes_seguido != $id_publicacion_seguido_recomendada && $cantidad_intereses_seguidos != $contador_repeticiones_seguido);
                            }
                        }



                        #endregion

                        // + Se recomienda una publicacion con el hashtag encontrado
                        if($publicacion_puede_ser_recomendada)
                        {
                            $query_obtener_publicacion_recomendada = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE borrado='no' AND id_grupo_publicacion IS NULL AND id_publicacion='$id_publicacion_recomendada'");

                            // + Si existen publicaciones entonces:
                            if(mysqli_num_rows($query_obtener_publicacion_recomendada) > 0)
                            {
                                $fila_publicacion_recomendada = mysqli_fetch_array($query_obtener_publicacion_recomendada);
                    
                                $objeto_usuario_loggeado = new Usuario($this->con, $id_usuario_loggeado);
                    
                                // + Guardamos en variables, las variables de la fila de la base de datos
                                $id_publicacion = $id_publicacion_recomendada;
                                $titulo = $fila_publicacion_recomendada['titulo'];
                                $cuerpo = $fila_publicacion_recomendada['cuerpo'];
                                $cuerpo = nl2br($cuerpo);
                    
                                $id_publicado_por = $fila_publicacion_recomendada['publicado_por'];
                                $query_recomendacion_publicado_por = mysqli_query($this->con, "SELECT username, tipo, mostrar_proyectos from usuarios WHERE id_usuario='$id_publicado_por'");
        
                                $fila_recomendacion_publicado_por = mysqli_fetch_array($query_recomendacion_publicado_por);
                                // - Asignamos nombre de usuario a publicado por
                                $usuario_publicado_por = $fila_recomendacion_publicado_por['username'];
        
                                $fecha_publicado = $fila_publicacion_recomendada['fecha_publicado'];
        
                                $tipo_usuario_publicado_por = $fila_recomendacion_publicado_por['tipo'];
                    
                                $visibilidad_proyecto = $fila_recomendacion_publicado_por['mostrar_proyectos'];
                    
                                $direccionImagen= $fila_publicacion_recomendada['imagen'];
                                $direccionArchivo = $fila_publicacion_recomendada['archivo'];
                                $id_proyecto = $fila_publicacion_recomendada['proyecto'];
                
                                $usuario_publicado_para = "";
        
                                #region verificar si la cuenta del usuario esta cerrada
                                // + Creamos un nuevo usuario para el usuario que realizo la publicacion
                                $objeto_publicado_por = new Usuario($this->con, $id_publicado_por);
                                if($objeto_publicado_por->estaCerrado())
                                {
                                    // $ continue -> Detiene la iteracion actual y vuelve al principio del bucle para realizar otra iteracion
                                    continue;
                                }
                                #endregion
                    
                                #region determinar tipo de usuario y boton de borrar
                                $tipo_usuario = $objeto_usuario_loggeado->obtenerTipoUsuario();
                    
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
        
                                #endregion
                    
                                #region publicado_por
                                // + Query para seleccionar el nombre del usuario que publico y su foto de perfil
                                $query_detalles_usuario_recomendado = mysqli_query($this->con, "SELECT nombre, apeP, apeM, foto_perfil FROM usuarios WHERE id_usuario='$id_publicado_por'");
                                // + Guardamos las variables en filas 
                                $fila_usuario_recomendado = mysqli_fetch_array($query_detalles_usuario_recomendado);
                                // + Guardamos en variables, las variables de la fila de la base de datos
                                $nombre = $fila_usuario_recomendado['nombre'];
                                $apeP = $fila_usuario_recomendado['apeP'];
                                $apeM = $fila_usuario_recomendado['apeM'];
                                $foto_perfil = $fila_usuario_recomendado['foto_perfil'];
                                #endregion
                    
                                #region script y logica de comentarios 
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
                                        if (!target.is("")) {
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
                    
                                $checar_si_hay_comentarios = mysqli_query($this->con, "SELECT * FROM comentarios WHERE (eliminado='no' AND publicacion_comentada='$id_publicacion')");
                                $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios);
            
                                if($numero_comentarios == 1)
                                {
                                    $numero_comentarios = $numero_comentarios . " Comentario";
                                }
                                else
                                {
                                    $numero_comentarios = $numero_comentarios . " Comentarios";
                                }
        
                                #endregion
            
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
                                        $dias = $intervalo->d. " día atrás";
                                    }
                                    //Mas de 1 dia
                                    else 
                                    {
                                        $dias = $intervalo->d . " días atrás";
                                    }
            
                                    //1 mes
                                    if($intervalo-> m == 1)
                                    {
                                        $mensaje_tiempo = $intervalo->m . " mes " . $dias;
                                    }
                                    //Mas de 1 mes
                                    else
                                    {
                                        $mensaje_tiempo = $intervalo->m . " meses " . $dias;
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
            
                                #region procesar archivos, hashtags, hashtags y proyectos
                                // + Procesar si hay una imagen
                                if($direccionImagen != "")
                                {
                                    $lista_imagenes_explode = explode("|", $direccionImagen);
                                    $lista_imagenes_explode = array_filter($lista_imagenes_explode);
            
                                    $divImagen = "<div class='contenedorImagenesPublicadas'>";
                                    
                                    foreach($lista_imagenes_explode as $imagen)
                                    {
                                        $divImagen .= "<div class='imagenPublicada'>
                                                            <img src='$imagen'>
                                                        </div>";                                                
                                    }
            
                                    $divImagen .= "</div>";
                                }
                                else
                                {
                                    $divImagen = "";
                                }
            
                                if($direccionArchivo != "")
                                {
                                    $lista_archivos_explode = explode("|", $direccionArchivo);
                                    $lista_archivos_explode = array_filter($lista_archivos_explode);
            
                                    $divArchivo = "<div class='contenedorArchivosPublicados'>
                                                        <h4>Archivos</h4>";
            
                                    foreach($lista_archivos_explode as $direccion_archivo)
                                    {
                                        $link_archivo = substr($direccion_archivo, strpos($direccion_archivo, "_") + 1);
                                        $divArchivo .= "<div class='archivoPublicado'>
                                                            <a href='" . $direccion_archivo . "' download='" . $link_archivo . "'><i class='fa-solid fa-file'></i> " . $link_archivo . "</a>
                                                        </div>";
                                    }
            
                                    $divArchivo .= "</div>";
            
                                }
                                else
                                {
                                    $divArchivo = "";
                                }
            
                                $hashtags = $fila_publicacion_recomendada['hashtags_publicacion'];
                                $lista_hashtags_explode = explode(",", $hashtags);
                                $lista_hashtags_explode = array_filter($lista_hashtags_explode);
            
                                if($hashtags != ",")
                                {
                                    $div_hashtags = "<div class='contenedor_hashtags'>
                                                        <h4>Hashtags</h4>";
            
                                    foreach($lista_hashtags_explode as $hashtag)
                                    {
                                        $query_info_hashtag = mysqli_query($this->con, "SELECT hashtag FROM hashtags WHERE id_hashtag='$hashtag'");
                                        $fila_info_hashtag = mysqli_fetch_array($query_info_hashtag);
                                        $nombre_hashtag =  $fila_info_hashtag['hashtag'];
                                        $nombre_sin_hashtag = str_replace("#", "", $fila_info_hashtag['hashtag']);
            
                                        $div_hashtags .= "<a href='publication_hashtag.php?hashtag=". $nombre_sin_hashtag . "'>
                                                            <div class='displayHashtag'>
                                                                " . $nombre_hashtag . "
                                                            </div>
                                                        </a>";
                                    }
                                    $div_hashtags .= "</div>";
                                }
                                else
                                {
                                    $div_hashtags = "";
                                }
            
                                // + Mostrar si se publico un proyecto
                                if($id_proyecto != NULL)
                                {
                                    $query_obtener_detalles_proyecto = mysqli_query($this->con, "SELECT * FROM proyectos WHERE id_proyecto='$id_proyecto'");
                                    $fila_detalles_proyecto = mysqli_fetch_array($query_obtener_detalles_proyecto);
                                    $nombre_proyecto = $fila_detalles_proyecto['nombre_proyecto'];
                                    $link_proyecto = $fila_detalles_proyecto['link_proyecto'];
                                    if($visibilidad_proyecto)
                                    {
                                        $mostrar_proyecto = true;
                                    }
                                    else
                                    {
                                        if($objeto_usuario_loggeado->esAmigo($id_publicado_por) && $objeto_usuario_loggeado->obtenerIDUsuario() == $id_publicado_por)
                                        {
                                            $mostrar_proyecto = true;
                                        }
                                        else
                                        {
                                            $mostrar_proyecto = false;
                                        }
                                    }
                                }
                                else
                                {
                                    $mostrar_proyecto = false;
                                }
            
                                if($mostrar_proyecto)
                                {
                                    if($id_usuario_loggeado == $id_publicado_por)
                                    {
                                        $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                                            <h4>Proyecto incluido</h4>
                                                            <div class='mostrarProyecto'>
                                                                <div class='nombre_proyecto_container'>
                                                                    <p> " . $nombre_proyecto . "</p>
                                                                </div>
                                                                <div class='imagen_fondo_mostrar_proyecto'>
                                                                    <img src='assets\images\icons\blockimino.png'>
                                                                </div>
                                                                <div class='contenedor_boton_ver_proyecto'>
                                                                    <button class='boton_ver_proyecto btn btn-success' id='editar" . $nombre_proyecto . "'>Editar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            $(document).ready(function(){
                                                                $('#editar$nombre_proyecto').on('click', function() {
                                                                    window.open('block_arena.php?project=$nombre_proyecto');
                                                                    
                                                                });
                                                            });
                                                        </script>";
                                    }
                                    else
                                    {
                                        $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                                            <h4>Proyecto incluido</h4>
                                                            <div class='mostrarProyecto'>
                                                                <div class='nombre_proyecto_container'>
                                                                    <p> " . $nombre_proyecto . "</p>
                                                                </div>
                                                                <div class='imagen_fondo_mostrar_proyecto'>
                                                                    <img src='assets\images\icons\blockimino.png'>
                                                                </div>
                                                                <div class='contenedor_boton_copiar_proyecto'>
                                                                    <button class='boton_copiar_proyecto btn btn-info' id='copiar_proyecto" . $nombre_proyecto . "'>Copiar proyecto</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                        $(document).ready(function(){
                                                            $('#copiar_proyecto$nombre_proyecto').on('click', function() {
                                                                bootbox.prompt('Introduce un nombre para el proyecto', function(result) {
                                                                    if(result != null)
                                                                    {
                                                                        $.ajax({
                                                                            url: 'includes/handlers/ajax_copy_project.php?id_usuario=$id_usuario_loggeado&link_proyecto=$link_proyecto',
                                                                            type: 'POST',
                                                                            data: {resultado:result},
                                                                            success: function(data) {
                                                                                alert(data);
                                                                            }
                                                                        });
                                                                    }
                                                                });
                                                            });
                                                        });
                                                    </script>";
                                    }                                        
                                }
                                else
                                {
                                    $divProyecto = "";
                                }
                                #endregion
                                        
                                // + En este string se guardara cada publciacion y cada que se ejecute la carga de una, se emitira un echo, para mostrarla al usuario
                                // + Tenemos divido por doto de perfil, un mensaje de cuanto tiempo ha pasado desde que se hizo la publicacion y el cuerpo de la publicacion
                                $string_publicacion .= 
                                // + onClick='javascript:toggle$id_publicacion' -> Cuando hagamos click, se ejecutara la funcion
                                // + <div class ='publicar_comentario'> -> lo ocultamos con display:none, este cambiara como ya lo explicamos antes entre block y none
                                    "<h4>Publicación de tu interés: <a href='publication_hashtag.php?hashtag=". $nombre_sin_hashtag_interes . "'>$nombre_sin_hashtag_interes</a></h4>
                                    <div class='publicacion'>
                                        <div class='foto_perfil_publicacion'>
                                            <img src='$foto_perfil' width='50'>
                                        </div>
            
                                        <div class='publicado_por' style='color:#ACACAC;'>
                                            <a href='$usuario_publicado_por'> $nombre $apeP $apeM </a> $usuario_publicado_para &nbsp;&nbsp;&nbsp;&nbsp;$mensaje_tiempo
                                            $boton_eliminar
                                        </div>
                                        <div id='titulo_publicacion' style='font-style: bold;'>
                                            $titulo
                                            <hr>
                                        </div>
                                        <div id='cuerpo_publicacion'>
                                            $cuerpo
                                            <br>
                                            $divImagen
                                            <br>
                                            <br>
                                        </div>
                                        $div_hashtags
            
                                        $divProyecto
            
                                        $divArchivo
                                        <div class='OpcionesDePublicacion'>
                                            &nbsp;
                                            <span class='mostrar_ocultar_comentarios' onClick='javascript:toggle$id_publicacion()'>
                                                <i class='fa-solid fa-comment'></i>&nbsp;$numero_comentarios
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
                                        // + Script de borrar publicacion
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
                                                        if(result == true)
                                                        {
                                                            // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado 
                                                            $.post("includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", {resultado:result});
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
                                                            $.post("includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", { resultado:result, razon:result});
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
                        }

                        // + Buscar otro tipo de publicacion
                        if($publicacion_probable_puede_ser_recomendada)
                        {
                            $query_obtener_publicacion_probable_recomendada = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE borrado='no' AND id_grupo_publicacion IS NULL AND id_publicacion='$id_publicacion_probable_recomendada'");

                            // + Si existen publicaciones entonces:
                            if(mysqli_num_rows($query_obtener_publicacion_probable_recomendada) > 0)
                            {
                                $fila_publicacion_recomendada = mysqli_fetch_array($query_obtener_publicacion_probable_recomendada);
                    
                                $objeto_usuario_loggeado = new Usuario($this->con, $id_usuario_loggeado);
                    
                                // + Guardamos en variables, las variables de la fila de la base de datos
                                $id_publicacion = $id_publicacion_recomendada;
                                $titulo = $fila_publicacion_recomendada['titulo'];
                                $cuerpo = $fila_publicacion_recomendada['cuerpo'];
                                $cuerpo = nl2br($cuerpo);
                    
                                $id_publicado_por = $fila_publicacion_recomendada['publicado_por'];
                                $query_recomendacion_publicado_por = mysqli_query($this->con, "SELECT username, tipo, mostrar_proyectos from usuarios WHERE id_usuario='$id_publicado_por'");
        
                                $fila_recomendacion_publicado_por = mysqli_fetch_array($query_recomendacion_publicado_por);
                                // - Asignamos nombre de usuario a publicado por
                                $usuario_publicado_por = $fila_recomendacion_publicado_por['username'];
        
                                $fecha_publicado = $fila_publicacion_recomendada['fecha_publicado'];
        
                                $tipo_usuario_publicado_por = $fila_recomendacion_publicado_por['tipo'];
                    
                                $visibilidad_proyecto = $fila_recomendacion_publicado_por['mostrar_proyectos'];
                    
                                $direccionImagen= $fila_publicacion_recomendada['imagen'];
                                $direccionArchivo = $fila_publicacion_recomendada['archivo'];
                                $id_proyecto = $fila_publicacion_recomendada['proyecto'];
                
                                $usuario_publicado_para = "";
        
                                #region verificar si la cuenta del usuario esta cerrada
                                // + Creamos un nuevo usuario para el usuario que realizo la publicacion
                                $objeto_publicado_por = new Usuario($this->con, $id_publicado_por);
                                if($objeto_publicado_por->estaCerrado())
                                {
                                    // $ continue -> Detiene la iteracion actual y vuelve al principio del bucle para realizar otra iteracion
                                    continue;
                                }
                                #endregion
                    
                                #region determinar tipo de usuario y boton de borrar
                                $tipo_usuario = $objeto_usuario_loggeado->obtenerTipoUsuario();
                    
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
        
                                #endregion
                    
                                #region publicado_por
                                // + Query para seleccionar el nombre del usuario que publico y su foto de perfil
                                $query_detalles_usuario_recomendado = mysqli_query($this->con, "SELECT nombre, apeP, apeM, foto_perfil FROM usuarios WHERE id_usuario='$id_publicado_por'");
                                // + Guardamos las variables en filas 
                                $fila_usuario_recomendado = mysqli_fetch_array($query_detalles_usuario_recomendado);
                                // + Guardamos en variables, las variables de la fila de la base de datos
                                $nombre = $fila_usuario_recomendado['nombre'];
                                $apeP = $fila_usuario_recomendado['apeP'];
                                $apeM = $fila_usuario_recomendado['apeM'];
                                $foto_perfil = $fila_usuario_recomendado['foto_perfil'];
                                #endregion
                    
                                #region script y logica de comentarios 
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
                                        if (!target.is("")) {
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
                    
                                $checar_si_hay_comentarios = mysqli_query($this->con, "SELECT * FROM comentarios WHERE (eliminado='no' AND publicacion_comentada='$id_publicacion')");
                                $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios);
            
                                if($numero_comentarios == 1)
                                {
                                    $numero_comentarios = $numero_comentarios . " Comentario";
                                }
                                else
                                {
                                    $numero_comentarios = $numero_comentarios . " Comentarios";
                                }
        
                                #endregion
            
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
                                        $dias = $intervalo->d. " día atrás";
                                    }
                                    //Mas de 1 dia
                                    else 
                                    {
                                        $dias = $intervalo->d . " días atrás";
                                    }
            
                                    //1 mes
                                    if($intervalo-> m == 1)
                                    {
                                        $mensaje_tiempo = $intervalo->m . " mes " . $dias;
                                    }
                                    //Mas de 1 mes
                                    else
                                    {
                                        $mensaje_tiempo = $intervalo->m . " meses " . $dias;
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
            
                                #region procesar archivos, hashtags, hashtags y proyectos
                                // + Procesar si hay una imagen
                                if($direccionImagen != "")
                                {
                                    $lista_imagenes_explode = explode("|", $direccionImagen);
                                    $lista_imagenes_explode = array_filter($lista_imagenes_explode);
            
                                    $divImagen = "<div class='contenedorImagenesPublicadas'>";
                                    
                                    foreach($lista_imagenes_explode as $imagen)
                                    {
                                        $divImagen .= "<div class='imagenPublicada'>
                                                            <img src='$imagen'>
                                                        </div>";                                                
                                    }
            
                                    $divImagen .= "</div>";
                                }
                                else
                                {
                                    $divImagen = "";
                                }
            
                                if($direccionArchivo != "")
                                {
                                    $lista_archivos_explode = explode("|", $direccionArchivo);
                                    $lista_archivos_explode = array_filter($lista_archivos_explode);
            
                                    $divArchivo = "<div class='contenedorArchivosPublicados'>
                                                        <h4>Archivos</h4>";
            
                                    foreach($lista_archivos_explode as $direccion_archivo)
                                    {
                                        $link_archivo = substr($direccion_archivo, strpos($direccion_archivo, "_") + 1);
                                        $divArchivo .= "<div class='archivoPublicado'>
                                                            <a href='" . $direccion_archivo . "' download='" . $link_archivo . "'><i class='fa-solid fa-file'></i> " . $link_archivo . "</a>
                                                        </div>";
                                    }
            
                                    $divArchivo .= "</div>";
            
                                }
                                else
                                {
                                    $divArchivo = "";
                                }
            
                                $hashtags = $fila_publicacion_recomendada['hashtags_publicacion'];
                                $lista_hashtags_explode = explode(",", $hashtags);
                                $lista_hashtags_explode = array_filter($lista_hashtags_explode);
            
                                if($hashtags != ",")
                                {
                                    $div_hashtags = "<div class='contenedor_hashtags'>
                                                        <h4>Hashtags</h4>";
            
                                    foreach($lista_hashtags_explode as $hashtag)
                                    {
                                        $query_info_hashtag = mysqli_query($this->con, "SELECT hashtag FROM hashtags WHERE id_hashtag='$hashtag'");
                                        $fila_info_hashtag = mysqli_fetch_array($query_info_hashtag);
                                        $nombre_hashtag =  $fila_info_hashtag['hashtag'];
                                        $nombre_sin_hashtag = str_replace("#", "", $fila_info_hashtag['hashtag']);
            
                                        $div_hashtags .= "<a href='publication_hashtag.php?hashtag=". $nombre_sin_hashtag . "'>
                                                            <div class='displayHashtag'>
                                                                " . $nombre_hashtag . "
                                                            </div>
                                                        </a>";
                                    }
                                    $div_hashtags .= "</div>";
                                }
                                else
                                {
                                    $div_hashtags = "";
                                }
            
                                // + Mostrar si se publico un proyecto
                                if($id_proyecto != NULL)
                                {
                                    $query_obtener_detalles_proyecto = mysqli_query($this->con, "SELECT * FROM proyectos WHERE id_proyecto='$id_proyecto'");
                                    $fila_detalles_proyecto = mysqli_fetch_array($query_obtener_detalles_proyecto);
                                    $nombre_proyecto = $fila_detalles_proyecto['nombre_proyecto'];
                                    $link_proyecto = $fila_detalles_proyecto['link_proyecto'];
                                    if($visibilidad_proyecto)
                                    {
                                        $mostrar_proyecto = true;
                                    }
                                    else
                                    {
                                        if($objeto_usuario_loggeado->esAmigo($id_publicado_por) && $objeto_usuario_loggeado->obtenerIDUsuario() == $id_publicado_por)
                                        {
                                            $mostrar_proyecto = true;
                                        }
                                        else
                                        {
                                            $mostrar_proyecto = false;
                                        }
                                    }
                                }
                                else
                                {
                                    $mostrar_proyecto = false;
                                }
            
                                if($mostrar_proyecto)
                                {
                                    if($id_usuario_loggeado == $id_publicado_por)
                                    {
                                        $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                                            <h4>Proyecto incluido</h4>
                                                            <div class='mostrarProyecto'>
                                                                <div class='nombre_proyecto_container'>
                                                                    <p> " . $nombre_proyecto . "</p>
                                                                </div>
                                                                <div class='imagen_fondo_mostrar_proyecto'>
                                                                    <img src='assets\images\icons\blockimino.png'>
                                                                </div>
                                                                <div class='contenedor_boton_ver_proyecto'>
                                                                    <button class='boton_ver_proyecto btn btn-success' id='editar" . $nombre_proyecto . "'>Editar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            $(document).ready(function(){
                                                                $('#editar$nombre_proyecto').on('click', function() {
                                                                    window.open('block_arena.php?project=$nombre_proyecto');
                                                                    
                                                                });
                                                            });
                                                        </script>";
                                    }
                                    else
                                    {
                                        $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                                            <h4>Proyecto incluido</h4>
                                                            <div class='mostrarProyecto'>
                                                                <div class='nombre_proyecto_container'>
                                                                    <p> " . $nombre_proyecto . "</p>
                                                                </div>
                                                                <div class='imagen_fondo_mostrar_proyecto'>
                                                                    <img src='assets\images\icons\blockimino.png'>
                                                                </div>
                                                                <div class='contenedor_boton_copiar_proyecto'>
                                                                    <button class='boton_copiar_proyecto btn btn-info' id='copiar_proyecto" . $nombre_proyecto . "'>Copiar proyecto</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                        $(document).ready(function(){
                                                            $('#copiar_proyecto$nombre_proyecto').on('click', function() {
                                                                bootbox.prompt('Introduce un nombre para el proyecto', function(result) {
                                                                    if(result != null)
                                                                    {
                                                                        $.ajax({
                                                                            url: 'includes/handlers/ajax_copy_project.php?id_usuario=$id_usuario_loggeado&link_proyecto=$link_proyecto',
                                                                            type: 'POST',
                                                                            data: {resultado:result},
                                                                            success: function(data) {
                                                                                alert(data);
                                                                            }
                                                                        });
                                                                    }
                                                                });
                                                            });
                                                        });
                                                    </script>";
                                    }                                        
                                }
                                else
                                {
                                    $divProyecto = "";
                                }
                                #endregion
                                        
                                // + En este string se guardara cada publciacion y cada que se ejecute la carga de una, se emitira un echo, para mostrarla al usuario
                                // + Tenemos divido por doto de perfil, un mensaje de cuanto tiempo ha pasado desde que se hizo la publicacion y el cuerpo de la publicacion
                                $string_publicacion .= 
                                // + onClick='javascript:toggle$id_publicacion' -> Cuando hagamos click, se ejecutara la funcion
                                // + <div class ='publicar_comentario'> -> lo ocultamos con display:none, este cambiara como ya lo explicamos antes entre block y none
                                    "<h4>Porque te intereso: <a href='publication_hashtag.php?hashtag=". $nombre_sin_hashtag_interes . "'>$nombre_sin_hashtag_interes</a></h4>
                                    <div class='publicacion'>
                                        <div class='foto_perfil_publicacion'>
                                            <img src='$foto_perfil' width='50'>
                                        </div>
            
                                        <div class='publicado_por' style='color:#ACACAC;'>
                                            <a href='$usuario_publicado_por'> $nombre $apeP $apeM </a> $usuario_publicado_para &nbsp;&nbsp;&nbsp;&nbsp;$mensaje_tiempo
                                            $boton_eliminar
                                        </div>
                                        <div id='titulo_publicacion' style='font-style: bold;'>
                                            $titulo
                                            <hr>
                                        </div>
                                        <div id='cuerpo_publicacion'>
                                            $cuerpo
                                            <br>
                                            $divImagen
                                            <br>
                                            <br>
                                        </div>
                                        $div_hashtags
            
                                        $divProyecto
            
                                        $divArchivo
                                        <div class='OpcionesDePublicacion'>
                                            &nbsp;
                                            <span class='mostrar_ocultar_comentarios' onClick='javascript:toggle$id_publicacion()'>
                                                <i class='fa-solid fa-comment'></i>&nbsp;$numero_comentarios
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
                                        // + Script de borrar publicacion
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
                                                        if(result == true)
                                                        {
                                                            // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado 
                                                            $.post("includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", {resultado:result});
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
                                                            $.post("includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", { resultado:result, razon:result});
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
                        }

                        // + Recomendar publicaciones de amigos
                        if($publicacion_amigo_puede_ser_recomendada)
                        {
                            $query_obtener_publicacion_recomendada = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE borrado='no' AND id_grupo_publicacion IS NULL AND id_publicacion='$id_publicacion_amigo_recomendada'");

                            // + Si existen publicaciones entonces:
                            if(mysqli_num_rows($query_obtener_publicacion_recomendada) > 0)
                            {
                                $fila_publicacion_recomendada = mysqli_fetch_array($query_obtener_publicacion_recomendada);
                    
                                $objeto_usuario_loggeado = new Usuario($this->con, $id_usuario_loggeado);
                    
                                // + Guardamos en variables, las variables de la fila de la base de datos
                                $id_publicacion = $id_publicacion_amigo_recomendada;
                                $titulo = $fila_publicacion_recomendada['titulo'];
                                $cuerpo = $fila_publicacion_recomendada['cuerpo'];
                                $cuerpo = nl2br($cuerpo);
                    
                                $id_publicado_por = $fila_publicacion_recomendada['publicado_por'];
                                $query_recomendacion_publicado_por = mysqli_query($this->con, "SELECT username, tipo, mostrar_proyectos from usuarios WHERE id_usuario='$id_publicado_por'");
        
                                $fila_recomendacion_publicado_por = mysqli_fetch_array($query_recomendacion_publicado_por);
                                // - Asignamos nombre de usuario a publicado por
                                $usuario_publicado_por = $fila_recomendacion_publicado_por['username'];
        
                                $fecha_publicado = $fila_publicacion_recomendada['fecha_publicado'];
        
                                $tipo_usuario_publicado_por = $fila_recomendacion_publicado_por['tipo'];
                    
                                $visibilidad_proyecto = $fila_recomendacion_publicado_por['mostrar_proyectos'];
                    
                                $direccionImagen= $fila_publicacion_recomendada['imagen'];
                                $direccionArchivo = $fila_publicacion_recomendada['archivo'];
                                $id_proyecto = $fila_publicacion_recomendada['proyecto'];
                
                                $usuario_publicado_para = "";
        
                                #region verificar si la cuenta del usuario esta cerrada
                                // + Creamos un nuevo usuario para el usuario que realizo la publicacion
                                $objeto_publicado_por = new Usuario($this->con, $id_publicado_por);
                                if($objeto_publicado_por->estaCerrado())
                                {
                                    // $ continue -> Detiene la iteracion actual y vuelve al principio del bucle para realizar otra iteracion
                                    continue;
                                }
                                #endregion
                    
                                #region determinar tipo de usuario y boton de borrar
                                $tipo_usuario = $objeto_usuario_loggeado->obtenerTipoUsuario();
                    
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
        
                                #endregion
                    
                                #region publicado_por
                                // + Query para seleccionar el nombre del usuario que publico y su foto de perfil
                                $query_detalles_usuario_recomendado = mysqli_query($this->con, "SELECT nombre, apeP, apeM, foto_perfil FROM usuarios WHERE id_usuario='$id_publicado_por'");
                                // + Guardamos las variables en filas 
                                $fila_usuario_recomendado = mysqli_fetch_array($query_detalles_usuario_recomendado);
                                // + Guardamos en variables, las variables de la fila de la base de datos
                                $nombre = $fila_usuario_recomendado['nombre'];
                                $apeP = $fila_usuario_recomendado['apeP'];
                                $apeM = $fila_usuario_recomendado['apeM'];
                                $foto_perfil = $fila_usuario_recomendado['foto_perfil'];
                                #endregion
                    
                                #region script y logica de comentarios 
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
                                        if (!target.is("")) {
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
                    
                                $checar_si_hay_comentarios = mysqli_query($this->con, "SELECT * FROM comentarios WHERE (eliminado='no' AND publicacion_comentada='$id_publicacion')");
                                $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios);
            
                                if($numero_comentarios == 1)
                                {
                                    $numero_comentarios = $numero_comentarios . " Comentario";
                                }
                                else
                                {
                                    $numero_comentarios = $numero_comentarios . " Comentarios";
                                }
        
                                #endregion
            
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
                                        $dias = $intervalo->d. " día atrás";
                                    }
                                    //Mas de 1 dia
                                    else 
                                    {
                                        $dias = $intervalo->d . " días atrás";
                                    }
            
                                    //1 mes
                                    if($intervalo-> m == 1)
                                    {
                                        $mensaje_tiempo = $intervalo->m . " mes " . $dias;
                                    }
                                    //Mas de 1 mes
                                    else
                                    {
                                        $mensaje_tiempo = $intervalo->m . " meses " . $dias;
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
            
                                #region procesar archivos, hashtags, hashtags y proyectos
                                // + Procesar si hay una imagen
                                if($direccionImagen != "")
                                {
                                    $lista_imagenes_explode = explode("|", $direccionImagen);
                                    $lista_imagenes_explode = array_filter($lista_imagenes_explode);
            
                                    $divImagen = "<div class='contenedorImagenesPublicadas'>";
                                    
                                    foreach($lista_imagenes_explode as $imagen)
                                    {
                                        $divImagen .= "<div class='imagenPublicada'>
                                                            <img src='$imagen'>
                                                        </div>";                                                
                                    }
            
                                    $divImagen .= "</div>";
                                }
                                else
                                {
                                    $divImagen = "";
                                }
            
                                if($direccionArchivo != "")
                                {
                                    $lista_archivos_explode = explode("|", $direccionArchivo);
                                    $lista_archivos_explode = array_filter($lista_archivos_explode);
            
                                    $divArchivo = "<div class='contenedorArchivosPublicados'>
                                                        <h4>Archivos</h4>";
            
                                    foreach($lista_archivos_explode as $direccion_archivo)
                                    {
                                        $link_archivo = substr($direccion_archivo, strpos($direccion_archivo, "_") + 1);
                                        $divArchivo .= "<div class='archivoPublicado'>
                                                            <a href='" . $direccion_archivo . "' download='" . $link_archivo . "'><i class='fa-solid fa-file'></i> " . $link_archivo . "</a>
                                                        </div>";
                                    }
            
                                    $divArchivo .= "</div>";
            
                                }
                                else
                                {
                                    $divArchivo = "";
                                }
            
                                $hashtags = $fila_publicacion_recomendada['hashtags_publicacion'];
                                $lista_hashtags_explode = explode(",", $hashtags);
                                $lista_hashtags_explode = array_filter($lista_hashtags_explode);
            
                                if($hashtags != ",")
                                {
                                    $div_hashtags = "<div class='contenedor_hashtags'>
                                                        <h4>Hashtags</h4>";
            
                                    foreach($lista_hashtags_explode as $hashtag)
                                    {
                                        $query_info_hashtag = mysqli_query($this->con, "SELECT hashtag FROM hashtags WHERE id_hashtag='$hashtag'");
                                        $fila_info_hashtag = mysqli_fetch_array($query_info_hashtag);
                                        $nombre_hashtag =  $fila_info_hashtag['hashtag'];
                                        $nombre_sin_hashtag = str_replace("#", "", $fila_info_hashtag['hashtag']);
            
                                        $div_hashtags .= "<a href='publication_hashtag.php?hashtag=". $nombre_sin_hashtag . "'>
                                                            <div class='displayHashtag'>
                                                                " . $nombre_hashtag . "
                                                            </div>
                                                        </a>";
                                    }
                                    $div_hashtags .= "</div>";
                                }
                                else
                                {
                                    $div_hashtags = "";
                                }
            
                                // + Mostrar si se publico un proyecto
                                if($id_proyecto != NULL)
                                {
                                    $query_obtener_detalles_proyecto = mysqli_query($this->con, "SELECT * FROM proyectos WHERE id_proyecto='$id_proyecto'");
                                    $fila_detalles_proyecto = mysqli_fetch_array($query_obtener_detalles_proyecto);
                                    $nombre_proyecto = $fila_detalles_proyecto['nombre_proyecto'];
                                    $link_proyecto = $fila_detalles_proyecto['link_proyecto'];
                                    if($visibilidad_proyecto)
                                    {
                                        $mostrar_proyecto = true;
                                    }
                                    else
                                    {
                                        if($objeto_usuario_loggeado->esAmigo($id_publicado_por) && $objeto_usuario_loggeado->obtenerIDUsuario() == $id_publicado_por)
                                        {
                                            $mostrar_proyecto = true;
                                        }
                                        else
                                        {
                                            $mostrar_proyecto = false;
                                        }
                                    }
                                }
                                else
                                {
                                    $mostrar_proyecto = false;
                                }
            
                                if($mostrar_proyecto)
                                {
                                    if($id_usuario_loggeado == $id_publicado_por)
                                    {
                                        $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                                            <h4>Proyecto incluido</h4>
                                                            <div class='mostrarProyecto'>
                                                                <div class='nombre_proyecto_container'>
                                                                    <p> " . $nombre_proyecto . "</p>
                                                                </div>
                                                                <div class='imagen_fondo_mostrar_proyecto'>
                                                                    <img src='assets\images\icons\blockimino.png'>
                                                                </div>
                                                                <div class='contenedor_boton_ver_proyecto'>
                                                                    <button class='boton_ver_proyecto btn btn-success' id='editar" . $nombre_proyecto . "'>Editar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            $(document).ready(function(){
                                                                $('#editar$nombre_proyecto').on('click', function() {
                                                                    window.open('block_arena.php?project=$nombre_proyecto');
                                                                    
                                                                });
                                                            });
                                                        </script>";
                                    }
                                    else
                                    {
                                        $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                                            <h4>Proyecto incluido</h4>
                                                            <div class='mostrarProyecto'>
                                                                <div class='nombre_proyecto_container'>
                                                                    <p> " . $nombre_proyecto . "</p>
                                                                </div>
                                                                <div class='imagen_fondo_mostrar_proyecto'>
                                                                    <img src='assets\images\icons\blockimino.png'>
                                                                </div>
                                                                <div class='contenedor_boton_copiar_proyecto'>
                                                                    <button class='boton_copiar_proyecto btn btn-info' id='copiar_proyecto" . $nombre_proyecto . "'>Copiar proyecto</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                        $(document).ready(function(){
                                                            $('#copiar_proyecto$nombre_proyecto').on('click', function() {
                                                                bootbox.prompt('Introduce un nombre para el proyecto', function(result) {
                                                                    if(result != null)
                                                                    {
                                                                        $.ajax({
                                                                            url: 'includes/handlers/ajax_copy_project.php?id_usuario=$id_usuario_loggeado&link_proyecto=$link_proyecto',
                                                                            type: 'POST',
                                                                            data: {resultado:result},
                                                                            success: function(data) {
                                                                                alert(data);
                                                                            }
                                                                        });
                                                                    }
                                                                });
                                                            });
                                                        });
                                                    </script>";
                                    }                                        
                                }
                                else
                                {
                                    $divProyecto = "";
                                }
                                #endregion
                                        
                                // + En este string se guardara cada publciacion y cada que se ejecute la carga de una, se emitira un echo, para mostrarla al usuario
                                // + Tenemos divido por doto de perfil, un mensaje de cuanto tiempo ha pasado desde que se hizo la publicacion y el cuerpo de la publicacion
                                $string_publicacion .= 
                                // + onClick='javascript:toggle$id_publicacion' -> Cuando hagamos click, se ejecutara la funcion
                                // + <div class ='publicar_comentario'> -> lo ocultamos con display:none, este cambiara como ya lo explicamos antes entre block y none
                                    "<h4>A tus amigos les interesa: <a href='publication_hashtag.php?hashtag=". $nombre_hashtag_interes_amigo . "'>$nombre_sin_hashtag_interes_amigo</a></h4>
                                    <div class='publicacion'>
                                        <div class='foto_perfil_publicacion'>
                                            <img src='$foto_perfil' width='50'>
                                        </div>
            
                                        <div class='publicado_por' style='color:#ACACAC;'>
                                            <a href='$usuario_publicado_por'> $nombre $apeP $apeM </a> $usuario_publicado_para &nbsp;&nbsp;&nbsp;&nbsp;$mensaje_tiempo
                                            $boton_eliminar
                                        </div>
                                        <div id='titulo_publicacion' style='font-style: bold;'>
                                            $titulo
                                            <hr>
                                        </div>
                                        <div id='cuerpo_publicacion'>
                                            $cuerpo
                                            <br>
                                            $divImagen
                                            <br>
                                            <br>
                                        </div>
                                        $div_hashtags
            
                                        $divProyecto
            
                                        $divArchivo
                                        <div class='OpcionesDePublicacion'>
                                            &nbsp;
                                            <span class='mostrar_ocultar_comentarios' onClick='javascript:toggle$id_publicacion()'>
                                                <i class='fa-solid fa-comment'></i>&nbsp;$numero_comentarios
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
                                        // + Script de borrar publicacion
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
                                                        if(result == true)
                                                        {
                                                            // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado 
                                                            $.post("includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", {resultado:result});
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
                                                            $.post("includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", { resultado:result, razon:result});
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
                        }
                        
                        
                        // + Recomendar publicaciones de seguidos
                        if($publicacion_seguido_puede_ser_recomendada)
                        {
                            $query_obtener_publicacion_recomendada = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE borrado='no' AND id_grupo_publicacion IS NULL AND id_publicacion='$id_publicacion_seguido_recomendada'");

                            // + Si existen publicaciones entonces:
                            if(mysqli_num_rows($query_obtener_publicacion_recomendada) > 0)
                            {
                                $fila_publicacion_recomendada = mysqli_fetch_array($query_obtener_publicacion_recomendada);
                    
                                $objeto_usuario_loggeado = new Usuario($this->con, $id_usuario_loggeado);
                    
                                // + Guardamos en variables, las variables de la fila de la base de datos
                                $id_publicacion = $id_publicacion_seguido_recomendada;
                                $titulo = $fila_publicacion_recomendada['titulo'];
                                $cuerpo = $fila_publicacion_recomendada['cuerpo'];
                                $cuerpo = nl2br($cuerpo);
                    
                                $id_publicado_por = $fila_publicacion_recomendada['publicado_por'];
                                $query_recomendacion_publicado_por = mysqli_query($this->con, "SELECT username, tipo, mostrar_proyectos from usuarios WHERE id_usuario='$id_publicado_por'");
        
                                $fila_recomendacion_publicado_por = mysqli_fetch_array($query_recomendacion_publicado_por);
                                // - Asignamos nombre de usuario a publicado por
                                $usuario_publicado_por = $fila_recomendacion_publicado_por['username'];
        
                                $fecha_publicado = $fila_publicacion_recomendada['fecha_publicado'];
        
                                $tipo_usuario_publicado_por = $fila_recomendacion_publicado_por['tipo'];
                    
                                $visibilidad_proyecto = $fila_recomendacion_publicado_por['mostrar_proyectos'];
                    
                                $direccionImagen= $fila_publicacion_recomendada['imagen'];
                                $direccionArchivo = $fila_publicacion_recomendada['archivo'];
                                $id_proyecto = $fila_publicacion_recomendada['proyecto'];
                
                                $usuario_publicado_para = "";
        
                                #region verificar si la cuenta del usuario esta cerrada
                                // + Creamos un nuevo usuario para el usuario que realizo la publicacion
                                $objeto_publicado_por = new Usuario($this->con, $id_publicado_por);
                                if($objeto_publicado_por->estaCerrado())
                                {
                                    // $ continue -> Detiene la iteracion actual y vuelve al principio del bucle para realizar otra iteracion
                                    continue;
                                }
                                #endregion
                    
                                #region determinar tipo de usuario y boton de borrar
                                $tipo_usuario = $objeto_usuario_loggeado->obtenerTipoUsuario();
                    
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
        
                                #endregion
                    
                                #region publicado_por
                                // + Query para seleccionar el nombre del usuario que publico y su foto de perfil
                                $query_detalles_usuario_recomendado = mysqli_query($this->con, "SELECT nombre, apeP, apeM, foto_perfil FROM usuarios WHERE id_usuario='$id_publicado_por'");
                                // + Guardamos las variables en filas 
                                $fila_usuario_recomendado = mysqli_fetch_array($query_detalles_usuario_recomendado);
                                // + Guardamos en variables, las variables de la fila de la base de datos
                                $nombre = $fila_usuario_recomendado['nombre'];
                                $apeP = $fila_usuario_recomendado['apeP'];
                                $apeM = $fila_usuario_recomendado['apeM'];
                                $foto_perfil = $fila_usuario_recomendado['foto_perfil'];
                                #endregion
                    
                                #region script y logica de comentarios 
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
                                        if (!target.is("")) {
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
                    
                                $checar_si_hay_comentarios = mysqli_query($this->con, "SELECT * FROM comentarios WHERE (eliminado='no' AND publicacion_comentada='$id_publicacion')");
                                $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios);
            
                                if($numero_comentarios == 1)
                                {
                                    $numero_comentarios = $numero_comentarios . " Comentario";
                                }
                                else
                                {
                                    $numero_comentarios = $numero_comentarios . " Comentarios";
                                }
        
                                #endregion
            
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
                                        $dias = $intervalo->d. " día atrás";
                                    }
                                    //Mas de 1 dia
                                    else 
                                    {
                                        $dias = $intervalo->d . " días atrás";
                                    }
            
                                    //1 mes
                                    if($intervalo-> m == 1)
                                    {
                                        $mensaje_tiempo = $intervalo->m . " mes " . $dias;
                                    }
                                    //Mas de 1 mes
                                    else
                                    {
                                        $mensaje_tiempo = $intervalo->m . " meses " . $dias;
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
            
                                #region procesar archivos, hashtags, hashtags y proyectos
                                // + Procesar si hay una imagen
                                if($direccionImagen != "")
                                {
                                    $lista_imagenes_explode = explode("|", $direccionImagen);
                                    $lista_imagenes_explode = array_filter($lista_imagenes_explode);
            
                                    $divImagen = "<div class='contenedorImagenesPublicadas'>";
                                    
                                    foreach($lista_imagenes_explode as $imagen)
                                    {
                                        $divImagen .= "<div class='imagenPublicada'>
                                                            <img src='$imagen'>
                                                        </div>";                                                
                                    }
            
                                    $divImagen .= "</div>";
                                }
                                else
                                {
                                    $divImagen = "";
                                }
            
                                if($direccionArchivo != "")
                                {
                                    $lista_archivos_explode = explode("|", $direccionArchivo);
                                    $lista_archivos_explode = array_filter($lista_archivos_explode);
            
                                    $divArchivo = "<div class='contenedorArchivosPublicados'>
                                                        <h4>Archivos</h4>";
            
                                    foreach($lista_archivos_explode as $direccion_archivo)
                                    {
                                        $link_archivo = substr($direccion_archivo, strpos($direccion_archivo, "_") + 1);
                                        $divArchivo .= "<div class='archivoPublicado'>
                                                            <a href='" . $direccion_archivo . "' download='" . $link_archivo . "'><i class='fa-solid fa-file'></i> " . $link_archivo . "</a>
                                                        </div>";
                                    }
            
                                    $divArchivo .= "</div>";
            
                                }
                                else
                                {
                                    $divArchivo = "";
                                }
            
                                $hashtags = $fila_publicacion_recomendada['hashtags_publicacion'];
                                $lista_hashtags_explode = explode(",", $hashtags);
                                $lista_hashtags_explode = array_filter($lista_hashtags_explode);
            
                                if($hashtags != ",")
                                {
                                    $div_hashtags = "<div class='contenedor_hashtags'>
                                                        <h4>Hashtags</h4>";
            
                                    foreach($lista_hashtags_explode as $hashtag)
                                    {
                                        $query_info_hashtag = mysqli_query($this->con, "SELECT hashtag FROM hashtags WHERE id_hashtag='$hashtag'");
                                        $fila_info_hashtag = mysqli_fetch_array($query_info_hashtag);
                                        $nombre_hashtag =  $fila_info_hashtag['hashtag'];
                                        $nombre_sin_hashtag = str_replace("#", "", $fila_info_hashtag['hashtag']);
            
                                        $div_hashtags .= "<a href='publication_hashtag.php?hashtag=". $nombre_sin_hashtag . "'>
                                                            <div class='displayHashtag'>
                                                                " . $nombre_hashtag . "
                                                            </div>
                                                        </a>";
                                    }
                                    $div_hashtags .= "</div>";
                                }
                                else
                                {
                                    $div_hashtags = "";
                                }
            
                                // + Mostrar si se publico un proyecto
                                if($id_proyecto != NULL)
                                {
                                    $query_obtener_detalles_proyecto = mysqli_query($this->con, "SELECT * FROM proyectos WHERE id_proyecto='$id_proyecto'");
                                    $fila_detalles_proyecto = mysqli_fetch_array($query_obtener_detalles_proyecto);
                                    $nombre_proyecto = $fila_detalles_proyecto['nombre_proyecto'];
                                    $link_proyecto = $fila_detalles_proyecto['link_proyecto'];
                                    if($visibilidad_proyecto)
                                    {
                                        $mostrar_proyecto = true;
                                    }
                                    else
                                    {
                                        if($objeto_usuario_loggeado->esAmigo($id_publicado_por) && $objeto_usuario_loggeado->obtenerIDUsuario() == $id_publicado_por)
                                        {
                                            $mostrar_proyecto = true;
                                        }
                                        else
                                        {
                                            $mostrar_proyecto = false;
                                        }
                                    }
                                }
                                else
                                {
                                    $mostrar_proyecto = false;
                                }
            
                                if($mostrar_proyecto)
                                {
                                    if($id_usuario_loggeado == $id_publicado_por)
                                    {
                                        $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                                            <h4>Proyecto incluido</h4>
                                                            <div class='mostrarProyecto'>
                                                                <div class='nombre_proyecto_container'>
                                                                    <p> " . $nombre_proyecto . "</p>
                                                                </div>
                                                                <div class='imagen_fondo_mostrar_proyecto'>
                                                                    <img src='assets\images\icons\blockimino.png'>
                                                                </div>
                                                                <div class='contenedor_boton_ver_proyecto'>
                                                                    <button class='boton_ver_proyecto btn btn-success' id='editar" . $nombre_proyecto . "'>Editar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            $(document).ready(function(){
                                                                $('#editar$nombre_proyecto').on('click', function() {
                                                                    window.open('block_arena.php?project=$nombre_proyecto');
                                                                    
                                                                });
                                                            });
                                                        </script>";
                                    }
                                    else
                                    {
                                        $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                                            <h4>Proyecto incluido</h4>
                                                            <div class='mostrarProyecto'>
                                                                <div class='nombre_proyecto_container'>
                                                                    <p> " . $nombre_proyecto . "</p>
                                                                </div>
                                                                <div class='imagen_fondo_mostrar_proyecto'>
                                                                    <img src='assets\images\icons\blockimino.png'>
                                                                </div>
                                                                <div class='contenedor_boton_copiar_proyecto'>
                                                                    <button class='boton_copiar_proyecto btn btn-info' id='copiar_proyecto" . $nombre_proyecto . "'>Copiar proyecto</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                        $(document).ready(function(){
                                                            $('#copiar_proyecto$nombre_proyecto').on('click', function() {
                                                                bootbox.prompt('Introduce un nombre para el proyecto', function(result) {
                                                                    if(result != null)
                                                                    {
                                                                        $.ajax({
                                                                            url: 'includes/handlers/ajax_copy_project.php?id_usuario=$id_usuario_loggeado&link_proyecto=$link_proyecto',
                                                                            type: 'POST',
                                                                            data: {resultado:result},
                                                                            success: function(data) {
                                                                                alert(data);
                                                                            }
                                                                        });
                                                                    }
                                                                });
                                                            });
                                                        });
                                                    </script>";
                                    }                                        
                                }
                                else
                                {
                                    $divProyecto = "";
                                }
                                #endregion
                                        
                                // + En este string se guardara cada publciacion y cada que se ejecute la carga de una, se emitira un echo, para mostrarla al usuario
                                // + Tenemos divido por doto de perfil, un mensaje de cuanto tiempo ha pasado desde que se hizo la publicacion y el cuerpo de la publicacion
                                $string_publicacion .= 
                                // + onClick='javascript:toggle$id_publicacion' -> Cuando hagamos click, se ejecutara la funcion
                                // + <div class ='publicar_comentario'> -> lo ocultamos con display:none, este cambiara como ya lo explicamos antes entre block y none
                                    "<h4>A los usuarios que sigues les interesa: <a href='publication_hashtag.php?hashtag=". $nombre_hashtag_interes_seguido . "'>$nombre_sin_hashtag_interes_seguido</a></h4>
                                    <div class='publicacion'>
                                        <div class='foto_perfil_publicacion'>
                                            <img src='$foto_perfil' width='50'>
                                        </div>
            
                                        <div class='publicado_por' style='color:#ACACAC;'>
                                            <a href='$usuario_publicado_por'> $nombre $apeP $apeM </a> $usuario_publicado_para &nbsp;&nbsp;&nbsp;&nbsp;$mensaje_tiempo
                                            $boton_eliminar
                                        </div>
                                        <div id='titulo_publicacion' style='font-style: bold;'>
                                            $titulo
                                            <hr>
                                        </div>
                                        <div id='cuerpo_publicacion'>
                                            $cuerpo
                                            <br>
                                            $divImagen
                                            <br>
                                            <br>
                                        </div>
                                        $div_hashtags
            
                                        $divProyecto
            
                                        $divArchivo
                                        <div class='OpcionesDePublicacion'>
                                            &nbsp;
                                            <span class='mostrar_ocultar_comentarios' onClick='javascript:toggle$id_publicacion()'>
                                                <i class='fa-solid fa-comment'></i>&nbsp;$numero_comentarios
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
                                        // + Script de borrar publicacion
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
                                                        if(result == true)
                                                        {
                                                            // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado 
                                                            $.post("includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", {resultado:result});
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
                                                            $.post("includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", { resultado:result, razon:result});
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
                        }
                        
                    }
                    // if de recomendar publicacion

                } // * while($fila = mysqli_fetch_array($info))
                ?>
                <script>
                    // + Script para borrar el comentario
                    function confirmDelete(button) {
                        var id_comentario = button.getAttribute("data-id");
                        var es_propio = button.getAttribute("data-es-propio");
                        if (es_propio === "true")
                        {
                            // - resultado -> Sera el resultado de lo que el usuario clickeo, si fue "si" o "no"
                            bootbox.confirm("¿Estas seguro que quieres eliminar este comentario?", function(result) {
                                if(result == true)
                                {
                                    // + Manda el id del comentario a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la                         
                                    $.post("includes/handlers/ajax_delete_comment.php?id_comentario=" + id_comentario + "&id_usuario=<?php echo $id_usuario_loggeado; ?>" + "&id_publicacion=<?php echo $id_publicacion; ?>", {resultado:result});
                                    location.reload();
                                }
                            });
                        }
                        else 
                        {
                            bootbox.prompt({
                            title: "Por favor, escribe una razón para la eliminación del comentario:",
                            buttons: {
                                confirm: {
                                label: 'Aceptar',
                                className: 'btn-danger'
                                }
                            },
                            callback: function(result) {
                                if (result !== true && result !== '') {
                                    $.post("includes/handlers/ajax_delete_comment.php?id_comentario=" + id_comentario + "&id_usuario=<?php echo $id_usuario_loggeado; ?>" + "&id_publicacion=<?php echo $id_publicacion; ?>", { resultado:result, razon:result});
                                    location.reload();
                                } 
                                else 
                                {
                                    alert("Debes ingresar una razón para eliminar el comentario.");
                                }
                            }
                            });
                        }
                    }
                </script>
                <?php
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
                    $string_publicacion .= "<input type='hidden' class='noMasPublicaciones' value='true'><p style='text-align: center;'> No hay más publicaciones por mostrar! </p>
                                            <div class='contenedor_no_mas_publicaciones_recomendar'>
                                                <a href='user_interests.php'>Gestionar mis intereses</a>
                                            </div>";
                }
                echo $string_publicacion;

            }

        }

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
        $query_info = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE (borrado='no' AND ((publicado_por='$id_usuario_perfil' AND publicado_para IS NULL) OR publicado_para='$id_usuario_perfil')) AND id_grupo_publicacion IS NULL ORDER BY id_publicacion DESC");

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
                $objeto_usuario_loggeado = new Usuario($this->con, $id_usuario_loggeado);

                // + Guardamos en variables, las variables de la fila de la base de datos
                $id_publicacion = $fila['id_publicacion'];
                $titulo = $fila['titulo'];
                $cuerpo = $fila['cuerpo'];
                $cuerpo = nl2br($cuerpo);

                // ! publicado por, me regresara un numero, necesito acceder al usuario de ese publicado por
                // ! SE PUEDE HACER CON UN INNER JOIN, PERO VOY A SACAR EL NOMBRE DE USUARIO CON SU ID Y METER EL NOMBRE DE USUARIO AL OBJET: objeto_publicado_por
                $id_publicado_por = $fila['publicado_por'];
                $query_publicado_por = mysqli_query($this->con, "SELECT username, tipo, mostrar_proyectos from usuarios WHERE id_usuario='$id_publicado_por'");
                $fila_publicado_por = mysqli_fetch_array($query_publicado_por);
                // - Asignamos nombre de usuario a publicado por
                $usuario_publicado_por = $fila_publicado_por['username'];
                $fecha_publicado = $fila['fecha_publicado'];
                $tipo_usuario_publicado_por = $fila_publicado_por['tipo'];

                $visibilidad_proyecto = $fila_publicado_por['mostrar_proyectos'];

                $direccionImagen= $fila['imagen'];
                $direccionArchivo = $fila['archivo'];
                $id_proyecto = $fila['proyecto'];


                #region publicado_para
                // + Si no una publicacion en un perfil de alguien, entonces el string de publicado_para estara vacio
                // ! RECORDAR QUE PUBLICADO PARA ES UN NUMERO DE FOREIGN KEY, REQUIERO SACAR EL NOMBRE DE USUARIO
                $id_publicado_para = $fila['publicado_para'];
                $query_publicado_para = mysqli_query($this->con, "SELECT id_usuario from usuarios WHERE id_usuario='$id_publicado_para'");
                // /$fila_publicado_para = mysqli_fetch_array($query_publicado_para);
                $checar_si = mysqli_num_rows($query_publicado_para);
                #endregion

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

                    $checar_si_hay_comentarios = mysqli_query($this->con, "SELECT * FROM comentarios WHERE (eliminado='no' AND publicacion_comentada='$id_publicacion')");
                    $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios);

                    if($numero_comentarios == 1)
                    {
                        $numero_comentarios = $numero_comentarios . " Comentario";
                    }
                    else
                    {
                        $numero_comentarios = $numero_comentarios . " Comentarios";
                    }

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
                            $dias = $intervalo->d. " día atrás";
                        }
                        //Mas de 1 dia
                        else 
                        {
                            $dias = $intervalo->d . " días atrás";
                        }

                        //1 mes
                        if($intervalo-> m == 1)
                        {
                            $mensaje_tiempo = $intervalo->m . " mes " . $dias;
                        }
                        //Mas de 1 mes
                        else
                        {
                            $mensaje_tiempo = $intervalo->m . " meses " . $dias;
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

                    // + Procesar si hay una imagen
                    if($direccionImagen != "")
                    {
                        $lista_imagenes_explode = explode("|", $direccionImagen);
                        $lista_imagenes_explode = array_filter($lista_imagenes_explode);

                        $divImagen = "<div class='contenedorImagenesPublicadas'>";
                        
                        foreach($lista_imagenes_explode as $imagen)
                        {
                            $divImagen .= "<div class='imagenPublicada'>
                                                <img src='$imagen'>
                                            </div>";                                                
                        }

                        $divImagen .= "</div>";
                    }
                    else
                    {
                        $divImagen = "";
                    }

                    if($direccionArchivo != "")
                    {
                        $lista_archivos_explode = explode("|", $direccionArchivo);
                        $lista_archivos_explode = array_filter($lista_archivos_explode);

                        $divArchivo = "<div class='contenedorArchivosPublicados'>
                                            <h4>Archivos</h4>";

                        foreach($lista_archivos_explode as $direccion_archivo)
                        {
                            $link_archivo = substr($direccion_archivo, strpos($direccion_archivo, "_") + 1);
                            $divArchivo .= "<div class='archivoPublicado'>
                                                <a href='" . $direccion_archivo . "' download='" . $link_archivo . "'><i class='fa-solid fa-file'></i> " . $link_archivo . "</a>
                                            </div>";
                        }

                        $divArchivo .= "</div>";

                    }
                    else
                    {
                        $divArchivo = "";
                    }

                    $hashtags = $fila['hashtags_publicacion'];
                    $lista_hashtags_explode = explode(",", $hashtags);
                    $lista_hashtags_explode = array_filter($lista_hashtags_explode);

                    if($hashtags != ",")
                    {
                        $div_hashtags = "<div class='contenedor_hashtags'>
                                            <h4>Hashtags</h4>";

                        foreach($lista_hashtags_explode as $hashtag)
                        {
                            $query_info_hashtag = mysqli_query($this->con, "SELECT hashtag FROM hashtags WHERE id_hashtag='$hashtag'");
                            $fila_info_hashtag = mysqli_fetch_array($query_info_hashtag);
                            $nombre_hashtag =  $fila_info_hashtag['hashtag'];
                            $nombre_sin_hashtag = str_replace("#", "", $fila_info_hashtag['hashtag']);

                            $div_hashtags .= "<a href='publication_hashtag.php?hashtag=". $nombre_sin_hashtag . "'>
                                                <div class='displayHashtag'>
                                                    " . $nombre_hashtag . "
                                                </div>
                                            </a>";
                        }
                        $div_hashtags .= "</div>";
                    }
                    else
                    {
                        $div_hashtags = "";
                    }

                    // + Mostrar si se publico un proyecto
                    if($id_proyecto != NULL)
                    {
                        $query_obtener_detalles_proyecto = mysqli_query($this->con, "SELECT * FROM proyectos WHERE id_proyecto='$id_proyecto'");
                        $fila_detalles_proyecto = mysqli_fetch_array($query_obtener_detalles_proyecto);
                        $nombre_proyecto = $fila_detalles_proyecto['nombre_proyecto'];
                        $link_proyecto = $fila_detalles_proyecto['link_proyecto'];
                        if($visibilidad_proyecto)
                        {
                            $mostrar_proyecto = true;
                        }
                        else
                        {
                            if($objeto_usuario_loggeado->esAmigo($id_publicado_por) && $objeto_usuario_loggeado->obtenerIDUsuario() == $id_publicado_por)
                            {
                                $mostrar_proyecto = true;
                            }
                            else
                            {
                                $mostrar_proyecto = false;
                            }
                        }
                    }
                    else
                    {
                        $mostrar_proyecto = false;
                    }

                    if($mostrar_proyecto)
                    {
                        if($id_usuario_loggeado == $id_publicado_por)
                        {
                            $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                                <h4>Proyecto incluido</h4>
                                                <div class='mostrarProyecto'>
                                                    <div class='nombre_proyecto_container'>
                                                        <p> " . $nombre_proyecto . "</p>
                                                    </div>
                                                    <div class='imagen_fondo_mostrar_proyecto'>
                                                        <img src='assets\images\icons\blockimino.png'>
                                                    </div>
                                                    <div class='contenedor_boton_ver_proyecto'>
                                                        <button class='boton_ver_proyecto btn btn-success' id='editar" . $nombre_proyecto . "'>Editar</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                $(document).ready(function(){
                                                    $('#editar$nombre_proyecto').on('click', function() {
                                                        window.open('block_arena.php?project=$nombre_proyecto');
                                                        
                                                    });
                                                });
                                            </script>";
                        }
                        else
                        {
                            $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                                <h4>Proyecto incluido</h4>
                                                <div class='mostrarProyecto'>
                                                    <div class='nombre_proyecto_container'>
                                                        <p> " . $nombre_proyecto . "</p>
                                                    </div>
                                                    <div class='imagen_fondo_mostrar_proyecto'>
                                                        <img src='assets\images\icons\blockimino.png'>
                                                    </div>
                                                    <div class='contenedor_boton_copiar_proyecto'>
                                                        <button class='boton_copiar_proyecto btn btn-info' id='copiar_proyecto" . $nombre_proyecto . "'>Copiar proyecto</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                            $(document).ready(function(){
                                                $('#copiar_proyecto$nombre_proyecto').on('click', function() {
                                                    bootbox.prompt('Introduce un nombre para el proyecto', function(result) {
                                                        if(result != null)
                                                        {
                                                            $.ajax({
                                                                url: 'includes/handlers/ajax_copy_project.php?id_usuario=$id_usuario_loggeado&link_proyecto=$link_proyecto',
                                                                type: 'POST',
                                                                data: {resultado:result},
                                                                success: function(data) {
                                                                    alert(data);
                                                                }
                                                            });
                                                        }
                                                    });
                                                });
                                            });
                                        </script>";
                        }                                        
                    }
                    else
                    {
                        $divProyecto = "";
                    }

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
                                $divImagen
                                <br>
                                <br>
                            </div>
                            $div_hashtags
                            $divProyecto
                            $divArchivo
                            <div class='OpcionesDePublicacion'>
                                &nbsp;
                                <span class='mostrar_ocultar_comentarios' onClick='javascript:toggle$id_publicacion()'>
                                    <i class='fa-solid fa-comment'></i>&nbsp;$numero_comentarios
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
                                    if(result == true)
                                    {
                                    // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                    $.post("includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", {resultado:result});                                
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
                                        $.post("includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", { resultado:result, razon:result});
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
            ?>
            <script>
                // + Script para borrar el comentario
                function confirmDelete(button) {
                    var id_comentario = button.getAttribute("data-id");
                    var es_propio = button.getAttribute("data-es-propio");
                    if (es_propio === "true")
                    {
                        // - resultado -> Sera el resultado de lo que el usuario clickeo, si fue "si" o "no"
                        bootbox.confirm("¿Estas seguro que quieres eliminar este comentario?", function(result) {
                            if(result == true)
                            {
                                // + Manda el id del comentario a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                $.post("includes/handlers/ajax_delete_comment.php?id_comentario=" + id_comentario + "&id_usuario=<?php echo $id_usuario_loggeado; ?>" + "&id_publicacion=<?php echo $id_publicacion; ?>", {resultado:result});
                                location.reload();
                            }
                        });
                    }
                    else 
                    {
                        bootbox.prompt({
                        title: "Por favor, escribe una razón para la eliminación del comentario:",
                        buttons: {
                            confirm: {
                            label: 'Aceptar',
                            className: 'btn-danger'
                            }
                        },
                        callback: function(result) {
                            if (result !== true && result !== '') {
                                $.post("includes/handlers/ajax_delete_comment.php?id_comentario=" + id_comentario + "&id_usuario=<?php echo $id_usuario_loggeado; ?>" + "&id_publicacion=<?php echo $id_publicacion; ?>", { resultado:result, razon:result});
                                location.reload();
                            } 
                            else 
                            {
                                alert("Debes ingresar una razón para eliminar el comentario.");
                            }
                        }
                        });
                    }
                }
            </script>
            <?php
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


        // + Se eliminara la notificacion si ya han pasado mas de 5 dias
        $fechaLimite = date('Y-m-d', strtotime('-5 days'));

        $query_eliminar_notificaciones = mysqli_query($this->con, "DELETE FROM notificaciones WHERE notificacion_para='$id_usuario_loggeado' AND link LIKE '%=$id_publicacion' AND abierta='si' AND fecha_notificacion <= '$fechaLimite'");


        // - Este string contendra todas las publicaciones
        $string_publicacion = "";
        // + Esta query va a obtener todas las publicaciones no borradas y las va a ordenar de forma descendente, es decir, las que se crearon primero, hasta abajo
        $query_info = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE borrado='no' AND id_publicacion='$id_publicacion'");

        // ! este if tambien es del scroll infinito
        // + Si existen publicaciones entonces:
        if(mysqli_num_rows($query_info) > 0)
        {
            $fila = mysqli_fetch_array($query_info);
            $objeto_usuario_loggeado = new Usuario($this->con, $id_usuario_loggeado);

            // + Guardamos en variables, las variables de la fila de la base de datos
            $titulo = $fila['titulo'];
            $cuerpo = $fila['cuerpo'];
            $cuerpo = nl2br($cuerpo);

            // ! publicado por, me regresara un numero, necesito acceder al usuario de ese publicado por
            // ! SE PUEDE HACER CON UN INNER JOIN, PERO VOY A SACAR EL NOMBRE DE USUARIO CON SU ID Y METER EL NOMBRE DE USUARIO AL OBJET: objeto_publicado_por
            $id_publicado_por = $fila['publicado_por'];
            $query_publicado_por = mysqli_query($this->con, "SELECT username, tipo, mostrar_proyectos from usuarios WHERE id_usuario='$id_publicado_por'");
            $fila_publicado_por = mysqli_fetch_array($query_publicado_por);
            // - Asignamos nombre de usuario a publicado por
            $usuario_publicado_por = $fila_publicado_por['username'];
            $fecha_publicado = $fila['fecha_publicado'];
            $tipo_usuario_publicado_por = $fila_publicado_por['tipo'];

            $visibilidad_proyecto = $fila_publicado_por['mostrar_proyectos'];

            $direccionImagen= $fila['imagen'];
            $direccionArchivo = $fila['archivo'];
            $id_proyecto = $fila['proyecto'];

            #region publicado_para
            // + Si no una publicacion en un perfil de alguien, entonces el string de publicado_para estara vacio
            // ! RECORDAR QUE PUBLICADO PARA ES UN NUMERO DE FOREIGN KEY, REQUIERO SACAR EL NOMBRE DE USUARIO
            $id_publicado_para = $fila['publicado_para'];
            $query_publicado_para = mysqli_query($this->con, "SELECT id_usuario from usuarios WHERE id_usuario='$id_publicado_para'");
            // /$fila_publicado_para = mysqli_fetch_array($query_publicado_para);
            $checar_si = mysqli_num_rows($query_publicado_para);

            if($checar_si == 0)
            {
                $usuario_publicado_para = "";
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
                $usuario_publicado_para = "para <a href='" . $publicado_para_N_usuario . "'>" . $publicado_para_nombre . "</a>";
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

            $tipo_usuario = $objeto_usuario_loggeado->obtenerTipoUsuario();
            // + Verifica si el usuario loggeado es amigo del que publico
            //! Aqui si necesito el nombre del que publico
            if($objeto_usuario_loggeado->esAmigo($id_publicado_por) || $objeto_usuario_loggeado->esSeguidor($id_publicado_por))
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

                $checar_si_hay_comentarios = mysqli_query($this->con, "SELECT * FROM comentarios WHERE (eliminado='no' AND publicacion_comentada='$id_publicacion')");
                $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios);

                if($numero_comentarios == 1)
                {
                    $numero_comentarios = $numero_comentarios . " Comentario";
                }
                else
                {
                    $numero_comentarios = $numero_comentarios . " Comentarios";
                }

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
                        $dias = $intervalo->d. " día atrás";
                    }
                    //Mas de 1 dia
                    else 
                    {
                        $dias = $intervalo->d . " días atrás";
                    }

                    //1 mes
                    if($intervalo-> m == 1)
                    {
                        $mensaje_tiempo = $intervalo->m . " mes " . $dias;
                    }
                    //Mas de 1 mes
                    else
                    {
                        $mensaje_tiempo = $intervalo->m . " meses " . $dias;
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
                // + Procesar si hay una imagen
                if($direccionImagen != "")
                {
                    $lista_imagenes_explode = explode("|", $direccionImagen);
                    $lista_imagenes_explode = array_filter($lista_imagenes_explode);

                    $divImagen = "<div class='contenedorImagenesPublicadas'>";
                    
                    foreach($lista_imagenes_explode as $imagen)
                    {
                        $divImagen .= "<div class='imagenPublicada'>
                                            <img src='$imagen'>
                                        </div>";                                                
                    }

                    $divImagen .= "</div>";
                }
                else
                {
                    $divImagen = "";
                }

                if($direccionArchivo != "")
                {
                    $lista_archivos_explode = explode("|", $direccionArchivo);
                    $lista_archivos_explode = array_filter($lista_archivos_explode);

                    $divArchivo = "<div class='contenedorArchivosPublicados'>
                                        <h4>Archivos</h4>";

                    foreach($lista_archivos_explode as $direccion_archivo)
                    {
                        $link_archivo = substr($direccion_archivo, strpos($direccion_archivo, "_") + 1);
                        $divArchivo .= "<div class='archivoPublicado'>
                                            <a href='" . $direccion_archivo . "' download='" . $link_archivo . "'><i class='fa-solid fa-file'></i> " . $link_archivo . "</a>
                                        </div>";
                    }

                    $divArchivo .= "</div>";

                }
                else
                {
                    $divArchivo = "";
                }

                $hashtags = $fila['hashtags_publicacion'];
                $lista_hashtags_explode = explode(",", $hashtags);
                $lista_hashtags_explode = array_filter($lista_hashtags_explode);

                if($hashtags != ",")
                {
                    $div_hashtags = "<div class='contenedor_hashtags'>
                                        <h4>Hashtags</h4>";

                    foreach($lista_hashtags_explode as $hashtag)
                    {
                        $query_info_hashtag = mysqli_query($this->con, "SELECT hashtag FROM hashtags WHERE id_hashtag='$hashtag'");
                        $fila_info_hashtag = mysqli_fetch_array($query_info_hashtag);
                        $nombre_hashtag =  $fila_info_hashtag['hashtag'];
                        $nombre_sin_hashtag = str_replace("#", "", $fila_info_hashtag['hashtag']);

                        $div_hashtags .= "<a href='publication_hashtag.php?hashtag=". $nombre_sin_hashtag . "'>
                                            <div class='displayHashtag'>
                                                " . $nombre_hashtag . "
                                            </div>
                                        </a>";
                    }
                    $div_hashtags .= "</div>";
                }
                else
                {
                    $div_hashtags = "";
                }

                // + Mostrar si se publico un proyecto
                if($id_proyecto != NULL)
                {
                    $query_obtener_detalles_proyecto = mysqli_query($this->con, "SELECT * FROM proyectos WHERE id_proyecto='$id_proyecto'");
                    $fila_detalles_proyecto = mysqli_fetch_array($query_obtener_detalles_proyecto);
                    $nombre_proyecto = $fila_detalles_proyecto['nombre_proyecto'];
                    $link_proyecto = $fila_detalles_proyecto['link_proyecto'];
                    if($visibilidad_proyecto)
                    {
                        $mostrar_proyecto = true;
                    }
                    else
                    {
                        if($objeto_usuario_loggeado->esAmigo($id_publicado_por) && $objeto_usuario_loggeado->obtenerIDUsuario() == $id_publicado_por)
                        {
                            $mostrar_proyecto = true;
                        }
                        else
                        {
                            $mostrar_proyecto = false;
                        }
                    }
                }
                else
                {
                    $mostrar_proyecto = false;
                }

                if($mostrar_proyecto)
                {
                    if($id_usuario_loggeado == $id_publicado_por)
                    {
                        $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                            <h4>Proyecto incluido</h4>
                                            <div class='mostrarProyecto'>
                                                <div class='nombre_proyecto_container'>
                                                    <p> " . $nombre_proyecto . "</p>
                                                </div>
                                                <div class='imagen_fondo_mostrar_proyecto'>
                                                    <img src='assets\images\icons\blockimino.png'>
                                                </div>
                                                <div class='contenedor_boton_ver_proyecto'>
                                                    <button class='boton_ver_proyecto btn btn-success' id='editar" . $nombre_proyecto . "'>Editar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            $(document).ready(function(){
                                                $('#editar$nombre_proyecto').on('click', function() {
                                                    window.open('block_arena.php?project=$nombre_proyecto');
                                                    
                                                });
                                            });
                                        </script>";
                    }
                    else
                    {
                        $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                            <h4>Proyecto incluido</h4>
                                            <div class='mostrarProyecto'>
                                                <div class='nombre_proyecto_container'>
                                                    <p> " . $nombre_proyecto . "</p>
                                                </div>
                                                <div class='imagen_fondo_mostrar_proyecto'>
                                                    <img src='assets\images\icons\blockimino.png'>
                                                </div>
                                                <div class='contenedor_boton_copiar_proyecto'>
                                                    <button class='boton_copiar_proyecto btn btn-info' id='copiar_proyecto" . $nombre_proyecto . "'>Copiar proyecto</button>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                        $(document).ready(function(){
                                            $('#copiar_proyecto$nombre_proyecto').on('click', function() {
                                                bootbox.prompt('Introduce un nombre para el proyecto', function(result) {
                                                    if(result != null)
                                                    {
                                                        $.ajax({
                                                            url: 'includes/handlers/ajax_copy_project.php?id_usuario=$id_usuario_loggeado&link_proyecto=$link_proyecto',
                                                            type: 'POST',
                                                            data: {resultado:result},
                                                            success: function(data) {
                                                                alert(data);
                                                            }
                                                        });
                                                    }
                                                });
                                            });
                                        });
                                    </script>";
                    }                                        
                }
                else
                {
                    $divProyecto = "";
                }

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
                            <a href='$usuario_publicado_por'> $nombre $apeP $apeM </a> $usuario_publicado_para &nbsp;&nbsp;&nbsp;&nbsp;$mensaje_tiempo
                            $boton_eliminar
                        </div>
                        <div id='titulo_publicacion' style='font-style: bold;'>
                            $titulo
                            <hr>
                        </div>
                        <div id='cuerpo_publicacion'>
                            $cuerpo
                            <br>
                            $divImagen
                            <br>
                            <br>
                        </div>
                        $div_hashtags
                        $divProyecto
                        $divArchivo
                        <div class='OpcionesDePublicacion'>
                            &nbsp;
                            <span class='mostrar_ocultar_comentarios' onClick='javascript:toggle$id_publicacion()'>
                                <i class='fa-solid fa-comment'></i>&nbsp;$numero_comentarios
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
                    // + Script para borrar el comentario
                    function confirmDelete(button) {
                        var id_comentario = button.getAttribute("data-id");
                        var es_propio = button.getAttribute("data-es-propio");
                        if (es_propio === "true")
                        {
                            // - resultado -> Sera el resultado de lo que el usuario clickeo, si fue "si" o "no"
                            bootbox.confirm("¿Estas seguro que quieres eliminar este comentario?", function(result) {
                                if(result == true)
                                {
                                    // + Manda el id del comentario a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                    $.post("includes/handlers/ajax_delete_comment.php?id_comentario=" + id_comentario + "&id_usuario=<?php echo $id_usuario_loggeado; ?>", {resultado:result});
                                    location.reload();
                                }
                            });
                        }
                        else 
                        {
                            bootbox.prompt({
                            title: "Por favor, escribe una razón para la eliminación del comentario:",
                            buttons: {
                                confirm: {
                                label: 'Aceptar',
                                className: 'btn-danger'
                                }
                            },
                            callback: function(result) {
                                if (result !== true && result !== '') {
                                    $.post("includes/handlers/ajax_delete_comment.php?id_comentario=" + id_comentario + "&id_usuario=<?php echo $id_usuario_loggeado; ?>", { resultado:result, razon:result});
                                    location.reload();
                                } 
                                else 
                                {
                                    alert("Debes ingresar una razón para eliminar el comentario.");
                                }
                            }
                            });
                        }
                    }
                </script>
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
                                    if(result == true)
                                    {
                                        // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la                                 
                                        $.post("includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", {resultado:result});
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
                                        $.post("includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", { resultado:result, razon:result});
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

    public function obtenerPublicacionEliminadaSolicitada($id_publicacion)
    {
        $id_usuario_loggeado = $this->objeto_usuario->obtenerIDUsuario();

        // - Este string contendra todas las publicaciones
        $string_publicacion = "";
        // + Esta query va a obtener todas las publicaciones no borradas y las va a ordenar de forma descendente, es decir, las que se crearon primero, hasta abajo
        $query_info = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE borrado='si' AND id_publicacion='$id_publicacion'");

        // ! este if tambien es del scroll infinito
        // + Si existen publicaciones entonces:
        if(mysqli_num_rows($query_info) > 0)
        {
            $fila = mysqli_fetch_array($query_info);
            $objeto_usuario_loggeado = new Usuario($this->con, $id_usuario_loggeado);

            // + Guardamos en variables, las variables de la fila de la base de datos
            $titulo = $fila['titulo'];
            $cuerpo = $fila['cuerpo'];
            $cuerpo = nl2br($cuerpo);

            // ! publicado por, me regresara un numero, necesito acceder al usuario de ese publicado por
            // ! SE PUEDE HACER CON UN INNER JOIN, PERO VOY A SACAR EL NOMBRE DE USUARIO CON SU ID Y METER EL NOMBRE DE USUARIO AL OBJET: objeto_publicado_por
            $id_publicado_por = $fila['publicado_por'];
            $query_publicado_por = mysqli_query($this->con, "SELECT username, tipo, mostrar_proyectos from usuarios WHERE id_usuario='$id_publicado_por'");
            $fila_publicado_por = mysqli_fetch_array($query_publicado_por);
            // - Asignamos nombre de usuario a publicado por
            $usuario_publicado_por = $fila_publicado_por['username'];
            $fecha_publicado = $fila['fecha_publicado'];
            $tipo_usuario_publicado_por = $fila_publicado_por['tipo'];

            $visibilidad_proyecto = $fila_publicado_por['mostrar_proyectos'];

            $direccionImagen= $fila['imagen'];
            $direccionArchivo = $fila['archivo'];
            $id_proyecto = $fila['proyecto'];

            #region publicado_para
            // + Si no una publicacion en un perfil de alguien, entonces el string de publicado_para estara vacio
            // ! RECORDAR QUE PUBLICADO PARA ES UN NUMERO DE FOREIGN KEY, REQUIERO SACAR EL NOMBRE DE USUARIO
            $id_publicado_para = $fila['publicado_para'];
            $query_publicado_para = mysqli_query($this->con, "SELECT id_usuario from usuarios WHERE id_usuario='$id_publicado_para'");
            // /$fila_publicado_para = mysqli_fetch_array($query_publicado_para);
            $checar_si = mysqli_num_rows($query_publicado_para);

            if($checar_si == 0)
            {
                $usuario_publicado_para = "";
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
                $usuario_publicado_para = "para <a href='" . $publicado_para_N_usuario . "'>" . $publicado_para_nombre . "</a>";
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

            $tipo_usuario = $objeto_usuario_loggeado->obtenerTipoUsuario();
            // + Verifica si el usuario loggeado es amigo del que publico
            //! Aqui si necesito el nombre del que publico
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

            $checar_si_hay_comentarios = mysqli_query($this->con, "SELECT * FROM comentarios WHERE (eliminado='si' AND publicacion_comentada='$id_publicacion')");
            $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios);

            if($numero_comentarios == 1)
            {
                $numero_comentarios = $numero_comentarios . " Comentario";
            }
            else
            {
                $numero_comentarios = $numero_comentarios . " Comentarios";
            }

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
                    $dias = $intervalo->d. " día atrás";
                }
                //Mas de 1 dia
                else 
                {
                    $dias = $intervalo->d . " días atrás";
                }

                //1 mes
                if($intervalo-> m == 1)
                {
                    $mensaje_tiempo = $intervalo->m . " mes " . $dias;
                }
                //Mas de 1 mes
                else
                {
                    $mensaje_tiempo = $intervalo->m . " meses " . $dias;
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
            // + Procesar si hay una imagen
            if($direccionImagen != "")
            {
                $lista_imagenes_explode = explode("|", $direccionImagen);
                $lista_imagenes_explode = array_filter($lista_imagenes_explode);

                $divImagen = "<div class='contenedorImagenesPublicadas'>";
                
                foreach($lista_imagenes_explode as $imagen)
                {
                    $divImagen .= "<div class='imagenPublicada'>
                                        <img src='$imagen'>
                                    </div>";                                                
                }

                $divImagen .= "</div>";
            }
            else
            {
                $divImagen = "";
            }

            if($direccionArchivo != "")
            {
                $lista_archivos_explode = explode("|", $direccionArchivo);
                $lista_archivos_explode = array_filter($lista_archivos_explode);

                $divArchivo = "<div class='contenedorArchivosPublicados'>
                                    <h4>Archivos</h4>";

                foreach($lista_archivos_explode as $direccion_archivo)
                {
                    $link_archivo = substr($direccion_archivo, strpos($direccion_archivo, "_") + 1);
                    $divArchivo .= "<div class='archivoPublicado'>
                                        <a href='" . $direccion_archivo . "' download='" . $link_archivo . "'><i class='fa-solid fa-file'></i> " . $link_archivo . "</a>
                                    </div>";
                }

                $divArchivo .= "</div>";

            }
            else
            {
                $divArchivo = "";
            }

            $hashtags = $fila['hashtags_publicacion'];
            $lista_hashtags_explode = explode(",", $hashtags);
            $lista_hashtags_explode = array_filter($lista_hashtags_explode);

            if($hashtags != ",")
            {
                $div_hashtags = "<div class='contenedor_hashtags'>
                                    <h4>Hashtags</h4>";

                foreach($lista_hashtags_explode as $hashtag)
                {
                    $query_info_hashtag = mysqli_query($this->con, "SELECT hashtag FROM hashtags WHERE id_hashtag='$hashtag'");
                    $fila_info_hashtag = mysqli_fetch_array($query_info_hashtag);
                    $nombre_hashtag =  $fila_info_hashtag['hashtag'];
                    $nombre_sin_hashtag = str_replace("#", "", $fila_info_hashtag['hashtag']);

                    $div_hashtags .= "<a href='publication_hashtag.php?hashtag=". $nombre_sin_hashtag . "'>
                                        <div class='displayHashtag'>
                                            " . $nombre_hashtag . "
                                        </div>
                                    </a>";
                }
                $div_hashtags .= "</div>";
            }
            else
            {
                $div_hashtags = "";
            }

            // + Mostrar si se publico un proyecto
            if($id_proyecto != NULL)
            {
                $query_obtener_detalles_proyecto = mysqli_query($this->con, "SELECT * FROM proyectos WHERE id_proyecto='$id_proyecto'");
                $fila_detalles_proyecto = mysqli_fetch_array($query_obtener_detalles_proyecto);
                $nombre_proyecto = $fila_detalles_proyecto['nombre_proyecto'];
                $link_proyecto = $fila_detalles_proyecto['link_proyecto'];
                if($visibilidad_proyecto)
                {
                    $mostrar_proyecto = true;
                }
                else
                {
                    if($objeto_usuario_loggeado->esAmigo($id_publicado_por) && $objeto_usuario_loggeado->obtenerIDUsuario() == $id_publicado_por)
                    {
                        $mostrar_proyecto = true;
                    }
                    else
                    {
                        $mostrar_proyecto = false;
                    }
                }
            }
            else
            {
                $mostrar_proyecto = false;
            }

            if($mostrar_proyecto)
            {
                if($id_usuario_loggeado == $id_publicado_por)
                {
                    $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                        <h4>Proyecto incluido</h4>
                                        <div class='mostrarProyecto'>
                                            <div class='nombre_proyecto_container'>
                                                <p> " . $nombre_proyecto . "</p>
                                            </div>
                                            <div class='imagen_fondo_mostrar_proyecto'>
                                                <img src='assets\images\icons\blockimino.png'>
                                            </div>
                                            <div class='contenedor_boton_ver_proyecto'>
                                                <button class='boton_ver_proyecto btn btn-success' id='editar" . $nombre_proyecto . "'>Editar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        $(document).ready(function(){
                                            $('#editar$nombre_proyecto').on('click', function() {
                                                window.open('block_arena.php?project=$nombre_proyecto');
                                                
                                            });
                                        });
                                    </script>";
                }
                else
                {
                    $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                        <h4>Proyecto incluido</h4>
                                        <div class='mostrarProyecto'>
                                            <div class='nombre_proyecto_container'>
                                                <p> " . $nombre_proyecto . "</p>
                                            </div>
                                            <div class='imagen_fondo_mostrar_proyecto'>
                                                <img src='assets\images\icons\blockimino.png'>
                                            </div>
                                            <div class='contenedor_boton_copiar_proyecto'>
                                                <button class='boton_copiar_proyecto btn btn-info' id='copiar_proyecto" . $nombre_proyecto . "'>Copiar proyecto</button>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                    $(document).ready(function(){
                                        $('#copiar_proyecto$nombre_proyecto').on('click', function() {
                                            bootbox.prompt('Introduce un nombre para el proyecto', function(result) {
                                                if(result != null)
                                                {
                                                    $.ajax({
                                                        url: 'includes/handlers/ajax_copy_project.php?id_usuario=$id_usuario_loggeado&link_proyecto=$link_proyecto',
                                                        type: 'POST',
                                                        data: {resultado:result},
                                                        success: function(data) {
                                                            alert(data);
                                                        }
                                                    });
                                                }
                                            });
                                        });
                                    });
                                </script>";
                }                                        
            }
            else
            {
                $divProyecto = "";
            }

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
                        <a href='$usuario_publicado_por'> $nombre $apeP $apeM </a> $usuario_publicado_para &nbsp;&nbsp;&nbsp;&nbsp;$mensaje_tiempo
                    </div>
                    <div id='titulo_publicacion' style='font-style: bold;'>
                        $titulo
                        <hr>
                    </div>
                    <div id='cuerpo_publicacion'>
                        $cuerpo
                        <br>
                        $divImagen
                        <br>
                        <br>
                    </div>
                    $div_hashtags
                    $divProyecto
                    $divArchivo
                    <div class='OpcionesDePublicacion'>
                        &nbsp;
                        <span class='mostrar_ocultar_comentarios' onClick='javascript:toggle$id_publicacion()'>
                            <i class='fa-solid fa-comment'></i>&nbsp;$numero_comentarios
                        </span>
                    </div>
                </div>
                <div class ='publicar_comentario' id='mostrarComentarios$id_publicacion' style='display:none;'>
                    <iframe src='deleted_comment_frame.php?id_publicacion=$id_publicacion' id='iframe_comentario' frameborder='0'></iframe>
                </div>
                <hr>";
            ?>
            <?php
        }
        else
        {
            echo "<p>No se encontro la publicación, puede que no haya sido eliminada o no exista!</p>";
            return;
        }
        echo $string_publicacion;
    }

    public function cargarPublicacionesGrupos ($info, $limite){
        // ! Esta seccion es del scroll infinito, checar como funciona
        // - Info es la variable $REQUEST mandaada a esta funcion
        // - Esta variable guardara la pagina actual
        // - $info['pagina'] accede a la variable pagina del request
        $pagina = $info['pagina'];
        // - Esta variable guardara el id del grupo
        $id_grupo = $info['id_grupo'];
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
        $query_info = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE borrado='no' AND id_grupo_publicacion='$id_grupo' ORDER BY id_publicacion DESC");

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
                $objeto_usuario_loggeado = new Usuario($this->con, $id_usuario_loggeado);

                // + Guardamos en variables, las variables de la fila de la base de datos
                $id_publicacion = $fila['id_publicacion'];
                $titulo = $fila['titulo'];
                $cuerpo = $fila['cuerpo'];
                $cuerpo = nl2br($cuerpo);

                // ! publicado por, me regresara un numero, necesito acceder al usuario de ese publicado por
                // ! SE PUEDE HACER CON UN INNER JOIN, PERO VOY A SACAR EL NOMBRE DE USUARIO CON SU ID Y METER EL NOMBRE DE USUARIO AL OBJET: objeto_publicado_por
                $id_publicado_por = $fila['publicado_por'];
                $query_publicado_por = mysqli_query($this->con, "SELECT username, tipo from usuarios WHERE id_usuario='$id_publicado_por'");
                $fila_publicado_por = mysqli_fetch_array($query_publicado_por);
                // - Asignamos nombre de usuario a publicado por
                $usuario_publicado_por = $fila_publicado_por['username'];
                $fecha_publicado = $fila['fecha_publicado'];
                $tipo_usuario_publicado_por = $fila_publicado_por['tipo'];

                $direccionImagen= $fila['imagen'];
                $direccionArchivo = $fila['archivo'];
                $id_proyecto = $fila['proyecto'];

                #region publicado_para
                // + Si no una publicacion en un perfil de alguien, entonces el string de publicado_para estara vacio
                // ! RECORDAR QUE PUBLICADO PARA ES UN NUMERO DE FOREIGN KEY, REQUIERO SACAR EL NOMBRE DE USUARIO
                $id_publicado_para = $fila['publicado_para'];
                $query_publicado_para = mysqli_query($this->con, "SELECT id_usuario, username from usuarios WHERE id_usuario='$id_publicado_para'");
                $checar_si = mysqli_num_rows($query_publicado_para);

                if($checar_si == 0)
                {
                    $usuario_publicado_para = "";
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
                    $usuario_publicado_para = "para <a href='" . $publicado_para_N_usuario . "'>" . $publicado_para_nombre . "</a>";
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

                $checar_si_hay_comentarios = mysqli_query($this->con, "SELECT * FROM comentarios WHERE (eliminado='no' AND publicacion_comentada='$id_publicacion')");
                $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios);

                if($numero_comentarios == 1)
                {
                    $numero_comentarios = $numero_comentarios . " Comentario";
                }
                else
                {
                    $numero_comentarios = $numero_comentarios . " Comentarios";
                }

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
                        $dias = $intervalo->d. " día atrás";
                    }
                    //Mas de 1 dia
                    else 
                    {
                        $dias = $intervalo->d . " días atrás";
                    }

                    //1 mes
                    if($intervalo-> m == 1)
                    {
                        $mensaje_tiempo = $intervalo->m . " mes " . $dias;
                    }
                    //Mas de 1 mes
                    else
                    {
                        $mensaje_tiempo = $intervalo->m . " meses " . $dias;
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

            // + Procesar si hay una imagen
            if($direccionImagen != "")
            {
                $lista_imagenes_explode = explode("|", $direccionImagen);
                $lista_imagenes_explode = array_filter($lista_imagenes_explode);

                $divImagen = "<div class='contenedorImagenesPublicadas'>";
                
                foreach($lista_imagenes_explode as $imagen)
                {
                    $divImagen .= "<div class='imagenPublicada'>
                                        <img src='../" . $imagen . "'>
                                    </div>";                                                
                }

                $divImagen .= "</div>";
            }
            else
            {
                $divImagen = "";
            }

            if($direccionArchivo != "")
            {
                $lista_archivos_explode = explode("|", $direccionArchivo);
                $lista_archivos_explode = array_filter($lista_archivos_explode);

                $divArchivo = "<div class='contenedorArchivosPublicados'>
                                    <h4>Archivos</h4>";

                foreach($lista_archivos_explode as $direccion_archivo)
                {
                    $link_archivo = substr($direccion_archivo, strpos($direccion_archivo, "_") + 1);
                    $divArchivo .= "<div class='archivoPublicado'>
                                        <a href='" . $direccion_archivo . "' download='" . $link_archivo . "'><i class='fa-solid fa-file'></i> " . $link_archivo . "</a>
                                    </div>";
                }

                $divArchivo .= "</div>";

            }
            else
            {
                $divArchivo = "";
            }

            // + Mostrar si se publico un proyecto
            if($id_proyecto != NULL)
            {
                $query_obtener_detalles_proyecto = mysqli_query($this->con, "SELECT * FROM proyectos WHERE id_proyecto='$id_proyecto'");
                $fila_detalles_proyecto = mysqli_fetch_array($query_obtener_detalles_proyecto);
                $nombre_proyecto = $fila_detalles_proyecto['nombre_proyecto'];
                $link_proyecto = $fila_detalles_proyecto['link_proyecto'];
                $mostrar_proyecto = true;
                // + Aqui no hace falta comprobar si son amigos, porque pertenecen a un mismo grupo
            }
            else
            {
                $mostrar_proyecto = false;
            }

            if($mostrar_proyecto)
            {
                if($id_usuario_loggeado == $id_publicado_por)
                {
                    $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                        <h4>Proyecto incluido</h4>
                                        <div class='mostrarProyecto'>
                                            <div class='nombre_proyecto_container'>
                                                <p> " . $nombre_proyecto . "</p>
                                            </div>
                                            <div class='imagen_fondo_mostrar_proyecto'>
                                                <img src='../assets/images/icons/blockimino.png'>
                                            </div>
                                            <div class='contenedor_boton_ver_proyecto'>
                                                <button class='boton_ver_proyecto btn btn-success' id='editar" . $nombre_proyecto . "'>Editar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        $(document).ready(function(){
                                            $('#editar$nombre_proyecto').on('click', function() {
                                                window.open('../block_arena.php?project=$nombre_proyecto');
                                                
                                            });
                                        });
                                    </script>";
                }
                else
                {
                    $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                        <h4>Proyecto incluido</h4>
                                        <div class='mostrarProyecto'>
                                            <div class='nombre_proyecto_container'>
                                                <p> " . $nombre_proyecto . "</p>
                                            </div>
                                            <div class='imagen_fondo_mostrar_proyecto'>
                                                <img src='assets\images\icons\blockimino.png'>
                                            </div>
                                            <div class='contenedor_boton_copiar_proyecto'>
                                                <button class='boton_copiar_proyecto btn btn-info' id='copiar_proyecto" . $nombre_proyecto . "'>Copiar proyecto</button>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                    $(document).ready(function(){
                                        $('#copiar_proyecto$nombre_proyecto').on('click', function() {
                                            bootbox.prompt('Introduce un nombre para el proyecto', function(result) {
                                                if(result != null)
                                                {
                                                    $.ajax({
                                                        url: '../includes/handlers/ajax_copy_project.php?id_usuario=$id_usuario_loggeado&link_proyecto=$link_proyecto',
                                                        type: 'POST',
                                                        data: {resultado:result},
                                                        success: function(data) {
                                                            alert(data);
                                                        }
                                                    });
                                                }
                                            });
                                        });
                                    });
                                </script>";
                }                                        
            }
            else
            {
                $divProyecto = "";
            }
                
                // + En este string se guardara cada publciacion y cada que se ejecute la carga de una, se emitira un echo, para mostrarla al usuario
                // + Tenemos divido por doto de perfil, un mensaje de cuanto tiempo ha pasado desde que se hizo la publicacion y el cuerpo de la publicacion
                $string_publicacion .= 
                // + onClick='javascript:toggle$id_publicacion' -> Cuando hagamos click, se ejecutara la funcion
                // + <div class ='publicar_comentario'> -> lo ocultamos con display:none, este cambiara como ya lo explicamos antes entre block y none
                    "<div class='publicacion'>
                        <div class='foto_perfil_publicacion'>
                            <img src='../$foto_perfil' width='50'>
                        </div>

                        <div class='publicado_por' style='color:#ACACAC;'>
                            <a href='$usuario_publicado_por'> $nombre $apeP $apeM </a> $usuario_publicado_para &nbsp;&nbsp;&nbsp;&nbsp;$mensaje_tiempo
                            $boton_eliminar
                        </div>
                        <div id='titulo_publicacion' style='font-style: bold;'>
                            $titulo
                            <hr>
                        </div>
                        <div id='cuerpo_publicacion'>
                            $cuerpo
                            <br>
                            $divImagen
                            <br>
                            <br>
                        </div>
                        $divProyecto
                        $divArchivo
                        <div class='OpcionesDePublicacion'>
                            &nbsp;
                            <span class='mostrar_ocultar_comentarios' onClick='javascript:toggle$id_publicacion()'>
                                <i class='fa-solid fa-comment'></i>&nbsp;$numero_comentarios
                            </span>
                            <iframe src='../like.php?id_publicacion=$id_publicacion' scrolling='no' id='iframe_likes'></iframe>
                        </div>
                    </div>
                    <div class ='publicar_comentario' id='mostrarComentarios$id_publicacion' style='display:none;'>
                        <iframe src='../comment_frame.php?id_publicacion=$id_publicacion' id='iframe_comentario' frameborder='0'></iframe>
                    </div>
                    <hr>";
                ?>         
                       
                <script>
                    // + Script de borrar publicacion
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
                                    if(result == true)
                                    {
                                        // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                        $.post("../includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", {resultado:result});
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
                                        $.post("../includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", { resultado:result, razon:result});
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
            ?>
            <script>
                // + Script para borrar el comentario
                function confirmDelete(button) {
                    var id_comentario = button.getAttribute("data-id");
                    var es_propio = button.getAttribute("data-es-propio");
                    if (es_propio === "true")
                    {
                        // - resultado -> Sera el resultado de lo que el usuario clickeo, si fue "si" o "no"
                        bootbox.confirm("¿Estas seguro que quieres eliminar este comentario?", function(result) {
                            if(result == true)
                            {
                                // + Manda el id del comentario a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                $.post("../includes/handlers/ajax_delete_comment.php?id_comentario=" + id_comentario + "&id_usuario=<?php echo $id_usuario_loggeado; ?>" + "&id_publicacion=<?php echo $id_publicacion; ?>", {resultado:result});    
                                location.reload();
                            }
                        });
                    }
                    else 
                    {
                        bootbox.prompt({
                        title: "Por favor, escribe una razón para la eliminación del comentario:",
                        buttons: {
                            confirm: {
                            label: 'Aceptar',
                            className: 'btn-danger'
                            }
                        },
                        callback: function(result) {
                            if (result !== true && result !== '') {
                                $.post("../includes/handlers/ajax_delete_comment.php?id_comentario=" + id_comentario + "&id_usuario=<?php echo $id_usuario_loggeado; ?>" + "&id_publicacion=<?php echo $id_publicacion; ?>", { resultado:result, razon:result});
                                location.reload();
                            } 
                            else 
                            {
                                alert("Debes ingresar una razón para eliminar el comentario.");
                            }
                        }
                        });
                    }
                }
            </script>
            <?php
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

    public function cargarPublicacionesHashtag ($info, $limite){
        // ! Esta seccion es del scroll infinito, checar como funciona
        // - Info es la variable $REQUEST mandaada a esta funcion
        // - Esta variable guardara la pagina actual
        // - $info['pagina'] accede a la variable pagina del request
        $pagina = $info['pagina'];
        // - Esta variable guardara el id del grupo
        $hashtag = $info['hashtag'];
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

        // ! este if tambien es del scroll infinito
        // + Si existen publicaciones entonces:
        $query_obtener_publicaciones_del_hashtag = mysqli_query($this->con, "SELECT publicaciones_con_este_hashtag FROM hashtags WHERE hashtag='#$hashtag'");
        if(mysqli_num_rows($query_obtener_publicaciones_del_hashtag) > 0)
        {

            $fila_publicaciones_hashtag = mysqli_fetch_array($query_obtener_publicaciones_del_hashtag);
            $publicaciones_con_este_hashtag = $fila_publicaciones_hashtag['publicaciones_con_este_hashtag'];
            $publicaciones_con_este_hashtag_explode = explode(",", $publicaciones_con_este_hashtag);
            $publicaciones_con_este_hashtag_explode = array_filter($publicaciones_con_este_hashtag_explode);

            // - Cuenta cuántas veces ha dado la vuelta el bucle
            $num_iteraciones = 0;
            // - Cuenta cuantos resultadoados hemos cargado
            $contador = 1;

            // + Mientras existan publicaciones en el arreglo, realizar el loop
            foreach($publicaciones_con_este_hashtag_explode as $publicacion_con_este_hashtag)
            {
                $objeto_usuario_loggeado = new Usuario($this->con, $id_usuario_loggeado);

                // + Esta query va a obtener todas las publicaciones no borradas y las va a ordenar de forma descendente, es decir, las que se crearon primero, hasta abajo
                $query_obtener_publicaciones_del_hashtag = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE (borrado='no' AND id_publicacion='$publicacion_con_este_hashtag') ORDER BY id_publicacion DESC");
                $fila = mysqli_fetch_array($query_obtener_publicaciones_del_hashtag);
                // + Guardamos en variables, las variables de la fila de la base de datos
                $id_publicacion = $fila['id_publicacion'];
                       
                $titulo = $fila['titulo'];
                $cuerpo = $fila['cuerpo'];
                $cuerpo = nl2br($cuerpo);

                // ! publicado por, me regresara un numero, necesito acceder al usuario de ese publicado por
                // ! SE PUEDE HACER CON UN INNER JOIN, PERO VOY A SACAR EL NOMBRE DE USUARIO CON SU ID Y METER EL NOMBRE DE USUARIO AL OBJET: objeto_publicado_por
                $id_publicado_por = $fila['publicado_por'];
                $query_publicado_por = mysqli_query($this->con, "SELECT username, tipo, mostrar_proyectos from usuarios WHERE id_usuario='$id_publicado_por'");
                $fila_publicado_por = mysqli_fetch_array($query_publicado_por);
                // - Asignamos nombre de usuario a publicado por
                $usuario_publicado_por = $fila_publicado_por['username'];
                $fecha_publicado = $fila['fecha_publicado'];
                $tipo_usuario_publicado_por = $fila_publicado_por['tipo'];

                $visibilidad_proyecto = $fila_publicado_por['mostrar_proyectos'];

                $direccionImagen = $fila['imagen'];
                $direccionArchivo = $fila['archivo'];
                $id_proyecto = $fila['proyecto'];

                #region publicado_para
                // + Si no una publicacion en un perfil de alguien, entonces el string de publicado_para estara vacio
                // ! RECORDAR QUE PUBLICADO PARA ES UN NUMERO DE FOREIGN KEY, REQUIERO SACAR EL NOMBRE DE USUARIO
                $id_publicado_para = $fila['publicado_para'];
                $query_publicado_para = mysqli_query($this->con, "SELECT id_usuario, username from usuarios WHERE id_usuario='$id_publicado_para'");
                $checar_si = mysqli_num_rows($query_publicado_para);

                if($checar_si == 0)
                {
                    $usuario_publicado_para = "";
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
                    $usuario_publicado_para = "para <a href='" . $publicado_para_N_usuario . "'>" . $publicado_para_nombre . "</a>";
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

                $checar_si_hay_comentarios = mysqli_query($this->con, "SELECT * FROM comentarios WHERE (eliminado='no' AND publicacion_comentada='$id_publicacion')");
                $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios);

                if($numero_comentarios == 1)
                {
                    $numero_comentarios = $numero_comentarios . " Comentario";
                }
                else
                {
                    $numero_comentarios = $numero_comentarios . " Comentarios";
                }

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
                        $dias = $intervalo->d. " día atrás";
                    }
                    //Mas de 1 dia
                    else 
                    {
                        $dias = $intervalo->d . " días atrás";
                    }

                    //1 mes
                    if($intervalo-> m == 1)
                    {
                        $mensaje_tiempo = $intervalo->m . " mes " . $dias;
                    }
                    //Mas de 1 mes
                    else
                    {
                        $mensaje_tiempo = $intervalo->m . " meses " . $dias;
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

                // + Procesar si hay una imagen
                if($direccionImagen != "")
                {
                    $lista_imagenes_explode = explode("|", $direccionImagen);
                    $lista_imagenes_explode = array_filter($lista_imagenes_explode);

                    $divImagen = "<div class='contenedorImagenesPublicadas'>";
                    
                    foreach($lista_imagenes_explode as $imagen)
                    {
                        $divImagen .= "<div class='imagenPublicada'>
                                            <img src='$imagen'>
                                        </div>";                                                
                    }

                    $divImagen .= "</div>";
                }
                else
                {
                    $divImagen = "";
                }

                if($direccionArchivo != "")
                {
                    $lista_archivos_explode = explode("|", $direccionArchivo);
                    $lista_archivos_explode = array_filter($lista_archivos_explode);

                    $divArchivo = "<div class='contenedorArchivosPublicados'>
                                        <h4>Archivos</h4>";

                    foreach($lista_archivos_explode as $direccion_archivo)
                    {
                        $link_archivo = substr($direccion_archivo, strpos($direccion_archivo, "_") + 1);
                        $divArchivo .= "<div class='archivoPublicado'>
                                            <a href='" . $direccion_archivo . "' download='" . $link_archivo . "'><i class='fa-solid fa-file'></i> " . $link_archivo . "</a>
                                        </div>";
                    }

                    $divArchivo .= "</div>";

                }
                else
                {
                    $divArchivo = "";
                }

                $hashtags = $fila['hashtags_publicacion'];
                $lista_hashtags_explode = explode(",", $hashtags);
                $lista_hashtags_explode = array_filter($lista_hashtags_explode);

                if($hashtags != ",")
                {
                    $div_hashtags = "<div class='contenedor_hashtags'>
                                        <h4>Hashtags</h4>";

                    foreach($lista_hashtags_explode as $hashtag)
                    {
                        $query_info_hashtag = mysqli_query($this->con, "SELECT hashtag FROM hashtags WHERE id_hashtag='$hashtag'");
                        $fila_info_hashtag = mysqli_fetch_array($query_info_hashtag);
                        $nombre_hashtag =  $fila_info_hashtag['hashtag'];
                        $nombre_sin_hashtag = str_replace("#", "", $fila_info_hashtag['hashtag']);

                        $div_hashtags .= "<a href='publication_hashtag.php?hashtag=". $nombre_sin_hashtag . "'>
                                            <div class='displayHashtag'>
                                                " . $nombre_hashtag . "
                                            </div>
                                        </a>";
                    }
                    $div_hashtags .= "</div>";
                }
                else
                {
                    $div_hashtags = "";
                }

                // + Mostrar si se publico un proyecto
                if($id_proyecto != NULL)
                {
                    $query_obtener_detalles_proyecto = mysqli_query($this->con, "SELECT * FROM proyectos WHERE id_proyecto='$id_proyecto'");
                    $fila_detalles_proyecto = mysqli_fetch_array($query_obtener_detalles_proyecto);
                    $nombre_proyecto = $fila_detalles_proyecto['nombre_proyecto'];
                    $link_proyecto = $fila_detalles_proyecto['link_proyecto'];
                    if($visibilidad_proyecto)
                    {
                        $mostrar_proyecto = true;
                    }
                    else
                    {
                        if($objeto_usuario_loggeado->esAmigo($id_publicado_por) && $objeto_usuario_loggeado->obtenerIDUsuario() == $id_publicado_por)
                        {
                            $mostrar_proyecto = true;
                        }
                        else
                        {
                            $mostrar_proyecto = false;
                        }
                    }
                }
                else
                {
                    $mostrar_proyecto = false;
                }

                if($mostrar_proyecto)
                {
                    if($id_usuario_loggeado == $id_publicado_por)
                    {
                        $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                            <h4>Proyecto incluido</h4>
                                            <div class='mostrarProyecto'>
                                                <div class='nombre_proyecto_container'>
                                                    <p> " . $nombre_proyecto . "</p>
                                                </div>
                                                <div class='imagen_fondo_mostrar_proyecto'>
                                                    <img src='assets\images\icons\blockimino.png'>
                                                </div>
                                                <div class='contenedor_boton_ver_proyecto'>
                                                    <button class='boton_ver_proyecto btn btn-success' id='editar" . $nombre_proyecto . "'>Editar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            $(document).ready(function(){
                                                $('#editar$nombre_proyecto').on('click', function() {
                                                    window.open('block_arena.php?project=$nombre_proyecto');
                                                    
                                                });
                                            });
                                        </script>";
                    }
                    else
                    {
                        $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                            <h4>Proyecto incluido</h4>
                                            <div class='mostrarProyecto'>
                                                <div class='nombre_proyecto_container'>
                                                    <p> " . $nombre_proyecto . "</p>
                                                </div>
                                                <div class='imagen_fondo_mostrar_proyecto'>
                                                    <img src='assets\images\icons\blockimino.png'>
                                                </div>
                                                <div class='contenedor_boton_copiar_proyecto'>
                                                    <button class='boton_copiar_proyecto btn btn-info' id='copiar_proyecto" . $nombre_proyecto . "'>Copiar proyecto</button>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                        $(document).ready(function(){
                                            $('#copiar_proyecto$nombre_proyecto').on('click', function() {
                                                bootbox.prompt('Introduce un nombre para el proyecto', function(result) {
                                                    if(result != null)
                                                    {
                                                        $.ajax({
                                                            url: 'includes/handlers/ajax_copy_project.php?id_usuario=$id_usuario_loggeado&link_proyecto=$link_proyecto',
                                                            type: 'POST',
                                                            data: {resultado:result},
                                                            success: function(data) {
                                                                alert(data);
                                                            }
                                                        });
                                                    }
                                                });
                                            });
                                        });
                                    </script>";
                    }                                        
                }
                else
                {
                    $divProyecto = "";
                }
                
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
                            <a href='$usuario_publicado_por'> $nombre $apeP $apeM </a> $usuario_publicado_para &nbsp;&nbsp;&nbsp;&nbsp;$mensaje_tiempo
                            $boton_eliminar
                        </div>
                        <div id='titulo_publicacion' style='font-style: bold;'>
                            $titulo
                            <hr>
                        </div>
                        <div id='cuerpo_publicacion'>
                            $cuerpo
                            <br>
                            $divImagen
                            <br>
                            <br>
                        </div>
                        $div_hashtags
                        $divProyecto
                        $divArchivo
                        <div class='OpcionesDePublicacion'>
                            &nbsp;
                            <span class='mostrar_ocultar_comentarios' onClick='javascript:toggle$id_publicacion()'>
                                <i class='fa-solid fa-comment'></i>&nbsp;$numero_comentarios
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
                    // + Script de borrar publicacion
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
                                    if(result == true)
                                    {
                                        // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                        $.post("../includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", {resultado:result});
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
                                        $.post("../includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", { resultado:result, razon:result});
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
            ?>
            <script>
                // + Script para borrar el comentario
                function confirmDelete(button) {
                    var id_comentario = button.getAttribute("data-id");
                    var es_propio = button.getAttribute("data-es-propio");
                    if (es_propio === "true")
                    {
                        // - resultado -> Sera el resultado de lo que el usuario clickeo, si fue "si" o "no"
                        bootbox.confirm("¿Estas seguro que quieres eliminar este comentario?", function(result) {
                            if(result == true)
                            {
                                // + Manda el id del comentario a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                $.post("../includes/handlers/ajax_delete_comment.php?id_comentario=" + id_comentario + "&id_usuario=<?php echo $id_usuario_loggeado; ?>" + "&id_publicacion=<?php echo $id_publicacion; ?>", {resultado:result});
                                location.reload();
                            }
                        });
                    }
                    else 
                    {
                        bootbox.prompt({
                        title: "Por favor, escribe una razón para la eliminación del comentario:",
                        buttons: {
                            confirm: {
                            label: 'Aceptar',
                            className: 'btn-danger'
                            }
                        },
                        callback: function(result) {
                            if (result !== true && result !== '') {
                                $.post("../includes/handlers/ajax_delete_comment.php?id_comentario=" + id_comentario + "&id_usuario=<?php echo $id_usuario_loggeado; ?>" + "&id_publicacion=<?php echo $id_publicacion; ?>", { resultado:result, razon:result});
                                location.reload();
                            } 
                            else 
                            {
                                alert("Debes ingresar una razón para eliminar el comentario.");
                            }
                        }
                        });
                    }
                }
            </script>
            <?php
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

    public function cargarPublicacionesTrend ($info, $limite){
        // ! Esta seccion es del scroll infinito, checar como funciona
        // - Info es la variable $REQUEST mandaada a esta funcion
        // - Esta variable guardara la pagina actual
        // - $info['pagina'] accede a la variable pagina del request
        $pagina = $info['pagina'];
        // - Esta variable guardara el id del grupo
        $trend = $info['trend'];
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

        // ! este if tambien es del scroll infinito
        // + Si existen publicaciones entonces:
        $query_obtener_publicaciones_del_trend = mysqli_query($this->con, "SELECT * FROM publicaciones WHERE titulo LIKE '%$trend%' OR cuerpo LIKE '%$trend%' ORDER BY id_publicacion DESC");
        if(mysqli_num_rows($query_obtener_publicaciones_del_trend) > 0)
        {
            // - Cuenta cuántas veces ha dado la vuelta el bucle
            $num_iteraciones = 0;
            // - Cuenta cuantos resultadoados hemos cargado
            $contador = 1;

            // + Mientras existan publicaciones en el arreglo, realizar el loop
            while($fila = mysqli_fetch_array($query_obtener_publicaciones_del_trend))
            {
                $objeto_usuario_loggeado = new Usuario($this->con, $id_usuario_loggeado);

                // + Esta query va a obtener todas las publicaciones no borradas y las va a ordenar de forma descendente, es decir, las que se crearon primero, hasta abajo
                // + Guardamos en variables, las variables de la fila de la base de datos
                $id_publicacion = $fila['id_publicacion'];
                       
                $titulo = $fila['titulo'];
                $cuerpo = $fila['cuerpo'];
                $cuerpo = nl2br($cuerpo);

                // ! publicado por, me regresara un numero, necesito acceder al usuario de ese publicado por
                // ! SE PUEDE HACER CON UN INNER JOIN, PERO VOY A SACAR EL NOMBRE DE USUARIO CON SU ID Y METER EL NOMBRE DE USUARIO AL OBJET: objeto_publicado_por
                $id_publicado_por = $fila['publicado_por'];
                $query_publicado_por = mysqli_query($this->con, "SELECT username, tipo, mostrar_proyectos from usuarios WHERE id_usuario='$id_publicado_por'");
                $fila_publicado_por = mysqli_fetch_array($query_publicado_por);
                // - Asignamos nombre de usuario a publicado por
                $usuario_publicado_por = $fila_publicado_por['username'];
                $fecha_publicado = $fila['fecha_publicado'];
                $tipo_usuario_publicado_por = $fila_publicado_por['tipo'];

                $visibilidad_proyecto = $fila_publicado_por['mostrar_proyectos'];

                $direccionImagen = $fila['imagen'];
                $direccionArchivo = $fila['archivo'];
                $id_proyecto = $fila['proyecto'];

                #region publicado_para
                // + Si no una publicacion en un perfil de alguien, entonces el string de publicado_para estara vacio
                // ! RECORDAR QUE PUBLICADO PARA ES UN NUMERO DE FOREIGN KEY, REQUIERO SACAR EL NOMBRE DE USUARIO
                $id_publicado_para = $fila['publicado_para'];
                $query_publicado_para = mysqli_query($this->con, "SELECT id_usuario, username from usuarios WHERE id_usuario='$id_publicado_para'");
                $checar_si = mysqli_num_rows($query_publicado_para);

                if($checar_si == 0)
                {
                    $usuario_publicado_para = "";
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
                    $usuario_publicado_para = "para <a href='" . $publicado_para_N_usuario . "'>" . $publicado_para_nombre . "</a>";
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

                $checar_si_hay_comentarios = mysqli_query($this->con, "SELECT * FROM comentarios WHERE (eliminado='no' AND publicacion_comentada='$id_publicacion')");
                $numero_comentarios = mysqli_num_rows($checar_si_hay_comentarios);

                if($numero_comentarios == 1)
                {
                    $numero_comentarios = $numero_comentarios . " Comentario";
                }
                else
                {
                    $numero_comentarios = $numero_comentarios . " Comentarios";
                }

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
                        $dias = $intervalo->d. " día atrás";
                    }
                    //Mas de 1 dia
                    else 
                    {
                        $dias = $intervalo->d . " días atrás";
                    }

                    //1 mes
                    if($intervalo-> m == 1)
                    {
                        $mensaje_tiempo = $intervalo->m . " mes " . $dias;
                    }
                    //Mas de 1 mes
                    else
                    {
                        $mensaje_tiempo = $intervalo->m . " meses " . $dias;
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

                // + Procesar si hay una imagen
                if($direccionImagen != "")
                {
                    $lista_imagenes_explode = explode("|", $direccionImagen);
                    $lista_imagenes_explode = array_filter($lista_imagenes_explode);

                    $divImagen = "<div class='contenedorImagenesPublicadas'>";
                    
                    foreach($lista_imagenes_explode as $imagen)
                    {
                        $divImagen .= "<div class='imagenPublicada'>
                                            <img src='$imagen'>
                                        </div>";                                                
                    }

                    $divImagen .= "</div>";
                }
                else
                {
                    $divImagen = "";
                }

                if($direccionArchivo != "")
                {
                    $lista_archivos_explode = explode("|", $direccionArchivo);
                    $lista_archivos_explode = array_filter($lista_archivos_explode);

                    $divArchivo = "<div class='contenedorArchivosPublicados'>
                                        <h4>Archivos</h4>";

                    foreach($lista_archivos_explode as $direccion_archivo)
                    {
                        $link_archivo = substr($direccion_archivo, strpos($direccion_archivo, "_") + 1);
                        $divArchivo .= "<div class='archivoPublicado'>
                                            <a href='" . $direccion_archivo . "' download='" . $link_archivo . "'><i class='fa-solid fa-file'></i> " . $link_archivo . "</a>
                                        </div>";
                    }

                    $divArchivo .= "</div>";

                }
                else
                {
                    $divArchivo = "";
                }

                $hashtags = $fila['hashtags_publicacion'];
                $lista_hashtags_explode = explode(",", $hashtags);
                $lista_hashtags_explode = array_filter($lista_hashtags_explode);

                if($hashtags != ",")
                {
                    $div_hashtags = "<div class='contenedor_hashtags'>
                                        <h4>Hashtags</h4>";

                    foreach($lista_hashtags_explode as $hashtag)
                    {
                        $query_info_hashtag = mysqli_query($this->con, "SELECT hashtag FROM hashtags WHERE id_hashtag='$hashtag'");
                        $fila_info_hashtag = mysqli_fetch_array($query_info_hashtag);
                        $nombre_hashtag =  $fila_info_hashtag['hashtag'];
                        $nombre_sin_hashtag = str_replace("#", "", $fila_info_hashtag['hashtag']);

                        $div_hashtags .= "<a href='publication_hashtag.php?hashtag=". $nombre_sin_hashtag . "'>
                                            <div class='displayHashtag'>
                                                " . $nombre_hashtag . "
                                            </div>
                                        </a>";
                    }
                    $div_hashtags .= "</div>";
                }
                else
                {
                    $div_hashtags = "";
                }

                // + Mostrar si se publico un proyecto
                if($id_proyecto != NULL)
                {
                    $query_obtener_detalles_proyecto = mysqli_query($this->con, "SELECT * FROM proyectos WHERE id_proyecto='$id_proyecto'");
                    $fila_detalles_proyecto = mysqli_fetch_array($query_obtener_detalles_proyecto);
                    $nombre_proyecto = $fila_detalles_proyecto['nombre_proyecto'];
                    $link_proyecto = $fila_detalles_proyecto['link_proyecto'];
                    if($visibilidad_proyecto)
                    {
                        $mostrar_proyecto = true;
                    }
                    else
                    {
                        if($objeto_usuario_loggeado->esAmigo($id_publicado_por) && $objeto_usuario_loggeado->obtenerIDUsuario() == $id_publicado_por)
                        {
                            $mostrar_proyecto = true;
                        }
                        else
                        {
                            $mostrar_proyecto = false;
                        }
                    }
                }
                else
                {
                    $mostrar_proyecto = false;
                }

                if($mostrar_proyecto)
                {
                    if($id_usuario_loggeado == $id_publicado_por)
                    {
                        $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                            <h4>Proyecto incluido</h4>
                                            <div class='mostrarProyecto'>
                                                <div class='nombre_proyecto_container'>
                                                    <p> " . $nombre_proyecto . "</p>
                                                </div>
                                                <div class='imagen_fondo_mostrar_proyecto'>
                                                    <img src='assets\images\icons\blockimino.png'>
                                                </div>
                                                <div class='contenedor_boton_ver_proyecto'>
                                                    <button class='boton_ver_proyecto btn btn-success' id='editar" . $nombre_proyecto . "'>Editar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            $(document).ready(function(){
                                                $('#editar$nombre_proyecto').on('click', function() {
                                                    window.open('block_arena.php?project=$nombre_proyecto');
                                                    
                                                });
                                            });
                                        </script>";
                    }
                    else
                    {
                        $divProyecto = "<div class='contenedor_mostrar_proyecto'>
                                            <h4>Proyecto incluido</h4>
                                            <div class='mostrarProyecto'>
                                                <div class='nombre_proyecto_container'>
                                                    <p> " . $nombre_proyecto . "</p>
                                                </div>
                                                <div class='imagen_fondo_mostrar_proyecto'>
                                                    <img src='assets\images\icons\blockimino.png'>
                                                </div>
                                                <div class='contenedor_boton_copiar_proyecto'>
                                                    <button class='boton_copiar_proyecto btn btn-info' id='copiar_proyecto" . $nombre_proyecto . "'>Copiar proyecto</button>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                        $(document).ready(function(){
                                            $('#copiar_proyecto$nombre_proyecto').on('click', function() {
                                                bootbox.prompt('Introduce un nombre para el proyecto', function(result) {
                                                    if(result != null)
                                                    {
                                                        $.ajax({
                                                            url: 'includes/handlers/ajax_copy_project.php?id_usuario=$id_usuario_loggeado&link_proyecto=$link_proyecto',
                                                            type: 'POST',
                                                            data: {resultado:result},
                                                            success: function(data) {
                                                                alert(data);
                                                            }
                                                        });
                                                    }
                                                });
                                            });
                                        });
                                    </script>";
                    }                                        
                }
                else
                {
                    $divProyecto = "";
                }
                
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
                            <a href='$usuario_publicado_por'> $nombre $apeP $apeM </a> $usuario_publicado_para &nbsp;&nbsp;&nbsp;&nbsp;$mensaje_tiempo
                            $boton_eliminar
                        </div>
                        <div id='titulo_publicacion' style='font-style: bold;'>
                            $titulo
                            <hr>
                        </div>
                        <div id='cuerpo_publicacion'>
                            $cuerpo
                            <br>
                            $divImagen
                            <br>
                            <br>
                        </div>
                        $div_hashtags
                        $divProyecto
                        $divArchivo
                        <div class='OpcionesDePublicacion'>
                            &nbsp;
                            <span class='mostrar_ocultar_comentarios' onClick='javascript:toggle$id_publicacion()'>
                                <i class='fa-solid fa-comment'></i>&nbsp;$numero_comentarios
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
                    // + Script de borrar publicacion
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
                                    if(result == true)
                                    {
                                        // + Manda el id de publicacion a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                        $.post("../includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", {resultado:result});
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
                                        $.post("../includes/handlers/ajax_delete_post.php?id_publicacion=<?php echo $id_publicacion; ?>&id_usuario=<?php echo $id_usuario_loggeado; ?>", { resultado:result, razon:result});
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
            ?>
            <script>
                // + Script para borrar el comentario
                function confirmDelete(button) {
                    var id_comentario = button.getAttribute("data-id");
                    var es_propio = button.getAttribute("data-es-propio");
                    if (es_propio === "true")
                    {
                        // - resultado -> Sera el resultado de lo que el usuario clickeo, si fue "si" o "no"
                        bootbox.confirm("¿Estas seguro que quieres eliminar este comentario?", function(result) {
                            if(result == true)
                            {
                                // + Manda el id del comentario a esta pagina -> el string es la pagina a la que lo manda y resultado:resultado, es lo que se manda, mandamos una variable resultado y la 
                                $.post("../includes/handlers/ajax_delete_comment.php?id_comentario=" + id_comentario + "&id_usuario=<?php echo $id_usuario_loggeado; ?>" + "&id_publicacion=<?php echo $id_publicacion; ?>", {resultado:result});
                                location.reload();
                            }
                        });
                    }
                    else 
                    {
                        bootbox.prompt({
                        title: "Por favor, escribe una razón para la eliminación del comentario:",
                        buttons: {
                            confirm: {
                            label: 'Aceptar',
                            className: 'btn-danger'
                            }
                        },
                        callback: function(result) {
                            if (result !== true && result !== '') {
                                $.post("../includes/handlers/ajax_delete_comment.php?id_comentario=" + id_comentario + "&id_usuario=<?php echo $id_usuario_loggeado; ?>" + "&id_publicacion=<?php echo $id_publicacion; ?>", { resultado:result, razon:result});
                                location.reload();
                            } 
                            else 
                            {
                                alert("Debes ingresar una razón para eliminar el comentario.");
                            }
                        }
                        });
                    }
                }
            </script>
            <?php
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
                    $string_publicacion .= "<input type='hidden' class='noMasPublicaciones' value='true'><p style='text-align: center;'> No hay más publicaciones por mostrar! </p>
                                            <div class='contenedor_no_mas_publicaciones_recomendar'>
                                                <a href='user_interests.php'>Gestionar mis intereses</a>
                                            </div>";            
            }
        }


        echo $string_publicacion;
    }
}

?>