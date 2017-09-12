$(document).ready(function() {
	$( "#clickExcel" ).click(function() {
		if (!document.getElementById("pdtActividadesVarias_tr1"))
        {
            $("#pdtactividadesvarias").prepend(
                $("<tr id='pdtActividadesVarias_tr1'>").append(
                    $("<td style='display:none'>")
                        .attr("colspan","25")
                        .html("Contribuyente: " + $('#clinombre').val() + " - CUIT: " + $('#nroCuitContribuyente').html())                      
                    )
                );
        }
        if (!document.getElementById("pdtActividadesVarias_tr2"))
        {
            $("#pdtactividadesvarias").prepend(
                $("<tr id='pdtActividadesVarias_tr2'>").append(
                    $("<td style='display:none'>")
                        .attr("colspan","25")
                        .html($('#tipoorganismoyNombre').html() + " - Periodo: "+ $('#periodoPDT').val())               
                    )
                );
        }
		$("#pdtactividadesvarias").table2excel({
			// exclude CSS class
			exclude: ".noExl",
			name: "ActividadesVarias",
			filename:($('#clinombre').val()).replace(/ /g,"_").replace(".","")+"_"+$('#periodoPDT').val().replace(/-/g,"_")+"_ActividadesVarias"
			//filename:$('#clinombre').val()+"-"+ $('#periodoPDT').val()+"-"+"ActividadesVarias"
		});
	});
    papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
	var beforePrint = function() {
		$('#header').hide();
		$('#Formhead').hide();
		//$('#divLiquidarActividadesVariar').hide();

		$('#clickExcel').hide();
		$('#btnImprimir').hide();
		$('#divPrepararPapelesDeTrabajo').hide();

		$('#index').css('float','left');
		$('#padding').css('padding','0px');
		$('#index').css('font-size','10px');
		$('#index').css('border-color','#FFF');
	};
	var afterPrint = function() {
		$('#index').css('font-size','14px');
		$('#header').show();
		$('#Formhead').show();
		//$('#divLiquidarActividadesVariar').show();

		$('#clickExcel').show();
		$('#btnImprimir').show();
		$('#divPrepararPapelesDeTrabajo').show();

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
	    $('#divLiquidarActividadesVariar').html(response);
	    //esconder elementos que no necesitamos en este momento
	    $('#tabsTareaImpuesto').hide();
		$('#divPagar').hide();
		$('#buttonPDT').hide();
	  	$('.btn_cancelar').hide();

		//$('#EventosimpuestoRealizartarea5Form').css('width','1500');
		
		var cantLocalidade = $('#Eventosimpuesto0CantProvincias').val();		
		for (var i = 0 ; i < cantLocalidade; i++) {
            var localidad = $('#Eventosimpuesto'+i+'LocalidadeId').val();
            var apagar = $('#apagar'+localidad).val();
            var afavor = $('#afavor'+localidad).val();
            if($('#Eventosimpuesto'+i+'Id').val()==0){//El Evento Impuesto no a sido creado previamente entonces vamos
				// a guardar el monto que calculamos

				$('#Eventosimpuesto'+i+'Montovto').val(apagar);
				$('#Eventosimpuesto'+i+'Monc').val(afavor);
			}
			//aca vamos a agregar por JS los campos de saldo a favor por cada una de las provincias que tengas saldo a favor
			if(afavor*1<0){
				afavor=afavor*-1;
			}
			if($('#Eventosimpuesto'+i+'Conceptosrestante'+i+'Id').length<=0){
				if(afavor*1>0){
					$('<input>').attr({
						type: 'hidden',
						id: 'Eventosimpuesto'+i+'Conceptosrestante'+i+'LocalidadeId',
						value: localidad,
						name: 'data[Eventosimpuesto]['+i+'][Conceptosrestante]['+i+'][localidade_id]'
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
						value: $('#afavor'+localidad).val()+"-"+$('#periodo').val(),
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
                      $('#divLiquidarActividadesVariar').hide();
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
function showhideactividad(actividad) {
	if ($('.tdActividad'+actividad+':visible').length){
        $(".thActividad"+actividad).attr('colspan', 1);
        $(".tdImpuestoDeterminado"+actividad).attr('colspan', 2);
		$('.tdActividad'+actividad).hide(1000);
	}
	else{
        $(".thActividad"+actividad).attr('colspan', 7);
        $(".tdImpuestoDeterminado"+actividad).attr('colspan', 1);
        $('.tdActividad'+actividad).show(1000);
	}
}
function imprimir(){
	window.print();
}
function cargarAsiento(){

    //aca vamos a buscar los valores guardados en los inputs que estan hidden y que tienen los valores que hay que guardar en los movimientos
    //los movimientos que tenemos que guardar son los siguentes

    // 506311001	Act. Vs.
    if($('#cuenta2589').length > 0){
        var orden = $('#cuenta2589').attr('orden');
        var totaldeterminadogeneral = $("#totaldeterminadogeneral").val();
        $('#Asiento0Movimiento'+orden+'Debe').val(totaldeterminadogeneral);
    }
    // 110405103	Act. Vs. - Saldo a Favor
    if($('#cuenta334').length > 0){
        var orden = $('#cuenta334').attr('orden');
        var totalafavor = parseFloat($("#totalafavor").val());
        var totalafavorperiodoanterior =  parseFloat($("#totalafavorperiodoanterior").val());
		var diferencia = totalafavor-totalafavorperiodoanterior;
		if(diferencia>0){
			$('#Asiento0Movimiento'+orden+'Debe').val(diferencia);
		}else{
			$('#Asiento0Movimiento'+orden+'Haber').val(diferencia*-1);
		}
    }
    // 110405101	Act. Vs. - Pagos a Cuenta
    if($('#cuenta3385').length > 0){
        var orden = $('#cuenta3385').attr('orden');
        var totalpagosacuenta = $("#totalpagosacuenta").val();
        $('#Asiento0Movimiento'+orden+'Haber').val(totalpagosacuenta);
    }
	// 110405101	Act. Vs. - Retenciones
    if($('#cuenta332').length > 0){
        var orden = $('#cuenta332').attr('orden');
        var totalretenciones = $("#totalretenciones").val();
        $('#Asiento0Movimiento'+orden+'Haber').val(totalretenciones);
    }
    // 110405102	Act. Vs. - Percepciones
    if($('#cuenta333').length > 0){
        var orden = $('#cuenta333').attr('orden');
        var totalpercepciones = $("#totalpercepciones").val();
        $('#Asiento0Movimiento'+orden+'Haber').val(totalpercepciones);
    }
    // 210403101	Actividades Varias a Pagar
    if($('#cuenta1518').length > 0){
        var orden = $('#cuenta1518').attr('orden');
        var totalapagar = $("#totalapagar").val();
        $('#Asiento0Movimiento'+orden+'Haber').val(totalapagar);
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
