var tblAsientos;
$(document).ready(function() {
 
    var nombrecliente = $('#clientenombre').val();
    var periodo = $('#periododefault').val();
    var peranio = $('#peranio').val();
    reloadInputDates();
    $("#liinformes").addClass("active");
    $( "#clickExcel" ).click(function() {  
       $("#tblLibroDiario").table2excel({
        // exclude CSS class
        exclude: ".noExl",
        name: "LibroDiario",
        filename: "LibroDiario"
      }); 
    });
    $('.chosen-select').chosen({search_contains:true});
    var beforePrint = function() {
        console.log('Functionality to run before printing.');
        $('#header').hide();
        $('#headerCliente').hide();
        $('#Formhead').hide();
    };

    var afterPrint = function() {
        console.log('Functionality to run after printing');
        $('#index').css('font-size','12px');
        $('#header').show();
        $('#headerCliente').show();
        $('#Formhead').show();
        //$('#tblLibroDiario').css('padding','0px 10%');
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
            if( $('#peranio').length>0){
                 $("input.datepickerOneYear").datepicker({ 
                    minDate: new Date($('#peranio').val()-1, 12, 1), 
                    dateFormat: 'dd-mm-yy',
                    maxDate: new Date($('#peranio').val(), 11, 31),
                    changeMonth: true,
                    changeYear: true,
                    constrainInput: false,
                    defaultDate: d,
                 });
            }else{
                $( "input.datepicker-dia" ).datepicker({
                    yearRange: "-1:+1",
                    changeMonth: false,
                    changeYear: false,
                    constrainInput: false,
                    dateFormat: 'dd',
                    defaultDate: d,
                });
            }
        })(jQuery);
    }
});
function imprimir(){

    window.print();

}
