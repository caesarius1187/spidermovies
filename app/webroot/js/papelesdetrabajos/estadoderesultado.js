/**
 * Created by caesarius on 04/01/2017.
 */
$(document).ready(function() {    
    
    $( "#clickExcel" ).click(function() {
        //showAllDivs();
        setTimeout(
        function() 
        {
           var table2excel = new Table2Excel();
            table2excel.export(document.querySelectorAll(".toExcelTable"));
            //CambiarTab("sumasysaldos");
        }, 2000);
        
    });
    var beforePrint = function() {
        $('#header').hide();
        $('#headerCliente').hide();
        $('#divTabs').hide();
        //showAllDivs();
        //tblsys.destroy();
        //$("#tableEvolucionPatrimonioNeto").css('font-size','11px');
        /*$("#tableEvolucionPatrimonioNeto").css('transform','rotate(270deg)');
        $("#tableEvolucionPatrimonioNeto").css('margin-left',' -250px');
        $("#tableEvolucionPatrimonioNeto").css('margin-top','250px');
        $("#tableEvolucionPatrimonioNeto").css('width','900px');
        
        //$("#divContenedorEvolucionPatrimonioNeto").css('height','100px')

        //$("#tblanexoIBienesdeuso").css('font-size','11px');
        $("#tblanexoIBienesdeuso").css('transform','rotate(270deg)');
        $("#tblanexoIBienesdeuso").css('margin-left',' -350px');
        $("#tblanexoIBienesdeuso").css('margin-top','430px');
        $("#tblanexoIBienesdeuso").css('width','900px');
        //$("#divContenedorAnexoIBienesdeUso").css('height','1050px')

        $("#tblEstadoSituacionPatrimonial").css('transform','rotate(270deg)');
        $("#tblEstadoSituacionPatrimonial").css('margin-left',' -250px');
        $("#tblEstadoSituacionPatrimonial").css('margin-top','330px');
        $("#tblEstadoSituacionPatrimonial").css('width','900px');
            */
          
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
        $('#headerCliente').show();
        C/ambiarTab("sumasysaldos");
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


    if(sTab == "sumasysaldos")
    {
        $("#tabSumasYSaldos").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").show();
        $("#divContenedorNotas").hide();
        $("#divContenedorAnexos").hide();
        $("#divContenedorEstadosResultados").hide();
        $("#divContenedorEvolucionPatrimonioNeto").hide();
        $("#divContenedorFlujoEfectivo").hide();
        $("#divContenedorNotaFlujoEfectivo").hide();
        $("#divContenedorSituacionPatrimonial").hide();
        $("#divContenedorNotaSituacionPatrimonial").hide();
        $("#divContenedorAnexoIBienesdeUso").hide();

    }
    if(sTab == "estadoderesultado")
    {
        $("#tabEstadoDeResultados").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divContenedorNotas").hide();
        $("#divContenedorAnexos").hide();
        $("#divContenedorEstadosResultados").show();
        $("#divContenedorEvolucionPatrimonioNeto").hide();
        $("#divContenedorFlujoEfectivo").hide();
        $("#divContenedorNotaFlujoEfectivo").hide();
        $("#divContenedorSituacionPatrimonial").hide();
        $("#divContenedorNotaSituacionPatrimonial").hide();
        $("#divContenedorAnexoIBienesdeUso").hide();
    }
    if (sTab == "notas")
    {

        $("#tabNotas").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divContenedorNotas").show();
        $("#divContenedorAnexos").hide();
        $("#divContenedorEstadosResultados").hide();
        $("#divContenedorEvolucionPatrimonioNeto").hide();
        $("#divContenedorFlujoEfectivo").hide();
        $("#divContenedorNotaFlujoEfectivo").hide();
        $("#divContenedorSituacionPatrimonial").hide();
        $("#divContenedorNotaSituacionPatrimonial").hide();
        $("#divContenedorAnexoIBienesdeUso").hide();
    }
    if (sTab == "anexos")
    {

        $("#tabAnexos").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divContenedorNotas").hide();
        $("#divContenedorAnexos").show();
        $("#divContenedorEstadosResultados").hide();
        $("#divContenedorEvolucionPatrimonioNeto").hide();
        $("#divContenedorFlujoEfectivo").hide();
        $("#divContenedorNotaFlujoEfectivo").hide();
        $("#divContenedorSituacionPatrimonial").hide();
        $("#divContenedorNotaSituacionPatrimonial").hide();
        $("#divContenedorAnexoIBienesdeUso").hide();
    }
    if (sTab == "patrimonioneto")
    {

        $("#tabEvolucionPatrimonioNeto").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divContenedorNotas").hide();
        $("#divContenedorAnexos").hide();
        $("#divContenedorEstadosResultados").hide();
        $("#divContenedorEvolucionPatrimonioNeto").show();
        $("#divContenedorFlujoEfectivo").hide();
        $("#divContenedorNotaFlujoEfectivo").hide();
        $("#divContenedorSituacionPatrimonial").hide();
        $("#divContenedorNotaSituacionPatrimonial").hide();
        $("#divContenedorAnexoIBienesdeUso").hide();
    }
    if (sTab == "flujoefectivo")
    {

        $("#tabFlujoEfectivo").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divContenedorNotas").hide();
        $("#divContenedorAnexos").hide();
        $("#divContenedorEstadosResultados").hide();
        $("#divContenedorEvolucionPatrimonioNeto").hide();
        $("#divContenedorFlujoEfectivo").show();
        $("#divContenedorNotaFlujoEfectivo").hide();
        $("#divContenedorSituacionPatrimonial").hide();
        $("#divContenedorNotaSituacionPatrimonial").hide();
        $("#divContenedorAnexoIBienesdeUso").hide();
    }
    if (sTab == "notaflujoefectivo")
    {
        $("#tabNotaFlujoEfectivo").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divContenedorNotas").hide();
        $("#divContenedorAnexos").hide();
        $("#divContenedorEstadosResultados").hide();
        $("#divContenedorEvolucionPatrimonioNeto").hide();
        $("#divContenedorFlujoEfectivo").hide();
        $("#divContenedorNotaFlujoEfectivo").show();
        $("#divContenedorSituacionPatrimonial").hide();
        $("#divContenedorNotaSituacionPatrimonial").hide();
        $("#divContenedorAnexoIBienesdeUso").hide();
    }
    if (sTab == "situacionpatrimonial")
    {
        $("#tabEvolucionSitacionPatrimonial").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divContenedorNotas").hide();
        $("#divContenedorAnexos").hide();
        $("#divContenedorEstadosResultados").hide();
        $("#divContenedorEvolucionPatrimonioNeto").hide();
        $("#divContenedorFlujoEfectivo").hide();
        $("#divContenedorNotaFlujoEfectivo").hide();
        $("#divContenedorSituacionPatrimonial").show();
        $("#divContenedorNotaSituacionPatrimonial").hide();
        $("#divContenedorAnexoIBienesdeUso").hide();
    }
    if (sTab == "notassituacionpatrimonial")
    {
        $("#tabEvolucionNotasSitacionPatrimonial").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divContenedorNotas").hide();
        $("#divContenedorAnexos").hide();
        $("#divContenedorEstadosResultados").hide();
        $("#divContenedorEvolucionPatrimonioNeto").hide();
        $("#divContenedorFlujoEfectivo").hide();
        $("#divContenedorNotaFlujoEfectivo").hide();
        $("#divContenedorSituacionPatrimonial").hide();
        $("#divContenedorNotaSituacionPatrimonial").show();
        $("#divContenedorAnexoIBienesdeUso").hide();
    }
    if (sTab == "anexoibienesdeuso")
    {
        $("#tabEvolucionAnexoIBienesdeUso").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divContenedorNotas").hide();
        $("#divContenedorAnexos").hide();
        $("#divContenedorEstadosResultados").hide();
        $("#divContenedorEvolucionPatrimonioNeto").hide();
        $("#divContenedorFlujoEfectivo").hide();
        $("#divContenedorNotaFlujoEfectivo").hide();
        $("#divContenedorSituacionPatrimonial").hide();
        $("#divContenedorNotaSituacionPatrimonial").hide();
        $("#divContenedorAnexoIBienesdeUso").show();
    }
}
function imprimir(){
       window.print();
       }