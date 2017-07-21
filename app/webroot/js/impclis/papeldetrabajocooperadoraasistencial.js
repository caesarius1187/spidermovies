$(document).ready(function() { 
	$( "#clickExcel" ).click(function() {
    	$("#sheetCooperadoraAsistencial").prepend(
    		$("<tr>").append(
				$("<td>")
					.attr("colspan","25")
					.html($('#clinombre').val()+"-"+ $('#periodoPDT').val()+"-"+"Cooperadora Asistencial")
    			)
    		);
        $("#sheetCooperadoraAsistencial").table2excel({
            // exclude CSS class
            exclude: ".noExl",
            name: "CooperadoraAsistencial",
            filename:$('#clinombre').val()+"-"+ $('#periodoPDT').val()+"-"+"Cooperadora Asistencial"

        });
    });
    papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
	$(".inputDebe").each(function () {
		$(this).change(addTolblTotalDebeAsieto);
	});
	$(".inputHaber").each(function () {
		$(this).change(addTolblTotalhaberAsieto);
	});
	$(".inputHaber").each(function(){
		$(this).trigger('change');
		return;
	});
	$(".inputDebe").each(function(){
		$(this).trigger('change');
		return;
	});
	catchAsientoIVA();
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
		$('#divNombreClienteEventoImpuesto').hide();
		$('.btn_cancelar').hide();
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
		  //aca vamos a agregar por JS los campos de saldo a favor para el periodo siguiente
		  if(afavor*1<0){
			  afavor=afavor*-1;
		  }
		  if($('#Eventosimpuesto0Conceptosrestante0Id').length<=0){
			  if(afavor*1>0){
				  $('<input>').attr({
					  type: 'hidden',
					  id: 'Eventosimpuesto0Conceptosrestante0ClienteId',
					  value: $('#cliid').val(),
					  name: 'data[Eventosimpuesto][0][Conceptosrestante][0][cliente_id]'
				  }).appendTo('#EventosimpuestoRealizartarea5Form');
				  $('<input>').attr({
					  type: 'hidden',
					  id: 'Eventosimpuesto0Conceptosrestante0ImpcliId',
					  value: $('#impcliid').val(),
					  name: 'data[Eventosimpuesto][0][Conceptosrestante][0][impcli_id]'
				  }).appendTo('#EventosimpuestoRealizartarea5Form');
				  $('<input>').attr({
					  type: 'hidden',
					  id: 'Eventosimpuesto0Conceptosrestante0ConceptostipoId',
					  value: 1,
					  name: 'data[Eventosimpuesto][0][Conceptosrestante][0][conceptostipo_id]'
				  }).appendTo('#EventosimpuestoRealizartarea5Form');
				  $('<input>').attr({
					  type: 'hidden',
					  id: 'Eventosimpuesto0Conceptosrestante0Periodo',
					  value: $('#periodoPDT').val(),
					  name: 'data[Eventosimpuesto][0][Conceptosrestante][0][periodo]'
				  }).appendTo('#EventosimpuestoRealizartarea5Form');
				  $('<input>').attr({
					  type: 'hidden',
					  id: 'Eventosimpuesto0Conceptosrestante0Montoretenido',
					  value: afavor,
					  name: 'data[Eventosimpuesto][0][Conceptosrestante][0][montoretenido]'
				  }).appendTo('#EventosimpuestoRealizartarea5Form');
				  $('<input>').attr({
					  type: 'hidden',
					  id: 'Eventosimpuesto0Conceptosrestante0Fecha',
					  value: $.datepicker.formatDate('yy/mm/dd', new Date()),
					  name: 'data[Eventosimpuesto][0][Conceptosrestante][0][fecha]'
				  }).appendTo('#EventosimpuestoRealizartarea5Form');

			  }
		  }else{
			  $('#Eventosimpuesto0Conceptosrestante0Montoretenido').val(afavor);
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
					  $('#AsientoAddForm').submit();
					  $('#divLiquidarCooperadoraAsistencial').hide();
		          }
		        }, 
		        error: function(xhr,textStatus,error){ 
		          callAlertPopint(textStatus); 
		          return false;
		        } 
		      }); 
	          return false;
	    });
		  //aca vamos a mover el div de asientos al de eventos impuesto
		  $('#divContenedorContabilidad').detach().appendTo('#divAsientoDeEventoImpuesto');

	  },
	 error:function (XMLHttpRequest, textStatus, errorThrown) {
	    alert(textStatus);
	    
	 }
	});
  return false;
}
function catchAsientoIVA(){
	$('#AsientoAddForm').submit(function(){
		/*Vamos a advertir que estamos reemplazando un asiento ya guardado*/
		var asientoyaguardado=false;
		if($("#AsientoAddForm #Asiento0Id").val()*1!=0){
			asientoyaguardado=true;
		}
		var r=true;
		if(asientoyaguardado){
			r = confirm("Este asiento sobreescribira al previamente guardado, reemplazando los valores por los calculados" +
				" en este momento. Para ver el asiento previamente guardado CANCELE, luego ingrese en el Informe de " +
				" Sumas y saldos y despues en Asientos");
		}
		if (r == true) {
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
		}else{
			callAlertPopint("El asiento no se ha sobreescrito.");
		}

		return false;
	});
}
function addTolblTotalDebeAsieto(event) {
	var debesubtotal = 0;
	$(".inputDebe").each(function () {
		debesubtotal = debesubtotal*1 + this.value*1;
		if(this.value*1!=0){
			$(this).removeClass("movimientoSinValor");
			$(this).addClass("movimientoConValor");
		}else{
			$(this).removeClass("movimientoConValor")
			$(this).addClass("movimientoSinValor");
		}

	});
	$("#lblTotalDebe").text(parseFloat(debesubtotal).toFixed(2)) ;
	showIconDebeHaber()
}
function addTolblTotalhaberAsieto(event) {
	//        $("#lblTotalAFavor").val(0) ;
	var habersubtotal = 0;
	$(".inputHaber").each(function () {
		habersubtotal = habersubtotal*1 + this.value*1;
		if(this.value*1!=0){
			$(this).removeClass("movimientoSinValor");
			$(this).addClass("movimientoConValor");
		}else{
			$(this).removeClass("movimientoConValor")
			$(this).addClass("movimientoSinValor");
		}
	});
	$("#lblTotalHaber").text(parseFloat(habersubtotal).toFixed(2)) ;
	showIconDebeHaber()
}
function showIconDebeHaber(){
	if($("#lblTotalHaber").text()==$("#lblTotalDebe").text()){
		$("#iconDebeHaber").attr('src',serverLayoutURL+'/img/test-pass-icon.png');
	}else{
		$("#iconDebeHaber").attr('src',serverLayoutURL+'/img/test-fail-icon.png');
	}
}
