$(document).ready(function() {

    // + Aqui ira el codigo ajax que publicara el formulario por nosotros
    $('#enviar_publicacion_perfil').click(function(){
        $.ajax({
            type: "POST",
            url: "includes/handlers/ajax_submit_profile_post.php",
            data: $('form.publicacion_perfil').serialize(),
            success: function(msg) {
                $("#formulario_publicacion").modal('hide');
                location.reload();
            },
            error: function() {
                alert('Fallo');
            }
        });
    });
});

function obtenerUsuarios(valor, usuario)
{
    // + Este sera el archivo al que mandara la informacion
    // + Va a mandar una request a esta pagina, con los valores que tenemos entre {}
    // + Lo que retorne, lo va a anexar
    $.post("includes/handlers/ajax_friend_search.php", {busqueda:valor, id_usuario_loggeado:usuario}, function(info) {
        // + Va a poner el valor de este div con el que le enviemos de info
        $(".resultados").html(info);
    });
} 

// + Al llamar esta funcion le pasaremos el nombre de usuario y el tipo de dato que pasaremos (notificacion o mensaje)
function obtenerInformacionDesplegable(usuario, tipo)
{
    if($(".ventana_desplegable").css("height") == "0px")
    {
        var nombrePagina;

        if(tipo == 'notificacion')
        {
            nombrePagina = "ajax_load_notifications.php";
            $("span").remove("#notificacion_no_leida");
        }
        else if (tipo == 'mensaje')
        {
            nombrePagina = "ajax_load_messages.php";
            $("span").remove("#mensaje_no_leido");
        }

        // + Creamos una ajax request que va a recuperar los mensajes
        var ajaxreq = $.ajax({
            // + Hacemos una llamada ajax al nombre de la pagina
            url: "includes/handlers/" + nombrePagina,
            type: "POST",
            data: "pagina=1&id_usuario_loggeado=" + usuario,
            cache: false,

            // + Lo colocara en una ventana desplegable
            success: function (response)
            {
                $(".ventana_desplegable").html(response);
                $(".ventana_desplegable").css({"padding:" : "0px", "height" : "280px", "border" : "1px solid #DADADA"});
                $("#ventana_despliegue_de_datos").val(tipo);
            }
        });
    }
    // + Si la altura es 0, significa que la ventana desplegable no se ha desplegado
    else
    {
        $(".ventana_desplegable").html("");
        $(".ventana_desplegable").css({"padding:" : "0px", "height" : "0px", "border" : "none"});
    }
}