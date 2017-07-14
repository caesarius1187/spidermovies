var tblTablaVentas;
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

    allcomprobantes =  JSON.parse($('#jsonallcomprobantes').val());
    var tblTablaConceptosrestantes = $('#tblTablaConceptosrestantes').dataTable().api();
    var nombrecliente = $('#clientenombre').val();
    var periodo = $('#periododefault').val();

    //Ahora la tabla de ventas la vamos a tener que cargar por ajax asi que las configuraciones de esta tabla deben
    //hacerse una vez finalizada esa carga
    cargaryconfigurarTablaVentas();

    $('.chosen-select').chosen({search_contains:true});

    addFormSubmitCatchs();
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
                            message: 'LibroIVA-Ventas-'+nombrecliente+'-'+periodo+'</br>Domicilio:'+$("#domiciliocliente").val(),/*todo: completar Domicilios de Libro IVA Ventas*/

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
                                columns: '.printable'
                            },
                            orientation: 'landscape',
                            footer: true,
                            autoPrint: false,
                            message: nombrecliente+"</br>"+
                            'Domicilio: '+$("#domiciliocliente").val()+" - "+
                            ' Inicio actividad: '+$("#fchcumpleanosconstitucion").val()+
                            ' Periodo: '+periodo+"</br>"+
                            '---------------------------------------Libro N° '+/*$("#numerolibro").val()+*/"</br>"+
                            'Resp: '+$("#condicioniva").val()+' Registro IVA VENTAS',
                            customize: function ( win ) {

                            },
                            // action: function ( e, dt, node, config ) {
                            //     // dt.column( 5 ).visible( false );
                            // }
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
                        callAlertPopint('Debes ingresar un numero de comprobante');
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
                                $( "#VentaNumerocomprobante" ).val(
                                    pad ($( "#VentaNumerocomprobante" ).val()*1 + 1, 8)
                                 );
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
                    $('#myModal').modal('hide');
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
                            $('#myModal').modal('show');
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
    function pad (str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }
    function ventasOnChange(){
        /*Ventas On Change*/
        $("#VentaIvapercep").on('change paste', function() {
            calcularivaytotal("saveVentasForm");
        });
        $("#VentaNumerocomprobante").on('change paste', function() {
            $("#VentaNumerocomprobante").val(pad ($("#VentaNumerocomprobante").val(), 8));
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
    }

    function addFormSubmitCatchs(){
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
    }

});
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
            var tieneAgenteDePercepcionIIBB = $("#saveVentasForm #VentaTieneAgenteDePercepcionIIBB").val();

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
    function realizarEventoCliente(periodo,clienteid,estadotarea){
        var datas =  "0/tarea3/"+periodo+"/"+clienteid;
        var data ="";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/eventosclientes/realizareventocliente/0/ventascargadas/"+periodo+"/"+clienteid+"/"+estadotarea, // URL to request
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

