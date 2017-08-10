$(document).ready(function() {
    $( "#clickExcel" ).click(function() {
    	$("#pdtconveniomultilateral").prepend(
    		$("<tr>").append(
				$("<td>")
					.attr("colspan","25")
					.html($('#clinombre').val()+"-"+ $('#periodoPDT').val()+"-"+"Conveniomultilateral")
    			)
    		);
        $("#pdtconveniomultilateral").table2excel({
            // exclude CSS class
            exclude: ".noExl",
            name: "Conveniomultilateral",
            filename:$('#clinombre').val()+"-"+ $('#periodoPDT').val()+"-"+"Conveniomultilateral"

        });
    });
    papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
    var beforePrint = function() {
        console.log('Functionality to run before printing.');
        $('#header').hide();
        $('#Formhead').hide();
        $('#divLiquidarConvenioMultilateral').hide();
        $('#index').css('float','left');
        $('#padding').css('padding','0px');
        $('#index').css('font-size','10px');
        $('#index').css('border-color','#FFF');
    };
    var afterPrint = function() {
        console.log('Functionality to run after printing');
        $('#index').css('font-size','14px');
        $('#header').show();
        $('#Formhead').show();
        $('#divLiquidarConvenioMultilateral').show();
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
	cargarAsiento();
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
	    $('#divLiquidarConvenioMultilateral').html(response);
	    $('#tabsTareaImpuesto').hide();
		$('#divPagar').hide();
        $('#buttonPDT').hide();
        $('.btn_cancelar').hide();
		//$('#EventosimpuestoRealizartarea5Form').css('width','1500');
		var cantProvincias = $('#Eventosimpuesto0CantProvincias').val();		
		var cantActividades = $('#Eventosimpuesto0CantActividades').val();		
		for (var i = 0 ; i < cantProvincias; i++) {
			var partido = $('#Eventosimpuesto'+i+'PartidoId').val();
			var apagar = $('#apagar'+partido).val();
			var afavor = $('#afavor'+partido).val();
			if($('#Eventosimpuesto'+i+'Id').val()==0){
				//El Evento Impuesto no a sido creado previamente entonces vamos a guardar el monto que calculamos

				$('#Eventosimpuesto'+i+'Montovto').val(apagar);
				if(afavor*1<0){
					afavor=afavor*-1;
				}
				$('#Eventosimpuesto'+i+'Monc').val(afavor);
			}
			//si la baseprorrateada no ha sido guardada entonces la cargamos
			for (var j = 0 ; j < cantActividades; j++) {
				var actividadClienteId = $('#Eventosimpuesto'+i+'Basesprorrateada'+j+'ActividadclienteId').val();
				var impcliProvinciaId = $('#Eventosimpuesto'+i+'Basesprorrateada'+j+'ImpcliprovinciaId').val();
				var baseProrrateada = $('#baseProrrateada'+impcliProvinciaId+'actividadclienteid'+actividadClienteId).val();
				$('#Eventosimpuesto'+i+'Basesprorrateada'+j+'Baseprorrateada').val(baseProrrateada);
			}
			//aca vamos a agregar por JS los campos de saldo a favor por cada una de las provincias que tengas saldo a favor
			if(afavor*1<0){
				afavor=afavor*-1;
			}
			if($('#Eventosimpuesto'+i+'Conceptosrestante'+i+'Id').length<=0){
				if(afavor*1>0){
					$('<input>').attr({
						type: 'hidden',
						id: 'Eventosimpuesto'+i+'Conceptosrestante'+i+'PartidoId',
						value: partido,
						name: 'data[Eventosimpuesto]['+i+'][Conceptosrestante]['+i+'][partido_id]'
					}).appendTo('#EventosimpuestoRealizartarea5Form');
					$('<input>').attr({
						type: 'hidden',
						id: 'Eventosimpuesto'+i+'Conceptosrestante'+i+'ClienteId',
						value: $('#cliid').val(),
						name: 'data[Eventosimpuesto]['+i+'][Conceptosrestante]['+i+'][cliente_id]'
					}).appendTo('#EventosimpuestoRealizartarea5Form');
					$('<input>').attr({
						type: 'hidden',
						id: 'Eventosimpuesto'+i+'Conceptosrestante'+i+'ImpcliId',
						value: $('#impcliid').val(),
						name: 'data[Eventosimpuesto]['+i+'][Conceptosrestante]['+i+'][impcli_id]'
					}).appendTo('#EventosimpuestoRealizartarea5Form');
					$('<input>').attr({
						type: 'hidden',
						id: 'Eventosimpuesto'+i+'Conceptosrestante'+i+'ConceptostipoId',
						value: 1,
						name: 'data[Eventosimpuesto]['+i+'][Conceptosrestante]['+i+'][conceptostipo_id]'
					}).appendTo('#EventosimpuestoRealizartarea5Form');
					$('<input>').attr({
						type: 'hidden',
						id: 'Eventosimpuesto'+i+'Conceptosrestante'+i+'Periodo',
						value: $('#periodonext').val(),
						name: 'data[Eventosimpuesto]['+i+'][Conceptosrestante]['+i+'][periodo]'
					}).appendTo('#EventosimpuestoRealizartarea5Form');
					$('<input>').attr({
						type: 'hidden',
						id: 'Eventosimpuesto'+i+'Conceptosrestante'+i+'Montoretenido',
						value: afavor,
						name: 'data[Eventosimpuesto]['+i+'][Conceptosrestante]['+i+'][montoretenido]'
					}).appendTo('#EventosimpuestoRealizartarea5Form');
					$('<input>').attr({
						type: 'hidden',
						id: 'Eventosimpuesto'+i+'Conceptosrestante'+i+'Fecha',
						value: $.datepicker.formatDate('yy/mm/dd', new Date()),
						name: 'data[Eventosimpuesto]['+i+'][Conceptosrestante]['+i+'][fecha]'
					}).appendTo('#EventosimpuestoRealizartarea5Form');
					$('<input>').attr({
						type: 'hidden',
						id: 'Eventosimpuesto'+i+'Conceptosrestante'+i+'Descripcion',
						value: $('#afavorPartido'+partido).val()+"-"+$('#periodo').val(),
						name: 'data[Eventosimpuesto]['+i+'][Conceptosrestante]['+i+'][descripcion]'
					}).appendTo('#EventosimpuestoRealizartarea5Form');
				}
			}else{
				$('#Eventosimpuesto'+i+'Conceptosrestante'+i+'Montoretenido').val(afavor);
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
	  	//aca vamos a mover el div de asientos al de eventos impuesto
		$('#divContenedorContabilidad').detach().appendTo('#divAsientoDeEventoImpuesto');
	},
	 error:function (XMLHttpRequest, textStatus, errorThrown) {
	    alert(textStatus);
	    
	 }
	});
  return false;
}
function showhideBaseRealJurisdiccion() {
    if ($('.baseRealJurisdiccion:visible').length){
        $("#baseRealJurisdiccionTDTitle").attr('colspan', 1);
        $('.baseRealJurisdiccion').hide(1000);
    }
    else{
        var colspan=$("#cantBaseRealJurisdiccionTD").val();
        $("#baseRealJurisdiccionTDTitle").attr('colspan', colspan);
        $('.baseRealJurisdiccion').show(1000);
    }
}
function showhideProrrateoPorAplicacionArticulo() {
    if ($('.prorrateoPorAplicacionArticulo:visible').length){
        $("#prorrateoPorAplicacionArticuloTDTitle").attr('colspan', 1);
        $('.prorrateoPorAplicacionArticulo').hide(1000);
    }
    else{
        var colspan=$("#cantBaseRealJurisdiccionTD").val();
        $("#prorrateoPorAplicacionArticuloTDTitle").attr('colspan', colspan);
        $('.prorrateoPorAplicacionArticulo').show(1000);
    }
}
function showhideBaseImponibleProrrateada() {
    if ($('.baseImponibleProrrateada:visible').length){
        $('.baseImponibleProrrateada:visible').hide(1000);
        var colspan=$("#cantBaseRealJurisdiccionTD").val();
        $("#baseImponibleProrrateadaTDTitle").attr('colspan', colspan);
        $(".baseImponibleProrrateadaActividad").attr('colspan', 1);
    }
    else{
        var colspanActividad=$("#cantBaseProrrateadaActividadTD").val();
        var colspanCuadros=$("#cantBaseProrrateadaTD").val();
        $(".baseImponibleProrrateadaActividad").attr('colspan', colspanActividad);
        $("#baseImponibleProrrateadaTDTitle").attr('colspan',colspanCuadros);
        $('.baseImponibleProrrateada').show(1000);
    }
}
function imprimir(){
    window.print();
}
function cargarAsiento(){

	//aca vamos a buscar los valores guardados en los inputs que estan hidden y que tienen los valores que hay que guardar en los movimientos
	//los movimientos que tenemos que guardar son los siguentes


	// 506210001 Ingresos Brutos
	if($('#cuenta2577').length > 0){
		var orden = $('#cuenta2577').attr('orden');
		var impuestoDeterminadoTotal = $("#impuestoDeterminadoTotal").val();
		$('#Asiento0Movimiento'+orden+'Debe').val(impuestoDeterminadoTotal);
	}
	// 110404301	I.I.B.B. - Saldo a Favor Capital Federal
	if($('#cuenta319').length > 0){
		var orden = $('#cuenta319').attr('orden');
		var saldoAFavorTotal = $("#saldoAFavorTotal").val();
		var saldoAFavorPeriodoAnt = $("#totalAFavor").val();
        var difSAF = saldoAFavorTotal*1-saldoAFavorPeriodoAnt*1;
        if(difSAF>=0){
            $('#Asiento0Movimiento'+orden+'Debe').val(difSAF);
        }else{
            $('#Asiento0Movimiento'+orden+'Haber').val(difSAF*-1);
        }
	}
	// 110404301	I.I.B.B. - Pagos a cuenta
	if($('#cuenta3378').length > 0){
		var orden = $('#cuenta3378').attr('orden');
		var pagosACuentaTotal = $("#pagosACuentaTotal").val();
		$('#Asiento0Movimiento'+orden+'Haber').val(pagosACuentaTotal);
	}
	// 110404101	I.I.B.B. - Retenciones Capital Federal
	if($('#cuenta313').length > 0){
		var orden = $('#cuenta313').attr('orden');
		var totalretenciones = $("#totalretenciones").val();
		$('#Asiento0Movimiento'+orden+'Haber').val(totalretenciones);
	}
	// 110404201	I.I.B.B. - Percepciones Capital Federal
	if($('#cuenta316').length > 0){
		var orden = $('#cuenta316').attr('orden');
		var totalpercepciones = $("#totalpercepciones").val();
		$('#Asiento0Movimiento'+orden+'Haber').val(totalpercepciones);
	}
	// 110404298	I.I.B.B. - Percepciones Bancarias
	if($('#cuenta317').length > 0){
		if($('#impid').val()==21) {
			var orden = $('#cuenta317').attr('orden');
			var totalpercepcionesbancarias = $("#totalpercepcionesbancarias").val();
			$('#Asiento0Movimiento' + orden + 'Haber').val(totalpercepcionesbancarias);
		}
	}
	// 210402101	Ingresos Brutos a Pagar
	if($('#cuenta1492').length > 0){
		var orden = $('#cuenta1492').attr('orden');
		var totalgeneralapagar = $("#totalgeneralapagar").val();
		$('#Asiento0Movimiento'+orden+'Haber').val(totalgeneralapagar);
	}
	// 110404299	I.I.B.B. - SIRCREB
	if($('#cuenta318').length > 0){
		/*SI es CONVENIOO MULTILATERAL*/
		if($('#impid').val()==174){
			var orden = $('#cuenta318').attr('orden');
			var totalpercepcionesbancarias = $("#totalpercepcionesbancarias").val()*1;
			var totalpercepcionestucuman = 0;
			if($("#totalpercepcionestucuman").length>0){
				totalpercepcionestucuman = $("#totalpercepcionestucuman").val()*1;
			}
			//restarle las percepciones de tucuman
			$('#Asiento0Movimiento'+orden+'Haber').val((totalpercepcionesbancarias-totalpercepcionestucuman).toFixed(2));
		}

	}
	if($('#cuenta3379').length > 0){
		/*SI es CONVENIOO MULTILATERAL*/
		if($('#impid').val()==174) {
			var orden = $('#cuenta3379').attr('orden');
			var totalpercepcionestucuman = $("#totalpercepcionestucuman").val() * 1;
			//restarle las percepciones de tucuman
			$('#Asiento0Movimiento' + orden + 'Haber').val(totalpercepcionestucuman);
		}
	}
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