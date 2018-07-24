var ordenTxt = 0;
$(document).ready(function() {
	$( "#clickExcel" ).click(function() {

		if (!document.getElementById("pdtSUSS_tr1"))
		{
	    	$("#tblExcelHeader").prepend(
	    		$("<tr id='pdtSUSS_tr1'>").append(
					$("<td style='display:none'>")
						.attr("colspan","25")
						.html("Contribuyente: " + $('#clinombre').val() + " - CUIT: " + $('#nroCuitContribuyente').html())						
	    			)
	    		);
    	}
    	if (!document.getElementById("pdtSUSS_tr2"))
    	{
    		$("#tblExcelHeader").prepend(
	    		$("<tr id='pdtSUSS_tr2'>").append(
					$("<td style='display:none'>")
						.attr("colspan","25")
						.html($('#tipoorganismoyNombre').html() + " - Periodo: "+ $('#periodoPDT').val())				
	    			)
	    		);
    	}

		$("#sheetCooperadoraAsistencial").table2excel({
			// exclude CSS class
			exclude: ".noExl",
			name: "SUSS",
			filename:"Exportacion_SUSS"			
		});
	});
	papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
    ordenTxt = 0;
	cargartxt();
    $("#EmpleadoPapeldetrabajosussForm input").change(function(){
        cargartxt();
    });

    var beforePrint = function() {
		$('#header').hide();				
		$('#clickExcel').hide();
		$('#btnImprimir').hide();
		$('#divPrepararPapelesDeTrabajo').hide();
        var tblDatosAIngresar = $('#tblDatosAIngresar');
    	tblDatosAIngresar.floatThead('destroy');
	};
	var afterPrint = function() {		
		$('#header').show();				
		$('#clickExcel').show();
		$('#btnImprimir').show();
		$('#divPrepararPapelesDeTrabajo').show();  		
		var tblDatosAIngresar = $('#tblDatosAIngresar');
    	tblDatosAIngresar.floatThead();  
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
    $("#ConceptosrestanteAlicuota").on('change',function(){
        var alicuota = $(this).val();
        var baseImponible = $("#ConceptosrestanteBaseimponible").val();
        var dcto814=baseImponible*alicuota/100;
         $("#ConceptosrestanteMontoretenido").val(dcto814);
    });    
    $("#ConceptosrestanteBaseimponible").on('change',function(){
        var alicuota = $("#ConceptosrestanteAlicuota").val();
        var baseImponible = $(this).val();
        var dcto814=baseImponible*alicuota/100;
         $("#ConceptosrestanteMontoretenido").val(dcto814.toFixed(2));
    });    
    $("#ConceptosrestanteBaseimponible").trigger('change');
    addFormSubmitCatchs();
});
function openWin(){
	window.print();
}
/*
function openWin()
{

	var myWindow=window.open('','','width=1010,height=1000px');
	myWindow.document.write('<html><head><title>SUSS</title><link rel="stylesheet" type="text/css" href="'+serverLayoutURL+'/css/cake.generic.css"></head><body>');
	myWindow.document.write($("#sheetCooperadoraAsistencial").html());
	myWindow.document.close();
	myWindow.focus();
	setTimeout(
		function()
		{
			myWindow.print();
			myWindow.close();

		}, 1000);
}
*/
function guardarDcto814(){
    $('#myModalAddConceptosrestante').on('show.bs.modal', function() {
        //$('#myModalAddConceptosrestante').find('.modal-title').html('Asiento automatico de venta');
        //$('#myModalAddConceptosrestante').find('.modal-body').html(response);
        // $('#myModal').find('.modal-footer').append($('<button>', {
        //     type:'button',
        //     datacontent:'remove',
        //     class:'btn btn-primary',
        //     id:'editRowBtn',
        //     onclick:"$('#BienesdeusoRelacionarventaForm').submit()",
        //     text:"Aceptar"
        // }));
         $('#myModalAddConceptosrestante').find('.modal-footer').html("");
        $('#myModalAddConceptosrestante').find('.modal-footer').append($('<button>', {
            type:'button',
            datacontent:'remove',
            class:'btn btn-primary',
            id:'editRowBtn',
            onclick:" $('#myModalAddConceptosrestante').modal('hide')",
            text:"Cerrar"
        }));
    });
    $('#myModalAddConceptosrestante').modal('show');
    $('.chosen-select-cuenta').chosen({
        search_contains:true,
        include_group_label_in_selected:true
    });
    $('#AsientoAddForm').submit(function(){
        $('#myModal').modal('hide');
        //serialize form data
        var formData = $(this).serialize();
        //get form action
        var formUrl = $(this).attr('action');
        //aca tenemos que sacar todos los disabled para que se envien los campos
        $('#AsientoAddForm input').each(function(){
            $(this).removeAttr('disabled');
        });
        $.ajax({
            type: 'POST',
            url: formUrl,
            data: formData,
            success: function(data,textStatus,xhr){
                var respuesta = JSON.parse(data);
                callAlertPopint(respuesta.respuesta);
            },
            error: function(xhr,textStatus,error){
                $('#myModal').modal('show');
                alert(textStatus);
            }
        });
        return false;
    });
}
function cargarAsiento(){
	// 503020017	Mano de Obra Salta
	if($('#cuenta2250').length > 0){
		var orden = $('#cuenta2250').attr('orden');
		var RemuneracionTotal = $("#RemuneracionTotal").val();
		$('#Asiento0Movimiento'+orden+'Debe').val(RemuneracionTotal);
	}
	// 503030001	Contr. Seg. Social
	if($('#cuenta2253').length > 0){
		var orden = $('#cuenta2253').attr('orden');
		var apagar351ContribucionesSegSocial = $("#apagar351ContribucionesSegSocial").val();
		$('#Asiento0Movimiento'+orden+'Debe').val(apagar351ContribucionesSegSocial);
	}

	// 503030002	Contr. Obra Social
	if($('#cuenta2254').length > 0){
		var orden = $('#cuenta2254').attr('orden');
		var apagar352ContribucionesObraSocial = $("#apagar352ContribucionesObraSocial").val();
		$('#Asiento0Movimiento'+orden+'Debe').val(apagar352ContribucionesObraSocial);
	}
	// 503030003	ART
	if($('#cuenta2255').length > 0){
		var orden = $('#cuenta2255').attr('orden');
		var apagar312AsegRiesgodeTrabajoL24557 = $("#apagar312AsegRiesgodeTrabajoL24557").val();
		$('#Asiento0Movimiento'+orden+'Debe').val(apagar312AsegRiesgodeTrabajoL24557);
	}
	// 503030004	Seguro de Vida Colectivo
	if($('#cuenta2256').length > 0){
		var orden = $('#cuenta2256').attr('orden');
		var apagar28SegurodeVidaColectivo = $("#apagar28SegurodeVidaColectivo").val();
		$('#Asiento0Movimiento'+orden+'Debe').val(apagar28SegurodeVidaColectivo);
	}
	// 503030005	RENATRE
	if($('#cuenta2257').length > 0){
		var orden = $('#cuenta2257').attr('orden');
		var apagar360ContribucionRENATEA = $("#apagar360ContribucionRENATEA").val();
		$('#Asiento0Movimiento'+orden+'Debe').val(apagar360ContribucionRENATEA);
	}
    // 210302062	Ap. RENATEA a Pagar
    if($('#cuenta1404').length > 0){
		var orden = $('#cuenta1404').attr('orden');
		var apagar935RENATEA = $("#apagar935RENATEA").val();
		$('#Asiento0Movimiento'+orden+'Haber').val(apagar935RENATEA);
	}
    /*
    *INACAP es un impuesto que se paga si el empleador es comercio asi que vamos a darlo de alta manualmente
    * pero si esta dado de alta vamos a usar este monto para completar ese campo
    *
    * =12905,75*0,005
    * 
    * */
	/*Contribuciones Sindicales */
	// 503030025	Contr. INACAP
	// 503030009	Contr. Seg. De Vida Oblig. Mercantil
	// 503030042	Contr. UTHGRA
	// 503030072	Contr. UOCRA
	// 503030082	Contr. UOM
    // 210303025	Contr. INACAP a Pagar
    /*Fin Contribuciones Sindicales*/

	// 506220001	Cooperadora Asistencial /*Este concepto debe estar en el pdt de Cooperadora Asistensial*/

	// 504990014	Otros Gastos
	if($('#cuenta2345').length > 0){
		var orden = $('#cuenta2345').attr('orden');
		var redondeoTotal = $("#redondeoTotal").val();
		$('#Asiento0Movimiento'+orden+'Debe').val(redondeoTotal);
	}


	// 110403901	Seg. Social - Retenciones/*Este concepto carga retenciones (que no estamos teniendo en cuenta en el papel de trabajo)*/


	// 210302001	Ap. Seguridad Social a Pagar
	if($('#cuenta1383').length > 0){
		var orden = $('#cuenta1383').attr('orden');
		var apagar301EmpleadorAportesSegSocial = $("#apagar301EmpleadorAportesSegSocial").val();
		$('#Asiento0Movimiento'+orden+'Haber').val(apagar301EmpleadorAportesSegSocial);
	}
	// 210302002	Ap. Obra Social a Pagar
	if($('#cuenta1384').length > 0){
		var orden = $('#cuenta1384').attr('orden');
		var apagar302AportesObrasSociales = $("#apagar302AportesObrasSociales").val();
		$('#Asiento0Movimiento'+orden+'Haber').val(apagar302AportesObrasSociales);
	}
	// 210303001	Contr. Seg. Social a Pagar
    if($('#cuenta1419').length > 0){
        var orden = $('#cuenta1419').attr('orden');
        var apagar351ContribucionesSegSocial = $("#apagar351ContribucionesSegSocial").val();
        $('#Asiento0Movimiento'+orden+'Haber').val(apagar351ContribucionesSegSocial);
    }
    // 210303002	Contr. Obra Social a Pagar
    if($('#cuenta1420').length > 0){
        var orden = $('#cuenta1420').attr('orden');
        var apagar352ContribucionesObraSocial = $("#apagar352ContribucionesObraSocial").val();
        $('#Asiento0Movimiento'+orden+'Haber').val(apagar352ContribucionesObraSocial);
    }
	// 210303003	ART a Pagar
    if($('#cuenta1421').length > 0){
        var orden = $('#cuenta1421').attr('orden');
        var apagar312AsegRiesgodeTrabajoL24557 = $("#apagar312AsegRiesgodeTrabajoL24557").val();
        $('#Asiento0Movimiento'+orden+'Haber').val(apagar312AsegRiesgodeTrabajoL24557);
    }
	// 210303004	Seguro de Vida Colectivo a Pag
    if($('#cuenta1422').length > 0){
        var orden = $('#cuenta1422').attr('orden');
        var apagar28SegurodeVidaColectivo = $("#apagar28SegurodeVidaColectivo").val();
        $('#Asiento0Movimiento'+orden+'Haber').val(apagar28SegurodeVidaColectivo);
    }
	// 210303005	RENATRE a pagar	!= de renatea????
    if($('#cuenta1423').length > 0){
        var orden = $('#cuenta1423').attr('orden');
        var apagar360ContribucionRENATEA = $("#apagar360ContribucionRENATEA").val();
        $('#Asiento0Movimiento'+orden+'Haber').val(apagar360ContribucionRENATEA);
    }
	// 210301002	Embargos
    if($('#cuenta1379').length > 0){
        var orden = $('#cuenta1379').attr('orden');
        var apagarEmbargos = $("#apagarEmbargos").val();
        $('#Asiento0Movimiento'+orden+'Haber').val(apagarEmbargos);
    }

    /*Aportes Sindicales*/
	// 210302021
	// 210302024
	// 210302031
	// 210302038
	// 210302041
	// 210302051
	// 210302052
	// 210302061
	// 210302062
	// 210302071
	// 210302081
	// 210302091
	var totalAportesSindicales=0;
        if($("#cuentasdeSUSSAportesSindicatos").length>0){
            var cuentasdeSUSSAportesSindicatos = jQuery.parseJSON($("#cuentasdeSUSSAportesSindicatos").val());

            cuentasdeSUSSAportesSindicatos.forEach(
                    function(item,index){
                            var cuenta = '#cuenta'+item;
                            if($(cuenta).length > 0){
                                    var orden = $(cuenta).attr('orden');
                                    var aporte = $('#Asiento0Movimiento'+orden+'Haber').val()*1;
                                    totalAportesSindicales += aporte;
                            }
                    }
            );
        }
	
    /*FIN Aportes Sindicales*/

    // 210402201	Cooperadora Asistencial a Pagar /*Este concepto debe estar en el pdt de Cooperadora Asistensial*/
	// 210301001	Sueldos - Personal XX
	if($('#cuenta1378').length > 0){
		var orden = $('#cuenta1378').attr('orden');
		var redondeoTotal = $("#redondeoTotal").val();
		var RemuneracionTotal = $("#RemuneracionTotal").val();
		var apagar301EmpleadorAportesSegSocial = $("#apagar301EmpleadorAportesSegSocial").val();
		var apagar302AportesObrasSociales = $("#apagar302AportesObrasSociales").val();
		var apagarEmbargos = $("#apagarEmbargos").val();
        var apagar935RENATEA = $("#apagar935RENATEA").val();
// =(redondeoTotal+RemuneracionTotal)-apagar301EmpleadorAportesSegSocial-apagar302AportesObrasSociales-SUMA(E32:E43)
		var sueldospersonal =
			(redondeoTotal*1+RemuneracionTotal*1)-
			apagar301EmpleadorAportesSegSocial*1-
			apagar302AportesObrasSociales*1-
			apagarEmbargos*1-
                        //apagar935RENATEA*1-
			totalAportesSindicales*1;
		sueldospersonal = parseFloat(sueldospersonal).toFixed(2);
		$('#Asiento0Movimiento'+orden+'Haber').val(sueldospersonal);
		//Falta restar APORTES DE Sindicatos
	}

    /*Aca vamos a identificar la cuenta de perdida Contribucion INACAP A PAGAR y vamos asignarle el valor de su debe
    * en la cuenta de pasivo Contribucion INACAP*/
	if($('#cuenta1427').length > 0){
		var orden = $('#cuenta1427').attr('orden');
		var cuentasdeSUSSContribucionesSindicatos = jQuery.parseJSON($("#cuentasdeSUSSContribucionesSindicatos").val());
		cuentasdeSUSSContribucionesSindicatos.forEach(
			function(item,index){
				var cuenta = '#cuenta'+item;
				if($(cuenta).length > 0){
					var ordencont = $(cuenta).attr('orden');
					var cuentanombre = $('#Asiento0Movimiento'+ordencont+'Nombre').val();
					if(cuentanombre=='Contribucion-INACAP-'){
						var debe = $('#Asiento0Movimiento'+ordencont+'Debe').val()*1;
						$('#Asiento0Movimiento'+orden+'Haber').val(debe);
					}
				}
			}
		);
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
function pad(n, width, z) {
    z = z || '0';
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}
function cargartxt(){
    $("#txtareaexportar").html("");
	$("#EmpleadoPapeldetrabajosussForm input").each(function(index){
		var ordenInput = $(this).attr('orden');
        if (typeof ordenInput === "undefined") {
            return;
        }
        var ordenTxt=  $("#orden").val();
        var cantDigitos = $(this).attr('maxLength');
        var miInputValue = pad($(this).val(), cantDigitos,'-');
        var linebreaker="";
        if((ordenInput*1) != (ordenTxt*1) &&(ordenInput*1)!=0){
            var linebreaker="\n";
            ordenTxt = ordenInput
            $("#orden").val(ordenInput*1);
            $("#txtareaexportar").html($("#txtareaexportar").html()+"\n"+miInputValue);
        }else{
            $("#txtareaexportar").html($("#txtareaexportar").html()+miInputValue);
        }
	});
}
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
        $('.btn_cancelar').hide();
        //$('#EventosimpuestoRealizartarea5Form').css('width','1500');
        var cantItems = $('#Eventosimpuesto0CantItems').val();
		for (var i = 0 ; i < cantItems; i++) {
			var item = $('#Eventosimpuesto'+i+'Item').val();
            var apagarItem = $('#apagar'+item+'');
            var apagar = 0;
            if(apagarItem.length>0){
                apagar = apagarItem.val();
            }
			if($('#Eventosimpuesto'+i+'Id').val()==0){
				//El Evento Impuesto no a sido creado previamente entonces vamos a guardar el monto que calculamos
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
					  callAlertPopint(resp);
					  $("#AsientoAddForm").submit();
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
		  var tblDatosAIngresar = $('#tblDatosAIngresar');
		  tblDatosAIngresar.floatThead();
	  },
	 error:function (XMLHttpRequest, textStatus, errorThrown) {
	    alert(textStatus);
	    
	 }
	});
  return false;
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
function addFormSubmitCatchs(){
        $('#saveConceptosrestantesForm').submit(function(){
            //serialize form data
            var formData = $(this).serialize();
            //get form action
            var formUrl = $(this).attr('action');
            //Controles de inputs
            $.ajax({
                type: 'POST',
                url: formUrl,
                data: formData,
                success: function(data,textStatus,xhr){
                    var respuesta = JSON.parse(data);
                    callAlertPopint(respuesta.respuesta);
                },
                error: function(xhr,textStatus,error){
                    alert(error);
                }
            });
            return false;
        });
    }