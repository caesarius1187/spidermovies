$(document).ready(function() { 
    $("#ligestion").addClass("active"); 
    var $table = $('#tbl_tareas');
    // $table.floatThead();
    var clientesAvanceForm =  $('#clientesAvanceForm');

    $( "input.datepicker" ).datepicker({
      yearRange: "-100:+50",
      changeMonth: true,
      changeYear: true,
      constrainInput: false,
      dateFormat: 'dd-mm-yy'
    });
    /*Comportamiento del los filtros*/
    $( "#clientesLclis" ).change(function(){
        if( $('#clientesLclis').val() ) {
            $("#clientesSelectby").val("clientes");
        }else{
            $("#clientesSelectby").val("todos");
        }
        $('#clientesFiltrodeimpuestos').val('').trigger('chosen:updated');
        $('#clientesGclis').val('').trigger('chosen:updated');
        $('#clientesFiltrodesolicitar').val('').trigger('chosen:updated');
        $('.filtroAvance').each(function(){
            if($(this).val()!=''){
                $(this).closest('div').addClass("filtroActive");
            }else{
                $(this).closest('div').removeClass("filtroActive");
            }
        });
    });
    $( "#clientesPeriodomes" ).change(function(){
        clientesAvanceForm.submit();
    });
    $( "#clientesPeriodoanio" ).change(function(){
        clientesAvanceForm.submit();
    });
    $( "#clientesGclis" ).change(function(){
        if( $('#clientesGclis').val() ) {
          $("#clientesSelectby").val("grupos");
        }else{
          $("#clientesSelectby").val("todos");
        }
        $('#clientesFiltrodeimpuestos').val('').trigger('chosen:updated');
        $('#clientesLclis').val('').trigger('chosen:updated');
        $('#clientesFiltrodesolicitar').val('').trigger('chosen:updated');
      $('.filtroAvance').each(function(){
          if($(this).val()!=''){
              $(this).closest('div').addClass("filtroActive");
          }else{
              $(this).closest('div').removeClass("filtroActive");
          }
      });
    });
    $( "#clientesFiltrodeimpuestos" ).change(function(){
        if( $('#clientesFiltrodeimpuestos').val() ) {
          $("#clientesSelectby").val("impuestos");
        }else{
          $("#clientesSelectby").val("todos");
        }
        $('#clientesGclis').val('').trigger('chosen:updated');
        $('#clientesLclis').val('').trigger('chosen:updated');
        $('#clientesFiltrodesolicitar').val('').trigger('chosen:updated');
      $('.filtroAvance').each(function(){
          if($(this).val()!=''){
              $(this).closest('div').addClass("filtroActive");
          }else{
              $(this).closest('div').removeClass("filtroActive");
          }
      });
    });
    $( "#clientesFiltrodesolicitar" ).change(function(){
            if( $('#clientesFiltrodesolicitar').val() ) {
              $("#clientesSelectby").val("solicitar");
            }else{
              $("#clientesSelectby").val("todos");
            }
            $('#clientesGclis').val('').trigger('chosen:updated');
            $('#clientesLclis').val('').trigger('chosen:updated');
            $('#clientesFiltrodeimpuestos').val('').trigger('chosen:updated');
          $('.filtroAvance').each(function(){
              if($(this).val()!=''){
                  $(this).closest('div').addClass("filtroActive");
              }else{
                  $(this).closest('div').removeClass("filtroActive");
              }
          });
      });
    $('.chosen-select').chosen({search_contains:true});
    $('.paging span a').click(function() {
      var uri = this.href; 
      var uriA = uri.split(":");
      var page = uriA[uriA.length-1];
        clientesAvanceForm.attr('action', clientesAvanceForm.attr('action')+'/page:'+page);
        clientesAvanceForm.submit();
      return false; 
    });    
    periodoSel = $('#periodoSel').val();
});
    var periodoSel;
/* 0 Mostrar PopIn NoHabilitado */
    function noHabilitado(texto){
    if(texto!=null){
      callAlertPopint(texto);
    }else{
      callAlertPopint('Usted no posee permisos para realizar esta tarea. En la seccion Parametros/Tareas podra habilitar la tarea.');
    }
  }
/* 3  ver Tarea Cargar -- Abrir el formulario para cargar Ventas Compras y Novedades*/
    function verFormCargarVentas(clienteid,periodoSel){
      window.open(
        serverLayoutURL+"/ventas/cargar/"+clienteid+"/"+periodoSel,
        serverLayoutURL+"/ventas/cargar/"+clienteid+"/"+periodoSel
      );
    }
    function verFormCargarCompras(clienteid,periodoSel){
      window.open(
        serverLayoutURL+"/compras/cargar/"+clienteid+"/"+periodoSel,
        serverLayoutURL+"/compras/cargar/"+clienteid+"/"+periodoSel
      );
    }
    function verFormCargarPagosacuentas(clienteid,periodoSel){
      window.open(
        serverLayoutURL+"/conceptosrestantes/cargar/"+clienteid+"/"+periodoSel,
        serverLayoutURL+"/conceptosrestantes/cargar/"+clienteid+"/"+periodoSel
      );
    }
    function verFormCargarNovedades(clienteid,periodoSel){
      window.open(
        serverLayoutURL+"/empleados/cargamasiva/"+clienteid+"/"+periodoSel,
        serverLayoutURL+"/empleados/cargamasiva/"+clienteid+"/"+periodoSel
      );
    }
    function verFormCargarBancos(clienteid,periodoSel){
      window.open(
        serverLayoutURL+"/movimientosbancarios/cargar/"+clienteid+"/"+periodoSel,
        serverLayoutURL+"/movimientosbancarios/cargar/"+clienteid+"/"+periodoSel
      );
  }
/* 5  ver Tarea Papeles de Trabajo -- Mostrar el formulario para papeles de trabajo del impcli del periodo*/
    function papelesDeTrabajo(periodo,impcli){
        var data = "";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/eventosimpuestos/getpapelestrabajo/"+periodo+"/"+impcli, // URL to request
            data: data,  // post data
            success: function(response) {
                //alert(response);
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModal').find('.modal-title').html('Liquidacion');
                    $('#myModal').find('.modal-body').html(response);
                    $('#myModal').find('.modal-content')
                        .css({
                            width: 'max-content',
                            'margin-left': function () {
                                return -($(this).width() / 2);
                            }
                        });
                    $('#myModal').find('.modal-footer').html("");
                    $('#myModal').find('.modal-footer').append($('<button>', {
                        type:'button',
                        datacontent:'remove',
                        class:'btn btn-primary',
                        id:'editRowBtn',
                        onclick:"$('#EventosimpuestoRealizartarea5Form').submit()",
                        text:"Aceptar"
                    }));
                    $('#myModal').find('.modal-footer').append($('<button>', {
                        type:'button',
                        datacontent:'remove',
                        class:'btn btn-primary',
                        id:'editRowBtn',
                        onclick:" $('#myModal').modal('hide')",
                        text:"Cerrar"
                    }));
                });
                $('#myModal').modal('show');
                $(document).ready(function() {
                    $( "input.datepicker" ).datepicker({
                      yearRange: "-100:+50",
                      changeMonth: true,
                      changeYear: true,
                      constrainInput: false,
                      dateFormat: 'dd-mm-yy',
                    });
                });
                $( "#vencimientogeneral" ).change(function(){
                    $('#EventosimpuestoRealizartarea5Form .hiddendatepicker').val( $( "#vencimientogeneral" ).val());
                });
                $( "#vencimientogeneral" ).trigger( "change" );
                $('#EventosimpuestoRealizartarea5Form').submit(function(){
                    $('.inputtodisable').prop("disabled", false);
                    //serialize form data
                    var formData = $(this).serialize();
                    //get form action
                    var formUrl = $(this).attr('action');
                    $.ajax({
                        type: 'POST',
                        url: formUrl,
                        data: formData,
                        success: function(data,textStatus,xhr){
                          var respuesta = jQuery.parseJSON(data);
                          var resp = respuesta.respuesta;
                          var error=respuesta.error;
                            $('#myModal').modal('hide');
                          if(error!=0){
                            alert(respuesta.validationErrors);
                            alert(respuesta.invalidFields);
                              $('#myModal').modal('hide');
                              return false;
                          }else{
                            //papelesDeTrabajo(periodo,impcli);
                          }
                          $('#EventosimpuestoHaycambios').val(1);
                          /*var cellid = 'cellimp'+clienteid+'-tarea5-'+impcliid;
                          $('#'+cellid).removeClass('pendiente');
                          $('#'+cellid).addClass('realizado');
                          var cellData = ' <img src="'+serverLayoutURL+'/img/edit.png" width="20" title="Papeles de Trabajo" height="20" onclick="papelesDeTrabajo('+"'"+periodo+"'"+','+impcliid+')" alt=""><label for="'+cant+'">'+cant+'</label> ';
                          $('#'+cellid).html(cellData);*/
                            $('#buttonImpCli'+impcli).removeClass('buttonImpcliListo');

                            var srcPT = $("#impPT"+impcli).attr('src');
                            var srcPago = $("#impPago"+impcli).attr('src');
                            if(respuesta.numero*1>=0){
                                $('#buttonImpCli'+impcli).addClass('buttonImpcliRealizado');
                                srcPT = srcPT.replace("ptgrey.png", "ptgreen.png");
                                srcPago = srcPago.replace("pesogrey.png", "pesogreen.png");
                            }else{
                                $('#buttonImpCli'+impcli).addClass('buttonImpcliSaldoNegativo ');
                                srcPT = srcPT.replace("ptgrey.png", "ptblue.png")
                                srcPago = srcPago.replace("pesogrey.png", "pesoblue.png")
                            }
                            $('#buttonImpCli'+impcli+' label').html("$"+respuesta.numero);

                           $("#impPT"+impcli).attr('src',srcPT);
                           $("#impPago"+impcli).attr('src',srcPago);



                            $('#myModal').modal('hide');
                         },
                        error: function(xhr,textStatus,error){
                          callAlertPopint(textStatus);
                          return false;
                        }
                    });
                      return false;
                });
                },
                error:function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(textStatus);
                }
        });
        return false;
    }
/* 5  ver Tarea Papeles de Trabajo -- Mostrar papeles de trabajo del impcli del periodo*/
    function verPapelDeTrabajoConvenioMultilateral(periodo,impcliid){
        var win = window.open(serverLayoutURL+'/impclis/papeldetrabajoconveniomultilateral/'+impcliid+'/'+periodo , '_blank');
    }
    function verPapelDeTrabajoMonotributo(periodo,impcliid){
        var win = window.open(serverLayoutURL+'/impclis/papeldetrabajomonotributo/'+impcliid+'/'+periodo , '_blank');
    }
    function verPapelDeTrabajoActividadesVarias(periodo,impcliid){
        var win = window.open(serverLayoutURL+'/impclis/papeldetrabajoactividadesvarias/'+impcliid+'/'+periodo , '_blank');
    }
    function verPapelDeTrabajoSUSS(periodo,impcliid){
        var win = window.open(serverLayoutURL+'/impclis/papeldetrabajosuss/'+impcliid+'/'+periodo , '_blank');
    }
    function verPapelDeTrabajoCooperadoraAsistencial(periodo,impcliid){
        var win = window.open(serverLayoutURL+'/impclis/papeldetrabajocooperadoraasistencial/'+impcliid+'/'+periodo , '_blank');
    }
    function verPapelDeTrabajoAutonomo(periodo,impcliid){
        var win = window.open(serverLayoutURL+'/papelesdetrabajos/autonomo/'+impcliid+'/'+periodo , '_blank');
    }
    function verPapelDeTrabajoSindicato(periodo,impcliid){
        var win = window.open(serverLayoutURL+'/impclis/papeldetrabajosindicatos/'+impcliid+'/'+periodo , '_blank');
    }
    function verPapelDeTrabajoIVA(periodo,impcliid){
        var win = window.open(serverLayoutURL+'/papelesdetrabajos/iva/'+impcliid+'/'+periodo , '_blank');
    }
    function verPapelDeTrabajoCasasParticulares(periodo,cliid){
        var win = window.open(serverLayoutURL+'/impclis/papeldetrabajocasasparticulares/'+cliid+'/'+periodo , '_blank');
    }
    function verCuentasDepuradas(cliid,periodo){
        var win = window.open(serverLayoutURL+'/controles/cuentasdepuradas/'+cliid+'/'+periodo , '_blank');
    }
/* 5  agregar Papel de Trabajo -- ejecutar la funcion que agrega el papel de trabajo cargado en el formulario*/
    function agregarPapeldeTrabajo(){
        if(error==""){
         var datas =  eventId2+"/"+periodo+"/"+impcliid+"/"+montovto+"/"+fchvto+"/"+monc+"/"+descripcion;
         var data ="";
         $.ajax({
               type: "post",  // Request method: post, get
               url: serverLayoutURL+"/eventosimpuestos/realizartarea5/"+id+"/"+periodo+"/"+impcliid+"/"+montovto+"/"+fchvto+"/"+monc+"/"+descripcion+"/"+tipopago, // URL to request
               data: data,  // post data
               success: function(response) {
                            var resp = response.split("&&");
                            var respuesta=resp[1];
                            var error=resp[0];
                            /*if(error!=0){
                              callAlertPopint("Hubo un error por favor intente de nuevo");
                              return false;
                            }*/
                            var cant=resp[3];

                            /*var cellid = 'cellimp'+clienteid+'-tarea5-'+impcliid;
                            $('#'+cellid).removeClass('pendiente');
                            $('#'+cellid).addClass('realizado');
                            var cellData = ' <img src="'+serverLayoutURL+'/img/edit.png" width="20" title="Papeles de Trabajo" height="20" onclick="papelesDeTrabajo('+"'"+periodo+"'"+','+impcliid+')" alt=""><label for="'+cant+'">'+cant+'</label> ';
                            $('#'+cellid).html(cellData);*/
                            $('#buttonImpCli'+impcliid+' label').html(cant);
                            callAlertPopint(respuesta);
                            $('#EventosimpuestoHaycambios').val(1);
                       },
               error:function (XMLHttpRequest, textStatus, errorThrown) {
                      alert(textStatus);

              }
          });
          return false;
        }else{
          callAlertPopint(error);
          return false;
        }
   
      return false;
  }  
    function marcarImpcliComoRealizado(periodo,impcli){
        var r = confirm("Esta seguro que desea marcar el impuesto como realizado?.");
        if (r == true) {
         //var datas =  $id = null,$tarea = null,$periodo= null,$implcid= null,$estadoTarea=null;
         var data ="";
         $.ajax({
               type: "post",  // Request method: post, get
               url: serverLayoutURL+"/eventosimpuestos/realizareventoimpuesto/0/tarea5/"+periodo+"/"+impcli+"/realizado", // URL to request
               data: data,  // post data
               success: function(response) {
                   var mirespuesta = jQuery.parseJSON(response);
                   callAlertPopint(mirespuesta.respuesta);
                   $('#buttonImpCli'+impcli).removeClass('buttonImpcliListo');

                   var srcPT = $("#impPT"+impcli).attr('src');
                   var srcPago = $("#impPago"+impcli).attr('src');
                   $('#buttonImpCli'+impcli).addClass('buttonImpcliRealizado');
                   srcPT = srcPT.replace("ptgrey.png", "ptgreen.png");
                   srcPago = srcPago.replace("pesogrey.png", "pesogreen.png");

                   $("#impPT"+impcli).attr('src',srcPT);
                   $("#impPago"+impcli).attr('src',srcPago);
               },
               error:function (XMLHttpRequest, textStatus, errorThrown) {
                      alert(textStatus);

              }
          });
          return false;
        }else{
          callAlertPopint("No se marco el impuesto como realizado");
          return false;
        }

      return false;
  }
/* 5  Eliminar Evento Impuesto */
    function eliminarEventoImpuesto(evenimpid){
        var r = confirm("Esta seguro que desea eliminar este registro?. Es una accion que no podra deshacer.");
        if (r == true) {
          $.ajax({
             type: "post",  // Request method: post, get
             url: serverLayoutURL+"/eventosimpuestos/delete/"+evenimpid, // URL to request
             data: "",  // post data
             success: function(response) {
                 try {
                      var mirespuesta = jQuery.parseJSON(response);
                     $('#buttonImpCli'+mirespuesta.impcliid+' label').html('$'+mirespuesta.numeroAMostrar);
                     callAlertPopint(mirespuesta.respuesta);
                 }catch (Exception){
                     callAlertPopint("Ocurrio un problema por favor intente mas tarde");
                 }
             },
             error:function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(textStatus);

            }
          });
        } else {
            txt = "No se a eliminado registro";
        }/*
        */
      }
/* 10 ver Tarea Informar -- Mostrar Honorario del periodo y Recibos del periodo*/
    function submitparent(button){
        $(button).closest('form').submit();
    }
    function verFormInformar(eventId,tarea,periodo,cliid,clientenombre,estadotarea,honoid,honomonto,honofecha,descripcion){
    var data = "";
     $.ajax({
           type: "post",  // Request method: post, get
           url: serverLayoutURL+"/depositos/getdepositos/"+periodo+"/"+cliid, // URL to request
           data: data,  // post data
           success: function(response) {
                  //alert(response);
                $('#divpopSolicitar').html(response);
                    $( "input.datepicker" ).datepicker({
                        yearRange: "-100:+50",
                        changeMonth: true,
                        changeYear: true,
                        constrainInput: false,
                        dateFormat: 'dd-mm-yy',
                      });
                    $('#formAddHonoraio').submit(function(){
                        location.hash ="#x";
                        $(this).find(':submit').attr('disabled','disabled');
                          //serialize form data
                        var formData = $(this).serialize();
                        //get form action
                        var formUrl = $(this).attr('action');
                        $.ajax({
                          type: 'POST',
                          url: formUrl,
                          data: formData,
                          success: function(data,textStatus,xhr){
                            //callAlertPopint(data);
                            var mirespuesta =jQuery.parseJSON(data);
                            if(mirespuesta.hasOwnProperty('respuesta')){
                              location.hash ="#x";
                              callAlertPopint(mirespuesta.respuesta);
                              return false;
                            }
                            verFormInformar(eventId,tarea,periodo,cliid,clientenombre,estadotarea,honoid,honomonto,honofecha,descripcion);
                            return false;
                          },
                          error: function(xhr,textStatus,error){
                            callAlertPopint(textStatus);
                            return false;
                          }
                        });
                            return false;
                      });
                    $('#formAddDeposito').submit(function(){
                        $(this).find(':submit').attr('disabled','disabled');
                          //serialize form data
                        var formData = $(this).serialize();
                        //get form action
                        var formUrl = $(this).attr('action');
                        $.ajax({
                          type: 'POST',
                          url: formUrl,
                          data: formData,
                        success: function(data,textStatus,xhr){
                            //callAlertPopint(data);

                            var mirespuesta =jQuery.parseJSON(data);
                            if(mirespuesta.hasOwnProperty('respuesta')){
                              callAlertPopint(mirespuesta.respuesta);
                            }
                            var newtd="";
                            verFormInformar(eventId,tarea,periodo,cliid,clientenombre,estadotarea,honoid,honomonto,honofecha,descripcion);
                            return false;
                          },
                        error: function(xhr,textStatus,error){
                            callAlertPopint(textStatus);
                            return false;
                          }
                        });
                        return false;
                      });
                    location.href="#popInSolicitar";
                },
           error:function (XMLHttpRequest, textStatus, errorThrown) {
                  alert(textStatus);
           }
        });
        return false;
    }
    function verFormHonorarios(cliid,periodo){
        var data = "";
        $.ajax({
           type: "post",  // Request method: post, get
           url: serverLayoutURL+"/honorarios/cargar/"+periodo+"/"+cliid, // URL to request
           data: data,  // post data
           success: function(response) {
                  //alert(response);
                $('#divpopSolicitar').html(response);
                    $( "input.datepicker" ).datepicker({
                        yearRange: "-100:+50",
                        changeMonth: true,
                        changeYear: true,
                        constrainInput: false,
                        dateFormat: 'dd-mm-yy',
                      });
                    $('#formAddHonoraio').submit(function(){
                        location.hash ="#x";
                        $(this).find(':submit').attr('disabled','disabled');
                          //serialize form data
                        var formData = $(this).serialize();
                        //get form action
                        var formUrl = $(this).attr('action');
                        $.ajax({
                          type: 'POST',
                          url: formUrl,
                          data: formData,
                          success: function(data,textStatus,xhr){
                            //callAlertPopint(data);
                            var mirespuesta =jQuery.parseJSON(data);
                            if(mirespuesta.hasOwnProperty('respuesta')){
                              location.hash ="#x";
                              callAlertPopint(mirespuesta.respuesta);

                              return false;
                            }
                              if($("#buttonCargaHonorarios"+cliid).length>0){
                                  $('#buttonCargaHonorarios'+cliid).removeClass('buttonImpcliListo');
                                  $('#buttonCargaHonorarios'+cliid).addClass('buttonImpcliRealizado');
                              }
                              verFormHonorarios(cliid,periodo);
                            return false;
                          },
                          error: function(xhr,textStatus,error){
                            callAlertPopint(textStatus);
                            return false;
                          }
                        });
                            return false;
                      });
                    location.href="#popInSolicitar";
                },
           error:function (XMLHttpRequest, textStatus, errorThrown) {
                  alert(textStatus);
           }
        });
        return false;
    }
    function verFormDepositos(cliid,periodo){
        var data = "";
        $.ajax({
           type: "post",  // Request method: post, get
           url: serverLayoutURL+"/depositos/cargar/"+periodo+"/"+cliid, // URL to request
           data: data,  // post data
           success: function(response) {
                  //alert(response);
                  $('#divpopSolicitar').html(response);
                      $( "input.datepicker" ).datepicker({
                        yearRange: "-100:+50",
                        changeMonth: true,
                        changeYear: true,
                        constrainInput: false,
                        dateFormat: 'dd-mm-yy',
                      });
                      $('#formAddDeposito').submit(function(){
                        $(this).find(':submit').attr('disabled','disabled');
                          //serialize form data
                        var formData = $(this).serialize();
                        //get form action
                        var formUrl = $(this).attr('action');
                        $.ajax({
                          type: 'POST',
                          url: formUrl,
                          data: formData,
                        success: function(data,textStatus,xhr){
                            //callAlertPopint(data);
                            var mirespuesta =jQuery.parseJSON(data);
                            if(mirespuesta.hasOwnProperty('respuesta')){
                              callAlertPopint(mirespuesta.respuesta);
                            }
                            var newtd="";
                            if($("#buttonCargaRecibos"+cliid).length>0){
                                $('#buttonCargaRecibos'+cliid).removeClass('buttonImpcliListo');
                                $('#buttonCargaRecibos'+cliid).addClass('buttonCargaRecibos');
                            }
                            verFormDepositos(cliid,periodo);
                            return false;
                          },
                        error: function(xhr,textStatus,error){
                            callAlertPopint(textStatus);
                            return false;
                          }
                        });
                        return false;
                      });
                    location.href="#popInSolicitar";
               },
           error:function (XMLHttpRequest, textStatus, errorThrown) {
                  alert(textStatus);
           }
        });
        return false;
  }
/* 10 Eliminar Deposito */
    function eliminarDeposito(depoid){
    var r = confirm("Esta seguro que desea eliminar este deposito?. Es una accion que no podra deshacer.");
    if (r == true) {
      $.ajax({
         type: "post",  // Request method: post, get
         url: serverLayoutURL+"/depositos/delete/"+depoid, // URL to request
         data: "",  // post data
         success: function(response) {
                      var mirespuesta = jQuery.parseJSON(response);
                      callAlertPopint(mirespuesta);
                 },
         error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
        }
      });
    } else {
        txt = "No se a eliminado el deposito";
    }
    }
    function checkVencimientoPagado(i){
      var myid='FormPagarEventoImpuesto Eventosimpuesto'+i+'Montovtoreal';
      var montovto = $('#FormPagarEventoImpuesto #Eventosimpuesto'+i+'Montovtoreal').val();
      var montorealizado = $('#FormPagarEventoImpuesto #Eventosimpuesto'+i+'Montorealizado').val();
      if(montovto!=montorealizado){
        $('#FormPagarEventoImpuesto #Eventosimpuesto'+i+'Montorealizado').css({'background-color' : 'lightsalmon'});
      }else{
        $('#FormPagarEventoImpuesto #Eventosimpuesto'+i+'Montorealizado').css({'background-color' : 'lightgreen'});
      }
  }
/* 11 ver Tarea Pagar -- Mostrar el formulario para pagar papeles de trabajo del impcli del periodo*/
    function catchFormAsiento(idForm){
        $('#'+idForm).submit(function(){
            //desactivar los inputs q son para agregar movimientos
            $("#rowdecarga :input").prop("disabled", true);
            //serialize form data
            var formData = $(this).serialize();
            //get form action
            var formUrl = $(this).attr('action');
            $.ajax({
                type: 'POST',
                url: formUrl,
                data: formData,
                success: function(data,textStatus,xhr){
                    var respuesta = JSON.parse(data);
                    // $('#myModal').modal('hide');
                    // $('#myModalFormAgregarAsiento').modal('hide');
                    callAlertPopint(respuesta.respuesta);
                    // reiniciarFormAgregarAsiento()
                },
                error: function(xhr,textStatus,error){
                    // $('#myModal').modal('hide');
                    // $('#myModalFormAgregarAsiento').modal('hide');
                    callAlertPopint(respuesta.error);
                    alert(textStatus);
                }
            });
            $("#rowdecarga :input").prop("disabled", false);
            return false;
        });
    }
    function loadPagar(periodo,impcli,cliid){
     var data = "";
         $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/eventosimpuestos/getapagar/"+periodo+"/"+impcli+"/"+cliid, // URL to request
            data: data,  // post data
            success: function(response) {
                //Aqui vamos a poner los listeners para los formularios para Pagar Papeles Preparados
                //alert(response);
                $('#myModal').on('show.bs.modal', function() {
                     $('#myModal').find('.modal-title').html('Pagos');
                     $('#myModal').find('.modal-body').html(response);
                     $('#myModal').find('.modal-content')
                         .css({
                             width: 'max-content',
                             'margin-left': function () {
                                 return -($(this).width() / 2);
                             }
                         });
                     $('#myModal').find('.modal-footer').html("");
                     $('#myModal').find('.modal-footer').append($('<button>', {
                        type:'button',
                        datacontent:'remove',
                        class:'btn btn-primary',
                        id:'editRowBtn',
                        onclick:"$('#FormPagarEventoImpuesto').submit()",
                        text:"Aceptar"
                     }));
                    $('#myModal').find('.modal-footer').append($('<button>', {
                        type:'button',
                        datacontent:'remove',
                        class:'btn btn-primary',
                        id:'editRowBtn',
                        onclick:" $('#myModal').modal('hide')",
                        text:"Cerrar"
                     }));
                });
                $('#myModal').modal('show');
                var i = 0;
                while(i<=20){
                    checkVencimientoPagado(i);
                    i++;
                }
                catchFormAsiento("AsientoAddForm");
                $('#FormPagarEventoImpuesto').submit(function(){
                    //vamos a controlar que los campos monto vencimiento sean iguales a los montos pagados
                    //serialize form data
                    var formData = $(this).serialize();
                    //get form action
                    var formUrl = $(this).attr('action');
                    $('#myModal').modal('hide');
                    $.ajax({
                    type: 'POST',
                    url: formUrl,
                    data: formData,
                    success: function(data,textStatus,xhr){
                        /*callAlertPopint(data);
                        return;*/
                        var mirespuesta =jQuery.parseJSON(data);
                        callAlertPopint(mirespuesta.sinerror);
                        $('#buttonImpCli'+impcli).removeClass('buttonImpcli0');
                        $('#buttonImpCli'+impcli).removeClass('buttonImpcli2');
                        $('#buttonImpCli'+impcli).addClass('buttonImpcli4');
                        $("#AsientoAddForm").submit();
                        return false;
                    },
                    error: function(xhr,textStatus,error){
                        callAlertPopint(textStatus);
                        return false;
                    }
                    });
                    return false;
               });
                $('.chosen-select-cuenta').chosen(
                    {
                        search_contains:true,
                        include_group_label_in_selected:true
                    }
                );
                //aca vamos a mover el div de asientos al de eventos impuesto
                $('#divContenedorContabilidad').detach().appendTo('#divAsientoDePagoEventoImpuesto');

                $( "input[relacionar='si']" ).each(function(){
                    var valObjetivo = $(this).attr('relacionara');
                    var ordenCuenta = $(this).attr('orden');
                    $( "input[value="+valObjetivo+"]" ).each(function(){
                        var orden = $(this).attr('orden');
                        $("#Eventosimpuesto"+orden+"Montorealizado").on('change paste', function() {
                            var valorDebe = $(this).val()*1 + 0;
                            $("#Asiento0Movimiento"+ordenCuenta+"Debe").val(valorDebe);
                            calcularValorCaja();
                        });
                        $("#Eventosimpuesto"+orden+"Montorealizado").trigger('change');
                    });
                });

                $(".inputDebe").each(function () {
                    $(this).change(addTolblTotalDebeAsieto);
                });
                $(".inputHaber").each(function () {
                    $(this).change(addTolblTotalhaberAsieto);
                });
                $(".inputHaber").each(function(){
                    $(this).trigger('change');
                    return;
                });
                $(".inputDebe").each(function(){
                    $(this).trigger('change');
                    return;
                });
           },
           error:function (XMLHttpRequest, textStatus, errorThrown) {
                      alert(textStatus);
                      
           }
        });
      return false;
  }
/* 19 Tarea Contabilizar*/
    function abrirsumasysaldos(clienteid,periodo){
        var win = window.open(serverLayoutURL+'/cuentasclientes/informesumaysaldo/'+clienteid+'/'+periodo , '_blank');
    }
    function contabilizarventas (clienteid,periodo) {
        var data="";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/asientos/contabilizarventa/"+clienteid+"/"+periodo,

            // URL to request
            data: data,  // post data
            success: function(response) {
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModal').find('.modal-title').html('Asiento automatico de venta');
                    $('#myModal').find('.modal-body').html(response);
                    $('#myModal').find('.modal-content')
                        .css({
                            width: 'max-content',
                            'margin-left': function () {
                                return -($(this).width() / 2);
                            }
                        });
                    $('#myModal').find('.modal-footer').html("");
                    $('#myModal').find('.modal-footer').append($('<button>', {
                        type:'button',
                        datacontent:'remove',
                        class:'btn btn-primary',
                        id:'editRowBtn',
                        onclick:"$('#AsientoAddForm').submit()",
                        text:"Aceptar"
                    }));
                    $('#myModal').find('.modal-footer').append($('<button>', {
                        type:'button',
                        datacontent:'remove',
                        class:'btn btn-primary',
                        id:'editRowBtn',
                        onclick:" $('#myModal').modal('hide')",
                        text:"Cerrar"
                    }));
                });
                $('#myModal').modal('show');
                $('.chosen-select-cuenta').chosen({
                    search_contains:true,
                    include_group_label_in_selected:true
                });
                $('#AsientoAddForm').submit(function(){
                    $('#myModal').modal('hide');
                    /*Vamos a advertir que estamos reemplazando un asiento ya guardado*/
                    var asientoyaguardado=false;
                    if($("#AsientoAddForm #Asiento0Id").val()*1!=0){
                        asientoyaguardado=true;
                    }
                    var r=true;
                    if(asientoyaguardado){
                        r = confirm("Este asiento sobreescribira al previamente guardado, reemplazando los valores por los calculados" +
                            " en este momento. Para ver el asiento previamente guardado CANCELE, luego ingrese en el Informe de " +
                            " Sumas y saldos y despues en Asientos");
                    }
                    if (r == true) {
                        $('#AsientoAddForm input').each(function(){
                            $(this).removeAttr('disabled');
                        });
                        //serialize form data
                        var formData = $(this).serialize();
                        //get form action
                        var formUrl = $(this).attr('action');
                        $.ajax({
                            type: 'POST',
                            url: formUrl,
                            data: formData,
                            success: function(data,textStatus,xhr){
                                var respuesta = jQuery.parseJSON(data);
                                var resp = respuesta.respuesta;
                                $('#myModal').modal('hide');
                                if($("#buttonAsVenta"+clienteid).length>0){
                                    $('#buttonAsVenta'+clienteid).removeClass('buttonImpcliListo');
                                    $('#buttonAsVenta'+clienteid).addClass('buttonImpcliRealizado');
                                }
                                callAlertPopint(resp);
                            },
                            error: function(xhr,textStatus,error){
                                $('#myModal').modal('show');
                                alert(textStatus);
                                callAlertPopint(textStatus);
                                return false;
                            }
                        });
                    }else{
                        callAlertPopint("El asiento no se ha sobreescrito.");
                    }

                    return false;
                });
                $(".inputDebe").each(function () {
                    $(this).change(addTolblTotalDebeAsieto);
                });
                $(".inputHaber").each(function () {
                    $(this).change(addTolblTotalhaberAsieto);
                });
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
                alert(XMLHttpRequest);
                alert(errorThrown);
            }
        });
    }
    function contabilizarcompras (clienteid,periodo) {
        var data="";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/asientos/contabilizarcompra/"+clienteid+"/"+periodo,

            // URL to request
            data: data,  // post data
            success: function(response) {
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModal').find('.modal-title').html('Asiento automatico de compra');
                    $('#myModal').find('.modal-body').html(response);
                    // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                });
                $('#myModal').modal('show');
                $('.chosen-select-cuenta').chosen({
                    search_contains:true,
                    include_group_label_in_selected:true
                });

                $('#AsientoAddForm').submit(function(){
                    $('#myModal').modal('hide');
                    /*Vamos a advertir que estamos reemplazando un asiento ya guardado*/
                    var asientoyaguardado=false;
                    if($("#AsientoAddForm #Asiento0Id").val()*1!=0){
                        asientoyaguardado=true;
                    }
                    var r=true;
                    if(asientoyaguardado){
                        r = confirm("Este asiento sobreescribira al previamente guardado, reemplazando los valores por los calculados" +
                            " en este momento. Para ver el asiento previamente guardado CANCELE, luego ingrese en el Informe de " +
                            " Sumas y saldos y despues en Asientos");
                    }
                    if (r == true) {
                        $('#AsientoAddForm input').each(function(){
                            $(this).removeAttr('disabled');
                        });
                        //serialize form data
                        var formData = $(this).serialize();
                        //get form action
                        var formUrl = $(this).attr('action');
                        $.ajax({
                            type: 'POST',
                            url: formUrl,
                            data: formData,
                            success: function(data,textStatus,xhr){
                                var respuesta = jQuery.parseJSON(data);
                                var resp = respuesta.respuesta;
                                $('#myModal').modal('hide');
                                if($("#buttonAsCompra"+clienteid).length>0){
                                    $('#buttonAsCompra'+clienteid).removeClass('buttonImpcliListo');
                                    $('#buttonAsCompra'+clienteid).addClass('buttonImpcliRealizado');
                                }
                                callAlertPopint(resp);
                            },
                            error: function(xhr,textStatus,error){
                                $('#myModal').modal('show');
                                alert(textStatus);
                                callAlertPopint(textStatus);
                                return false;
                            }
                        });
                    }else{
                        callAlertPopint("El asiento no se ha sobreescrito.");
                    }
                    return false;
                });
                $(".inputDebe").each(function () {
                    $(this).change(addTolblTotalDebeAsieto);
                });
                $(".inputHaber").each(function () {
                    $(this).change(addTolblTotalhaberAsieto);
                });
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
                alert(XMLHttpRequest);
                alert(errorThrown);
            }
        });
    }
    function contabilizarretencionessufridas (clienteid,periodo) {
        var data="";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/asientos/contabilizarretencionessufridas/"+clienteid+"/"+periodo,

            // URL to request
            data: data,  // post data
            success: function(response) {
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModal').find('.modal-title').html('Asiento automatico de Retenciones sufridas');
                    $('#myModal').find('.modal-body').html(response);
                    // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                });
                $('#myModal').modal('show');
                $('.chosen-select-cuenta').chosen({
                    search_contains:true,
                    include_group_label_in_selected:true
                });
                $('#AsientoAddForm').submit(function(){
                    $('#myModal').modal('hide');
                    /*Vamos a advertir que estamos reemplazando un asiento ya guardado*/
                    var asientoyaguardado=false;
                    if($("#AsientoAddForm #Asiento0Id").val()*1!=0){
                        asientoyaguardado=true;
                    }
                    var r=true;
                    if(asientoyaguardado){
                        r = confirm("Este asiento sobreescribira al previamente guardado, reemplazando los valores por los calculados" +
                            " en este momento. Para ver el asiento previamente guardado CANCELE, luego ingrese en el Informe de " +
                            " Sumas y saldos y despues en Asientos");
                    }
                    if (r == true) {
                        $('#AsientoAddForm input').each(function(){
                            $(this).removeAttr('disabled');
                        });
                        //serialize form data
                        var formData = $(this).serialize();
                        //get form action
                        var formUrl = $(this).attr('action');
                        $.ajax({
                            type: 'POST',
                            url: formUrl,
                            data: formData,
                            success: function(data,textStatus,xhr){
                                var respuesta = jQuery.parseJSON(data);
                                var resp = respuesta.respuesta;
                                $('#myModal').modal('hide');
                                if($("#buttonRetencionessufridas"+clienteid).length>0){
                                    $('#buttonRetencionessufridas'+clienteid).removeClass('buttonImpcliListo');
                                    $('#buttonRetencionessufridas'+clienteid).addClass('buttonImpcliRealizado');
                                }
                                callAlertPopint(resp);
                            },
                            error: function(xhr,textStatus,error){
                                $('#myModal').modal('show');
                                alert(textStatus);
                                callAlertPopint(textStatus);
                                return false;
                            }
                        });
                    }else{
                        callAlertPopint("El asiento no se ha sobreescrito.");
                    }

                    return false;
                });

                $(".inputDebe").each(function () {
                    $(this).change(addTolblTotalDebeAsieto);
                });
                $(".inputHaber").each(function () {
                    $(this).change(addTolblTotalhaberAsieto);
                });
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
                alert(XMLHttpRequest);
                alert(errorThrown);
            }
        });
    }
    function contabilizarBanco(cliid,periodo,bancimpcli,cbuid){
        var data="";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/asientos/contabilizarbanco/"+cliid+"/"+periodo+"/"+bancimpcli+"/"+cbuid,
            // URL to request
            data: data,  // post data
            success: function(response) {
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModal').find('.modal-title').html('Asiento automatico de banco');
                    $('#myModal').find('.modal-body').html(response);
                    // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                });
                $('#myModal').modal('show');
                $('.chosen-select-cuenta').chosen({
                    search_contains:true,
                    include_group_label_in_selected:true
                });
                $('#AsientoAddForm').submit(function(){
                    /*Vamos a advertir que estamos reemplazando un asiento ya guardado*/
                    var asientoyaguardado=false;
                    if($("#AsientoAddForm #Asiento0Id").val()*1!=0){
                        asientoyaguardado=true;
                    }
                    var r=true;
                    if(asientoyaguardado){
                        r = confirm("Este asiento sobreescribira al previamente guardado, reemplazando los valores por los calculados" +
                            " en este momento. Para ver el asiento previamente guardado CANCELE, luego ingrese en el Informe de " +
                            " Sumas y saldos y despues en Asientos");
                    }
                    if (r == true) {
                        //serialize form data
                        var formData = $(this).serialize();
                        //get form action
                        var formUrl = $(this).attr('action');
                        $.ajax({
                            type: 'POST',
                            url: formUrl,
                            data: formData,
                            success: function(data,textStatus,xhr){
                                var respuesta = jQuery.parseJSON(data);
                                var resp = respuesta.respuesta;
                                $('#myModal').modal('hide');
                                if($("#buttonCbu"+cbuid).length>0){
                                    $('#buttonCbu'+cbuid).removeClass('buttonImpcliListo');
                                    $('#buttonCbu'+cbuid).addClass('buttonImpcliRealizado');
                                }
                                callAlertPopint(resp);
                            },
                            error: function(xhr,textStatus,error){
                                callAlertPopint(textStatus);
                                return false;
                            }
                        });
                    }else{
                        callAlertPopint("El asiento no se ha sobreescrito.");
                    }

                    return false;
                });
                $(".inputDebe").each(function () {
                    $(this).change(addTolblTotalDebeAsieto);
                });
                $(".inputHaber").each(function () {
                    $(this).change(addTolblTotalhaberAsieto);
                });
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
                alert(XMLHttpRequest);
                alert(errorThrown);
            }
        });
    }
    function calcularValorCaja() {
        var valorCaja = 0;
        $( "input[relacionar='si']" ).each(function(){
            var valObjetivo = $(this).attr('relacionara');
            var ordenCuenta = $(this).attr('orden');
            $( "input[value="+valObjetivo+"]" ).each(function(){
                valorCaja += $("#Asiento0Movimiento"+ordenCuenta+"Debe").val()*1;
            });
        });
        $( "input[cuentaasientopago='si']" ).each(function(){
            var ordenCuenta = $(this).attr('orden');
            $( "input[cuentacontable='5']" ).each(function(){
                $(this).val(valorCaja.toFixed(2));
            });
            $( "input[cuentacontable='1069']" ).each(function(){
                $(this).val(valorCaja.toFixed(2));
            });
        });
    }
    function addTolblTotalDebeAsieto(event) {
        var debesubtotal = 0;
        $(".inputDebe").each(function () {
            debesubtotal = debesubtotal*1 + this.value*1;
            if(this.value*1!=0){
                $(this).removeClass("movimientoSinValor");
                $(this).addClass("movimientoConValor");
            }else{
                $(this).removeClass("movimientoConValor")
                $(this).addClass("movimientoSinValor");
            }
        });
        $("#lblTotalDebe").text(parseFloat(debesubtotal).toFixed(2)) ;
        showIconDebeHaber()
    }
    function addTolblTotalhaberAsieto(event) {
        //        $("#lblTotalAFavor").val(0) ;
        var habersubtotal = 0;
        $(".inputHaber").each(function () {
            habersubtotal = habersubtotal*1 + this.value*1;
            if(this.value*1!=0){
                $(this).removeClass("movimientoSinValor");
                $(this).addClass("movimientoConValor");
            }else{
                $(this).removeClass("movimientoConValor")
                $(this).addClass("movimientoSinValor");
            }
        });
        $("#lblTotalHaber").text(parseFloat(habersubtotal).toFixed(2)) ;
        showIconDebeHaber()
    }
    function showIconDebeHaber(){
        if($("#lblTotalHaber").text()==$("#lblTotalDebe").text()){
            $("#iconDebeHaber").attr('src',serverLayoutURL+'/img/test-pass-icon.png');
        }else{
            $("#iconDebeHaber").attr('src',serverLayoutURL+'/img/test-fail-icon.png');
        }
    }