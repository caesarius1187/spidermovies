$(document).ready(function() {
           					$("#tablaTareasClientes").dataTable( { 
        						"sPaginationType": "full_numbers"		
    						});	
        				});


function loadTareaId(id){
	$('#tareasxclientesxestudioIdtarea').val(id);
	location.href='#popincambiarresp';
}