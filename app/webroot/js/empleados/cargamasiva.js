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
    $("#buscarempleado").keyup(function( event ){
        $(".parafiltrarempleados").each(function () {
           var valorparafiltrar =  $(this).attr('valorparafiltrar');
            var contienefiltro =  valorparafiltrar.toLowerCase().indexOf($("#buscarempleado").val().toLowerCase());
            if(valorparafiltrar!=""&&contienefiltro==-1){
                $(this).hide();
            }else{
                $(this).show();
            }
        });
    });
});
function cargarTodosLosSueldos(convenio){
    var liquidacion = $("#tipoliquidacion").val();
    $("#divSueldoForm").html("");
    $("#divSueldoForm").css('width','5000px');

    empleado1=0;
    var empleados = JSON.parse($("#arrayEmpleados").val());

    var  deferredCollection = [];
    jQuery.each(empleados, function(name, value) {
        if(name==convenio){
            value.forEach(function (valor, indice, array) {
                var cliid = $('#cliid').val();
                var periodo = $('#periodo').val();
                deferredCollection.push(cargarSueldoEmpleado(cliid,periodo,valor,liquidacion,indice));
                if(empleado1==0){
                    empleado1=valor;
                }
            });
        }
    });
}
function guardarTodosLosSueldos(){
    var liquidacion = $("#tipoliquidacion").val();
    // $("#divSueldoForm").html("");
    var empleados = JSON.parse($("#arrayEmpleados").val());

    var  deferredCollection = [];
    jQuery.each(empleados, function(name, value) {
        value.forEach(function (valor, indice, array) {
            if($('#ValorreciboPapeldetrabajosueldosForm'+valor).length>0){
                deferredCollection.push($('#ValorreciboPapeldetrabajosueldosForm'+valor).submit());
            }
        });
    });
}
function showHideEmpleadoOnClick(){
    $(".snapempleado").click(function(){
        var empid = $(this).attr('data-identificacion');
        showHideColumnsEmpleado(empid);
    });
}
function showHideColumnsEmpleado(empid){
    if( $("#indiceCargaEmpleado"+empid).val()!=0){
        $('#ValorreciboPapeldetrabajosueldosForm'+empid+' .tdconcepto').each(function(){
            if($(this).is(":visible")){
                $(this).hide();
            }else{
                $(this).show();
            }
        });
        $('#ValorreciboPapeldetrabajosueldosForm'+empid+' .tdseccion').each(function(){
            if($(this).is(":visible")){
                $(this).hide();
            }else{
                $(this).show();
            }
        });
    }

    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' .tdalicuota').each(function(){
        if($(this).is(":visible")){
            $(this).hide();
        }else{
            $(this).show();
        }
    });
    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' .tdformula').each(function(){
        if($(this).is(":visible")){
            $(this).hide();
        }else{
            $(this).show();
        }
    });
    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' .tdcodigo').each(function(){
        if($(this).is(":visible")){
            $(this).hide();
        }else{
            $(this).show();
        }
    });
    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' .tdcodigoalicuota').each(function(){
        if($(this).is(":visible")){
            $(this).hide();
        }else{
            $(this).show();
        }
    });
}
function cargarunsueldoempleado(clienteid,periodo,empid,liquidacion,indice){
    $("#divSueldoForm").html("");
    $("#divSueldoForm").css('width','auto');
    empleado1=empid;
    cargarSueldoEmpleado(clienteid,periodo,empid,liquidacion,indice);
}
function cargarSueldoEmpleado(clienteid,periodo,empid,liquidacion,indice){
    var liquidacion = $("#tipoliquidacion").val();
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/empleados/papeldetrabajosueldos/"+clienteid+"/"+periodo+"/"+empid+"/"+liquidacion, // URL to request
        data: data,  // post data
        success: function(response) {
            var empleados = JSON.parse($("#arrayEmpleados").val());

            $(".btn_empleados_liq ").each(function(){
                $(this).removeClass("btn_empleados_selected");
            });
            $(".btn_empleados").each(function(){
                $(this).removeClass("btn_empleados_selected");
            });

            $("#buttonEmpleado"+empid).addClass("btn_empleados_selected");

            if(indice==0){
                $("#divSueldoForm").prepend(
                    $("<div>")
                        .append(response)
                        .addClass('divsueldomasivo')
                ).prepend(
                    $('<div />',{
                        'style':"width:100%",
                    }).append(
                        $('<label />',{
                            'text':"Mostrar Todo",
                            'for':"mostrarcamposencero",
                            'style':"display:inline",
                        })
                    ) .append(
                        $('<input />',{
                            'type':'checkbox',
                            'id':"mostrarcamposencero",
                            'style':"display:inline",
                            'onclick':"ocultarFunciones()",
                        })
                    )
                );
                $('#ValorreciboPapeldetrabajosueldosForm'+empid).find('.aplicableATodos').each(function(){
                    //$(this).css('background','blue');
                    var myselect = $(this).attr('id');
                    var span = $('<span />')
                        .attr('class', 'tooltiptext')
                        .html(
                            $('<input />',{
                                'type':'button',
                                'value':"Aplicar a todos",
                                'onclick':"aplicarATodos('"+empid+"','"+myselect+"')"
                            })
                        );
                    $(this).closest('div')
                        .addClass( "tooltip" )
                        .append(
                            span
                        );
                });
            }else if(indice==empleados.length-1) {
                $("#divSueldoForm").append(
                    $("<div>")
                        .append(response)
                        .addClass('divsueldomasivo')
                );
                // }else if(indice==empleados.length-1){
            }else{
                if($( ".divsueldomasivo").first().length>0){
                    $("<div>")
                        .append(response)
                        .addClass('divsueldomasivo')
                        .insertAfter($( ".divsueldomasivo").first())
                }else{
                    $("#divSueldoForm").append(
                        $("<div>")
                            .append(response)
                            .addClass('divsueldomasivo')
                    );
                }

            }
            $("#indiceCargaEmpleado"+empid).val(indice);

            showHideColumnsEmpleado(empid)
            activarCalXOnSueldos(empid);
            showHideEmpleadoOnClick()
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
            return false;
        }
    });
    return false;
}
function aplicarATodos(empid,miinput){
    $("#loading").css('visibility','visible')
    var valueAAplicar = $('#ValorreciboPapeldetrabajosueldosForm'+empid+' #'+miinput).val();
    var inputclass = $('#ValorreciboPapeldetrabajosueldosForm'+empid+' #'+miinput).attr('inputclass');
    $('input[inputclass="'+inputclass+'"]').each(function() {
        $(this).val(valueAAplicar).trigger('change');
        $(this).closest('form').calx({ });
    });
    $("#loading").css('visibility','hidden')

}
var tablaSueldoCalx=[];
function ocultarFunciones(){
    var empleados = JSON.parse($("#arrayEmpleados").val());
    var  deferredCollection = [];
    jQuery.each(empleados, function(name, value) {
        value.forEach(function (valor, indice, array) {
            if(indice==0)
                deferredCollection.push(ocultarFuncinesDeUnFormulario(valor));
        });
    });

}

function ocultarFuncinesDeUnFormulario(empid){
    if(ajaxAbierto){
       return false;
    }

    console.log("inicio de Ocultar: "+empid);
    var empidFuncion = 0;
    $("#ValorreciboPapeldetrabajosueldosForm"+empid+" .funcionAAplicar").each(function() {
        empidFuncion++;
        var posicion = $(this).attr('posicion');
        var seccion = $(this).attr('seccion');
        var headsection = $(this).attr('headseccion');
        var dataCodigo = $(this).attr('data-codigo');

        //aca tengo que preguntar si todos los valoresrecibos tienen valor 0 sino no oculto
        var todosvacios = true;
        $('.'+dataCodigo).each(function() {
            $mysheet = $(this).closest('form').calx("getSheet");
            if(dataCodigo=="R33"){
                // console.log(empid+"-"+empidFuncion+"-"+dataCodigo+"= "+$mysheet.getCellValue("R33"));
            }
            if(dataCodigo=="R10"){
                // console.log(empid+"-"+empidFuncion+"-"+dataCodigo+"= "+$mysheet.getCellValue("R10"));
            }
            if(dataCodigo.charAt(0)=='O') {
                 // $mysheet.getCell(dataCodigo).calculate();
                var myCell = $mysheet.getCell(dataCodigo);
                console.log(empid+"-"+empidFuncion+"-"+dataCodigo+ " = '" + myCell.getFormat() + "'");
                console.log(empid+"-"+empidFuncion+"-"+dataCodigo+ " = '" + myCell.getFormattedValue() + "'");
                console.log(empid+"-"+empidFuncion+"-"+dataCodigo+ " = '" + myCell.getFormula() + "'");
                console.log(empid+"-"+empidFuncion+"-"+dataCodigo+ " = '" + myCell.evaluateFormula() + "'");
                console.log(empid+"-"+empidFuncion+"-"+dataCodigo+ " = '" + myCell.getValue() + "'");
                console.log(empid+"-"+empidFuncion+"-"+dataCodigo+ " = '" + myCell.renderComputedValue() + "'");
            }

            if(($mysheet.getCellValue(dataCodigo)*1)!=0){
                todosvacios = false;
                // console.log(empid+"-Este es distinto de 0 != "+$mysheet.getCellValue(dataCodigo));
                return false;
            }
        });
        //por alguna razon los que son "O"(otros) no se estan autocalculando (beto a saber por que)
        //asi que aca vamos a forzar un recalculo

        if(todosvacios){
            //tengo que ocultar el row solo si no es cabeza de seccion por que sino me oculta toda la seccion
            if(headsection=="0"){
                if($("#mostrarcamposencero").is(':checked')){
                    showRow(empid,dataCodigo,seccion);
                }else{
                    hideRow(empid,dataCodigo,seccion);
                }
            }
        }else{
            showRow(empid,dataCodigo,seccion)
        }
    });
}
function hideRow(empid,dataCodigo,seccion){
    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' input[valdata-codigo="'+dataCodigo+'"]').each(function() {
        //si esta visible tengo que mermar en 1 el rowspan
        var rowspan = 0;
        var rowVisible =  $(this).closest('tr').is(':visible');
        if(rowVisible==true) {
            rowspan = $('#ValorreciboPapeldetrabajosueldosForm'+empid+' #seccion'+seccion).attr('rowspan');
            rowspan = rowspan - 1;
            // $('#ValorreciboPapeldetrabajosueldosForm'+empid+' #seccion'+seccion).attr('rowspan',rowspan);
            $('.seccion'+seccion).each(function(){
                $(this).attr('rowspan',rowspan);
            });
        }
        $('input[valdata-codigo="'+dataCodigo+'"]').each(function() {
            $(this).closest('tr').hide();
        });
    });
}
function showRow(empid,dataCodigo,seccion){
    //si esta oculto tengo que aumentar en 1 el rowspan
    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' input[valdata-codigo="'+dataCodigo+'"]').each(function() {
        //si esta visible tengo que mermar en 1 el rowspan
        var rowspan = 0;
        var rowVisible = $(this).closest('tr').is(':visible');
        if(rowVisible==false ) {
            rowspan = $('#ValorreciboPapeldetrabajosueldosForm'+empid+' #seccion' + seccion).attr('rowspan')*1;
            rowspan = rowspan + 1;
            // $('#ValorreciboPapeldetrabajosueldosForm'+empid+' #seccion'+seccion).attr('rowspan',rowspan);
            $('.seccion'+seccion).each(function(){
                $(this).attr('rowspan',rowspan);
            });
        }
        //tengo que mostrar el row
        $('input[valdata-codigo="'+dataCodigo+'"]').each(function() {
            $(this).closest('tr').show();
        });
    });
}
function activarCalXOnSueldos(empid){

    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' .funcionAAplicar').on('change', function() {
        var posicion = $(this).attr('posicion');
        $('#ValorreciboPapeldetrabajosueldosForm'+empid).calx(
            'getCell',
            $('#ValorreciboPapeldetrabajosueldosForm'+empid+' #Valorrecibo'+posicion+'Valor').attr('data-cell')
        ).setFormula($(this).val());
        tablaSueldoCalx[empid].calx('calculate');
        $('#Valorrecibo'+posicion+'Formulamodificada').prop('checked', true);
        $('#Valorrecibo'+posicion+'Nuevaformula').val( $('#Valorrecibo'+posicion+'Formula').val());
    });

    tablaSueldoCalx[empid] = $('#ValorreciboPapeldetrabajosueldosForm'+empid).calx({
        language : 'id',
        'autoCalculate'         : true,
        onAfterRender : function(){
            ocultarFuncinesDeUnFormulario(empid);
        } ,
        onBeforeRender : function(){
        }
    });
    tablaSueldoCalx[empid].submit(function(){
        //serialize form data
        var formData = $(this).serialize();
        //get form action
        var formUrl = $(this).attr('action');
        $.ajax({
            type: 'POST',
            url: formUrl,
            data: formData,
            success: function(data,textStatus,xhr){
                callAlertPopint("Sueldo guardado, los totales se han recalculado.");
                var empleados = JSON.parse($("#arrayEmpleados").val());
                var indice = $("#indiceCargaEmpleado"+empid).val();
                //de este formulario borrar el div mas cercano con clase divsueldomasivo
                $('#ValorreciboPapeldetrabajosueldosForm'+empid).closest('div.divsueldomasivo').remove();

                $("#buttonEmpleado"+empid).addClass("btn_empleados_liq");
                if(indice==0){
                    $("#divSueldoForm").prepend(
                        $("<div>")
                            .append(data)
                            .addClass('divsueldomasivo')
                    );
                    $('#ValorreciboPapeldetrabajosueldosForm'+empid).find('.aplicableATodos').each(function(){
                        //$(this).css('background','blue');
                        var myselect = $(this).attr('id');
                        var span = $('<span />')
                            .attr('class', 'tooltiptext')
                            .html(
                                $('<input />',{
                                    'type':'button',
                                    'value':"Aplicar a todos",
                                    'onclick':"aplicarATodos('"+empid+"','"+myselect+"')"
                                })
                            );
                        $(this).closest('div')
                            .addClass( "tooltip" )
                            .append(
                                span
                            );
                    });
                }else if(indice==empleados.length-1) {
                    $("#divSueldoForm").append(
                        $("<div>")
                            .append(data)
                            .addClass('divsueldomasivo')
                    );
                    // }else if(indice==empleados.length-1){
                }else{
                    if($( ".divsueldomasivo").first().length>0){
                        $("<div>")
                            .append(data)
                            .addClass('divsueldomasivo')
                            .insertAfter($( ".divsueldomasivo").first())
                    }else{
                        $("#divSueldoForm").append(
                            $("<div>")
                                .append(response)
                                .addClass('divsueldomasivo')
                        );
                    }

                }
                $("#indiceCargaEmpleado"+empid).val(indice);

                showHideColumnsEmpleado(empid)
                activarCalXOnSueldos(empid);
                showHideEmpleadoOnClick()
            },
            error: function(xhr,textStatus,error){
                callAlertPopint(textStatus);
            }
        });
        return false;
    });


    $('#ValorreciboPapeldetrabajosueldosForm'+empid+' input').change(function(){
       // $('#ValorreciboPapeldetrabajosueldosForm'+empid).calx({ });
       //      ocultarFuncinesDeUnFormulario(empleado1);
    });
}

function cargarTodosLosRecibos(){

    $("#divSueldoForm").html("");
    $("#divSueldoForm").css('width','auto');
    empleado1=0;
    var empleados = JSON.parse($("#arrayEmpleados").val());
    var liquidacion = numeroliquidacion[$("#tipoliquidacion").val()];
    var periodo = $('#periodo').val();
    jQuery.each(empleados, function(name, value) {
        value.forEach(function (valor, indice, array) {
            cargarUnReciboSueldo(valor,periodo,liquidacion);
        });
    });
}
function cargarUnReciboSueldo(empid,periodo,liquidacion){
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/empleados/papeldetrabajorecibosueldo/"+empid+"/"+periodo+"/"+liquidacion, // URL to request
        data: data,  // post data
        success: function(response) {
            $("#divSueldoForm").append(
                response
            );

            $("#reciboDuplicado"+empid).html($("#reciboOriginal"+empid).html());
            $("#reciboDuplicado"+empid+" #firmaempleador").html("<b>Firma empleador</b>");

            $("#reciboOriginal"+empid+" #bancos").change(function () {
                $("#reciboOriginal"+empid+" #pbanco").html($("#reciboOriginal"+empid+" #bancos option:selected").text());
                $("#reciboDuplicado"+empid+" #pbanco").html($("#reciboOriginal"+empid+" #bancos option:selected").text());
            });
            $("#reciboOriginal"+empid+" #bancoempleados").change(function () {
                $("#reciboOriginal"+empid+" #pbancoempleado").html($("#reciboOriginal"+empid+" #bancoempleados option:selected").text());
                $("#reciboDuplicado"+empid+" #pbancoempleado").html($("#reciboOriginal"+empid+" #bancoempleados option:selected").text());
            });
            $("#reciboDuplicado"+empid+" #bancos").remove();
            $("#reciboDuplicado"+empid+" #bancoempleados").remove();

            $("#reciboOriginal"+empid+" #bancos").trigger('change');
            $("#reciboOriginal"+empid+" #bancoempleados").trigger('change');


            $('.btn_imprimir').each(function (index) {
                if(index==0){
                    $(this).attr('onClick','openWinRecibosSueldos()');
                }else{
                    $(this).hide();
                }
            });

        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
            return false;
        }
    });
    return false;
}

function cargarTodosLosLibros(){
    $("#divSueldoForm").html("");
    $("#divSueldoForm").css('width','auto');

    empleado1=0;
    var empleados = JSON.parse($("#arrayEmpleados").val());
    var liquidacion = numeroliquidacion[$("#tipoliquidacion").val()];
    var periodo = $('#periodo').val();
    jQuery.each(empleados, function(name, value) {
        value.forEach(function (valor, indice, array) {
            cargarUnLibroSueldo(valor,periodo,liquidacion,indice);
        });
    });
}
function cargarUnLibroSueldo(empid,periodo,liquidacion,indice){
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/empleados/papeldetrabajolibrosueldo/"+empid+"/"+periodo+"/"+liquidacion, // URL to request
        data: data,  // post data
        success: function(response) {
            $("#divSueldoForm").append(response);
            $("#libroSueldoContent"+empid+" #hoja").val(indice+1);

            $("#libroSueldoContent"+empid+" #hoja").change(function () {
                $("#libroSueldoContent"+empid+" label[for='hoja']").html("Hoja: "+$(this).val());
            });
            $("#libroSueldoContent"+empid+" #tomo").change(function () {
                $("#libroSueldoContent"+empid+" label[for='tomo']").html("Padron: "+$(this).val());
            });
            $("#libroSueldoContent"+empid+" #tomo").trigger('change');
            $("#libroSueldoContent"+empid+" #hoja").trigger('change');
            $('.btn_imprimir').each(function (index) {
                if(index==0){
                    $(this).attr('onClick','openWinRecibosSueldos()');
                }else{
                    $(this).hide();
                }
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
function openWinRecibosSueldos()
{
    $('.btn_imprimir').each(function (index) {
        $(this).hide();
    });
    $('.hideOnPrint').each(function (index) {
        $(this).hide();
    });
    var myWindow=window.open('','','width=1010,height=1000px');
    myWindow.document.write('<html><head><title>Recibos de sueldos</title><link rel="stylesheet" type="text/css" href="'+serverLayoutURL+'/css/cake.generic.css"></head><body>');
    myWindow.document.write($("#divSueldoForm").html());
    myWindow.document.close();
    myWindow.focus();
    setTimeout(
        function()
        {
            myWindow.print();
            myWindow.close();
            // $("#divSueldoForm").css('float','left');
            // $(".index").css('float','left');
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
function openWinLibroSueldo()
{
    $("#sueldoContent #tomo").hide();
    $("#sueldoContent #hoja").hide();
    var myWindow=window.open('','','width=1010,height=1000px');
    myWindow.document.write('<html><head><title>Libro de sueldo</title><link rel="stylesheet" type="text/css" href="'+serverLayoutURL+'/css/cake.generic.css"></head><body>');
    myWindow.document.write($("#divLibroSueldo").html());
    myWindow.document.close();
    myWindow.focus();
    setTimeout(
        function()
        {
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
function realizarEventoCliente(periodo,clienteid,estadotarea){
    var datas =  "0/tarea3/"+periodo+"/"+clienteid;
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/eventosclientes/realizareventocliente/0/novedadescargadas/"+periodo+"/"+clienteid+"/"+estadotarea, // URL to request
        data: data,  // post data
        success: function(response) {
            var resp = response.split("&&");
            var respuesta=resp[1];
            var error=resp[0];
            if(error!=0){
                callAlertPopint('Error por favor intente mas tarde');
                return;
            }else{
                callAlertPopint('Estado de la tarea modificado');
            }
            return false;
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
            return false;
        }
    });
    return false;
}

