fecha_ida = jQuery('#appbundle_viaje_fechaSalida');
fecha_vuelta = jQuery('#appbundle_viaje_fechaVuelta');

jQuery.datetimepicker.setLocale('es');
jQuery(function(){
    fecha_ida.datetimepicker({
        minDate: new Date(),
        onShow:function( ct ){
            this.setOptions({
                maxDate:fecha_vuelta.val()?fecha_vuelta.val():false
            })
        },
    });
    fecha_vuelta.datetimepicker({
        minDate: new Date(),
        onShow:function( ct ){
            this.setOptions({
                minDate:fecha_ida.val()?fecha_ida.val():false
            })
        },
    });
});
