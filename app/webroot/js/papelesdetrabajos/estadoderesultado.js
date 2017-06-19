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
    // $("#tblsys tr").each(function(){
    //     if($(this).hasClass('trclickeable')){
    //         $(this).dblclick(function () {
    //             //vamos a preguntar si es visible el Div que tenemos que mostrar para no recibir el click si no se tiene el foco
    //             var cuecliid = $(this).attr('cuecliid');
    //             var cliid = $("#cliid").val();
    //             var periodo = $("#periodo").val();
    //             var data = "";
    //             $.ajax({
    //                 type: "post",  // Request method: post, get
    //                 url: serverLayoutURL+"/asientos/index/"+cliid+"/"+periodo+"/"+cuecliid, // URL to request
    //                 data: data,  // post data
    //                 success: function(response) {
    //
    //                     $('#myModal').on('show.bs.modal', function() {
    //                         if ($('#myModal').is(":visible")){
    //                             return;
    //                         }
    //                         $('#myModal').find('.modal-title').html('Asientos de la cuenta');
    //                         $('#myModal').find('.modal-body').html(response);
    //                         // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
    //                     });
    //                     $('#myModal').modal('show');
    //                     $("#tblListaMovimientos").DataTable();
    //                     $("#myModal #cargarAsiento").hide();
    //                     $("#tblAsientos").DataTable();
    //                     $('.my-div').css('height', window.innerHeight);
    //                 },
    //                 error:function (XMLHttpRequest, textStatus, errorThrown) {
    //                     alert(textStatus);
    //                 }
    //             });
    //         });
    //     }
    // });
    var tblsys = $('#tblsys').dataTable().api();
    CambiarTab("sumasysaldos");
});
function CambiarTab(sTab)	{
    $("#tabSumasYSaldos").attr("class", "cliente_view_tab");
    $("#tabEstadoDeResultados").attr("class", "cliente_view_tab");
    $("#tabNotas").attr("class", "cliente_view_tab");
    $("#tabAnexos").attr("class", "cliente_view_tab");

    if(sTab == "sumasysaldos")
    {
        $("#tabSumasYSaldos").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").show();
        $("#divContenedorNotas").hide();
        $("#divContenedorAnexos").hide();
        $("#divContenedorEstadosResultados").hide();
    }
    if(sTab == "estadoderesultado")
    {
        $("#tabEstadoDeResultados").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divContenedorNotas").hide();
        $("#divContenedorAnexos").hide();
        $("#divContenedorEstadosResultados").show();
    }
    if (sTab == "notas")
    {

        $("#tabNotas").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divContenedorNotas").show();
        $("#divContenedorAnexos").hide();
        $("#divContenedorEstadosResultados").hide();
    }
    if (sTab == "anexos")
    {

        $("#tabAnexos").attr("class", "cliente_view_tab_active");
        $("#divContenedorBSyS").hide();
        $("#divContenedorNotas").hide();
        $("#divContenedorAnexos").show();
        $("#divContenedorEstadosResultados").hide();
    }
}
function imprimir(){
    window.print();
}