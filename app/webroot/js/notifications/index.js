$(document).ready(function() {
   

    
    $('#tblNotifications tbody').on( 'click', 'tr', function () {
       var tipoNotificacion = $(this).attr('action');       
       var impid = $(this).attr('impid');
       var cliid = $(this).attr('cliid');
       var periodoMes = $(this).attr('periodoMes');
       var periodoAno = $(this).attr('periodoAno');
       var notid = $(this).attr('notid');
       
        switch (tipoNotificacion) {
            case 'vencimiento':
                $("#clientesPeriodomes").val(periodoMes);
                $("#clientesPeriodoanio").val(periodoAno);
                $("#clientesLclis").val(cliid);
                $("#clientesSelectby").val('clientes');
                $("#clientesAvanceForm").submit();
                break;
                
            default:
                
                break;
        }
      
        setNotificationAsReaded(notid,1);
    } );
  

    /*Cargar Asientos de Venta automaticos*/
    function pad (str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }
});
function setNotificationAsReaded(notid,readed){
  $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/notifications/setNotificationReaded/"+notid+"/"+readed,
        // URL to request
        data: "",  // post data
        success: function(response) {
             $("#trNotification"+notid).addClass('notificationReaded');   
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
                callAlertPopint(textStatus);
                callAlertPopint(XMLHttpRequest);
                callAlertPopint(errorThrown);
        }
    });
}
