var tblAsientos;
$(document).ready(function() {
    $('.chosen-select').chosen({
        search_contains:true,
        include_group_label_in_selected:true
    });
    var nombrecliente = $('#clientenombre').val();
    var periodo = $('#periododefault').val();
    var peranio = $('#peranio').val();
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
    tblAsientos = $("#tblAsientos").DataTable( {
        order: [[ 7, "desc" ]],
        dom: 'Bfrtip',
        select: true,
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10', '25', '50', 'todas' ]
        ],
        buttons: [
            {
                extend: 'pageLength',
                text: 'Ver',
            },
            {
                extend: 'csv',
                text: 'CSV',
                title: 'Asientos-'+nombrecliente+'-'+periodo,
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                text: 'Excel',
                title: 'Asientos-'+nombrecliente+'-'+periodo,
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                text: 'PDF',
                title: 'Asientos-'+nombrecliente+'-'+periodo,
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                download: 'open',
                message: 'Asientos-'+nombrecliente+'-'+periodo,

            },
            {
                extend: 'copy',
                text: 'Copiar',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print',
                text: 'Imprimir',
                exportOptions: {
                    columns: '.printable'
                },
                orientation: 'landscape',
                footer: true,
                autoPrint: true,
                message: 'Asientos-'+nombrecliente+'-'+periodo,
                customize: function ( win ) {
                },
            },
        ],
        iDisplayLength: -1
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
            if( $('#peranio').length>0){
                 $("input.datepickerOneYear").datepicker({ 
                    minDate: new Date($('#peranio').val()-1, 12, 1), 
                    dateFormat: 'dd-mm-yy',
                    maxDate: new Date($('#peranio').val(), 11, 31),
                    changeMonth: true,
                    changeYear: true,
                    constrainInput: false,
                    defaultDate: d,
                 });
            }else{
                $( "input.datepicker-dia" ).datepicker({
                    yearRange: "-1:+1",
                    changeMonth: false,
                    changeYear: false,
                    constrainInput: false,
                    dateFormat: 'dd',
                    defaultDate: d,
                });
            }
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
function eliminarAsiento(asientoID){
     var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/asientos/eliminar/"+asientoID,
        // URL to request
        data: data,  // post data
        success: function(mirespuesta) {
            var respuesta = JSON.parse(mirespuesta);          
            callAlertPopint(respuesta.respuesta);
            location.reload();
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
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
              
                $("#myModal").animate({ 
                              scrollTop: $("#myModal").height() 
                          }, 
                          "slow"
                      );      
            }

            $('.selectedAsiento').removeClass('selectedAsiento');
            $('#rowasiento'+asientoID).addClass('selectedAsiento');

            $('.chosen-select').chosen({search_contains:true});
            //$("#FormEditaMovimientoCuentascliente_chosen").css('width','300px');
            $('.chosen-container').css('width','300px');

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
                            if(respuesta.error==0){
                                $('#myModalAsientos').modal('hide');
                                var r = confirm(respuesta.respuesta + "Desea Recargar la pagina?");
                                if(r){
                                    location.reload();
                                }
                            }else{
                               var divRespueta = $('<div>')
                                        .append(
                                            $('<h4>').html(
                                               "Error:"
                                            )
                                        )
                                       .append(
                                            $('<label>').html(
                                                respuesta.respuesta
                                            )
                                        )
                                        .addClass('index');;
                                $('#myModalAsientos').find('.modal-body').prepend(divRespueta);
                                $("#myModalAsientos").scrollTop(0);
                            }
                        }else{
                            
                            if(respuesta.error==0){
                                 $('#divEditarAsiento').html("");
                                var r = confirm(respuesta.respuesta + "<label>Desea Recargar la pagina?</label>");
                                if(r){
                                    location.reload();
                                }
                            }else{
                                var divRespueta = $('<div>')
                                        .append(
                                            $('<h4>').html(
                                               "Error:"
                                            )
                                        )
                                        .append(
                                            $('<label>').html(
                                                respuesta.respuesta
                                            )
                                        ).addClass('index');
                                $('#divEditarAsiento').prepend(divRespueta);
                                $("#myModal").scrollTop(0);
                            }
                        }
                        
                        
                       
                        //editarMovimientos(asientoID);
                    },
                    error: function(xhr,textStatus,error){
                        if($('#isajaxrequest').val()==0){
                            $('#myModalAsientos').modal('hide');
                        }else{
                            $('#myModal').modal('show');
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
                 //$('#myModalAsientos').modal('hide');
                 //$('#myModalFormAgregarAsiento').modal('hide');
                if(respuesta.error!=0){
                    //callAlertPopint(respuesta.respuesta);
                    var divRespueta = $('<div>')
                            .append(
                                $('<h4>').html(
                                   "Error:"
                                )
                            )
                           .append(
                                $('<label>').html(
                                    respuesta.respuesta
                                )
                            )
                            .addClass('index');;
                    if($('#myModalFormAgregarAsiento').length>0){
                        $('#myModalFormAgregarAsiento').find('.modal-body').prepend(divRespueta);
                    }else{
                        $('#myModalAsientos').find('.modal-body').prepend(divRespueta);
                        $("#myModalAsientos").scrollTop(0);
                    }
                }else{
                    callAlertPopint(respuesta.respuesta);
                }
                 // reiniciarFormAgregarAsiento()
                 //location.reload();
            },
            error: function(xhr,textStatus,error){
                $('#myModalAsientos').modal('hide');
                $('#myModalFormAgregarAsiento').modal('hide');
                callAlertPopint(error);
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
                        .attr('id','Asiento1Movimiento'+movimientonumero+'Id')
                        .attr('name','data[Asiento][1][Movimiento]['+movimientonumero+'][id]')
                        .attr('type','hidden')
                ).append(
                    $("<input>")
                        .val(cuentaclienteid)
                        .attr('id','Asiento1Movimiento'+movimientonumero+'CuentasclienteId')
                        .attr('name','data[Asiento][1][Movimiento]['+movimientonumero+'][cuentascliente_id]')
                        .attr('type','hidden')
                ).append(
                    $("<input>")
                        .val(cuentanombre)
                        .css('width','300px')
                        .attr('id','Asiento1Movimiento'+movimientonumero+'CuentasclienteNombre')
                        .attr('name','data[Asiento][1][Movimiento]['+movimientonumero+'][nombre]')
                ).append(
                    $("<input>")
                        .val(debe)
                        .attr('id','Asiento1Movimiento'+movimientonumero+'CuentasclienteDebe')
                        .attr('name','data[Asiento][1][Movimiento]['+movimientonumero+'][debe]')
                        .attr('type','number')
                        .attr('step','any')
                        .addClass("inputDebeAdd movimientoConValor")
                ).append(
                    $("<input>")
                        .val(haber)
                        .attr('id','Asiento1Movimiento'+movimientonumero+'CuentasclienteHaber')
                        .attr('name','data[Asiento][1][Movimiento]['+movimientonumero+'][haber]')
                        .attr('type','number')
                        .attr('step','any')
                        .addClass("inputHaberAdd movimientoConValor")
                ).append(
                    $("<input>")
                        .val(fecha)
                        .attr('id','Asiento1Movimiento'+movimientonumero+'CuentasclienteFecha')
                        .attr('name','data[Asiento][1][Movimiento]['+movimientonumero+'][fecha]')
                        .attr('type','hidden')
                ).append(
                    $("<img>")
                        .attr('src',serverLayoutURL+'/img/eliminar.png')
                        .attr('width',20)
                        .attr('height',20)
                        .attr('onclick','deleteRowMovimiento('+movimientonumero+')')
                )
            )
    );

    $("#nextmovimiento").val(movimientonumero*1);
    $("#Asiento0MovimientoKkkCuentasclienteId option:selected").remove();
    $("#Asiento0MovimientoKkkCuentasclienteId").trigger("chosen:updated");
    $("#Asiento0MovimientoKkkDebe").val(0);
    $("#Asiento0MovimientoKkkHaber").val(0);
    $(".inputDebeAdd").each(function () {
        $(this).change(addTolblTotalDebeAsietoAdd);
    });
    $(".inputHaberAdd").each(function () {
        $(this).change(addTolblTotalHaberAsietoAdd);
    });
    $(".inputDebeAdd").each(function () {
        $(this).trigger("change");
        return;
    });
    $(".inputHaberAdd").each(function () {
        $(this).trigger("change");
        return;
    });


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
                        .css('width','auto')
                        .attr('id','Asiento0Movimiento'+movimientonumero+'CuentasclienteDebe')
                        .attr('name','data[Asiento][0][Movimiento]['+movimientonumero+'][debe]')
                        .addClass("inputDebe movimientoConValor")
            ).append(
                $("<input>")
                    .val(haber)
                    .css('width','auto')
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
    );

    $(".inputDebe").each(function () {
        $(this).change(addTolblTotalDebeAsietoAdd);
    });
    $(".inputHaber").each(function () {
        $(this).change(addTolblTotalHaberAsietoAdd);
    });
    $("#topmovimiento").val(movimientonumero*1);
    $("#FormEditaMovimientoCuentascliente option:selected").remove();
    $("#MovimientoDebe").val(0);
    $("#MovimientoHaber").val(0)
    $("#FormEditaMovimientoCuentascliente").trigger("chosen:updated");
    $(".inputDebe").each(function () {
        $(this).trigger("change");
        return;
    });
    $(".inputHaber").each(function () {
        $(this).trigger("change");
        return;
    });
}
function addTolblTotalDebeAsietoAdd(event) {
    var debesubtotal = 0;
    $(".inputDebe").each(function () {
        debesubtotal = debesubtotal*1 + this.value*1;
        if(this.value*1!=0){
            $(this).removeClass("movimientoSinValor");
            $(this).addClass("movimientoConValor");
        }else{
            $(this).removeClass("movimientoConValor")
            $(this).addClass("movimientoSinValor");
        }

    });
    $("#lblTotalDebe").text(parseFloat(debesubtotal).toFixed(2)) ;
    var debesubtotalAdd = 0;
    $(".inputDebeAdd").each(function () {
        debesubtotalAdd = debesubtotalAdd*1 + this.value*1;
        if(this.value*1!=0){
            $(this).removeClass("movimientoSinValor");
            $(this).addClass("movimientoConValor");
        }else{
            $(this).removeClass("movimientoConValor")
            $(this).addClass("movimientoSinValor");
        }

    });
    $("#lblTotalDebeAdd").text(parseFloat(debesubtotalAdd).toFixed(2)) ;
    showIconDebeHaberAdd()
}
function addTolblTotalHaberAsietoAdd(event) {
    //        $("#lblTotalAFavor").val(0) ;
    var habersubtotal = 0;
    $(".inputHaber").each(function () {
        habersubtotal = habersubtotal*1 + this.value*1;
        if(this.value*1!=0){
            $(this).removeClass("movimientoSinValor");
            $(this).addClass("movimientoConValor");
        }else{
            $(this).removeClass("movimientoConValor")
            $(this).addClass("movimientoSinValor");
        }
    });
    $("#lblTotalHaber").text(parseFloat(habersubtotal).toFixed(2)) ;
    var habersubtotalAdd = 0;
    $(".inputHaberAdd").each(function () {
        habersubtotalAdd = habersubtotalAdd*1 + this.value*1;
        if(this.value*1!=0){
            $(this).removeClass("movimientoSinValor");
            $(this).addClass("movimientoConValor");
        }else{
            $(this).removeClass("movimientoConValor")
            $(this).addClass("movimientoSinValor");
        }
    });
    $("#lblTotalHaberAdd").text(parseFloat(habersubtotalAdd).toFixed(2)) ;
    showIconDebeHaberAdd()
}
function showIconDebeHaberAdd(){
    //Este es del edit
    if($("#lblTotalHaber").text()==$("#lblTotalDebe").text()){
        $("#iconDebeHaber").attr('src',serverLayoutURL+'/img/test-pass-icon.png');
    }else{
        $("#iconDebeHaber").attr('src',serverLayoutURL+'/img/test-fail-icon.png');
    }
    //este es del add
    if($("#lblTotalHaberAdd").text()==$("#lblTotalDebeAdd").text()){
        $("#iconDebeHaberAdd").attr('src',serverLayoutURL+'/img/test-pass-icon.png');
    }else{
        $("#iconDebeHaberAdd").attr('src',serverLayoutURL+'/img/test-fail-icon.png');
    }
}