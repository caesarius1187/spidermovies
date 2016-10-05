$.noConflict();  //Not to conflict with other scripts
jQuery(document).ready(function($) {
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
                    $("#saveDatosPersonalesForm #ClienteEditLabelNombre").text("Apellido y Nombre");
                    $('#saveDatosPersonalesForm #ClienteTipopersonajuridica').val("");
                    $('#saveDatosPersonalesForm #ClienteTipopersonajuridica').attr('disabled', true);

                    $("#saveDatosPersonalesForm #ClienteDni").attr('disabled', false);

                    $("#saveDatosPersonalesForm #ClienteModificacionescontrato").val("");
                    $("#saveDatosPersonalesForm #ClienteModificacionescontrato").attr('disabled', true);
                    break;
                case "juridica":
                    $("#saveDatosPersonalesForm #clienteEditLabelNombre").text("Razon Social");

                    $('#saveDatosPersonalesForm #ClienteTipopersonajuridica').attr('disabled', false);

                    $("#saveDatosPersonalesForm #ClienteModificacionescontrato").attr('disabled', false);

                    $("#saveDatosPersonalesForm #ClienteDni").val("");
                    $("#saveDatosPersonalesForm #ClienteDni").attr('disabled', true);
                    break;
            }
            if($('#ClienteTipopersona').val()=='juridica'){
            }else{

            }
        });
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
                    if (data.indexOf('redireccionar') == -1)
                    {
                        callAlertPopint(data);
                        loadFormEditarPersona();
                    }
                    else
                    {
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
                    var subclienteID = mirespuesta.subcliente.Subcliente.id;
                    $("#relatedClientes").find('tbody')
                        .append($('<tr>')
                            .attr('class', "subcliente")
                            .attr('style', "display: table-row;")
                            .attr('id', "rowSubcliente"+subclienteID)
                            .append($('<td>')
                                .text(mirespuesta.subcliente.Subcliente.cuit)
                            )
                            .append($('<td>')
                                .text(mirespuesta.subcliente.Subcliente.dni)
                            )
                            .append($('<td>')
                                .text(mirespuesta.subcliente.Subcliente.nombre)
                            )
                            .append($('<td>')
                                .append($('<a>')
                                    .attr('onclick', "loadFormSubcliente("+subclienteID+")")
                                    .append($('<img>')
                                        .attr('src', serverLayoutURL+'/img/edit_view.png')
                                        .text('Image cell')
                                    )
                                )
                                .append('<form action="'+serverLayoutURL+'/Subclientes/delete/'+subclienteID+'" name="post_57e41d8188965451050686'+subclienteID+'" id="post_57e41d8188965451050686'+subclienteID+'" style="display:none;" method="post"><input type="hidden" name="_method" value="POST"></form>')
                                .append($('<a>')
                                    .attr('class', "deleteSubcliente")
                                    .append($('<img>')
                                        .attr('src', serverLayoutURL+'/img/ic_delete_black_24dp.png')
                                        .text('Image cell')
                                    )
                                )
                            )
                        );
                    catchEliminarSubcliente();
                    location.hash ="#x";
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
                        $('#CompraProvedoreId').append($('<option>', {
                            value: respuesta.provedor.Provedore.id,
                            text: respuesta.provedor.Provedore.nombre+'-'+
                            respuesta.provedor.Provedore.dni+'-'+
                            respuesta.provedor.Provedore.cuit
                        }));
                        var provedoreID = respuesta.provedor.Provedore.id;
                        var newtr =$('<tr>')
                            .attr('id', 'rowProvedore'+provedoreID)
                            .attr('class', 'provedor')
                            .append($('<td>')
                                .text(respuesta.provedor.Provedore.cuit)
                            )
                            .append($('<td>')
                                .text(respuesta.provedor.Provedore.dni)
                            )
                            .append($('<td>')
                                .text(respuesta.provedor.Provedore.nombre)
                            )
                            .append($('<td>')
                                .append($('<a>')
                                    .attr('onclick', "loadFormProvedore("+provedoreID+")")
                                    .append($('<img>')
                                        .attr('src', serverLayoutURL+'/img/edit_view.png')
                                        .text('Image cell')
                                    )
                                )
                                .append('<form action="'+serverLayoutURL+'/Provedores/delete/'+provedoreID+'" name="post_57e41d8188965451050686'+provedoreID+'" id="post_57e41d8188965451050686'+provedoreID+'" style="display:none;" method="post"><input type="hidden" name="_method" value="POST"></form>')
                                .append($('<a>')
                                    .attr('class', "deleteProvedore")
                                    .append($('<img>')
                                        .attr('src', serverLayoutURL+'/img/ic_delete_black_24dp.png')
                                        .text('Image cell')
                                    )
                                )
                            );
                        $("#relatedProvedores").append(newtr);
                        catchEliminarProvedore();
                    }
                },
                error: function(xhr,textStatus,error){
                    callAlertPopint(textStatus);

                }
            });
            return false;
        });
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
                    $("#relatedEmpleados").append(data);
                    location.hash ="#x";
                    $('#EmpleadoAddForm input').val('');
                },
                error: function(xhr,textStatus,error){
                    callAlertPopint(textStatus);
                }
            });
            return false;
        });
        catchEliminarProvedore();
        catchEliminarEmpleado();
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
		$( "#lblFacturacion" ).click(function() {		
		 if($('.facturacion').is(":visible")){
		 	 $('.facturacion').hide();
			 	 $("#imgFacturacion").attr('src',serverLayoutURL+"/img/menos2.png");
		 	}else{
	 		 $('.facturacion').show();
			 	 $("#imgFacturacion").attr('src',serverLayoutURL+"/img/mas2.png");
		 	}
		});
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
				$("#imgSubclientes").attr('src',serverLayoutURL+"/img/mas2.png");
			}
		});
		$( "#lblProvedores" ).click(function() {
			if($('.provedor').is(":visible")){
				$('.provedor').hide();
				$("#imgProvedores").attr('src',serverLayoutURL+"/img/menos2.png");
			}else{
				$('.provedor').show();
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
				$("#form_impcli_dgrm_provincia").html(response);
				$('.chosen-select').chosen({search_contains:true});
				//aca vamos a sacar el json con las actividadesclientes que tienen actividades que tienen alicuotas cargadas
				var jsonactividadcliente = jQuery.parseJSON($('#ImpcliprovinciaJsonactividadcliente').val());
				setOnChangeProvincia(jsonactividadcliente);
				location.href='#nuevo_IMPProv';
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
				$("#form_impcli_dgrm_provincia").html(response);
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
				}
				catch(err) {

				}

				location.href='#nuevo_IMPProv';

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
function deleteImpcliProvincia(impcliprovinciaid){
	jQuery(document).ready(function($) {
		var r = confirm("Esta seguro que desea eliminar esta provincia?.");
		if (r == true) {
			$.ajax({
				type: "post",  // Request method: post, get
				url: serverLayoutURL+"/impcliprovincias/delete/"+impcliprovinciaid, // URL to request
				data: "",  // post data
				success: function(response) {
					var midata = jQuery.parseJSON(response);
					callAlertPopint(midata.respuesta);
					$('#rowImpcliProvincia'+impcliprovinciaid).hide();
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
					}else{
						$("#"+impTable).append(mirespuesta.impclirow);
						$("#"+impForm+" #ImpcliImpuestoId").find('option:selected').remove();
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
function loadFormEmpleado(empid){
    jQuery(document).ready(function($) {
        $.ajax({
            type: 'GET',
            url: serverLayoutURL+"/empleados/edit/"+empid,
            data: "",
            success: function(data,textStatus,xhr){
                $("#form_modificar_empleado").html(data);
                //$("#form_modificar_domicilio").width("600px");
                $('.chosen-select').chosen({search_contains:true});
                location.href='#modificar_empleado';
                reloadDatePickers();
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
                            $("#rowEmpleado"+empid).replaceWith(data);
                            catchEliminarEmpleado();
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


