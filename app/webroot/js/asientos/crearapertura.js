/**
 * Created by caesarius on 04/01/2017.
 */
$(document).ready(function() {    
    $('.chosen-select-cuenta').chosen(
        {
            search_contains:true,
            include_group_label_in_selected:true
        }
    );
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
    CambiarTab("asientoapertura");
    corregirAsientoApertura();
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
    catchFormAsiento("AsientoAddForm");
});
function corregirAsientoApertura(){
    //primero vamos a mover los inicial a los final
    if($('#cuenta358').length > 0){
        //110500013 Mercaderías XX E Final a 110500011 Mercaderías XX E Inicial,
        var orden = $('#cuenta358').attr('orden');        
        var final = $('#Asiento0Movimiento'+orden+'Debe').val();
        if($('#cuenta356').length > 0){
            var orden2 = $('#cuenta356').attr('orden');        
            $('#Asiento0Movimiento'+orden2+'Debe').val(final);
            $('#Asiento0Movimiento'+orden+'Debe').val(0);
        }
    }
    if($('#cuenta363').length > 0){
        //110502013 Prod. Terminado XX E Final a 110502011 Prod. Terminado XX E Inicial,
        var orden = $('#cuenta363').attr('orden');        
        var final = $('#Asiento0Movimiento'+orden+'Debe').val();
        if($('#cuenta361').length > 0){
            var orden2 = $('#cuenta361').attr('orden');        
            $('#Asiento0Movimiento'+orden2+'Debe').val(final);
        }
    }
    if($('#cuenta368').length > 0){
        //110504013 Prod. en Proceso XX E Final a 110504011 Prod. en Proceso XX E Inicial,
        var orden = $('#cuenta368').attr('orden');        
        var final = $('#Asiento0Movimiento'+orden+'Debe').val();
        if($('#cuenta366').length > 0){
            var orden2 = $('#cuenta366').attr('orden');        
            $('#Asiento0Movimiento'+orden2+'Debe').val(final);
        }
    }
    if($('#cuenta3727').length > 0){
        //110506013 MP y Materiales XX E Final a 110506011 MP y Materiales XX E Inicial,
        var orden = $('#cuenta3727').attr('orden');        
        var final = $('#Asiento0Movimiento'+orden+'Debe').val();
        if($('#cuenta371').length > 0){
            var orden2 = $('#cuenta371').attr('orden');        
            $('#Asiento0Movimiento'+orden2+'Debe').val(final);
        }
    }
    if($('#cuenta373').length > 0){
        //110506013 MP y Materiales XX E Final a 110506011 MP y Materiales XX E Inicial,
        var orden = $('#cuenta373').attr('orden');        
        var final = $('#Asiento0Movimiento'+orden+'Debe').val();
        if($('#cuenta3550').length > 0){
            var orden2 = $('#cuenta3550').attr('orden');        
            $('#Asiento0Movimiento'+orden2+'Debe').val(final);
        }
    }
    
    if($('#cuenta2212').length > 0){
        //420300001 Rtdos. No Asignados
        var orden = $('#cuenta2212').attr('orden');        
        $('#Asiento0Movimiento'+orden+'Debe').val(0);        
        $('#Asiento0Movimiento'+orden+'Haber').val(0);        
        var debesubtotal=0;
        $(".inputDebe").each(function () {
            debesubtotal = debesubtotal*1 + this.value*1;
        });
        var habersubtotal=0;
        $(".inputHaber").each(function () {
            habersubtotal = habersubtotal*1 + this.value*1;
        });
        var diferencia = debesubtotal - habersubtotal;
        diferencia = diferencia.toFixed(2);
        if(diferencia>0){
            $('#Asiento0Movimiento'+orden+'Debe').val(0);        
            $('#Asiento0Movimiento'+orden+'Haber').val(diferencia);        
        }else{
            $('#Asiento0Movimiento'+orden+'Debe').val(diferencia*-1);        
            $('#Asiento0Movimiento'+orden+'Haber').val(0);        
        }        
    }
}
function showAllDivs(){
    $("#divContenedorBSyS").show();
    $("#divAsientoApertura").show();    
}
function CambiarTab(sTab)	{
    $("#tabSumasYSaldos").attr("class", "cliente_view_tab");
    $("#tabAsientoApertura").attr("class", "cliente_view_tab");    

    if(sTab == "sumasysaldos")
    {
        $("#tabSumasYSaldos").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").show();
        $("#divAsientoApertura").hide();
    }
    if(sTab == "asientoapertura")
    {
        $("#tabAsientoApertura").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divAsientoApertura").show();        
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
                // $('#myModal').modal('hide');
                // $('#myModalFormAgregarAsiento').modal('hide');
                callAlertPopint(respuesta.respuesta);
                location.reload();
            },
            error: function(xhr,textStatus,error){
                // $('#myModal').modal('hide');
                // $('#myModalFormAgregarAsiento').modal('hide');
                callAlertPopint(error);
                alert(textStatus);
            }
        });
        $("#rowdecarga :input").prop("disabled", false);
        return false;
    });
}