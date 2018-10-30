var form_empleadoHTML = "";

jQuery(document).ready(function($) {
    $.noConflict();  //Not to conflict with other scripts
    reloadDatePickers();
    $('.chosen-select').chosen({search_contains:true});
});
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
        $( "input.datepicker-year" ).datepicker({
                                    yearRange: "-100:+50",
                                    changeMonth: false,
                                    changeYear: true,
                                    constrainInput: false,
                                    dateFormat: 'yy',
                                }); 
    });     
}
