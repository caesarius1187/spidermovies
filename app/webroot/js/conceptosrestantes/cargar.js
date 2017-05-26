var tblTablaConceptosrestantes;
jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
    return this.flatten().reduce( function ( a, b ) {
        if ( typeof a === 'string' ) {
            a = a.replace(/[^\d.-]/g, '') * 1;
        }
        if ( typeof b === 'string' ) {
            b = b.replace(/[^\d.-]/g, '') * 1;
        }

        return a + b;
    }, 0 );
} );
$(document).ready(function() {
    var beforePrint = function() {
        $('#header').hide();
        $('#Formhead').hide();
        $('#headerCliente').hide();
        $('#divAllTabs').hide();
        $('#index').css('border-color','#FFF');
    };
    var afterPrint = function() {
        //$('#index').css('font-size','10px');
        $('#header').show();
        $('#Formhead').show();
        $('#form_empleados').show();
        $('#headerCliente').show();
        $('#divAllTabs').show();
    };
    if (window.matchMedia) {
        var mediaQueryList = window.matchMedia('print');
        mediaQueryList.addListener(function(mql) {
            if (mql.matches) {
                beforePrint();
            } else {
                afterPrint();
            }
        });
    }
    window.onbeforeprint = beforePrint;
    window.onafterprint = afterPrint;

    tblTablaConceptosrestantes = $('#tblTablaConceptosrestantes').DataTable( {
        drawCallback: function () {
            var api = this.api();
            $( api.table().footer() ).html(
                $('<tr>').append(
                    $('<td>').html(
                        "Monto (o Saldo si IVA) $" + api.column( 8, {page:'current'} ).data().sum() +
                        " Monto Retenido (o SLD si IVA) $" + api.column( 9, {page:'current'} ).data().sum()
                    )//8
                )
            );
        }
    });
    // tblTablaConceptosrestantes .on( 'search.dt', function () {
    //     calcularFooterTotales(tblTablaConceptosrestantes);
    // } );
    // calcularFooterTotales(tblTablaConceptosrestantes);
    var nombrecliente = $('#clientenombre').val();
    var periodo = $('#periododefault').val();


    var tblTablaConceptosRestantes = $('#tblTablaConceptosrestantes').dataTable().api();

    $('.chosen-select').chosen({search_contains:true});

    conceptosRestantesOnChange();
    addFormSubmitCatchs();
    reloadInputDates();
    function hiddeAllImputsFromConceptosRestantesAddForm(){
        $('#saveConceptosrestantesForm div').hide();

        var impcliseleccionado = $("#ConceptosrestanteImpcliId").val();
        var impuestoseleccionado = $('#ConceptosrestanteImpclisid option[value="' + impcliseleccionado + '"]').html();

          for (var i = 24 - 1; i >= 0; i--) {
              var columni = tblTablaConceptosrestantes.column(i);
              columni.visible( false );
          };

            switch (impuestoseleccionado){
                  //estos impuestos se pagan por provincia todos los otros por localidad
                  case '21':/*Actividades Economicas*/
                  case '174':/*Convenio Multilateral*/
                      $('#ConceptosrestanteLocalidadeId').closest('div').hide();
                      $('#ConceptosrestantePartidoId').closest('div').show();
                      showcolumnConceptoRestante(tblTablaConceptosrestantes,1,true);//Partido
                      break;
                  default:
                      $('#ConceptosrestanteLocalidadeId').closest('div').show();
                      $('#ConceptosrestantePartidoId').closest('div').hide();
                      showcolumnConceptoRestante(tblTablaConceptosrestantes,2,true);//Localidad
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
        showcolumnConceptoRestante(tblTablaConceptosrestantes,25,true);//Actions*/
    }
    function showcolumnConceptoRestante(table,column,visible){
        var column = table.column( column );
        column.visible( visible );
    }
    /*Cargar Asientos de Venta automaticos*/
    function pad (str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }
    function conceptosRestantesOnChange(){
        //Esta funcion asigna el comportamiento del formulario Conceptosrestante buscando que impuesto se ha seleccionado y que tipo de concepto
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
                case '19'/*IVA*/:
                    $("#ConceptosrestanteConceptostipoId").children("option[value=12]").show();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=14]").show();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=15]").show();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=16]").show();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=17]").show();
                    $('#ConceptosrestanteConceptostipoId option:contains("Saldo a Favor")').text('Saldo Libre Disponibilidad');
                    $('.chosen-select').trigger("chosen:updated");
                    break;
                case '6'/*Actividades Varias*/:
                    $('#ConceptosrestanteConceptostipoId option:contains("Saldo Libre Disponibilidad")').text('Saldo a Favor');
                    $("#ConceptosrestanteConceptostipoId").children('option').hide();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=1]").show();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=2]").show();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=4]").show();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=10]").show();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=13]").show();
                    $('.chosen-select').trigger("chosen:updated");
                    break;
                case '21':/*Actividades Economicas*/
                case '174':/*Convenio Multilateral*/
                    $('#ConceptosrestanteConceptostipoId option:contains("Saldo Libre Disponibilidad")').text('Saldo a Favor');
                    $("#ConceptosrestanteConceptostipoId").children('option').hide();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=1]").show();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=2]").show();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=3]").show();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=4]").show();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=5]").show();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=10]").show();
                    $('.chosen-select').trigger("chosen:updated");

                    $('#ConceptosrestanteLocalidadeId').closest('div').hide();
                    $('#ConceptosrestanteLocalidadeId').val("");

                    $('#ConceptosrestantePartidoId').closest('div').show();
                    showcolumnConceptoRestante(tblTablaConceptosrestantes,1,true);/*Partido*/
                    showcolumnConceptoRestante(tblTablaConceptosrestantes,2,false);/*Localidad*/
                    break;
                default:
                    $('#ConceptosrestanteConceptostipoId option:contains("Saldo Libre Disponibilidad")').text('Saldo a Favor');
                    $("#ConceptosrestanteConceptostipoId").children('option').show();
                    //Vamos a esconder todos los que sean unicamente del IVA
                    $("#ConceptosrestanteConceptostipoId").children("option[value=12]").hide();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=14]").hide();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=15]").hide();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=16]").hide();
                    $("#ConceptosrestanteConceptostipoId").children("option[value=17]").hide();
                    $('.chosen-select').trigger("chosen:updated");
                    $('#ConceptosrestanteLocalidadeId').closest('div').show();
                    $('#ConceptosrestantePartidoId').closest('div').hide();
                    $('#ConceptosrestantePartidoId').val("");
                    showcolumnConceptoRestante(tblTablaConceptosrestantes,2,true);/*Localidad*/
                    showcolumnConceptoRestante(tblTablaConceptosrestantes,1,false);/*Partido*/
                    break;
            }
            $("label[for='ConceptosrestanteMonto']").html("Monto Op.");
            $("label[for='ConceptosrestanteMontoretenido']").html("Monto");
            $( "#ConceptosrestanteMontoretenido").unbind( "change" );
            switch($("#ConceptosrestanteConceptostipoId").val()*1){

                case 1:/*Saldos A Favor*/
                    $('#ConceptosrestanteDescripcion').closest('div').show();
                    showcolumnConceptoRestante(tblTablaConceptosrestantes,20,true);/*Descripcion*/
                    switch(impuestoseleccionado) {
                        case '19':/*IVA*/
                            $('#ConceptosrestanteMonto').closest('div').show();
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,8,true);/*Monto*/
                            break;
                    }
                    $('#ConceptosrestanteMontoretenido').change(function(){
                        $('#ConceptosrestanteMonto').val($(this).val());
                    });
                    $("label[for='ConceptosrestanteMonto']").html("Saldo");
                    $("label[for='ConceptosrestanteMontoretenido']").html("Monto");

                    break;
                case 2:/*Retenciones*/
                    $("label[for='ConceptosrestanteMontoretenido']").html("Monto Retenido");
                    switch(impuestoseleccionado){
                        case '174':/*Convenio Multilateral*/
                            $('#ConceptosrestanteCuit').closest('div').show();
                            $('#ConceptosrestanteNumerofactura').closest('div').show();
                            $('#ConceptosrestantePuntosdeventa').closest('div').show();
                            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
                            $('#ConceptosrestanteOrdendepago').closest('div').show();
                            $('#ConceptosrestanteComprobanteId').closest('div').show();
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,4,true);/*TipoComptobante*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,22,true);/*PuntoDeVenta*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,23,true);/*numeroFactura*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,24,true);/*Orden de pago*/
                        break
                        case '21':/*Actividades Economicas*/
                            $('#ConceptosrestanteNumeropadron').closest('div').show();
                            $('#ConceptosrestanteAgente').closest('div').show();
                            $('#ConceptosrestanteCuit').closest('div').show();
                            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
                            $('#ConceptosrestanteOrdendepago').closest('div').show();
                            $('#ConceptosrestanteMonto').closest('div').show();
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,22,true);/*NumeroPadron*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,17,true);/*Agente*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,8,true);/*Monto*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,24,true);/*Orden de pago*/
                            break
                        case '160':/*Ganancia(PF)*/
                        case '5':/*Ganancia(PJ)*/
                            $('#ConceptosrestanteCuit').closest('div').show();
                            $('#ConceptosrestanteRegimen').closest('div').show();
                            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
                            $('#ConceptosrestanteDescripcion').closest('div').show();
                            $('#ConceptosrestanteOrdendepago').closest('div').show();
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,19,true);/*Regimen*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,20,true);/*Descripcion*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,24,true);/*Orden de pago*/
                        /*falta:
                         numero certificado
                         decha comprobante
                         descripcion comprobante
                         Fecha Registración DJ Ag Ret
                         */
                        case '19':/*IVA*/
                            $('#ConceptosrestanteCuit').closest('div').show();
                            $('#ConceptosrestanteRegimen').closest('div').show();
                            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
                            $('#ConceptosrestanteDescripcion').closest('div').show();
                            $('#ConceptosrestanteOrdendepago').closest('div').show();
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,19,true);/*Regimen*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,20,true);/*Descripcion*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,24,true);/*Orden de pago*/
                            /*falta:
                             numero certificado
                             decha comprobante
                             descripcion comprobante
                             Fecha Registración DJ Ag Ret
                             */
                            break;
                        case '6':/*Actividades Varias*/
                            $('#ConceptosrestanteCuit').closest('div').show();
                            $('#ConceptosrestanteAgente').closest('div').show();
                            $('#ConceptosrestanteNumeropadron').closest('div').show();
                            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
                            $('#ConceptosrestanteOrdendepago').closest('div').show();
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,22,true);/*NumeroPadron*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,17,true);/*Agente*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,24,true);/*Orden de pago*/
                            /*falta:
                                                        denominacion
                                                        */
                            break;
                        case '10':/*SUSS*/
                            $('#ConceptosrestanteCuit').closest('div').show();
                            $('#ConceptosrestanteRegimen').closest('div').show();
                            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
                            $('#ConceptosrestanteDescripcion').closest('div').show();
                            $('#ConceptosrestanteOrdendepago').closest('div').show();
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,19,true);/*Regimen*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,20,true);/*Descripcion*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,24,true);/*Orden de pago*/
                            break;
                        default:
                            $('#ConceptosrestanteCuit').closest('div').show();
                            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
                            $('#ConceptosrestanteComprobanteId').closest('div').show();
                            $('#ConceptosrestanteOrdendepago').closest('div').show();
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,4,true);/*TipoComptobante*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,24,true);/*Orden de pago*/
                            break;
                    }
                    break;
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
                    //showcolumnConceptoRestante(tblTablaConceptosrestantes,18,true);/*EnteRecaudador*/
                    //showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
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
    }
    function addFormSubmitCatchs(){
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
                                if (!(typeof respuesta.conceptosrestante.Partido.nombre === 'undefined' || respuesta.conceptosrestante.Partido.nombre === null)) {
                                    partido = ""+respuesta.conceptosrestante.Partido.nombre;
                                }
                                break;
                            default:
                                if (!(typeof respuesta.conceptosrestante.Localidade.Partido === 'undefined' || respuesta.conceptosrestante.Localidade === null)) {
                                    localidad =  ""+respuesta.conceptosrestante.Localidade.Partido.nombre+'-'+respuesta.conceptosrestante.Localidade.nombre;
                                }
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
    }
});
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
function setTwoNumberDecimal(event) {
        this.value = parseFloat(this.value).toFixed(2);
    }
function addTolblTotalDebeAsieto(event) {
        var debesubtotal = 0;
        $(".inputDebe").each(function () {
            debesubtotal = debesubtotal*1 + this.value*1;
        });
        $("#lblTotalDebe").text(parseFloat(debesubtotal).toFixed(2)) ;
        showIconDebeHaber()
    }
function addTolblTotalhaberAsieto(event) {
    //        $("#lblTotalAFavor").val(0) ;
        var habersubtotal = 0;
        $(".inputHaber").each(function () {
            habersubtotal = habersubtotal*1 + this.value*1;
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
function calcularFooterTotales(mitabla){
            mitabla.columns( '.sum' ).every( function () {
                try {
                    var micolumndata = this.flatten();
                    var columnLength = this.flatten().length;
                    if(columnLength > 0){
                        var sum = this
                            .flatten()
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
                                    var resultado = a + b;
                                    return resultado;
                                } else {
                                    return 0;
                                }
                            } );
                        if (typeof sum === 'string') {
                            sum = sum.replace('.', "");
                            sum = sum.replace(',', ".");
                            $( this.footer() ).html((sum*1).toFixed(2));
                        }else{
                            $( this.footer() ).html(sum.toFixed(2));
                        }
                    }
                }
                catch (e)
                {
                    alert(e.message);
                }
            } );
        }
    var tipodecomprobanteseleccionado = 'A';
    var tipodecomprobanteCompraseleccionado = '';

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
                switch(impuestoseleccionado) {
                    case '19':
                        $('#' + formnombre + ' #ConceptosrestanteMonto').closest('div').show();
                        break;
                }
                break;
            case 2:/*Retenciones*/
                switch(impuestoseleccionado){
                    case '174':/*Convenio Multilateral*/
                        $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteNumerofactura').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestantePuntosdeventa').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteOrdendepago').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteComprobanteId').closest('div').show();
                        break
                    case '21':/*Actividades Economicas*/
                        $('#'+formnombre+' #ConceptosrestanteNumeropadron').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteAgente').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteOrdendepago').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteMonto').closest('div').show();
                        break
                    case '160':/*Ganancia(PF)*/
                    case '5':/*Ganancia(PJ)*/
                        $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteRegimen').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteDescripcion').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteOrdendepago').closest('div').show();
                        break;
                    case '19':/*IVA*/
                        $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteRegimen').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteDescripcion').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteOrdendepago').closest('div').show();
                        break;
                    case '6':/*Actividades Varias*/
                        $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteAgente').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteNumeropadron').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteOrdendepago').closest('div').show();

                        break;
                    case '10':/*SUSS*/
                        $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteRegimen').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteDescripcion').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteOrdendepago').closest('div').show();
                        break;
                    default:
                        $('#'+formnombre+' #ConceptosrestanteCuit').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteNumerocomprobante').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteComprobanteId').closest('div').show();
                        $('#'+formnombre+' #ConceptosrestanteOrdendepago').closest('div').show();
                        break;
                }
                break;
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

/*Update Pagos a cuenta*/
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
                        $('#tblTablaConceptosrestantes').dataTable().fnDeleteRow($("#rowconceptorestante"+conresid));
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
                //var oldrow = $("#"+rowid).html();
                //$("#"+rowid).html(oldrow+response);
                //$(".tdViewConceptosrestanteO"+conid).hide();
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModal').find('.modal-title').html('Editar Venta');
                    $('#myModal').find('.modal-body').html(response);
                    // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                });

                $('#myModal').modal('show');
                // $('#myModal input[0]').focus();

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
                                        if (!(typeof respuesta.conceptosrestante.Partido.nombre === 'undefined' || respuesta.conceptosrestante.Partido.nombre === null)) {
                                            partido = ""+respuesta.conceptosrestante.Partido.nombre;
                                        }
                                        break;
                                    default:
                                        if (!(typeof respuesta.conceptosrestante.Localidade.Partido === 'undefined' || respuesta.conceptosrestante.Localidade === null)) {
                                            localidad =  ""+respuesta.conceptosrestante.Localidade.Partido.nombre+'-'+respuesta.conceptosrestante.Localidade.nombre;
                                        }
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
                                        conceptoCargado.numeropadron,
                                        conceptoCargado.ordendepago
                                    ];
                                var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="modificarConceptosrestante('+conceptoCargado.id+')" alt="">';
                                tdactions = tdactions + '<img src="'+serverLayoutURL+'/img/eliminar.png" width="20" height="20" onclick="eliminarConceptosrestante('+conceptoCargado.id+')" alt="">';
                                rowData.push(tdactions);

                                $('#tblTablaConceptosrestantes').dataTable().fnDeleteRow($("#rowconceptorestante"+conid));

                                var rowIndex = $('#tblTablaConceptosrestantes').dataTable().fnAddData(rowData);
                                var row = $('#tblTablaConceptosrestantes').dataTable().fnGetNodes(rowIndex);
                                $(row).attr( 'id', "rowconceptorestante"+respuesta.conid );
                                $('#myModal').modal('hide');

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
/*fin Update Pagos a cuenta*/
    function realizarEventoCliente(periodo,clienteid,estadotarea){
    var datas =  "0/tarea3/"+periodo+"/"+clienteid;
    var data ="";
    $.ajax({
    type: "post",  // Request method: post, get
    url: serverLayoutURL+"/eventosclientes/realizareventocliente/0/pagosacuentacarados/"+periodo+"/"+clienteid+"/"+estadotarea, // URL to request
    data: data,  // post data
    success: function(response) {
      var resp = response.split("&&");
      var respuesta=resp[1];
      var error=resp[0];
      if(error!=0){
        callAlertPopint('Error por favor intente mas tarde');
        return;
      }else{
        callAlertPopint('Estado de la tarea modificado');
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
    function usosSLD(conid){
        var data ="";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/usosaldos/index/"+conid,
            // URL to request
            data: data,  // post data
            success: function(response) {
                //var oldRow = $("#rowconceptorestante"+conid).html();
                $("#divUsosaldos").html(response);
                location.href="#popinUsosaldos";
                $('#UsosaldoAddForm').submit(function(){
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
                            return false;
                        },
                        error: function(xhr,textStatus,error){
                            alert(textStatus);
                            return false;
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

