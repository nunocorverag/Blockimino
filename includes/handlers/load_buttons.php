<?php
include("../../config/config.php");

?>
<div class="contenedor_botones_especiales">
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
</div>
