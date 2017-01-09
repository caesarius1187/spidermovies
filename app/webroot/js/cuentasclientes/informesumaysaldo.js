/**
 * Created by caesarius on 04/01/2017.
 */
$(document).ready(function() {
    $("#tblsys tr").each(function(){
        if($(this).hasClass('trclickeable')){
            $(this).dblclick(function () {
                var cuecliid = $(this).attr('cuecliid');
                var cliid = $("#cliid").val();
                var periodo = $("#periodo").val();
                var data = "";
                $.ajax({
                    type: "post",  // Request method: post, get
                    url: serverLayoutURL+"/asientos/index/"+cliid+"/"+periodo+"/"+cuecliid, // URL to request
                    data: data,  // post data
                    success: function(response) {
                        $('#myModal').on('show.bs.modal', function() {
                            $('#myModal').find('.modal-title').html('Asientos de la cuenta');
                            $('#myModal').find('.modal-body').html(response);
                            // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                        });
                        $('#myModal').modal('show');
                        $("#tblListaMovimientos").DataTable();
                        
                    },
                    error:function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(textStatus);
                    }
                });
            });
        }
    });
});