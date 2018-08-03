$(document).ready(function() {
    $('.chosen-select').chosen(
        {
            search_contains:true,
            include_group_label_in_selected:true
        }
    );
});
function deletefile(name,cliid,folder,periodo) {
    var r = confirm("Esta seguro que desea eliminar este archivo?. Es una accion que no podra deshacer.");
    if (r == true) {
        var data = "";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL + "/conceptosrestantes/deletefile/" + name + "/" + cliid + "/" + folder+ "/" + periodo,
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
