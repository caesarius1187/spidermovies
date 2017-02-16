$(document).ready(function() {
	$( "#clickExcel" ).click(function() {
		$("#pdtactividadesvarias").prepend(
    		$("<tr>").append(
				$("<td>")
					.attr("colspan","25")
					.html($('#clinombre').val()+"-"+ $('#periodoPDT').val()+"-"+"ActividadesVarias")
    			)
    		);
		$("#pdtactividadesvarias").table2excel({
			// exclude CSS class
			exclude: ".noExl",
			name: "ActividadesVarias",
			filename:$('#clinombre').val()+"-"+ $('#periodoPDT').val()+"-"+"ActividadesVarias"
		});
	});
    papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
	var beforePrint = function() {
		$('#header').hide();
		$('#Formhead').hide();
		$('#divLiquidarActividadesVariar').hide();
		$('#index').css('float','left');
		$('#padding').css('padding','0px');
		$('#index').css('font-size','10px');
		$('#index').css('border-color','#FFF');
	};
	var afterPrint = function() {
		$('#index').css('font-size','14px');
		$('#header').show();
		$('#Formhead').show();
		$('#divLiquidarActividadesVariar').show();
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
			if($('#Eventosimpuesto'+i+'Id').val()==0){//El Evento Impuesto no a sido creado previamente entonces vamos a guardar el monto que calculamos
				var localidad = $('#Eventosimpuesto'+i+'LocalidadeId').val();
				var apagar = $('#apagar'+localidad).val();
				var afavor = $('#afavor'+localidad).val();
				$('#Eventosimpuesto'+i+'Montovto').val(apagar);
				$('#Eventosimpuesto'+i+'Monc').val(afavor);
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
}
function catchAsientoIVA(){
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
