$(document).ready(function() {
    window.addEventListener("beforeunload", function (e) {
        var confirmationMessage = "Esta seguro?";

        e.returnValue = confirmationMessage;     // Gecko, Trident, Chrome 34+
        return confirmationMessage;              // Gecko, WebKit, Chrome <34
    });
    $("#CompraImportar").find('select').each(function(){
        //$(this).find('option[text="'+$(this).attr( "defaultoption" )+'"]').val();
        var defaultoption = $(this).attr( "defaultoption" );
        /*$('#'+this.id+' option').filter(function () {
         return $(this).html() == defaultoption;
         }).val();*/
        $('#'+this.id+' option:contains("' + defaultoption + '")').prop('selected', true);

        var attr = $(this).attr('defaultoptionlocalidade');
        if (typeof attr !== typeof undefined && attr !== false) {
            $(this).val(attr);
        }
    });
    $('.chosen-select').chosen({
        search_contains:true,
        include_group_label_in_selected:true
    });
    $('#CompraImportar').submit(function(){
        //serialize form data
        var formData = $(this).serialize();
        //get form action
        var formUrl = $(this).attr('action');
        $("#CompraImportarAEnviar #Compra0Jsonencript").val(formData);
        var formJsonData = $("#CompraImportarAEnviar").serialize();
        $.ajax({
            type: 'POST',
            url: formUrl,
            data: formJsonData,
            success: function(data,textStatus,xhr){
                var midata = jQuery.parseJSON(data);
                callAlertPopint(midata.respuesta);
                var cliid = $("#CompraCliid").val();
                var periodo = $("#CompraPeriodo").val();
                callAlertPopint('Recargando formulario por favor espere');
                window.location.reload(true);
                setTimeout(function(){
                    $("#loading").css('visibility','visible');
                    window.location.reload(true);
                },4000);
            },
            error: function(xhr,textStatus,error){
                callAlertPopint(textStatus);
            }
        });
        return false;
    });
    agregarApplyToAllInFirstRow();
});
function deleterow(rowid){
    $("#row"+rowid).remove();
}
function deletefile(name,cliid,folder,periodo) {
    var r = confirm("Esta seguro que desea eliminar este archivo?. Es una accion que no podra deshacer.");
    if (r == true) {
        var data = "";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL + "/compras/deletefile/" + name + "/" + cliid + "/" + folder+ "/" + periodo,
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
    var row = $('#tblAddCompras').find('tr:visible:first');
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