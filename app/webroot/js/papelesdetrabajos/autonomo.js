$(document).ready(function() { 
	$( "#clickExcel" ).click(function() {
    	$("#sheetAutonomo").prepend(
    		$("<tr>").append(
				$("<td>")
					.attr("colspan","25")
					.html($('#clinombre').val()+"-"+ $('#periodoPDT').val()+"-"+$('#impclinombre').val())
    			)
    		);
        $("#sheetAutonomo").table2excel({
            // exclude CSS class
            exclude: ".noExl",
            name: $('#impclinombre').val(),
            filename:$('#clinombre').val()+"-"+ $('#periodoPDT').val()+"-"+$('#impclinombre').val()

        });
    });
    papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
	cargarAsiento();
	catchAsiento();
});
function papelesDeTrabajo(periodo,impcli){
	var data = "";
	$.ajax({
	  type: "post",  // Request method: post, get
	  url: serverLayoutURL+"/eventosimpuestos/getpapelestrabajo/"+periodo+"/"+impcli, // URL to request
	  data: data,  // post data
	  success: function(response) {
	    //alert(response);
	    $('#divLiquidarAutonomo').html(response);
	  	//esconder elementos que no se utilizan de "getpapelestrabajo"
	    $('#tabsTareaImpuesto').hide();
		$('#divPagar').hide();
		$('#buttonPDT').hide();
		//$('#EventosimpuestoRealizartarea5Form').css('width','1500');
		var apagarInput = $('#apagarAutonomo');
		var apagar = 0;
		if(apagarInput.length>0){
			apagar = apagarInput.val();
		}
		if($('#Eventosimpuesto0Id').val()==0){//El Evento Impuesto no a sido creado previamente entonces vamos a guardar el monto que calculamos
			$('#Eventosimpuesto0Montovto').val(apagar);
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
					$('#AsientoAddForm').submit();
		          	var respuesta = jQuery.parseJSON(data);
		          	var resp = respuesta.respuesta;
					callAlertPopint(resp);

		          	var error=respuesta.error;
		          	if(error!=0){
		            	alert(respuesta.validationErrors);
		            	alert(respuesta.invalidFields);
		          	}else{
		           	 	$('#divLiquidarSindicatos').hide();
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
function cargarAsiento(){
	// 504990006	Autonomo
	if($('#cuenta2335').length > 0){
		var orden = $('#cuenta2335').attr('orden');
		var apagarAutonomo = $("#apagarAutonomo").val();
		$('#Asiento0Movimiento'+orden+'Debe').val(apagarAutonomo);
	}
	// 210401801	Autonomo a Pagar
	if($('#cuenta1477').length > 0){
		var orden = $('#cuenta1477').attr('orden');
		var apagarAutonomo = $("#apagarAutonomo").val();
		$('#Asiento0Movimiento'+orden+'Haber').val(apagarAutonomo);
	}
}
function catchAsiento(){
	$('#AsientoAddForm').submit(function(){
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
				callAlertPopint(resp);
			},
			error: function(xhr,textStatus,error){
				callAlertPopint(textStatus);
				return false;
			}
		});
		return false;
	});
}

