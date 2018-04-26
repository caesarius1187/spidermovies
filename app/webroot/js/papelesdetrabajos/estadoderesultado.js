/**
 * Created by caesarius on 04/01/2017.
 */
$(document).ready(function() {    
    
    $( "#clickExcel" ).click(function() {
        showAllDivs();
        setTimeout(
        function() 
        {
           var table2excel = new Table2Excel();
            table2excel.export(document.querySelectorAll(".toExcelTable"));
            //CambiarTab("sumasysaldos");
        }, 2000);
        
    });
    var beforePrint = function() {
        $('.noprint').hide();
        $('#header').hide();
        $('#headerCliente').hide();
        $('#divTabs').hide();                  
        $('#AjustescontableEditForm submit').hide();
        $(".index").each(
                function(){
                    var cssObj = {
                        'border' : '0px',
                        'box-shadow' :'0px 0px 0px 0px #262626',
                      }
                    $(this).css(cssObj);
                }
        );
        $("#content").css('background' , '#fff');
        $("#container").css('background' , '#fff');
        $("body").css('background' , '#fff');
    };
    var afterPrint = function() {
        $('.noPrint').show();
        $('#header').show();
        $('button').show();
        $('a').show();
        $('#divTabs').show();
        $('#headerCliente').show();
        //$('#AjustescontableEditForm submit').show();

        //CambiarTab("sumasysaldos");
        //tblsys.recreate();
        ;
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
    /*var tblsys = $('#tblsys').dataTable({
        aLengthMenu: [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]
    ],
    iDisplayLength: -1
    }).api();*/
    CambiarTab("patrimonioneto");
    ajustarheadeepn();
    CambiarTab("sumasysaldos");
    catchAjustescontablesAdd();
    catchEditFormsAjusteContable();
    reloadDatePickers();
    catchEditFormsLiquidacionDetalles();
    loadInformeAuditor();
    //loadNotasYDatos();
});
function ajustarheadeepn() {
        var header_height = 0;
        $('.tblEEPN th').css('font-size','8');
        $('.tblEEPN th span').each(function() {
            if ($(this).outerWidth() > header_height) {
                header_height = $(this).outerWidth();
            }
        });
        $('.tblEEPN th').height(header_height);
    };
function showAllDivs(){
    $("#divContenedorBSyS").show();
    $("#divContenedorNotas").show();
    $("#divContenedorAnexos").show();
    $("#divContenedorEstadosResultados").show();
    $("#divContenedorEvolucionPatrimonioNeto").show();
    $("#divContenedorFlujoEfectivo").show();
    $("#divContenedorNotaFlujoEfectivo").show();
    $("#divContenedorSituacionPatrimonial").show();
    $("#divContenedorNotaSituacionPatrimonial").show();
    $("#divContenedorAnexoIBienesdeUso").show();
    $("#divAjustes").show();
    $("#divAuditor").show();
}
function hideAll(){
    $("#divContenedorBSyS").hide();
    $("#divContenedorNotas").hide();
    $("#divContenedorAnexos").hide();
    $("#divContenedorEstadosResultados").hide();
    $("#divContenedorEvolucionPatrimonioNeto").hide();
    $("#divContenedorFlujoEfectivo").hide();
    $("#divContenedorNotaFlujoEfectivo").hide();
    $("#divContenedorSituacionPatrimonial").hide();
    $("#divContenedorNotaSituacionPatrimonial").hide();
    $("#divContenedorAnexoIBienesdeUso").hide();
    $("#divAjustes").hide();
    $("#divAuditor").hide();
    $("#divNotasAclaratorias").hide();
}
function CambiarTab(sTab)	{
    $("#tabSumasYSaldos").attr("class", "cliente_view_tab");
    $("#tabEstadoDeResultados").attr("class", "cliente_view_tab");
    $("#tabNotas").attr("class", "cliente_view_tab");
    $("#tabAnexos").attr("class", "cliente_view_tab");
    $("#tabEvolucionPatrimonioNeto").attr("class", "cliente_view_tab");
    $("#tabFlujoEfectivo").attr("class", "cliente_view_tab");
    $("#tabNotaFlujoEfectivo").attr("class", "cliente_view_tab");
    $("#tabEvolucionSitacionPatrimonial").attr("class", "cliente_view_tab");
    $("#tabEvolucionNotasSitacionPatrimonial").attr("class", "cliente_view_tab");
    $("#tabEvolucionAnexoIBienesdeUso").attr("class", "cliente_view_tab");
    $("#tabAjuste").attr("class", "cliente_view_tab");
    $("#tabAuditor").attr("class", "cliente_view_tab");
    $("#tabNotasAclaratorias").attr("class", "cliente_view_tab");


    if(sTab == "sumasysaldos")
    {
        $("#tabSumasYSaldos").attr("class", "cliente_view_tab_active");
        hideAll();
        $("#divContenedorBSyS").show();

    }
    if(sTab == "estadoderesultado")
    {
        $("#tabEstadoDeResultados").attr("class", "cliente_view_tab_active");
        hideAll()   ;
        $("#divContenedorEstadosResultados").show();
             
    }
    if (sTab == "notas")
    {
        $("#tabNotas").attr("class", "cliente_view_tab_active");
        hideAll()   ;
        $("#divContenedorNotas").show();        
    }
    if (sTab == "anexos")
    {

        $("#tabAnexos").attr("class", "cliente_view_tab_active");
        hideAll()   ;
        $("#divContenedorAnexos").show();        
    }
    if (sTab == "patrimonioneto")
    {

        $("#tabEvolucionPatrimonioNeto").attr("class", "cliente_view_tab_active");
        hideAll();
        $("#divContenedorEvolucionPatrimonioNeto").show();      
    }
    if (sTab == "flujoefectivo")
    {

        $("#tabFlujoEfectivo").attr("class", "cliente_view_tab_active");        
        hideAll();        
        $("#divContenedorFlujoEfectivo").show();
    }
    if (sTab == "notaflujoefectivo")
    {
        $("#tabNotaFlujoEfectivo").attr("class", "cliente_view_tab_active");       
        hideAll();       
        $("#divContenedorNotaFlujoEfectivo").show();        
    }
    if (sTab == "situacionpatrimonial")
    {
        $("#tabEvolucionSitacionPatrimonial").attr("class", "cliente_view_tab_active");
        hideAll();       
        $("#divContenedorSituacionPatrimonial").show();        
    }
    if (sTab == "notassituacionpatrimonial")
    {
        $("#tabEvolucionNotasSitacionPatrimonial").attr("class", "cliente_view_tab_active");
        hideAll();       
        $("#divContenedorNotaSituacionPatrimonial").show();       
    }
    if (sTab == "anexoibienesdeuso")
    {
        $("#tabEvolucionAnexoIBienesdeUso").attr("class", "cliente_view_tab_active");
        hideAll();       
        $("#divContenedorAnexoIBienesdeUso").show();
    }
    if (sTab == "ajustes")
    {
        $("#tabAjuste").attr("class", "cliente_view_tab_active");
        hideAll();       
        $("#divAjustes").show();
    }
    if (sTab == "auditor")
    {
        $("#tabAuditor").attr("class", "cliente_view_tab_active");
        hideAll();       
        $("#divAuditor").show();
    }
    if (sTab == "notasAclaratorias")
    {
        $("#tabNotasAclaratorias").attr("class", "cliente_view_tab_active");
        hideAll();       
        $("#divNotasAclaratorias").show();
    }
}
function imprimir(){
       window.print();
       }
function contabilizarganancias (impcliid,periodo) {
 var data="";
 $.ajax({
     type: "post",  // Request method: post, get
     url: serverLayoutURL+"/asientos/contabilizarimpuesto/"+impcliid+"/"+periodo,
     // URL to request
     data: data,  // post data
     success: function(response) {
         $('#myModal').on('show.bs.modal', function() {
             $('#myModal').find('.modal-title').html('Asiento automatico Devengamiento Ganancias');
             $('#myModal').find('.modal-body').html(response);
             /*$('#myModal').find('.modal-content')
                 .css({
                     width: 'max-content',
                     'margin-left': function () {
                         return -($(this).width() / 2);
                     }
                 });*/
             $('#myModal').find('.modal-footer').html("");
             $('#myModal').find('.modal-footer').append($('<button>', {
                 type:'button',
                 datacontent:'remove',
                 class:'btn btn-primary',
                 id:'editRowBtn',
                 onclick:"$('#AsientoAddForm').submit()",
                 text:"Aceptar"
             }));
             $('#myModal').find('.modal-footer').append($('<button>', {
                 type:'button',
                 datacontent:'remove',
                 class:'btn btn-primary',
                 id:'editRowBtn',
                 onclick:" $('#myModal').modal('hide')",
                 text:"Cerrar"
             }));
         });
         $('#myModal').modal('show');
         $('.chosen-select-cuenta').chosen({
             search_contains:true,
             include_group_label_in_selected:true
         });
         rellenarAsientoGanancias();
         $('#AsientoAddForm').submit(function(){
             $('#myModal').modal('hide');
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
                $('#AsientoAddForm input').each(function(){
                    $(this).removeAttr('disabled');
                });
                //serialize form data
                var formData = $(this).serialize();
                //get form action
                var formUrl = $(this).attr('action');
                $("#AjustescontableEditForm").submit();
                $.ajax({
                    type: 'POST',
                    url: formUrl,
                    data: formData,
                    success: function(data,textStatus,xhr){
                        //vamos a hacer el reload desde el guardado de los ajustes
                        //location.reload();
                        $(".ajusteImpuestoALaGanancia").each(function(){
                            $(this).attr('disabled',false);
                        })
                        
                    },
                    error: function(xhr,textStatus,error){
                        $('#myModal').modal('show');
                        alert(textStatus);
                        callAlertPopint(textStatus);
                        return false;
                    }
                });
             }else{
                 callAlertPopint("El asiento no se ha sobreescrito.");
             }

             return false;
         });
         $(".inputDebe").each(function () {
             $(this).change(addTolblTotalDebeAsieto);
         });
         $(".inputHaber").each(function () {
             $(this).change(addTolblTotalhaberAsieto);
         });
     },
     error:function (XMLHttpRequest, textStatus, errorThrown) {
         alert(textStatus);
         alert(XMLHttpRequest);
         alert(errorThrown);
     }
 });
}
function reloadDatePickers(){
    jQuery(document).ready(function($) {
        $( "input.datepicker" ).datepicker({
            yearRange: "-100:+50",
            changeMonth: true,
            changeYear: true,
            constrainInput: false,
            dateFormat: 'dd-mm-yy',
        });	
        $( "input.datepicker-day-month" ).datepicker({
            yearRange: "-100:+50",
            changeMonth: true,
            changeYear: false,
            constrainInput: false,
            dateFormat: 'dd-mm',
        });	
        $( "input.datepicker-month-year" ).datepicker({
            yearRange: "-100:+50",
            changeMonth: true,
            changeYear: true,
            constrainInput: false,
            dateFormat: 'mm-yy',
        });	
        $("#ui-datepicker-div").hide();
    });		
}
function loadNotasYDatos(){
    var nota1 = $('#liquidaciondetalleNota1').val();
    $('#spnESPNota1').html(nota1);
}
function loadInformeAuditor(){
    var date = $('#liquidaciondetalleFechainforme').val();
    var parts = date.split("-");
    var dt = new Date(parts[2], parts[1]-1 , parts[0]);                            
    var dia = $.datepicker.formatDate('dd', dt);
    var mes = $.datepicker.formatDate('mm', dt);
    var ano = $.datepicker.formatDate('yy', dt);
    var meses = [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"];
    var fecha = dia+" del mes de "+meses[mes*1-1]+" de "+ano;
    $('#spanFechaFooter').html(fecha);
}
function rellenarAsientoGanancias(){
    var fecha = '31-'+$("#periodo").val();
    $("#Asiento0Fecha").val(fecha)
    $("#Asiento1Fecha").val(fecha)
    $("#Asiento2Fecha").val(fecha)
    var impuestodeterminadofinal = $('#impuestodeterminadofinal').val()*1;
   impuestodeterminadofinal = impuestodeterminadofinal.toFixed(2);
    if(impuestodeterminadofinal>=0){
         var activoimpuestodiferido = $('#activoimpuestodiferido').val()*1;
            activoimpuestodiferido = activoimpuestodiferido.toFixed(2);

        var saldoAPagar = 0;
        if(impuestodeterminadofinal<=activoimpuestodiferido){
            activoimpuestodiferido=impuestodeterminadofinal;
        }else{
            saldoAPagar = impuestodeterminadofinal-activoimpuestodiferido;
        }
        //Tenemos GANANCIA
        if($('#0cuenta2534').length > 0){
            //Imp a las Ganancias
            var orden = $('#0cuenta2534').attr('orden');
            if($('#Asiento0Movimiento'+orden+'Id').val()==0){
                $('#Asiento0Movimiento'+orden+'Debe').val(impuestodeterminadofinal);
            }                        
        }
        if($('#0cuenta266').length > 0){
            //Ganancias - Activo Diferido
            var orden = $('#0cuenta266').attr('orden');
            if($('#Asiento0Movimiento'+orden+'Id').val()==0){
                $('#Asiento0Movimiento'+orden+'Haber').val(activoimpuestodiferido);
            }
        }
        if($('#0cuenta1447').length > 0){
            //Ganancias - Saldo a Pagar
            var orden = $('#0cuenta1447').attr('orden');
            if($('#Asiento0Movimiento'+orden+'Id').val()==0){
                $('#Asiento0Movimiento'+orden+'Haber').val(saldoAPagar);
            }
        }
        var saldolibredisponibilidad = $('#saldolibredisponibilidad').val()*1;
            saldolibredisponibilidad = saldolibredisponibilidad.toFixed(2);
        var retenciones = $('#retenciones').val()*1; 
            retenciones = retenciones.toFixed(2);
        var anticiposacomput = $('#anticiposacomput').val()*1; 
            anticiposacomput = anticiposacomput.toFixed(2);
        var igmpsaldoafavor = $('#igmpsaldoafavor').val()*1; 
            igmpsaldoafavor = igmpsaldoafavor.toFixed(2);
        //var igmpanticipos = $('#igmpanticipos').val()*1; 
        var ley25413 = $('#ley25413').val()*1; 
            ley25413 = ley25413.toFixed(2);
        
        var totalAfavor = retenciones*1+anticiposacomput*1+igmpsaldoafavor*1+ley25413*1+saldolibredisponibilidad*1;
            totalAfavor = totalAfavor.toFixed(2);
        var saldo = saldoAPagar-totalAfavor;
            saldo = saldo.toFixed(2);
        
        //asiento de pago
        if($('#1cuentaPago268').length > 0){
            //Ganancias - Retenciones
            var orden = $('#1cuentaPago268').attr('orden');
            $('#Asiento1Movimiento'+orden+'Haber').val(retenciones);
        }
        if($('#1cuentaPago265').length > 0){
            //Ganancias - Saldo a Pagar
            var orden = $('#1cuentaPago265').attr('orden');
            $('#Asiento1Movimiento'+orden+'Haber').val(anticiposacomput);
        }
        if($('#1cuentaPago275').length > 0){
            //Ganancias - Saldo a Pagar
            var orden = $('#1cuentaPago275').attr('orden');
            $('#Asiento1Movimiento'+orden+'Haber').val(igmpsaldoafavor);
        }
        /*if($('#cuentaPago274').length > 0){
            //Ganancias - Saldo a Pagar
            var orden = $('#cuentaPago274').attr('orden');
            $('#Asiento1Movimiento'+orden+'Haber').val(igmpanticipos);
        }*/
        if($('#1cuentaPago298').length > 0){
            //Ganancias - Saldo a Pagar
            var orden = $('#1cuentaPago298').attr('orden');
            $('#Asiento1Movimiento'+orden+'Haber').val(ley25413);
        }
        
        if($('#1cuentaPago1447').length > 0){
            //Ganancias - Saldo a Pagar
            var orden = $('#1cuentaPago1447').attr('orden');
            $('#Asiento1Movimiento'+orden+'Debe').val(saldoAPagar);
        }
        if(saldo>=0){
            //debo pagar
            if($('#1cuentaPago5').length > 0){
                //Caja
                var orden = $('#1cuentaPago5').attr('orden');
                $('#Asiento1Movimiento'+orden+'Haber').val(saldo);
            }
            if($('#1cuentaPago1069').length > 0){
                //Caja no 3ra
                var orden = $('#1cuentaPago1069').attr('orden');
                $('#Asiento1Movimiento'+orden+'Haber').val(saldo);
            }
            if($('#1cuentaPago264').length > 0){
                //Ganancias - SLD
                var orden = $('#1cuentaPago264').attr('orden');
                $('#Asiento1Movimiento'+orden+'Haber').val(saldolibredisponibilidad);
            }
        }else{
            if($('#0cuentaPago264').length > 0){
                //Ganancias - Saldo a Pagar
                var orden = $('#0cuentaPago264').attr('orden');
                $('#Asiento1Movimiento'+orden+'Debe').val((saldo*-1)-saldolibredisponibilidad);
            }
        }
        
    }else{
        if($('#0cuenta3681').length > 0){
            var orden = $('#0cuenta3681').attr('orden');
            $('#Asiento0Movimiento'+orden+'Haber').val(saldoAPagar);
        }
    }    
     //Ahora vamos a rellenar el Ajuste de Impuesto a las ganancias y despues el submit
    $(".ajusteImpuestoALaGanancia").each(function(){
        $(this).val(impuestodeterminadofinal);
    })
}
function catchEditFormsAjusteContable(){
    $(".formAjusteContable").each(function(){
         $(this).submit(function(){
             $('#myModal').modal('hide');
             /*Vamos a advertir que estamos reemplazando un asiento ya guardado*/
             var asientoyaguardado=false;
             if($("#AsientoAddForm #Asiento0Id").val()*1!=0){
                 asientoyaguardado=true;
             }
             var r=true;
             if(asientoyaguardado){
                 r = confirm("Esta modificando este ajuste recuerde que si ya "+
                    "se guardo el asiento del impuesto a las ganancias se debe rehacer el mismo");
             }
             if (r == true) {
                $(this).find('input').each(function(){
                    $(this).removeAttr('disabled');
                });
                //serialize form data
                var formData = $(this).serialize();
                //get form action
                var formUrl = $(this).attr('action');
                $.ajax({
                    type: 'POST',
                    url: formUrl,
                    data: formData,
                    success: function(data,textStatus,xhr){
                        location.reload();
                    },
                    error: function(xhr,textStatus,error){
                        $('#myModal').modal('show');
                        alert(textStatus);
                        callAlertPopint(textStatus);
                        return false;
                    }
                });
             }else{
                 callAlertPopint("El Ajuste no se ha modificado.");
             }

             return false;
         });
    });
}
function catchEditFormsLiquidacionDetalles(){
    $("#liquidaciondetalle").submit(function(){
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
                callAlertPopint(respuesta.respuesta);
                location.reload();
            },
            error: function(xhr,textStatus,error){
                $('#myModal').modal('show');
                alert(textStatus);
                callAlertPopint(textStatus);
                return false;
            }
        });
        return false;
    });
}
function contabilizarexistenciafinal (clienteid,periodo) {
    var data="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/asientos/contabilizarexistenciafinal/"+clienteid+"/"+periodo,
        // URL to request
        data: data,  // post data
        success: function(response) {
            $('#myModal').on('show.bs.modal', function() {
                $('#myModal').find('.modal-title').html('Asiento automatico Existencia Final');
                $('#myModal').find('.modal-body').html(response);
                /*$('#myModal').find('.modal-content')
                    .css({
                        width: 'max-content',
                        'margin-left': function () {
                            return -($(this).width() / 2);
                        }
                    });*/
                $('#myModal').find('.modal-footer').html("");
                $('#myModal').find('.modal-footer').append($('<button>', {
                    type:'button',
                    datacontent:'remove',
                    class:'btn btn-primary',
                    id:'editRowBtn',
                    onclick:"$('#AsientoAddForm').submit()",
                    text:"Aceptar"
                }));
                $('#myModal').find('.modal-footer').append($('<button>', {
                    type:'button',
                    datacontent:'remove',
                    class:'btn btn-primary',
                    id:'editRowBtn',
                    onclick:" $('#myModal').modal('hide')",
                    text:"Cerrar"
                }));
            });
            $('#myModal').modal('show');
            $('.chosen-select-cuenta').chosen({
                search_contains:true,
                include_group_label_in_selected:true
            });
            $('#AsientoAddForm').submit(function(){
                $('#myModal').modal('hide');
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
                   $('#AsientoAddForm input').each(function(){
                       $(this).removeAttr('disabled');
                   });
                   //serialize form data
                   var formData = $(this).serialize();
                   //get form action
                   var formUrl = $(this).attr('action');
                   $.ajax({
                       type: 'POST',
                       url: formUrl,
                       data: formData,
                       success: function(data,textStatus,xhr){
                           location.reload();
                       },
                       error: function(xhr,textStatus,error){
                           $('#myModal').modal('show');
                           alert(textStatus);
                           callAlertPopint(textStatus);
                           return false;
                       }
                   });
                }else{
                    callAlertPopint("El asiento no se ha sobreescrito.");
                }

                return false;
            });
            $(".inputDebe").each(function () {
                $(this).change(addTolblTotalDebeAsieto);
            });
            $(".inputHaber").each(function () {
                $(this).change(addTolblTotalhaberAsieto);
            });
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
            alert(XMLHttpRequest);
            alert(errorThrown);
        }
    });
}
function contabilizarApertura (clienteid,periodo) {
 var data="";
 $.ajax({
     type: "post",  // Request method: post, get
     url: serverLayoutURL+"/asientos/contabilizarapertura/"+clienteid+"/"+periodo,
     // URL to request
     data: data,  // post data
     success: function(response) {
         $('#myModal').on('show.bs.modal', function() {
             $('#myModal').find('.modal-title').html('Asiento automatico Existencia Final');
             $('#myModal').find('.modal-body').html(response);
             /*$('#myModal').find('.modal-content')
                 .css({
                     width: 'max-content',
                     'margin-left': function () {
                         return -($(this).width() / 2);
                     }
                 });*/
             $('#myModal').find('.modal-footer').html("");
             $('#myModal').find('.modal-footer').append($('<button>', {
                 type:'button',
                 datacontent:'remove',
                 class:'btn btn-primary',
                 id:'editRowBtn',
                 onclick:"$('#AsientoAddForm').submit()",
                 text:"Aceptar"
             }));
             $('#myModal').find('.modal-footer').append($('<button>', {
                 type:'button',
                 datacontent:'remove',
                 class:'btn btn-primary',
                 id:'editRowBtn',
                 onclick:" $('#myModal').modal('hide')",
                 text:"Cerrar"
             }));
         });
         $('#myModal').modal('show');
         $('.chosen-select-cuenta').chosen({
             search_contains:true,
             include_group_label_in_selected:true
         });
         $('#AsientoAddForm').submit(function(){
             $('#myModal').modal('hide');
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
                $('#AsientoAddForm input').each(function(){
                    $(this).removeAttr('disabled');
                });
                //serialize form data
                var formData = $(this).serialize();
                //get form action
                var formUrl = $(this).attr('action');
                $.ajax({
                    type: 'POST',
                    url: formUrl,
                    data: formData,
                    success: function(data,textStatus,xhr){
                        location.reload();
                    },
                    error: function(xhr,textStatus,error){
                        $('#myModal').modal('show');
                        alert(textStatus);
                        callAlertPopint(textStatus);
                        return false;
                    }
                });
             }else{
                 callAlertPopint("El asiento no se ha sobreescrito.");
             }

             return false;
         });
         $(".inputDebe").each(function () {
             $(this).change(addTolblTotalDebeAsieto);
         });
         $(".inputHaber").each(function () {
             $(this).change(addTolblTotalhaberAsieto);
         });
     },
     error:function (XMLHttpRequest, textStatus, errorThrown) {
         alert(textStatus);
         alert(XMLHttpRequest);
         alert(errorThrown);
     }
 });
}

function addTolblTotalDebeAsieto(event) {
    var debesubtotal = 0;
    var habersubtotal = 0;
     if($('#cuenta2224').length > 0){         
         var orden = $('#cuenta2224').attr('orden');
        $('#Asiento0Movimiento'+orden+'Debe').val(0);
        $('#Asiento0Movimiento'+orden+'Haber').val(0);          
    }
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
    $(".inputHaber").each(function () {
        habersubtotal = habersubtotal*1 + this.value*1;
    });
    if($('#cuenta2224').length > 0){
        var orden = $('#cuenta2224').attr('orden');
        var debe=debesubtotal*1;
        var haber=habersubtotal*1;
        var saldo = debe-haber;
        if(saldo>0){
            $('#Asiento0Movimiento'+orden+'Haber').val(saldo);
            $('.inputHaber').each(function(){
                $(this).trigger("change");
                 return;
             });
        }else{
            $('#Asiento0Movimiento'+orden+'Debe').val(saldo*-1);
            debesubtotal+=saldo*-1;
        }
    }
    $("#lblTotalDebe").text(parseFloat(debesubtotal).toFixed(2)) ;         
    showIconDebeHaber()
}
function addTolblTotalhaberAsieto(event) {
    var debesubtotal = 0;
    var habersubtotal = 0;
     if($('#cuenta2224').length > 0){         
         var orden = $('#cuenta2224').attr('orden');
        $('#Asiento0Movimiento'+orden+'Debe').val(0);
        $('#Asiento0Movimiento'+orden+'Haber').val(0);          
    }
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
    $(".inputDebe").each(function () {
        debesubtotal = debesubtotal*1 + this.value*1;
    });
    if($('#cuenta2224').length > 0){
        var orden = $('#cuenta2224').attr('orden');
        var debe=debesubtotal*1;
        var haber=habersubtotal*1;
        var saldo = debe-haber;         
        if(saldo>0){
             $('#Asiento0Movimiento'+orden+'Haber').val(saldo);
            habersubtotal+=saldo-1;
        }else{
            $('#Asiento0Movimiento'+orden+'Debe').val(saldo*-1);               
            $('.inputDebe').each(function(){
                $(this).trigger("change");
                 return;
             });
        }
    }
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

function catchAjustescontablesAdd(){
    $('#AjustescontableAddForm').submit(function(){
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
                        location.reload();
                    },
                    error: function(xhr,textStatus,error){
                        callAlertPopint("Deposito NO Modificado. Intente de nuevo mas Tarde");
                    }
            });
            return false;
    });
}