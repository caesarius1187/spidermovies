var ventasACargar ;
$(document).ready(function() {
    /*window.addEventListener("beforeunload", function (e) {
        var confirmationMessage = "Esta seguro?";
        e.returnValue = confirmationMessage;     // Gecko, Trident, Chrome 34+
        return confirmationMessage;              // Gecko, WebKit, Chrome <34
    });*/
    $("#loading").css('visibility','visible')
    //callAlertPopint("Automatizando Seleccion");
    $("#VentaImportar").find('select').each(function(){
        //$(this).find('option[text="'+$(this).attr( "defaultoption" )+'"]').val();
        var defaultoption = $(this).attr( "defaultoption" );
        /*$('#'+this.id+' option').filter(function () {
            return $(this).html() == defaultoption;
        }).val();*/
        $('#'+this.id+' option:contains("' + defaultoption + '")').prop('selected', true);

        var attr = $(this).attr('defaultoptionlocalidade');
        if (typeof attr !== typeof undefined && attr !== false) {
            $('#puntosdeventasdomicilio').val(attr);
            var defaultlocalidad = $( "#puntosdeventasdomicilio option:selected" ).text();
            $(this).val(defaultlocalidad);
        }       
    });
    ventasACargar = jQuery.parseJSON($('#ventasACargar').val());
    //callAlertPopint("Mejorando Visibilidad de campos");
    $('.chosen-select').chosen(
        {
            search_contains:true,
            include_group_label_in_selected:true
        }
    );
    $('#VentaImportar').submit(function(){
        var r = confirm("Esta seguro que desea importar estas ventas?");
        if (r == true) {
            //serialize form data
            var formData = $(this).serialize();
            //get form action
            var formUrl = $(this).attr('action');
            $("#VentaImportarAEnviar #Venta0Jsonencript").val(formData);
            var formJsonData = $("#VentaImportarAEnviar").serialize();
            $.ajax({
                type: 'POST',
                url: formUrl,
                data: formJsonData,
                success: function(data,textStatus,xhr){
                    var midata = jQuery.parseJSON(data);
                    callAlertPopint(midata.respuesta);
                    var cliid = $("#VentaCliid").val();
                    var periodo = $("#VentaPeriodo").val();
                    callAlertPopint('Recargando formulario por favor espere');
                    cargarCompras();
                    borrarPrimerasVentas();
                },
                error: function(xhr,textStatus,error){
                    callAlertPopint(textStatus);
                }
            });
        }
        return false;
    });
    //seleccion de tipos gastos por actividad
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
    comportamientoDeFiltros();
    aplicarFiltros();
    agregarConsultaCondicionAFIP();
    $("#loading").css('visibility','hidden');
    actividadCategoria();
    callAlertPopint("Formulario de importacion listo!");
});
function borrarPrimerasVentas(){
    var ventaNumero=0;
    $.each(ventasACargar, function (indexVenta, venta) {
        var numalicuota = 0;
        $.each(venta.Alicuota, function (indexAlicuota, alicuota) {
            ventaNumero++;
            delete ventasACargar[indexAlicuota];
            if(ventaNumero>99){
                return false;
            }
        });
        delete ventasACargar[indexVenta];
        if(ventaNumero>99){
            return false;
        }
    });    
}
function cargarCompras(){
    //vamos a cargar 100 ventas en el formulario
    //vamos a eliminar las 100 ventas del array de ventas a cargar cuando la importacion se haga con exito
    //borrar las 100 solo si fueron cargadas desde aca
    var ventaNumero = 1;
    var tieneMonotributo = $("#tieneMonotributo").val();
    if(Object.keys(ventasACargar).length==0){
        callAlertPopint('No hay mas ventas para importar.');
         $('#VentaImportar').hide('slow');
        location.reload();
        return false;
    }
    if(Object.keys(ventasACargar).length<100){
        callAlertPopint('quedan '+Object.keys(ventasACargar).length+' ventas para importar recargando formulario.');
         $('#VentaImportar').hide('slow');
        location.reload();
        return false;
    }
    $.each(ventasACargar, function (indexVenta, venta) {
        var numalicuota = 0;
        $.each(venta.Alicuota, function (indexAlicuota, alicuota) {
            $("#Venta"+ventaNumero+"Fecha").val(venta.Venta.fecha);        
            $("#Venta"+ventaNumero+"ComprobanteId").val(venta.Venta.comprobanteTipoNuevo);        
            $("#Venta"+ventaNumero+"Comprobanteid").val(venta.Venta.comprobantetipo);        
            $("#Venta"+ventaNumero+"PuntosdeventaId").val(venta.Venta.pdvNuevo);        
            $("#Venta"+ventaNumero+"Puntosdeventaid").val(venta.Venta.puntodeventa);        
            $("#Venta"+ventaNumero+"Numerocomprobante").val(venta.Venta.comprobantenumero);        
            $("#Venta"+ventaNumero+"SubclienteId").val(venta.Venta.clienteNuevo);        
            //cliente _ ID
            $("#Venta"+ventaNumero+"Subclientenombre").val(venta.Venta.nombre);        
            $("#Venta"+ventaNumero+"Subclientecuit").val(venta.Venta.identificacionnumero);        
            $("#Venta"+ventaNumero+"Condicioniva").val();//????????????
            $('#Venta'+ventaNumero+'Condicioniva option:contains("' + venta.Venta.condicioniva + '")').prop('selected', true);
            $("#Venta"+ventaNumero+"Condicioniva").removeClass('controlarInput');
            $("#Venta"+ventaNumero+"Condicioniva").addClass(venta.Venta.classcondicionIVA);
            //Actividad no vamos a poner
            //Tipo Ingreso no vamos a poner
            //Localidad tampoco vamos a cambiar
            $("#Venta"+ventaNumero+"Alicuota").val(alicuota.alicuotaNuevo);    
            var neto = 0;
            if(tieneMonotributo==1){
                neto = venta.Venta.importetotaloperacion;
            }else{
                neto = alicuota.importenetogravado;
            }
            $("#Venta"+ventaNumero+"Neto").val(neto*1);        
            $("#Venta"+ventaNumero+"Iva").val(alicuota.impuestoliquidado*1);        
            $("#Venta"+ventaNumero+"Ivapercep").val(venta.Venta.percepcionesnocategorizados*1);        
            $("#Venta"+ventaNumero+"Iibbpercep").val(venta.Venta.importeingresosbrutos*1);        
            $("#Venta"+ventaNumero+"Actvspercep").val(venta.Venta.importeimpuestosmunicipales*1);        
            $("#Venta"+ventaNumero+"Impinternos").val(venta.Venta.importeimpuestosinternos*1);        
            $("#Venta"+ventaNumero+"Nogravados").val(venta.Venta.importeconceptosprecionetogravado*1);        
            $("#Venta"+ventaNumero+"Excentos").val(venta.Venta.importeoperacionesexentas*1);        
            $("#Venta"+ventaNumero+"Exentosactividadeseconomicas").val(0);        
            $("#Venta"+ventaNumero+"Exentosactividadesvarias").val(0);        
            if(venta.Alicuota.length>=2){
                var totalrecalculado = 0;
                totalrecalculado += alicuota.importenetogravado*1;
                totalrecalculado += alicuota.impuestoliquidado*1;
                if(numalicuota==0){
                    totalrecalculado += venta.Venta.percepcionesnocategorizados*1;
                    totalrecalculado += venta.Venta.importeingresosbrutos*1;
                    totalrecalculado += venta.Venta.importeimpuestosmunicipales*1;
                    totalrecalculado += venta.Venta.importeimpuestosinternos*1;
                    totalrecalculado += venta.Venta.importeconceptosprecionetogravado*1;
                }
            }else{
                totalrecalculado = venta.Venta.importetotaloperacion*1;
            }
            $("#Venta"+ventaNumero+"Total").val(totalrecalculado*1);        
            ventaNumero++;
            numalicuota++;
            if(ventaNumero>100){
                return false;
            }
        });
    });
    callAlertPopint('Formulario Recargado, faltan importar '+Object.keys(ventasACargar).length+' ventas');

}
//vamos a inicalizar los Filtros
function reducirFiltros(filtroID,filtroClase){
    //vamos a reducir el filtro a los elementos presentes en el formulario.
    $("#"+filtroID+" > option").each(function() {
        var opcionEnForm =false;
        var textoOpcionComprobante = this.text;

        if( this.value != ''  ){
            $("."+filtroClase).each(function(){
                var selectedText="";
                if( $(this).is('input:text') ) {
                    selectedText = $(this).val();
                }else{
                    selectedText = $(this).children(':selected').text();
                }
                if(textoOpcionComprobante.indexOf(selectedText)!=-1){
                    opcionEnForm =true;
                }
            });
            if(!opcionEnForm){
                $(this).detach();
            }
        }

    });
}
function comportamientoDeFiltros(){
    //callAlertPopint("Cargando filtros");

    $('#Filtro0ComprobanteId').change(function() {
        aplicarFiltros();
    });
    reducirFiltros("Filtro0ComprobanteId","filtrocomprobante");
    $('#Filtro0PuntosdeventaId').change(function() {
        aplicarFiltros();
    });
    reducirFiltros("Filtro0PuntosdeventaId","filtropuntodeventa");

    $('#Filtro0SubclienteId').change(function() {
        aplicarFiltros();
    });
    reducirFiltros("Filtro0SubclienteId","filtrosubcliente");

    $('#Filtro0Condicioniva').change(function() {
        aplicarFiltros();
    });
    reducirFiltros("Filtro0Condicioniva","filtrocondicioniva");
    $('#Filtro0ActividadclienteId').change(function() {
        aplicarFiltros();
    });
    $('#Filtro0Alicuota').change(function() {
        aplicarFiltros();
    });
    reducirFiltros("Filtro0Alicuota","filtroalicuota");
}
function aplicarFiltros(){
    //callAlertPopint("Aplicando filtros");
    $("#tablaFormVentas tr").each(function(){
        aplicarControlFiltro($(this));
    });
    agregarApplyToAllInFirstRow();
}
function aplicarControlFiltro(mytr){
    var mostrarPorComprobante = false;
    if($('#Filtro0ComprobanteId option:selected').val()!=''){
        mostrarPorComprobante = buscarCoincidenciasFiltro(mytr,'Filtro0ComprobanteId','filtrocomprobante');
    }else{
        mostrarPorComprobante = true;
    }

    var mostrarPorPuntoVenta = false;
    if($('#Filtro0PuntosdeventaId option:selected').val()!=''){
        mostrarPorPuntoVenta = buscarCoincidenciasFiltro(mytr,'Filtro0PuntosdeventaId','filtropuntodeventa');
    }else{
        mostrarPorPuntoVenta = true;
    }

    var mostrarPorSubcliente = false;
    if($('#Filtro0SubclienteId option:selected').val()!=''){
        mostrarPorSubcliente = buscarCoincidenciasFiltro(mytr,'Filtro0SubclienteId','filtrosubcliente');
    }else{
        mostrarPorSubcliente = true;
    }

    var mostrarPorCondicionIVA = false;
    if($('#Filtro0Condicioniva option:selected').val()!=''){
        mostrarPorCondicionIVA = buscarCoincidenciasFiltro(mytr,'Filtro0Condicioniva','filtrocondicioniva');
    }else{
        mostrarPorCondicionIVA = true;
    }

    var mostrarPorActividadcliente = false;
    if($('#Filtro0ActividadclienteId option:selected').val()!=''){
        mostrarPorActividadcliente = buscarCoincidenciasFiltro(mytr,'Filtro0ActividadclienteId','filtroactividadcliente');
    }else{
        mostrarPorActividadcliente = true;
    }

    var mostrarPorAlicuota = false;
    if($('#Filtro0Alicuota option:selected').val()!=''){
        mostrarPorAlicuota = buscarCoincidenciasFiltro(mytr,'Filtro0Alicuota','filtroalicuota');
    }else{
        mostrarPorAlicuota = true;
    }

    if(mostrarPorComprobante && mostrarPorPuntoVenta && mostrarPorSubcliente && mostrarPorCondicionIVA &&
        mostrarPorActividadcliente && mostrarPorAlicuota){
        mytr.show();
    }else{
        mytr.hide();
    }
}
function buscarCoincidenciasFiltro(trToShow, idfiltro, clasefiltro){
    var textFiltro = $('#'+idfiltro+' option:selected').text();

    var textoEncontrado =  false;
    trToShow.find( "."+clasefiltro ).each(function(){
        var selectedText = "";
        if( $(this).is('input:text') ) {
            selectedText = $(this).val();
        }else{
            selectedText = $(this).children(':selected').text();
        }
        if(textFiltro.indexOf(selectedText)){
            textoEncontrado = false;
            return;
        }else{
            textoEncontrado = true;
            return;
        }
    });
    return textoEncontrado;
}
function deletefile(name,cliid,folder,periodo) {
    var r = confirm("Esta seguro que desea eliminar este archivo?. Es una accion que no podra deshacer.");
    if (r == true) {
        var data = "";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL + "/ventas/deletefile/" + name + "/" + cliid + "/" + folder+ "/" + periodo,
            // URL to request
            data: data,  // post data
            success: function (response) {
                location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                callAlertPopint(textStatus);
            }
        });
    }
}
function agregarApplyToAllInFirstRow(){
    /*primero tengo que remover todos los tooltips que estaban creados*/
    $('.aplicableATodos').each(function(){
        $(this).removeClass('tooltip');
    });
    $('.aplicableATodos.tooltiptext').each(function(){
        $(this).remove();
    });

    /*ahora agregamos los nuevos*/
    var row = $('#tablaFormVentas').find('tr:visible:first');
    $(row).find('.aplicableATodos').each(function(){
        //$(this).css('background','blue');
        var myselect = $(this).attr('id');
        var span = $('<span />')
            .attr('class', 'tooltiptext')
            .html(
                $('<input />',{
                    'type':'button',
                    'value':"Aplicar a Todos los visibles",
                    'onclick':"aplicarATodos('"+myselect+"')"
                })
            );
        $(this).closest('div')
            .addClass( "tooltip" )
            .append(
                span
            );
    });
}
function actividadCategoria(){
    $('.inputactividad').each(function(){
        var orden = $(this).attr('ordeventa');
        if($('#Venta'+orden+'jsonactividadescategorias option').size()>0 ){
            console.log( "#Venta"+orden+"TipogastoId filterGroups" );
            $("#Venta"+orden+"TipogastoId").filterGroups({groupSelector: "#Venta"+orden+"jsonactividadescategorias", });
            console.log( "#Venta"+orden+"TipogastoId filterGroups Finished" );
        }

        console.log( "#Venta"+orden+"TipogastoId filterGroups Set ON Change" );
        $("#Venta"+orden+"ActividadclienteId").on('change', function() {
            console.log( "#Venta"+orden+"TipogastoId Changed" );
            $("#Venta"+orden+"jsonactividadescategorias").val($("#Venta"+orden+"ActividadclienteId").val());
            $("#Venta"+orden+"jsonactividadescategorias").trigger( "change" );
        });
        console.log( "#Venta"+orden+"TipogastoId filterGroups Set ON Change Finished" );
        console.log( "#Venta"+orden+"TipogastoId filterGroups Trigguer Change" );
        $("#Venta"+orden+"jsonactividadescategorias").trigger( "change" );
        console.log( "#Venta"+orden+"TipogastoId filterGroups Trigguer Finished" );

    });
}
function aplicarATodos(miinput){
    var valueAAplicar = $('#'+miinput).val();
    var inputclass = $('#'+miinput).attr('inputclass');
    $('select[inputclass="'+inputclass+'"]').each(function() {
        if($(this).is(":visible")){
            $(this).val(valueAAplicar).trigger('chosen:updated');;
        }else if($(this).hasClass('chosen-select')){
            if($('#'+$(this).attr('id')+'_chosen').is(":visible")) {
                $(this).val(valueAAplicar).trigger("chosen:updated");
            }
        }
        //si es clase inputactividad vamos a hacer un trigger change
        if($(this).hasClass('inputactividad')){
            $(this).trigger("change");
        }
    });
}
function agregarConsultaCondicionAFIP(){
    /*ahora agregamos los nuevos*/
    $('.cuitConsultaAFIP').each(function(){
        var mycuit = $(this).val();
        var span = $('<span />')
            .attr('class', 'tooltiptext')
            .html(
                $('<input />',{
                    'type':'button',
                    'value':"Consultar Condicion AFIP",
                    'onclick':"consultaCondicionAfip('"+mycuit+"')"
                })
            );
        $(this).closest('div')
            .addClass( "tooltip" )
            .append(
                span
            );
    });
}
