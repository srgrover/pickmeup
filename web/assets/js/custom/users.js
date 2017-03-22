$(document).ready(function () {

    //Paginación de scroll infinita
    var ias = jQuery.ias({
        container: '.box-users',                    //El contenedor que tiene todos los usuarios a paginar
        item: '.user-item',                         //El item a paginar
        pagination: '.pagination',                  //El control de paginacion está dentro de .pagination
        next: '.pagination .next_link',             //Donde pulsar para pasar a la siquiente pagina
        triggetPageThreshold: 5                     //Cada cuantos elementos se hace la petición ajax para paginar
    });

    ias.extension(new IASTriggerExtension({
        text: 'Ver mas',                            //Boton que se muestra al cargar 'x' usuarios
        offset: 3                                   //Cada 3 bloques de usuarios se mostrará el 'Ver mas'
    }));

    ias.extension(new IASSpinnerExtension({         //Poner una imagen mientras carga los datos
        src: URL+'/../assets/images/ajax-loader.gif'    //Ruta de la imagen
    }));

    ias.extension(new IASNoneLeftExtension({
        text: 'No hay mas conductores para mostrar' //Texto que saldrá cuando se acaben los datos a mostrar
    }));

    ias.on('ready', function (event) {
        followButtons();
    });

    ias.on('rendered', function (event) {
        followButtons();
    });

});

function followButtons() {
    $(".btn-follow").unbind("click").click(function () {
        $(this).addClass("hidden");
        $(this).parent().find(".btn-unfollow").removeClass("hidden");
        $.ajax({
            url: URL+'/follow',
            type: 'POST',
            data: {followed: $(this).attr("data-followed")},    //followed es la variable que se le pasará por POST
            success: function (response) {
                console.log(response);
            }
        });
    });

    $(".btn-unfollow").unbind("click").click(function () {
        $(this).addClass("hidden");
        $(this).parent().find(".btn-follow").removeClass("hidden");
        $.ajax({
            url: URL+'/unfollow',
            type: 'POST',
            data: {followed: $(this).attr("data-followed")},    //followed es la variable que se le pasará por POST
            success: function (response) {
                console.log(response);
            }
        });
    });
}