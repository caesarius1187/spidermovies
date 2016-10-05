$(document).ready(function() {
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
                    window.location.reload(true);
                },4000);
            },
            error: function(xhr,textStatus,error){
                callAlertPopint(textStatus);
            }
        });
        return false;
    });
});
function deletefile(name,cliid,periodo) {
    var r = confirm("Esta seguro que desea eliminar este archivo?. Es una accion que no podra deshacer.");
    if (r == true) {
        var data = "";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL + "/compras/deletefile/" + name + "/" + cliid + "/" + periodo,
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