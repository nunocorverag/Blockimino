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

    // $("form#publicacion_perfil").submit(function(e){
    //     alert("eeeea");
    //     e.preventDefault();
    //     var formData = new FormData(this);

    //     $.ajax({
    //         type: "POST",
    //         url: "includes/handlers/ajax_submit_profile_post.php",
    //         data: formData,
    //         success: function(data) {
    //             alert(data);
    //         },
    //         error: function() {
    //             alert('Fallo al realizar la publicación');
    //         },
    //         cache: false,
    //         processData: false,
    //         contentType: false,
    //     });
    // });

    // + Aqui ira el codigo ajax que publicara el formulario por nosotros
    $('#enviar_publicacion_perfil').click(function() {
        var form_data = new FormData($('form.publicacion_perfil')[0]);
    
        $.ajax({
            type: "POST",
            url: "includes/handlers/ajax_submit_profile_post.php",
            data: form_data,
            processData: false,
            contentType: false,
            success: function(data) {
                alert(data);
                $("#formulario_publicacion").modal('hide');
                location.reload();
            },
            error: function() {
                alert('Fallo al realizar la publicación');
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
        $(".ventana_desplegable").css({"padding" : "0px", "height" : "0px"});
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

function obtenerLiveSearchUsuarios(valor, usuario)
{
    // + Va a mandar la informacion a esta pagina, la primera va a ser la busqueda y la segunda el usuario loggeado
    // + Todo lo que retorne, va a ser guardado en info
    $.post("includes/handlers/ajax_search.php", {query:valor, id_usuario_loggeado:usuario}, function(info)
    {
        if($(".resultados_busqueda_pie_pagina_vacios")[0])
        {
            // + Si esta escondido, lo muestra, si esta mostrandolo, lo esconde
            $(".resultados_busqueda_pie_pagina_vacios").toggleClass("resultados_busqueda_pie_pagina");
            $(".resultados_busqueda_pie_pagina_vacios").toggleClass("resultados_busqueda_pie_pagina_vacios");
        }

        // ! este lo tenga que cambiar no se
        $(".resultados_busqueda").html(info);
        $(".resultados_busqueda_pie_pagina").html("<a href='search.php?query=" + valor + "'>Ver todos los resultados</a>");

        if(valor == "")
        {
            $(".resultados_busqueda_pie_pagina").html("");
            $(".resultados_busqueda_pie_pagina").toggleClass("resultados_busqueda_pie_pagina_vacios");
            $(".resultados_busqueda_pie_pagina").toggleClass("resultados_busqueda_pie_pagina");
        }
    });
}