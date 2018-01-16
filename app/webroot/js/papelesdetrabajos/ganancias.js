/**
 * Created by caesarius on 04/01/2017.
 */
$(document).ready(function() {    
    
    $( "#clickExcel" ).click(function() {
        showAllDivs();
        var table2excel = new Table2Excel();
        table2excel.export(document.querySelectorAll(".toExcelTable"));
        CambiarTab("sumasysaldos");
    });
    var beforePrint = function() {
        $('#header').hide();
        $('#headerCliente').hide();
        $('#divTabs').hide();
        $('#noprint').hide();
        //showAllDivs();
        tblsys.destroy();
       

          
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
           
        };
    var afterPrint = function() {
        $('#header').show();
        $('button').show();
        $('#noprint').hide();
        $('a').show();
        $('#divTabs').show();
        //CambiarTab("sumasysaldos");
        tblsys = $('#tblsys').dataTable({
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        iDisplayLength: -1
        }).api();
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
    var tblsys = $('#tblsys').dataTable({
        aLengthMenu: [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]
    ],
    iDisplayLength: -1
    }).api();
    CambiarTab("sumasysaldos");
    
    $(".inputDebe").each(function () {
        $(this).change(addTolblTotalDebeAsieto);
    });
    $(".inputHaber").each(function () {
        $(this).change(addTolblTotalhaberAsieto);
    });
    $(".inputDebe").each(function () {
        $(this).trigger("change");
        return;
    });
    $(".inputHaber").each(function () {
        $(this).trigger("change");
        return;
    });
    catchAsientoDeduccionGeneral();
});
function showAllDivs(){
    $("#divContenedorBSyS").show();
    $("#divPrimeraCategoria").show();
    $("#divPatrimonioTercera").show();
    $("#divPatrimonio").show();
    $("#divDeduccionesGenerales").show();
    $("#divDeduccionesPersonales").show();
    $("#divDeterminacionGPF").show();
    $("#divJustificacionVarPat").show();
    $("#divQuebrantos").show();
    $("#divAsientos").show();
}
function CambiarTab(sTab)	{
    $("#tabSumasYSaldos").attr("class", "cliente_view_tab");
    $("#tabPrimeraCategoria").attr("class", "cliente_view_tab");
    $("#tabPatrimoniotercera").attr("class", "cliente_view_tab");
    $("#tabTercerarRestoCategoria").attr("class", "cliente_view_tab");
    $("#tabTerceraCategoria").attr("class", "cliente_view_tab");
    $("#tabCuartaABCCategoria").attr("class", "cliente_view_tab");
    $("#tabCuartaDEFCategoria").attr("class", "cliente_view_tab");
    $("#tabJustVarPat").attr("class", "cliente_view_tab");
    $("#tabQuebranto").attr("class", "cliente_view_tab");
    $("#tabAsiento").attr("class", "cliente_view_tab");

    if(sTab == "sumasysaldos")
    {
        $("#tabSumasYSaldos").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").show();
        $("#divPrimeraCategoria").hide();
	    $("#divPatrimonioTercera").hide();
	    $("#divPatrimonio").hide();
	    $("#divDeduccionesGenerales").hide();
	    $("#divDeduccionesPersonales").hide();
	    $("#divDeterminacionGPF").hide();
	    $("#divJustificacionVarPat").hide();        
	    $("#divQuebrantos").hide();        
    }
    if(sTab == "primeracategoria")
    {
        $("#tabPrimeraCategoria").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divPrimeraCategoria").show();
	    $("#divPatrimonioTercera").hide();
	    $("#divPatrimonio").hide();
	    $("#divDeduccionesGenerales").hide();
	    $("#divDeduccionesPersonales").hide();
	    $("#divDeterminacionGPF").hide();
            $("#divJustificacionVarPat").hide();
            $("#divQuebrantos").hide();      
            $("#divAsientos").hide();
    }
    if (sTab == "patrimoniotercera")
    {

        $("#tabPatrimoniotercera").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divPrimeraCategoria").hide();
	    $("#divPatrimonioTercera").show();
	    $("#divPatrimonio").hide();
	    $("#divDeduccionesGenerales").hide();
	    $("#divDeduccionesPersonales").hide();
	    $("#divDeterminacionGPF").hide();
            $("#divJustificacionVarPat").hide();
            $("#divQuebrantos").hide();       
            $("#divAsientos").hide();
    }
    if (sTab == "tercerarestocategoria")
    {

        $("#tabTercerarRestoCategoria").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divPrimeraCategoria").hide();
	    $("#divPatrimonioTercera").hide();
	    $("#divPatrimonio").show();
	    $("#divDeduccionesGenerales").hide();
	    $("#divDeduccionesPersonales").hide();
	    $("#divDeterminacionGPF").hide();
            $("#divJustificacionVarPat").hide();
            $("#divQuebrantos").hide();   
            $("#divAsientos").hide();
    }
    if (sTab == "terceracategoria")
    {

        $("#tabTerceraCategoria").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divPrimeraCategoria").hide();
	    $("#divPatrimonioTercera").hide();
	    $("#divPatrimonio").hide();
	    $("#divDeduccionesGenerales").show();
	    $("#divDeduccionesPersonales").hide();
	    $("#divDeterminacionGPF").hide();
            $("#divJustificacionVarPat").hide();
            $("#divQuebrantos").hide();        
            $("#divAsientos").hide();
    }
    if (sTab == "cuartaabccategoria")
    {

        $("#tabCuartaABCCategoria").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divPrimeraCategoria").hide();
	    $("#divPatrimonioTercera").hide();
	    $("#divPatrimonio").hide();
	    $("#divDeduccionesGenerales").hide();
	    $("#divDeduccionesPersonales").show();
	    $("#divDeterminacionGPF").hide();
            $("#divJustificacionVarPat").hide();
            $("#divQuebrantos").hide();        
            $("#divAsientos").hide();
    }
    if (sTab == "cuartadefcategoria")
    {
        $("#tabCuartaDEFCategoria").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divPrimeraCategoria").hide();
	    $("#divPatrimonioTercera").hide();
	    $("#divPatrimonio").hide();
	    $("#divDeduccionesGenerales").hide();
	    $("#divDeduccionesPersonales").hide();
            $("#divJustificacionVarPat").hide();
	    $("#divDeterminacionGPF").show();
            $("#divQuebrantos").hide();     
            $("#divAsientos").hide();
    }   
    if (sTab == "justificacionvarpat")
    {
        $("#tabJustVarPat").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divPrimeraCategoria").hide();
	    $("#divPatrimonioTercera").hide();
	    $("#divPatrimonio").hide();
	    $("#divDeduccionesGenerales").hide();
	    $("#divDeduccionesPersonales").hide();
            $("#divJustificacionVarPat").show();
	    $("#divDeterminacionGPF").hide();
            $("#divQuebrantos").hide();     
            $("#divAsientos").hide();
    }   
    if (sTab == "quebrantos")
    {
        $("#tabQuebranto").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divPrimeraCategoria").hide();
	    $("#divPatrimonioTercera").hide();
	    $("#divPatrimonio").hide();
	    $("#divDeduccionesGenerales").hide();
	    $("#divDeduccionesPersonales").hide();
            $("#divJustificacionVarPat").hide();
	    $("#divDeterminacionGPF").hide();
            $("#divQuebrantos").show();     
            $("#divAsientos").hide();
    }   
    if (sTab == "asiento")
    {
        $("#tabAsiento").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divPrimeraCategoria").hide();
	    $("#divPatrimonioTercera").hide();
	    $("#divPatrimonio").hide();
	    $("#divDeduccionesGenerales").hide();
	    $("#divDeduccionesPersonales").hide();
            $("#divJustificacionVarPat").hide();
	    $("#divDeterminacionGPF").hide();
            $("#divQuebrantos").hide();     
            $("#divAsientos").show();
    }   
}
function imprimir(){
    showAllDivs();
    setTimeout(
    function() 
    {
       window.print();
    }, 3000);
   
}

function addTolblTotalDebeAsieto(event) {
    var debesubtotal = 0;
    var habersubtotal = 0;
     if($('#myModal #cuenta2224').length > 0){         
         var orden = $('#myModal #cuenta2224').attr('orden');
        $('#myModal #Asiento0Movimiento'+orden+'Debe').val(0);
        $('#myModal #Asiento0Movimiento'+orden+'Haber').val(0);          
    }
    $("#myModal .inputDebe").each(function () {
        debesubtotal = debesubtotal*1 + this.value*1;
        if(this.value*1!=0){
            $(this).removeClass("movimientoSinValor");
            $(this).addClass("movimientoConValor");
        }else{
            $(this).removeClass("movimientoConValor")
            $(this).addClass("movimientoSinValor");
        }
    });
    $("#myModal .inputHaber").each(function () {
        habersubtotal = habersubtotal*1 + this.value*1;
    });
    if($('#myModal #cuenta2224').length > 0){
        var orden = $('#myModal #cuenta2224').attr('orden');
        var debe=debesubtotal*1;
        var haber=habersubtotal*1;
        var saldo = debe-haber;
        if(saldo>0){
            $('#myModal #Asiento0Movimiento'+orden+'Haber').val(saldo);
            $('#myModal .inputHaber').each(function(){
                $(this).trigger("change");
                 return;
             });
        }else{
            $('#myModal #Asiento0Movimiento'+orden+'Debe').val(saldo*-1);
            debesubtotal+=saldo*-1;
        }
    }
    $("#myModal #lblTotalDebe").text(parseFloat(debesubtotal).toFixed(2)) ;         
    showIconDebeHaber()
}
function addTolblTotalhaberAsieto(event) {
    var debesubtotal = 0;
    var habersubtotal = 0;
     if($('#myModal #cuenta2224').length > 0){         
         var orden = $('#myModal #cuenta2224').attr('orden');
        $('#myModal #Asiento0Movimiento'+orden+'Debe').val(0);
        $('#myModal #Asiento0Movimiento'+orden+'Haber').val(0);          
    }
    $("#myModal .inputHaber").each(function () {
        habersubtotal = habersubtotal*1 + this.value*1;
        if(this.value*1!=0){
            $(this).removeClass("movimientoSinValor");
            $(this).addClass("movimientoConValor");
        }else{
            $(this).removeClass("movimientoConValor")
            $(this).addClass("movimientoSinValor");
        }
    });
    $("#myModal .inputDebe").each(function () {
        debesubtotal = debesubtotal*1 + this.value*1;
    });
    if($('#myModal #cuenta2224').length > 0){
        var orden = $('#cuenta2224').attr('orden');
        var debe=debesubtotal*1;
        var haber=habersubtotal*1;
        var saldo = debe-haber;         
        if(saldo>0){
             $('#myModal #Asiento0Movimiento'+orden+'Haber').val(saldo);
            habersubtotal+=saldo-1;
        }else{
            $('#myModal #Asiento0Movimiento'+orden+'Debe').val(saldo*-1);               
            $('#myModal .inputDebe').each(function(){
                $(this).trigger("change");
                 return;
             });
        }
    }
    $("#myModal #lblTotalHaber").text(parseFloat(habersubtotal).toFixed(2)) ;
    showIconDebeHaber()
}
function showIconDebeHaber(){
    if($("#myModal #lblTotalHaber").text()==$("#myModal #lblTotalDebe").text()){
        $("#myModal #iconDebeHaber").attr('src',serverLayoutURL+'/img/test-pass-icon.png');
    }else{
        $("#myModal #iconDebeHaber").attr('src',serverLayoutURL+'/img/test-fail-icon.png');
    }       
}
function catchAsientoDeduccionGeneral(){
    $('#AsientoDeduccionGeneral').submit(function(){
            /*Vamos a advertir que estamos reemplazando un asiento ya guardado*/
            var asientoyaguardado=false;
            if($("#AsientoAddForm #Asiento0Id").val()*1!=0){
                    asientoyaguardado=true;
            }
            var r=true;
            if(asientoyaguardado){
                    r = confirm("Este asiento sobreescribira al previamente guardado.");
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
                                    location.reload();
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

function loadFormImpuestoQuebrantos(impcliid,periodo){
    jQuery(document).ready(function($) {
        var data ="";
        $.ajax({
            type: "get",  // Request method: post, get
            url: serverLayoutURL+"/quebrantos/add/"+impcliid+"/"+periodo,
            // URL to request
            data: data,  // post data
            success: function(response) {
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModal').find('.modal-title').html('Modificar Quebrantos Guardados');
                    $('#myModal').find('.modal-body').html(response);
                    // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                });
                $('#myModal').modal('show');
                
                $('.chosen-select').chosen({search_contains:true});
                $("#Quebranto0Periodogeneral").on('change', function() {
                    $(".periodoaplicacion").each(function(){
                        $(this).val($("#Quebranto0Periodogeneral").val());
                    });    
                    var strDatePeriodoOrig = '01-'+$("#Quebranto0Periodogeneral").val();
                    var parts = strDatePeriodoOrig.split("-");
                    var dt = new Date(parts[2], parts[1] , parts[0]);                            
                    dt.setMonth(dt.getMonth() - 1);
                    dt.setMonth(dt.getMonth() - 12);
                    var datePeriodoOrig0 = $.datepicker.formatDate('mm-yy', dt);
                    dt.setMonth(dt.getMonth() - 12);
                    var datePeriodoOrig1 = $.datepicker.formatDate('mm-yy', dt);
                    dt.setMonth(dt.getMonth() - 12);
                    var datePeriodoOrig2 = $.datepicker.formatDate('mm-yy', dt);
                    dt.setMonth(dt.getMonth() - 12);
                    var datePeriodoOrig3 = $.datepicker.formatDate('mm-yy', dt);
                    dt.setMonth(dt.getMonth() - 12);
                    var datePeriodoOrig4 = $.datepicker.formatDate('mm-yy', dt);
                    $("#Quebranto0Periodogenerado").val(datePeriodoOrig0);
                    $("#Quebranto1Periodogenerado").val(datePeriodoOrig1);
                    $("#Quebranto2Periodogenerado").val(datePeriodoOrig2);
                    $("#Quebranto3Periodogenerado").val(datePeriodoOrig3);
                    $("#Quebranto4Periodogenerado").val(datePeriodoOrig4);
                });
                
                $('#QuebrantoAddForm').submit(function(){
                        //serialize form data
                        var formData = $(this).serialize();
                        //get form action
                        var formUrl = $(this).attr('action');
                        $.ajax({
                                type: 'POST',
                                url: formUrl,
                                data: formData,
                                success: function(data,textStatus,xhr){                                    
                                    $('#myModal').modal('hide');
                                    location.reload();                           
                                },
                                error: function(xhr,textStatus,error){
                                        callAlertPopint("Deposito NO Modificado. Intente de nuevo mas Tarde");
                                }
                        });
                        return false;
                });
                reloadDatePickers();
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                callAlertPopint(textStatus);
            }
        });
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
         $('#myModal #AsientoAddForm').submit(function(){
             $('#myModal').modal('hide');
             /*Vamos a advertir que estamos reemplazando un asiento ya guardado*/
             var asientoyaguardado=false;
             if($("#myModal #AsientoAddForm #Asiento0Id").val()*1!=0){
                 asientoyaguardado=true;
             }
             var r=true;
             if(asientoyaguardado){
                 r = confirm("Este asiento sobreescribira al previamente guardado, reemplazando los valores por los calculados" +
                     " en este momento. Para ver el asiento previamente guardado CANCELE, luego ingrese en el Informe de " +
                     " Sumas y saldos y despues en Asientos");
             }
             if (r == true) {
                $('#myModal #AsientoAddForm input').each(function(){
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
function rellenarAsientoGanancias(){
    var fecha = '01-'+$("#periodo").val();
    $("#myModal #Asiento0Fecha").val(fecha)
    $("#myModal #Asiento1Fecha").val(fecha)
    $("#myModal #Asiento2Fecha").val(fecha)
    var impuestodeterminadofinal = $('#impuestodeterminadofinal').val()*1;
    var impuestodeterminadofinal = impuestodeterminadofinal.toFixed(2);
    var gananciaMinimaPresunta = $('#gananciaMinimaPresunta').val()*1;
    var gananciaMinimaPresunta = gananciaMinimaPresunta.toFixed(2);
    var retenciones = $('#retenciones').val()*1;
    var retenciones = retenciones.toFixed(2);
    var percepciones = $('#percepciones').val()*1;
    var percepciones = percepciones.toFixed(2);
    var anticiposacomputar = $('#anticiposacomputar').val()*1;
    var anticiposacomputar = anticiposacomputar.toFixed(2);
    var saldoAFavorPeriodoAnterior = $('#saldoAFavorPeriodoAnterior').val()*1;
    var saldoAFavorPeriodoAnterior = saldoAFavorPeriodoAnterior.toFixed(2);
    var saldoDeLibreDisponibilidadPeriodo = $('#saldoDeLibreDisponibilidadPeriodo').val()*1;
    var saldoDeLibreDisponibilidadPeriodo = saldoDeLibreDisponibilidadPeriodo.toFixed(2);
    var ley25413 = $('#ley25413').val()*1;
    var ley25413 = ley25413.toFixed(2);
    var apagar = $('#apagar').val()*1;
    var apagar = apagar.toFixed(2);
   
   var variacionSaldoLibreDisponibilidad = saldoDeLibreDisponibilidadPeriodo-saldoAFavorPeriodoAnterior;
   
    var saldoAPagar = 0;  
    if($('#0cuenta2534').length > 0){
        //Imp a las Ganancias
        var orden = $('#0cuenta2534').attr('orden');
        if($('#myModal #Asiento0Movimiento'+orden+'Id').val()==0){
            $('#myModal #Asiento0Movimiento'+orden+'Debe').val(impuestodeterminadofinal);
        }                        
    }
    if($('#0cuenta1447').length > 0){
        //Imp a las Ganancias
        var orden = $('#0cuenta1447').attr('orden');
        if($('#myModal #Asiento0Movimiento'+orden+'Id').val()==0){
            $('#myModal #Asiento0Movimiento'+orden+'Haber').val(impuestodeterminadofinal);
        }                        
    }
    if($('#1cuenta264').length > 0){
        //Imp a las Ganancias
        var orden = $('#1cuenta264').attr('orden');
        if($('#myModal #Asiento0Movimiento'+orden+'Id').val()==0){
            if(variacionSaldoLibreDisponibilidad>0)
                $('#myModal #Asiento0Movimiento'+orden+'Debe').val(variacionSaldoLibreDisponibilidad);
            else
                $('#myModal #Asiento0Movimiento'+orden+'Haber').val(variacionSaldoLibreDisponibilidad*-1);
        }                        
    }
    
    if($('#1cuenta298').length > 0){
        //Imp a las Ganancias
        var orden = $('#1cuenta298').attr('orden');
        if($('#myModal #Asiento0Movimiento'+orden+'Id').val()==0){
            $('#myModal #Asiento0Movimiento'+orden+'Haber').val(ley25413);
        }                        
    }
    if($('#1cuenta267').length > 0){
        //Imp a las Ganancias
        var orden = $('#1cuenta267').attr('orden');
        if($('#myModal #Asiento0Movimiento'+orden+'Id').val()==0){
            $('#myModal #Asiento0Movimiento'+orden+'Haber').val(percepciones);
        }                        
    }
    if($('#1cuenta268').length > 0){
        //Imp a las Ganancias
        var orden = $('#1cuenta268').attr('orden');
        if($('#myModal #Asiento0Movimiento'+orden+'Id').val()==0){
            $('#myModal #Asiento0Movimiento'+orden+'Haber').val(retenciones);
        }                        
    }
    if($('#1cuenta265').length > 0){
        //Imp a las Ganancias
        var orden = $('#1cuenta265').attr('orden');
        if($('#myModal #Asiento0Movimiento'+orden+'Id').val()==0){
            $('#myModal #Asiento0Movimiento'+orden+'Haber').val(anticiposacomputar);
        }                        
    }
    if($('#1cuenta275').length > 0){
        //Imp a las Ganancias
        var orden = $('#1cuenta275').attr('orden');
        if($('#myModal #Asiento0Movimiento'+orden+'Id').val()==0){
            $('#myModal #Asiento0Movimiento'+orden+'Haber').val(gananciaMinimaPresunta);
        }                        
    }
    if($('#1cuenta275').length > 0){
        //Imp a las Ganancias
        var orden = $('#1cuenta275').attr('orden');
        if($('#myModal #Asiento0Movimiento'+orden+'Id').val()==0){
            $('#myModal #Asiento0Movimiento'+orden+'Haber').val(gananciaMinimaPresunta);
        }                        
    }
    
    if($('#1cuenta1447').length > 0){
        //Imp a las Ganancias
        var orden = $('#1cuenta1447').attr('orden');
        if($('#myModal #Asiento0Movimiento'+orden+'Id').val()==0){            
            $('#myModal #Asiento0Movimiento'+orden+'Debe').val(apagar);
        }                        
    }
  
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
 	});		
}