$(document).ready(function() {
    $("#loading").css('visibility','visible')
    $('#MovimientosbancarioImportar').submit(function(){
        /**
         * Primero vamos a controlar que no haya debitos y creditos cargados par el mismo movimiento
         * o tiene debito o tiene credito*/
        var cantmovimientos = $("#Movimientosbancario1Cantmovimientos").val();
        for( i = 1; i<cantmovimientos; i++ ){
            if($("#Movimientosbancario"+i+"Debito").length>0){
                if($("#Movimientosbancario"+i+"Debito").val()!=0 && $("#Movimientosbancario"+i+"Credito").val()!=0){
                    alert("Error esta tratando de cargar debito y credito en una cuenta al mismo tiempo");
                    return false;
                }
            }
        }
        //serialize form data
        var formData = $(this).serialize();
        //get form action
        var formUrl = $(this).attr('action');
        $("#MovimientosbancarioImportarAEnviar #Movimientosbancario0Jsonencript").val(formData);
        var formJsonData = $("#MovimientosbancarioImportarAEnviar").serialize();
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

    $('#Filtro0Concepto').change(function() {
        aplicarFiltros();
    });
    $('#Filtro0CuentasclienteId').change(function() {
        aplicarFiltros();
    });
}
function aplicarFiltros(){
    callAlertPopint("Aplicando filtros");
    $("#tablaFormMovimientosbancarios tr").each(function(){
        aplicarControlFiltro($(this));
    });
}
function deleterow(rowid){
    $("#row"+rowid).remove();
}
function aplicarControlFiltro(mytr){
    var mostrarPorConcepto = false;
    if($('#Filtro0Concepto option:selected').val()!=''){
        mostrarPorConcepto = buscarCoincidenciasFiltroTexto(mytr,'Filtro0Concepto','filtroconcepto');
    }else{
        mostrarPorConcepto = true;
    }
    var mostrarPorCuentaContable = false;
    if($('#Filtro0CuentasclienteId option:selected').val()!=''){
        mostrarPorCuentaContable = buscarCoincidenciasFiltro(mytr,'Filtro0CuentasclienteId','filtrocuentascontable');
    }else{
        mostrarPorCuentaContable = true;
    }


    if(mostrarPorConcepto && mostrarPorCuentaContable){
        mytr.show();
    }else{
        mytr.hide();
    }
}
function buscarCoincidenciasFiltro(trToShow, idfiltro, clasefiltro){
    var textFiltro = $('#'+idfiltro+' option:selected').text();

    var textoEncontrado =  false;
    trToShow.find( "."+clasefiltro ).each(function(){
        var selectedText = $(this).children(':selected').text();
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
function buscarCoincidenciasFiltroTexto(trToShow, idfiltro, clasefiltro){
    var textFiltro = $('#'+idfiltro+' option:selected').text();

    var textoEncontrado =  false;
    trToShow.find( "."+clasefiltro ).each(function(){
        var selectedText = $(this).val();
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
function deletefile(name,cliid,folder,periodo,impcli,cbu) {
    var r = confirm("Esta seguro que desea eliminar este archivo?. Es una accion que no podra deshacer.");
    if (r == true) {
        location.href= serverLayoutURL + "/movimientosbancarios/deletefile/" + name + "/" + cliid + "/" + folder+ "/" + periodo+ "/" + impcli+ "/" + cbu;
        // var data = "";
        // $.ajax({
        //     type: "post",  // Request method: post, get
        //     url: serverLayoutURL + "/movimientosbancarios/deletefile/" + name + "/" + cliid + "/" + folder+ "/" + periodo+ "/" + impcli+ "/" + cbu,
        //     // URL to request
        //     data: data,  // post data
        //     success: function (response) {
        //         //location.reload();
        //     },
        //     error: function (XMLHttpRequest, textStatus, errorThrown) {
        //         callAlertPopint(textStatus);
        //     }
        // });
    }
}