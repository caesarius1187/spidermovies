$(document).ready(function() {
    reloadDatePickers();
    $("#saveDatosPersonalesForm #ClienteTipopersona").on('change', function() {
      switch(this.value){
        case "fisica":
            $("#saveDatosPersonalesForm #ClienteEditLabelNombre").text("Apellido y Nombre");
            $('#saveDatosPersonalesForm #ClienteTipopersonajuridica').val("");
            $('#saveDatosPersonalesForm #ClienteTipopersonajuridica').attr('disabled', true);

            $("#saveDatosPersonalesForm #ClienteDni").attr('disabled', false);

            $("#saveDatosPersonalesForm #ClienteAnosduracion").val("");
            $("#saveDatosPersonalesForm #ClienteAnosduracion").attr('disabled', true);

            $("#saveDatosPersonalesForm #ClienteInscripcionregistrocomercio").val("");
            $("#saveDatosPersonalesForm #ClienteInscripcionregistrocomercio").attr('disabled', true);

            $("#saveDatosPersonalesForm #ClienteModificacionescontrato").val("");
            $("#saveDatosPersonalesForm #ClienteModificacionescontrato").attr('disabled', true);
            
        break;
        case "juridica":
            $("#saveDatosPersonalesForm #clienteEditLabelNombre").text("Razon Social");

            $('#saveDatosPersonalesForm #ClienteTipopersonajuridica').attr('disabled', false);

            $("#saveDatosPersonalesForm #ClienteModificacionescontrato").attr('disabled', false);

            $("#saveDatosPersonalesForm #ClienteDni").val("");
            $("#saveDatosPersonalesForm #ClienteDni").attr('disabled', true);

            $("#saveDatosPersonalesForm #ClienteAnosduracion").attr('disabled', false);

            $("#saveDatosPersonalesForm #ClienteInscripcionregistrocomercio").attr('disabled', false);
        break;
      }
     
    });
    $( "#saveDatosPersonalesForm #ClienteTipopersona" ).trigger( "change" );
});

function reloadDatePickers(){
    $( "input.datepicker" ).datepicker({
                                yearRange: "-100:+50",
                                changeMonth: true,
                                changeYear: true,
                                constrainInput: false,
                                dateFormat: 'dd-mm-yy',
                            }); 
    $( "input.datepicker-day-month" ).datepicker({
                                yearRange: "-100:+50",
                                changeMonth: true,
                                changeYear: true,
                                constrainInput: false,
                                dateFormat: 'dd-mm',
                            }); 
}	