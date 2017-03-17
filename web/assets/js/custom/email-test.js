/**
 * Created by jonathan.
 */

$(document).ready(function () {

    $(".email-input").blur(function () {
        var email = this.value;

        $.ajax({
            url: URL+'/email-test',       //URL es una variable declarada en la plantilla base con un script
            data: {email: email},
            type: 'POST',
            success: function (response) {
                if(response == "used"){
                    $(".email-input").css({
                        "border":"1px solid red",
                        "color": "red"
                    });

                }else{
                    $(".email-input").css({
                        "border":"1px solid green",
                        "color": "green"
                    });
                }
            }
        });

    });
    //<span class="glyphicon glyphicon-check" aria-hidden="true"></span>
});