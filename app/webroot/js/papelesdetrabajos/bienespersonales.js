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
    $("#tblsys tr").each(function(){
        if($(this).hasClass('trclickeable')){
            $(this).dblclick(function () {
                //vamos a preguntar si es visible el Div que tenemos que mostrar para no recibir el click si no se tiene el foco

                var cuecliid = $(this).attr('cuecliid');
                var saldoactual = $(this).attr('saldoactual');
                var cliid = $("#cliid").val();
                var periodo = $("#periodo").val();
                var data = "";
                $.ajax({
                    type: "GET",  // Request method: post, get
                    url: serverLayoutURL+"/bienespersonales/add/"+cliid+"/"+periodo+"/"+cuecliid+"/"+saldoactual, // URL to request
                    data: data,  // post data
                    success: function(response) {
                        $('#myModal').on('show.bs.modal', function() {
                            if ($('#myModal').is(":visible")){
                                return;
                            }
                            $('#myModal').find('.modal-title').html('Libro Mayor');
                            $('#myModal').find('.modal-body').html(response);
                            catchFormAsiento("FormAgregarBienesPersonales");
                            // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                        });
                        $('#myModal').modal('show');
                    },
                    error:function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(textStatus);
                    }
                });
            });
        }
    });
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
});
function showAllDivs(){
    $("#divContenedorBSyS").show();
}
function CambiarTab(sTab)	{
    $("#tabSumasYSaldos").attr("class", "cliente_view_tab");

    if(sTab == "sumasysaldos")
    {
        $("#tabSumasYSaldos").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").show();
       
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
                    $('#myModal').find('.modal-body').prepend(divRespueta);
                    $("#myModal").scrollTop(0);
                }else{
                    $("#bienespersonales"+respuesta.bienespersonale.Bienespersonale.cuentascliente_id).html(
                            respuesta.bienespersonale.Bienespersonale.monto
                            );
                    $("#exento"+respuesta.bienespersonale.Bienespersonale.cuentascliente_id).html(
                            respuesta.bienespersonale.Bienespersonale.exento
                            );
                    $('#myModal').modal('hide');
                    callAlertPopint(respuesta.respuesta);
                }
            },
            error: function(xhr,textStatus,error){
                $('#myModal').modal('hide');
                callAlertPopint(error);
                alert(textStatus);
            }
        });
        return false;
    });
}
function addTolblTotalDebeAsieto(event) {
    var debesubtotal = 0;
    var habersubtotal = 0;
     if($('#cuenta2224').length > 0){         
         var orden = $('#cuenta2224').attr('orden');
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

function addTolblTotalDebeAsietoModal(event) {
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
    showIconDebeHaberModal();
}
function addTolblTotalhaberAsietoModal(event) {
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
    showIconDebeHaberModal();
}
function showIconDebeHaberModal(){
    if($("#myModal #lblTotalHaber").text()==$("#myModal #lblTotalDebe").text()){
        $("#myModal #iconDebeHaber").attr('src',serverLayoutURL+'/img/test-pass-icon.png');
    }else{
        $("#myModal #iconDebeHaber").attr('src',serverLayoutURL+'/img/test-fail-icon.png');
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
