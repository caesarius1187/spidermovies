$(document).ready(function() {
    $('.chosen-select').chosen({
        search_contains:true,
        include_group_label_in_selected:true
    });
    reloadInputDates();
    $("#cargarAsiento").click(function(){
        $('#myModalFormAgregarAsiento').on('show.bs.modal', function() {
            $('#myModalFormAgregarAsiento').find('.modal-title').html('Agregar Asiento');
//				$('#myModalFormAgregarAsiento').find('.modal-body').html(response);
            // $('#myModalFormAgregarAsiento').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
        });
        $('#myModalFormAgregarAsiento').modal('show');
        $('#Asiento0MovimientoKkkCuentasclienteId_chosen').css('width','300px');
        return false;
    });
    $("#cargarMovimiento").click(function(){
        cargarMovimiento();
        return false;
    });
    catchFormAsiento('FormAgregarAsiento');
    function reloadInputDates(){
        var d = new Date( );
        d.setMonth( d.getMonth( ) - 1 );
        (function($){
            $( "input.datepicker" ).datepicker({
                yearRange: "-100:+50",
                changeMonth: true,
                changeYear: true,
                constrainInput: false,
                dateFormat: 'dd-mm-yy',
                defaultDate: d,
            });
            $( "input.datepicker-dia" ).datepicker({
                yearRange: "-100:+50",
                changeMonth: false,
                changeYear: false,
                constrainInput: false,
                dateFormat: 'dd',
                defaultDate: d,

            });
        })(jQuery);
    }
});
function agregarMovimiento()
{
    var CuentaCliente = $("#chsnCuentasClientes").val();
    var sDebe = $("#txtDebe").val();
    var sHaber = $("#txtHaber").val();
    alert(CuentaCliente);
}
function editarMovimientos(asientoID){
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/movimientos/index/"+asientoID,
        // URL to request
        data: data,  // post data
        success: function(response) {
            $('#myModal').on('show.bs.modal', function() {
                $('#myModal').find('.modal-title').html('Editar Asiento');
                $('#myModal').find('.modal-body').html(response);
                // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
            });
            $('#myModal').modal('show');
            $('.chosen-select').chosen({search_contains:true});
            $("#FormEditaMovimientoCuentascliente_chosen").css('width','300px');
            $('#FormAgregaMovimiento').submit(function(){
                cargarMovimientoEdit();
                return false;
            });
            $('#FormEditAsiento').submit(function(){
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
                        $('#myModal').modal('hide');
                        callAlertPopint(respuesta.respuesta);
                        editarMovimientos(asientoID);
                    },
                    error: function(xhr,textStatus,error){
                        $('#myModal').modal('hide');
                        callAlertPopint(respuesta.error);
                        alert(textStatus);
                    }
                });
                return false;
            });
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
}
function reiniciarFormAgregarAsiento(){
    //Vamos a reiniciar el select de cuentas por que se eliminaron las cuentas ya seleccionadas
    $("#Asiento0MovimientoKkkCuentasclienteId").html( $("#Asiento0MovimientoKkkHidencuentasclienteId").html() );
    $("#Asiento0MovimientoKkkCuentasclienteId").trigger("chosen:updated");
    $(".rowMovimiento").remove();
}
function catchFormAsiento(idForm){
    $('#'+idForm).submit(function(){
        //desactivar los inputs q son para agregar movimientos
        $("#rowdecarga :input").prop("disabled", true);
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
                $('#myModal').modal('hide');
                $('#myModalFormAgregarAsiento').modal('hide');
                callAlertPopint(respuesta.respuesta);
                reiniciarFormAgregarAsiento()
            },
            error: function(xhr,textStatus,error){
                $('#myModal').modal('hide');
                $('#myModalFormAgregarAsiento').modal('hide');
                callAlertPopint(respuesta.error);
                alert(textStatus);
            }
        });
        $("#rowdecarga :input").prop("disabled", false);
        return false;
    });
}

function deleteMovimiento(movid){
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/movimientos/delete/"+movid,
        // URL to request
        data: data,  // post data
        success: function(response) {
            var respuesta = JSON.parse(response);
            callAlertPopint(respuesta.respuesta);
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
}
function deleteRowMovimiento(img){
    $("#movimientonumero"+img).remove();
}
function deleteRowMovimientoEdit(img){
    var movimientoid = $("#Asiento0Movimiento"+img+"Id");
    if(movimientoid.length>0&&movimientoid.val()!=0){
        deleteMovimiento(movimientoid.val());
    }
    $("#movimientoeditnumero"+img).remove();
}
function cargarMovimiento(){
    var cuentaclienteid = $("#Asiento0MovimientoKkkCuentasclienteId").val();
    var cuentanombre = $("#Asiento0MovimientoKkkCuentasclienteId option:selected").text();
    var debe = $("#Asiento0MovimientoKkkDebe").val();
    var haber = $("#Asiento0MovimientoKkkHaber").val();
    var fecha = $("#Asiento0MovimientoKkkFecha").val();
    var movimientonumero =$("#nextmovimiento").val()*1+1;
    $("#tablaasiento").append(
        $('<tr>')
            .attr('id','movimientonumero'+movimientonumero)
            .addClass('rowMovimiento')
            .append(
                $("<td>").append(
                    $("<input>")
                        .val(cuentaclienteid)
                        .attr('id','Asiento0Movimiento'+movimientonumero+'CuentasclienteId')
                        .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][cuentascliente_id]')
                        .attr('type','hidden')
                ).append(
                    $("<input>")
                        .val(cuentanombre)
                        .css('width','300px')
                        .attr('id','Asiento0Movimiento'+movimientonumero+'CuentasclienteNombre')
                        .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][nombre]')
                ).append(
                    $("<input>")
                        .val(debe)
                        .attr('id','Asiento0Movimiento'+movimientonumero+'CuentasclienteDebe')
                        .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][debe]')
                ).append(
                    $("<input>")
                        .val(haber)
                        .attr('id','Asiento0Movimiento'+movimientonumero+'CuentasclienteHaber')
                        .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][haber]')
                ).append(
                    $("<input>")
                        .val(fecha)
                        .attr('id','Asiento0Movimiento'+movimientonumero+'CuentasclienteFecha')
                        .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][fecha]')
                        .attr('type','hidden')
                ).append(
                    $("<img>")
                        .attr('src',serverLayoutURL+'/img/eliminar.png')
                        .attr('width',20)
                        .attr('height',20)
                        .attr('onclick','deleteRowMovimiento('+movimientonumero+')')
                )
            )
    )
    $("#nextmovimiento").val(movimientonumero*1);
    $("#Asiento0MovimientoKkkCuentasclienteId option:selected").remove();
    $("#Asiento0MovimientoKkkCuentasclienteId").trigger("chosen:updated");
}
function cargarMovimientoEdit(){
    var cuentaclienteid = $("#FormEditaMovimientoCuentascliente").val();
    var cuentanombre = $("#FormEditaMovimientoCuentascliente option:selected").text();
    var debe = $("#MovimientoDebe").val();
    var haber = $("#MovimientoHaber").val();
    var fecha = $("#MovimientoFecha").val();
    var movimientonumero =$("#topmovimiento").val()*1+1;
    $("#tablaModificarAsiento").append(
        $('<tr>')
            .attr('id','movimientoeditnumero'+movimientonumero)
            .addClass('rowMovimiento')
            .append(
                $("<td>")
                    .append(
                        $("<input>")
                            .val(cuentaclienteid)
                            .attr('id','Asiento0Movimiento'+movimientonumero+'CuentasclienteId')
                            .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][cuentascliente_id]')
                            .attr('type','hidden')
                    ).append(
                    $("<input>")
                        .val(cuentanombre)
                        .css('width','300px')
                        .attr('id','Asiento0Movimiento'+movimientonumero+'CuentasclienteNombre')
                        .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][nombre]')
                )
            ).append(
            $("<td>").append(
                $("<input>")
                    .val(debe)
                    .attr('id','Asiento0Movimiento'+movimientonumero+'CuentasclienteDebe')
                    .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][debe]')
            ).append(
                $("<input>")
                    .val(haber)
                    .attr('id','Asiento0Movimiento'+movimientonumero+'CuentasclienteHaber')
                    .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][haber]')
            ).append(
                $("<input>")
                    .val(fecha)
                    .attr('id','Asiento0Movimiento'+movimientonumero+'CuentasclienteFecha')
                    .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][fecha]')
                    .attr('type','hidden')
            ).append(
                $("<img>")
                    .attr('src',serverLayoutURL+'/img/eliminar.png')
                    .attr('width',20)
                    .attr('height',20)
                    .attr('onclick','deleteRowMovimientoEdit('+movimientonumero+')')
            )
        )
    )
    $("#topmovimiento").val(movimientonumero*1);
    $("#FormEditaMovimientoCuentascliente option:selected").remove();
    $("#FormEditaMovimientoCuentascliente").trigger("chosen:updated");
}