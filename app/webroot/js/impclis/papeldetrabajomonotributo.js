$(document).ready(function() {
    $( "#clickExcel" ).click(function() {
        if($('#divRecategorizacion').is(':visible')){

        	if (!document.getElementById("pdtMonotributo_tr1"))
			{
		    	$("#tblExcelHeader").prepend(
		    		$("<tr id='pdtMonotributo_tr1'>").append(
						$("<td style='display:none'>")
							.attr("colspan","25")
							.html("Contribuyente: " + $('#clinombre').val() + " - CUIT: " + $('#nroCuitContribuyente').html())						
		    			)
		    		);
	    	}
	    	if (!document.getElementById("pdtMonotributo_tr2"))
	    	{
	    		$("#tblExcelHeader").prepend(
		    		$("<tr id='pdtMonotributo_tr2'>").append(
						$("<td style='display:none'>")
							.attr("colspan","25")
							.html($('#tipoorganismoyNombre').html() + " - Periodo: "+ $('#periodoPDT').val())				
		    			)
		    		);
	    	}

            $("#divRecategorizacion").table2excel({
                // exclude CSS class
                exclude: ".noExl",
                name: "Monotributo",
                filename:($('#clinombre').val()).replace(/ /g,"_").replace(".","") + "_"+ $('#periodoPDT').val().replace(/-/g,"_")+"_"+"Recategoizacion"
            });
        }else{
            $("#divDDJJ").table2excel({
                // exclude CSS class
                exclude: ".noExl",
                name: "Monotributo",
                filename:$('#clinombre').val().replace(/ /g,"_").replace(".","")+"_"+ $('#periodoPDT').val().replace(/-/g,"_")+"_"+"DDJJ"
            });
        }

    });
    var orden = $("#topOrden").val();
    $("#orden-"+orden+" td").css({'background-color' : 'lightgreen'});
    $("#orden-"+orden).css({'border' : 'blue solid 4px'});
    papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
	loadFormImpuesto($('#impcliidPDT').val(),$('#cliid').val());

	var beforePrint = function() {
		$('#header').hide();
		//$('#Formhead').hide();
		//$('#divLiquidarMonotributo').hide();
		$('#tabsTareaMonotributo').hide();		
		$('#clickExcel').hide();
		$('#btnImprimir').hide();
		$('#divPrepararPapelesDeTrabajo').hide();
		//$('#divHeaderEventoImpuesto').hide();   
		$('#index').css('float','left');
		$('#padding').css('padding','0px');
		$('#index').css('font-size','10px');
        $('#index').css('border-color','#FFF');
        $('.tbl_tareas').css('width','80%');

	};
	var afterPrint = function() {
		$('#index').css('font-size','14px');
		$('#header').show();
		//$('#Formhead').show();
		//$('#divLiquidarMonotributo').show();
		$('#tabsTareaMonotributo').show();
		$('#clickExcel').show();
		$('#btnImprimir').show();
		//$('#divHeaderEventoImpuesto').show();   
		$('#divPrepararPapelesDeTrabajo').show();  
		 $('#index').css('float','right');
		$('#padding').css('padding','10px 1%');
        $('.tbl_tareas').css('width','50%');
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
	    $('#divLiquidarMonotributo').html(response);
	    $('#tabsTareaImpuesto').hide();
		$('#divPagar').hide();
		$('#buttonPDT').hide();
	  	$('.btn_cancelar').hide();
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
			//Aca primero vamos a enviar la modificacion del impuesto seleccionando la nueva categoria
			$('#ImpcliEditForm'+impcli).submit();
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
		//aca vamos a mover el div de asientos al de eventos impuesto
	  	$('#divContenedorContabilidad').detach().appendTo('#divAsientoDeEventoImpuesto');
	  },
	 error:function (XMLHttpRequest, textStatus, errorThrown) {
	    alert(textStatus);
	    
	 }
	});
  return false;
}
function showRecategorizacion(){
	if($("#tab_Recategorizacion").hasClass("tabsTareaImpuesto_active")){

	}else{
		$( ".tabsTareaImpuesto" ).switchClass( "tabsTareaImpuesto", "tabsTareaImpuesto_active", 500 );
		$( ".tabsTareaImpuesto_active" ).switchClass( "tabsTareaImpuesto_active", "tabsTareaImpuesto", 500 );
		$('#divRecategorizacion').show();
		$('#divDDJJ').hide();
	}
}
function showDDJJ(){

	if($("#tab_DDJJ").hasClass("tabsTareaImpuesto_active")){

	}else{
		$( ".tabsTareaImpuesto" ).switchClass( "tabsTareaImpuesto", "tabsTareaImpuesto_active", 500 );
		$( ".tabsTareaImpuesto_active" ).switchClass( "tabsTareaImpuesto_active", "tabsTareaImpuesto", 500 );
		$('#divRecategorizacion').hide();
		$('#divDDJJ').show();
        var haycambios = $('#haycambios').val()*1;
        if(haycambios){
            loadDDJJ($('#periodoPDT').val(),$('#impcliidPDT').val());
            $('#haycambios').val(0);
        }
	}
}
function loadDDJJ(periodo,impcli){
    var data = "";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/impclis/papeldetrabajoddjj/"+periodo+"/"+impcli, // URL to request
        data: data,  // post data
        success: function(response) {
            //alert(response);
            $('#divDDJJ').html(response);
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
        }
    });
    return false;
}
function imprimir(){
	window.print();
}
function loadFormImpuesto(impcliid,cliid){
	jQuery(document).ready(function($) {
		var data ="";
		$.ajax({
			type: "post",  // Request method: post, get
			url: serverLayoutURL+"/impclis/editajax/"+impcliid+"/"+cliid,
			// URL to request
			data: data,  // post data
			success: function(response) {
				$('#divEditImpCliMonotributo').html(response);
				$('#ImpcliEditForm'+impcliid+' input[type="submit"]').hide();
				var alertMsg  = "";
				var mesParaProximaRecategorizacion = $("#mesParaProximaRecategorizacion").val();
				var recategoriza = false;
				if(mesParaProximaRecategorizacion==4){
					//Aca se recategoriza sino no hay q cambiar nada en el impcli
					recategoriza = true;
					alertMsg = 'Se cambiara de la categoria '+$('#ImpcliCategoriamonotributo').val()+' a la categoria '+$('#topCategoria').val();
					$("#ImpcliCategoriamonotributo").after(
						$('<img>').attr({
							src: serverLayoutURL+'/img/ii.png',
							style: 'width:15px;height:15px',
							title: alertMsg,
							alt: ''
						})
					);
					$("#ImpcliCategoriamonotributo").val($('#topCategoria').val());
				}else{
					alertMsg = 'NO Se cambiara de la categoria '+$('#ImpcliCategoriamonotributo').val()+' a la categoria '+$('#topCategoria').val() +' Por que no es mes de impacto de recategorizacion.';
					$("#ImpcliCategoriamonotributo").after(
						$('<img>').attr({
							src: serverLayoutURL+'/img/ii.png',
							style: 'width:15px;height:15px',
							title: alertMsg,
							alt: ''
						})
					);
				}
				$('#ImpcliEditForm'+impcliid).submit(function(){

					if (!confirm(alertMsg)) {
						alert('No se ha modificado el Impuesto del cliente');
						return false;
					}
					//serialize form data
					var formData = $(this).serialize();
					//get form action
					var formUrl = $(this).attr('action');
					$.ajax({
						type: 'POST',
						url: formUrl,
						data: formData,
						success: function(data,textStatus,xhr){
							callAlertPopint("Impuesto Modificado");
						},
						error: function(xhr,textStatus,error){
							callAlertPopint("Impuesto NO Modificado. Intente de nuevo mas Tarde");
						}
					});
					return false;

				});
			},

			error:function (XMLHttpRequest, textStatus, errorThrown) {
				callAlertPopint(textStatus);
				callAlertPopint(XMLHttpRequest);
				callAlertPopint(errorThrown);
			}
		});
	});
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
function cargarAsiento(){
	var apagarMonotributo = $('#apagarMonotributo').val()*1;
	var apagarAutonomo = $('#apagarAutonomo').val()*1;
	var apagarObrasocial = $('#apagarObrasocial').val()*1;
	// 50140005	Monotributo
	if($('#cuenta2548').length > 0){
		var orden = $('#cuenta2548').attr('orden');
		var apagar = apagarMonotributo+apagarAutonomo+apagarObrasocial;
		$('#Asiento0Movimiento'+orden+'Debe').val(apagar);
	}
	// 210401805 Monotributo A PAGAR
	if($('#cuenta1481').length > 0){
		var orden = $('#cuenta1481').attr('orden');
		var apagar = apagarMonotributo+apagarAutonomo+apagarObrasocial;
		$('#Asiento0Movimiento'+orden+'Haber').val(apagar);
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