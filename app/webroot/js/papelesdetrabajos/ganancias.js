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
        showAllDivs();
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
        $('a').show();
        $('#divTabs').show();
        CambiarTab("sumasysaldos");
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
    $(".inputDebe").each(function () {
        debesubtotal += $(this).val()*1;        
    });
    $("#lblTotalDebe").text(parseFloat(debesubtotal).toFixed(2)) ;    
    showIconDebeHaber()
    if($('#cuenta5').length > 0){
        var orden = $('#cuenta5').attr('orden');        
        $('#Asiento0Movimiento'+orden+'Haber').val(debesubtotal);
        $('#Asiento0Movimiento'+orden+'Haber').trigger("change");
    }
    if($('#cuenta1069').length > 0){
        var orden = $('#cuenta1069').attr('orden');        
        $('#Asiento0Movimiento'+orden+'Haber').val(debesubtotal);
        $('#Asiento0Movimiento'+orden+'Haber').trigger("change");
    }
}
function addTolblTotalhaberAsieto(event) {
    //        $("#lblTotalAFavor").val(0) ;
    var habersubtotal = 0;
    $(".inputHaber").each(function () {
        habersubtotal += $(this).val()*1;
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
function catchAsientoDeduccionGeneral(){
    $('#AsientoAddForm').submit(function(){
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