var form_empleadoHTML = "";

jQuery(document).ready(function($) {
    $.noConflict();  //Not to conflict with other scripts
    $.fn.filterGroups = function( options ) {
        var settings = $.extend( {}, options);

        return this.each(function(){

            var $select = $(this);
            // Clone the optgroups to data, then remove them from dom
            $select.data('fg-original-groups', $select.find('optgroup').clone()).children('optgroup').remove();

            $(settings.groupSelector).change(function(){
                var $this = $(this);
                var $optgroup_label = $(this).find('option:selected').text();
                var $optgroup =  $select.data('fg-original-groups').filter('optgroup[label="' + $optgroup_label + '"]').clone();
                $select.children('optgroup').remove();
                $select.append($optgroup);
            }).change();
        });
    };
	/*Index*/
        $('#txtBuscarClintes').keyup(function () {
            var valThis = this.value.toLowerCase();
            //var lenght  = this.value.length;
            $('a[id^="lnkCliente_"]').each(function () {
                var oLabelObj = $(this).find('label');
                var text  = oLabelObj.html();
                var textL = text.toLowerCase();
                if (textL.indexOf(valThis) >= 0)
                {
                    //$(this).slideDown();
                    $(this).show();
                }
                else
                {
                    //$(this).slideUp();
                    $(this).hide();
                }
            });
            $('a[id^="lnkGrupoCliente_"]').each(function () {
                var textGpo  = $(this).html();
                var textLGpo = textGpo.toLowerCase();
                if (textLGpo.indexOf(valThis) >= 0)
                {
                    //$(this).slideDown();
                    $(this).show();
                }
                else
                {
                    //$(this).slideUp();
                    $(this).hide();
                }
            });
        });
        $('#txtBuscarClintesDeshabilitados').keyup(function () {
		var texto = this.value.toLowerCase();
		//var lenght  = this.value.length;
		$('a[id^="lnkClienteDeshab_"]').each(function () {
			var oLabelObjD = $(this).find('label');
			var textD  = oLabelObjD.html();
			var textLD = textD.toLowerCase();
			if (textLD.indexOf(texto) >= 0)
			{
				//$(this).slideDown();
				$(this).show();
			}
			else
			{
				//$(this).slideUp();
				$(this).hide();
			}
		});
		$('a[id^="lnkGpoClienteDeshab_"]').each(function () {
			var textGpoD  = $(this).html();
			var textLGpoD = textGpoD.toLowerCase();
			if (textLGpoD.indexOf(texto) >= 0)
			{
				//$(this).slideDown();
				$(this).show();
			}
			else
			{
				//$(this).slideUp();
				$(this).hide();
			}
		});
	});
	/*Labels*/
        $("#liclientes").addClass("active");
        showDatosCliente();
        activateLabelsFunctionality();
	/*Contribuyente*/
        $("#saveDatosPersonalesForm #ClienteTipopersona").on('change', function() {
            switch(this.value){
                case "fisica":
                    $("#saveDatosPersonalesForm #clienteEditLabelNombre").text("Apellido y Nombre");
                    $('#saveDatosPersonalesForm #ClienteTipopersonajuridica').val("");
                    $('#saveDatosPersonalesForm #ClienteTipopersonajuridica').closest( "div" ).hide();

                    $("#saveDatosPersonalesForm #ClienteDni").closest( "div" ).show();

                    $("#saveDatosPersonalesForm #ClienteModificacionescontrato").val("");
                    $("#saveDatosPersonalesForm #ClienteModificacionescontrato").closest( "div" ).hide();
                    break;
                case "juridica":
                    $("#saveDatosPersonalesForm #clienteEditLabelNombre").text("Razon Social");

                    $('#saveDatosPersonalesForm #ClienteTipopersonajuridica').closest( "div" ).show();

                    $("#saveDatosPersonalesForm #ClienteModificacionescontrato").closest( "div" ).show();

                    $("#saveDatosPersonalesForm #ClienteDni").val("");
                    $("#saveDatosPersonalesForm #ClienteDni") .closest( "div" ).hide();
                    break;
            }
            if($('#ClienteTipopersona').val()=='juridica'){
            }else{

            }
        });
        $("#saveDatosPersonalesForm #ClienteTipopersona").trigger('change');
        $('#saveDatosPersonalesForm').submit(function(){
            //serialize form data
            var formData = $(this).serialize();
            //get form action
            var formUrl = $(this).attr('action');
            $.ajax({
                type: 'POST',
                url: formUrl,
                data: formData,
                success: function(data,textStatus,xhr){
                    var respuesta = JSON.parse(data);
                    if(respuesta.error==0){
                        callAlertPopint(respuesta.respuesta);
                        loadFormEditarPersona();
                    }else{
                        //location.href="#close";
                        location.reload();
                    }
                },
                error: function(xhr,textStatus,error){
                    callAlertPopint(textStatus);
                }
            });
            return false;
        });
        $('#saveDatosPersonalesForm .ui-datepicker-trigger').datepicker().hide();
        $('#DomicilioAddForm').submit(function(){
            //serialize form data
            var formData = $(this).serialize();
            //get form action
            var formUrl = $(this).attr('action');
            $.ajax({
                type: 'POST',
                url: formUrl,
                data: formData,
                success: function(data,textStatus,xhr){
                    $("#relatedDomicilios").append(data);
                    catchEliminarDomicilio();
                    location.hash ="#x";
                },
                error: function(xhr,textStatus,error){
                    callAlertPopint(textStatus);
                }
            });
            return false;
        });
        catchEliminarDomicilio();
        $('#ActividadclienteAddForm').submit(function(){
            //serialize form data
            var formData = $(this).serialize();
            //get form action
            var formUrl = $(this).attr('action');
            $.ajax({
                type: 'POST',
                url: formUrl,
                data: formData,
                success: function(data,textStatus,xhr){
                    $("#relatedActividades").append(data);
                    $("#ActividadclienteAddForm #ActividadclienteActividadeId").find('option:selected').remove();
                    location.hash ="#x";
                },
                error: function(xhr,textStatus,error){
                    callAlertPopint(textStatus);
                }
            });
            return false;
        });
        $('#PersonasrelacionadaAddForm').submit(function(){
            //serialize form data
            var formData = $(this).serialize();
            //get form action
            var formUrl = $(this).attr('action');
            $.ajax({
                type: 'POST',
                url: formUrl,
                data: formData,
                success: function(data,textStatus,xhr){
                    $("#relatedPersonas").append(data);
                    location.hash ="#x";
                },
                error: function(xhr,textStatus,error){
                    callAlertPopint(textStatus);
                }
            });
            return false;
        });
        //esconder de datos personales la edicion y el row de aceptar
        $("#tableDatosPersonalesEdit :input").prop("disabled", true);
        $('#rowButtonsDetallesPersonales').hide();

	/*Organismos*/
        //Catch de los formularios que agregan impclis, periodos activos y modifican los periodos activos que ya existen
        catchImpCliAFIP();
        $("#FormImpcliAFIP #ImpcliImpuestoId").on('change', function() {
            if($(this).val()!=4/*Id Impuesto Monotributo*/){
                $("#FormImpcliAFIP #tdcategoriamonotributo").hide();
            }else{
                $("#FormImpcliAFIP #tdcategoriamonotributo").show();
            }
            if($(this).val()!=14/*Id Impuesto Autonomo*/){
                $("#FormImpcliAFIP #tdcategoriaautonomo").hide();
            }else{
                $("#FormImpcliAFIP #tdcategoriaautonomo").show();
            }

        });
        $("#FormImpcliAFIP #ImpcliImpuestoId").trigger("change");
        catchImpCliDGR();
        catchImpCliDGRM();
        catchImpCliSindicatos();
        catchImpCliBancos();
        //Catch de los formularios que modifican usuario y clave de los organismos AFIP DGR y DGRM
        catchFormOrganismoxCliente('formOrganismoAFIP');
        catchFormOrganismoxCliente('formOrganismoDGR');
        catchFormOrganismoxCliente('formOrganismoDGRM');

	/*Otros*/
        $('#SubclienteAddForm').submit(function(){
            location.hash ="#x";
            //serialize form data
            var formData = $(this).serialize();
            //get form action
            var formUrl = $(this).attr('action');
            $.ajax({
                type: 'POST',
                url: formUrl,
                data: formData,
                success: function(data,textStatus,xhr){
                    var mirespuesta = jQuery.parseJSON(data);
                    var subclienteID = mirespuesta.subcliente;
                    if(subclienteID!=0){
                        var subclienteID = mirespuesta.subcliente.Subcliente.id;
                        var rowData =
                        [
                            mirespuesta.subcliente.Subcliente.nombre,
                            mirespuesta.subcliente.Subcliente.cuit,
                            mirespuesta.subcliente.Subcliente.dni,
                        ];
                        var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="loadFormSubcliente('+subclienteID+')" alt="">';
                        tdactions = tdactions + '<form action="'+serverLayoutURL+'/Subclientes/delete/'+subclienteID+'" name="post_58b6e59f6102d291860796" id="post_58b6e59f6102d291860796" style="display:none;" method="post"><input type="hidden" name="_method" value="POST"></form>';
                        tdactions = tdactions + '<a href="#" class="deleteSubcliente"><img src="'+serverLayoutURL+'/img/ic_delete_black_24dp.png" width="20" height="20"  alt="Eliminar"></a>';
                        //onclick="eliminarSubcliente('+respuesta.compra_id+')"
                        rowData.push(tdactions);

                        var rowIndex = $('#subclientesDatatable').dataTable().fnAddData(rowData);
                        var row = $('#subclientesDatatable').dataTable().fnGetNodes(rowIndex);
                        $(row).attr( 'id', "rowSubcliente"+mirespuesta.subcliente.Subcliente.id );

                        catchEliminarSubcliente();
                    }
                    else{
                        callAlertPopint(mirespuesta.respuesta);
                    }
                },
                error: function(xhr,textStatus,error){
                    callAlertPopint(textStatus);
                }
            });
            return false;
        });
        catchEliminarSubcliente();
        $('#PuntosdeventaAddForm').submit(function(){
            //serialize form data
            var formData = $(this).serialize();
            //get form action
            var formUrl = $(this).attr('action');
            $.ajax({
                type: 'POST',
                url: formUrl,
                data: formData,
                success: function(data,textStatus,xhr){
                    var mirespuesta =jQuery.parseJSON(data);
                    callAlertPopint(mirespuesta.respuesta);
                    var puntoid = mirespuesta.puntoid;
                    var linkid = "editLinkPuntoVenta"+puntoid;
                    $("#tablepuntosdeventas").find('tbody')
                        .append($('<tr>')
                            .attr('class', "puntosdeventa")
                            .attr('id', "rowPuntodeVenta"+puntoid)
                            .attr('style', "display: table-row;")
                            .append($('<td>')
                                .text(mirespuesta.puntodeventa.Puntosdeventa.nombre)
                            )
                            .append($('<td>')
                                .text(mirespuesta.puntodeventa.Puntosdeventa.sistemafacturacion)
                            )
                            .append($('<td>')
                                .text(mirespuesta.puntodeventa.Domicilio.calle)
                            )
                            .append($('<td>')
                                .text(mirespuesta.puntodeventa.Puntosdeventa.direccion)
                            )
                            .append($('<td>')
                                .append($('<a>')
                                    .attr('onclick', "loadFormPuntoDeVenta("+puntoid+")")
                                    .attr('id', "editLinkPuntoVenta"+puntoid)
                                    .append($('<img>')
                                        .attr('src', serverLayoutURL+'/img/edit_view.png')
                                        .text('Image cell')
                                    )
                                )

                            )
                        );

                    location.hash ="#x";
                },
                error: function(xhr,textStatus,error){
                    callAlertPopint(textStatus);
                }
            });
            return false;
        });
        $('#PuntosdeventaNombre').change(function(){
            $('#PuntosdeventaNombre').val(pad($('#PuntosdeventaNombre').val(),5)) ;
        });
        $('#ProvedoreAddForm').submit(function(){
            //serialize form data
            var formData = $(this).serialize();
            //get form action
            var formUrl = $(this).attr('action');

            $.ajax({
                type: 'POST',
                url: formUrl,
                data: formData,
                success: function(data,textStatus,xhr){
                    var respuesta = JSON.parse(data);
                    callAlertPopint(respuesta.respuesta);
                    if(respuesta.provedor.Provedore!=null){
                        var provedoreID = respuesta.provedor.Provedore.id;
                        var rowData =
                            [
                                respuesta.provedor.Provedore.dni,
                                respuesta.provedor.Provedore.nombre,
                                respuesta.provedor.Provedore.cuit,
                            ];
                        var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="loadFormProvedore('+provedoreID+')" alt="">';
                        tdactions = tdactions + '<form action="'+serverLayoutURL+'/Provedores/delete/'+provedoreID+'" name="post_58b6e59f6102d291860796" id="post_58b6e59f6102d291860796" style="display:none;" method="post"><input type="hidden" name="_method" value="POST"></form>';
                        tdactions = tdactions + '<a href="#" class="deleteProvedore"><img src="'+serverLayoutURL+'/img/ic_delete_black_24dp.png" width="20" height="20"  alt="Eliminar"></a>';
                        //onclick="eliminarProvedore('+respuesta.compra_id+')"
                        rowData.push(tdactions);

                        var rowIndex = $('#provedoresDatatable').dataTable().fnAddData(rowData);
                        var row = $('#provedoresDatatable').dataTable().fnGetNodes(rowIndex);
                        $(row).attr( 'id', "rowProvedore"+respuesta.provedor.Provedore.id );

                        catchEliminarProvedore();
                    }
                },
                error: function(xhr,textStatus,error){
                    callAlertPopint(textStatus);

                }
            });
            return false;
        });
        $("#relatedEmpleados").DataTable();
        $("#relatedBienesdeusos").DataTable();
        //por alguna razon el width de los datatable se va a 0 y no a 100
        $(".dataTable").css('width','100%');
        catchEliminarProvedore();
        catchEliminarEmpleado();
        catchEliminarBienesdeuso();
        catchEliminarSubcliente();
	/*General*/
        var iHeaderHeight = $("#header").height() + 40;
        var sCliViewPageHeight = $(window).height();
        sCliViewPageHeight = sCliViewPageHeight - iHeaderHeight;
        sCliViewPageHeight = (sCliViewPageHeight < 250) ? 250 : sCliViewPageHeight;
        $("#divClientesIndex").css("height",sCliViewPageHeight + "px");
        $(".numeric").keyup(function () {
            this.value = this.value.replace(/[^0-9\.]/g,'');
        });
        var sClienteInfoHeight = sCliViewPageHeight - 22;
        //30 es el tamaño de los Tabs.
        $("#divCliente_Info").css("height",sClienteInfoHeight + "px");
        reloadDatePickers();
        $('.chosen-select').chosen({search_contains:true});

        "vamos a guardar este html en una variable por que tenemos que cargarlo en Modal"
        form_empleadoHTML = $("#form_empleado").html();
        $("#form_empleado").html("");

});

/*Labels*/

function activateLabelsFunctionality(){
	jQuery(document).ready(function($) {	
	   	$( "#lblDatosPeronales" ).click(function() {		
			if($('.datosPersonales').is(":visible")){
			 	 $('.datosPersonales').hide();
			 	 $("#imgDatosPersonales").attr('src',serverLayoutURL+"/img/menos2.png");
		 	}else{
	 			 $('.datosPersonales').show();
			 	 $("#imgDatosPersonales").attr('src',serverLayoutURL+"/img/mas2.png");
		 	}
		});
		$( "#lblDomicilio" ).click(function() {		
		 if($('.domicilios').is(":visible")){
		 	 $('.domicilios').hide();
		 	 $("#imgDomicilio").attr('src',serverLayoutURL+"/img/menos2.png");
		 	}else{
	 		 $('.domicilios').show();
			 	 $("#imgDomicilio").attr('src',serverLayoutURL+"/img/mas2.png");
		 	}
		});
		$( "#lblActividad" ).click(function() {		
		 if($('.actividades').is(":visible")){
		 	 $('.actividades').hide();
		 	 $("#imgActividad").attr('src',serverLayoutURL+"/img/menos2.png");
		 	}else{
	 		 $('.actividades').show();
			 	 $("#imgActividad").attr('src',serverLayoutURL+"/img/mas2.png");
		 	}
		});
		$( "#lblPersona" ).click(function() {		
		 if($('.personas').is(":visible")){
		 	 $('.personas').hide();
		 	 $("#imgPersona").attr('src',serverLayoutURL+"/img/menos2.png");
		 	}else{
	 		 $('.personas').show();
			 	 $("#imgPersona").attr('src',serverLayoutURL+"/img/mas2.png");
		 	}
		});
		// $( "#lblFacturacion" ).click(function() {
		//  if($('.facturacion').is(":visible")){
		//  	 $('.facturacion').hide();
		// 	 	 $("#imgFacturacion").attr('src',serverLayoutURL+"/img/menos2.png");
		//  	}else{
	 	// 	 $('.facturacion').show();
		// 	 	 $("#imgFacturacion").attr('src',serverLayoutURL+"/img/mas2.png");
		//  	}
		// });
		$( "#lblAFIP" ).click(function() {		
		 if($('.afip').is(":visible")){
		 	 $('.afip').hide();
			 	 $("#imgAFIP").attr('src',serverLayoutURL+"/img/menos2.png");
		 	}else{
	 		 $('.afip').show();
			 	 $("#imgAFIP").attr('src',serverLayoutURL+"/img/mas2.png");	 		 
		 	}
		});
		$( "#lblDGRM" ).click(function() {		
		 if($('.dgrm').is(":visible")){
		 	 $('.dgrm').hide();
			 	 $("#imgDGRM").attr('src',serverLayoutURL+"/img/menos2.png");
		 	}else{
	 		 $('.dgrm').show();
			 	 $("#imgDGRM").attr('src',serverLayoutURL+"/img/mas2.png");	 		 
		 	}
		});
		$( "#lblDGR" ).click(function() {		
		 if($('.dgr').is(":visible")){
		 	 $('.dgr').hide();
			 	 $("#imgDGR").attr('src',serverLayoutURL+"/img/menos2.png");
		 	}else{
	 		 $('.dgr').show();
			 	 $("#imgDGR").attr('src',serverLayoutURL+"/img/mas2.png");	 		 
		 	}
		});	
		$( "#lblBANCO" ).click(function() {		
		 if($('.bancos').is(":visible")){
		 	 $('.bancos').hide();
			 	 $("#imgBancos").attr('src',serverLayoutURL+"/img/menos2.png");
		 	}else{
	 		 $('.bancos').show();
			 	 $("#imgBancos").attr('src',serverLayoutURL+"/img/mas2.png");	 		 
		 	}
		});
		$( "#lblSINDICATO" ).click(function() {		
		 if($('.sindicatos').is(":visible")){
		 	 $('.sindicatos').hide();
			 	 $("#ImgSindicatos").attr('src',serverLayoutURL+"/img/menos2.png");
		 	}else{
	 		 $('.sindicatos').show();
			 	 $("#ImgSindicatos").attr('src',serverLayoutURL+"/img/mas2.png");	 		 
		 	}	 	
		});	
		$( "#lblPuntosdeventas").click(function() {		
		 if($('.puntosdeventa').is(":visible")){
	 	 	$("#imgPuntosdeventas").attr('src',serverLayoutURL+"/img/menos2.png");
				$('.puntosdeventa').hide();
	 	 }else{
				 $('.puntosdeventa').show();
		 	 $("#imgPuntosdeventas").attr('src',serverLayoutURL+"/img/mas2.png");	 		 
	 	 } 
		});
		$( "#lblSubclientes" ).click(function() {
			if($('.subcliente').is(":visible")){
				$('.subcliente').hide();
				$("#imgSubclientes").attr('src',serverLayoutURL+"/img/menos2.png");
			}else{
				$('.subcliente').show();
                if($('#tablaSubclienteVacia').val()*1){
                    loadSubclientes($("#ClienteId").val());
                }
				$("#imgSubclientes").attr('src',serverLayoutURL+"/img/mas2.png");
			}
		});
		$( "#lblProvedores" ).click(function() {
			if($('.provedor').is(":visible")){
				$('.provedor').hide();
				$("#imgProvedores").attr('src',serverLayoutURL+"/img/menos2.png");
			}else{
				$('.provedor').show();
                if($('#tablaProvedoresVacia').val()*1){
                    loadProvedores($("#ClienteId").val());
                }
				$("#imgProvedores").attr('src',serverLayoutURL+"/img/mas2.png");
			}
		});
		$( "#lblEmpleados" ).click(function() {
			if($('.empleado').is(":visible")){
				$('.empleado').hide();
				$("#imgEmpleados").attr('src',serverLayoutURL+"/img/menos2.png");
			}else{
				$('.empleado').show();
				$("#imgEmpleados").attr('src',serverLayoutURL+"/img/mas2.png");
			}
		});
        $( "#lblBienesdeusos" ).click(function() {
			if($('.biendeuso').is(":visible")){
				$('.biendeuso').hide();
				$("#imgBienesdeusos").attr('src',serverLayoutURL+"/img/menos2.png");
			}else{
				$('.biendeuso').show();
				$("#imgBienesdeusos").attr('src',serverLayoutURL+"/img/mas2.png");
			}
		});
	});
}
function showDatosCliente(){
	jQuery(document).ready(function($) {
		hiddeAll();
		deselectAll('cliente');
		$('.rowheaderdatosPersonales').show();
		$('.rowheaderdomicilios').show();
		$('.rowheaderactividades').show();
		$('.rowheaderpersonas').show();

		$('.rowheaderafip').hide();
		$('.rowheaderdgrm').hide();
		$('.rowheaderdgr').hide();
		$('.rowheaderbancos').hide();
		$('.rowheadersindicatos').hide();

		$('.rowheaderventas').hide();
		$('.rowheaderfacturacion').hide();
		$('.rowheaderpuntosdeventas').hide();
		$('.rowheadersubclientes').hide();
		$('.rowheaderempleados').hide();
        $('.rowheaderbienesdeusos').hide();
        $('.rowheaderprovedores').hide();
		$('.rowheadercompras').hide();
	});
}
function showDatosImpuesto(){
	jQuery(document).ready(function($) {
		hiddeAll();
		deselectAll('impuesto');
		$('.rowheaderdatosPersonales').hide();
		$('.rowheaderdomicilios').hide();
		$('.rowheaderactividades').hide();
		$('.rowheaderpersonas').hide();
		$('.rowheaderfacturacion').hide();

		$('.rowheaderafip').show();
		$('.rowheaderdgrm').show();
		$('.rowheaderdgr').show();
		$('.rowheaderbancos').show();
		$('.rowheadersindicatos').show();

		$('.rowheaderventas').hide();
		$('.rowheaderfacturacion').hide();
		$('.rowheaderpuntosdeventas').hide();
		$('.rowheadersubclientes').hide();
		$('.rowheaderempleados').hide();
        $('.rowheaderbienesdeusos').hide();
        $('.rowheaderprovedores').hide();
		$('.rowheadercompras').hide();
	});
}
function showDatosVenta(){
	jQuery(document).ready(function($) {
		hiddeAll();
		deselectAll('venta');
		$('.rowheaderdatosPersonales').hide();
		$('.rowheaderdomicilios').hide();
		$('.rowheaderactividades').hide();
		$('.rowheaderpersonas').hide();
		$('.rowheaderfacturacion').hide();

		$('.rowheaderrecibo').hide();
		$('.rowheaderingreso').hide();
		$('.rowheaderhonorario').hide();

		$('.rowheaderafip').hide();
		$('.rowheaderdgrm').hide();
		$('.rowheaderdgr').hide();
		$('.rowheaderbancos').hide();
		$('.rowheadersindicatos').hide();

		$('.rowheaderventas').show();
		$('.rowheaderfacturacion').show();
		$('.rowheaderpuntosdeventas').show();
		$('.rowheadersubclientes').show();
		$('.rowheaderprovedores').show();
		$('.rowheaderempleados').show();
		$('.rowheaderbienesdeusos').show();
		$('.rowheadercompras').hide();
	});
}
function deselectAll(sTab){
	jQuery(document).ready(function($) {
		$('#cliente_view_tab_cliente').attr('class', 'cliente_view_tab');
		$('#cliente_view_tab_impuesto').attr('class', 'cliente_view_tab');
		$('#cliente_view_tab_venta').attr('class', 'cliente_view_tab');

		if (sTab == "cliente")
			$('#cliente_view_tab_cliente').attr('class', 'cliente_view_tab_active');
		if (sTab == "impuesto")
			$('#cliente_view_tab_impuesto').attr('class', 'cliente_view_tab_active');
		if (sTab == "venta")
			$('#cliente_view_tab_venta').attr('class', 'cliente_view_tab_active');
	});
}
function hiddeAll(){
	jQuery(document).ready(function($) {
		$('.datosPersonales').hide();
		$("#imgDatosPersonales").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.domicilios').hide();
		$("#imgDomicilio").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.actividades').hide();
		$("#imgActividad").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.personas').hide();
		$("#imgPersona").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.facturacion').hide();
		$("#imgFacturacion").attr('src',serverLayoutURL+"/img/menos2.png");

		$('.afip').hide();
		$("#imgAFIP").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.dgrm').hide();
		$("#imgDGRM").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.dgr').hide();
		$("#imgDGR").attr('src',serverLayoutURL+"/img/menos2.png");


		$("#imgBancos").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.bancos').hide();

		$("#ImgSindicatos").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.sindicatos').hide();

		$("#imgVentas").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.venta').hide();
		$("#imgPuntosdeventas").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.puntosdeventa').hide();
		$("#imgSubclientes").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.subcliente').hide();

		$("#imgCompras").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.compra').hide();

		$("#imgProvedores").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.provedor').hide();

		$("#imgEmpleados").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.empleado').hide();

        $("#imgBienesdeusos").attr('src',serverLayoutURL+"/img/menos2.png");
		$('.biendeuso').hide();
	});
}

/*Contribuyente*/
function loadFormEditarPersona(){
	jQuery(document).ready(function($) {
		if($('#rowButtonsDetallesPersonales').is(":visible")){
			$("#tableDatosPersonalesEdit :input").prop("disabled", true);
			$('#rowButtonsDetallesPersonales').hide();
			$('#saveDatosPersonalesForm .ui-datepicker-trigger').datepicker().hide();
			$('.datosPersonales').hide();
			$("#imgDatosPersonales").attr('src',serverLayoutURL+"/img/menos2.png");
		}else{
			$("#tableDatosPersonalesEdit :input").prop("disabled", false);
			$('#rowButtonsDetallesPersonales').show();
			$( "#saveDatosPersonalesForm #ClienteTipopersona" ).trigger( "change" );
			$('#saveDatosPersonalesForm .ui-datepicker-trigger').datepicker().show();
			$('.datosPersonales').show();
			$("#imgDatosPersonales").attr('src',serverLayoutURL+"/img/mas2.png");
		}
	});
}
function loadFormDomicilio(domid,cliid){
	jQuery(document).ready(function($) {
		var data ="";
		$.ajax({
			type: "post",  // Request method: post, get
			url: serverLayoutURL+"/domicilios/editajax/"+domid+"/"+cliid,
			// URL to request
			data: data,  // post data
			success: function(response) {
				$("#form_modificar_domicilio").html(response);
				//$("#form_modificar_domicilio").width("600px");
				$('.chosen-select').chosen({search_contains:true});
				location.href='#modificar_domicilio';
				//Overflow: hidden;
				reloadDatePickers();

				//Catch the modify Domicilio
				$('#DomicilioEditForm').submit(function(){
					//serialize form data
					var formData = $(this).serialize();
					//get form action
					var formUrl = $(this).attr('action');
					$.ajax({
						type: 'POST',
						url: formUrl,
						data: formData,
						success: function(data,textStatus,xhr){
							//callAlertPopint(data);
							var rowid="rowdomicilio"+domid;
							$("#"+rowid).html( data);
							location.hash ="#x";
						},
						error: function(xhr,textStatus,error){
							callAlertPopint(textStatus);
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
function loadFormActividadcliente(actcliid,cliid){
	jQuery(document).ready(function($) {
		var data ="";
		$.ajax({
			type: "get",  // Request method: post, get
			url: serverLayoutURL+"/actividadclientes/edit/"+actcliid+"/"+cliid,
			// URL to request
			data: data,  // post data
			success: function(response) {
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModal').find('.modal-title').html('Modificar Actividad del Cliente');
                    $('#myModal').find('.modal-body').html(response);
                });
                $('#myModal').modal('show');
				reloadDatePickers();

				//Catch the modify Domicilio
				$('#ActividadclienteEditForm').submit(function(){
					//serialize form data
					var formData = $(this).serialize();
					//get form action
					var formUrl = $(this).attr('action');
					$.ajax({
						type: 'POST',
						url: formUrl,
						data: formData,
						success: function(data,textStatus,xhr){
							//callAlertPopint(data);
							var rowid="rowActividadcliente"+actcliid;
							$("#"+rowid).html( data);
							location.hash ="#x";
						},
						error: function(xhr,textStatus,error){
							callAlertPopint(textStatus);
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
function catchEliminarDomicilio(){
	jQuery(document).ready(function($) {

		$('.deleteDomicilio').removeAttr('onclick');

		$('.deleteDomicilio').click(function(e) {
			e.preventDefault();
			if (!confirm('Esta seguro que desea eliminar este Domicilio')) {
				return false;
			}
			var form = $(this).prev();
			var url = $(form).attr("action");
			var tr = $(this).closest('tr');
			url = url + '.json';
			$.post(url).success(function(res) {
				var respuesta = jQuery.parseJSON(res);
				callAlertPopint(respuesta.respuesta)
				tr.fadeOut(200);
			}).error(function() {
				alert("Error");
			})
			return false;
		});
	});
}

/*Organismos*/
function catchFormOrganismoxCliente(forname){
	jQuery(document).ready(function($) {
		$('#'+forname).submit(function(){
			//serialize form data
			var formData = $(this).serialize();
			//get form action
			var formUrl = $(this).attr('action');
			$.ajax({
				type: 'POST',
				url: formUrl,
				data: formData,
				success: function(data,textStatus,xhr){
					callAlertPopint(data);
				},
				error: function(xhr,textStatus,error){
					callAlertPopint(textStatus);
				}
			});
			return false;
		});
	});
}
function loadFormImpuestoPeriodos(impcliid){
	jQuery(document).ready(function($) {
		var data ="";
		$.ajax({
			type: "post",  // Request method: post, get
			url: serverLayoutURL+"/periodosactivos/index/"+impcliid,
			// URL to request
			data: data,  // post data
			success: function(response) {
				$("#form_modificar_periodosactivos").html(response);
				$("#form_modificar_periodosactivos").width("600px");
				location.href='#modificar_periodoactivo';
				reloadDatePickers();
				$('#formPeriodosActivosAdd').submit(function(){
					//serialize form data
					var formData = $(this).serialize();
					//get form action
					var formUrl = $(this).attr('action');
					$.ajax({
						type: 'POST',
						url: formUrl,
						data: formData,
						success: function(data,textStatus,xhr){
							var respuesta = jQuery.parseJSON(data);;
							callAlertPopint(respuesta.respuesta);
							var oldrow = $('#rowImpcli'+respuesta.periodosactivo.Periodosactivo.impcli_id)
							var newtr =$('<tr>')
								.attr('id', 'rowImpcli'+respuesta.periodosactivo.Periodosactivo.impcli_id)
								.append($('<td>')
									.text(respuesta.periodosactivo.Impcli.Impuesto.nombre)
								)
								.append($('<td>')
									.text(respuesta.periodosactivo.Periodosactivo.desde)
								)
								.append($('<td>')
									.append($('<a>')
										.attr('onclick', "loadFormImpuesto("+respuesta.periodosactivo.Periodosactivo.impcli_id+","+respuesta.periodosactivo.Periodosactivo.cliente_id+")")
										.append($('<img>')
											.attr('src', serverLayoutURL+'/img/edit_view.png')
											.text('Image cell')
										)
									)
									.append($('<a>')
										.attr('onclick', "loadFormImpuestoPeriodos("+respuesta.periodosactivo.Periodosactivo.impcli_id+")")
										.append($('<img>')
											.attr('src', serverLayoutURL+'/img/calendario.png')
											.text('Image cell')
										)
									)
									.append($('<a>')
										.attr('onclick', "deleteImpcli("+respuesta.periodosactivo.Periodosactivo.impcli_id+")")
										.append($('<img>')
											.attr('src', serverLayoutURL+'/img/delete.png')
											.text('Image cell')
										)
									)
								);
							location.href='#close';
						},
						error: function(xhr,textStatus,error){
							callAlertPopint(textStatus);
							location.href='#close';
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
function deleteImpcli(impcliid){
	jQuery(document).ready(function($) {
		var r = confirm("Esta seguro que desea eliminar este impuesto?.");
		if (r == true) {
			$.ajax({
				type: "post",  // Request method: post, get
				url: serverLayoutURL+"/impclis/delete/"+impcliid, // URL to request
				data: "",  // post data
				success: function(response) {
					var midata = jQuery.parseJSON(response);
					callAlertPopint(midata.respuesta);
					$('#rowImpcli'+impcliid).hide();
				},
				error:function (XMLHttpRequest, textStatus, errorThrown) {
					alert(textStatus);
					alert(XMLHttpRequest);
					alert(errorThrown);
				}
			});

		} else {
			txt = "No se a eliminado el impuesto";
		}
	});
}
function loadFormImpuestoProvincias(impcliid){
	jQuery(document).ready(function($) {
		var data ="";
		$.ajax({
			type: "get",  // Request method: post, get
			url: serverLayoutURL+"/impcliprovincias/add/"+impcliid,
			// URL to request
			data: data,  // post data
			success: function(response) {
				//$("#form_impcli_dgrm_provincia").html(response);
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModal').find('.modal-title').html('Agregar Provincia');
                    $('#myModal').find('.modal-body').html(response);
                    // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                });
                $('#myModal').modal('show');


				$('.chosen-select').chosen({search_contains:true});
				//aca vamos a sacar el json con las actividadesclientes que tienen actividades que tienen alicuotas cargadas
				var jsonactividadcliente = jQuery.parseJSON($('#ImpcliprovinciaJsonactividadcliente').val());
				setOnChangeProvincia(jsonactividadcliente);
				// location.href='#nuevo_IMPProv';
				$("#ImpcliprovinciaCoeficiente").on('change', function() {
					if(this.value>1){
						this.value=0;
					}else{
						this.value = parseFloat(this.value).toFixed(4);
					}
				});
				/*reloadDatePickers();  */
				$('#ImpcliprovinciaAddForm').submit(function(){
					//serialize form data
					var formData = $(this).serialize();
					//get form action
					var formUrl = $(this).attr('action');
					$.ajax({
						type: 'POST',
						url: formUrl,
						data: formData,
						success: function(data,textStatus,xhr){
							try {
                                $('#myModal').modal('hide');
								var respuesta = jQuery.parseJSON(data);
								callAlertPopint(respuesta.respuesta);
							}catch (Exception){
								callAlertPopint("Ocurrio un problema por favor intente mas tarde");
							}
							setTimeout(function(){
								loadFormImpuestoProvincias(impcliid);
							}, 3000);
						},
						error: function(xhr,textStatus,error){
							callAlertPopint(textStatus);
							location.href='#close';
						}
					});
					return false;
				});
                reloadDatePickers();
			},
			error:function (XMLHttpRequest, textStatus, errorThrown) {
				callAlertPopint(textStatus);
			}
		});
	});
}
function loadFormImpuestoLocalidades(impcliid){
	jQuery(document).ready(function($) {
		var data ="";
		$.ajax({
			type: "get",  // Request method: post, get
			url: serverLayoutURL+"/impcliprovincias/add/"+impcliid,
			// URL to request
			data: data,  // post data
			success: function(response) {
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModal').find('.modal-title').html('Agregar Localidad');
                    $('#myModal').find('.modal-body').html(response);
                    // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                });
                $('#myModal').modal('show');
				try {
					$('.chosen-select').chosen({search_contains:true});
					//aca vamos a sacar el json con las actividadesclientes que tienen actividades que tienen alicuotas cargadas
					var jsonactividadcliente = jQuery.parseJSON($('#ImpcliprovinciaJsonactividadcliente').val());
					setOnChangeLocalidad(jsonactividadcliente);
					/*reloadDatePickers();  */
					$('#ImpcliprovinciaAddForm').submit(function(){
						//serialize form data
						var formData = $(this).serialize();
						//get form action
						var formUrl = $(this).attr('action');
						$.ajax({
							type: 'POST',
							url: formUrl,
							data: formData,
							success: function(data,textStatus,xhr){
                                $('#myModal').modal('hide');
                                var respuesta = jQuery.parseJSON(data);
								callAlertPopint(respuesta.respuesta);
								setTimeout(function(){
									loadFormImpuestoProvincias(impcliid);
								}, 3000);
							},
							error: function(xhr,textStatus,error){
								callAlertPopint(textStatus);
								location.href='#close';
							}
						});
						return false;
					});
                    reloadDatePickers();
                }
				catch(err) {

				}

			},
			error:function (XMLHttpRequest, textStatus, errorThrown) {
				callAlertPopint(textStatus);
			}
		});
	});
}
function setOnChangeProvincia(jsonactividadcliente){
	jQuery(document).ready(function($) {
		$("#ImpcliprovinciaPartidoId").on('change', function() {
			/*
			 1_cuando cambia la provincia vamos a recorrer las actividades
			 2_vamos a sacar las opciones cargadas en el "nombre" de las alicuotas (que vendria a ser el tipo de contribuyente)
			 3_por cada actividad que coincida con la que estamos mostrando (recorriendo) vamos a cargarle las alicuotas (Actividade.Alicuota)
			 4_hay que seleccionar la primer alicuota y cargar los valores de esta para cargar en "encuadre alicuota"
			 */
			var provinciaseleccionada = $('#ImpcliprovinciaPartidoId').val();

			$(jsonactividadcliente).each(function(k,oActividadcliente){
				/*vamos a buscar la posicion "i" que corresponda a la actividad que estamos recorriendo*/
				pos=0;i=0;
				while(i<jsonactividadcliente.length){
					var actividadclienteseleccionada = $('#Encuadrealicuota'+i+'ActividadclienteId').val();
					if(oActividadcliente.Actividadcliente.id==actividadclienteseleccionada){
                        //esta es la posicion "pos" que buscamos
                        pos=i;
						i=jsonactividadcliente.length;
					}else{
						i++;
					}
				}

				$('#Encuadrealicuota'+pos+'AlicuotaId').empty();//vaciamos las opciones de las alicuotasprecargadas

				var hayalicuotaenprovincia = false;
				$(oActividadcliente.Actividade.Alicuota).each(function(l,oAlicuota){
					//si la provincia no tiene nungina alicuota entonces tenemos que borrar everything
					if(provinciaseleccionada==oAlicuota.partido_id){
						hayalicuotaenprovincia = true;
					}
				});
            //Alicuota General
				//aca vamos a cargar las alicuotas generales de las provincias(tal vez en el futuro le demos un lugar en la base de datos)
				switch (provinciaseleccionada){
					case '1':
						var mimamamemima="algo";
						$('#Encuadrealicuota'+pos+'AlicuotaId')
							.append(
								$("<option />")
									.val(0)
									.text("Monotributista")
									.attr('alicuota',3.0)
									.attr('concepto',"Monotributistas ante AFIP y Efectores Sociales")
									.attr('desde',"01-1990")
									.attr('pos',pos)
							).append(
							$("<option />")
								.val(1)
								.text("General")
								.attr('alicuota',3.6)
								.attr('concepto',"Alicuota General")
								.attr('desde',"01-1990")
								.attr('pos',pos)
						);
						hayalicuotaenprovincia = true;
						if($('#Encuadrealicuota'+pos+'Precargado').length==0) {
							$('#Encuadrealicuota' + pos + 'AlicuotaId').trigger('change');
						}
						break;
					default :
						var mimamamemima="algo";
						break;
				}
				//Si no hay alicuota general(escrita en el switch de arriba) ni excepcion(guardado en BD) muestro vacio
				if(!hayalicuotaenprovincia){
					$('#Encuadrealicuota'+pos+'AlicuotaId')
						.append('<option selected="selected" value="0">Por favor ingrese alicuota</option>')
					;
					if(!$('#Encuadrealicuota'+pos+'Precargado').val()==1){
						$('#Encuadrealicuota'+pos+'Alicuota').val(0.0);
						$('#Encuadrealicuota'+pos+'Concepto').val('sin detalle de alicuota');
						$('#Encuadrealicuota'+pos+'Desde').val('01-1990');
					}
					return true;
				}
				$("#Encuadrealicuota"+pos+"AlicuotaId").on('change', function() {
					var alicuotaid = $(this).val();
					var alicuota =  $(this).find('option:selected').attr('alicuota');
					var concepto =  $(this).find('option:selected').attr('concepto');
					var desde =  $(this).find('option:selected').attr('desde');
					var pos =  $(this).find('option:selected').attr('pos');
					$('#Encuadrealicuota'+pos+'Alicuota').val(alicuota);
					$('#Encuadrealicuota'+pos+'Concepto').val(concepto);
					$('#Encuadrealicuota'+pos+'Desde').val(desde);
				});
                //Alicuota Exepcional
				//recorremos las alicuotas de las actividades de la actividadcliente para buscar una que sea de la provincia seleccionada
				$(oActividadcliente.Actividade.Alicuota).each(function(j,oAlicuota){
					if(provinciaseleccionada!=oAlicuota.partido_id){
						return;
					}
					//aca la alicuota es de la actividad y de la provincia que se selecciono
					$('#Encuadrealicuota'+pos+'AlicuotaId')
						.append(
							//'<option selected="selected" value="'+oAlicuota.id+'">'+oAlicuota.nombre+'</option>'
							$("<option />")
								.val(oAlicuota.id)
								.text(oAlicuota.nombre)
								.attr('alicuota',oAlicuota.alicuota)
								.attr('concepto',oAlicuota.concepto)
								.attr('desde',oAlicuota.desde)
								.attr('pos',pos)
						);
					//si "precargado = 1" entonces no reemplazo los valores por los sugeridos
					if($('#Encuadrealicuota'+pos+'Precargado').val()==1){

					}else{
						$('#Encuadrealicuota'+pos+'Alicuota').val(oAlicuota.alicuota);
						$('#Encuadrealicuota'+pos+'Concepto').val(oAlicuota.concepto);
						$('#Encuadrealicuota'+pos+'Desde').val(oAlicuota.desde);
					}
				});
                var  precargado= $('#Encuadrealicuota'+pos+'Concepto').attr('precargado');
				if(precargado=="0"){
                    $('#Encuadrealicuota'+pos+'AlicuotaId').trigger('change');
				}else{
                    //aca vamos a seleccionar el "nombre" de la alicuota que tiene un atributo "alicuota" igual a la alicuota precargada
                    $('#Encuadrealicuota'+pos+'AlicuotaId option').each(function(){
                        if($(this).attr('alicuota')==$('#Encuadrealicuota'+pos+'Alicuota').val()){
                            $('#Encuadrealicuota'+pos+'AlicuotaId').val($(this).val());
                        }
                    });
                }

			});
		});
		$("#ImpcliprovinciaPartidoId").trigger('change');
	});
}
function setOnChangeLocalidad(jsonactividadcliente){
	jQuery(document).ready(function($) {
		$("#ImpcliprovinciaLocalidadeId").on('change', function() {
			/*
			 1_cuando cambia la provincia vamos a recorrer las actividades
			 2_vamos a sacar las opciones cargadas en el "nombre" de las alicuotas (que vendria a ser el tipo de contribuyente)
			 3_por cada actividad que coincida con la que estamos mostrando (recorriendo) vamos a cargarle las alicuotas (Actividade.Alicuota)
			 4_hay que seleccionar la primer alicuota y cargar los valores de esta para cargar en "encuadre alicuota"
			 */
			var localidadseleccionada = $('#ImpcliprovinciaLocalidadeId').val();

			$(jsonactividadcliente).each(function(k,oActividadcliente){
				/*vamos a buscar la posicion "i" que corresponda a la actividad que estamos recorriendo*/
				pos=0;i=0;

				while(i<jsonactividadcliente.length){
					var actividadclienteseleccionada = $('#Encuadrealicuota'+i+'ActividadclienteId').val();
					if(oActividadcliente.Actividadcliente.id==actividadclienteseleccionada){
						pos=i;
						i=jsonactividadcliente.length;
					}else{
						i++;
					}
				}
				//esta es la posicion "pos" que buscamos

				$('#Encuadrealicuota'+pos+'AlicuotaId').empty();//vaciamos las opciones de las alicuotasprecargadas

				var hayalicuotaenlocalidad = false;
				$(oActividadcliente.Actividade.Alicuota).each(function(l,oAlicuota){
					//si la provincia no tiene nungina alicuota entonces tenemos que borrar everything
					if(localidadseleccionada==oAlicuota.localidade_id){
						hayalicuotaenlocalidad = true;
					}
				});
				switch (localidadseleccionada){
					case '48':
						var mimamamemima="algo";
						$('#Encuadrealicuota'+pos+'AlicuotaId')
							.append(
								$("<option />")
									.val(0)
									.text("A")
									.attr('alicuota',0.43)
									.attr('concepto',"A")
									.attr('desde',"01-1990")
									.attr('pos',pos)
							).append(
							$("<option />")
								.val(1)
								.text("B")
								.attr('alicuota',0.60)
								.attr('concepto',"Categoria B")
								.attr('desde',"01-1990")
								.attr('pos',pos)
						).append(
							$("<option />")
								.val(2)
								.text("C")
								.attr('alicuota',1.20)
								.attr('concepto',"Categoria C")
								.attr('desde',"01-1990")
								.attr('pos',pos)
						).append(
							$("<option />")
								.val(3)
								.text("D")
								.attr('alicuota',2.00)
								.attr('concepto',"Categoria D")
								.attr('desde',"01-1990")
								.attr('pos',pos)
						).append(
							$("<option />")
								.val(4)
								.text("E")
								.attr('alicuota',2.40)
								.attr('concepto',"Categoria E")
								.attr('desde',"01-1990")
								.attr('pos',pos)
						).append(
							$("<option />")
								.val(5)
								.text("F")
								.attr('alicuota',3.60)
								.attr('concepto',"Categoria F")
								.attr('desde',"01-1990")
								.attr('pos',pos)
						);
						hayalicuotaenlocalidad = true;
						if($('#Encuadrealicuota'+pos+'Precargado').length==0) {
							$('#Encuadrealicuota' + pos + 'AlicuotaId').trigger('change');
						}
						break;
					default :
						var mimamamemima="algo";
						break;
				}
				if(!hayalicuotaenlocalidad){
					$('#Encuadrealicuota'+pos+'AlicuotaId')
						.append('<option selected="selected" value="0">Por favor ingrese alicuota</option>')
					;
					if(!$('#Encuadrealicuota'+pos+'Precargado').val()==1){
						$('#Encuadrealicuota'+pos+'Alicuota').val(0.0);
						$('#Encuadrealicuota'+pos+'Concepto').val('no concept');
						$('#Encuadrealicuota'+pos+'Desde').val('01-1990');
					}

					return true;
				}
				$("#Encuadrealicuota"+pos+"AlicuotaId").on('change', function() {

					var alicuotaid = $(this).val();
					var alicuota =  $(this).find('option:selected').attr('alicuota');
					var concepto =  $(this).find('option:selected').attr('concepto');
					var desde =  $(this).find('option:selected').attr('desde');
					var pos =  $(this).find('option:selected').attr('pos');
					$('#Encuadrealicuota'+pos+'Alicuota').val(alicuota);
					$('#Encuadrealicuota'+pos+'Concepto').val(concepto);
					$('#Encuadrealicuota'+pos+'Desde').val(desde);

				});
				//recorremos las alicuotas de las actividades de la actividadcliente para buscar una que sea de la localidad seleccionada
				$(oActividadcliente.Actividade.Alicuota).each(function(j,oAlicuota){
					if(localidadseleccionada!=oAlicuota.localidade_id){
						return;
					}
					$('#Encuadrealicuota'+pos+'AlicuotaId')
						.append(
							$("<option />")
								.val(oAlicuota.id)
								.text(oAlicuota.nombre)
								.attr('alicuota',oAlicuota.alicuota)
								.attr('concepto',oAlicuota.concepto)
								.attr('desde',oAlicuota.desde)
								.attr('pos',pos)
						);
					if($('#Encuadrealicuota'+pos+'Precargado').val()==1){

					}else{
						$('#Encuadrealicuota'+pos+'Alicuota').val(oAlicuota.alicuota);
						$('#Encuadrealicuota'+pos+'Concepto').val(oAlicuota.concepto);
						$('#Encuadrealicuota'+pos+'Desde').val(oAlicuota.desde);
					}
				});
                var  precargado= $('#Encuadrealicuota'+pos+'Concepto').attr('precargado');
                if(precargado=="0"){
                    $('#Encuadrealicuota'+pos+'AlicuotaId').trigger('change');
                }
			});
		});
		$("#ImpcliprovinciaLocalidadeId").trigger('change');
	});
}
function loadFormImpuestoCuentasganancias(cliid){
    jQuery(document).ready(function($) {
        var data ="";
        $.ajax({
            type: "get",  // Request method: post, get
            url: serverLayoutURL+"/cuentasganancias/index/"+cliid,
            // URL to request
            data: data,  // post data
            success: function(response) {
                $("#form_impcli_dgrm_provincia").html(response);
                $('.chosen-select').chosen({search_contains:true});
                location.href='#nuevo_IMPProv';
                // $(".inputcategoria").change(function () {
                //     var categoriaelegida = $(this).val();
                //     var posicion = $(this).attr('posicion');
                //     var miCategoria = $('#Cuentasganancia'+posicion+'CuentaId');
                //     miCategoria.empty();
                //     var options;
                //     switch (categoriaelegida){
                //
                //         /*$categorias = [
                //          'primeracateg'=>'primera',
                //          'segundacateg'=>'segunda',
                //          'terceracateg'=>'tercera empresas',
                //          'terceracateg45'=>'tercera otros',
                //          'cuartacateg'=>'cuarta'
                //          ];*/
                //         case 'primeracateg':
                //             options = jQuery.parseJSON($("#cuentascategoriaprimera").val());
                //             break;
                //         case 'segundacateg':
                //             options = jQuery.parseJSON($("#cuentascategoriasegunda").val());
                //             break;
                //         case 'terceracateg':
                //             options = jQuery.parseJSON($("#cuentascategoriatercera").val());
                //             break;
                //         case 'terceracateg45':
                //             options = jQuery.parseJSON($("#cuentascategoriaterceraotros").val());
                //             break;
                //         case 'cuartacateg':
                //             options = jQuery.parseJSON($("#cuentascategoriacuarta").val());
                //             break;
                //     };
                //     $.each(options, function(key, value) {
                //         miCategoria
                //             .append($("<option></option>")
                //                 .attr("value",key)
                //                 .text(value));
                //     });
                //     miCategoria.val($(miCategoria).attr('defaultoption'))
                // });
                // $(".inputcategoria").trigger("change");

                $('#CuentasgananciaIndexForm').submit(function(){
                    //serialize form data
                    var formData = $(this).serialize();
                    //get form action
                    var formUrl = $(this).attr('action');
                    $.ajax({
                        type: 'POST',
                        url: formUrl,
                        data: formData,
                        success: function(data,textStatus,xhr){
                            try {
                                var respuesta = jQuery.parseJSON(data);
                                callAlertPopint(respuesta.respuesta);
                            }catch (Exception){
                                callAlertPopint("Ocurrio un problema por favor intente mas tarde");
                            }
                        },
                        error: function(xhr,textStatus,error){
                            callAlertPopint(textStatus);
                            location.href='#close';
                        }
                    });
                    return false;
                });
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                callAlertPopint(textStatus);
            }
        });
    });
}
function loadFormImpuestoDeducciones(impcliid){
    jQuery(document).ready(function($) {
        var data ="";
        $.ajax({
            type: "get",  // Request method: post, get
            url: serverLayoutURL+"/deducciones/add/"+impcliid,
            // URL to request
            data: data,  // post data
            success: function(response) {
                $("#form_impcli_dgrm_provincia").html(response);
                $('.chosen-select').chosen({search_contains:true});
                location.href='#nuevo_IMPProv';
                $("#DeduccioneTipo").on('change', function () {
                    if($(this).val()=='general'){
                        $(".personal").parent().hide();
                        $(".general").parent().show();
                    }
                    if($(this).val()=='personal'){
                       $("#DeduccioneClase").trigger('change');
                    }
                });
                 $("#DeduccioneClase").on('change', function () {                    
                        $(".general").parent().hide();
                        $(".personal").parent().hide();
                        switch ($('#DeduccioneClase').val()) {
                            case 'Deduccion especial':
                                $(".deduccionespecial").parent().show();
                                break;
                            case 'Conyuge':
                                $(".conyuge").parent().show();
                                break;
                            case 'Hijos':
                                 $(".hijo").parent().show();
                                break;
                            case 'Otras Cargas':
                                 $(".otrascargas").parent().show();
                                break;                       
                        }
                 });
                $('#DeduccioneClase').filterGroups({groupSelector: '#DeduccioneTipo', });
                $('#DeduccioneAddForm').submit(function(){
                        //serialize form data
                        var formData = $(this).serialize();
                        //get form action
                        var formUrl = $(this).attr('action');
                        $.ajax({
                                type: 'POST',
                                url: formUrl,
                                data: formData,
                                success: function(data,textStatus,xhr){
                                    $('#myModal').modal('hide');   
                                    callAlertPopint("Deduccion Agregada");                                       
                                },
                                error: function(xhr,textStatus,error){
                                        callAlertPopint("Deposito NO Modificado. Intente de nuevo mas Tarde");
                                }
                        });
                        return false;
                });
                reloadDatePickers();
                 $("#DeduccioneTipo").parent().hide();
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                callAlertPopint(textStatus);
            }
        });
    });
}

function loadFormImpuestoQuebrantos(impcliid){
    jQuery(document).ready(function($) {
        var data ="";
        $.ajax({
            type: "get",  // Request method: post, get
            url: serverLayoutURL+"/quebrantos/add/"+impcliid,
            // URL to request
            data: data,  // post data
            success: function(response) {
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModal').find('.modal-title').html('Modificar Localidad/Provincia');
                    $('#myModal').find('.modal-body').html(response);
                    // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                });
                $('#myModal').modal('show');
                
                $('.chosen-select').chosen({search_contains:true});
                $("#Quebranto0Periodogeneral").on('change', function() {
                    $(".periodoaplicacion").each(function(){
                        $(this).val($("#Quebranto0Periodogeneral").val());
                    });    
                    var strDatePeriodoOrig = '01-'+$("#Quebranto0Periodogeneral").val();
                    var parts = strDatePeriodoOrig.split("-");
                    var dt = new Date(parts[2], parts[1] , parts[0]);                            
                    dt.setMonth(dt.getMonth() - 1);
                    dt.setMonth(dt.getMonth() - 12);
                    var datePeriodoOrig0 = $.datepicker.formatDate('mm-yy', dt);
                    dt.setMonth(dt.getMonth() - 12);
                    var datePeriodoOrig1 = $.datepicker.formatDate('mm-yy', dt);
                    dt.setMonth(dt.getMonth() - 12);
                    var datePeriodoOrig2 = $.datepicker.formatDate('mm-yy', dt);
                    dt.setMonth(dt.getMonth() - 12);
                    var datePeriodoOrig3 = $.datepicker.formatDate('mm-yy', dt);
                    dt.setMonth(dt.getMonth() - 12);
                    var datePeriodoOrig4 = $.datepicker.formatDate('mm-yy', dt);
                    $("#Quebranto0Periodogenerado").val(datePeriodoOrig0);
                    $("#Quebranto1Periodogenerado").val(datePeriodoOrig1);
                    $("#Quebranto2Periodogenerado").val(datePeriodoOrig2);
                    $("#Quebranto3Periodogenerado").val(datePeriodoOrig3);
                    $("#Quebranto4Periodogenerado").val(datePeriodoOrig4);
                });
                
                $('#QuebrantoAddForm').submit(function(){
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
                                    $('#myModal').modal('hide');
                                    callAlertPopint(respuesta.respuesta);                                  
                                },
                                error: function(xhr,textStatus,error){
                                        callAlertPopint("Deposito NO Modificado. Intente de nuevo mas Tarde");
                                }
                        });
                        return false;
                });
                reloadDatePickers();
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                callAlertPopint(textStatus);
            }
        });
    });
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
				var rowAModificar = $('#rowImpcli'+impcliid).html();
				$('#rowImpcli'+impcliid).html(response);
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
							$('#ImpcliEditForm'+impcliid).parent().replaceWith(data);
						},
						error: function(xhr,textStatus,error){
							callAlertPopint("Deposito NO Modificado. Intente de nuevo mas Tarde");
						}
					});
					return false;
				});
				reloadDatePickers();
			},

			error:function (XMLHttpRequest, textStatus, errorThrown) {
				callAlertPopint(textStatus);
				callAlertPopint(XMLHttpRequest);
				callAlertPopint(errorThrown);
			}
		});
	});
}

function editImpcliProvincia(impcliprovinciaid,impcliid){
    jQuery(document).ready(function($) {
        $.ajax({
            type: "GET",  // Request method: post, get
            url: serverLayoutURL+"/impcliprovincias/edit/"+impcliprovinciaid+"/"+impcliid, // URL to request
            data: "",  // post data
            success: function(response) {
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModal').find('.modal-title').html('Modificar Localidad/Provincia');
                    $('#myModal').find('.modal-body').html(response);
                    // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                });
                $('#myModal').modal('show');
                $('#ImpcliprovinciaEditForm').submit(function(){
                    //serialize form data
                    var formData = $(this).serialize();
                    //get form action
                    var formUrl = $(this).attr('action');
                    $.ajax({
                        type: 'POST',
                        url: formUrl,
                        data: formData,
                        success: function(data,textStatus,xhr){
                            location.href='#close';
                            var respuesta = jQuery.parseJSON(data);
                            $('#myModal').modal('hide');
                            callAlertPopint(respuesta.respuesta);
                        },
                        error: function(xhr,textStatus,error){
                            callAlertPopint(textStatus);
                            location.href='#close';
                        }
                    });
                    return false;
                });
                reloadDatePickers();
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
                alert(XMLHttpRequest);
                alert(errorThrown);
            }
        });
    });
}
function deleteDeduccion(dedid){
    jQuery(document).ready(function($) {
        var r = confirm("Esta seguro que desea eliminar esta deduccion?.");
        if (r == true) {
            $.ajax({
                type: "post",  // Request method: post, get
                url: serverLayoutURL+"/deducciones/delete/"+dedid, // URL to request
                data: "",  // post data
                success: function(response) {
                    var midata = jQuery.parseJSON(response);
                    $('#myModal').modal('hide');
                    callAlertPopint(midata.respuesta);
                    $('#rowDeduccion'+dedid).hide();
                },
                error:function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(textStatus);
                    alert(XMLHttpRequest);
                    alert(errorThrown);
                }
            });

        } else {
            txt = "No se a eliminado la deduccion";
        }
    });
}
function deleteQuebranto(queid){
    jQuery(document).ready(function($) {
        var r = confirm("Esta seguro que desea eliminar este quebranto?.");
        if (r == true) {
            $.ajax({
                type: "post",  // Request method: post, get
                url: serverLayoutURL+"/quebrantos/delete/"+queid, // URL to request
                data: "",  // post data
                success: function(response) {
                    var midata = jQuery.parseJSON(response);
                    $('#myModal').modal('hide');
                    callAlertPopint(midata.respuesta);
                    $('#rowQuebranto'+queid).hide();
                },
                error:function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(textStatus);
                    alert(XMLHttpRequest);
                    alert(errorThrown);
                }
            });

        } else {
            txt = "No se a eliminado el quebranto";
        }
    });
}
function editImpcliProvincia(impcliprovinciaid,impcliid){
    jQuery(document).ready(function($) {
        $.ajax({
            type: "GET",  // Request method: post, get
            url: serverLayoutURL+"/impcliprovincias/edit/"+impcliprovinciaid+"/"+impcliid, // URL to request
            data: "",  // post data
            success: function(response) {
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModal').find('.modal-title').html('Modificar Localidad/Provincia');
                    $('#myModal').find('.modal-body').html(response);
                    // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                });
                $('#myModal').modal('show');
                $('#ImpcliprovinciaEditForm').submit(function(){
                    //serialize form data
                    var formData = $(this).serialize();
                    //get form action
                    var formUrl = $(this).attr('action');
                    $.ajax({
                        type: 'POST',
                        url: formUrl,
                        data: formData,
                        success: function(data,textStatus,xhr){
                            location.href='#close';
                            var respuesta = jQuery.parseJSON(data);
                            $('#myModal').modal('hide');
                            callAlertPopint(respuesta.respuesta);
                        },
                        error: function(xhr,textStatus,error){
                            callAlertPopint(textStatus);
                            location.href='#close';
                        }
                    });
                    return false;
                });
                reloadDatePickers();
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
                alert(XMLHttpRequest);
                alert(errorThrown);
            }
        });
    });
}
function loadCbus(impcliid){
    jQuery(document).ready(function($) {
        var data ="";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/cbus/index/"+impcliid,
            // URL to request
            data: data,  // post data
            success: function(response) {
                $("#form_cbus").html(response);
                $("#form_cbus").width("600px");
                location.href='#load_cbuform';
                $('#CbuAddForm').submit(function(){
                    //serialize form data
                    var formData = $(this).serialize();
                    //get form action
                    var formUrl = $(this).attr('action');
                    $.ajax({
                        type: 'POST',
                        url: formUrl,
                        data: formData,
                        success: function(data,textStatus,xhr){
                            location.href='#close';
                            var respuesta = jQuery.parseJSON(data);;
                            callAlertPopint(respuesta.respuesta);
                            setTimeout(function(){
                                loadCbus(impcliid);
                            }, 3000);
                        },
                        error: function(xhr,textStatus,error){
                            callAlertPopint(textStatus);
                            location.href='#close';
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
function loadModificarCbu(impcliid,cbuid){
    jQuery(document).ready(function($) {
        var data ="";
        $.ajax({
            type: "get",  // Request method: post, get
            url: serverLayoutURL+"/cbus/edit/"+cbuid+"/"+impcliid,
            // URL to request
            data: data,  // post data
            success: function(response) {
                $("#form_cbus").html(response);
                $("#form_cbus").width("600px");
                location.href='#load_cbuform';
                $('#CbuEditForm').submit(function(){
                    //serialize form data
                    var formData = $(this).serialize();
                    //get form action
                    var formUrl = $(this).attr('action');
                    $.ajax({
                        type: 'POST',
                        url: formUrl,
                        data: formData,
                        success: function(data,textStatus,xhr){
                            location.href='#close';
                            var respuesta = jQuery.parseJSON(data);;
                            callAlertPopint(respuesta.respuesta);
                            setTimeout(function(){
                                loadCbus(impcliid);
                            }, 3000);
                        },
                        error: function(xhr,textStatus,error){
                            callAlertPopint(textStatus);
                            location.href='#close';
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

function catchImpCliAFIP(){
	catchFormAndSaveResult('FormImpcliAFIP','tablaImpAfip','ImpcliAltaafip');
}
function catchImpCliDGR(){
	catchFormAndSaveResult('FormImpcliDGR','tablaImpDGR','ImpcliAltadgr');
}
function catchImpCliDGRM(){
	catchFormAndSaveResult('FormImpcliDGRM','tablaImpDGRM','ImpcliAltadgrm');
}
function catchImpCliSindicatos(){
	catchFormAndSaveResult('FormImpcliSindicato','tablaImpSINDICATO','ImpcliAltasindicato');
}
function catchImpCliBancos(){
	catchFormAndSaveResult('FormImpcliBanco','tablaImpBanco','ImpcliAltabanco');
}
function catchFormAndSaveResult(impForm,impTable,impAlta){
	jQuery(document).ready(function($) {
		$('#'+impForm).submit(function(){
			$("#"+impForm+" #ImpcliAlta").val($("#"+impForm+" #"+impAlta).val());
			if($("#"+impForm+" #ImpcliAlta").val().length == 0) {
				callAlertPopint("Debe seleccionar un periodo de alta");
				return false;
			}
			location.hash ="#x";
			//serialize form data
			var formData = $(this).serialize();
			//get form action
			var formUrl = $(this).attr('action');
			$.ajax({
				type: 'POST',
				url: formUrl,
				data: formData,
				success: function(data,textStatus,xhr){
					var mirespuesta =jQuery.parseJSON(data);
					if(mirespuesta.hasOwnProperty('respuesta')){
						location.hash ="#x";
						callAlertPopint(mirespuesta.respuesta);
					}else if(mirespuesta.accion == 'editar'){
                                            location.hash ="#x";
                                            callAlertPopint("Impuesto relacionado con exito.Periodo activo creado.");
                                            $("#rowImpcli"+mirespuesta.impid).replaceWith(mirespuesta.impclirow);
                                            $("#"+impForm+" #ImpcliImpuestoId").find('option:selected').remove();
                                            $("#"+impForm+" #ImpcliImpuestoId").trigger("chosen:updated");
					}else{
                                            $("#"+impTable).append(mirespuesta.impclirow);
                                            $("#"+impForm+" #ImpcliImpuestoId").find('option:selected').remove();
                                            $("#"+impForm+" #ImpcliImpuestoId").trigger("chosen:updated");
                                            location.hash ="#x";
					}
				},
				error: function(xhr,textStatus,error){
					callAlertPopint(textStatus);
				}
				//aqui no deberiamos recargar la pagina sino simplemente agregar esta info donde debe ser.
			});
			return false;
		});
	});
}

/*Otros*/
function loadFormPuntoDeVenta(puntoid){
	jQuery(document).ready(function($) {

		$.ajax({
			type: 'GET',
			url: serverLayoutURL+"/puntosdeventas/edit/"+puntoid,
			data: "",
			success: function(data,textStatus,xhr){
				oldrow = $("#rowPuntodeVenta"+puntoid).html();
				$("#rowPuntodeVenta"+puntoid).html(data);
				$('#PuntodeVentaFormEdit'+puntoid).submit(function(){
					//serialize form data
					var formData = $(this).serialize();
					//get form action
					var formUrl = $(this).attr('action');
					$.ajax({
						type: 'POST',
						url: formUrl,
						data: formData,
						success: function(data,textStatus,xhr){
							$("#rowPuntodeVenta"+puntoid).html(data);
							location.hash ="#x";
						},
						error: function(xhr,textStatus,error){
							callAlertPopint(textStatus);
						}
					});
					return false;
				});
			},
			error: function(xhr,textStatus,error){
				callAlertPopint(textStatus);
			}
			//aqui no deberiamos recargar la pagina sino simplemente agregar esta info donde debe ser.
		});
	});
}
function loadFormPersonaRelacionada(perid,cliid,rowid){
	jQuery(document).ready(function($) {
		var data ="";
		$.ajax({
			type: "post",  // Request method: post, get
			url: serverLayoutURL+"/personasrelacionadas/editajax/"+perid+"/"+cliid,

			// URL to request
			data: data,  // post data
			success: function(response) {
				$("#form_modificar_persona").html(response);
				reloadDatePickers();
				location.href='#modificar_persona';
				$('#PersonasrelacionadaEditForm').submit(function(){
					//serialize form data
					var formData = $(this).serialize();
					//get form action
					var formUrl = $(this).attr('action');
					$.ajax({
						type: 'POST',
						url: formUrl,
						data: formData,
						success: function(data,textStatus,xhr){
							$('#'+rowid).replaceWith(data);
							location.hash ="#x";
						},
						error: function(xhr,textStatus,error){
							callAlertPopint(textStatus);
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
function loadFormSubcliente(subcliid){
    jQuery(document).ready(function($) {
        $.ajax({
            type: 'GET',
            url: serverLayoutURL+"/subclientes/edit/"+subcliid,
            data: "",
            success: function(data,textStatus,xhr){
                oldrow = $("#rowSubcliente"+subcliid).html();
                $("#rowSubcliente"+subcliid).html(data);
                $('#SubclienteFormEdit'+subcliid).submit(function(){
                    //serialize form data
                    var formData = $(this).serialize();
                    //get form action
                    var formUrl = $(this).attr('action');
                    $.ajax({
                        type: 'POST',
                        url: formUrl,
                        data: formData,
                        success: function(data,textStatus,xhr){
                            $("#rowSubcliente"+subcliid).html(data);
                            catchEliminarSubcliente();
                            location.hash ="#x";
                        },
                        error: function(xhr,textStatus,error){
                            callAlertPopint(textStatus);
                        }
                    });
                    return false;
                });
            },
            error: function(xhr,textStatus,error){
                callAlertPopint(textStatus);
            }
            //aqui no deberiamos recargar la pagina sino simplemente agregar esta info donde debe ser.
        });
    });
}
function loadSubclientes(cliid){
    jQuery(document).ready(function($) {
        
        $.ajax({
            type: 'GET',
            url: serverLayoutURL+"/subclientes/index/"+cliid,
            data: "",
            success: function(data,textStatus,xhr){
                var respuesta = JSON.parse(data);
                if(respuesta.subclientes != null){
                    $("#subclientesDatatable").DataTable();
                    //por alguna razon el width de los datatable se va a 0 y no a 100
                    $(".dataTable").css('width','100%');
                    respuesta.subclientes.forEach(function(subcliente){
                        var rowData =
                            [
                                subcliente.Subcliente.cuit,
                                subcliente.Subcliente.dni,
                                subcliente.Subcliente.nombre,
                            ];
                        var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="loadFormSubcliente('+subcliente.Subcliente.id+')" alt="">';
                        tdactions = tdactions + '<form action="'+serverLayoutURL+'/Subclientes/delete/'+subcliente.Subcliente.id+'" name="post_58b6e59f6102d291860796" id="post_58b6e59f6102d291860796" style="display:none;" method="post"><input type="hidden" name="_method" value="POST"></form>';
                        tdactions = tdactions + '<a href="#" class="deleteSubcliente"><img src="'+serverLayoutURL+'/img/ic_delete_black_24dp.png" width="20" height="20"  alt="Eliminar"></a>';
                        //onclick="eliminarSubcliente('+respuesta.compra_id+')"
                        rowData.push(tdactions);

                        var rowIndex = $('#subclientesDatatable').dataTable().fnAddData(rowData);
                        var row = $('#subclientesDatatable').dataTable().fnGetNodes(rowIndex);
                        $(row).attr( 'id', "rowSubcliente"+subcliente.Subcliente.id );
                    });
                    $('#tablaSubclienteVacia').val(0);
                    catchEliminarSubcliente();
                }

            },
            error: function(xhr,textStatus,error){
                callAlertPopint(textStatus);
            }
            //aqui no deberiamos recargar la pagina sino simplemente agregar esta info donde debe ser.
        });
    });
}
function loadProvedores(cliid){
    jQuery(document).ready(function($) {

        $.ajax({
            type: 'GET',
            url: serverLayoutURL+"/provedores/index/"+cliid,
            data: "",
            success: function(data,textStatus,xhr){
                var respuesta = JSON.parse(data);
                if(respuesta.provedores != null){
                    $("#provedoresDatatable").DataTable();
                    //por alguna razon el width de los datatable se va a 0 y no a 100
                    $(".dataTable").css('width','100%');
                    respuesta.provedores.forEach(function(provedore){
                        var rowData =
                            [
                                provedore.Provedore.dni,
                                provedore.Provedore.nombre,
                                provedore.Provedore.cuit,
                            ];
                        var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="loadFormProvedore('+provedore.Provedore.id+')" alt="">';
                        tdactions = tdactions + '<form action="'+serverLayoutURL+'/Provedores/delete/'+provedore.Provedore.id+'" name="post_58b6e59f6102d291860796" id="post_58b6e59f6102d291860796" style="display:none;" method="post"><input type="hidden" name="_method" value="POST"></form>';
                        tdactions = tdactions + '<a href="#" class="deleteProvedore"><img src="'+serverLayoutURL+'/img/ic_delete_black_24dp.png" width="20" height="20"  alt="Eliminar"></a>';
                        //onclick="eliminarProvedore('+respuesta.compra_id+')"
                        rowData.push(tdactions);

                        var rowIndex = $('#provedoresDatatable').dataTable().fnAddData(rowData);
                        var row = $('#provedoresDatatable').dataTable().fnGetNodes(rowIndex);
                        $(row).attr( 'id', "rowProvedore"+provedore.Provedore.id );
                    });
                    $('#tablaProvedoresVacia').val(0);
                    catchEliminarProvedore();
                }

            },
            error: function(xhr,textStatus,error){
                callAlertPopint(textStatus);
            }
        });
    });
}

function loadFormProvedore(provedorid){
    jQuery(document).ready(function($) {
        $.ajax({
            type: 'GET',
            url: serverLayoutURL+"/provedores/edit/"+provedorid,
            data: "",
            success: function(data,textStatus,xhr){
                oldrow = $("#rowProvedore"+provedorid).html();
                $("#rowProvedore"+provedorid).html(data);
                $('#ProvedoreFormEdit'+provedorid).submit(function(){
                    //serialize form data
                    var formData = $(this).serialize();
                    //get form action
                    var formUrl = $(this).attr('action');
                    $.ajax({
                        type: 'POST',
                        url: formUrl,
                        data: formData,
                        success: function(data,textStatus,xhr){
                            $("#rowProvedore"+provedorid).html(data);
                            location.hash ="#x";
                            catchEliminarProvedore();
                        },
                        error: function(xhr,textStatus,error){
                            callAlertPopint(textStatus);
                        }
                    });
                    return false;
                });
            },
            error: function(xhr,textStatus,error){
                callAlertPopint(textStatus);
            }
            //aqui no deberiamos recargar la pagina sino simplemente agregar esta info donde debe ser.
        });
    });
}
function loadFormAddEmpleado(){
    jQuery(document).ready(function($) {
        $('#myModal').on('show.bs.modal', function () {
            $('#myModal').find('.modal-title').html('Agregar Empleado');
            $('#myModal').find('.modal-body').html(form_empleadoHTML);
            $('#EmpleadoAddForm').submit(function(){
                //serialize form data
                var formData = $(this).serialize();
                //get form action
                var formUrl = $(this).attr('action');
                $.ajax({
                    type: 'POST',
                    url: formUrl,
                    data: formData,
                    success: function(data,textStatus,xhr){
                        $('#myModal').modal('hide');
                        var respuesta = JSON.parse(data);
                        callAlertPopint(respuesta.respuesta);
                        if(respuesta.empleado.Empleado!=null){
                            var empleadoID = respuesta.empleado.Empleado.id;
                            var rowData =
                                [
                                    respuesta.empleado.Empleado.legajo,
                                    respuesta.empleado.Empleado.nombre,
                                    respuesta.empleado.Empleado.dni,
                                    respuesta.empleado.Empleado.cuit,
                                    respuesta.empleado.Empleado.fechaingreso,
                                ];
                            var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="loadFormEmpleado('+empleadoID+')" alt="">';
                            tdactions = tdactions + '<form action="'+serverLayoutURL+'/Empleados/delete/'+empleadoID+'" name="post_58b8299bb3aae846453655" id="post_58b6e59f6102d291860796" style="display:none;" method="post"><input type="hidden" name="_method" value="POST"></form>';
                            tdactions = tdactions + '<a href="#" class="deleteEmpleado"><img src="'+serverLayoutURL+'/img/ic_delete_black_24dp.png" width="20" height="20"  alt="Eliminar"></a>';
                            //onclick="eliminarProvedore('+respuesta.compra_id+')"
                            rowData.push(tdactions);

                            var rowIndex = $('#relatedEmpleados').dataTable().fnAddData(rowData);
                            var row = $('#relatedEmpleados').dataTable().fnGetNodes(rowIndex);
                            $(row).attr( 'id', "rowEmpleado"+empleadoID);

                            catchEliminarEmpleado();
                        }
                        location.hash ="#x";
                        $('#EmpleadoAddForm input').val('');

                    },
                    error: function(xhr,textStatus,error){
                        $('#myModal').modal('hide');
                        callAlertPopint(textStatus);
                    }
                });
                return false;
            });
            $('.chosen-select').chosen({search_contains:true});
            reloadDatePickers();
            $('#EmpleadoAddForm #EmpleadoCargoId').filterGroups({groupSelector: '#EmpleadoAddForm #EmpleadoConveniocolectivotrabajoId', });
        });
        $('#myModal').modal('show');

        // Since confModal is essentially a nested modal it's enforceFocus method
        // must be no-op'd or the following error results
        // "Uncaught RangeError: Maximum call stack size exceeded"
        // But then when the nested modal is hidden we reset modal.enforceFocus

    });
}
function loadFormEmpleado(empid){
    jQuery(document).ready(function($) {
        $.ajax({
            type: 'GET',
            url: serverLayoutURL+"/empleados/edit/"+empid,
            data: "",
            success: function(data,textStatus,xhr){
                $('#myModal').on('show.bs.modal', function () {
                        $('#myModal').find('.modal-title').html('Agregar Empleado');
                        $('#myModal').find('.modal-body').html(data);
                    $('.chosen-select').chosen({search_contains:true});
                    reloadDatePickers();
                    $('#EmpleadoEditForm #EmpleadoCargoId').filterGroups({groupSelector: '#EmpleadoEditForm #EmpleadoConveniocolectivotrabajoId', });
                    $('#EmpleadoEditForm').submit(function(){
                        //serialize form data
                        var formData = $(this).serialize();
                        //get form action
                        var formUrl = $(this).attr('action');
                        $.ajax({
                            type: 'POST',
                            url: formUrl,
                            data: formData,
                            success: function(data,textStatus,xhr){
                                $('#myModal').modal('hide');
                                var respuesta = JSON.parse(data);
                                callAlertPopint(respuesta.respuesta);
                                if(respuesta.empleado.Empleado!=null){
                                    var empleadoID = respuesta.empleado.Empleado.id;
                                    var rowData =
                                        [
                                            respuesta.empleado.Empleado.legajo,
                                            respuesta.empleado.Empleado.nombre,
                                            respuesta.empleado.Empleado.dni,
                                            respuesta.empleado.Empleado.cuit,
                                            respuesta.empleado.Empleado.fechaingreso,
                                        ];
                                    var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="loadFormEmpleado('+empleadoID+')" alt="">';
                                    tdactions = tdactions + '<form action="'+serverLayoutURL+'/Empleados/delete/'+empleadoID+'" name="post_58b8299bb3aae846453655" id="post_58b6e59f6102d291860796" style="display:none;" method="post"><input type="hidden" name="_method" value="POST"></form>';
                                    tdactions = tdactions + '<a href="#" class="deleteEmpleado"><img src="'+serverLayoutURL+'/img/ic_delete_black_24dp.png" width="20" height="20"  alt="Eliminar"></a>';
                                    //onclick="eliminarProvedore('+respuesta.compra_id+')"
                                    rowData.push(tdactions);

                                    var rowIndex = $('#relatedEmpleados').dataTable().fnAddData(rowData);
                                    var row = $('#relatedEmpleados').dataTable().fnGetNodes(rowIndex);
                                    $(row).attr( 'id', "rowEmpleado"+empleadoID);

                                    catchEliminarEmpleado();
                                }
                                // var tr = $(this).closest('tr');
                                var tr = $("#rowEmpleado"+empid);
                                $('#relatedEmpleados').dataTable().fnDeleteRow(tr);
//                            $("#rowEmpleado"+empid).replaceWith(data);
                                catchEliminarEmpleado();
                                location.hash ="#x";
                            },
                            error: function(xhr,textStatus,error){
                                $('#myModal').modal('hide');
                                callAlertPopint(textStatus);
                            }
                        });
                        return false;
                    });
                });
                $('#myModal').modal('show');

            },
            error: function(xhr,textStatus,error){
                callAlertPopint(textStatus);
            }
            //aqui no deberiamos recargar la pagina sino simplemente agregar esta info donde debe ser.
        });
    });
}
function catchEliminarProvedore(){
    jQuery(document).ready(function($) {

        $('.deleteProvedore').removeAttr('onclick');

        $('.deleteProvedore').click(function(e) {
            e.preventDefault();
            if (!confirm('Esta seguro que desea eliminar este Provedor')) {
                return false;
            }
            var form = $(this).prev();
            var url = $(form).attr("action");
            var tr = $(this).closest('tr');
            url = url + '.json';
            $.post(url)
                .success(function(res) {
                    var respuesta = jQuery.parseJSON(res);
                    if(!respuesta.error){
                        //tr.fadeOut(200);
                        $('#provedoresDatatable').dataTable().fnDeleteRow(tr);

                    }else{
                    }
                    callAlertPopint(respuesta.respuesta);

                })
                .error(function() {
                    alert("Error");
                })
            return false;
        });
    });
}
function catchEliminarEmpleado(){
    jQuery(document).ready(function($) {

        $('.deleteEmpleado').removeAttr('onclick');

        $('.deleteEmpleado').click(function(e) {
            e.preventDefault();
            if (!confirm('Esta seguro que desea eliminar este Empleado?')) {
                return false;
            }
            var form = $(this).prev();
            var url = $(form).attr("action");
            var tr = $(this).closest('tr');
            url = url + '.json';
            $.post(url)
                .success(function(res) {
                    var respuesta = jQuery.parseJSON(res);
                    if(!respuesta.error){
                        tr.fadeOut(200);
                    }else{
                    }
                    callAlertPopint(respuesta.respuesta);
                })
                .error(function() {
                    alert("Error");
                })
            return false;
        });
    });
}
function catchEliminarSubcliente(){
    jQuery(document).ready(function($) {

        $('.deleteSubcliente').removeAttr('onclick');

        $('.deleteSubcliente').click(function(e) {
            e.preventDefault();
            if (!confirm('Esta seguro que desea eliminar este Subcliente?')) {
                return false;
            }
            var form = $(this).prev();
            var url = $(form).attr("action");
            var tr = $(this).closest('tr');
            url = url + '.json';
            $.post(url)
                .success(function(res) {
                    var respuesta = jQuery.parseJSON(res);
                    if(!respuesta.error){
                        // tr.fadeOut(200);
                        $('#subclientesDatatable').dataTable().fnDeleteRow(tr);
                    }else{
                    }
                    callAlertPopint(respuesta.respuesta);
                })
                .error(function() {
                    alert("Error");
                })
            return false;
        });
    });
}
function catchEliminarBienesdeuso(){
    jQuery(document).ready(function($) {

        $('.deleteBiendeuso').removeAttr('onclick');

        $('.deleteBiendeuso').click(function(e) {
            e.preventDefault();
            if (!confirm('Esta seguro que desea eliminar este Bien de uso?')) {
                return false;
            }
            var form = $(this).prev();
            var url = $(form).attr("action");
            var tr = $(this).closest('tr');
            url = url + '.json';
            $.post(url)
                .success(function(res) {
                    var respuesta = jQuery.parseJSON(res);
                    if(!respuesta.error){
                        tr.fadeOut(200);
                    }else{
                    }
                    callAlertPopint(respuesta.respuesta);
                })
                .error(function() {
                    alert("Error");
                })
            return false;
        });
    });
}
function hideallinputsbdu(){
    jQuery(document).ready(function($) {
        $(".inmuebleFNE").parent().hide();
        $(".inmuebleFE").parent().hide();
        $(".inmuebleJE").parent().hide();
        $(".naves").parent().hide();
        $(".aeronaves").parent().hide();
        $(".instalacionesPF").parent().hide();
        $(".instalacionesPJ").parent().hide();
        $(".otrobiendeusoPF").parent().hide();
        $(".otrobiendeusoPJ").parent().hide();
        $(".bienesmueblesregistrables").parent().hide();
        $(".otrosbienes").parent().hide();
        $(".rodadoPJ").parent().hide();
        $(".rodadoPF").parent().hide();
        $(".automotor").parent().hide();
    });
}
function loadFormBiendeuso(cliid,biendeusoid){
    jQuery(document).ready(function($) {
        var data = "";
        $.ajax({
            type: "get",  // Request method: post, get
            url: serverLayoutURL + "/bienesdeusos/add/" + cliid + "/" + biendeusoid + "/0",

            // URL to request
            data: data,  // post data
            success: function (response) {
                $('#myModal').on('show.bs.modal', function () {
                    $('#myModal').find('.modal-title').html('Agregar Bien de uso de la compra');
                    $('#myModal').find('.modal-body').html(response);
                    // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                });

                $('#myModal').modal('show');
//perzonalizar formulario para tipo de Bien de uso
                $("#BienesdeusoTipo").on('change', function () {
                    var selectedTipo = $(this).val();
                    var tipoPersona = $("#ClienteTipopersona").val();
                    /*
                   * Fisica - Empresa
                   * Fisica NO Empresa
                   * Juridica Empresa*/
                    hideallinputsbdu();
                    switch (selectedTipo) {
                        case 'Automotor':
                            $(".automotor").parent().show();
                            break;
                        case 'Rodado':
                            if(tipoPersona=="fisica"){
                                $(".rodadoPJ").parent().hide();
                                $(".rodadoPF").parent().show();
                                /*estos dos son para personas Fisica Empresa*/
                            }else{
                                $(".rodadoPF").parent().hide();
                                $(".rodadoPJ").parent().show();
                                /*estos dos son para personas Juridica Empresa*/
                            }
                            break;
                        case 'Inmueble':
                            if(tipoPersona=="fisica"){
                                $(".inmuebleFE").parent().show();
                                /*estos dos son para personas Fisica Empresa*/
                            }else{
                                $(".inmuebleJE").parent().show();
                                /*estos dos son para personas Juridica Empresa*/
                            }
                            break;
                        case 'Inmuebles':
                            /*estos dos son para personas Fisicas No Empresa*/
                            $(".inmuebleFNE").parent().show();
                            break;
                        case 'Aeronave':
                            $(".aeronaves").parent().show();
                            break;
                        case 'Naves, Yates y similares':
                            $(".naves").parent().show();
                            break;
                        case 'Instalaciones':
                            if(tipoPersona=="fisica"){
                                $(".instalacionesPF").parent().show();
                                /*estos dos son para personas Fisica Empresa*/
                            }else{
                                $(".instalacionesPJ").parent().show();
                                /*estos dos son para personas Juridica Empresa*/
                            }
                            break;
                        case 'Otros bienes de uso Muebles':
                        case 'Otros bienes de uso Maquinas':
                        case 'Otros bienes de uso Activos Biologicos':
                            if(tipoPersona=="fisica"){
                                $(".otrobiendeusoPF").parent().show();
                                /*estos dos son para personas Fisica Empresa*/
                            }else{
                                $(".otrobiendeusoPJ").parent().show();
                                /*estos dos son para personas Juridica Empresa*/
                            }
                            break;
                        case 'Bien mueble registrable':
                            $(".bienesmueblesregistrables").parent().show();
                            break;
                        case 'Otros bienes':
                            $(".otrosbienes").parent().show();
                            break;
                    }
                });
                $("#BienesdeusoTipo").trigger("change");
                $("#BienesdeusoPorcentajeamortizacion").on('change', function() {
                    var valororiginal = $("#BienesdeusoValororiginal").val();
                    var amortizacionperiodo = valororiginal / $("#BienesdeusoPorcentajeamortizacion").val();
                    if($("#BienesdeusoImporteamorteizaciondelperiodo is:visible")){
                        $("#BienesdeusoImporteamorteizaciondelperiodo").val(amortizacionperiodo);                                    
                    }           
                });
                $("#BienesdeusoPorcentajeamortizacion" ).trigger( "change" );
                
                $('#BienesdeusoAddForm').submit(function () {
                    //serialize form data
                    var formData = $(this).serialize();
                    //get form action
                    var formUrl = $(this).attr('action');
                    $.ajax({
                        type: 'post',
                        url: formUrl,
                        data: formData,
                        success: function (data, textStatus, xhr) {
                            var respuesta = JSON.parse(data);
                            callAlertPopint(respuesta.respuesta);
                            if(respuesta.bienesdeuso.Bienesdeuso!=null){
                                var bienesdeusoID = respuesta.bienesdeuso.Bienesdeuso.id;
                                var descripcionBDU = "";
                                switch (respuesta.bienesdeuso.Bienesdeuso.tipo){
                                    //Empresa
                                    case 'Rodado':
                                        if(respuesta.bienesdeuso.Bienesdeuso.patente!="")
                                            descripcionBDU  += respuesta.bienesdeuso.Bienesdeuso.patente;
                                        if(respuesta.bienesdeuso.Bienesdeuso.aniofabricacion!="")
                                            descripcionBDU  += " -"+respuesta.bienesdeuso.Bienesdeuso.aniofabricacion;
                                        
                                        break;
                                    case 'Inmueble':
                                        if(respuesta.bienesdeuso.Bienesdeuso.calle!="")
                                            descripcionBDU  += respuesta.bienesdeuso.Bienesdeuso.calle;
                                        if(respuesta.bienesdeuso.Bienesdeuso.numero!="")
                                            descripcionBDU  += " -"+respuesta.bienesdeuso.Bienesdeuso.numero;
                                       
                                        break;
                                    case 'Instalaciones':
                                        if(respuesta.bienesdeuso.Bienesdeuso.descripcion!="")
                                            descripcionBDU  += respuesta.bienesdeuso.Bienesdeuso.descripcion;
                                        
                                        break;
                                    case 'Otros bienes de uso Muebles':
                                        if(respuesta.bienesdeuso.Bienesdeuso.descripcion!="")
                                            descripcionBDU  += respuesta.bienesdeuso.Bienesdeuso.descripcion;
                                       
                                        break;
                                    case 'Otros bienes de uso Maquinas':
                                        if(respuesta.bienesdeuso.Bienesdeuso.descripcion!="")
                                            descripcionBDU  += respuesta.bienesdeuso.Bienesdeuso.descripcion;
                                       
                                        break;
                                    case 'Otros bienes de uso Activos Biologicos':
                                        if(respuesta.bienesdeuso.Bienesdeuso.descripcion!="")
                                            descripcionBDU  += respuesta.bienesdeuso.Bienesdeuso.descripcion;
                                         
                                        break;
                                    //NO empresa
                                    case 'Inmuebles':
                                        if(respuesta.bienesdeuso.Bienesdeuso.calle!="")
                                            descripcionBDU  += respuesta.bienesdeuso.Bienesdeuso.calle;
                                        if(respuesta.bienesdeuso.Bienesdeuso.numero!="")
                                            descripcionBDU  += " -"+respuesta.bienesdeuso.Bienesdeuso.numero;
                                        break;
                                    case 'Automotor':
                                        if(respuesta.bienesdeuso.Bienesdeuso.patente!="")
                                            descripcionBDU  += respuesta.bienesdeuso.Bienesdeuso.patente;
                                        if(respuesta.bienesdeuso.Bienesdeuso.aniofabricacion!="")
                                            descripcionBDU  += " -"+respuesta.bienesdeuso.Bienesdeuso.aniofabricacion;
                                         
                                        break;
                                    case 'Naves, Yates y similares':
                                        if(respuesta.bienesdeuso.Bienesdeuso.marca!="")
                                            descripcionBDU  += respuesta.bienesdeuso.Bienesdeuso.marca;
                                        if(respuesta.bienesdeuso.Bienesdeuso.modelo!="")
                                            descripcionBDU  += " -"+respuesta.bienesdeuso.Bienesdeuso.modelo;
                                         
                                        break;
                                    case 'Aeronave':
                                        if(respuesta.bienesdeuso.Bienesdeuso.matricula!="")
                                            descripcionBDU  += respuesta.bienesdeuso.Bienesdeuso.matricula;
                                        if(respuesta.bienesdeuso.Bienesdeuso.fechaadquisicion!="")
                                            descripcionBDU  += " -"+respuesta.bienesdeuso.Bienesdeuso.fechaadquisicion;
                                         
                                        break;
                                    case 'Bien mueble registrable':
                                        if(respuesta.bienesdeuso.Bienesdeuso.descripcion!="")
                                            descripcionBDU  += respuesta.bienesdeuso.Bienesdeuso.descripcion;
                                         
                                        break;
                                    case 'Otros bienes':
                                        if(respuesta.bienesdeuso.Bienesdeuso.descripcion!="")
                                            descripcionBDU  += respuesta.bienesdeuso.Bienesdeuso.descripcion;                                         
                                        break;
                                    }       
                                var rowData =
                                    [
                                       respuesta.bienesdeuso.Bienesdeuso.tipo,
                                       respuesta.bienesdeuso.Bienesdeuso.periodo,
                                       respuesta.bienesdeuso.Bienesdeuso.titularidad,
                                       descripcionBDU,
                                    ];
                                var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="loadFormBiendeuso('+respuesta.bienesdeuso.Bienesdeuso.cliente_id+','+bienesdeusoID+')" alt="">';
                                tdactions = tdactions + '<form action="'+serverLayoutURL+'/Bienesdeusos/delete/'+bienesdeusoID+'" name="post_58b8299bb3aae846453655'+bienesdeusoID+'" id="post_58b6e59f6102d291860796'+bienesdeusoID+'" style="display:none;" method="post"><input type="hidden" name="_method" value="POST"></form>';
                                tdactions = tdactions + '<a href="#" class="deleteBiendeuso"><img src="'+serverLayoutURL+'/img/ic_delete_black_24dp.png" width="20" height="20"  alt="Eliminar"></a>';
                                //onclick="eliminarProvedore('+respuesta.compra_id+')"
                                rowData.push(tdactions);

                                var rowIndex = $('#relatedBienesdeusos').dataTable().fnAddData(rowData);
                                var row = $('#relatedBienesdeusos').dataTable().fnGetNodes(rowIndex);
                                $(row).attr( 'id', "rowBiendeuso"+bienesdeusoID);

                                catchEliminarBienesdeuso();
                            }
                            $('#myModal').modal('hide');

                        },
                        error: function (xhr, textStatus, error) {
                            alert(textStatus);
                        }
                    });
                    return false;
                });
                $('.chosen-select').chosen({search_contains: true});
                $("#BienesdeusoLocalidadeId_chosen").css('width', 'auto');
                $("#BienesdeusoModeloId_chosen").css('width', 'auto');
                reloadDatePickers();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });
}
/*General*/
function restoreRow(row,oldrow,button){
	$( "#"+button ).click(function() {		
		$("#"+row).html(oldrow);
	});
}
function getLocalidades(myform,fromfield,tofield){
	jQuery(document).ready(function($) {
		var formData = $('#'+myform+' #'+fromfield).serialize(); 
		$.ajax({ 
			type: 'POST', 
			url: serverLayoutURL+'/localidades/getbycategory', 
			data: formData, 
			success: function(data,textStatus,xhr){ 
				$('#'+myform+' #'+tofield).empty();
				$('#'+myform+' #'+tofield).html(data);

				}, 
			error: function(xhr,textStatus,error){ 
				callAlertPopint(textStatus); 
			} 
		});	
		return false; 
	});
}
function reloadDatePickers(){
	jQuery(document).ready(function($) {
		$( "input.datepicker" ).datepicker({
						            yearRange: "-100:+50",
						            changeMonth: true,
						            changeYear: true,
						            constrainInput: false,
						            dateFormat: 'dd-mm-yy',
						        });	
		$( "input.datepicker-day-month" ).datepicker({
						            yearRange: "-100:+50",
						            changeMonth: true,
						            changeYear: false,
						            constrainInput: false,
						            dateFormat: 'dd-mm',
						        });	
		$( "input.datepicker-month-year" ).datepicker({
						            yearRange: "-100:+50",
						            changeMonth: true,
						            changeYear: true,
						            constrainInput: false,
						            dateFormat: 'mm-yy',
						        });	
 	});		
}
function pad (str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
}

