function viewPost(postId){
       var data = "id="+ postId;
       $.ajax({
             type: "post",  // Request method: post, get
             url: serverLayoutURL+"/clientes/impclis/", // URL to request
             data: data,  // post data
             success: function(response) {
                                  document.getElementById("impxcli").innerHTML = response;
                           },
                           error:function (XMLHttpRequest, textStatus, errorThrown) {
                                  alert(textStatus);
                           }
          });
          return false;
}