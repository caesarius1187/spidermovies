$(document).ready(function() {
    $("#liinformes").addClass("active");
    $( "#clickExcel" ).click(function() {  
       $("#situacionIntegral").table2excel({
        // exclude CSS class
        exclude: ".noExl",
        name: "InformeTributarioFinanciero",
        filename: "InformeTributarioFinanciero"
      }); 
    });
    $('.chosen-select').chosen({search_contains:true});
    $('.rowMovimientoCliente').each(function(i,obj){
        $(obj).insertBefore($(obj).prev());
    });
    var beforePrint = function() {
        console.log('Functionality to run before printing.');
        $('#header').hide();
        $('#Formhead').hide();
        $('#index').css('float','left');
        $('#padding').css('padding','0px');
        $('#index').css('font-size','10px');
        $('#index').css('border-color','#FFF');
        $('#situacionIntegral').css('padding','0px');
    };

    var afterPrint = function() {
        console.log('Functionality to run after printing');
        $('#index').css('font-size','14px');
        $('#header').show();
        $('#Formhead').show();
        $('#index').css('float','right');
        $('#padding').css('padding','10px 1%');
        $('#situacionIntegral').css('padding','0px 10%');
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
});
function imprimir(){

    window.print();

}
