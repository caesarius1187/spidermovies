$(document).ready(function() {
	$( "#clickExcel" ).click(function() {
		$("#pdtactividadesvarias").table2excel({
			// exclude CSS class
			exclude: ".noExl",
			name: "ActividadesVarias",
			filename:$('#clinombre').val()+"-"+ $('#periodoPDT').val()+"-"+"ActividadesVarias"
		});
	});
    papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
	var beforePrint = function() {
		console.log('Functionality to run before printing.');
		$('#header').hide();
		$('#Formhead').hide();
		$('#divLiquidarActividadesVariar').hide();
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