$(document).ready(function() { 
	papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
	var tblDatosAIngresar = $('#tblDatosAIngresar');
    tblDatosAIngresar.floatThead();
});
function papelesDeTrabajo(periodo,impcli){
	var data = "";
	$.ajax({
	  type: "post",  // Request method: post, get
	  url: serverLayoutURL+"/eventosimpuestos/getpapelestrabajo/"+periodo+"/"+impcli, // URL to request
	  data: data,  // post data
	  success: function(response) {
	    //alert(response);
	    $('#divLiquidarSUSS').html(response);
	    $('#tabsTareaImpuesto').hide();
		$('#divPagar').hide();
		$('#buttonPDT').hide();
		//$('#EventosimpuestoRealizartarea5Form').css('width','1500');
        var cantItems = $('#Eventosimpuesto0CantItems').val();
		for (var i = 0 ; i < cantItems; i++) {
			var item = $('#Eventosimpuesto'+i+'Item').val();
            var apagarItem = $('#apagar'+item+'');
            var apagar = 0;
            if(apagarItem.length>0){
                apagar = apagarItem.val();
            }
			if($('#Eventosimpuesto'+i+'Id').val()==0){//El Evento Impuesto no a sido creado previamente entonces vamos a guardar el monto que calculamos
				$('#Eventosimpuesto'+i+'Montovto').val(apagar);
			}
		};
		$(document).ready(function() {
	        $( "input.datepicker" ).datepicker({
	          yearRange: "-100:+50",
	          changeMonth: true,
	          changeYear: true,
	          constrainInput: false,
	          dateFormat: 'dd-mm-yy',
	        });
	      });
	    $( "#vencimientogeneral" ).change(function(){
	      $('#EventosimpuestoRealizartarea5Form .hiddendatepicker').val( $( "#vencimientogeneral" ).val());
	    });
	    $( "#vencimientogeneral" ).trigger( "change" );
	    $('#EventosimpuestoRealizartarea5Form').submit(function(){
            $('.inputtodisable').prop("disabled", false);
		    //serialize form data 
		    var formData = $(this).serialize(); 
		    //get form action 
		      var formUrl = $(this).attr('action'); 
		      $.ajax({ 
		        type: 'POST', 
		        url: formUrl, 
		        data: formData, 
		        success: function(data,textStatus,xhr){ 
		          var respuesta = jQuery.parseJSON(data);
		          var resp = respuesta.respuesta;
		          var error=respuesta.error;
		          if(error!=0){
		            alert(respuesta.validationErrors);
		            alert(respuesta.invalidFields);
		          }else{
		            $('#divLiquidarConvenioMultilateral').hide();
		          }
		        }, 
		        error: function(xhr,textStatus,error){ 
		          callAlertPopint(textStatus); 
		          return false;
		        } 
		      }); 
	          return false;
	    });               
	  },
	 error:function (XMLHttpRequest, textStatus, errorThrown) {
	    alert(textStatus);
	    
	 }
	});
  return false;
}
