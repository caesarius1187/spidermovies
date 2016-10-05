$(document).ready(function() {
	var htmltabla = $("#divContenedor").html();
	$('#clientesComparativolistaclienteForm').submit(function(){
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
				$.each(respuesta.clientes, function(i, item) {
					var $trCli = "";
					var $trTitulo = "";
					//Agregando o buscando una row para el nombre del cliente y el periodo
					if(!$('#clientesShownombre').prop('checked')){
						$("#divContenedor").html(htmltabla);
						//This is the first time;
						//Row de nombre de cliente
				        $trCli = $('<tr class="tbl_border" id="cliente'+item.Cliente.id+'">'
				        	).append(
				            	$('<th class="tbl_border"  width="200" colspan="20">').text(item.Cliente.nombre)			          
				       		).appendTo('#tblClienteBody');

						//Row de titulo de impuesto
						 $trTitulo = $('<tr class="tbl_border" id="tituloimpuesto'+item.Cliente.id+'">'
				        	).append(
				            	$('<th class="tbl_border" width="200">').text('Tributario')			          
				       		).appendTo('#tblClienteBody');
					}else{
						//this is not the first time
						$trCli = $('#cliente'+item.Cliente.id);
						$trTitulo = $('#tituloimpuesto'+item.Cliente.id);
					}

					$trTitulo.append(
				            $('<th class="tbl_border" width="150">').text($("#clientesPeriodomes option:selected").text()+'-'+$("#clientesPeriodoanio option:selected").text())			          
				        );						
				    var totalCliente = 0;
					$.each(item.Impcli, function(i, implci) {
						var $trImpcli = "";
						if(!$('#clientesShownombre').prop('checked')){
							//This is the first time;
							$trImpcli = $('<tr class="tbl_border"  id="impuesto'+ implci.id +'">'
								).append(
				            		$('<td class="tbl_border" width="150">').text(implci.Impuesto.nombre)			          
				        		).appendTo('#tblClienteBody');
						}else{
							//this is not the first time
							$trImpcli = $('#impuesto'+implci.id);
						}
						
			        	var subtotalimpcli = 0;
						$.each(implci.Eventosimpuesto, function(i, eventoimpuesto) {
							subtotalimpcli += eventoimpuesto.montovto*1;
							totalCliente += eventoimpuesto.montovto*1;
						});
						$trImpcli.append(
			            	$('<td class="tbl_border" width="150">').text("$"+subtotalimpcli.toFixed(2))			          
			        	);
					});
					var $trTotal = "";
					if(!$('#clientesShownombre').prop('checked')){
						//Row de serapacion					
			        	$trTotal = $('<tr class="tbl_border"  id="total'+item.Cliente.id+'">'
							).append(
			            		$('<td class="tbl_border" width="150">').text("Total")			          
			        		).appendTo('#tblClienteBody');
			        	var $trSeparador = $('<tr>'
			        	).append(
			            	$('<th colspan="20">').html('<hr color="#000000" size="2px" width="100%" style="margin: 10px 0px 10px 0px;">')			          
			       		).appendTo('#tblClienteBody');	
					}else{
						$trTotal = $('#total'+item.Cliente.id);
						
					}
					$trTotal.append(
			            		$('<td class="tbl_border" width="150">').text("$"+totalCliente.toFixed(2))
			            		);
						 
			    });
				$('#clientesShownombre').prop('checked', true);
			}, 
			error: function(xhr,textStatus,error){ 
				callAlertPopint(textStatus); 
			} 
		});	
		return false; 
	});
   	$('.chosen-select').chosen();
	$('#clientesGclis').change(function(e){
		$('#clientesShownombre').prop('checked',false)
	});

});
function mesSiguiente(){
	var selected_element = $('#clientesPeriodomes option:selected');
	selected_element.removeAttr('selected');
	selected_element.next().attr('selected', 'selected');
	$('#clientesPeriodomes').val(selected_element.next().val());
}
function anoSiguiente(){
	var selected_element = $('#clientesPeriodoanio option:selected');
	selected_element.removeAttr('selected');
	selected_element.next().attr('selected', 'selected');
	$('#clientesPeriodoanio').val(selected_element.next().val());
}
