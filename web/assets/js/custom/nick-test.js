/**
 * Created by jonathan.
 */

$(document).ready(function () {

   $(".nick-input").blur(function () {
       var nick = this.value;

       $.ajax({
           url: URL+'/nick-test',       //URL es una variable declarada en la plantilla base con un script
           data: {nick: nick},
           type: 'POST',
           success: function (response) {
               if(response === "used"){
                   $(".nick-input").css({
                       "border":"1px solid red",
                       "color": "red"
                   });

               }else{
                   $(".nick-input").css({
                       "border":"1px solid green",
                       "color": "green"
                   });
               }
           }
       });

   });
    //<span class="glyphicon glyphicon-check" aria-hidden="true"></span>
});
