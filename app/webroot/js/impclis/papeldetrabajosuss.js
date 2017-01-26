var ordenTxt = 0;
$(document).ready(function() {
	papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
	var tblDatosAIngresar = $('#tblDatosAIngresar');
    tblDatosAIngresar.floatThead();
    ordenTxt = 0;
	cargartxt();
    $("#EmpleadoPapeldetrabajosussForm input").change(function(){
        cargartxt();
    });
	cargarAsiento();
	catchAsiento();
    CambiarTab("papeldetrabajo");
});

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
    /*FIN Aportes Sindicales*/

    // 210402201	Cooperadora Asistencial a Pagar /*Este concepto debe estar en el pdt de Cooperadora Asistensial*/
	// 210301001	Sueldos - Personal XX
	if($('#cuenta1378').length > 0){
		var orden = $('#cuenta1378').attr('orden');
		var redondeoTotal = $("#redondeoTotal").val();
		var RemuneracionTotal = $("#RemuneracionTotal").val();
		var apagar301EmpleadorAportesSegSocial = $("#apagar301EmpleadorAportesSegSocial").val();
		var apagar302AportesObrasSociales = $("#apagar302AportesObrasSociales").val();
		// =(redondeoTotal+RemuneracionTotal)-apagar301EmpleadorAportesSegSocial-apagar302AportesObrasSociales-SUMA(E32:E43)
		var sueldospersonal =
			(redondeoTotal*1+RemuneracionTotal*1)-
			apagar301EmpleadorAportesSegSocial*1-
			apagar302AportesObrasSociales*1-
			totalAportesSindicales*1;
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
		//$('#EventosimpuestoRealizartarea5Form').css('width','1500');
        var cantItems = $('#Eventosimpuesto0CantItems').val();
		for (var i = 0 ; i < cantItems; i++) {
			var item = $('#Eventosimpuesto'+i+'Item').val();
            var apagarItem = $('#apagar'+item+'');
            var apagar = 0;
            if(apagarItem.length>0){
                apagar = apagarItem.val();
            }
			if($('#Eventosimpuesto'+i+'Id').val()==0){//El Evento Impuesto no a sido creado previamente entonces vamos a guardar el monto que calculamos
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
	  },
	 error:function (XMLHttpRequest, textStatus, errorThrown) {
	    alert(textStatus);
	    
	 }
	});
  return false;
}
function CambiarTab(sTab)
{
	$("#tabPdT").attr("class", "cliente_view_tab");
	$("#tabLiquidacion").attr("class", "cliente_view_tab");
	$("#tabExportacion").attr("class", "cliente_view_tab");
	$("#tabContabilidad").attr("class", "cliente_view_tab");

	if(sTab == "papeldetrabajo")
	{
		$("#tabPdT").attr("class", "cliente_view_tab_active");
		$("#sheetCooperadoraAsistencial").show();
		$("#divLiquidarSUSS").hide();
		$("#divExportacion").hide();
		$("#divContenedorContabilidad").hide();
	}
	if (sTab == "liquidacion")
	{
		$("#tabLiquidacion").attr("class", "cliente_view_tab_active");
		$("#sheetCooperadoraAsistencial").hide();
		$("#divLiquidarSUSS").show();
		$("#divExportacion").hide();
		$("#divContenedorContabilidad").hide();
	}
	if(sTab == "exportar")
	{
		$("#tabExportacion").attr("class", "cliente_view_tab_active");
		$("#sheetCooperadoraAsistencial").hide();
		$("#divLiquidarSUSS").hide();
		$("#divExportacion").show();
		$("#divContenedorContabilidad").hide();
	}
	if (sTab == "contabilidad")
	{
		$("#tabContabilidad").attr("class", "cliente_view_tab_active");
		$("#sheetCooperadoraAsistencial").hide();
		$("#divLiquidarSUSS").hide();
		$("#divExportacion").hide();
		$("#divContenedorContabilidad").show();
	}
}
