$(document).ready(function() { 
	$('#VencimientoAddForm').submit(function(){ 
		var anio = $( "#anoYear" ).val();
		var anobien=anio!=null || anio!="";
		if(anobien){
			return true; 
		}else{
			return false; 
		}
	});
	$("#impuesto_id").on('change', function() {
		$('.selectImpuestoId').val($("#impuesto_id").val());
	});
	$( "#impuesto_id" ).trigger( "change" );
	$("#anoYear").on('change', function() {
		$('.selectAno').val($("#anoYear").val());
	});
	$( "#anoYear" ).trigger( "change" );
});	
function agregarColumna(){
	var orden = $('#Vencimiento0Orden').val();
	orden++;
	$('#trCuit')
	.append($('<td>')
		.append($('<div>')
   			.attr('class', "input number")
			.append(
				$('<label>')
				.html('Desde')
		       	.attr('for', "Vencimiento"+orden+"Desde")
	       	)
			.append(
				$('<input>')
		       	.attr('name', "data[Vencimiento]["+orden+"][desde]")
		       	.attr('id', "Vencimiento"+orden+"Desde")
		       	.attr('required', "required")
		       	.attr('type', "number")
	       	)
       	)
	    .append($('<div>')
   			.attr('class', "input number")   	
	       	.append(
				$('<label>')
				.html('Hasta')
		       	.attr('for', "Vencimiento"+orden+"Hasta")
	       	)
			.append(
				$('<input>')
		       	.attr('name', "data[Vencimiento]["+orden+"][hasta]")
		       	.attr('id', "Vencimiento"+orden+"Hasta")
		       	.attr('required', "required")
		       	.attr('type', "number")
	       	)
	       	.append(
				$('<input>')
		       	.attr('name', "data[Vencimiento]["+orden+"][impuesto_id]")
		       	.attr('id', "Vencimiento"+orden+"ImpuestoId")
		       	.attr('required', "required")
		       	.attr('type', "hidden")
		       	.attr('class', "selectImpuestoId")
	       	)
	       	.append(
				$('<input>')
		       	.attr('name', "data[Vencimiento]["+orden+"][ano]")
		       	.attr('id', "Vencimiento"+orden+"Ano")
		       	.attr('required', "required")
		       	.attr('type', "hidden")
		       	.attr('class', "selectAno")
	       	)
       	)
	)

	agregarTD('tr1',orden,'p01','P01');
	agregarTD('tr2',orden,'p02','P02');
	agregarTD('tr3',orden,'p03','P03');
	agregarTD('tr4',orden,'p04','P04');
	agregarTD('tr5',orden,'p05','P05');
	agregarTD('tr6',orden,'p06','P06');
	agregarTD('tr7',orden,'p07','P07');
	agregarTD('tr8',orden,'p08','P08');
	agregarTD('tr9',orden,'p09','P09');
	agregarTD('tr10',orden,'p10','P10');
	agregarTD('tr11',orden,'p11','P11');
	agregarTD('tr12',orden,'p12','P12');
	$('#Vencimiento0Orden').val(orden);
	$( "#impuesto_id" ).trigger( "change" );
	$( "#anoYear" ).trigger( "change" );

}
function agregarTD(row,orden,minField,MayField){
	$('#'+row)
	.append($('<td>')
		.append($('<div>')
       		.attr('class', "input number")
			.append(
				$('<input>')
		       	.attr('name', "data[Vencimiento]["+orden+"]["+minField+"]")
		       	.attr('id', "Vencimiento"+orden+MayField)
		       	.attr('required', "required")
		       	.attr('type', "number")
	       	)
       	)
   	);
}