$(document).ready(function() {
    $( "#clickExcel" ).click(function() {
        if($('#divRecategorizacion').is(':visible')){
            $("#divRecategorizacion").table2excel({
                // exclude CSS class
                exclude: ".noExl",
                name: "Monotributo",
                filename:$('#clinombre').val()+"-"+ $('#periodoPDT').val()+"-"+"Recategoizacion"
            });
        }else{
            $("#divDDJJ").table2excel({
                // exclude CSS class
                exclude: ".noExl",
                name: "Monotributo",
                filename:$('#clinombre').val()+"-"+ $('#periodoPDT').val()+"-"+"DDJJ"
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
		$('#divLiquidarMonotributo').hide();
		$('#tabsTareaMonotributo').hide();
		$('.btn_imprimir').hide();

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
		$('#divLiquidarMonotributo').show();
		 $('#tabsTareaMonotributo').show();
		 $('.btn_imprimir').show();
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
				var mesParaProximaRecategorizacion = $("#mesParaProximaRecategorizacion").val();
				if(mesParaProximaRecategorizacion==4){
					//Aca se recategoriza sino no hay q cambiar nada en el impcli
					$("#ImpcliCategoriamonotributo").after(
						$('<img>').attr({
							src: serverLayoutURL+'/img/ii.png',
							style: 'width:15px;height:15px',
							title: 'Se cambiara de la categoria '+$('#ImpcliCategoriamonotributo').val()+' a la categoria '+$('#topCategoria').val(),
							alt: ''
						})
					);
					$("#ImpcliCategoriamonotributo").val($('#topCategoria').val());
				}else{
					$("#ImpcliCategoriamonotributo").after(
						$('<img>').attr({
							src: serverLayoutURL+'/img/ii.png',
							style: 'width:15px;height:15px',
							title: 'NO Se cambiara de la categoria '+$('#ImpcliCategoriamonotributo').val()+' a la categoria '+$('#topCategoria').val()
								+' Por que no es mes de impacto de recategorizacion.',
							alt: ''
						})
					);
				}
				$('#ImpcliEditForm'+impcliid).submit(function(){
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
							callAlertPopint("Deposito NO Modificado. Intente de nuevo mas Tarde");
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