$(document).ready(function() { 
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
	    $('#divLiquidarCooperadoraAsistencial').html(response);
	  	//esconder elementos que no se utilizan de "getpapelestrabajo"
	    $('#tabsTareaImpuesto').hide();
		$('#divPagar').hide();
		$('#buttonPDT').hide();
		//$('#EventosimpuestoRealizartarea5Form').css('width','1500');
		var apagarInput = $('#apagar');
		var afavorInput = $('#afavor');
		var apagar = 0;
		var afavor = 0;
		if(apagarInput.length>0){
			apagar = apagarInput.val();
			afavor = afavorInput.val();
		}
		if($('#Eventosimpuesto0Id').val()==0){//El Evento Impuesto no a sido creado previamente entonces vamos a guardar el monto que calculamos
			$('#Eventosimpuesto0Montovto').val(apagar);
			$('#Eventosimpuesto0Monc').val(afavor);
		}
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
	      $('#EventosimpuestoRealizartarea5Form .hiddendatepicker').val( $( "#vencimientogeneral" ).val() );
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

