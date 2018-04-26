/**
 * Created by caesarius on 04/01/2017.
 */
$(document).ready(function() {    
    $( "#clickExcel" ).click(function() {
        setTimeout(
        function() 
        {
           var table2excel = new Table2Excel();
            table2excel.export(document.querySelectorAll(".toExcelTable"));
            //CambiarTab("sumasysaldos");
        }, 2000);
    });
     $('.chosen-select').chosen({search_contains:true});
});
function showhideBase(){
    var cantActividades = $("#cantactividades").val();
    $(".hiddable").each(function(){
        var mtcolspan = $(this).attr('mycolspan');
        if(mtcolspan!=null){
            if($(this).attr('mycolspan')==$(this).attr('colspan')){
                //tiene el tamaño original
                $(this).attr('colspan',1);
            }else{
                //tiene el tamaño reducido
                $(this).attr('colspan',mtcolspan);
            }
        }else{
            if($(this).is(":visible")){
                $(this).hide();
            }else{
                $(this).show();
            }
       }
    })
    
}