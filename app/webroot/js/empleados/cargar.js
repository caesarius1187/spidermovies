var tblTablaVentas;
var tblTablaCompras;
$(document).ready(function() {
    var beforePrint = function() {
        $('#header').hide();
        $('#Formhead').hide();
        $('#headerCliente').hide();
        $('#divAllTabs').hide();
        $('#formOldSueldo').hide();
        $('#form_empleados').hide();
        $('.btn_empleados').hide();
        $('.btn_empleados_liq').hide();
        $('.btn_cargarliq_selected').hide();
        $('.btn_cargarliq_liq').hide();
        $('.btn_cargarliq').hide();
        $('.btn_sueldo').hide();
        $('.btn_imprimir').hide();
        $('#TituloPdtSueldo').hide();
        $('#index').css('border-color','#FFF');
    };
    var afterPrint = function() {
        //$('#index').css('font-size','10px');
        $('#header').show();
        $('#Formhead').show();
        $('#form_empleados').show();
        $('#headerCliente').show();
        $('#divAllTabs').show();
        $('#formOldSueldo').show();
        $('.btn_empleados').show();
        $('.btn_empleados_liq').show();
        $('.btn_cargarliq_selected').show();
        $('.btn_cargarliq_liq').show();
        $('.btn_cargarliq').show();
        $('.btn_sueldo').show();
        $('.btn_imprimir').show();
        $('#divLiquidarActividadesVariar').show();
        $('#TituloPdtSueldo').show();
    };
    if (window.matchMedia) {
        var mediaQueryList = window.matchMedia('print');
        mediaQueryList.addListener(function(mql) {
            if (mql.matches) {
                beforePrint();
            } else {
                afterPrint();
            }
        });
    }
    window.onbeforeprint = beforePrint;
    window.onafterprint = afterPrint;

    var nombrecliente = $('#clientenombre').val();
    var periodo = $('#periododefault').val();


    $('.chosen-select').chosen({search_contains:true});
});
    function setTwoNumberDecimal(event) {
        this.value = parseFloat(this.value).toFixed(2);
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
/*Update Sueldos*/
    function cargarSueldoEmpleado(clienteid,periodo,empid,liquidacion){
        var data ="";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/empleados/papeldetrabajosueldos/"+clienteid+"/"+periodo+"/"+empid+"/"+liquidacion, // URL to request
            data: data,  // post data
            success: function(response) {
                $(".btn_empleados_liq ").each(function(){
                    $(this).removeClass("btn_empleados_selected");
                });
                $(".btn_empleados").each(function(){
                    $(this).removeClass("btn_empleados_selected");
                });

                $("#buttonEmpleado"+empid).addClass("btn_empleados_selected");
                $("#divSueldoForm").html(response);
                activarCalXOnSueldos();
                var pdtsueldo = $('#pdtsueldo');
                pdtsueldo.floatThead();
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
                return false;
            }
        });
        return false;
    }
    function cargarLibroSueldo(empid,periodo,liquidacion){
        var data ="";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/empleados/papeldetrabajolibrosueldo/"+empid+"/"+periodo+"/"+liquidacion, // URL to request
            data: data,  // post data
            success: function(response) {
                $("#sueldoContent").html(response);
                $("#sueldoContent #hoja").change(function () {
                    $("label[for='hoja']").html("Hoja: "+$(this).val());
                });
                $("#sueldoContent #tomo").change(function () {
                    $("label[for='tomo']").html("Padron: "+$(this).val());
                });
                $("#sueldoContent #tomo").trigger('change');
                $("#sueldoContent #hoja").trigger('change');
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
                return false;
            }
        });
        return false;
    }
    function cargarTodosLosRecibos(liquidacion){
        $("#divSueldoForm").html("");
        var empleados = JSON.parse($("#arrayEmpleados").val());

        var  deferredCollection = [];
        empleados.forEach(function (valor, indice, array) {
            var periodo = $('#periodo').val();
            deferredCollection.push(cargarUnReciboSueldo(valor,periodo,liquidacion));
        });
        $.when.apply( $, deferredCollection ).done(function(atxArgs, greenlingArgs, momandpopsArgs){
            //alert("termine todo?");
        });
    }
    function cargarTodosLosLibros(liquidacion){
        $("#divSueldoForm").html("");
        var empleados = JSON.parse($("#arrayEmpleados").val());

        empleados.forEach(function (valor, indice, array) {
            var periodo = $('#periodo').val();
            cargarUnLibroSueldo(valor,periodo,liquidacion,indice);
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
    function cargarReciboSueldo(empid,periodo,liquidacion){
        var data ="";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/empleados/papeldetrabajorecibosueldo/"+empid+"/"+periodo+"/"+liquidacion, // URL to request
            data: data,  // post data
            success: function(response) {
                $("#sueldoContent").html(response);
                var recibo = $("#reciboOriginal"+empid).html();
                $("#reciboDuplicado"+empid).html(recibo);
                $("#reciboDuplicado"+empid+" #firmaempleador").html("<b>Firma empleador</b>");

                $("#reciboOriginal"+empid+" #bancos").change(function () {
                    $("#reciboOriginal"+empid+" #pbanco").html($("#reciboOriginal"+empid+" #bancos option:selected").text());
                    $("#reciboDuplicado"+empid+" #pbanco").html($("#reciboOriginal"+empid+" #bancos option:selected").text());
                });
                $("#reciboOriginal"+empid+" #bancoempleados").change(function () {
                    $("#reciboOriginal"+empid+" #pbancoempleado").html($("#reciboOriginal"+empid+" #bancoempleados option:selected").text());
                    $("#reciboDuplicado"+empid+" #pbancoempleado").html($("#reciboOriginal"+empid+" #bancoempleados option:selected").text());
                });
                $("#reciboDuplicado"+empid+" #bancos").hide();
                $("#reciboOriginal"+empid+" #bancos").trigger('change');
                $("#reciboDuplicado"+empid+" #bancoempleados").hide();
                $("#reciboOriginal"+empid+" #bancoempleados").trigger('change');
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
                return false;
            }
        });
        return false;
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
/*fin Update Sueldos*/
    function imprimirElemento(elemento){
        PopupPrint($(elem).html());
    }
    var tablaSueldoCalx;
    function ocultarFunciones(){

        $(".funcionAAplicar").each(function() {
            var posicion = $(this).attr('posicion');
            var seccion = $(this).attr('seccion');
            var headsection = $(this).attr('headseccion');

            if($('#Valorrecibo'+posicion+'Valor').val()==0){
                //tengo que ocultar el row solo si no es cabeza de seccion por que sino me oculta toda la seccion
                if(headsection=="0"){

                    //si esta visible tengo que mermar en 1 el rowspan
                    var rowspan = 0;
                    var rowVisible =  $(this).closest('tr').is(':visible');
                    if(rowVisible==true) {
                        rowspan = $('#seccion'+seccion).attr('rowspan');
                        rowspan = rowspan - 1;
                        $('#seccion'+seccion).attr('rowspan',rowspan);
                    }
                    $(this).closest('tr').hide();
                }

            }else{

                //si esta oculto tengo que aumentar en 1 el rowspan
                var rowspan = 0;
                var rowVisible = $(this).is(':visible');
                if(rowVisible==false ) {
                    rowspan = $('#seccion' + seccion).attr('rowspan')*1;
                    rowspan = rowspan + 1;
                    $('#seccion'+seccion).attr('rowspan',rowspan);
                }
                //tengo que mostrar el row
                $(this).closest('tr').show();
            }
        });
    }
    function activarCalXOnSueldos(){
        $(".funcionAAplicar").on('change', function() {
            var posicion = $(this).attr('posicion');
              $('#ValorreciboPapeldetrabajosueldosForm').calx(
                'getCell',
                $('#Valorrecibo'+posicion+'Valor').attr('data-cell')
            ).setFormula($(this).val());
            tablaSueldoCalx.calx('calculate');
            $('#Valorrecibo'+posicion+'Formulamodificada').prop('checked', true);
            $('#Valorrecibo'+posicion+'Nuevaformula').val( $('#Valorrecibo'+posicion+'Formula').val());
        });
        tablaSueldoCalx = $('#ValorreciboPapeldetrabajosueldosForm').calx({
            language : 'id'
        });
        tablaSueldoCalx.submit(function(){
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
                    $("#divSueldoForm").html(data);
                    var empid = $("#Valorrecibo0EmpleadoId").val();
                    $("#buttonEmpleado"+empid).addClass("btn_empleados_liq");
                    activarCalXOnSueldos();
                },
                error: function(xhr,textStatus,error){
                    callAlertPopint(textStatus);
                }
            });
            return false;
          });
        ocultarFunciones();

        $('#ValorreciboPapeldetrabajosueldosForm input').change(function(){
            $('#ValorreciboPapeldetrabajosueldosForm').calx({ });
            ocultarFunciones();
        });
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
