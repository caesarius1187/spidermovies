$(document).ready(function() {
    allcomprobantes =  JSON.parse($('#jsonallcomprobantes').val());
    var tblTablaConceptosrestantes = $('#tblTablaConceptosrestantes').dataTable().api();
    var tblTablaVentas = $('#tablaVentas').dataTable().api();
    var tblTablaCompras = $('#tablaCompras').dataTable().api();
    var tblTablaConceptosRestantes = $('#tblTablaConceptosrestantes').dataTable().api();
    calcularFooterTotales(tblTablaVentas);
    calcularFooterTotales(tblTablaCompras);
    $('.chosen-select').chosen({search_contains:true});
    $('#bodyTablaVentas').width("100%");
 
    /*Ventas On Change*/
      $("#VentaIvapercep").on('change paste', function() {
       calcularivaytotal("saveVentasForm");
      });
      $("#VentaImpinternos").on('change paste', function() {
        calcularivaytotal("saveVentasForm");
      });
      $("#VentaActvspercep").on('change paste', function() {
        calcularivaytotal("saveVentasForm");
      });
      $("#VentaIibbpercep").on('change paste', function() {
        calcularivaytotal("saveVentasForm");
      });
      $("#VentaAlicuota").on('change paste', function() {
        calcularivaytotal("saveVentasForm");
      });
      $("#VentaTotal").on('change paste', function() {
        calcularivaytotal("saveVentasForm");
      });
      $("#VentaNeto").on('change paste', function() {
        calcularivaytotal("saveVentasForm");
      });
      $("#VentaExcentos").on('change paste', function() {
        calcularivaytotal("saveVentasForm");
      });
      $("#VentaNogravados").on('change paste', function() {
        calcularivaytotal("saveVentasForm");
      });
    /*Fin Ventas On Change*/

    /*Esta funcion asigna el comportamiento del formulario Venta buscando el tipo de comprobante seleccionado ya que cambia si es tipo A, B o C*/
    $("#VentaComprobanteId").on('change paste', function() {
    allcomprobantes.forEach(function(comprobante) {
      if($("#VentaComprobanteId").val()==comprobante.Comprobante.id){
        switch(comprobante.Comprobante.tipodebitoasociado){
          case 'Debito fiscal o bien de uso':
            $("#VentaTipodebito option[value='Debito Fiscal']").show();
            $("#VentaTipodebito option[value='Bien de uso']").show();
            $("#VentaTipodebito option[value='Restitucion debito fiscal']").hide();
            $("#VentaTipodebito").val($("#VentaTipodebito option:eq(0)").val());
          
          break;
          case 'Restitucion de debito fiscal':
            $("#VentaTipodebito option[value='Debito Fiscal']").hide();
            $("#VentaTipodebito option[value='Bien de uso']").hide();
            $("#VentaTipodebito option[value='Restitucion debito fiscal']").show();
            $("#VentaTipodebito").val($("#VentaTipodebito option:eq(2)").val());
          break;
        }

        if(comprobante.Comprobante.tipo=="A"){
          $("#VentaNeto").prop("readonly",false);
          $("#VentaIva").prop("readonly",true);
          $("#VentaTotal").prop("readonly",true);
          tipodecomprobanteseleccionado = 'A';
          $("#CompraCondicioniva option[value='monotributista']").show();
          $("#CompraCondicioniva option[value='responsableinscripto']").hide();
          $("#CompraCondicioniva option[value='consf/exento/noalcanza']").hide();
        }else  if(comprobante.Comprobante.tipo=="B"){
          $("#VentaNeto").prop('readonly', true);
          $("#VentaIva").prop('readonly', true);
          $("#VentaTotal").prop('readonly', false);
          tipodecomprobanteseleccionado = 'B';
          $("#CompraCondicioniva option[value='monotributista']").show();
          $("#CompraCondicioniva option[value='responsableinscripto']").hide();
          $("#CompraCondicioniva option[value='consf/exento/noalcanza']").hide();
        }else  if(comprobante.Comprobante.tipo=="C"){
          tipodecomprobanteseleccionado = 'C';
          $("#CompraCondicioniva option[value='monotributista']").show();
          $("#CompraCondicioniva option[value='responsableinscripto']").hide();
          $("#CompraCondicioniva option[value='consf/exento/noalcanza']").hide();
        }
      }
    }, this);
  });
    $( "#VentaComprobanteId" ).trigger( "change" );
    reloadInputDates();
    showVentas();
    $('#saveVentasForm').submit(function(){
        //serialize form data
        var formData = $(this).serialize();
        //get form action
        var formUrl = $(this).attr('action');

        //Controles de inputs
        var fecha = $('#VentaFecha').val();
        var ventaNumeroComprobante = $('#VentaNumerocomprobante').val();
        if(fecha==null || fecha == ""){
          callAlertPopint('Debes seleccionar una fecha');
          return false;
        }
        if(ventaNumeroComprobante==null || ventaNumeroComprobante == ""){
          return false;
        }
        $.ajax({
          type: 'POST',
          url: formUrl,
          data: formData,
          success: function(data,textStatus,xhr){
            var respuesta = JSON.parse(data);
            if(respuesta.venta.Venta!=null){
              //Incrementar en 1 el numero de comprobante
              $( "#VentaNumerocomprobante" ).val($( "#VentaNumerocomprobante" ).val()*1 + 1);

              //Agregar la fila nueva a la tabla
              var  tdClass = "tdViewVenta"+respuesta.venta_id;

                var mineto = respuesta.venta.Venta.neto;
                var mitotal = respuesta.venta.Venta.total;

                mineto = parseFloat(mineto);
                mitotal = parseFloat(mitotal);

                if(respuesta.venta.Venta.tipodebito=='Restitucion debito fiscal'){
                    mineto = mineto*-1;
                    mitotal = mitotal*-1;
                }

              var rowData =
              [
                respuesta.venta.Venta.fecha,
                respuesta.comprobante.Comprobante.nombre+"-"+respuesta.puntosdeventa.Puntosdeventa.nombre+"-"+respuesta.venta.Venta.numerocomprobante,
                respuesta.subcliente.Subcliente.cuit,
                respuesta.subcliente.Subcliente.nombre,
                respuesta.venta.Venta.condicioniva,
                respuesta.actividadcliente.Actividade.nombre,
                respuesta.localidade.Localidade.nombre,
              ];

              if(!respuesta.tieneMonotributo){
                rowData.push(respuesta.venta.Venta.tipodebito);
                rowData.push(respuesta.venta.Venta.alicuota+"%");
                rowData.push(mineto);
                rowData.push(respuesta.venta.Venta.iva);
              }
              if(respuesta.tieneIVAPercepciones){
                rowData.push(respuesta.venta.Venta.ivapercep);
              }
              if(respuesta.tieneAgenteDePercepcionIIBB){
                rowData.push(respuesta.venta.Venta.iibbpercep);
              }
              if(respuesta.tieneAgenteDePercepcionActividadesVarias){
                rowData.push(respuesta.venta.Venta.actvspercep);
              }
              if(respuesta.tieneImpuestoInterno){
                rowData.push(respuesta.venta.Venta.impinternos);
              }
              rowData.push(respuesta.venta.Venta.nogravados);
              rowData.push(respuesta.venta.Venta.excentos);
              rowData.push(respuesta.venta.Venta.exentosactividadeseconomicas);
              rowData.push(respuesta.venta.Venta.exentosactividadesvarias);
              rowData.push(mitotal);
              var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="modificarVenta('+respuesta.venta_id+')" alt="">';
              tdactions = tdactions + '<img src="'+serverLayoutURL+'/img/eliminar.png" width="20" height="20" onclick="eliminarVenta('+respuesta.venta_id+')" alt="">';
              rowData.push(tdactions);

              var rowIndex = $('#tablaVentas').dataTable().fnAddData(rowData);
              var row = $('#tablaVentas').dataTable().fnGetNodes(rowIndex);
              $(row).attr( 'id', "rowventa"+respuesta.venta_id );
              calcularFooterTotales(tblTablaVentas);
            }else{
              callAlertPopint(respuesta.respuesta);
            }
          },
          error: function(xhr,textStatus,error){
            callAlertPopint(textStatus);
          }
        });
        return false;
    });
  
  /*Compras On Change*/
    $("#CompraIvapercep").on('change paste', function() {
      calcularivaytotalcompra("saveComprasForm");    
    });
    $("#CompraImpinternos").on('change paste', function() {
      calcularivaytotalcompra("saveComprasForm");
    });
    $("#CompraActvspercep").on('change paste', function() {
      calcularivaytotalcompra("saveComprasForm");
    });
    $("#CompraIibbpercep").on('change paste', function() {
      calcularivaytotalcompra("saveComprasForm");
    });
    $("#CompraAlicuota").on('change paste', function() {
      calcularivaytotalcompra("saveComprasForm");
    });
    $("#CompraTotal").on('change paste', function() {
      calcularivaytotalcompra("saveComprasForm");
    });
    $("#CompraNeto").on('change paste', function() {
      calcularivaytotalcompra("saveComprasForm");
    });

  /*Esta funcion asigna el comportamiento del formulario Compra buscando el tipo de comprobante seleccionado ya que cambia si es tipo A, B o C*/
  $("#CompraComprobanteId").on('change paste', function() {
    allcomprobantes.forEach(function(comprobante) {
      if($("#CompraComprobanteId").val()==comprobante.Comprobante.id){
        switch(comprobante.Comprobante.tipocreditoasociado){
          case 'Credito Fiscal':
            $("#CompraTipocredito option[value='Credito Fiscal']").show();
            $("#CompraTipocredito option[value='Restitucion credito fiscal']").hide();
            $("#CompraTipocredito").val($("#CompraTipocredito option:eq(0)").val());
          
          break;
          case 'Restitucion credito fiscal':
            $("#CompraTipocredito option[value='Credito Fiscal']").hide();
            $("#CompraTipocredito option[value='Restitucion credito fiscal']").show();
            $("#CompraTipocredito").val($("#CompraTipocredito option:eq(1)").val());
          break;
        }

        if(tipodecomprobanteCompraseleccionado==comprobante.Comprobante.tipo){
          return;
        }

        if(comprobante.Comprobante.tipo=="A"){
          //Preparar para recibir Neto y calcular IVA y Total
          $("#CompraNeto").prop("readonly",false);
          $("#CompraIva").prop("readonly",true);
          $("#CompraImputacion").prop("readonly",false);
          $("#CompraIvapercep").prop('readonly', false);
          $("#CompraTotal").prop("readonly",true);
          //Permitir editar los campos relacionados a IVA 
          tipodecomprobanteCompraseleccionado = 'A';
          $("#CompraCondicioniva option[value='monotributista']").hide();
          $("#CompraCondicioniva option[value='responsableinscripto']").show();
          $("#CompraCondicioniva option[value='consf/exento/noalcanza']").show();
          $("#CompraCondicioniva").val($("#CompraCondicioniva option:eq(1)").val());

          $("#CompraAlicuota option[value='0']").show();
          $("#CompraAlicuota option[value='2.5']").show();
          $("#CompraAlicuota option[value='5']").show();
          $("#CompraAlicuota option[value='10.5']").show();
          $("#CompraAlicuota option[value='21']").show();
          $("#CompraAlicuota option[value='27']").show();          
          $("#CompraAlicuota").val($("#CompraAlicuota option:eq(1)").val());
        }else  if(comprobante.Comprobante.tipo=="B"){
          $("#CompraNeto").prop("readonly",false);
          $("#CompraIva").prop("readonly",true);
          $("#CompraImputacion").prop("readonly",true);
          $("#CompraIvapercep").prop('readonly', true);
          $("#CompraTotal").prop("readonly",false);

          $("#CompraIva").val(0);
          $("#CompraImputacion").val(0);
          $("#CompraIvapercep").val(0);
          tipodecomprobanteCompraseleccionado = 'B';
          $("#CompraCondicioniva option[value='monotributista']").hide();
          $("#CompraCondicioniva option[value='responsableinscripto']").show();
          $("#CompraCondicioniva option[value='consf/exento/noalcanza']").show();
          $("#CompraCondicioniva").val($("#CompraCondicioniva option:eq(1)").val());

          $("#CompraAlicuota option[value='0']").show();
          $("#CompraAlicuota option[value='2.5']").hide();
          $("#CompraAlicuota option[value='5']").hide();
          $("#CompraAlicuota option[value='10.5']").hide();
          $("#CompraAlicuota option[value='21']").hide();
          $("#CompraAlicuota option[value='27']").hide();
          $("#CompraAlicuota option:eq(0)").val();
        }else  if(comprobante.Comprobante.tipo=="C"){
          $("#CompraNeto").prop("readonly",false);
          $("#CompraIva").prop("readonly",true);
          $("#CompraImputacion").prop("readonly",true);
          $("#CompraIvapercep").prop('readonly', true);
          $("#CompraTotal").prop("readonly",false);

          $("#CompraIva").val(0);
          $("#CompraImputacion").val(0);
          $("#CompraIvapercep").val(0);
          tipodecomprobanteCompraseleccionado = 'C';
          $("#CompraCondicioniva option[value='monotributista']").show();
          $("#CompraCondicioniva option[value='responsableinscripto']").hide();
          $("#CompraCondicioniva option[value='consf/exento/noalcanza']").hide();
          $("#CompraCondicioniva").val($("#CompraCondicioniva option:eq(0)").val());

          $("#CompraAlicuota option[value='0']").show();
          $("#CompraAlicuota option[value='2.5']").hide();
          $("#CompraAlicuota option[value='5']").hide();
          $("#CompraAlicuota option[value='10.5']").hide();
          $("#CompraAlicuota option[value='21']").hide();
          $("#CompraAlicuota option[value='27']").hide();
          $("#CompraAlicuota option:eq(0)").val();
        }
        
      }
    }, this);
  });
  $("#CompraComprobanteId" ).trigger( "change" );
  $("#ConceptosrestanteImpcliId").on('change paste', function() {
    $("#ConceptosrestanteConceptostipoId" ).trigger( "change" );
  });
  $("#ConceptosrestanteConceptostipoId").on('change paste', function() {
      hiddeAllImputsFromConceptosRestantesAddForm();
      var impcliseleccionado = $("#ConceptosrestanteImpcliId").val();
      if(impcliseleccionado==null) return;
      var impuestoseleccionadoNombre = $('#ConceptosrestanteImpcliId option[value="' + impcliseleccionado + '"]').html();
      var impuestoseleccionado = $('#ConceptosrestanteImpclisid option[value="' + impcliseleccionado + '"]').html();
      switch (impuestoseleccionado){
          case '21':/*Actividades Economicas*/
          case '174':/*Convenio Multilateral*/
              $('#ConceptosrestanteLocalidadeId').closest('div').hide();
              $('#ConceptosrestanteLocalidadeId').val("");
              $('#ConceptosrestantePartidoId').closest('div').show();
              showcolumnConceptoRestante(tblTablaConceptosrestantes,1,true);/*Partido*/
              showcolumnConceptoRestante(tblTablaConceptosrestantes,2,false);/*Localidad*/
              break;
          default:
              $('#ConceptosrestanteLocalidadeId').closest('div').show();
              $('#ConceptosrestantePartidoId').closest('div').hide();
              $('#ConceptosrestantePartidoId').val("");
              showcolumnConceptoRestante(tblTablaConceptosrestantes,1,false);/*Partido*/
              showcolumnConceptoRestante(tblTablaConceptosrestantes,2,true);/*Localidad*/
              break;
      }
      switch($("#ConceptosrestanteConceptostipoId").val()*1){
      case 1:/*Saldos A Favor*/
        $('#ConceptosrestanteDescripcion').closest('div').show();
        showcolumnConceptoRestante(tblTablaConceptosrestantes,20,true);/*Descripcion*/
        break;     
      case 2:/*Retenciones*/
        switch(impuestoseleccionado){
          case '21':/*Actividades Economicas*/
            $('#ConceptosrestanteAgente').closest('div').show();
            $('#ConceptosrestanteCuit').closest('div').show();
            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
            $('#ConceptosrestanteMonto').closest('div').show();
            showcolumnConceptoRestante(tblTablaConceptosrestantes,17,true);/*Agente*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,8,true);/*Monto*/
          break
          case '160':/*Ganancia(PF)*/
          case '5':/*Ganancia(PJ)*/
            $('#ConceptosrestanteCuit').closest('div').show();
            $('#ConceptosrestanteRegimen').closest('div').show();
            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
            $('#ConceptosrestanteDescripcion').closest('div').show();
            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,19,true);/*Regimen*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,20,true);/*Descripcion*/
            /*falta:
            numero certificado
            decha comprobante
            descripcion comprobante
            Fecha Registración DJ Ag Ret
            */
          case '4':/*IVA*/
            $('#ConceptosrestanteCuit').closest('div').show();
            $('#ConceptosrestanteRegimen').closest('div').show();
            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
            $('#ConceptosrestanteDescripcion').closest('div').show();
            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,19,true);/*Regimen*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,20,true);/*Descripcion*/
            /*falta:
            numero certificado
            decha comprobante
            descripcion comprobante
            Fecha Registración DJ Ag Ret
            */
          break;
          case '6':/*Actividades Varias*/
            $('#ConceptosrestanteCuit').closest('div').show();
            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
            $('#ConceptosrestanteNumeropadron').closest('div').show();
            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,22,true);/*NumeroPadron*/
            /*falta:
            denominacion
            */
          break;
          case '10':/*SUSS*/
            $('#ConceptosrestanteCuit').closest('div').show();
            $('#ConceptosrestanteRegimen').closest('div').show();
            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
            $('#ConceptosrestanteDescripcion').closest('div').show();
            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,19,true);/*Regimen*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,20,true);/*Descripcion*/
          break;
          default:
            $('#ConceptosrestanteCuit').closest('div').show();
            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
            $('#ConceptosrestanteComprobanteId').closest('div').show();
            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,4,true);/*TipoComptobante*/
          break;
        }
      case 3:/*Recaudacion Bancaria*/
        switch(impuestoseleccionado){
          case 21:/*Actividades Economicas*/
            $('#ConceptosrestanteAgente').closest('div').show();
            $('#ConceptosrestanteCuit').closest('div').show();
            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
            $('#ConceptosrestanteMonto').closest('div').show();
            showcolumnConceptoRestante(tblTablaConceptosrestantes,17,true);/*Agente*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,8,true);/*Monto*/
          break
          default:
            $('#ConceptosrestanteCuit').closest('div').show();
            $('#ConceptosrestanteAnticipo').closest('div').show();
            $('#ConceptosrestanteCbu').closest('div').show();
            $('#ConceptosrestanteTipocuenta').closest('div').show();
            $('#ConceptosrestanteTipomoneda').closest('div').show();
            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,13,true);/*Anticipo*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,14,true);/*CBU*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,15,true);/*TipoCuenta*/
            showcolumnConceptoRestante(tblTablaConceptosrestantes,16,true);/*TipoMoneda*/
          break;
        }
        break;
      case 4:/*Otros*/
        $('#ConceptosrestanteDescripcion').closest('div').show();
        showcolumnConceptoRestante(tblTablaConceptosrestantes,20,true);/*Descripcion*/
        break;
      case 5:/*Percepciones Aduaneras*/
        $('#ConceptosrestanteCuit').closest('div').show();
        $('#ConceptosrestanteNumerodespachoaduanero').closest('div').show();
        showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
        showcolumnConceptoRestante(tblTablaConceptosrestantes,12,true);/*DespachoAduanero*/
        break;
      case 6:/*Credito por adicional*/
        break;
      case 7:/*Anticipos especiales*/
        break;
      case 8:/*Pago en bonos*/
        $('#ConceptosrestanteDescripcion').closest('div').show();
        showcolumnConceptoRestante(tblTablaConceptosrestantes,20,true);/*Descripcion*/
        break;
      case 9:/*Compensaciones*/
        $('#ConceptosrestanteDescripcion').closest('div').show();
        showcolumnConceptoRestante(tblTablaConceptosrestantes,20,true);/*Descripcion*/
        break;
      case 10:/*Pagos a Cuenta*/
        $('#ConceptosrestanteEnterecaudador').closest('div').show();
        $('#ConceptosrestanteNumerocomprobante').closest('div').show();
        showcolumnConceptoRestante(tblTablaConceptosrestantes,18,true);/*EnteRecaudador*/
        showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
        break;
      case 11:/*SICREB*/
        $('#ConceptosrestanteAgente').closest('div').show();
        $('#ConceptosrestanteCbu').closest('div').show();
        $('#ConceptosrestanteTipocuenta').closest('div').show();
        $('#ConceptosrestanteTipomoneda').closest('div').show();
        showcolumnConceptoRestante(tblTablaConceptosrestantes,17,true);/*Agente*/
        showcolumnConceptoRestante(tblTablaConceptosrestantes,14,true);/*CBU*/
        showcolumnConceptoRestante(tblTablaConceptosrestantes,15,true);/*TipoCuenta*/
        showcolumnConceptoRestante(tblTablaConceptosrestantes,16,true);/*TipoMoneda*/
        break;
    }     
    $('#tblTablaConceptosrestantes').width("100%");
    tblTablaConceptosrestantes.column( 0 ).search( impuestoseleccionadoNombre ).draw();
  });
  $("#ConceptosrestanteConceptostipoId" ).trigger( "change" );
  

  $('#saveComprasForm').submit(function(){ 
      //serialize form data 
      var formData = $(this).serialize(); 
      //get form action 
      var formUrl = $(this).attr('action'); 
      //Controles de inputs
      var fecha = $('#CompraFecha').val();
      var provedor = $('#CompraProvedoreId').val();
      var compraNumerocomprobante = $('#CompraNumerocomprobante').val();
      if(fecha==null || fecha == ""){
        callAlertPopint('Debes seleccionar una fecha');
        return false;
      }
      if(provedor==null || provedor == ""){
        callAlertPopint('Debes seleccionar un provedor');
        return false;
      }
      if(compraNumerocomprobante==null || compraNumerocomprobante == ""){
        callAlertPopint('Debes ingresar un numero de Comprobante');
        return false;
      }
     
      $.ajax({ 
        type: 'POST', 
        url: formUrl, 
        data: formData, 
        success: function(data,textStatus,xhr){ 
          var respuesta = JSON.parse(data);
          callAlertPopint(respuesta.respuesta);
          if(respuesta.compra.Compra!=null){
              //Incrementa campo de Factura en 1
              $( "#CompraNumerocomprobante" ).val($( "#CompraNumerocomprobante" ).val()*1 + 1);

              var mineto = respuesta.compra.Compra.neto;
              var mitotal = respuesta.compra.Compra.total;

              mineto = parseFloat(mineto);
              mitotal = parseFloat(mitotal);

              if(respuesta.compra.Compra.tipocredito=='Restitucion credito fiscal'){
                  mineto = mineto*-1;
                  mitotal = mitotal*-1;
              }else{
                  mineto = mineto;
                  mitotal = mitotal;
              }

              //Agregar Row Nueva a la tabla
              var rowData =
                  [
                      respuesta.compra.Compra.fecha,
                      respuesta.comprobante.Comprobante.nombre+"-"+respuesta.compra.Compra.puntosdeventa+"-"+respuesta.compra.Compra.numerocomprobante,
                      respuesta.provedore.Provedore.nombre,
                      respuesta.compra.Compra.condicioniva,
                      respuesta.actividadcliente.Actividade.nombre,
                      respuesta.localidade.Localidade.nombre,
                      respuesta.compra.Compra.tipocredito,
                      respuesta.tipogasto.Tipogasto.nombre,
                      respuesta.compra.Compra.tipoiva,
                      respuesta.compra.Compra.imputacion,
                      respuesta.compra.Compra.alicuota+"%",
                      mineto,
                      respuesta.compra.Compra.iva,
                      respuesta.compra.Compra.ivapercep,
                      respuesta.compra.Compra.iibbpercep,
                      respuesta.compra.Compra.actvspercep,
                      respuesta.compra.Compra.impinternos,
                      respuesta.compra.Compra.impcombustible,
                      respuesta.compra.Compra.nogravados,
                      respuesta.compra.Compra.exentos,
                      respuesta.compra.Compra.kw,
                      mitotal,
                  ];
              var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="modificarCompra('+respuesta.compra_id+')" alt="">';
              tdactions = tdactions + '<img src="'+serverLayoutURL+'/img/eliminar.png" width="20" height="20" onclick="eliminarCompra('+respuesta.compra_id+')" alt="">';
              rowData.push(tdactions);

              var rowIndex = $('#tablaCompras').dataTable().fnAddData(rowData);
              var row = $('#tablaCompras').dataTable().fnGetNodes(rowIndex);
              $(row).attr( 'id', "rowcompra"+respuesta.compra_id );
              calcularFooterTotales(tblTablaCompras);

            }
          }, 
        error: function(xhr,textStatus,error){ 
          alert(error); 
        } 
      }); 
      return false; 
    });
  $('#saveSueldosForm').submit(function(){ 
      //serialize form data 
      var formData = $(this).serialize(); 
      //get form action 
      var formUrl = $(this).attr('action'); 
      //Controles de inputs
      var fecha = $('#SueldoFecha').val();
      if(fecha==null || fecha == ""){
        callAlertPopint('Debes seleccionar una fecha');
        return false;
      }
      $.ajax({ 
        type: 'POST', 
        url: formUrl, 
        data: formData, 
        success: function(data,textStatus,xhr){ 
          var respuesta = JSON.parse(data);
          callAlertPopint(respuesta.respuesta);
          if(respuesta.sueldo.Sueldo!=null){
              var  tdClass = "tdViewSueldo"+respuesta.sueldo_id;
              var row = "<tr id='rowsueldo"+respuesta.sueldo_id+"'> ";
              row = row +"<td class='"+tdClass+"'>"+respuesta.sueldo.Sueldo.fecha+"</td>";
              row = row + "<td class='"+tdClass+"'>"+respuesta.sueldo.Sueldo.monto+"</td>";
              row = row + "<td class='"+tdClass+"'>";
              row = row + '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="modificarSueldo('+respuesta.sueldo_id+')" alt="">';                                  
              row = row + '<img src="'+serverLayoutURL+'/img/eliminar.png" width="20" height="20" onclick="eliminarSueldo('+respuesta.sueldo_id+')" alt=""></td>';                                  
              row = row + "</tr>";
              $("#bodyTablaSueldos").append(row);
            }
          }, 
        error: function(xhr,textStatus,error){ 
          alert(textStatus); 
          alert(error); 
        } 
      }); 
      return false; 
    });
  $('#saveConceptosrestantesForm').submit(function(){ 
      //serialize form data 
      var formData = $(this).serialize(); 
      //get form action 
      var formUrl = $(this).attr('action'); 
      //Controles de inputs
      var fecha = $('#ConceptosrestanteFecha').val();
      if(fecha==null || fecha == ""){
        callAlertPopint('Debes seleccionar una fecha');
        return false;
      }
      /*var localidade = $('#ConceptosrestanteLocalidadeId').val();
      if(localidade==null || localidade == ""){
        callAlertPopint('Debes seleccionar una localidad');
        return false;
      }*/
      var fecha = $('#ConceptosrestanteMontoretenido').val();
      if(fecha==null || fecha == ""){
        callAlertPopint('Debes cargar un monto');
        return false;
      }
      $.ajax({ 
        type: 'POST', 
        url: formUrl, 
        data: formData, 
        success: function(data,textStatus,xhr){ 
          var respuesta = JSON.parse(data);
          callAlertPopint(respuesta.respuesta);
          var conceptoCargado = respuesta.conceptosrestante.Conceptosrestante
          if(conceptoCargado!=null){
                //Agregar la fila nueva a la tabla
                var  tdClass = "tdViewConceptosrestanteO"+conceptoCargado.id;
                var partido = "";
                var localidad = "";
                var impcliseleccionado = $("#ConceptosrestanteImpcliId").val();
                var impuestoseleccionado = $('#ConceptosrestanteImpclisid option[value="' + impcliseleccionado + '"]').html();
                switch (impuestoseleccionado){
                  //estos impuestos se pagan por provincia todos los otros por localidad
                  case '21':/*Actividades Economicas*/
                  case '174':/*Convenio Multilateral*/
                      partido = respuesta.conceptosrestante.Partido.nombre;
                      break;
                  default:
                      localidad =  respuesta.conceptosrestante.Localidade.Partido.nombre+'-'+respuesta.conceptosrestante.Localidade.nombre;
                      break;
                }
                var rowData =
                [
                  respuesta.conceptosrestante.Impcli.Impuesto.nombre,
                  partido,
                  localidad,
                  respuesta.conceptosrestante.Conceptostipo.nombre,
                  respuesta.conceptosrestante.Comprobante.nombre,
                  conceptoCargado.numerocomprobante,
                  conceptoCargado.rectificativa,
                  conceptoCargado.razonsocial,
                  conceptoCargado.monto,
                  conceptoCargado.montoretenido,
                  conceptoCargado.cuit,
                  conceptoCargado.fecha,
                  conceptoCargado.numerodespachoaduanero,
                  conceptoCargado.anticipo,
                  conceptoCargado.cbu,
                  conceptoCargado.tipocuenta,
                  conceptoCargado.tipomoneda,
                  conceptoCargado.agente,
                  conceptoCargado.enterecaudador,
                  conceptoCargado.regimen,
                  conceptoCargado.descripcion,
                  conceptoCargado.numeropadron
                ];
                var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="modificarConceptosrestante('+conceptoCargado.id+')" alt="">';
                tdactions = tdactions + '<img src="'+serverLayoutURL+'/img/eliminar.png" width="20" height="20" onclick="eliminarConceptosrestante('+conceptoCargado.id+')" alt="">';
                rowData.push(tdactions);

                var rowIndex = $('#tblTablaConceptosrestantes').dataTable().fnAddData(rowData);
                var row = $('#tblTablaConceptosrestantes').dataTable().fnGetNodes(rowIndex);
                $(row).attr( 'id', "rowconceptorestante"+conceptoCargado.id );
            }
        }, 
      error: function(xhr,textStatus,error){ 
        alert(error); 
      } 
    }); 
    return false; 
  }); 
  $('#SubclienteAddForm').submit(function(){ 
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
        callAlertPopint(respuesta.respuesta);
        if(respuesta.subcliente.Subcliente!=null){
          $('#VentaSubclienteId').append($('<option>', {
              value: respuesta.subcliente.Subcliente.id,
              text: respuesta.subcliente.Subcliente.nombre+'-'+respuesta.subcliente.Subcliente.dni+'-'+respuesta.subcliente.Subcliente.cuit
          }));
          $('.chosen-select').trigger("chosen:updated");   
        }
      }, 
      error: function(xhr,textStatus,error){ 
        callAlertPopint(textStatus);

      } 
    }); 
    return false; 
    });
  $('#ProvedoreAddForm').submit(function(){ 
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
        callAlertPopint(respuesta.respuesta);
        if(respuesta.provedor.Provedore!=null){
          $('#CompraProvedoreId').append($('<option>', {
              value: respuesta.provedor.Provedore.id,
              text: respuesta.provedor.Provedore.nombre+'-'+respuesta.provedor.Provedore.dni+'-'+respuesta.provedor.Provedore.cuit
          }));
          $('.chosen-select').trigger("chosen:updated");   
        }
      }, 
      error: function(xhr,textStatus,error){ 
        callAlertPopint(textStatus);

      } 
    }); 
    return false; 
    });
  /*TABs On Click*/
    $("#tabVentas" ).click(function() {   
       $("#tabVentas" ).switchClass( 'cliente_view_tab', 'cliente_view_tab_active');
       $("#tabCompras" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
       $("#tabNovedades" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
       $("#tabConceptosrestantes" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
       showVentas();
      });
    $("#tabCompras" ).click(function() {   
       $("#tabCompras" ).switchClass( 'cliente_view_tab', 'cliente_view_tab_active');
       $("#tabVentas" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
       $("#tabNovedades" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
       $("#tabConceptosrestantes" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
       showCompras();
      });
    $("#tabNovedades" ).click(function() {   
       $("#tabNovedades" ).switchClass( 'cliente_view_tab', 'cliente_view_tab_active');
       $("#tabCompras" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
       $("#tabVentas" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
       $("#tabConceptosrestantes" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
       showNovedades();
      });
    $("#tabConceptosrestantes" ).click(function() {   
       $("#tabNovedades" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
       $("#tabCompras" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
       $("#tabVentas" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
       $("#tabConceptosrestantes" ).switchClass( 'cliente_view_tab', 'cliente_view_tab_active');
       showConceptosRestantes();
      });
  /*Fin TABs On Click*/
  /*Configuracion del comportamiento de la seccion de Empleados*/
  $("#CctxconceptoCalculado").on('change', function() {
    if ($("#CctxconceptoCalculado").prop( "checked")==true){
      $("#CctxconceptoFuncionaaplicar").parent().show();
    }else{
      $("#CctxconceptoFuncionaaplicar").parent().hide();
    }
  });
  $("#CctxconceptoCalculado").trigger('click');
  $("#CctxconceptoCalculado").trigger('click');

  /*Fin Seccion Empleados*/
  function hiddeAllImputsFromConceptosRestantesAddForm(){
    $('#saveConceptosrestantesForm div').hide();
    
    var impcliseleccionado = $("#ConceptosrestanteImpcliId").val();
    var impuestoseleccionado = $('#ConceptosrestanteImpclisid option[value="' + impcliseleccionado + '"]').html();

      for (var i = 22 - 1; i >= 0; i--) {
          var columni = tblTablaConceptosrestantes.column(i);
          columni.visible( false );
      };

        switch (impuestoseleccionado){
              //estos impuestos se pagan por provincia todos los otros por localidad
              case '21':/*Actividades Economicas*/
              case '174':/*Convenio Multilateral*/
                  $('#ConceptosrestanteLocalidadeId').closest('div').hide();
                  $('#ConceptosrestantePartidoId').closest('div').show();
                  showcolumnConceptoRestante(tblTablaConceptosrestantes,2,true);//Localidad
                  break;
              default:
                  $('#ConceptosrestanteLocalidadeId').closest('div').show();
                  $('#ConceptosrestantePartidoId').closest('div').hide();
                  showcolumnConceptoRestante(tblTablaConceptosrestantes,1,true);//Localidad
                  break;
        }
        $('#ConceptosrestanteConceptostipoId').closest('div').show();
        $('#ConceptosrestanteImpcliId').closest('div').show();
        $('.chosen-container').show();
        $('.chosen-container div').show();
        $('#ConceptosrestanteMontoretenido').closest('div').show();
        $('#saveConceptosrestantesForm .submit input').closest('div').show();
        $('#ConceptosrestanteFecha').closest('div').show();
    


    showcolumnConceptoRestante(tblTablaConceptosrestantes,0,true);//Impcli
    showcolumnConceptoRestante(tblTablaConceptosrestantes,3,true);//Concepto
    showcolumnConceptoRestante(tblTablaConceptosrestantes,9,true);//Monto Retenido
    showcolumnConceptoRestante(tblTablaConceptosrestantes,11,true);//Fecha
    showcolumnConceptoRestante(tblTablaConceptosrestantes,22,true);//Actions*/
  }
  function showcolumnConceptoRestante(table,column,visible){
    var column = table.column( column );
    column.visible( visible );
  }

    //Crear Lenguaje para Jquery-Calx
    // load a language
    numeral.language('id', {
        delimiters: {
            thousands: ' ',
            decimal: '.'
        },
        abbreviations: {
            thousand: 'k',
            million: 'm',
            billion: 'b',
            trillion: 't'
        },
        ordinal : function (number) {
            return '';
        },
        currency: {
            symbol: '$'
        }
    });
    numeral.language('id');
});
    function calcularFooterTotales(mitabla){
        mitabla.columns( '.sum' ).every( function () {
            try {
                var micolumndata = this.data();
                var columnLength = this.data().length;
                if(columnLength > 0){
                    var sum = this
                        .data()
                        .reduce( function (a,b) {

                            if (a != null && b != null) {
                                if (typeof a === 'string') {
                                    a = a.replace('.', "");
                                    a = a.replace(',', ".");
                                }
                                a = Number(a);
                                if (typeof b === 'string') {
                                    b = b.replace('.', "");
                                    b = b.replace(',', ".");
                                }
                                b = Number(b);
                                return a + b;
                            } else {
                                return 0;
                            }
                        } );
                    $( this.footer() ).html(sum);
                }
            }
            catch (e)
            {
                alert(e.message);
            }
        } );
    }
  function hiddeAllImputsFromConceptosRestantesAddForm(formname,id){
    $('#'+formname+' div').hide();        
    
    var impcliseleccionado = $("#"+formname+" #ConceptosrestanteImpcliId").val();
    var impuestoseleccionado = $('#'+formname+' #ConceptosrestanteImpclisid option[value="' + impcliseleccionado + '"]').html();
    
    $('#'+formname+' #ConceptosrestanteImpcliId').closest('div').show();
    $('#'+formname+' #ConceptosrestanteLocalidadeId').closest('div').show();
    $('#'+formname+' .chosen-container').show();
    $('#'+formname+' .chosen-container div').show();

    $('#'+formname+' #ConceptosrestanteMontoretenido').closest('div').show();
    $('#'+formname+' #ConceptosrestanteFecha').closest('div').show();    

    $('#'+formname+' #ConceptosrestanteConceptofecha'+id).closest('div').show();   
    $('#'+formname+' #ConceptosrestanteConceptostipoId').closest('div').show();
    $('#'+formname+' .submit input').closest('div').show(); 
    reloadInputDates();
  }

  var allcomprobantes;
  var tipodecomprobanteseleccionado = 'A';
  var tipodecomprobanteCompraseleccionado = '';
  function calcularivaytotal(formulario){

    $("#"+formulario+" #VentaComprobanteId").trigger( "change" );

    var tieneMonotributo = $("#saveVentasForm #VentaTieneMonotributo").val();
    var tieneIVA = $("#saveVentasForm #VentaTieneIVA").val();
    var tieneIVAPercepciones = $("#saveVentasForm #VentaTieneIVAPercepciones").val();
    var tieneImpuestoInterno = $("#saveVentasForm #VentaTieneImpuestoInterno").val();
    var tieneAgenteDePercepcionActividadesVarias = $("#saveVentasForm #VentaTieneAgenteDePercepcionActividadesVarias").val();
    var tieneAgenteDePercepcionIIBB = $("#saveVentasForm #VentaIibbpercep").val();
    
    tieneMonotributo ? tieneMonotributo = true : tieneMonotributo = false;
    tieneIVA ? tieneIVA = true : tieneIVA = false;
    tieneIVAPercepciones ? tieneIVAPercepciones = true : tieneIVAPercepciones = false;
    tieneImpuestoInterno ? tieneImpuestoInterno = true : tieneImpuestoInterno = false;
    tieneAgenteDePercepcionActividadesVarias ? tieneAgenteDePercepcionActividadesVarias = true : tieneAgenteDePercepcionActividadesVarias = false;
    tieneAgenteDePercepcionIIBB ? tieneAgenteDePercepcionIIBB = true : tieneAgenteDePercepcionIIBB = false;

    var alicuota = 0;
    var ivapercep = 0;
    var agenteDePercepcionIIBB = 0;
    var agenteDePercepcionActividadesVarias = 0;
    var impinternos = 0;

    var neto = 0;
    var iva = 0;

    var total = 0;
    var noGravados = $("#"+formulario+" #VentaNogravados").val();
    var excentos = $("#"+formulario+" #VentaExcentos").val();

    if(tieneMonotributo){
      total = $("#"+formulario+" #VentaTotal").val();
      $("#"+formulario+" #VentaAlicuota").val(0);
      $("#"+formulario+" #VentaNeto").val(0)
      $("#"+formulario+" #VentaIva").val(0);
      total+= noGravados * 1;
      total+= excentos * 1;
      return;
    }else{
       alicuota = $("#"+formulario+" #VentaAlicuota").val() * 1;
      if(tipodecomprobanteseleccionado == "A"){
        //Ingreso Alicuota
        //Ingreso Neto
        //Calcular IVA
        //Calcular TOTAl
        neto = $("#"+formulario+" #VentaNeto").val();
        iva=neto*(alicuota/100);

        total+=neto * 1;
        total+=iva * 1;

        if(tieneIVAPercepciones){
          ivapercep = $("#"+formulario+" #VentaIvapercep").val() * 1;
          total+= ivapercep * 1;
        }
        if(tieneImpuestoInterno){
          impinternos = $("#"+formulario+" #VentaImpinternos").val() * 1;
          total+= impinternos * 1;
        }
        if(tieneAgenteDePercepcionActividadesVarias){
          agenteDePercepcionActividadesVarias = $("#"+formulario+" #VentaActvspercep").val() * 1;
          total+= agenteDePercepcionActividadesVarias * 1;
        }
        if(tieneAgenteDePercepcionIIBB){
          agenteDePercepcionIIBB = $("#"+formulario+" #VentaIibbpercep").val() * 1;
          total+= agenteDePercepcionIIBB * 1;
        }
        total+= noGravados * 1;
        total+= excentos * 1;

        $("#"+formulario+" #VentaTotal").val(total)
        $("#"+formulario+" #VentaIva").val(iva);
      }
      if(tipodecomprobanteseleccionado=="B"){
        //Ingreso alicuota 
        //Ingreso Total
        //Calcular IVA
        //Calcular Neto
        total = $("#"+formulario+" #VentaTotal").val() * 1;

        neto += total; 
        if(tieneIVAPercepciones){
          ivapercep = $("#"+formulario+" #VentaIvapercep").val() * 1;
          neto-= ivapercep * 1;
        }
        if(tieneImpuestoInterno){
          impinternos = $("#"+formulario+" #VentaImpinternos").val() * 1;
          neto-= impinternos * 1;
        }
        if(tieneAgenteDePercepcionActividadesVarias){
          agenteDePercepcionActividadesVarias = $("#"+formulario+" #VentaActvspercep").val() * 1;
          neto-= agenteDePercepcionActividadesVarias * 1;
        }
        if(tieneAgenteDePercepcionIIBB){
          agenteDePercepcionIIBB = $("#"+formulario+" #VentaIibbpercep").val() * 1;
          neto-= agenteDePercepcionIIBB * 1;
        }
        neto-= noGravados * 1;
        neto-= excentos * 1;
        iva = neto/((alicuota/100)+1)*(alicuota/100);
        neto = neto/((alicuota/100)+1);
        $("#"+formulario+" #VentaNeto").val(neto);
        $("#"+formulario+" #VentaIva").val(iva);
      }
    }  
  }
  function calcularivaytotalcompra(formulario){

    $("#"+formulario+" #CompraComprobanteId").trigger( "change" );

    var tieneMonotributo = $("#saveComprasForm #CompraTieneMonotributo").val();
    var tieneIVA = $("#saveComprasForm #CompraTieneIVA").val();
    var tieneIVAPercepciones = $("#saveComprasForm #CompraTieneIVAPercepciones").val();
    var tieneImpuestoInterno = $("#saveComprasForm #CompraTieneImpuestoInterno").val();
    var tieneAgenteDePercepcionActividadesVarias = $("#saveComprasForm #CompraTieneAgenteDePercepcionActividadesVarias").val();
    var tieneAgenteDePercepcionIIBB = $("#saveComprasForm #CompraIibbpercep").val();
    
    tieneMonotributo ? tieneMonotributo = true : tieneMonotributo = false;
    tieneIVA ? tieneIVA = true : tieneIVA = false;
    tieneIVAPercepciones ? tieneIVAPercepciones = true : tieneIVAPercepciones = false;
    tieneImpuestoInterno ? tieneImpuestoInterno = true : tieneImpuestoInterno = false;
    tieneAgenteDePercepcionActividadesVarias ? tieneAgenteDePercepcionActividadesVarias = true : tieneAgenteDePercepcionActividadesVarias = false;
    tieneAgenteDePercepcionIIBB ? tieneAgenteDePercepcionIIBB = true : tieneAgenteDePercepcionIIBB = false;

    var alicuota = 0;
    var ivapercep = 0;
    var agenteDePercepcionIIBB = 0;
    var agenteDePercepcionActividadesVarias = 0;
    var impinternos = 0;
    var impcombustible=0;

    var neto = 0;
    var iva = 0;

    var total = 0;
    
    alicuota = $("#"+formulario+" #CompraAlicuota").val() * 1;
    //Ingreso Alicuota
    //Ingreso Neto
    //Calcular IVA
    //Calcular TOTAl
    neto = $("#"+formulario+" #CompraNeto").val();
    iva=neto*(alicuota/100);

    total+=neto * 1;
    total+=iva * 1;

    ivapercep = $("#"+formulario+" #CompraIvapercep").val() * 1;
    total+= ivapercep * 1;
    
    agenteDePercepcionIIBB = $("#"+formulario+" #CompraIibbpercep").val() * 1;
    total+= agenteDePercepcionIIBB * 1;

    agenteDePercepcionActividadesVarias = $("#"+formulario+" #CompraActvspercep").val() * 1;
    total+= agenteDePercepcionActividadesVarias * 1;
    
    impinternos = $("#"+formulario+" #CompraImpinternos").val() * 1;
    total+= impinternos * 1;

    impcombustible = $("#"+formulario+" #CompraImpcombustible").val() * 1;
    total+= impinternos * 1;
      
    $("#"+formulario+" #CompraTotal").val(total)
    $("#"+formulario+" #CompraIva").val(iva);
  }
  function adaptarConceptorestanteForm(formnombre,conid){

    hiddeAllImputsFromConceptosRestantesAddForm(formnombre,conid);
      var impcliseleccionado = $("#ConceptosrestanteImpcliId").val();
      if(impcliseleccionado==null) return;
      var impuestoseleccionadoNombre = $('#ConceptosrestanteImpcliId option[value="' + impcliseleccionado + '"]').html();
      var impuestoseleccionado = $('#ConceptosrestanteImpclisid option[value="' + impcliseleccionado + '"]').html();
    switch (impuestoseleccionado){
      case '21':/*Actividades Economicas*/
      case '174':/*Convenio Multilateral*/
        $('#'+formnombre+' #ConceptosrestanteLocalidadeId').closest('div').hide();
        $('#'+formnombre+' #ConceptosrestantePartidoId').closest('div').show();
      break;
      default:
        $('#'+formnombre+' #ConceptosrestanteLocalidadeId').closest('div').show();
        $('#'+formnombre+' #ConceptosrestantePartidoId').closest('div').hide();
      break;
    }
    switch($("#"+formnombre+" #ConceptosrestanteConceptostipoId").val()*1){
      case 1:/*Saldos A Favor*/
        $('#'+formnombre+' #ConceptosrestanteDescripcion').closest('div').show();
        break;     
      case 2:/*Retenciones*/
        switch(impuestoseleccionado){
          case '21':/*Actividades Economicas*/
            $('#'+formnombre+' #ConceptosrestanteAgente').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteMonto').closest('div').show();
          break
          case '160':/*Ganancia(PF)*/
          case '5':/*Ganancia(PJ)*/
            $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteRegimen').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteDescripcion').closest('div').show();
            /*falta:
            numero certificado
            decha comprobante
            descripcion comprobante
            Fecha Registración DJ Ag Ret
            */
          case '4':/*IVA*/
            $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteRegimen').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteDescripcion').closest('div').show();
            /*falta:
            numero certificado
            decha comprobante
            descripcion comprobante
            Fecha Registración DJ Ag Ret
            */
          break;
          case '6':/*Actividades Varias*/
            $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteNumeropadron').closest('div').show();
            /*falta:
            denominacion
            */
          break;
          case '10':/*SUSS*/
            $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteRegimen').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteDescripcion').closest('div').show();
          break;
          default:
            $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteComprobanteId').closest('div').show();
          break;
        }
      case 3:/*Recaudacion Bancaria*/
        switch(impuestoseleccionado){
          case 21:/*Actividades Economicas*/
            $('#'+formnombre+' #ConceptosrestanteAgente').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteMonto').closest('div').show();
          break
          default:
            $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteAnticipo').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteCbu').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteTipocuenta').closest('div').show();
            $('#'+formnombre+' #ConceptosrestanteTipomoneda').closest('div').show();
          break;
        }
        break;
      case 4:/*Otros*/
        $('#'+formnombre+' #ConceptosrestanteDescripcion').closest('div').show();
        break;
      case 5:/*Percepciones Aduaneras*/
        $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
        $('#'+formnombre+' #ConceptosrestanteNumerodespachoaduanero').closest('div').show();
        break;
      case 6:/*Credito por adicional*/
        break;
      case 7:/*Anticipos especiales*/
        break;
      case 8:/*Pago en bonos*/
        $('#'+formnombre+' #ConceptosrestanteDescripcion').closest('div').show();
        break;
      case 9:/*Compensaciones*/
        $('#'+formnombre+' #ConceptosrestanteDescripcion').closest('div').show();
        break;
      case 10:/*Pagos a Cuenta*/
        $('#'+formnombre+' #ConceptosrestanteEnterecaudador').closest('div').show();
        $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
        break;
      case 11:/*SICREB*/
        $('#'+formnombre+' #ConceptosrestanteAgente').closest('div').show();
        $('#'+formnombre+' #ConceptosrestanteCbu').closest('div').show();
        $('#'+formnombre+' #ConceptosrestanteTipocuenta').closest('div').show();
        $('#'+formnombre+' #ConceptosrestanteTipomoneda').closest('div').show();
        break;
    }     
  }
  function reloadInputDates(){
    var d = new Date( );
    d.setMonth( d.getMonth( ) - 1 );
    (function($){
       $( "input.datepicker" ).datepicker({
        yearRange: "-100:+50",
        changeMonth: true,
        changeYear: true,
        constrainInput: false,
        dateFormat: 'dd-mm-yy',
        defaultDate: d,
      });
      $( "input.datepicker-dia" ).datepicker({
        yearRange: "-100:+50",
        changeMonth: false,
        changeYear: false,
        constrainInput: false,
        dateFormat: 'dd',
        defaultDate: d,

      });
    })(jQuery);
    
  }
  /*Show Hide from Tabs*/
    function showVentas(){
      $('.tabVentas').show();
      $('.tabCompras').hide();
      $('.tabNovedades').hide();
      $('.tabConceptosrestantes').hide();
    }
    function showCompras(){
      $('.tabVentas').hide();
      $('.tabNovedades').hide();
      $('.tabCompras').show();
      $('.tabConceptosrestantes').hide();
    }
    function showNovedades(){
      $('.tabVentas').hide();
      $('.tabNovedades').show();
      $('.tabCompras').hide();
      $('.tabConceptosrestantes').hide();
    }
    function showConceptosRestantes(){
      $('.tabVentas').hide();
      $('.tabNovedades').hide();
      $('.tabCompras').hide();
      $('.tabConceptosrestantes').show();
    }
  
  /*Hide Formulario para modificar*/
    function hideFormModVenta(venid){
      //$(".tdViewVenta"+venid).show();
      $("#rowventa"+venid).find('td').each(function(){
        $(this).show();
      })
      $("#tdventa"+venid).remove(); 
    }
    function hideFormModCompra(comid){
      $(".tdViewCompra"+comid).show();
      $("#tdcompra"+comid).remove(); 
    }
    function hideFormModSueldo(comid){
      $(".tdViewSueldo"+comid).show();
      $("#tdsueldo"+comid).remove(); 
    }
  
  function modificarVenta(venid){  
    var tieneMonotributo = $("#VentaTieneMonotributo").val();
    var tieneIVA = $("#VentaTieneIVA").val();
    var tieneIVAPercepciones = $("#VentaTieneIVAPercepciones").val();
    var tieneImpuestoInterno = $("#VentaTieneImpuestoInterno").val();
    var tieneAgenteDePercepcionActividadesVarias = $("#VentaTieneAgenteDePercepcionActividadesVarias").val();
    var tieneAgenteDePercepcionIIBB = $("#VentaTieneAgenteDePercepcionIIBB").val();
    tieneMonotributo ? tieneMonotributo = true : tieneMonotributo = false;
    tieneIVA ? tieneIVA = true : tieneIVA = false;
    tieneIVAPercepciones ? tieneIVAPercepciones = true : tieneIVAPercepciones = false;
    tieneImpuestoInterno ? tieneImpuestoInterno = true : tieneImpuestoInterno = false;
    tieneAgenteDePercepcionActividadesVarias ? tieneAgenteDePercepcionActividadesVarias = true : tieneAgenteDePercepcionActividadesVarias = false;
    tieneAgenteDePercepcionIIBB ? tieneAgenteDePercepcionIIBB = true : tieneAgenteDePercepcionIIBB = false;
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/ventas/edit/"+venid+"/"+tieneMonotributo+"/"+tieneIVAPercepciones+"/"+tieneImpuestoInterno+"/"+tieneIVA+"/"+tieneAgenteDePercepcionIIBB+"/"+tieneAgenteDePercepcionActividadesVarias,

        // URL to request
        data: data,  // post data
        success: function(response) {
          var oldRow = $("#rowventa"+venid).html();
          var rowid="rowventa"+venid;
          $("#rowventa"+venid).find('td').each(function(){
            $(this).hide();
          })
          $("#"+rowid).html(response);

          //$(".tdViewVenta"+venid).hide();
          
          reloadInputDates();
          //Aca vamos a agregar los controles para este formulario tal cual los ejecutamos al agregar una venta
          $("#VentaFormEdit"+venid+" #VentaIvapercep").on('change paste', function() {
            calcularivaytotal("VentaFormEdit"+venid);    
          });
          $("#VentaFormEdit"+venid+" #VentaImpinternos").on('change paste', function() {
            calcularivaytotal("VentaFormEdit"+venid);
          });
          $("#VentaFormEdit"+venid+" #VentaActvspercep").on('change paste', function() {
            calcularivaytotal("VentaFormEdit"+venid);
          });
          $("#VentaFormEdit"+venid+" #VentaIibbpercep").on('change paste', function() {
            calcularivaytotal("VentaFormEdit"+venid);
          });
          $("#VentaFormEdit"+venid+" #VentaAlicuota").on('change paste', function() {
            calcularivaytotal("VentaFormEdit"+venid);
          });
          $("#VentaFormEdit"+venid+" #VentaTotal").on('change paste', function() {
            calcularivaytotal("VentaFormEdit"+venid);
          });
          $("#VentaFormEdit"+venid+" #VentaNeto").on('change paste', function() {
            calcularivaytotal("VentaFormEdit"+venid);
          });
          $("#VentaFormEdit"+venid+" #VentaComprobanteId").on('change paste', function() {
            allcomprobantes.forEach(function(comprobante) {
              if($("#VentaFormEdit"+venid+" #VentaComprobanteId").val()==comprobante.Comprobante.id){
                if(tipodecomprobanteseleccionado==comprobante.Comprobante.tipo){
                  return;
                }
                if(comprobante.Comprobante.tipo=="A"){
                  $("#VentaFormEdit"+venid+" #VentaNeto").prop("readonly",false);
                  $("#VentaFormEdit"+venid+" #VentaIva").prop("readonly",true);
                  $("#VentaFormEdit"+venid+" #VentaTotal").prop("readonly",true);
                  tipodecomprobanteseleccionado="A";
                  //$('#VentaFormEdit'+venid+' #VentaAlicuota').find("option:not(:hidden):eq(0)");

                }else  if(comprobante.Comprobante.tipo=="B"){
                  $("#VentaFormEdit"+venid+" #VentaNeto").prop('readonly', true);
                  $("#VentaFormEdit"+venid+" #VentaIva").prop('readonly', true);
                  $("#VentaFormEdit"+venid+" #VentaTotal").prop('readonly', false);
                  tipodecomprobanteseleccionado="B";
                 // $('#VentaFormEdit'+venid+' #VentaAlicuota').find("option:not(:hidden):eq(0)");
                }else  if(comprobante.Comprobante.tipo=="C"){
                  tipodecomprobanteseleccionado="C";
                 // $('#VentaFormEdit'+venid+' #VentaAlicuota').find("option:not(:hidden):eq(0)");
                }
                

              }
            }, this);
          });
          $('.chosen-select').chosen({search_contains:true});
          $('#VentaFormEdit'+venid).submit(function(){ 
            //serialize form data 
            var formData = $(this).serialize(); 
            //get form action 
            var formUrl = $(this).attr('action'); 
                                        
            $.ajax({ 
              type: 'POST', 
              url: formUrl, 
              data: formData, 
              success: function(data,textStatus,xhr){ 
                    try
                    {
                       var respuesta = JSON.parse(data);
                       callAlertPopint(respuesta.respuesta);
                    }
                    catch(e)
                    {
                      var rowid="rowventa"+venid;
                      $("#"+rowid).html( data); 
                    }
                                                 
                }, 
              error: function(xhr,textStatus,error){ 
                alert(textStatus); 
              } 
            }); 
            return false; 
          });
        },                
       error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
       }
    });
  }
  function eliminarVenta(venid){
      var r = confirm("Esta seguro que desea eliminar esta venta?. Es una accion que no podra deshacer.");
      if (r == true) {
        $.ajax({
           type: "post",  // Request method: post, get
           url: serverLayoutURL+"/ventas/delete/"+venid, // URL to request
           data: "",  // post data
           success: function(response) {
                        var mirespuesta = jQuery.parseJSON(response);
                        if(mirespuesta.error==0){
                          callAlertPopint(mirespuesta.respuesta);
                          $('#tablaVentas').DataTable().row('#rowventa'+venid)
                              .remove()
                              .draw();
                          calcularFooterTotales($('#tablaVentas').DataTable());
                        }else if(mirespuesta.error==1){
                           callAlertPopint(mirespuesta.respuesta);
                        }else{
                          callAlertPopint("Error por favor intente mas tarde");
                        }        
                        
                   },
           error:function (XMLHttpRequest, textStatus, errorThrown) {
                  alert(textStatus);
                  alert(XMLHttpRequest);
                  alert(errorThrown);
          }
        });
      } else {
          txt = "No se a eliminado la venta";
          callAlertPopint(txt);
      }/*
      */
  }
  function modificarCompra(comid){  
    var data ="";
    var tieneMonotributo = $("#VentaTieneMonotributo").val();
    var tieneIVA = $("#VentaTieneIVA").val();
    var tieneIVAPercepciones = $("#VentaTieneIVAPercepciones").val();
    var tieneImpuestoInterno = $("#VentaTieneImpuestoInterno").val();
    var tieneAgenteDePercepcionActividadesVarias = $("#VentaTieneAgenteDePercepcionActividadesVarias").val();
    var tieneAgenteDePercepcionIIBB = $("#VentaTieneAgenteDePercepcionIIBB").val();
    tieneMonotributo ? tieneMonotributo = true : tieneMonotributo = false;
    tieneIVA ? tieneIVA = true : tieneIVA = false;
    tieneIVAPercepciones ? tieneIVAPercepciones = true : tieneIVAPercepciones = false;
    tieneImpuestoInterno ? tieneImpuestoInterno = true : tieneImpuestoInterno = false;
    tieneAgenteDePercepcionActividadesVarias ? tieneAgenteDePercepcionActividadesVarias = true : tieneAgenteDePercepcionActividadesVarias = false;
    tieneAgenteDePercepcionIIBB ? tieneAgenteDePercepcionIIBB = true : tieneAgenteDePercepcionIIBB = false;

    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/compras/edit/"+comid+"/"+tieneMonotributo+"/"+tieneIVAPercepciones+"/"+tieneImpuestoInterno+"/"+tieneIVA+"/"+tieneAgenteDePercepcionIIBB+"/"+tieneAgenteDePercepcionActividadesVarias,

        // URL to request
        data: data,  // post data
        success: function(response) {
            var oldRow = $("#rowcompra"+comid).html();
            var rowid="#rowcompra"+comid;
            $(rowid).find('td').each(function(){
                $(this).hide();
            })
            $(rowid).html(response);

            // var button = '<a href="#" class="btn_cancelar" onClick="hideFormModCompra('+comid+')">X</a>';
            //$("#tdventa"+comid).append(button);
            reloadInputDates();
            //Aca vamos a agregar los controles para este formulario tal cual los ejecutamos al agregar una compra
            $("#CompraFormEdit"+comid+" #CompraIvapercep").on('change paste', function() {
                calcularivaytotalcompra("CompraFormEdit"+comid);
            });
            $("#CompraFormEdit"+comid+" #CompraImpinternos").on('change paste', function() {
                calcularivaytotalcompra("CompraFormEdit"+comid);
            });
            $("#CompraFormEdit"+comid+" #CompraActvspercep").on('change paste', function() {
                calcularivaytotalcompra("CompraFormEdit"+comid);
            });
            $("#CompraFormEdit"+comid+" #CompraIibbpercep").on('change paste', function() {
                calcularivaytotalcompra("CompraFormEdit"+comid);
            });
            $("#CompraFormEdit"+comid+" #CompraAlicuota").on('change paste', function() {
                calcularivaytotalcompra("CompraFormEdit"+comid);
            });
            $("#CompraFormEdit"+comid+" #CompraTotal").on('change paste', function() {
                calcularivaytotalcompra("CompraFormEdit"+comid);
            });
            $("#CompraFormEdit"+comid+" #CompraNeto").on('change paste', function() {
                calcularivaytotalcompra("CompraFormEdit"+comid);
            });
            $("#CompraFormEdit"+comid+" #CompraComprobanteId").on('change paste', function() {
                allcomprobantes.forEach(function(comprobante) {
                if($("#CompraFormEdit"+comid+" #CompraComprobanteId").val()==comprobante.Comprobante.id){
                    if(tipodecomprobanteCompraseleccionado==comprobante.Comprobante.tipo){
                        return;
                    }
                if(comprobante.Comprobante.tipo=="A"){
                  //Preparar para recibir Neto y calcular IVA y Total
                  $("#CompraFormEdit"+comid+" #CompraNeto").prop("readonly",false);
                  $("#CompraFormEdit"+comid+" #CompraIva").prop("readonly",true);
                  $("#CompraFormEdit"+comid+" #CompraImputacion").prop("readonly",false);
                  $("#CompraFormEdit"+comid+" #CompraIvapercep").prop('readonly', false);
                  $("#CompraFormEdit"+comid+" #CompraTotal").prop("readonly",true);
                  //Permitir editar los campos relacionados a IVA 
                  tipodecomprobanteCompraseleccionado = 'A';
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='monotributista']").hide();
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='responsableinscripto']").show();
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='consf/exento/noalcanza']").show();

                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='0']").hide();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='2.5']").show();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='5']").show();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='10.5']").show();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='21']").show();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='27']").show();          
                  $("#CompraAlicuota").val($("#CompraAlicuota option:eq(1)").val());
                }else  if(comprobante.Comprobante.tipo=="B"){
                  $("#CompraFormEdit"+comid+" #CompraNeto").prop("readonly",false);
                  $("#CompraFormEdit"+comid+" #CompraIva").prop("readonly",true);
                  $("#CompraFormEdit"+comid+" #CompraImputacion").prop("readonly",true);
                  $("#CompraFormEdit"+comid+" #CompraIvapercep").prop('readonly', true);
                  $("#CompraFormEdit"+comid+" #CompraTotal").prop("readonly",false);

                  $("#CompraFormEdit"+comid+" #CompraIva").val(0);
                  $("#CompraFormEdit"+comid+" #CompraImputacion").val(0);
                  $("#CompraFormEdit"+comid+" #CompraIvapercep").val(0);
                  tipodecomprobanteCompraseleccionado = 'B';
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='monotributista']").hide();
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='responsableinscripto']").show();
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='consf/exento/noalcanza']").show();

                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='0']").show();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='2.5']").hide();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='5']").hide();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='10.5']").hide();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='21']").hide();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='27']").hide();
                  $("#CompraAlicuota").val($("#CompraAlicuota option:eq(0)").val());
                }else  if(comprobante.Comprobante.tipo=="C"){
                  $("#CompraFormEdit"+comid+" #CompraNeto").prop("readonly",false);
                  $("#CompraFormEdit"+comid+" #CompraIva").prop("readonly",true);
                  $("#CompraFormEdit"+comid+" #CompraImputacion").prop("readonly",true);
                  $("#CompraFormEdit"+comid+" #CompraIvapercep").prop('readonly', true);
                  $("#CompraFormEdit"+comid+" #CompraTotal").prop("readonly",false);

                  $("#CompraFormEdit"+comid+" #CompraIva").val(0);
                  $("#CompraFormEdit"+comid+" #CompraImputacion").val(0);
                  $("#CompraFormEdit"+comid+" #CompraIvapercep").val(0);
                  tipodecomprobanteCompraseleccionado = 'C';
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='monotributista']").show();
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='responsableinscripto']").hide();
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='consf/exento/noalcanza']").hide();

                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='0']").show();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='2.5']").hide();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='5']").hide();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='10.5']").hide();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='21']").hide();
                  $("#CompraFormEdit"+comid+" #CompraAlicuota option[value='27']").hide();
                  $("#CompraAlicuota").val($("#CompraAlicuota option:eq(0)").val());
                }

              }
            }, this);
          });
          $('.chosen-select').chosen({search_contains:true});

          $('#CompraFormEdit'+comid).submit(function(){ 
            //serialize form data 
            var formData = $(this).serialize(); 
            //get form action 
            var formUrl = $(this).attr('action'); 
                                        
            $.ajax({ 
              type: 'POST', 
              url: formUrl, 
              data: formData, 
              success: function(data,textStatus,xhr){ 
                    try
                    {
                       var respuesta = JSON.parse(data);
                       callAlertPopint(respuesta.respuesta);
                    }
                    catch(e)
                    {
                      var rowid="rowcompra"+comid;
                      $("#"+rowid).html( data);
                    } 

                }, 
              error: function(xhr,textStatus,error){ 
                alert(textStatus); 
              } 
            }); 
            return false; 
          });
        },                
       error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
       }
    });
  }
  function eliminarCompra(comid){
      var r = confirm("Esta seguro que desea eliminar esta compra?. Es una accion que no podra deshacer.");
      if (r == true) {
        $.ajax({
           type: "post",  // Request method: post, get
           url: serverLayoutURL+"/compras/delete/"+comid, // URL to request
           data: "",  // post data
           success: function(response) {
                        var mirespuesta = jQuery.parseJSON(response);
                        if(mirespuesta.error==0){
                          callAlertPopint(mirespuesta.respuesta);
                          $('#rowcompra'+comid).remove();
                        }else if(mirespuesta.error==1){
                           callAlertPopint(mirespuesta.respuesta);
                        }else{
                          callAlertPopint("Error por favor intente mas tarde");
                        }

                   },
           error:function (XMLHttpRequest, textStatus, errorThrown) {
                  alert(textStatus);
                  alert(XMLHttpRequest);
                  alert(errorThrown);
          }
        });
      } else {
          txt = "No se a eliminado la compra";
          callAlertPopint(txt);
      }/*
      */
  }
    function eliminarConceptosrestante(conresid){
        var r = confirm("Esta seguro que desea eliminar este pago a cuenta?. Es una accion que no podra deshacer.");
        if (r == true) {
            $.ajax({
                type: "post",  // Request method: post, get
                url: serverLayoutURL+"/conceptosrestantes/delete/"+conresid, // URL to request
                data: "",  // post data
                success: function(response) {
                    var mirespuesta = jQuery.parseJSON(response);
                    if(mirespuesta.error==0){
                        callAlertPopint(mirespuesta.respuesta);
                        $('#rowconceptorestante'+conresid).remove();
                    }else if(mirespuesta.error==1){
                        callAlertPopint(mirespuesta.respuesta);
                    }else{
                        callAlertPopint("Error por favor intente mas tarde");
                    }

                },
                error:function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(textStatus);
                    alert(XMLHttpRequest);
                    alert(errorThrown);
                }
            });
        } else {
            txt = "No se a eliminado la compra";
            callAlertPopint(txt);
        }/*
         */
    }
  function modificarSueldo(sueid){  
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/sueldos/edit/"+sueid,

        // URL to request
        data: data,  // post data
        success: function(response) {
          var oldRow = $("#rowsueldo"+sueid).html();
          var rowid="rowsueldo"+sueid;
          $("#"+rowid).html(oldRow + response);
         // var button = '<a href="#" class="btn_cancelar" onClick="hideFormModVenta('+venid+')">X</a>';
          //$("#tdventa"+venid).append(button); 
          $(".tdViewSueldo"+sueid).hide();
          reloadInputDates();
          $('#SueldoFormEdit'+sueid).submit(function(){ 
            //serialize form data 
            var formData = $(this).serialize(); 
            //get form action 
            var formUrl = $(this).attr('action'); 
                                        
            $.ajax({ 
              type: 'POST', 
              url: formUrl, 
              data: formData, 
              success: function(data,textStatus,xhr){ 
                    var rowid="rowsueldo"+sueid;
                    $("#"+rowid).html(data);               
                }, 
              error: function(xhr,textStatus,error){ 
                alert(textStatus); 
              } 
            }); 
            return false; 
          });
        },                
       error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
            alert(XMLHttpRequest);
            alert(errorThrown);
       }
    });
  }
  function eliminarSueldo(sueid){
      var r = confirm("Esta seguro que desea eliminar este sueldo?. Es una accion que no podra deshacer.");
      if (r == true) {
        $.ajax({
           type: "post",  // Request method: post, get
           url: serverLayoutURL+"/sueldos/delete/"+sueid, // URL to request
           data: "",  // post data
           success: function(response) {
                        var mirespuesta = jQuery.parseJSON(response);
                        if(mirespuesta.error==0){
                          callAlertPopint(mirespuesta.respuesta);
                          $('#rowsueldo'+sueid).remove();
                        }else if(mirespuesta.error==1){
                           callAlertPopint(mirespuesta.respuesta);
                        }else{
                          callAlertPopint("Error por favor intente mas tarde");
                        }        
                        
                   },
           error:function (XMLHttpRequest, textStatus, errorThrown) {
                  alert(textStatus);
                  alert(XMLHttpRequest);
                  alert(errorThrown);
          }
        });
      } else {
          txt = "No se a eliminado el sueldo";
          callAlertPopint(txt);
      }/*
      */
  }
  function modificarConceptosrestante(conid){  
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/conceptosrestantes/edit/"+conid,

        // URL to request
        data: data,  // post data
        success: function(response) {
            //var oldRow = $("#rowconceptorestante"+conid).html();
            var rowid="rowconceptorestante"+conid;
            var oldrow = $("#"+rowid).html();
            $("#"+rowid).html(oldrow+response);
            $(".tdViewConceptosrestanteO"+conid).hide();
          reloadInputDates();
          $('.chosen-select').chosen({search_contains:true});
          adaptarConceptorestanteForm("ConceptosRestanteFormEdit"+conid,conid)
          $('#ConceptosRestanteFormEdit'+conid).submit(function(){ 
            //serialize form data 
            var formData = $(this).serialize(); 
            //get form action 
            var formUrl = $(this).attr('action'); 
                                        
            $.ajax({ 
              type: 'POST', 
              url: formUrl, 
              data: formData, 
              success: function(response2,textStatus,xhr){
                  var respuesta = JSON.parse(response2);
                  callAlertPopint(respuesta.respuesta);
                  var conceptoCargado = respuesta.conceptosrestante.Conceptosrestante
                  if(conceptoCargado!=null){
                      //Agregar la fila nueva a la tabla
                      var  tdClass = "tdViewConceptosrestante"+conceptoCargado.id;
                      var partido = "";
                      var localidad = "";
                      var impcliseleccionado = $("#ConceptosrestanteImpcliId").val();
                      var impuestoseleccionado = $('#ConceptosrestanteImpclisid option[value="' + impcliseleccionado + '"]').html();
                      switch (impuestoseleccionado){
                          //estos impuestos se pagan por provincia todos los otros por localidad
                          case '21':/*Actividades Economicas*/
                          case '174':/*Convenio Multilateral*/
                              partido = respuesta.conceptosrestante.Partido.nombre;
                              break;
                          default:
                              localidad =  respuesta.conceptosrestante.Localidade.Partido.nombre+'-'+respuesta.conceptosrestante.Localidade.nombre;
                              break;
                      }
                      var rowData =
                          [
                              respuesta.conceptosrestante.Impcli.Impuesto.nombre,
                              partido,
                              localidad,
                              respuesta.conceptosrestante.Conceptostipo.nombre,
                              respuesta.conceptosrestante.Comprobante.nombre,
                              conceptoCargado.numerocomprobante,
                              conceptoCargado.rectificativa,
                              conceptoCargado.razonsocial,
                              conceptoCargado.monto,
                              conceptoCargado.montoretenido,
                              conceptoCargado.cuit,
                              conceptoCargado.fecha,
                              conceptoCargado.numerodespachoaduanero,
                              conceptoCargado.anticipo,
                              conceptoCargado.cbu,
                              conceptoCargado.tipocuenta,
                              conceptoCargado.tipomoneda,
                              conceptoCargado.agente,
                              conceptoCargado.enterecaudador,
                              conceptoCargado.regimen,
                              conceptoCargado.descripcion,
                              conceptoCargado.numeropadron
                          ];
                      var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="modificarConceptosrestante('+conceptoCargado.id+')" alt="">';
                      tdactions = tdactions + '<img src="'+serverLayoutURL+'/img/eliminar.png" width="20" height="20" onclick="eliminarConceptosrestante('+conceptoCargado.id+')" alt="">';
                      rowData.push(tdactions);

                      var tdid = "#tdconceptosrestante"+conid;
                      $(tdid).remove();

                      $(".tdViewConceptosrestanteO"+conid).show();
                      var rowid="#rowconceptorestante"+conid;
                      tblTablaConceptosRestantes = $('#tblTablaConceptosrestantes').dataTable().api();
                      var mirows = tblTablaConceptosRestantes.rows();
                      var mirow = tblTablaConceptosRestantes.row();
                      var mirowId = tblTablaConceptosRestantes.row(rowid)._fnAjaxUpdateDraw();
                      var mirowIdData = tblTablaConceptosRestantes.row(rowid).data();
                      var mirowIdNewData = tblTablaConceptosRestantes.row(rowid).data(rowData);
                      var drawRow = tblTablaConceptosRestantes.row(rowid).draw();
                      var drawTable = tblTablaConceptosRestantes.draw();

                          //.data(rowData)

                        /*TODO: ReDraw pendiente
                        *aca nos falta completar el "redibujado de la tabla, se actualizan los datos pero
                        * no se por que no se redibuja la tabla */
                  }
                }, 
              error: function(xhr,textStatus,error){ 
                alert(textStatus); 
              } 
            }); 
            return false; 
          });
        },                
       error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
       }
    });
  }

function cargarSueldoEmpleado(clienteid,periodo,empid,liquidacion){
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/empleados/papeldetrabajosueldos/"+clienteid+"/"+periodo+"/"+empid+"/"+liquidacion, // URL to request
        data: data,  // post data
        success: function(response) {
            $(".btn_empleados_liq ").each(function(){
                $(this).removeClass("btn_empleados_selected");
            });
            $(".btn_empleados").each(function(){
                $(this).removeClass("btn_empleados_selected");
            });
            $("#buttonEmpleado"+empid).addClass("btn_empleados_selected");
            $("#divSueldoForm").html(response);
            activarCalXOnSueldos();
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
            return false;
        }
    });
    return false;
}
function cargarLibroSueldo(empid,periodo){
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/empleados/papeldetrabajolibrosueldo/"+empid+"/"+periodo, // URL to request
        data: data,  // post data
        success: function(response) {
            $("#sueldoContent").html(response);
        },
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
            return false;
        }
    });
    return false;
}
var tablaSueldoCalx;

function activarCalXOnSueldos(){
    $(".funcionAAplicar").on('change', function() {
        var posicion = $(this).attr('posicion');
          $('#ValorreciboPapeldetrabajosueldosForm').calx(
            'getCell',
            $('#Valorrecibo'+posicion+'Valor').attr('data-cell')
        ).setFormula($(this).val());
        tablaSueldoCalx.calx('calculate');
    });
    tablaSueldoCalx = $('#ValorreciboPapeldetrabajosueldosForm').calx({
        language : 'id'
    });
    tablaSueldoCalx.submit(function(){
        //serialize form data
        var formData = $(this).serialize();
        //get form action
        var formUrl = $(this).attr('action');
        $.ajax({
            type: 'POST',
            url: formUrl,
            data: formData,
            success: function(data,textStatus,xhr){
                callAlertPopint("Sueldo guardado, los totales se han recalculado.");
                $("#divSueldoForm").html(data);
                var empid = $("#Valorrecibo0EmpleadoId").val();
                $("#buttonEmpleado"+empid).addClass("btn_empleados_liq");
                activarCalXOnSueldos();
            },
            error: function(xhr,textStatus,error){
                callAlertPopint(textStatus);
            }
        });
        return false;
      });
}
function realizarEventoCliente(periodo,clienteid,estadotarea){
  var datas =  "0/tarea3/"+periodo+"/"+clienteid;
  var data ="";
  $.ajax({
    type: "post",  // Request method: post, get
    url: serverLayoutURL+"/eventosclientes/realizareventocliente/0/tarea3/"+periodo+"/"+clienteid+"/"+estadotarea, // URL to request
    data: data,  // post data
    success: function(response) {
      var resp = response.split("&&");
      var respuesta=resp[1];
      var error=resp[0];
      if(error!=0){
        callAlertPopint('Error por favor intente mas tarde');
        return;
      }else{
        callAlertPopint('Tarea Cargar modificada. Estado:'+estadotarea);
      }
      return false;
    },
    error:function (XMLHttpRequest, textStatus, errorThrown) {
      alert(textStatus);
      return false;
    }
  });
  return false;
}
