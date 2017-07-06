$(document).ready(function() {
    window.addEventListener("beforeunload", function (e) {
        var confirmationMessage = "Esta seguro?";

        e.returnValue = confirmationMessage;     // Gecko, Trident, Chrome 34+
        return confirmationMessage;              // Gecko, WebKit, Chrome <34
    });
    $("#loading").css('visibility','visible')
    callAlertPopint("Automatizando Seleccion");
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
    callAlertPopint("Mejorando Visibilidad de campos");
    $('.chosen-select').chosen(
        {
            search_contains:true,
            include_group_label_in_selected:true
        }
    );
    $('#VentaImportar').submit(function(){
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
                window.location.reload(true);
                setTimeout(function(){
                    window.location.reload(true);
                },4000);
            },
            error: function(xhr,textStatus,error){
                callAlertPopint(textStatus);
            }
        });
        return false;
    });
    comportamientoDeFiltros();
    aplicarFiltros();
    $("#loading").css('visibility','hidden')

});
//vamos a inicalizar los Filtros
function comportamientoDeFiltros(){
    callAlertPopint("Cargando filtros");

    $('#Filtro0ComprobanteId').change(function() {
        aplicarFiltros();
    });
    $('#Filtro0PuntosdeventaId').change(function() {
        aplicarFiltros();
    });
    $('#Filtro0SubclienteId').change(function() {
        aplicarFiltros();
    });
    $('#Filtro0Condicioniva').change(function() {
        aplicarFiltros();
    });
    $('#Filtro0ActividadclienteId').change(function() {
        aplicarFiltros();
    });
    $('#Filtro0Tipodebito').change(function() {
        aplicarFiltros();
    });
    $('#Filtro0Alicuota').change(function() {
        aplicarFiltros();
    });
}
function aplicarFiltros(){
    callAlertPopint("Aplicando filtros");
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

    var mostrarPorTipoDebito = false;
    if($('#Filtro0Tipodebito option:selected').val()!=''){
        mostrarPorTipoDebito = buscarCoincidenciasFiltro(mytr,'Filtro0Tipodebito','filtrotipodebito');
    }else{
        mostrarPorTipoDebito = true;
    }

    var mostrarPorAlicuota = false;
    if($('#Filtro0Alicuota option:selected').val()!=''){
        mostrarPorAlicuota = buscarCoincidenciasFiltro(mytr,'Filtro0Alicuota','filtroalicuota');
    }else{
        mostrarPorAlicuota = true;
    }

    if(mostrarPorComprobante && mostrarPorPuntoVenta && mostrarPorSubcliente && mostrarPorCondicionIVA &&
        mostrarPorActividadcliente && mostrarPorTipoDebito && mostrarPorAlicuota){
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
        if(selectedText.indexOf(textFiltro)){
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
    $('.tooltiptext').each(function(){
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
    });
}