$(document).ready(function() {
    reloadDatePickers();
    var iTableHeight = $(window).height();
    iTableHeight = (iTableHeight < 200) ? 200 : (iTableHeight - 320);
    //alert(iTableHeight)
    $("#TablaListaPlanesDePago").dataTable( { 
        "sPaginationType": "full_numbers",
        "sScrollY": iTableHeight + "px",
        "bScrollCollapse": true,
        "iDisplayLength":50,
    }); 

    $('#PlandepagoAddForm').submit(function(){ 
        //serialize form data 
        var formData = $(this).serialize(); 
        //get form action 
        var formUrl = $(this).attr('action'); 
        $.ajax({ 
            type: 'POST', 
            url: formUrl, 
            data: formData, 
            success: function(data,textStatus,xhr){ 
                    var mirespuesta = jQuery.parseJSON(data);
                    if(mirespuesta.error==0){
                        location.reload();
                    }else{
                        callAlertPopint(mirespuesta.resultado);
                    }
                }, 
            error: function(xhr,textStatus,error){ 
                callAlertPopint(textStatus); 
            } 
        }); 
        return false; 
    });
    $('#PlandepagoIndexForm').change(function(){
        $('#divPlandepagoAddForm').hide();
    });
    $('.chosen-select').chosen({search_contains:true});
});
function pagarPlandePago(pdpid){
    var data ="";
    $.ajax({
        type: "GET",  // Request method: post, get
        url: serverLayoutURL+"/plandepagos/edit/"+pdpid,

        // URL to request
        data: data,  // post data
        success: function(data,textStatus,xhr){ 
                $('#divPagarPlandePago').html(data);
                 reloadDatePickers();
                 $('#PlandepagoEditForm').submit(function(){ 
                        //serialize form data 
                        var formData = $(this).serialize(); 
                        //get form action 
                        var formUrl = $(this).attr('action'); 
                        $.ajax({ 
                            type: 'POST', 
                            url: formUrl, 
                            data: formData, 
                            success: function(data,textStatus,xhr){ 
                                    var mirespuesta = jQuery.parseJSON(data);
                                    if(mirespuesta.error==0){
                                        location.reload();
                                    }else{
                                        callAlertPopint(mirespuesta.resultado);
                                    }
                                }, 
                            error: function(xhr,textStatus,error){ 
                                callAlertPopint(textStatus); 
                            } 
                        }); 
                        return false; 
                    });
                location.href='#popinPagarPlandePago'
            }, 
        error: function(xhr,textStatus,error){ 
            callAlertPopint(textStatus); 
        } 
    }); 
}
function reloadDatePickers(){
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
        changeYear: true,
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
}	