<script type="text/javascript">
function borrar_comentario(id_comentario) {
    // Preguntamos al usuario si esta seguro de eliminar el comentario
    if (confirm("¿Estás seguro de eliminar este comentario?")) {
        // Hacemos una petición AJAX para eliminar el comentario
        $.ajax({
            url: "eliminar_comentario.php",
            type: "POST",
            data: {id_comentario: id_comentario},
            success: function(response) {
                // Si la petición es exitosa, recargamos la página para mostrar los comentarios actualizados
                location.reload();
            }
        });
    }
}
</script>