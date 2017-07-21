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
                var obligacionmensual = $(this).val();
                $("#obligacionmensual"+empid).html(obligacionmensual)*1;
                $("#Eventosimpuesto0Montovto").val(obligacionmensual);
                //Cargar Asiento
                //Casa Particular < Tope
                // 599002004	Deduccion Ley 26063 Servicio Domestico
                if($('#cuenta2776').length > 0){
                    var orden = $('#cuenta2776').attr('orden');
                    var sueldoTotal = getnumberfromString($("#sueldoTotal").html());
                    $('#Asiento0Movimiento'+orden+'Debe').val(sueldoTotal);
                }
                // 514000017	Contribuciones SS Ley 26063 Casas Particulares
                if($('#cuenta2789').length > 0){
                    var orden = $('#cuenta2789').attr('orden');
                    $('#Asiento0Movimiento'+orden+'Debe').val(obligacionmensual);
                }
                // 230102010	Ley 260663 Aportes Servicios Domesticos a Pagar
                if($('#cuenta3383').length > 0){
                    var orden = $('#cuenta3383').attr('orden');
                    var aportes = getnumberfromString($("#aportes").html());
                    $('#Asiento0Movimiento'+orden+'Haber').val(aportes);
                }
                // 230102011	Ley 260663 Contribuciones Servicios Domesticos a Pagar
                if($('#cuenta3384').length > 0){
                    var orden = $('#cuenta3384').attr('orden');
                    var aportes = getnumberfromString($("#aportes").html());
                    $('#Asiento0Movimiento'+orden+'Haber').val(obligacionmensual-aportes);
                }
                // 230101010	Ley 26063 Servicio Domestico a Pagar
                if($('#cuenta3381').length > 0){
                    var orden = $('#cuenta3381').attr('orden');
                    var sueldoTotal = getnumberfromString($("#sueldoTotal").html());
                    $('#Asiento0Movimiento'+orden+'Haber').val(sueldoTotal);
                }
            })
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

