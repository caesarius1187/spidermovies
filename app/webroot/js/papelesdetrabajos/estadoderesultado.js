/**
 * Created by caesarius on 04/01/2017.
 */
$(document).ready(function() {
    $( "#clickExcel" ).click(function() {
        $("#tblsys").table2excel({
            // exclude CSS class
            exclude: ".noExl",
            name: "Conveniomultilateral",
            filename:$('#clientenombre').val()+"-"+ $('#periodo').val()+"-"+"IVA"
        });
    });
    var beforePrint = function() {
        $('#header').hide();
        $('button').hide();
        $('a').hide();

    };
    var afterPrint = function() {
        $('#header').show();
        $('button').show();
        $('a').show();
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
    var tblsys = $('#tblsys').dataTable().api();
    CambiarTab("sumasysaldos");
});
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