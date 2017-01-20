$(document).ready(function() { 
    $("#ligestion").addClass("active"); 
    var $table = $('#tbl_tareas');
    $table.floatThead();
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
    cargarOnClickTareaSolicitar();
    periodoSel = $('#periodoSel').val();
    $( ".imgcb" ).each(function(  ) {
      var ImgCheckBox = $(this);
      if(ImgCheckBox.prev().is('.checked')){
          ImgCheckBox.prev().prop('checked', true);
          ImgCheckBox.prev().addClass('checked');
          $(this).prop('src', serverLayoutURL+'/img/checked_checkbox.png');
      } else {
          ImgCheckBox.prev().removeClass('checked');
          ImgCheckBox.prev().prop('checked', false);
          $(this).prop('src', serverLayoutURL+'/img/unchecked_checkbox.png');
      }
    });
});
  
  var periodoSel;
/* 0 Realizar Evento Cliente */
  function realizarEventoCliente(eventId,tarea,periodo,clienteid,estadotarea){

     var data ="";
     $.ajax({
           type: "post",  // Request method: post, get
           url: serverLayoutURL+"/eventosclientes/realizareventocliente/"+eventId+"/"+tarea+"/"+periodo+"/"+clienteid+"/"+estadotarea, // URL to request
           data: data,  // post data
           success: function(response) {
                    var resp = response.split("&&");
                        var error=resp[0];
               if(0!=error){
                   callAlertPopint('Error por favor intente mas tarde');
               }else{
                   var newtd="";
                   var idCell='#cell'+clienteid+'-'+tarea;
                   var myparams="";
                   if(estadotarea=='pendiente'){
                       myparams= eventId+",'"+tarea+"','"+periodo+"','"+clienteid+"','realizado'";
                       newtd+='<img src="'+serverLayoutURL+'/img/add.png" onclick="realizarEventoCliente('+myparams+')" height="20" width="20"> ';

                       $(idCell).attr("class", "pendiente");
                   }else{
                       myparams= eventId+",'"+tarea+"','"+periodo+"','"+clienteid+"','pendiente'";
                       newtd+='<img src="'+serverLayoutURL+'/img/edit.png" onclick="realizarEventoCliente('+myparams+')" height="20" width="20"> ';

                       $(idCell).attr("class", "realizado");
                   }

                   $(idCell).html(newtd);
                   callAlertPopint('Tarea modificada. Estado:'+estadotarea);
               }
           },
         error:function (XMLHttpRequest, textStatus ) {
             alert(textStatus);

         }
        });
        return false;
  }
/* 0 Realizar Evento Impuesto*/
  function realizarEventoImpuesto(eventId,tarea,periodo,clienteid,impcliid,estadotarea){
         var data ="";
         $.ajax({
               type: "post",  // Request method: post, get
               url: serverLayoutURL+"/eventosimpuestos/realizareventoimpuesto/"+eventId+"/"+tarea+"/"+periodo+"/"+impcliid+"/"+estadotarea, // URL to request
               data: data,  // post data
               success: function(response) {
                            var resp = response.split("&&");
                            var error=resp[0];
                            if(error!=0){
                              callAlertPopint('Error por favor intente mas tarde');
                            }else{
                              var newtd="";
                              var idCell='#cellimp'+clienteid+'-'+tarea+'-'+impcliid;
                              var myparams="";
                              if(estadotarea=='pendiente'){
                                myparams= eventId+",'"+tarea+"','"+periodo+"','"+clienteid+"','"+impcliid+"','realizado'";      
                                newtd+='<img src="'+serverLayoutURL+'/img/add.png" onclick="realizarEventoImpuesto('+myparams+')" height="20" width="20"> ';
                                $(idCell).attr("class", "pendiente");   
                              }else{
                                myparams= eventId+",'"+tarea+"','"+periodo+"','"+clienteid+"','"+impcliid+"','pendiente'";   
                                newtd+='<img src="'+serverLayoutURL+'/img/edit.png" onclick="realizarEventoImpuesto('+myparams+')" height="20" width="20"> ';
                                $(idCell).attr("class", "realizado");  
                              }
                               
                                $(idCell).html(newtd);
                                callAlertPopint('Tarea modificada. Estado:'+estadotarea);
                            }                                                                                              
                           },
               error:function (XMLHttpRequest, textStatus) {
                      alert(textStatus);
                      
               }
            });
            return false;
  }
/* 0 Mostrar PopIn NoHabilitado */
  function noHabilitado(texto){
    if(texto!=null){
      callAlertPopint(texto);
    }else{
      callAlertPopint('Usted no posee permisos para realizar esta tarea. En la seccion Parametros/Tareas podra habilitar la tarea.');
    }
  }
/* 1  ver Tarea Solicitar -- cargar imagenes para los checkboxes de las tareas solicitar*/
  function cargarOnClickTareaSolicitar(){
    $('.imgcb').on('click', function(){
      var ImgCheckBox = $(this);
      if(!ImgCheckBox.prev().is('.checked')){
          ImgCheckBox.prev().prop('checked', true);
          ImgCheckBox.prev().addClass('checked');
          $(this).prop('src', serverLayoutURL+'/img/checked_checkbox.png');

      } else {
          ImgCheckBox.prev().removeClass('checked');
          ImgCheckBox.prev().prop('checked', false);
          $(this).prop('src', serverLayoutURL+'/img/unchecked_checkbox.png');
      }
      enviarTareaSolicitar(ImgCheckBox);
    });
  }
/* 1  ver Enviar Solicitar */
  function enviarTareaSolicitar(element){
      var myParentForm= element.closest('form');
      //serialize form data 
      var formData = myParentForm.serialize(); 
      //get form action 
      var formUrl = myParentForm.attr('action'); 
      $.ajax({ 
        type: 'POST', 
        url: formUrl, 
        data: formData, 
        success: function(data,textStatus,xhr){ 
          var mirespuesta =jQuery.parseJSON(data);
          if(mirespuesta.hasOwnProperty('error')){
            callAlertPopint(mirespuesta.respuesta);
            return false;
          }
          return false;
        }, 
        error: function(xhr,textStatus,error){ 
          callAlertPopint(textStatus); 
          return false;
        } 
      }); 
      return false;
  }
/* 3  ver Tarea Cargar -- Abrir el formulario para cargar Ventas Compras y Novedades*/
  function verFormCargar(eventoID,periodoSel,clienteid){
      window.open(
        serverLayoutURL+"/clientes/tareacargar/"+clienteid+"/"+periodoSel,
        serverLayoutURL+"/clientes/tareacargar/"+clienteid+"/"+periodoSel
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
            $('#divpopPapelesDeTrabajo').html(response);
            $(document).ready(function() {
                $( "input.datepicker" ).datepicker({
                  yearRange: "-100:+50",
                  changeMonth: true,
                  changeYear: true,
                  constrainInput: false,
                  dateFormat: 'dd-mm-yy',
                });
              });
            location.href='#popInPapelesDeTrabajo';
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
                  if(error!=0){
                    alert(respuesta.validationErrors);
                    alert(respuesta.invalidFields);
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
                    $('#buttonImpCli'+impcli+' label').html("$"+respuesta.numero);
                    $('#buttonImpCli'+impcli).removeClass('buttonImpcli0');
                    $('#buttonImpCli'+impcli).addClass('buttonImpcli2');
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
    function verPapelDeTrabajoSindicato(periodo,impcliid){
        var win = window.open(serverLayoutURL+'/impclis/papeldetrabajosindicatos/'+impcliid+'/'+periodo , '_blank');
    }
    function verPapelDeTrabajoIVA(periodo,impcliid){
        var win = window.open(serverLayoutURL+'/papelesdetrabajos/iva/'+impcliid+'/'+periodo , '_blank');
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
/* 5  Funcionalidad de Tab de Papeles de Trabajo */
  function showPapelesDeTrabajo(){
      if($("#tab_PapelesDeTrabajo").hasClass("tabsTareaImpuesto_active")){

      }else{
          $("#tab_PapelesDeTrabajo").switchClass( "tabsTareaImpuesto", "tabsTareaImpuesto_active", 500 );
          $("#tab_Contabilidad_Impuestos").switchClass( "tabsTareaImpuesto_active", "tabsTareaImpuesto", 500 );
          $("#tab_Pagos").switchClass( "tabsTareaImpuesto_active", "tabsTareaImpuesto", 500 );
          $("#tab_Contabilidad_Pagos").switchClass( "tabsTareaImpuesto_active", "tabsTareaImpuesto", 500 );

          $('.tareapapeldetrabajo').show();
          $('.tareaContabilidadImpuestos').hide();
          $('.tareapagos').hide();
          $('.tareaContabilidadPagar').hide();
      }
  }
  function showPagos(){
      if($("#tab_Pagos").hasClass("tabsTareaImpuesto_active")){
      }else{
          $("#tab_PapelesDeTrabajo").switchClass( "tabsTareaImpuesto_active", "tabsTareaImpuesto", 500 );
          $("#tab_Contabilidad_Impuestos").switchClass( "tabsTareaImpuesto", "tabsTareaImpuesto_active", 500 );
          $("#tab_Pagos").switchClass( "tabsTareaImpuesto_active", "tabsTareaImpuesto", 500 );
          $("#tab_Contabilidad_Pagos").switchClass( "tabsTareaImpuesto_active", "tabsTareaImpuesto", 500 );

          $('.tareapapeldetrabajo').hide();
          $('.tareaContabilidadImpuestos').hide();
          $('.tareapagos').show();
          $('.tareaContabilidadPagar').hide();

          var haycambios = $('#EventosimpuestoHaycambios').val()*1;
          var divPagosVacio = false;
          if($("#divPagar").html()=="")
              divPagosVacio = true;
          if(haycambios||divPagosVacio){
              var impcli = $('#EventosimpuestoRealizartarea5Form #Eventosimpuesto0ImpcliId').val();
              var cliid = $('#EventosimpuestoRealizartarea5Form #Eventosimpuesto0ClienteId').val();
              loadPagar(periodoSel,impcli,cliid);
              $('#EventosimpuestoHaycambios').val(0);
          }
      }
  }
    function showContabilidadImpuesto(){
        if($("#tab_Contabilidad_Impuestos").hasClass("tabsTareaImpuesto_active")){

        }else{
            $("#tab_PapelesDeTrabajo").switchClass( "tabsTareaImpuesto_active", "tabsTareaImpuesto", 500 );
            $("#tab_Contabilidad_Impuestos").switchClass( "tabsTareaImpuesto", "tabsTareaImpuesto_active", 500 );
            $("#tab_Pagos").switchClass( "tabsTareaImpuesto_active", "tabsTareaImpuesto", 500 );
            $("#tab_Contabilidad_Pagos").switchClass( "tabsTareaImpuesto_active", "tabsTareaImpuesto", 500 );

            $('.tareapapeldetrabajo').hide();
            $('.tareaContabilidadImpuestos').show();
            $('.tareapagos').hide();
            $('.tareaContabilidadPagar').hide();
            var haycambios = $('#EventosimpuestoHaycambios').val()*1;
            var divcontImpuestoVacio = false;
            if($("#divContabilidadImpuestos").html()=="")
                divcontImpuestoVacio = true;
            if(haycambios||divcontImpuestoVacio){
                var impcli = $('#EventosimpuestoRealizartarea5Form #Eventosimpuesto0ImpcliId').val();
                loadContabilidadImpuesto(periodoSel,impcli);
                $('#EventosimpuestoHaycambios').val(0);
            }
        }
    }
    function showContabilidadPagos(){
        if($("#tab_Contabilidad_Pagos").hasClass("tabsTareaImpuesto_active")){

        }else{
            $("#tab_PapelesDeTrabajo").switchClass( "tabsTareaImpuesto_active", "tabsTareaImpuesto", 500 );
            $("#tab_Contabilidad_Impuestos").switchClass( "tabsTareaImpuesto_active", "tabsTareaImpuesto", 500 );
            $("#tab_Pagos").switchClass( "tabsTareaImpuesto_active", "tabsTareaImpuesto", 500 );
            $("#tab_Contabilidad_Pagos").switchClass( "tabsTareaImpuesto", "tabsTareaImpuesto_active", 500 );

            $('.tareapapeldetrabajo').hide();
            $('.tareaContabilidadImpuestos').hide();
            $('.tareapagos').hide();
            $('.tareaContabilidadPagar').show();
            var haycambios = $('#EventosimpuestoHaycambios').val()*1;
            var divcontPagosVacio = false;
            if($("#divContabilidadPagar").html()=="")
                divcontPagosVacio = true;
            if(haycambios||divcontPagosVacio){
                var impcli = $('#EventosimpuestoRealizartarea5Form #Eventosimpuesto0ImpcliId').val();
                loadContabilidadPagos(periodoSel,impcli);
                $('#EventosimpuestoHaycambios').val(0);
            }
        }
    }
/* 10 ver Tarea Informar -- Mostrar Honorario del periodo y Recibos del periodo*/
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
                  $('#divPagar').html(response);
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
                    $.ajax({ 
                      type: 'POST', 
                      url: formUrl, 
                      data: formData, 
                      success: function(data,textStatus,xhr){ 
                       /*callAlertPopint(data); 
                        return;*/
                        var mirespuesta =jQuery.parseJSON(data);
                        location.hash ="#x";                
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
