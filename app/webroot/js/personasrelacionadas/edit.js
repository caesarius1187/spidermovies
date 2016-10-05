$(document).ready(function() {
    $( "input.datepicker" ).datepicker({
        yearRange: "-100:+50",
        changeMonth: true,
        changeYear: true,
        constrainInput: false,
        dateFormat: 'dd-mm-yy',
    });
   
});
