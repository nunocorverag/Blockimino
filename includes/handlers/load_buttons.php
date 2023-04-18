<?php
include("../../config/config.php");

?>
<div class="contenedor_botones_especiales">
    <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/../../help_manage.php">
        <button>Peticiones de ayuda</button>
    </a>
    <br>
    <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/../../sanctions.php">
        <button>Sanciones</button>
    </a>
    <br>
    <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/../../deleted_posts_log.php">
        <button>Publicaciones Eliminadas</button>
    </a>
    <br>
    <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/../../deleted_comments_log.php">
        <button>Comentarios Eliminados</button>
    </a>

    <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/../../sanctions_log.php">
        <button>Log de Sanciones</button>
    </a>

    <?php
    $id_usuario_loggeado = $_REQUEST['id_usuario_loggeado'];
    $query_verificar_que_usuario__sea_admin = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='$id_usuario_loggeado' AND tipo='administrador'");
    if(mysqli_num_rows($query_verificar_que_usuario__sea_admin) == 1)
    {
        ?>
        <a href="<?php echo dirname($_SERVER['PHP_SELF']) ?>/../../manage_users.php">
            <button>Administrar Usuarios</button>
        </a>
        <?php
    }
    ?>
</div>
