function getBaseUrl() 
{
    // !NOTA IMPORTANTE, EN EL HOST, DEBO DE DEJAR EL COMENTARIO DE ABAJO, EN CASO DE LOCALHOST LO VOY A DEJAR COMO BLOCKIMINO
    // var baseUrl = window.location.protocol + "//" + window.location.host;
    // !NOTA EN EL LOCAL USARE ESTE DE MIENTRAS
    var baseUrl = window.location.protocol + "//" + window.location.host + "//" + "blockimino";
    // baseUrl = "blockimino";
    return baseUrl;
}

$(document).ready(function() {

    $('#input_busqueda_texto').focus(function(){
        // + Si la ventana tiene un largo de 800 o mas, ESTO VA A HACER QUE SE HAGA MAS GRANDE LA BARRA DE BUSQUEDA
        if(window.matchMedia( "(min-width: 800px)" ).matches)
        {
            $(this).animate({width: '250px'}, 500);
        }
    });

    // + Esto es para enviar el formulario a la hora de hacer click en el icono de busqueda
    $('.contenedor_boton').on('click', function() {
        document.formulario_busqueda.submit();
    });

    // + Esto es para enviar el formulario de buscar miembros a la hora de hacer click en el icono de busqueda
    $('.contenedor_boton_busqueda_invitacion').on('click', function() {
        document.formulario_busqueda_invitacion.submit();
    });

    // + Aqui ira el codigo ajax que publicara el formulario de publicacion por nosotros
    $('#enviar_publicacion_perfil').click(function() {
        var form_data = new FormData($('form.publicacion_perfil')[0]);
    
        $.ajax({
            type: "POST",
            url: "includes/handlers/ajax_submit_profile_post.php",
            data: form_data,
            processData: false,
            contentType: false,
            success: function(msg) {
                $("#formulario_publicacion").modal('hide');
                location.reload();
            },
            error: function(xhr, textStatus, errorThrown) {
                var error_message = xhr.responseText;
                alert(error_message);
            }
        });
    });

    // + Aqui ira el codigo ajax que publicara el formulario de grupo por nosotros
    $('#enviar_publicacion_grupo').click(function() {
        var form_data = new FormData($('form.publicacion_grupo')[0]);
        
        $.ajax({
            type: "POST",
            url: "../includes/handlers/ajax_submit_group_post.php",
            data: form_data,
            processData: false,
            contentType: false,
            success: function(msg) {
                $("#publicacion_grupo").modal('hide');
                location.reload();
            },
            error: function(xhr, textStatus, errorThrown) {
                var error_message = xhr.responseText;
                alert(error_message);
            }
        });
    });
    
});

$(document).click(function(click){
    // + click.target es la cosa que hicimos click y class es la clase de ese target, si hacemos click en un div, obtenemos ese div
    if(click.target.class != "resultados_busqueda" && click.target.id != "input_busqueda_texto")
    {
        $(".resultados_busqueda").html("");
        $(".resultados_busqueda_pie_pagina").html("");
        $(".resultados_busqueda_pie_pagina").toggleClass("resultados_busqueda_pie_pagina_vacios");
        $(".resultados_busqueda_pie_pagina").toggleClass("resultados_busqueda_pie_pagina");
    }
    // + e.target es la cosa que hicimos click y class es la clase de ese target, si hacemos click en un div, obtenemos ese div
    if(click.target.class != "ventana_desplegable")
    {
        $(".ventana_desplegable").html("");
        $(".ventana_desplegable").css({"padding" : "0px", "height" : "0px", "border" : "none"});
    }
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
        else if(tipo == "botones")
        {
            nombrePagina = "ajax_load_buttons.php";
        }
        else if(tipo == "ocp_proyectos")
        {
            nombrePagina = "ajax_load_opc_projects.php";
        }

        // + Creamos una ajax request que va a recuperar los mensajes o notificaciones
        var ajaxreq = $.ajax({
            // + Hacemos una llamada ajax al nombre de la pagina
            url: getBaseUrl() + "/includes/handlers/" + nombrePagina,
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

function obtenerLiveSearchUsuarios(valor, usuario)
{
    // + Va a mandar la informacion a esta pagina, la primera va a ser la busqueda y la segunda el usuario loggeado
    // + Todo lo que retorne, va a ser guardado en info
    $.post(getBaseUrl() + "/includes/handlers/ajax_search.php", {query:valor, id_usuario_loggeado:usuario}, function(info)
    {
        if($(".resultados_busqueda_pie_pagina_vacios")[0])
        {
            // + Si esta escondido, lo muestra, si esta mostrandolo, lo esconde
            $(".resultados_busqueda_pie_pagina_vacios").toggleClass("resultados_busqueda_pie_pagina");
            $(".resultados_busqueda_pie_pagina_vacios").toggleClass("resultados_busqueda_pie_pagina_vacios");
        }

        // ! este lo tenga que cambiar no se
        $(".resultados_busqueda").html(info);
        if (valor.startsWith("#")) {
            valor_sin_hashtag = valor.substring(1); // elimina el primer caracter (el hashtag) del valor
            $(".resultados_busqueda_pie_pagina").html("<a href=' " + getBaseUrl() + "/search.php?query=" + valor_sin_hashtag + "&tipo=hashtag'>Ver todos los resultados</a>");
        }
        else
        {
            $(".resultados_busqueda_pie_pagina").html("<a href=' " + getBaseUrl() + "/search.php?query=" + valor + "&tipo=usuarios_nombres_y_grupos'>Ver todos los resultados</a>");
        }

        if(valor == "")
        {
            $(".resultados_busqueda_pie_pagina").html("");
            $(".resultados_busqueda_pie_pagina").toggleClass("resultados_busqueda_pie_pagina_vacios");
            $(".resultados_busqueda_pie_pagina").toggleClass("resultados_busqueda_pie_pagina");
        }
    });
}

function obtenerLiveSearchInvitarUsuarios(valor, usuario, grupo)
{
    // + Va a mandar la informacion a esta pagina, la primera va a ser la busqueda y la segunda el usuario loggeado
    // + Todo lo que retorne, va a ser guardado en info
    $.post("../../includes/handlers/ajax_invite_members_search.php", {query:valor, id_usuario_loggeado:usuario, id_grupo:grupo}, function(info)
    {
        if($(".resultados_busqueda_invitar_miembros_pie_pagina_vacios")[0])
        {
            // + Si esta escondido, lo muestra, si esta mostrandolo, lo esconde
            $(".resultados_busqueda_invitar_miembros_pie_pagina_vacios").toggleClass("resultados_busqueda_invitar_miembros_pie_pagina");
            $(".resultados_busqueda_invitar_miembros_pie_pagina_vacios").toggleClass("resultados_busqueda_invitar_miembros_pie_pagina_vacios");
        }

        // ! este lo tenga que cambiar no se
        $(".resultados_busqueda_invitar_miembros").html(info);
        $(".resultados_busqueda_invitar_miembros_pie_pagina").html("<a href='invite?query=" + valor + "'>Ver todos los resultados</a>");

        if(valor == "")
        {
            $(".resultados_busqueda_invitar_miembros_pie_pagina").html("");
            $(".resultados_busqueda_invitar_miembros_pie_pagina").toggleClass("resultados_busqueda_invitar_miembros_pie_pagina_vacios");
            $(".resultados_busqueda_invitar_miembros_pie_pagina").toggleClass("resultados_busqueda_invitar_miembros_pie_pagina");
        }
    });
}

function invitarUsuario(id_usuario_loggeado, id_usuario_invitado, id_grupo) {
    $.post("../../includes/handlers/ajax_invite_members.php", { id_usuario_loggeado:id_usuario_loggeado, id_usuario_invitado:id_usuario_invitado, id_grupo:id_grupo }, function(response) {
        // Reload page
        location.reload();
        alert("Se ha enviado la invitacion");
    });
}