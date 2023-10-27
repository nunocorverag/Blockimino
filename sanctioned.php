<?php
require 'config/config.php';

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
 
// require "/home4/blockimi/public_html/PHPMailer/src/Exception.php";
// require "/home4/blockimi/public_html/PHPMailer/src/PHPMailer.php";
// require "/home4/blockimi/public_html/PHPMailer/src/SMTP.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/sanctioned_style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">

    <title>Bloqueado</title>
    <link rel="icon" href="assets/images/icons/blockimino.png">
</head>
<body>
    <?php
    function generarContraseña() {
        $longitud = 8;
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
        $contraseña = '';
        $tieneNumero = false;
        $tieneMayuscula = false;
        $tieneMinuscula = false;
        $tieneSimbolo = false;
    
        while (strlen($contraseña) < $longitud || !$tieneNumero || !$tieneMayuscula || !$tieneMinuscula || !$tieneSimbolo) {
            $caracter = $caracteres[rand(0, strlen($caracteres) - 1)];
            $contraseña .= $caracter;
    
            if (ctype_digit($caracter)) {
                $tieneNumero = true;
            } elseif (ctype_upper($caracter)) {
                $tieneMayuscula = true;
            } elseif (ctype_lower($caracter)) {
                $tieneMinuscula = true;
            } else {
                $tieneSimbolo = true;
            }
        }
    
        return $contraseña;
    }

    if(isset($_GET['username']))
    {
        $nombre_usuario = $_GET['username'];
        $query_obtener_datos_usuario = mysqli_query($con, "SELECT id_usuario, email FROM usuarios WHERE username='$nombre_usuario'");

        $msg = "";

        if(mysqli_num_rows($query_obtener_datos_usuario) == 1)
        {
            $fila_datos_usuario = mysqli_fetch_array($query_obtener_datos_usuario);
            $id_usuario = $fila_datos_usuario['id_usuario'];
            $email_usuario = $fila_datos_usuario['email'];

            if(isset($_POST['recuperar_contra']))
            {
                $nueva_contraseña = generarContraseña();
                $new_password_md5 = md5($nueva_contraseña);

                $query_actualizar_contra = mysqli_query($con, "UPDATE usuarios SET password='$new_password_md5' WHERE id_usuario='$id_usuario'");

                // + Configuracion de mail en mi dominio
                // $mail = new PHPMailer(true);

                // $mail->isSMTP();
                // $mail->Mailer = "mail";
                // $mail->SMTPSecure = "ssl";  
                // $mail->Timeout = 10; // Timeout de 10 segundos
                // $mail->Host = "mail.blockimino.com";  // STMP server 
                // $mail->Port = 587;
                // $mail->SMTPAuth = true;
                // $mail->Username = "noreply@blockimino.com";
                // $mail->Password = "Rq#7pW&fX9";
        
                // $mail->setFrom("noreply@blockimino.com");
        
                // $subject = "Usted ha solicitado un cambio de clave!";
                // $message .= "Nueva clave: ".$nueva_contraseña;
        
                // $mail->addAddress($email_usuario);
                // $mail->Subject = $subject;
                // $mail->Body = $message;
                // $mail->send();
                // $mail->clearAddresses();

                $msg =  "Nueva clave enviada al correo electronico";
            }

            $query_verificar_bloqueos_usuario = mysqli_query($con, "SELECT * FROM sanciones WHERE id_usuario_sancionado='$id_usuario' AND sancion_eliminada='no' AND (tipo_sancion='temporal_login_fallido' OR tipo_sancion='permanente_login_fallido')");
            if(mysqli_num_rows($query_verificar_bloqueos_usuario) > 0)
            {
                $sanciones_intentos_fallidos = true;
                ?>
                <div class="cuerpo_principal">
                    <div class="contenedor_display_sanciones_a_usuario">
                        <h1>Su cuenta ha sido bloqueada por exceso de intentos de inicio de sesion</h1>
                        <div class="contenedor_bloque_sancion">
                            <?php
                                $fila = mysqli_fetch_array($query_verificar_bloqueos_usuario);
                                ?>
                                <div class="contenedor_motivo_sancion">
                                    <?php
                                    $tipo = $fila['tipo_sancion'];
                                    $razon = $fila['razon_sancion'];
                                    $id_publicacion_sancion = $fila['id_publicacion_sancion'];
                                    $id_comentario_sancion = $fila['id_comentario_sancion'];
                                    echo "<div class='displaySancionAUsuario'>
                                            <p>Motivo: " . $fila['razon_sancion'] . "</p>";
                                    echo "</div>";
                                ?>
                                </div>
                        </div>
                        <br><br>
                        <div class='divInferiorDisplaySancion'>
                                <div class="tipoSancionUsuario">
                                    <p>Bloqueado : 
                                        <?php 
                                            if($tipo == "temporal_login_fallido")
                                            {
                                                echo "Temporalmente";
                                            }
                                            else if($tipo == "permanente_login_fallido")
                                            {
                                                echo "Permenentemente";
                                            }
                                        ?>
                                    </p>
                                </div>
                            <?php
                                if($tipo == "temporal_login_fallido")
                                {
                                    ?>
                                    <div class="divTiempoSancionUsuario">
                                        <?php
                                        // + fecha y hora actual
                                        $tiempo_actual = date("Y-m-d H:i:s");
                                        $tiempo_actual = new DateTime($tiempo_actual);
                    
                                        // + fecha y hora restante, con el formato para que acepte operaciones de datetime
                                        $fecha_sancion = DateTime::createFromFormat('Y-m-d H:i:s', $fila['fecha_sancion']);
                    
                                        // + calcular la diferencia entre las dos fechas
                                        $tiempo_restante = $tiempo_actual->diff($fecha_sancion);
                    
                                        // guardar la diferencia en variables separadas
                                        $dias = $tiempo_restante->days;
                                        $horas = $tiempo_restante->h;
                                        $minutos = $tiempo_restante->i;
                                        $segundos = $tiempo_restante->s;

                                        ?>
                                            <p>
                                            Tiempo restante:
                                            Dias: <?php echo $dias ?>
                                            Horas: <?php echo $horas ?>
                                            Minutos: <?php echo $minutos ?>
                                            Segundos: <?php echo $segundos ?>

                                            </p>
                                    </div>
                                    <?php
                                }
                                
                                ?>
                            <div class="contenedor_recuperar_contra">
                                <form action="sanctioned.php?username=<?php echo $nombre_usuario?>" method="POST" name="recuperar_contra">
                                    <input type="submit" class="boton_recuperar_contra" value="Recuperar clave" name="recuperar_contra">
                                </form>
                            </div>
                        </div>
                        <?php
                        if($msg != "")
                        {
                            ?>
                            <br>
                            <div class="alert alert-success" style="text-align:center; width: 50%; margin: 0 auto;">
                                <p>
                                    <?php echo $msg ?>
                                </p>
                            </div>
                            <?php
                        }

                        ?>
                    </div>
                </div>
                <?php
            }
            else
            {
                $sanciones_intentos_fallidos = false;
            }
        
            $query_verificar_sanciones_usuario = mysqli_query($con, "SELECT * FROM sanciones WHERE id_usuario_sancionado='$id_usuario' AND sancion_eliminada='no' AND tipo_sancion NOT IN ('temporal_login_fallido', 'permanente_login_fallido')");
            if(mysqli_num_rows($query_verificar_sanciones_usuario) > 0)
            {
                $sanciones_a_usuario = true;
                ?>
                <div class="cuerpo_principal">
                    <div class="contenedor_display_sanciones_a_usuario">
                        <h1>Usted ha sido sancionado por los siguientes motivos:</h1>
                        <div class="contenedor_bloque_sancion">
                            <?php
                                $tipo = "";
                                    while($fila = mysqli_fetch_array($query_verificar_sanciones_usuario))
                                    {
                                        ?>
                                        <div class="contenedor_motivo_sancion">
                                            <?php
                                            $tipo = $fila['tipo_sancion'];
                                            $razon = $fila['razon_sancion'];
                                            $id_publicacion_sancion = $fila['id_publicacion_sancion'];
                                            $id_comentario_sancion = $fila['id_comentario_sancion'];
                                            echo "<div class='displaySancionAUsuario'>
                                                    <p>Motivo: " . $fila['razon_sancion'] . "</p>";

                                            if($id_publicacion_sancion != NULL)
                                            {
                                                $query_seleccionar_publicacion_sancionada = mysqli_query($con, "SELECT * FROM publicaciones WHERE id_publicacion='$id_publicacion_sancion'");
                                                $fila_info_publicacion = mysqli_fetch_array($query_seleccionar_publicacion_sancionada);
                                                $titulo_publicacion = $fila_info_publicacion['titulo'];
                                                $cuerpo_publicacion = $fila_info_publicacion['cuerpo'];

                                                echo "<div class='displayPublicacionSancion'>
                                                        <p>Contenido publicacion: </p>
                                                        <p>Titulo: $titulo_publicacion </p>
                                                        <p>Cuerpo: $cuerpo_publicacion </p>
                                                    </div>";


                                            }
                                            else if($id_comentario_sancion != NULL)
                                            {
                                                $query_seleccionar_comentario_sancionado = mysqli_query($con, "SELECT * FROM comentarios WHERE id_comentario='$id_comentario_sancion'");
                                                $fila_info_comentario = mysqli_fetch_array($query_seleccionar_comentario_sancionado);
                                                $cuerpo_comentario = $fila_info_comentario['cuerpo_comentario'];

                                                echo "<div class='displayComentario'>
                                                        <p>Comentario: $cuerpo_comentario </p>
                                                    </div>";
                                            }
                                            echo "</div>";
                                        ?>
                                        </div>
                                        <?php

                                        
                                    }
                            ?>
                        </div>
                        <br><br>
                        <div class='divInferiorDisplaySancion'>
                                <div class="tipoSancionUsuario">
                                    <p>Tipo de sancion: <?php echo $tipo ?></p>
                                </div>
                            <?php
                                if($tipo == "temporal")
                                {
                                    $query_seleccionar_ultima_sancion_usuario = mysqli_query($con, "SELECT fecha_sancion FROM sanciones WHERE id_usuario_sancionado='$id_usuario' AND id_sancion = (SELECT MAX(id_sancion) FROM sanciones WHERE id_usuario_sancionado ='$id_usuario' AND sancion_eliminada='no')");
                                    $fila_ultima_sancion_usuario = mysqli_fetch_array($query_seleccionar_ultima_sancion_usuario);
                                    ?>
                                    <div class="divTiempoSancionUsuario">
                                        <?php
                                        // + fecha y hora actual
                                        $tiempo_actual = date("Y-m-d H:i:s");
                                        $tiempo_actual = new DateTime($tiempo_actual);
                    
                                        // + fecha y hora restante, con el formato para que acepte operaciones de datetime
                                        $fecha_sancion = DateTime::createFromFormat('Y-m-d H:i:s', $fila_ultima_sancion_usuario['fecha_sancion']);
                    
                                        // + calcular la diferencia entre las dos fechas
                                        $tiempo_restante = $tiempo_actual->diff($fecha_sancion);
                    
                                        // guardar la diferencia en variables separadas
                                        $dias = $tiempo_restante->days;
                                        $horas = $tiempo_restante->h;
                                        $minutos = $tiempo_restante->i;
                                        $segundos = $tiempo_restante->s;

                                        ?>
                                            <p>
                                            Tiempo restante:
                                            Dias: <?php echo $dias ?>
                                            Horas: <?php echo $horas ?>
                                            Minutos: <?php echo $minutos ?>
                                            Segundos: <?php echo $segundos ?>

                                            </p>
                                    </div>
                                    <?php
                                }
                                ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            else
            {
                $sanciones_a_usuario = false;
            }
            if(($sanciones_intentos_fallidos == false) && ($sanciones_a_usuario == false))
            {
                header("Location: index.php");
            }
        }
        else
        {
            header("Location: index.php");
        }
    }
    else
    {
        header("Location: index.php");
    }

    ?>
</body>
</html>
