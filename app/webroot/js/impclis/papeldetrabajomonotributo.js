$(document).ready(function() { 
    var orden = $("#topOrden").val();
    $("#orden-"+orden+" td").css({'background-color' : 'lightgreen'});
    $("#orden-"+orden).css({'border' : 'blue solid 4px'});
    papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
   

});
function papelesDeTrabajo(periodo,impcli){
	var data = "";
	$.ajax({
	  type: "post",  // Request method: post, get
	  url: serverLayoutURL+"/eventosimpuestos/getpapelestrabajo/"+periodo+"/"+impcli, // URL to request
	  data: data,  // post data
	  success: function(response) {
	    //alert(response);
	    $('#divLiquidarMonotributo').html(response);
	    $('#tabsTareaImpuesto').hide();
		$('#divPagar').hide();
		$('#buttonPDT').hide();
		//$('#EventosimpuestoRealizartarea5Form').css('width','453');
		for (var i = 0 ; i < 3; i++) {
			if($('#Eventosimpuesto'+i+'Id').val()==0){//El Evento Impuesto no a sido creado previamente entonces vamos a guardar el monto que calculamos
				switch($('#Eventosimpuesto'+i+'Item').val()){
					case 'Monotributo':
						var apagarMonotributo = $('#apagarMonotributo').val();
						$('#Eventosimpuesto'+i+'Montovto').val(apagarMonotributo);
					break;
					case 'Monotributo Autonomo':
						var apagarAutonomo = $('#apagarAutonomo').val();
						$('#Eventosimpuesto'+i+'Montovto').val(apagarAutonomo)
					break;
					case 'Monotributo Obra Social':
						var apagarObrasocial = $('#apagarObrasocial').val();
						$('#Eventosimpuesto'+i+'Montovto').val(apagarObrasocial)
					break;
				}
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
	            $('#divLiquidarMonotributo').hide();
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