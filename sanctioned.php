<?php
require 'config/config.php';
?>

<link rel="stylesheet" href="<?php echo dirname($_SERVER['PHP_SELF']) . '/assets/css/style.css'; ?>">

<?php

if(isset($_SESSION['id_usuario']))
{
    $id_usuario = $_SESSION['id_usuario'];
    $query_verificar_sanciones_usuario = mysqli_query($con, "SELECT * FROM sanciones WHERE id_usuario_sancionado='$id_usuario'");
    if(mysqli_num_rows($query_verificar_sanciones_usuario) > 0)
    {
        ?>
        <div class="cuerpo_principal">
            <div class="contenedor_display_sanciones_a_usuario">
            Usted ha sido sancionado por los siguientes motivos:
            <?php
            $tipo = "";
                while($fila = mysqli_fetch_array($query_verificar_sanciones_usuario))
                {
                    $tipo = $fila['tipo_sancion'];
                    $razon = $fila['razon_sancion'];
                    echo "<div class='displaySancionAUsuario'>
                            <p>Motivo: " . $fila['razon_sancion'] . "</p>
                        </div>";
                }
            ?>
                <div class="tipoSancionUsuario">
                    <p>Tipo de sancion: <?php echo $tipo ?></p>
                </div>
                <?php
                if($tipo == "temporal")
                {
                    $query_seleccionar_ultima_sancion_usuario = mysqli_query($con, "SELECT fecha_sancion FROM sanciones WHERE id_usuario_sancionado='$id_usuario' AND id_sancion = (SELECT MAX(id_sancion) FROM sanciones WHERE id_usuario_sancionado ='$id_usuario')");
                    $fila_ultima_sancion_usuario = mysqli_fetch_array($query_seleccionar_ultima_sancion_usuario);
                    ?>
                    <div class="divTiempoSancionUsuario">
                    <?php
                    // + fecha y hora actual
                    $tiempo_actual = date("Y-m-d H:i:s");
                    $tiempo_actual = date("Y-m-d H:i:s", strtotime($tiempo_actual . " -1 hour"));
                    $tiempo_actual = new DateTime($tiempo_actual);

                    // + fecha y hora restante, con el formato para que acepte operaciones de datetime
                    $fecha_sancion = DateTime::createFromFormat('Y-m-d H:i:s', $fila_ultima_sancion_usuario['fecha_sancion']);

                    // + calcular la diferencia entre las dos fechas
                    $tiempo_restante = $tiempo_actual->diff($fecha_sancion);

                    // guardar la diferencia en variables separadas
                    $dias = $tiempo_restante->days;
                    $horas = $tiempo_restante->h;
                    $minutos = $tiempo_restante->i;
                    ?>
                        <p>
                        Tiempo restante:
                        Dias: <?php echo $dias ?>
                        Horas: <?php echo $horas ?>
                        Minutos: <?php echo $minutos ?>
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
        header("Location: " . dirname($_SERVER['PHP_SELF']) . "/index.php");
    }
}

?>
