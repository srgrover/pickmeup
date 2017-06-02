/**
 * Created by jonathan on 21/04/17.
 */

$(document).ready(function () {
    if ($('.label-notifications').text() == 0) {
        $('.label-notifications').addClass("hidden");
    } else {
        $('.label-notifications').removeClass("hidden");
    }

    if($(".label-notifications-msg").text() == 0){
        $(".label-notifications-msg").addClass("hidden");
    }else{
        $(".label-notifications-msg").removeClass("hidden");
    }

    notificaciones();

    setInterval(function (){
        notificaciones();
    }, 30000);
});

function notificaciones(){
    $.ajax({
        url: URL+'/notificaciones/count',
        type: 'GET',
        success: function (response) {
            $(".label-notifications").html(response);

            if(response == 0){
                $(".label-notifications").addClass("hidden");
            }else{
                $(".label-notifications").removeClass("hidden");
            }
        }
    });

    $.ajax({
        url: URL+'/mensajes/no-leidos',
        type: 'GET',
        success: function (response) {
            $(".label-notifications-msg").html(response);

            if(response == 0){
                $(".label-notifications-msg").addClass("hidden");
            }else{
                $(".label-notifications-msg").removeClass("hidden");
            }
        }
    });
}
