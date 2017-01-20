var tblTablaVentas;
var tblTablaCompras;
$(document).ready(function() {
    var beforePrint = function() {
        $('#header').hide();
        $('#Formhead').hide();
        $('#headerCliente').hide();
        $('#divAllTabs').hide();
        $('#formOldSueldo').hide();
        $('#form_empleados').hide();
        $('.btn_empleados').hide();
        $('.btn_empleados_liq').hide();
        $('.btn_cargarliq_selected').hide();
        $('.btn_cargarliq_liq').hide();
        $('.btn_cargarliq').hide();
        $('.btn_sueldo').hide();
        $('.btn_imprimir').hide();
        $('#divLiquidarActividadesVariar').hide();
        $('#TituloPdtSueldo').hide();
        //$('#index').css('float','left');
        //$('#padding').css('padding','0px');
        //$('#padding').css('margin','0px');
        //$('#index').css('font-size','6px');
        $('#index').css('border-color','#FFF');
        //$('#sueldoContent').css('width','648px');
        //$('#tblLibroSueldo').css('padding','0');
        //$('#sheetCooperadoraAsistencial').css('width','100%');
        //$('#sheetCooperadoraAsistencial').css('margin','0');
        //$('#tblLibroSueldo').css('font-size','9px');
    };
    var afterPrint = function() {
        //$('#index').css('font-size','10px');
        $('#header').show();
        $('#Formhead').show();
        $('#form_empleados').show();
        $('#headerCliente').show();
        $('#divAllTabs').show();
        $('#formOldSueldo').show();
        $('.btn_empleados').show();
        $('.btn_empleados_liq').show();
        $('.btn_cargarliq_selected').show();
        $('.btn_cargarliq_liq').show();
        $('.btn_cargarliq').show();
        $('.btn_sueldo').show();
        $('.btn_imprimir').show();
        $('#divLiquidarActividadesVariar').show();
        $('#TituloPdtSueldo').show();
        //$('#index').css('float','right');
        //$('#padding').css('padding','10px 1%');
        //$('#sueldoContent').css('width','1340px');
       // $('#tblLibroSueldo').css('padding','0 10%');
        //$('#sheetCooperadoraAsistencial').css('margin','10px 25px');
        //$('#sheetCooperadoraAsistencial').css('width','96%');
        //$('#tblLibroSueldo').css('font-size','12px');
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

    allcomprobantes =  JSON.parse($('#jsonallcomprobantes').val());
    var tblTablaConceptosrestantes = $('#tblTablaConceptosrestantes').dataTable().api();
    var nombrecliente = $('#clientenombre').val();
    var periodo = $('#periododefault').val();

    //Ahora la tabla de ventas la vamos a tener que cargar por ajax asi que las configuraciones de esta tabla deben
    //hacerse una vez finalizada esa carga
    cargaryconfigurarTablaVentas();

    tblTablaCompras = $('#tablaCompras').DataTable( {
        dom: 'Bfrtip',
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10', '25', '50', 'todas' ]
        ],
        buttons: [
            {
                extend: 'collection',
                text: 'Columnas',
                autoClose: true,
                buttons: [
                    {
                        text: 'Conmutar Fecha',
                        action: function ( e, dt, node, config ) {
                            dt.column( 0 ).visible( ! dt.column( 0 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Comprobante',
                        action: function ( e, dt, node, config ) {
                            dt.column( 1 ).visible( ! dt.column( 1 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Provedor',
                        action: function ( e, dt, node, config ) {
                            dt.column( 2 ).visible( ! dt.column( 2 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Cond. IVA',
                        action: function ( e, dt, node, config ) {
                            dt.column( 3 ).visible( ! dt.column( 3 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Actividad',
                        action: function ( e, dt, node, config ) {
                            dt.column( 4 ).visible( ! dt.column( 4 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Localidad',
                        action: function ( e, dt, node, config ) {
                            dt.column( 5 ).visible( ! dt.column( 5 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Tipo Credito',
                        action: function ( e, dt, node, config ) {
                            dt.column( 6 ).visible( ! dt.column( 6 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Tipo Gasto',
                        action: function ( e, dt, node, config ) {
                            dt.column( 7 ).visible( ! dt.column( 7 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Tipo IVA',
                        action: function ( e, dt, node, config ) {
                            dt.column( 8 ).visible( ! dt.column( 8 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Imputacion',
                        action: function ( e, dt, node, config ) {
                            dt.column( 9 ).visible( ! dt.column( 9 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Alicuota',
                        action: function ( e, dt, node, config ) {
                            dt.column( 10 ).visible( ! dt.column( 10 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Neto',
                        action: function ( e, dt, node, config ) {
                            dt.column( 11 ).visible( ! dt.column( 11 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar IVA',
                        action: function ( e, dt, node, config ) {
                            dt.column( 12 ).visible( ! dt.column( 12 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar IVA Percep',
                        action: function ( e, dt, node, config ) {
                            dt.column( 13 ).visible( ! dt.column( 13 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar IIBB Percep',
                        action: function ( e, dt, node, config ) {
                            dt.column( 14 ).visible( ! dt.column( 14 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Act.Vs.Percep',
                        action: function ( e, dt, node, config ) {
                            dt.column( 15 ).visible( ! dt.column( 15 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Imp. Interno',
                        action: function ( e, dt, node, config ) {
                            dt.column( 16 ).visible( ! dt.column( 16 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Imp. Comb.',
                        action: function ( e, dt, node, config ) {
                            dt.column( 17 ).visible( ! dt.column( 17 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar No Gravados',
                        action: function ( e, dt, node, config ) {
                            dt.column( 18 ).visible( ! dt.column( 18 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Exentos',
                        action: function ( e, dt, node, config ) {
                            dt.column( 19 ).visible( ! dt.column( 19 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Total',
                        action: function ( e, dt, node, config ) {
                            dt.column( 21 ).visible( ! dt.column( 21 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar KW',
                        action: function ( e, dt, node, config ) {
                            dt.column( 20 ).visible( ! dt.column( 20 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Acciones',
                        action: function ( e, dt, node, config ) {
                            dt.column( -1 ).visible( ! dt.column( -1 ).visible() );
                        }
                    }
                ]
            },
            {
                extend: 'pageLength',
                text: 'Ver',
            },
            {
                extend: 'csv',
                text: 'CSV',
                title: 'LibroIVA-Compras-'+nombrecliente+'-'+periodo,
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                text: 'Excel',
                title: 'LibroIVA-Compras-'+nombrecliente+'-'+periodo,
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                text: 'PDF',
                title: 'LibroIVA-Compras-'+nombrecliente+'-'+periodo,
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                download: 'open',
                message: 'LibroIVA-Compras-'+nombrecliente+'-'+periodo+'</br>Domicilio:',/*todo: completar Domicilios de Libro IVA Ventas*/

            },
            {
                extend: 'copy',
                text: 'Copiar',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print',
                text: 'Imprimir',
                exportOptions: {
                    columns: ':visible'
                },
                orientation: 'landscape',
                message: 'LibroIVA-Compras-'+nombrecliente+'-'+periodo+'</br>Domicilio:',/*todo: completar Domicilios de Libro IVA Ventas*/
            },
        ],
    } );
    var tblTablaConceptosRestantes = $('#tblTablaConceptosrestantes').dataTable().api();
    var tblTablaMovimientosBancarios = $('#tblTablaMovimientosBancarios').dataTable().api();

    calcularFooterTotales(tblTablaCompras);
    $('.chosen-select').chosen({search_contains:true});

    comprasOnChange();
    conceptosRestantesOnChange();
    addFormSubmitCatchs();
    tabsFuncionalidad();
    cctxconeptosOnChange();
    function cargaryconfigurarTablaVentas(){

        var data="";
        var clienteid = $('#cliid').val();
        var periodo = $('#periodo').val();
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/ventas/index/"+clienteid+"/"+periodo,

            // URL to request
            data: data,  // post data
            success: function(response) {
                $("#divTablaVentas").html(response);
                tblTablaVentas = $('#tablaVentas').DataTable( {
                    dom: 'Bfrtip',
                    lengthMenu: [
                        [ 10, 25, 50, -1 ],
                        [ '10', '25', '50', 'todas' ]
                    ],
                    buttons: [
                        {
                            extend: 'collection',
                            text: 'Columnas',
                            autoClose: true,
                            buttons: [
                                {
                                    text: 'Conmutar Fecha',
                                    action: function ( e, dt, node, config ) {
                                        dt.column( 0 ).visible( ! dt.column( 0 ).visible() );
                                    }
                                },
                                {
                                    text: 'Conmutar Comprobante',
                                    action: function ( e, dt, node, config ) {
                                        dt.column( 1 ).visible( ! dt.column( 1 ).visible() );
                                    }
                                },
                                {
                                    text: 'Conmutar CUIT',
                                    action: function ( e, dt, node, config ) {
                                        dt.column( 2 ).visible( ! dt.column( 2 ).visible() );
                                    }
                                },
                                {
                                    text: 'Conmutar Nombre',
                                    action: function ( e, dt, node, config ) {
                                        dt.column( 3 ).visible( ! dt.column( 3 ).visible() );
                                    }
                                },
                                {
                                    text: 'Conmutar Condicion IVA',
                                    action: function ( e, dt, node, config ) {
                                        dt.column( 4 ).visible( ! dt.column( 4 ).visible() );
                                    }
                                },
                                {
                                    text: 'Conmutar Actividad',
                                    action: function ( e, dt, node, config ) {
                                        dt.column( 5 ).visible( ! dt.column( 5 ).visible() );
                                    }
                                },
                                {
                                    text: 'Conmutar Localidad',
                                    action: function ( e, dt, node, config ) {
                                        dt.column( 6 ).visible( ! dt.column( 6 ).visible() );
                                    }
                                },
                                {
                                    text: 'Conmutar Debito',
                                    action: function ( e, dt, node, config ) {
                                        dt.column( 7 ).visible( ! dt.column( 7 ).visible() );
                                    }
                                },
                                {
                                    text: 'Conmutar Acciones',
                                    action: function ( e, dt, node, config ) {
                                        dt.column( -1 ).visible( ! dt.column( -1 ).visible() );
                                    }
                                }
                            ]
                        },
                        {
                            extend: 'pageLength',
                            text: 'Ver',
                        },
                        {
                            extend: 'csv',
                            text: 'CSV',
                            title: 'LibroIVA-Ventas-'+nombrecliente+'-'+periodo,
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            title: 'LibroIVA-Ventas-'+nombrecliente+'-'+periodo,
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF',
                            title: 'LibroIVA-Ventas-'+nombrecliente+'-'+periodo,
                            exportOptions: {
                                columns: ':visible'
                            },
                            orientation: 'landscape',
                            download: 'open',
                            message: 'LibroIVA-Ventas-'+nombrecliente+'-'+periodo+'</br>Domicilio:',/*todo: completar Domicilios de Libro IVA Ventas*/

                        },
                        {
                            extend: 'copy',
                            text: 'Copiar',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'print',
                            text: 'Imprimir',
                            exportOptions: {
                                columns: ':visible'
                            },
                            orientation: 'landscape',
                            message: 'LibroIVA-Ventas-'+nombrecliente+'-'+periodo+'</br>Domicilio:',/*todo: completar Domicilios de Libro IVA Ventas*/
                        },
                    ],
                } );
                $('#bodyTablaVentas').width("100%");
                calcularFooterTotales(tblTablaVentas);
                ventasOnChange();
                $('#saveVentasForm').submit(function(){
                    var formData = $(this).serialize();
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
                                var positivo = 1;
                                if(respuesta.venta.Venta.tipodebito=='Restitucion debito fiscal'){
                                    positivo = positivo*-1;
                                }
                                var rowData =
                                    [
                                        respuesta.venta.Venta.fecha,
                                        respuesta.comprobante.Comprobante.abreviacion+"-"+respuesta.puntosdeventa.Puntosdeventa.nombre+"-"+respuesta.venta.Venta.numerocomprobante,
                                        respuesta.subcliente.Subcliente.cuit,
                                        respuesta.subcliente.Subcliente.nombre,
                                        respuesta.venta.Venta.condicioniva,
                                        respuesta.actividadcliente.Actividade.nombre,
                                        respuesta.localidade.Localidade.nombre,
                                    ];

                                if(!respuesta.tieneMonotributo){
                                    rowData.push(respuesta.venta.Venta.tipodebito);
                                    rowData.push(respuesta.venta.Venta.alicuota+"%");
                                    rowData.push(respuesta.venta.Venta.neto*positivo);
                                    rowData.push(respuesta.venta.Venta.iva*positivo);
                                }
                                if(respuesta.tieneIVAPercepciones){
                                    rowData.push(respuesta.venta.Venta.ivapercep*positivo);
                                }
                                if(respuesta.tieneAgenteDePercepcionIIBB){
                                    rowData.push(respuesta.venta.Venta.iibbpercep*positivo);
                                }
                                if(respuesta.tieneAgenteDePercepcionActividadesVarias){
                                    rowData.push(respuesta.venta.Venta.actvspercep*positivo);
                                }
                                if(respuesta.tieneImpuestoInterno){
                                    rowData.push(respuesta.venta.Venta.impinternos*positivo);
                                }
                                if(!respuesta.tieneMonotributo) {
                                    rowData.push(respuesta.venta.Venta.nogravados * positivo);
                                    rowData.push(respuesta.venta.Venta.excentos * positivo);
                                }
                                rowData.push(respuesta.venta.Venta.exentosactividadeseconomicas*positivo);
                                rowData.push(respuesta.venta.Venta.exentosactividadesvarias*positivo);
                                rowData.push(respuesta.venta.Venta.total*positivo);
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
                //Fin Configuracion Tabla Ventas
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
                alert(XMLHttpRequest);
                alert(errorThrown);
            }
        });



    }
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
    showcolumnConceptoRestante(tblTablaConceptosrestantes,24,true);//Actions*/
  }
    function showcolumnConceptoRestante(table,column,visible){
        var column = table.column( column );
        column.visible( visible );
      }


    /*Cargar Asientos de Venta automaticos*/
    $('#buttonCargarAsientosVenta').bind('click',function (e) {
        e.preventDefault();
        var data="";
        var clienteid = $('#cliid').val();
        var periodo = $('#periodo').val();
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/asientos/contabilizarventa/"+clienteid+"/"+periodo,

            // URL to request
            data: data,  // post data
            success: function(response) {

                $('#myModal').on('show.bs.modal', function() {
                    $('#myModal').find('.modal-title').html('Asiento automatico de venta');
                    $('#myModal').find('.modal-body').html(response);
                    // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
                });
                $('#myModal').modal('show');
                $('.chosen-select-cuenta').chosen({
                    search_contains:true,
                    include_group_label_in_selected:true
                });
                
                $('#AsientoAddForm').submit(function(){
                    //serialize form data
                    var formData = $(this).serialize();
                    //get form action
                    var formUrl = $(this).attr('action');
                    //aca tenemos que sacar todos los disabled para que se envien los campos
                    $('#AsientoAddForm input').each(function(){
                        $(this).removeAttr('disabled');
                    });
                    $.ajax({
                        type: 'POST',
                        url: formUrl,
                        data: formData,
                        success: function(data,textStatus,xhr){
                            var respuesta = JSON.parse(data);
                            callAlertPopint(respuesta.respuesta);
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
    });
    $('#buttonCargarAsientosCompra').bind('click',function (e) {
        e.preventDefault();
        var data="";
        var clienteid = $('#cliid').val();
        var periodo = $('#periodo').val();
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
                    //serialize form data
                    var formData = $(this).serialize();
                    //get form action
                    var formUrl = $(this).attr('action');
                    //aca tenemos que sacar todos los disabled para que se envien los campos
                    $('#AsientoAddForm input').each(function(){
                        $(this).removeAttr('disabled');
                    });
                    $.ajax({
                        type: 'POST',
                        url: formUrl,
                        data: formData,
                        success: function(data,textStatus,xhr){
                            var respuesta = JSON.parse(data);
                            callAlertPopint(respuesta.respuesta);
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
    });

    function ventasOnChange(){
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
                    $("#VentaTipodebito").trigger("chosen:updated");

                    if(comprobante.Comprobante.tipo=="A"){
                        $("#VentaNeto").prop("readonly",false);
                        $("#VentaIva").prop("readonly",true);
                        $("#VentaTotal").prop("readonly",true);
                        tipodecomprobanteseleccionado = 'A';
                        $("#CompraCondicioniva option[value='monotributista']").show();
                        $("#CompraCondicioniva option[value='responsableinscripto']").show();
                        $("#CompraCondicioniva option[value='consf/exento/noalcanza']").hide();
                    }else  if(comprobante.Comprobante.tipo=="B"){
                        $("#VentaNeto").prop('readonly', true);
                        $("#VentaIva").prop('readonly', true);
                        $("#VentaTotal").prop('readonly', false);
                        tipodecomprobanteseleccionado = 'B';
                        $("#CompraCondicioniva option[value='monotributista']").show();
                        $("#CompraCondicioniva option[value='responsableinscripto']").show();
                        $("#CompraCondicioniva option[value='consf/exento/noalcanza']").hide();
                    }else  if(comprobante.Comprobante.tipo=="C"){
                        tipodecomprobanteseleccionado = 'C';
                        $("#CompraCondicioniva option[value='monotributista']").show();
                        $("#CompraCondicioniva option[value='responsableinscripto']").show();
                        $("#CompraCondicioniva option[value='consf/exento/noalcanza']").hide();
                    }
                }
            }, this);
        });
        $( "#VentaComprobanteId" ).trigger( "change" );
        reloadInputDates();
        showVentas();
    }
    function comprasOnChange(){
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
        $("#CompraNogravados").on('change paste', function() {
            calcularivaytotalcompra("saveComprasForm");
        });
        $("#CompraExentos").on('change paste', function() {
            calcularivaytotalcompra("saveComprasForm");
        });


        $("#CompraCondicioniva").on('change paste', function() {
            $("#CompraComprobanteId" ).trigger( "change" );
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
                        $("#CompraCondicioniva option[value='monotributista']").show();
                        $("#CompraCondicioniva option[value='responsableinscripto']").show();
                        $("#CompraCondicioniva option[value='consf/exento/noalcanza']").hide();
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
                        $("#CompraCondicioniva option[value='monotributista']").show();
                        $("#CompraCondicioniva option[value='responsableinscripto']").show();
                        $("#CompraCondicioniva option[value='consf/exento/noalcanza']").hide();
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
                        $("#CompraCondicioniva option[value='responsableinscripto']").show();
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
                            $('#ConceptosrestanteComprobanteId').closest('div').show();
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,4,true);/*TipoComptobante*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,22,true);/*PuntoDeVenta*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,23,true);/*numeroFactura*/
                        break
                        case '21':/*Actividades Economicas*/
                            $('#ConceptosrestanteNumeropadron').closest('div').show();
                            $('#ConceptosrestanteAgente').closest('div').show();
                            $('#ConceptosrestanteCuit').closest('div').show();
                            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
                            $('#ConceptosrestanteMonto').closest('div').show();
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,22,true);/*NumeroPadron*/
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
                        case '19':/*IVA*/
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
                            $('#ConceptosrestanteAgente').closest('div').show();
                            $('#ConceptosrestanteNumeropadron').closest('div').show();
                            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,5,true);/*NumeroComprobante*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,10,true);/*CUIT*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,22,true);/*NumeroPadron*/
                            showcolumnConceptoRestante(tblTablaConceptosrestantes,17,true);/*Agente*/
                            /*falta:
                             denominacion
                             */
                            break;
                        case '10':/*SUSS*/
                            $('#ConceptosrestanteCuit').closest('div').show();
                            $('#ConceptosrestanteRegimen').closest('div').show();
                            $('#ConceptosrestanteNumerocomprobante').closest('div').show();
                            $('#ConceptosrestanteDescripcion').closest('div').show();
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

                        var positivo = 1;
                        if(respuesta.compra.Compra.tipocredito=='Restitucion credito fiscal'){
                            positivo= -1;
                        }else{
                            positivo = 1;
                        }

                        //Agregar Row Nueva a la tabla
                        var rowData =
                            [
                                respuesta.compra.Compra.fecha,
                                respuesta.comprobante.Comprobante.abreviacion+"-"+respuesta.compra.Compra.puntosdeventa+"-"+respuesta.compra.Compra.numerocomprobante,
                                respuesta.provedore.Provedore.nombre,
                                respuesta.compra.Compra.condicioniva,
                                respuesta.actividadcliente.Actividade.nombre,
                                respuesta.localidade.Localidade.nombre,
                                respuesta.compra.Compra.tipocredito,
                                respuesta.tipogasto.Tipogasto.nombre,
                                respuesta.compra.Compra.tipoiva,
                                respuesta.compra.Compra.imputacion,
                                respuesta.compra.Compra.alicuota+"%",
                                respuesta.compra.Compra.neto*positivo,
                                respuesta.compra.Compra.iva*positivo,
                                respuesta.compra.Compra.ivapercep*positivo,
                                respuesta.compra.Compra.iibbpercep*positivo,
                                respuesta.compra.Compra.actvspercep*positivo,
                                respuesta.compra.Compra.impinternos*positivo,
                                respuesta.compra.Compra.impcombustible*positivo,
                                respuesta.compra.Compra.nogravados*positivo,
                                respuesta.compra.Compra.exentos*positivo,
                                respuesta.compra.Compra.total*positivo,
                                respuesta.compra.Compra.kw*positivo,
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
        $('#saveMovimientosbancariosForm').submit(function(){
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
                    var conceptoCargado = respuesta.movimiento.Movimientosbancario
                    if(conceptoCargado!=null){
                        //Agregar la fila nueva a la tabla
                        var  tdClass = "tdViewMovimientosBancario"+respuesta.movimientoid;
                        var rowData =
                            [
                                conceptoCargado.cbu_id,
                                conceptoCargado.fecha,
                                conceptoCargado.concepto,
                                conceptoCargado.debito,
                                conceptoCargado.credito,
                                conceptoCargado.saldo,
                                conceptoCargado.cuentascliente_id,
                                conceptoCargado.codigoafip
                            ];
                        var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="modificarMovimientosbancario('+respuesta.movimientoid +')" alt="">';
                        tdactions = tdactions + '<img src="'+serverLayoutURL+'/img/eliminar.png" width="20" height="20" onclick="eliminarMovimientosbancario('+respuesta.movimientoid +')" alt="">';
                        rowData.push(tdactions);

                        var rowIndex = $('#tblTablaMovimientosBancarios').dataTable().fnAddData(rowData);
                        var row = $('#tblTablaMovimientosBancarios').dataTable().fnGetNodes(rowIndex);
                        $(row).attr( 'id', "rowmovimientosbancarios"+respuesta.movimientoid );
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

    }
    function tabsFuncionalidad(){
        /*TABs On Click*/
        $("#tabVentas" ).click(function() {
            $("#tabVentas" ).switchClass( 'cliente_view_tab', 'cliente_view_tab_active');
            $("#tabCompras" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabNovedades" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabConceptosrestantes" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabBancos" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            showVentas();
        });
        $("#tabCompras" ).click(function() {
            $("#tabCompras" ).switchClass( 'cliente_view_tab', 'cliente_view_tab_active');
            $("#tabVentas" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabNovedades" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabConceptosrestantes" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabBancos" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            showCompras();
        });
        $("#tabNovedades" ).click(function() {
            $("#tabNovedades" ).switchClass( 'cliente_view_tab', 'cliente_view_tab_active');
            $("#tabCompras" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabVentas" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabConceptosrestantes" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabBancos" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            showNovedades();
        });
        $("#tabConceptosrestantes" ).click(function() {
            $("#tabNovedades" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabCompras" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabVentas" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabConceptosrestantes" ).switchClass( 'cliente_view_tab', 'cliente_view_tab_active');
            $("#tabBancos" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            showConceptosRestantes();
        });
        $("#tabBancos" ).click(function() {
            $("#tabNovedades" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabCompras" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabVentas" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabConceptosrestantes" ).switchClass( 'cliente_view_tab_active', 'cliente_view_tab');
            $("#tabBancos" ).switchClass( 'cliente_view_tab', 'cliente_view_tab_active');
            showBancos();
        });
        /*Fin TABs On Click*/
    }
    function cctxconeptosOnChange(){
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
    }
});
function contabilizarBanco(periodo,cliid,bancimpcli,cbuid){
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
                //serialize form data
                var formData = $(this).serialize();
                //get form action
                var formUrl = $(this).attr('action');
                //aca tenemos que sacar todos los disabled para que se envien los campos
                $('#AsientoAddForm input').each(function(){
                    $(this).removeAttr('disabled');
                });
                $.ajax({
                    type: 'POST',
                    url: formUrl,
                    data: formData,
                    success: function(data,textStatus,xhr){
                        var respuesta = JSON.parse(data);
                        $('#myModal').modal('hide');
                        callAlertPopint(respuesta.respuesta);
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
function openWin()
{
    var myWindow=window.open('','','width=1010,height=1000px');
    myWindow.document.write('<html><head><title>Recibo de sueldo</title><link rel="stylesheet" type="text/css" href="'+serverLayoutURL+'/css/cake.generic.css"></head><body>');
    myWindow.document.write($("#divToPrintRecibo").html());
    myWindow.document.close();
    myWindow.focus();
    setTimeout(
        function()
        {
            myWindow.print();
            myWindow.close();
        }, 1000);

}
function openWinLibroSueldo()
{
    var myWindow=window.open('','','width=1010,height=1000px');
    myWindow.document.write('<html><head><title>Libro de sueldo</title><link rel="stylesheet" type="text/css" href="'+serverLayoutURL+'/css/cake.generic.css"></head><body>');
    myWindow.document.write($("#sheetCooperadoraAsistencial").html());
    myWindow.document.close();
    myWindow.focus();
    setTimeout(
        function()
        {
            myWindow.print();
            myWindow.close();
        }, 1000);

}
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
            $("#"+formulario+" #VentaAlicuota").val(0);
            $("#"+formulario+" #VentaIva").val(0);
            total = $("#"+formulario+" #VentaTotal").val()*1;
            $("#"+formulario+" #VentaNeto").val(total*1);
            $("#"+formulario+" #VentaTotal").val(total*1);
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
        var nogravados=0;
        var exentos=0;
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
        total+= impcombustible * 1;

        nogravados = $("#"+formulario+" #CompraNogravados").val() * 1;
        total+= nogravados * 1;

        exentos = $("#"+formulario+" #CompraExentos").val() * 1;
        total+= exentos * 1;

        $("#"+formulario+" #CompraTotal").val(total);
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
                    $('#'+formnombre+' #ConceptosrestanteComprobanteId').closest('div').show();
                    break
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
                    break;
                case '19':/*IVA*/
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
        $('.tabBancos').hide();
    }
    function showCompras(){
      $('.tabVentas').hide();
      $('.tabNovedades').hide();
      $('.tabCompras').show();
      $('.tabConceptosrestantes').hide();
        $('.tabBancos').hide();
    }
    function showNovedades(){
      $('.tabVentas').hide();
      $('.tabNovedades').show();
      $('.tabCompras').hide();
      $('.tabConceptosrestantes').hide();
        $('.tabBancos').hide();
    }
    function showConceptosRestantes(){
      $('.tabVentas').hide();
      $('.tabNovedades').hide();
      $('.tabCompras').hide();
      $('.tabConceptosrestantes').show();
        $('.tabBancos').hide();
    }
    function showBancos(){
        $('.tabVentas').hide();
        $('.tabNovedades').hide();
        $('.tabCompras').hide();
        $('.tabConceptosrestantes').hide();
        $('.tabBancos').show();
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
/*Update Ventas*/
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
        data: data,  // post data
        success: function(response) {
            //var oldRow = $("#rowventa"+venid).html();
            var rowid="rowventa"+venid;
            // $("#rowventa"+venid).find('td').each(function(){
            //   $(this).hide();
            // })
            // $("#"+rowid).html(response);
            $('#myModal').on('show.bs.modal', function() {
                $('#myModal').find('.modal-title').html('Editar Venta');
                $('#myModal').find('.modal-body').html(response);
                // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
            });

            $('#myModal').modal('show');
            // $('#myModal input[0]').focus();
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
            var formData = $(this).serialize();
            var formUrl = $(this).attr('action');
            $.ajax({
              type: 'POST',
              url: formUrl,
              data: formData,
              success: function(data,textStatus,xhr){
                    try
                    {
                       var respuesta = JSON.parse(data);
                        //Agregar la fila nueva a la tabla
                        var  tdClass = "tdViewVenta"+respuesta.venta_id;
                        var positivo = 1;
                        if(respuesta.venta.Venta.tipodebito=='Restitucion debito fiscal'){
                            positivo = -1;
                        }
                        var rowData =
                            [
                                respuesta.venta.Venta.fecha,
                                respuesta.comprobante.Comprobante.abreviacion+"-"+respuesta.puntosdeventa.Puntosdeventa.nombre+"-"+respuesta.venta.Venta.numerocomprobante,
                                respuesta.subcliente.Subcliente.cuit,
                                respuesta.subcliente.Subcliente.nombre,
                                respuesta.venta.Venta.condicioniva,
                                respuesta.actividadcliente.Actividade.nombre,
                                respuesta.localidade.Localidade.nombre,
                            ];
                        if(!(respuesta.tieneMonotributo=="true")){
                            rowData.push(respuesta.venta.Venta.tipodebito);
                            rowData.push(respuesta.venta.Venta.alicuota+"%");
                            rowData.push(respuesta.venta.Venta.neto*positivo);
                            rowData.push(respuesta.venta.Venta.iva*positivo);
                        }
                        if(respuesta.tieneIVAPercepciones=="true"){
                            rowData.push(respuesta.venta.Venta.ivapercep*positivo);
                        }
                        if(respuesta.tieneAgenteDePercepcionIIBB=="true"){
                            rowData.push(respuesta.venta.Venta.iibbpercep*positivo);
                        }
                        if(respuesta.tieneAgenteDePercepcionActividadesVarias=="true"){
                            rowData.push(respuesta.venta.Venta.actvspercep*positivo);
                        }
                        if(respuesta.tieneImpuestoInterno=="true"){
                            rowData.push(respuesta.venta.Venta.impinternos*positivo);
                        }
                        rowData.push(respuesta.venta.Venta.nogravados*positivo);
                        rowData.push(respuesta.venta.Venta.excentos*positivo);
                        rowData.push(respuesta.venta.Venta.exentosactividadeseconomicas*positivo);
                        rowData.push(respuesta.venta.Venta.exentosactividadesvarias*positivo);
                        rowData.push(respuesta.venta.Venta.total*positivo);
                        var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="modificarVenta('+respuesta.venta_id+')" alt="">';
                        tdactions = tdactions + '<img src="'+serverLayoutURL+'/img/eliminar.png" width="20" height="20" onclick="eliminarVenta('+respuesta.venta_id+')" alt="">';
                        rowData.push(tdactions);

                        $('#tablaVentas').dataTable().fnDeleteRow($("#rowventa"+venid));

                        var rowIndex = $('#tablaVentas').dataTable().fnAddData(rowData);
                        var row = $('#tablaVentas').dataTable().fnGetNodes(rowIndex);
                        $(row).attr( 'id', "rowventa"+respuesta.venta_id );
                        calcularFooterTotales(tblTablaVentas);
                        $('#myModal').modal('hide');
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
/* fin Update Ventas*/
/*Update Compras*/
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
            //var oldRow = $("#rowcompra"+comid).html();
            // var rowid="#rowcompra"+comid;
            // $(rowid).find('td').each(function(){
            //     $(this).hide();
            // })
            // $(rowid).html(response);
            $('#myModal').on('show.bs.modal', function() {
                $('#myModal').find('.modal-title').html('Editar Compra');
                $('#myModal').find('.modal-body').html(response);
                // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
            });

            $('#myModal').modal('show');
            // $('#myModal input[0]').focus();
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
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='monotributista']").show();
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='responsableinscripto']").show();
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='consf/exento/noalcanza']").hide();

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
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='monotributista']").show();
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='responsableinscripto']").show();
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='consf/exento/noalcanza']").hide();

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
                  $("#CompraFormEdit"+comid+" #CompraCondicioniva option[value='responsableinscripto']").show();
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
                       // callAlertPopint(respuesta.respuesta);
                      //Incrementa campo de Factura en 1
                      $( "#CompraNumerocomprobante" ).val($( "#CompraNumerocomprobante" ).val()*1 + 1);

                      var positivo = 1;

                      if(respuesta.compra.Compra.tipocredito=='Restitucion credito fiscal'){
                          positivo = -1;
                      }else{
                          positivo = 1;
                      }

                      //Agregar Row Nueva a la tabla
                      var rowData =
                          [
                              respuesta.compra.Compra.fecha,
                              respuesta.comprobante.Comprobante.abreviacion+"-"+respuesta.compra.Compra.puntosdeventa+"-"+respuesta.compra.Compra.numerocomprobante,
                              respuesta.provedore.Provedore.nombre,
                              respuesta.compra.Compra.condicioniva,
                              respuesta.actividadcliente.Actividade.nombre,
                              respuesta.localidade.Localidade.nombre,
                              respuesta.compra.Compra.tipocredito,
                              respuesta.tipogasto.Tipogasto.nombre,
                              respuesta.compra.Compra.tipoiva,
                              respuesta.compra.Compra.imputacion,
                              respuesta.compra.Compra.alicuota+"%",
                              respuesta.compra.Compra.neto*positivo,
                              respuesta.compra.Compra.iva*positivo,
                              respuesta.compra.Compra.ivapercep*positivo,
                              respuesta.compra.Compra.iibbpercep*positivo,
                              respuesta.compra.Compra.actvspercep*positivo,
                              respuesta.compra.Compra.impinternos*positivo,
                              respuesta.compra.Compra.impcombustible*positivo,
                              respuesta.compra.Compra.nogravados*positivo,
                              respuesta.compra.Compra.exentos*positivo,
                              respuesta.compra.Compra.total*positivo,
                              respuesta.compra.Compra.kw*positivo,
                          ];
                      var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="modificarCompra('+respuesta.compra_id+')" alt="">';
                      tdactions = tdactions + '<img src="'+serverLayoutURL+'/img/eliminar.png" width="20" height="20" onclick="eliminarCompra('+respuesta.compra_id+')" alt="">';
                      rowData.push(tdactions);

                    $('#tablaCompras').dataTable().fnDeleteRow($("#rowcompra"+comid));

                      var rowIndex = $('#tablaCompras').dataTable().fnAddData(rowData);
                      var row = $('#tablaCompras').dataTable().fnGetNodes(rowIndex);
                      $(row).attr( 'id', "rowcompra"+respuesta.compra_id );
                      calcularFooterTotales(tblTablaCompras);
                      $('#myModal').modal('hide');
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
                            $('#tablaCompras').dataTable().fnDeleteRow($("#rowcompra"+comid));
                            calcularFooterTotales($('#tablaCompras').DataTable());
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
/*fin Update Compras*/
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
                                        conceptoCargado.numeropadron
                                    ];
                                var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="modificarConceptosrestante('+conceptoCargado.id+')" alt="">';
                                tdactions = tdactions + '<img src="'+serverLayoutURL+'/img/eliminar.png" width="20" height="20" onclick="eliminarConceptosrestante('+conceptoCargado.id+')" alt="">';
                                rowData.push(tdactions);

                                $('#tblTablaConceptosrestantes').dataTable().fnDeleteRow($("#rowconceptorestante"+conid));

                                var rowIndex = $('#tblTablaConceptosrestantes').dataTable().fnAddData(rowData);
                                var row = $('#tblTablaConceptosrestantes').dataTable().fnGetNodes(rowIndex);
                                $(row).attr( 'id', "rowconceptorestante"+respuesta.conid );
                                calcularFooterTotales(tblTablaVentas);
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
/*Update Sueldos*/
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
                            $('#tablaSueldos').DataTable().row('rowsueldo'+sueid)
                                .remove()
                                .draw();
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
                var pdtsueldo = $('#pdtsueldo');
                pdtsueldo.floatThead();
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
    function cargarReciboSueldo(empid,periodo){
        var data ="";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/empleados/papeldetrabajorecibosueldo/"+empid+"/"+periodo, // URL to request
            data: data,  // post data
            success: function(response) {
                $("#sueldoContent").html(response);
                var recibo = $("#reciboOriginal").html();
                $("#reciboDuplicado").html(recibo);
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
                return false;
            }
        });
        return false;
    }
/*fin Update Sueldos*/
/*Update Movimientos bancarios*/
function modificarMovimientosbancario(movbid){
    var cliid = $("#cliid").val();
    var data ="";
    $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/movimientosbancarios/edit/"+movbid+"/"+cliid,

        // URL to request
        data: data,  // post data
        success: function(response) {
            //var oldRow = $("#rowconceptorestante"+conid).html();
            var rowid="rowmovimientosbancarios"+movbid;
            //var oldrow = $("#"+rowid).html();
            //$("#"+rowid).html(oldrow+response);
            //$(".tdViewConceptosrestanteO"+conid).hide();
            $('#myModal').on('show.bs.modal', function() {
                $('#myModal').find('.modal-title').html('Editar Movimiento Bancario');
                $('#myModal').find('.modal-body').html(response);
                // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
            });

            $('#myModal').modal('show');
            // $('#myModal input[0]').focus();
            reloadInputDates();
            $('.chosen-select').chosen({search_contains:true});
            $('#MovimientosBancarioFormEdit'+movbid).submit(function(){
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
                        var movimientobancario = respuesta.movimientosbancario.Movimientosbancario
                        if(movimientobancario!=null){
                            //Agregar la fila nueva a la tabla
                            var  tdClass = "tdViewMovimientosbancario"+movbid;
                            var rowData =
                                [
                                    respuesta.movimientosbancario.Cbu.cbu,
                                    movimientobancario.fecha,
                                    movimientobancario.concepto,
                                    movimientobancario.debito,
                                    movimientobancario.credito,
                                    movimientobancario.saldo,
                                    respuesta.movimientosbancario.Cuentascliente.Cuenta.nombre,
                                    movimientobancario.codigoafip
                                ];
                            var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="modificarMovimientosbancario('+movbid+')" alt="">';
                            tdactions = tdactions + '<img src="'+serverLayoutURL+'/img/eliminar.png" width="20" height="20" onclick="eliminarMovimientosbancario('+movbid+')" alt="">';
                            rowData.push(tdactions);

                            $('#tblTablaMovimientosBancarios').dataTable().fnDeleteRow($("#rowmovimientosbancarios"+movbid));

                            var rowIndex = $('#tblTablaMovimientosBancarios').dataTable().fnAddData(rowData);
                            var row = $('#tblTablaMovimientosBancarios').dataTable().fnGetNodes(rowIndex);
                            $(row).attr( 'id', "rowmovimientosbancarios"+respuesta.conid );
                            //calcularFooterTotales(tblTablaVentas);
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
function eliminarMovimientosbancario(movbid){
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
/*fin Update Movimientos bancarios*/
function imprimirElemento(elemento){
    PopupPrint($(elem).html());
}
var tablaSueldoCalx;
function ocultarFunciones(){

    $(".funcionAAplicar").each(function() {
        var posicion = $(this).attr('posicion');
        var seccion = $(this).attr('seccion');
        var headsection = $(this).attr('headseccion');

        if($('#Valorrecibo'+posicion+'Valor').val()==0){
            //tengo que ocultar el row solo si no es cabeza de seccion por que sino me oculta toda la seccion
            if(headsection=="0"){

                //si esta visible tengo que mermar en 1 el rowspan
                var rowspan = 0;
                var rowVisible =  $(this).closest('tr').is(':visible');
                if(rowVisible==true) {
                    rowspan = $('#seccion'+seccion).attr('rowspan');
                    rowspan = rowspan - 1;
                    $('#seccion'+seccion).attr('rowspan',rowspan);
                }
                $(this).closest('tr').hide();
            }

        }else{

            //si esta oculto tengo que aumentar en 1 el rowspan
            var rowspan = 0;
            var rowVisible = $(this).is(':visible');
            if(rowVisible==false ) {
                rowspan = $('#seccion' + seccion).attr('rowspan')*1;
                rowspan = rowspan + 1;
                $('#seccion'+seccion).attr('rowspan',rowspan);
            }
            //tengo que mostrar el row
            $(this).closest('tr').show();
        }
    });
}
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
    ocultarFunciones();

    $('#ValorreciboPapeldetrabajosueldosForm input').change(function(){
        $('#ValorreciboPapeldetrabajosueldosForm').calx({ });
        ocultarFunciones();
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
function PrintElem(elem){
// PopupElement($(elem).html());
window.print();

}
function PopupElement(data){
var mywindow = window.open('', 'my div', 'height=400,width=1200');
mywindow.document.write('<html><head><title>my div</title>');
/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
mywindow.document.write('</head><body >');
mywindow.document.write(data.html());
mywindow.document.write('</body></html>');

mywindow.document.close(); // necessary for IE >= 10
mywindow.focus(); // necessary for IE >= 10

mywindow.print();
mywindow.close();

return true;
}
