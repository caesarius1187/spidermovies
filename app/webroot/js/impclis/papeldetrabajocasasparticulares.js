/**
 * Created by caesarius on 04/04/2017.
 */
var numeroliquidacion = [];
var ajaxAbierto = false;
var empleado1=0;
$(document).ready(function() {
    numeroliquidacion["liquidaprimeraquincena"] = 1;
    numeroliquidacion["liquidasegundaquincena"] = 2;
    numeroliquidacion["liquidamensual"] = 3;
    numeroliquidacion["liquidapresupuestoprimera"] = 4;
    numeroliquidacion["liquidapresupuestosegunda"] = 5;
    numeroliquidacion["liquidapresupuestomensual"] = 6;
    numeroliquidacion["liquidasac"] = 7;
    jQuery(document).ready(function($) {
        $(document).ajaxStart(function () {
            ajaxAbierto = true;
        });
        $(document).ajaxComplete(function () {
            checkPendingRequest();
        });
        $('#ui-datepicker-div').hide();
        function checkPendingRequest() {
            if ($.active > 0) {
                window.setTimeout(checkPendingRequest, 1000);
                //Mostrar peticiones pendientes ejemplo: $("#control").val("Peticiones pendientes" + $.active);
            }
            else {
                ajaxAbierto = false;
            }
        };
    });
    papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
    catchAsientoCasasParticulares();
    $(".inputDebe").each(function () {
        $(this).change(addTolblTotalDebeAsieto);
    });
    $(".inputHaber").each(function () {
        $(this).change(addTolblTotalhaberAsieto);
    });


});
function cargarTodosLosFormularios102(){
    $("#divSueldoForm").html("");
    empleado1=0;
    var empleados = JSON.parse($("#arrayEmpleados").val());
    var liquidacion = numeroliquidacion[$("#tipoliquidacion").val()];
    var periodo = $('#periodoPDT').val();
    jQuery.each(empleados, function(name, value) {
        value.forEach(function (valor, indice, array) {
            cargarUnFormulario102(valor,periodo,liquidacion,indice);
        });
    });
}
function catchAsientoCasasParticulares(){
    $('#AsientoAddForm').submit(function(){
        /*Vamos a advertir que estamos reemplazando un asiento ya guardado*/
        var asientoyaguardado=false;
        if($("#AsientoAddForm #Asiento0Id").val()*1!=0){
            asientoyaguardado=true;
        }
        var r=true;
        if(asientoyaguardado){
            r = confirm("Este asiento sobreescribira al previamente guardado, reemplazando los valores por los calculados" +
                " en este momento. Para ver el asiento previamente guardado CANCELE, luego ingrese en el Informe de " +
                " Sumas y saldos y despues en Asientos");
        }
        if (r == true) {
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
                    var resp = respuesta.respuesta;
                    callAlertPopint(resp);
                },
                error: function(xhr,textStatus,error){
                    callAlertPopint(textStatus);
                    return false;
                }
            });
        }else{
            callAlertPopint("El asiento no se ha sobreescrito.");
        }

        return false;
    });
}

function papelesDeTrabajo(periodo,impcli){
    var data = "";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/eventosimpuestos/getpapelestrabajo/"+periodo+"/"+impcli, // URL to request
        data: data,  // post data
        success: function(response) {
            //alert(response);
            $('#divLiquidarCasasParticulares').html(response);
            //esconder elementos que no necesitamos en este momento
            $('#tabsTareaImpuesto').hide();
            $('#divPagar').hide();
            $('#buttonPDT').hide();
            $('.btn_cancelar').hide();

            $(document).ready(function() {
                $( "input.datepicker" ).datepicker({
                    yearRange: "-100:+50",
                    changeMonth: true,
                    changeYear: true,
                    constrainInput: false,
                    dateFormat: 'dd-mm-yy',
                });
            });
            $( "#vencimientogeneral" ).change(function(){
                $('#EventosimpuestoRealizartarea5Form .hiddendatepicker').val( $( "#vencimientogeneral" ).val());
            });
            $( "#vencimientogeneral" ).trigger( "change" );
            $('#EventosimpuestoRealizartarea5Form').submit(function(){
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
                        var resp = respuesta.respuesta;
                        var error=respuesta.error;
                        if(error!=0){
                            alert(respuesta.validationErrors);
                            alert(respuesta.invalidFields);
                        }else{
                            $('#AsientoAddForm').submit();
                            $('#divLiquidarCasasParticulares').hide();
                        }
                    },
                    error: function(xhr,textStatus,error){
                        callAlertPopint(textStatus);
                        return false;
                    }
                });
                return false;
            });
            //aca vamos a mover el div de asientos al de eventos impuesto
            $('#divContenedorContabilidad').detach().appendTo('#divAsientoDeEventoImpuesto');
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);

        }
    });
    return false;
}
function getnumberfromString(strnumber){
    strnumber = strnumber.replace('$','');
    strnumber = strnumber.replace('.','');
    strnumber = strnumber.replace('-','');
    strnumber = strnumber.replace(',','.');
    return strnumber*1;
}
function cargarUnFormulario102(empid,periodo,liquidacion,indice){
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/empleados/formulario102/"+empid+"/"+periodo+"/"+liquidacion, // URL to request
        data: data,  // post data
        success: function(response) {
            $("#divSueldoForm").append(response);
            $("#volantepago"+empid+" .checkboxrubroI").change(function(){
                var obligacionmensual = summAllFromClass("checkboxrubroI",'checkbox');
                $("#obligacionmensual"+empid).html($(this).val())*1;
                $("#Eventosimpuesto0Montovto").val(obligacionmensual);
                //Cargar Asiento
                //Casa Particular < Tope
                // 599002004	Deduccion Ley 26063 Servicio Domestico
                if($('#cuenta2776').length > 0){
                    var orden = $('#cuenta2776').attr('orden');
                    var sueldoBasico = summAllFromClass("sueldoBasico");
                    var SAC = summAllFromClass("SAC");
                    var vacaciones = summAllFromClass("vacaciones");
                    var adicional = summAllFromClass("adicional");
                    var vacacionesnogozadas = summAllFromClass("vacacionesnogozadas");
                    var licanciamatrimonio = summAllFromClass("licanciamatrimonio");
                    var totalRemunerativos = sueldoBasico+SAC+vacaciones+adicional+vacacionesnogozadas+licanciamatrimonio;
                    $('#Asiento0Movimiento'+orden+'Debe').val(totalRemunerativos);
                }
                // 514000017	Contribuciones SS Ley 26063 Casas Particulares
                if($('#cuenta2789').length > 0){
                    var orden = $('#cuenta2789').attr('orden');
                    var aportes = summAllFromClass("aportes");
                    $('#Asiento0Movimiento'+orden+'Debe').val(obligacionmensual-aportes);
                }
                // 230101010	Ley 260663 Aportes Servicios Domesticos a Pagar
                if($('#cuenta3381').length > 0){
                    var orden = $('#cuenta3381').attr('orden');
                    var sueldoTotal = summAllFromClass("sueldoTotal");
                    $('#Asiento0Movimiento'+orden+'Haber').val(sueldoTotal);
                }
                // 230102010	Ley 26063 Servicio Domestico a Pagar
                if($('#cuenta3383').length > 0){
                    var orden = $('#cuenta3383').attr('orden');
                    var aportes = summAllFromClass("aportes");
                    $('#Asiento0Movimiento'+orden+'Haber').val(aportes);
                }
                // 230102011	Ley 260663 Contribuciones Servicios Domesticos a Pagar
                if($('#cuenta3384').length > 0){
                    var orden = $('#cuenta3384').attr('orden');
                    var aportes = summAllFromClass("aportes");
                    $('#Asiento0Movimiento'+orden+'Haber').val(obligacionmensual-aportes);
                }
                $(".inputHaber").each(function(){
                    $(this).trigger('change');
                    return;
                });
                $(".inputDebe").each(function(){
                    $(this).trigger('change');
                    return;
                });
            });
            $(".cantHoras").change(function(){
                $(this).attr(
                    'value',
                    $(this).val()
                );
            });
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
            return false;
        }
    });
    return false;
}
function openWin()
{
    $('.btn_imprimir').each(function (index) {
        $(this).hide();
    });
    $('.hideOnPrint').each(function (index) {
        $(this).hide();
    });
    var myWindow=window.open('','','width=1010,height=1000px');
    myWindow.document.write('<html><head><title>Recibo de sueldo</title><link rel="stylesheet" type="text/css" href="'+serverLayoutURL+'/css/cake.generic.css"></head><body>');
    myWindow.document.write($("#divToPrintRecibo").html());
    myWindow.document.close();
    myWindow.focus();
    setTimeout(
        function()
        {
            myWindow.print();
            myWindow.close();
            $('.hideOnPrint').each(function (index) {
                $(this).show();
            });
            $('.btn_imprimir').each(function (index) {
                if(index==0){
                    $(this).show();
                }else{
                    $(this).hide();
                }
            });
        }, 1000);
}
function openWinForm102()
{
    $('.btn_imprimir').each(function (index) {
        $(this).hide();
    });
    var myWindow=window.open('','','width=1010,height=1000px');
    myWindow.document.write('<html><head><title>Formulario 102</title><link rel="stylesheet" type="text/css" href="'+serverLayoutURL+'/css/cake.generic.css"></head><body>');
    myWindow.document.write($("#divSueldoForm").html());
    myWindow.document.close();
    myWindow.focus();
    setTimeout(
        function()
        {
            $('.btn_imprimir').each(function (index) {
                $(this).show();
            });
            myWindow.print();
            myWindow.close();
            $("#sueldoContent #tomo").show();
            $("#sueldoContent #hoja").show();
        }, 1000);
}
function openWinLibrosSueldos()
{
    $('.btn_imprimir').each(function (index) {
        $(this).hide();
    });
    $('.hideOnPrint').each(function (index) {
        $(this).hide();
    });
    var myWindow=window.open('','','width=1010,height=1000px');
    myWindow.document.write('<html><head><title>Libro de sueldo</title><link rel="stylesheet" type="text/css" href="'+serverLayoutURL+'/css/cake.generic.css"></head><body>');
    myWindow.document.write($("#sheetCooperadoraAsistencial").html());
    myWindow.document.close();
    myWindow.focus();
    setTimeout(
        function()
        {
            myWindow.print();
            myWindow.close();
            $('.btn_imprimir').each(function (index) {
                if(index==0){
                    $(this).show();
                }else{
                    $(this).hide();
                }
            });
            $('.hideOnPrint').each(function (index) {
                $(this).show();
            });
        }, 1000);
}
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
function summAllFromClass(classtosum,atrrvalor){
    var sum = 0;
    $('.'+classtosum).each(function(){
        if(atrrvalor=='checkbox'){
            if($(this).is(':checked')) {
                sum += $(this).val()*1;  // Or this.innerHTML, this.innerText
            }
        }else{
            sum += getnumberfromString($(this).html());  // Or this.innerHTML, this.innerText
        }
    });
    return sum;
}
function addTolblTotalDebeAsieto(event) {
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
    showIconDebeHaber()
}
function addTolblTotalhaberAsieto(event) {
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
    showIconDebeHaber()
}
function showIconDebeHaber(){
    if($("#lblTotalHaber").text()==$("#lblTotalDebe").text()){
        $("#iconDebeHaber").attr('src',serverLayoutURL+'/img/test-pass-icon.png');
    }else{
        $("#iconDebeHaber").attr('src',serverLayoutURL+'/img/test-fail-icon.png');
    }
}