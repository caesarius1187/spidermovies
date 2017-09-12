$(document).ready(function() { 
	$( "#clickExcel" ).click(function() {
		if (!document.getElementById("pdtconveniomultilateral_tr1"))
		{
	    	$("#sheetAutonomo").prepend(
	    		$("<tr id='pdtconveniomultilateral_tr1'>").append(
					$("<td style='display:none'>")
						.attr("colspan","25")
						.html("Contribuyente: " + $('#clinombre').val() + " - CUIT: " + $('#nroCuitContribuyente').html())						
	    			)
	    		);
    	}
    	if (!document.getElementById("pdtconveniomultilateral_tr2"))
    	{
    		$("#sheetAutonomo").prepend(
	    		$("<tr id='pdtconveniomultilateral_tr2'>").append(
					$("<td style='display:none'>")
						.attr("colspan","25")
						.html($('#tipoorganismoyNombre').html() + " - Periodo: "+ $('#periodoPDT').val())				
	    			)
	    		);
    	}
        $("#sheetAutonomo").table2excel({
            // exclude CSS class
            exclude: ".noExl",
            name: $('#impclinombre').val(),
            filename:($('#clinombre').val()).replace(/ /g,"_").replace(".","") + "_" + $('#periodoPDT').val().replace(/-/g,"_") + "_Autonomo"
        });
    });

	var beforePrint = function() {
        //console.log('New Functionality to run before printing.');
        $('#header').hide();
        $('#Formhead').hide();
        $('#clickExcel').hide();
		$('#btnImprimir').hide();        
		$('#divPrepararPapelesDeTrabajo').hide();   
        //$('#divLiquidarConvenioMultilateral').hide();
        $('#index').css('float','left');
        $('#padding').css('padding','0px');
        $('#index').css('font-size','10px');
        $('#index').css('border-color','#FFF');
    };
    var afterPrint = function() {
        //console.log('Functionality to run after printing');
        $('#index').css('font-size','14px');
        $('#header').show();
        $('#Formhead').show();
        $('#clickExcel').show();
		$('#btnImprimir').show();        
		$('#divPrepararPapelesDeTrabajo').show();        		
        //$('#divLiquidarConvenioMultilateral').show();
        $('#index').css('float','right');
        $('#padding').css('padding','10px 1%');
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
	cargarAsiento();
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
	catchAsiento();
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
	    $('#divLiquidarAutonomo').html(response);
	  	//esconder elementos que no se utilizan de "getpapelestrabajo"
	    $('#tabsTareaImpuesto').hide();
		$('#divPagar').hide();
		$('#buttonPDT').hide();
		  $('.btn_cancelar').hide();
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
		  //aca vamos a mover el div de asientos al de eventos impuesto
		  $('#divContenedorContabilidad').detach().appendTo('#divAsientoDeEventoImpuesto');
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

