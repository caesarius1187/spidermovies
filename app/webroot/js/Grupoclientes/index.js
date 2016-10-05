$(document).ready(function() {
    $("#liclientes").addClass("active");
    var iTableHeight = $(window).height();
    iTableHeight = (iTableHeight < 200) ? 200 : (iTableHeight - 330);

	$("#tblListaGrupoClientes").dataTable( { 
		"sPaginationType": "full_numbers",
		"sScrollY": iTableHeight + "px",
	    "bScrollCollapse": true,
	    "iDisplayLength":50,
	});	
});

function editarGrupo(GrupoId)
{
	//alert(GrupoId)
	//alert(serverLayoutURL + "/Grupoclientes/editajax/" + GrupoId)
	var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL + "/Grupoclientes/editajax/" + GrupoId,

        // URL to request
        data: data,  // post data
        success: function(response) {
         					$("#form_editar_grupocliente").html(response);
         					location.href='#editar_grupocliente';
         					$("#form_editar_grupocliente").width("600");

                       },	                  
       error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus + ". " + XMLHttpRequest.status + ": " + errorThrown);
            //alert(textStatus);
		 	//alert(XMLHttpRequest);
		 	//alert(errorThrown);
       }
    });
}
/*
function agregarGrupoCliente()
{
	nomGrupo = $('#form_grupocliente #nombre').val();
	descGrupo = $('#form_grupocliente #descripcion').val();
	estado = "habilitado";
	estudioId = $('#estudio_id').val();

	var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        // URL to request
        url: "/sigesec/Grupoclientes/addGrupoCliente/"+nomGrupo+"/"+descGrupo+"/"+estado+"/"+estudioId, 
        //url: "/sigesec/Grupoclientes/addGrupoCliente/",
        data: data,  // post data
        //data: {
        //  'nomGrupo': nomGrupo, 
        //  'descGrupo': descGrupo,
        //  'estado': estado,
        //  'estudioId': estudioId
        //},
        dataType: "json",
        success: function(response) 
        {   
			if(response.respuesta != 'error')
			{
				
				alert(response.respuesta);				
				var sGrupoId = response.Grupocliente_id;
				$('#tblListaGrupoClientes').append('<tr class="even"><td>'+nomGrupo+'</td><td>'+descGrupo+'</td><td>'+estado+'</td><td class="actions"><a href="/sigesec/Grupoclientes/view/'+sGrupoId+'">View</a><a href="/sigesec/Grupoclientes/edit/'+sGrupoId+'">Edit</a><a href="/sigesec/Grupoclientes/delete/'+sGrupoId+'">delete</a></td></tr>');				
				
			}
			else
			{				
				alert('Se produjo un error al agregar el Grupo de Clientes');
				return;
			}				
       },                  
       error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus + ". " + XMLHttpRequest.status + ": " + errorThrown);
		 	//alert(XMLHttpRequest.error);
		 	//alert(XMLHttpRequest.status);		 	
		 	//alert(errorThrown);
       }
    });
}*/