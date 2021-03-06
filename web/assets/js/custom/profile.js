$(document).ready(function () {

    //Paginación de scroll infinita
    var ias = jQuery.ias({
        container: '.profile-box #user-publicationa',                     //El contenedor que tiene todos los usuarios a paginar
        item: '.publication-item',                         //El item a paginar
        pagination: '.profile-box .pagination',                  //El control de paginacion está dentro de .pagination
        next: '.profile-box .pagination .next_link',             //Donde pulsar para pasar a la siquiente pagina
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
        text: 'No hay mas publicaciones para mostrar' //Texto que saldrá cuando se acaben los datos a mostrar
    }));

    ias.on('ready', function (event) {
        followButtons();
    })
});