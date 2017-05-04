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
    $("#tblAsientos").DataTable();
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
        success: function(mirespuesta) {
            if($('#isajaxrequest').val()==0){
                //si existe este div Cargo everything aca
                $('#myModalAsientos').on('show.bs.modal', function() {
                    $('#myModalAsientos').find('.modal-title').html('Editar Asiento');
                    $('#myModalAsientos').find('.modal-body').html(mirespuesta);
                    // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                    //Aca vamos a personalizar el close y la X para cerrar por que puede que tengamos 2 modal uno encima del otro
                    $('#myModalAsientos').find('.close').click(function(e){
                        // $(this).preventDefault();
                        setTimeout(
                            function()
                            {
                                $('#myModal').modal('show');
                            }, 100);
                    });
                    $('#myModalAsientos').find('.btn-default').click(function(e){
                        // $(this).preventDefault();
                        setTimeout(
                            function()
                            {
                                $('#myModal').modal('show');
                            }, 100);
                    });

                });
                $('#myModalAsientos').modal('show');
            }else{
                //Si no existe voy a buscar el div "divEditarAsiento" que deberia estar en asientos/index
                $('#divEditarAsiento').html(mirespuesta);
            }

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
                        if($('#isajaxrequest').val()==0){
                            $('#myModalAsientos').modal('hide');
                        }else{
                            $('#divEditarAsiento').html("");
                        }
                        $('#myModalAsientos').modal('hide');
                        callAlertPopint(respuesta.respuesta);
                        //editarMovimientos(asientoID);
                    },
                    error: function(xhr,textStatus,error){
                        if($('#isajaxrequest').val()==0){
                            $('#myModalAsientos').modal('hide');
                        }else{
                            $('#myModal').modal('hide');
                        }
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
                 $('#myModalAsientos').modal('hide');
                 $('#myModalFormAgregarAsiento').modal('hide');
                 callAlertPopint(respuesta.respuesta);
                // reiniciarFormAgregarAsiento()
                location.reload();
            },
            error: function(xhr,textStatus,error){
                $('#myModalAsientos').modal('hide');
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
                        .val(0)
                        .attr('id','Asiento0Movimiento'+movimientonumero+'Id')
                        .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][id]')
                        .attr('type','hidden')
                ).append(
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
                        .addClass("inputDebe movimientoConValor")

                ).append(
                    $("<input>")
                        .val(haber)
                        .attr('id','Asiento0Movimiento'+movimientonumero+'CuentasclienteHaber')
                        .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][haber]')
                        .addClass("inputHaber movimientoConValor")
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
    var cuentanumero = $("#FormEditaMovimientoCuentascliente option:selected").closest('optgroup').prop('label');
    var debe = $("#MovimientoDebe").val();
    var haber = $("#MovimientoHaber").val();
    var movimientonumero =$("#topmovimiento").val()*1+1;
    var fecha = $("#MovimientoFecha").val();

    $("#tablaModificarAsiento").append(
        $('<tr>')
            .attr('id','movimientoeditnumero'+movimientonumero)
            .addClass('rowMovimiento')
            .append(
                $("<td>")
                    .append(
                        $("<input>")
                            .val(cuentanumero)
                            .css('width','300px')
                            .attr('id','Asiento0Movimiento'+movimientonumero+'CuentasclienteNumero')
                            .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][numero]')
                    )
            ).append(
                $("<td>")
                    .append(
                        $("<input>")
                            .val(0)
                            .attr('id','Asiento0Movimiento'+movimientonumero+'Id')
                            .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][id]')
                            .attr('type','hidden')
                    ).append(
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
                        .addClass("inputDebe movimientoConValor")
            ).append(
                $("<input>")
                    .val(haber)
                    .attr('id','Asiento0Movimiento'+movimientonumero+'CuentasclienteHaber')
                    .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][haber]')
                    .addClass("inputHaber movimientoConValor")
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
    $(".inputDebe").each(function () {
        $(this).change(addTolblTotalDebeAsieto);
    });
    $(".inputHaber").each(function () {
        $(this).change(addTolblTotalhaberAsieto);
    });
    $("#topmovimiento").val(movimientonumero*1);
    $("#FormEditaMovimientoCuentascliente option:selected").remove();
    $("#FormEditaMovimientoCuentascliente").trigger("chosen:updated");
    $(".inputHaber").each(function () {
        $(this).trigger("change");
        return;
    });
}