/**
 * Created by jonathan on 21/04/17.
 */

$(document).ready(function () {
    if ($('.label-notifications').text() == 0) {
    $('.label-notifications').addClass("hidden");
} else {
    $('.label-notifications').removeClass("hidden");
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
}