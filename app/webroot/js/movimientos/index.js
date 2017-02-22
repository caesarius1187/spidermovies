/**
 * Created by caesarius on 10/02/2017.
 */
$(document).ready(function() {
    $('.chosen-select').chosen({search_contains:true});
    $('#AsientoAddForm').submit(function(){
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
                $('#myModal').modal('hide');
            },
            error: function(xhr,textStatus,error){
                alert(textStatus);
            }
        });
        return false;
    });
    $(".inputDebe").each(function () {
        $(this).change(addTolblTotalDebeAsieto);
    });
    $(".inputHaber").each(function () {
        $(this).change(addTolblTotalhaberAsieto);
    });
});
function agregarMovimiento()
{
    var tablaAsiento = $("#tablasiento");
    /*Sacar numero de movimiento siguiente*/
    /*Tengo que agregar cuentacliente_id*/
    /*Tengo que agregar debe*/
    /*Tengo que agregar haber*/
}
function setTwoNumberDecimal(event) {
    this.value = parseFloat(this.value).toFixed(2);
}
function addTolblTotalDebeAsieto(event) {
    var debesubtotal = 0;
    $(".inputDebe").each(function () {
        debesubtotal = debesubtotal*1 + this.value*1;
    });
    $("#lblTotalDebe").text(parseFloat(debesubtotal).toFixed(2)) ;
    showIconDebeHaber()
}
function addTolblTotalhaberAsieto(event) {
//        $("#lblTotalAFavor").val(0) ;
    var habersubtotal = 0;
    $(".inputHaber").each(function () {
        habersubtotal = habersubtotal*1 + this.value*1;
    });
    $("#lblTotalHaber").text(parseFloat(habersubtotal).toFixed(2)) ;
    showIconDebeHaber()
}
function showIconDebeHaber(){
    if($("#lblTotalHaber").text()==$("#lblTotalDebe").text()){
        $("#iconDebeHaber").attr('src',serverLayoutURL+'/img/test-pass-icon.png');
    }else{
        $("#iconDebeHaber").attr('src',serverLayoutURL+'/img/test-fail-icon.png');
    }

}
