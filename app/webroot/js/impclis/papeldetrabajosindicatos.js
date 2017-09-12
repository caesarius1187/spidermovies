$(document).ready(function() { 
	$( "#clickExcel" ).click(function() {
		if (!document.getElementById("pdtSindicato_tr1"))
        {
            $("#tblSindicatos").prepend(
                $("<tr id='pdtSindicato_tr1'>").append(
                    $("<td style='display:none'>")
                        .attr("colspan","25")
                        .html("Contribuyente: " + $('#clinombre').val() + " - CUIT: " + $('#nroCuitContribuyente').html())                      
                    )
                );
        }
        if (!document.getElementById("pdtSindicato_tr2"))
        {
            $("#tblSindicatos").prepend(
                $("<tr id='pdtSindicato_tr2'>").append(
                    $("<td style='display:none'>")
                        .attr("colspan","25")
                        .html($('#tipoorganismoyNombre').html() + " - Periodo: "+ $('#periodoPDT').val())               
                    )
                );
        }       
        $("#tblSindicatos").table2excel({
            // exclude CSS class
            exclude: ".noExl",
            name: $('#impclinombre').val(),
            filename:($('#clinombre').val()).replace(/ /g,"_").replace(".","")+"_"+$('#periodoPDT').val().replace(/-/g,"_")+"_Sindicatos"
        });
    });
	var beforePrint = function() {
		$('#header').hide();
		$('#Formhead').hide();
		$('#clickExcel').hide();
		$('#btnImprimir').hide();
		$('#divPrepararPapelesDeTrabajo').hide();		
	};
	var afterPrint = function() {		
		$('#header').show();
		$('#Formhead').show();
		$('#clickExcel').show();
		$('#btnImprimir').show();
		$('#divPrepararPapelesDeTrabajo').show();		
	};
	if (window.matchMedia) {
		var mediaQueryList = window.matchMedia('print');
		mediaQueryList.addListener(function(mql) {
			if (mql.matches) {
				beforePrint();
			} else {
				afterPrint();
			}
		});
	}
	window.onbeforeprint = beforePrint;
	window.onafterprint = afterPrint;    
    papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
	catchAsiento();
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
});
function imprimir(){
	window.print();
}
function papelesDeTrabajo(periodo,impcli){
	var data = "";
	$.ajax({
	  type: "post",  // Request method: post, get
	  url: serverLayoutURL+"/eventosimpuestos/getpapelestrabajo/"+periodo+"/"+impcli, // URL to request
	  data: data,  // post data
	  success: function(response) {
	    //alert(response);
	    $('#divLiquidarSindicatos').html(response);
	  	//esconder elementos que no se utilizan de "getpapelestrabajo"
	    $('#tabsTareaImpuesto').hide();
		$('#divPagar').hide();
		$('#buttonPDT').hide();
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
	  	//aca vamos a mover el div de asientos al de eventos impuesto
	  	$('#divContenedorContabilidad').detach().appendTo('#divAsientoDeEventoImpuesto');
	  },
	 error:function (XMLHttpRequest, textStatus, errorThrown) {
	    alert(textStatus);
	    
	 }
	});
  return false;
}
function catchAsiento(){
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
