/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 $.fn.filterGroups = function( options ) {
    var settings = $.extend( {}, options);

    return this.each(function(){

        var $select = $(this);
        // Clone the optgroups to data, then remove them from dom
        $select.data('fg-original-groups', $select.find('optgroup').clone()).children('optgroup').remove();

        $(settings.groupSelector).change(function(){
            var $this = $(this);
            var $optgroup_label = $(this).find('option:selected').text();
            var $optgroup =  $select.data('fg-original-groups').filter('optgroup[label="' + $optgroup_label + '"]').clone();
            $select.children('optgroup').remove();
            $select.append($optgroup);
        }).change();
    });    
};
jQuery(document).ready(function($) {
    
    $('.cargo').each(function(){
        var orden = $(this).attr('orden');
        var selectoConvenio = '#Empleado'+orden+'ConveniocolectivotrabajoId';
        $(this).filterGroups({groupSelector: selectoConvenio, });
    })
     
     reloadDatePickers();
     $(".autoselect").each(function(){
        //$(this).find('option[text="'+$(this).attr( "defaultoption" )+'"]').val();
        var defaultoption = $(this).attr( "defaultoption" );
        /*$('#'+this.id+' option').filter(function () {
            return $(this).html() == defaultoption;
        }).val();*/
        $('#'+this.id+' option:contains("' + defaultoption + '")').prop('selected', true);

        var attr = $(this).attr('defaultoptionlocalidade');
        if (typeof attr !== typeof undefined && attr !== false) {
            $('#puntosdeventasdomicilio').val(attr);
            var defaultlocalidad = $( "#puntosdeventasdomicilio option:selected" ).text();
            $(this).val(defaultlocalidad);
        }
    });
    $('.chosen-select').chosen(
        {
            search_contains:true,
            include_group_label_in_selected:true
        }
    );
    $('#EmpleadoImportar').submit(function(){
        //serialize form data
        var formData = $(this).serialize();
        //get form action
        var formUrl = $(this).attr('action');
        $("#EmpleadoImportarAEnviar #Empleado0Jsonencript").val(formData);
        var formJsonData = $("#EmpleadoImportarAEnviar").serialize();
        $.ajax({
            type: 'POST',
            url: formUrl,
            data: formJsonData,
            success: function(data,textStatus,xhr){
                var midata = jQuery.parseJSON(data);
                callAlertPopint(midata.respuesta);
                var cliid = $("#EmpleadoCliid").val();
                callAlertPopint('Recargando formulario por favor espere');
                window.location.reload(true);
                setTimeout(function(){
                    window.location.reload(true);
                },4000);
            },
            error: function(xhr,textStatus,error){
                callAlertPopint(textStatus);
            }
        });
        return false;
    });
    agregarApplyToAllInFirstRow();
});
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
}
function deletefile(name,cliid,folder) {
    var r = confirm("Esta seguro que desea eliminar este archivo?. Es una accion que no podra deshacer.");
    if (r == true) {
        var data = "";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL + "/empleados/deletefile/" + name + "/" + cliid + "/" + folder,
            // URL to request
            data: data,  // post data
            success: function (response) {
                location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                callAlertPopint(textStatus);
            }
        });
    }
}
function agregarApplyToAllInFirstRow(){
    /*primero tengo que remover todos los tooltips que estaban creados*/
    $('.aplicableATodos').each(function(){
        $(this).removeClass('tooltip');
    });
    $('.aplicableATodos.tooltiptext').each(function(){
        $(this).remove();
    });

    /*ahora agregamos los nuevos*/
    var row = $('#tblAddEmpleados').find('tr');
    $(row).find('.aplicableATodos').each(function(){
        //$(this).css('background','blue');
        var myselect = $(this).attr('id');
        var span = $('<span />')
            .attr('class', 'tooltiptext')
            .html(
                $('<input />',{
                    'type':'button',
                    'value':"Aplicar a Todos los visibles",
                    'onclick':"aplicarATodos('"+myselect+"')"
                })
            );
        $(this).closest('div')
            .addClass( "tooltip" )
            .append(
                span
            );
    });
}
function aplicarATodos(miinput){
    var valueAAplicar = $('#'+miinput).val();
    var inputclass = $('#'+miinput).attr('inputclass');
    $('select[inputclass="'+inputclass+'"]').each(function() {
        if($(this).is(":visible")){
            $(this).val(valueAAplicar).trigger('chosen:updated');;
        }else if($(this).hasClass('chosen-select')){
            if($('#'+$(this).attr('id')+'_chosen').is(":visible")) {
                $(this).val(valueAAplicar).trigger("chosen:updated");
            }
        }
        //si es clase inputactividad vamos a hacer un trigger change
        if($(this).hasClass('inputactividad')){
            $(this).trigger("change");
        }
    });
}