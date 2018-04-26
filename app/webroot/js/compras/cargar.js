var tblTablaCompras;
var actividadescategorias;
var allcomprobantes;

$(document).ready(function() {
    allcomprobantes =  JSON.parse($('#jsonallcomprobantes').val());
    actividadescategorias =  JSON.parse($('#jsonactividadescategorias').val());
    var nombrecliente = $('#clientenombre').val();
    var periodo = $('#periododefault').val();

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
                        text: 'Conmutar CUIT',
                        action: function ( e, dt, node, config ) {
                            dt.column( 2 ).visible( ! dt.column( 2 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Provedor',
                        action: function ( e, dt, node, config ) {
                            dt.column( 3 ).visible( ! dt.column( 3 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Cond. IVA',
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
                        text: 'Conmutar Tipo Credito',
                        action: function ( e, dt, node, config ) {
                            dt.column( 7 ).visible( ! dt.column( 7 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Tipo Gasto',
                        action: function ( e, dt, node, config ) {
                            dt.column( 8 ).visible( ! dt.column( 8 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Tipo IVA',
                        action: function ( e, dt, node, config ) {
                            dt.column( 9 ).visible( ! dt.column( 9 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Imputacion',
                        action: function ( e, dt, node, config ) {
                            dt.column( 10 ).visible( ! dt.column( 10 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Alicuota',
                        action: function ( e, dt, node, config ) {
                            dt.column( 11 ).visible( ! dt.column( 11 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Neto',
                        action: function ( e, dt, node, config ) {
                            dt.column( 12 ).visible( ! dt.column( 12 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar IVA',
                        action: function ( e, dt, node, config ) {
                            dt.column( 13 ).visible( ! dt.column( 13 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar IVA Percep',
                        action: function ( e, dt, node, config ) {
                            dt.column( 14 ).visible( ! dt.column( 14 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar IIBB Percep',
                        action: function ( e, dt, node, config ) {
                            dt.column( 15 ).visible( ! dt.column( 15 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Act.Vs.Percep',
                        action: function ( e, dt, node, config ) {
                            dt.column( 16 ).visible( ! dt.column( 16 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Gana.Percep',
                        action: function ( e, dt, node, config ) {
                            dt.column( 17 ).visible( ! dt.column( 16 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Imp. Interno',
                        action: function ( e, dt, node, config ) {
                            dt.column( 18 ).visible( ! dt.column( 17 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Imp. Comb.',
                        action: function ( e, dt, node, config ) {
                            dt.column( 19 ).visible( ! dt.column( 18 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar No Gravados',
                        action: function ( e, dt, node, config ) {
                            dt.column( 20 ).visible( ! dt.column( 19 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Exentos',
                        action: function ( e, dt, node, config ) {
                            dt.column( 21 ).visible( ! dt.column( 20 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Total',
                        action: function ( e, dt, node, config ) {
                            dt.column( 22 ).visible( ! dt.column( 21 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar KW',
                        action: function ( e, dt, node, config ) {
                            dt.column( 23 ).visible( ! dt.column( 22 ).visible() );
                        }
                    },
                    {
                        text: 'Conmutar Acciones',
                        action: function ( e, dt, node, config ) {
                            dt.column( -2 ).visible( ! dt.column( -2 ).visible() );
                        }
                    },
                    {
                        text: 'Cargado',
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
                    columns: '.printable'
                },
                orientation: 'landscape',
                footer: true,
                autoPrint: true,
                message: nombrecliente+"</br>"+
                'Domicilio: '+$("#domiciliocliente").val()+" - "+
                ' Inicio actividad: '+$("#fchcumpleanosconstitucion").val()+
                ' Periodo: '+periodo+"</br>"+
                '---------------------------------------Libro N° '+/*$("#numerolibro").val()+*/"</br>"+
                'Resp: '+$("#condicioniva").val()+' Registro IVA COMPRAS',
                customize: function ( win ) {
                },
            },
        ],
        createdRow: function( row, data, dataIndex){
            var totalCompra = 0;
            var neto = StrToNumber(data[12]);
            var iva = StrToNumber(data[13]);
            var ivapercep = StrToNumber(data[14]);
            var iibbpercep = StrToNumber(data[15]);
            var actvspercep = StrToNumber(data[16]);
            var ganapercep = StrToNumber(data[17]);
            var impinterno = StrToNumber(data[18]);
            var impcomb = StrToNumber(data[19]);
            var nogravados = StrToNumber(data[20]);
            var exentos = StrToNumber(data[21]);
            totalCompra = neto + iva + ivapercep + iibbpercep + actvspercep + ganapercep + impinterno + impcomb + nogravados + exentos;
            totalCompra = totalCompra.toFixed(2);
            if( StrToNumber(data[22]) !=  totalCompra){
                //si el total no es igual a la sumatoria de los componentes de la compra
                $(row).addClass('compraWithError');
                $(row).children().each(function(){
                     $(this).addClass('compraWithError');
                });
            }
            if( StrToNumber(data[22]) !=  totalCompra){
                //si iva != neto * alicuota
                $(row).addClass('compraWithError');
                $(row).children().each(function(){
                     $(this).addClass('compraWithError');
                });
            }
          }
    } );
    $('#tablaCompras tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            //tblTablaCompras.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
  
    calcularFooterTotales(tblTablaCompras);
    $('.chosen-select').chosen({search_contains:true});

    comprasOnChange();
    reloadInputDates();
    addFormSubmitCatchs();
    /*Cargar Asientos de Venta automaticos*/
    function pad (str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }
    function comprasOnChange(){
        /*Compras On Change*/
        $("#CompraPuntosdeventa").on('change paste', function() {
            $("#CompraPuntosdeventa").val(pad ($("#CompraPuntosdeventa").val(), 4));
        });
        $("#CompraNumerocomprobante").on('change paste', function() {
            $("#CompraNumerocomprobante").val(pad ($("#CompraNumerocomprobante").val(), 8));
        });
        $("#CompraIvapercep").on('change paste', function() {
            calcularivaytotalcompra("saveComprasForm");
        });
        $("#CompraImpinternos").on('change paste', function() {
            calcularivaytotalcompra("saveComprasForm");
        });
        $("#CompraActvspercep").on('change paste', function() {
            calcularivaytotalcompra("saveComprasForm");
        });
        $("#CompraGanapercep").on('change paste', function() {
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
                            //$("#CompraTipocredito option[value='Credito Fiscal']").show();
                            //$("#CompraTipocredito option[value='Restitucion credito fiscal']").hide();
                            $("#CompraTipocredito").val($("#CompraTipocredito option:eq(0)").val());
                            //$('.chosen-select').trigger("chosen:updated");
                            break;
                        case 'Restitucion credito fiscal':
                            //$("#CompraTipocredito option[value='Credito Fiscal']").hide();
                            //$("#CompraTipocredito option[value='Restitucion credito fiscal']").show();
                            $("#CompraTipocredito").val($("#CompraTipocredito option:eq(1)").val());
                            //$('.chosen-select').trigger("chosen:updated");
                            break;
                    }
                        
                    /*if(tipodecomprobanteCompraseleccionado==comprobante.Comprobante.tipo){
                        $('.chosen-select').trigger("chosen:updated");
                        return;
                    }*/

                    if(comprobante.Comprobante.tipo=="A"){
                        //Preparar para recibir Neto y calcular IVA y Total
                        $("#CompraNeto").prop("readonly",false);
                        $("#CompraIva").prop("readonly",true);
                        $("#CompraIvapercep").prop('readonly', false);
                        $("#CompraTotal").prop("readonly",true);
                        //Permitir editar los campos relacionados a IVA
                        tipodecomprobanteCompraseleccionado = 'A';
                        /*$("#CompraCondicioniva option[value='monotributista']").show();
                        $("#CompraCondicioniva option[value='responsableinscripto']").show();
                        $("#CompraCondicioniva option[value='consf/exento/noalcanza']").hide();*/
                        //$("#CompraCondicioniva").val($("#CompraCondicioniva option:eq(1)").val());

                        $("#CompraAlicuota option[value='0']").show();
                        $("#CompraAlicuota option[value='2.5']").show();
                        $("#CompraAlicuota option[value='5']").show();
                        $("#CompraAlicuota option[value='10.5']").show();
                        $("#CompraAlicuota option[value='21']").show();
                        $("#CompraAlicuota option[value='27']").show();
                        //$("#CompraAlicuota").val($("#CompraAlicuota option:eq(1)").val());
                    }else  if(comprobante.Comprobante.tipo=="B"){
                        $("#CompraNeto").prop("readonly",false);
                        $("#CompraIva").prop("readonly",true);
                        $("#CompraIvapercep").prop('readonly', true);
                        $("#CompraTotal").prop("readonly",false);

                        $("#CompraIva").val(0);
                        $("#CompraIvapercep").val(0);
                        tipodecomprobanteCompraseleccionado = 'B';
                        $("#CompraAlicuota option:eq(0)").val();
                    }else  if(comprobante.Comprobante.tipo=="C"){
                        $("#CompraNeto").prop("readonly",false);
                        $("#CompraIva").prop("readonly",true);
                        $("#CompraIvapercep").prop('readonly', true);
                        $("#CompraTotal").prop("readonly",false);

                        $("#CompraIva").val(0);
                        $("#CompraIvapercep").val(0);
                        tipodecomprobanteCompraseleccionado = 'C';
                        $("#CompraAlicuota option:eq(0)").val();
                    }
                    $('.chosen-select').trigger("chosen:updated");
                }
            }, this);
        });
        $("#CompraComprobanteId" ).trigger( "change" );
        $('.chosen-select').trigger("chosen:updated");
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
                        $( "#CompraNumerocomprobante" ).val(pad ($( "#CompraNumerocomprobante" ).val()*1 + 1, 8));
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
                                respuesta.provedore.Provedore.cuit,
                                respuesta.provedore.Provedore.nombre,
                                respuesta.compra.Compra.condicioniva,
                                respuesta.actividadcliente.Actividade.nombre,
                                respuesta.localidade.Partido.nombre+" "+respuesta.localidade.Localidade.nombre,
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
                                respuesta.compra.Compra.ganapercep*positivo,
                                respuesta.compra.Compra.impinternos*positivo,
                                respuesta.compra.Compra.impcombustible*positivo,
                                respuesta.compra.Compra.nogravados*positivo,
                                respuesta.compra.Compra.exentos*positivo,
                                respuesta.compra.Compra.total*positivo,
                                respuesta.compra.Compra.kw*positivo
                            ];
                        var tdactions= '<img src="'+serverLayoutURL+'/img/edit_view.png" width="20" height="20" onclick="modificarCompra('+respuesta.compra_id+')" alt="">';
                        tdactions = tdactions + '<img src="'+serverLayoutURL+'/img/eliminar.png" width="20" height="20" onclick="eliminarCompra('+respuesta.compra_id+')" alt="">';
                        if(respuesta.compra.Compra.imputacion=='Bs Uso') {
                            tdactions = tdactions + '<img src="' + serverLayoutURL + '/img/biendeuso.png" width="20" height="20" onclick="abrirBiendeuso(' + respuesta.compra.Compra.cliente_id + ',' + respuesta.compra_id + ')" alt="">';
                        }
                        rowData.push(tdactions);
                        rowData.push(respuesta.compra.Compra.created);

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

    $("#CompraActividadclienteId").on('change', function() {
        $("#CompraPuntosdeventa").val(pad ($("#CompraPuntosdeventa").val(), 4));
    });
    $.fn.filterGroups = function( options ) {
        var settings = $.extend( {}, options);

        return this.each(function(){

            var $select = $(this);
            // Clone the optgroups to data, then remove them from dom
            $select.data('fg-original-groups', $select.find('optgroup').clone()).children('optgroup').remove();

            $(settings.groupSelector).change(function(){
                var $this = $(this);
                var $optgroup_label = $(this).find('option:selected').text();
                var $optgroup =  $select.data('fg-original-groups').filter('optgroup[label="' + $optgroup_label + '"]').clone();
                $select.children('optgroup').remove();
                $select.append($optgroup);
                $('.chosen-select').trigger("chosen:updated");
            }).change();
        });
    };
    if($('#jsonactividadescategorias option').size()>0 ){
        $('#CompraTipogastoId').filterGroups({groupSelector: '#jsonactividadescategorias', });
    }
    $("#CompraActividadclienteId").on('change', function() {
        $("#jsonactividadescategorias").val($("#CompraActividadclienteId").val());
        $('#jsonactividadescategorias').trigger( "change" );
    });
    $('#jsonactividadescategorias').trigger( "change" );
});
    function StrToNumber (strNumber){
        if (typeof strNumber === 'string') {
            if(strNumber.charAt(0)=="<"){
                //es un p y no se debe sumar
                strNumber = 0
            }else{
                strNumber = strNumber.replace('.', "");
                strNumber = strNumber.replace('.', "");
                strNumber = strNumber.replace(',', ".");
            }

        }
        return Number(strNumber).toFixed(2)*1;
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
            // var mismovimientos =mitabla.rows( '.movimientobancario' ,{order:'applied'}).indexes();
            mitabla.columns( '.sum' ).every( function () {
                try {
                    var micolumndata = this.data();
                    var columnLength = this.data().length;
                    var index = 1;
                    if(columnLength > 0){
                        var sum = this
                            .data()
                            .reduce( function (a,b) {
                                if (a != null && b != null) {
                                    if (typeof a === 'string') {
                                        if(a.charAt(0)=="<"){
                                            //es un p y no se debe sumar
                                            a = 0
                                        }else{
                                            a = a.replace('.', "");
                                            a = a.replace('.', "");
                                            a = a.replace(',', ".");
                                        }

                                    }
                                    a = Number(a);

                                    if (typeof b === 'string') {
                                        if(b.charAt(0)=="<"){
                                            //es un p y no se debe sumar
                                            b = 0
                                        }else {
                                            b = b.replace('.', "");
                                            b = b.replace('.', "");
                                            b = b.replace(',', ".");
                                        }
                                    }
                                    b = Number(b);
                                    var resultado = a + b;
                                    index++;
                                    return resultado;
                                } else {
                                    index++;
                                    return 0;
                                }
                            } );
                        if (typeof sum === 'string') {
                            sum = sum.replace('.', "");
                            sum = sum.replace('.', "");
                            sum = sum.replace(',', ".");
                            $( this.footer() ).html((sum*1).toFixed(2));
                            // $(mitabla.table().footer()).find(" tr:last td").html((sum*1).toFixed(2));
                        }else{
                            $( this.footer() ).html(sum.toFixed(2));
                            // $(mitabla.table().footer()).find(" tr:last td").html((sum).toFixed(2));
                            var position = $(this)[0] - 2;
                            var prevoiusVal = $(mitabla.table().footer()).find(" tr:eq(1) th:eq("+position+")").html()*1 + 0;
                            prevoiusVal *= 1;
                            var totalSUM = sum + prevoiusVal;
                            $(mitabla.table().footer()).find(" tr:last th:eq("+position+")").html((totalSUM).toFixed(2));
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
            var agenteDePercepcionGanancias = 0;
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
            iva=iva.toFixed(2);
            total+=neto * 1;
            total+=iva * 1;

            ivapercep = $("#"+formulario+" #CompraIvapercep").val() * 1;
            total+= ivapercep * 1;

            agenteDePercepcionIIBB = $("#"+formulario+" #CompraIibbpercep").val() * 1;
            total+= agenteDePercepcionIIBB * 1;

            agenteDePercepcionActividadesVarias = $("#"+formulario+" #CompraActvspercep").val() * 1;
            total+= agenteDePercepcionActividadesVarias * 1;
            
            agenteDePercepcionGanancias = $("#"+formulario+" #CompraGanapercep").val() * 1;
            total+= agenteDePercepcionGanancias * 1;

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
            $( "input.datepicker-day-month" ).datepicker({
                yearRange: "-100:+50",
                changeMonth: true,
                changeYear: false,
                constrainInput: false,
                dateFormat: 'dd-mm',
            });
            $( "input.datepicker-month-year" ).datepicker({
                yearRange: "-100:+50",
                changeMonth: true,
                changeYear: true,
                constrainInput: false,
                dateFormat: 'mm-yy',
            });
        })(jQuery);
        
      }
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
                  $("#CompraFormEdit"+comid+" #CompraIvapercep").prop('readonly', true);
                  $("#CompraFormEdit"+comid+" #CompraTotal").prop("readonly",false);

                  $("#CompraFormEdit"+comid+" #CompraIva").val(0);
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
                  $("#CompraFormEdit"+comid+" #CompraIvapercep").prop('readonly', true);
                  $("#CompraFormEdit"+comid+" #CompraTotal").prop("readonly",false);

                  $("#CompraFormEdit"+comid+" #CompraIva").val(0);
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
                $('.chosen-select').chosen({search_contains:true});
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
                      // $( "#CompraNumerocomprobante" ).val($( "#CompraNumerocomprobante" ).val()*1 + 1);

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
                              respuesta.provedore.Provedore.cuit,
                              respuesta.provedore.Provedore.nombre,
                              respuesta.compra.Compra.condicioniva,
                              respuesta.actividadcliente.Actividade.nombre,
                              respuesta.localidade.Partido.nombre+" "+respuesta.localidade.Localidade.nombre,
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
                              respuesta.compra.Compra.ganapercep*positivo,
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
                      rowData.push(respuesta.compra.Compra.created);

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
    function hideallinputsbdu(){
        $(".inmuebleFNE").parent().hide();
        $(".inmuebleFE").parent().hide();
        $(".inmuebleJE").parent().hide();
        $(".naves").parent().hide();
        $(".aeronaves").parent().hide();
        $(".instalacionesPF").parent().hide();
        $(".instalacionesPJ").parent().hide();
        $(".otrobiendeusoPF").parent().hide();
        $(".otrobiendeusoPJ").parent().hide();
        $(".bienesmueblesregistrables").parent().hide();
        $(".otrosbienes").parent().hide();
        $(".rodadoPJ").parent().hide();
        $(".rodadoPF").parent().hide();
        $(".automotor").parent().hide();
    }
    function abrirBiendeuso(cliid,comid){
        var data ="";
        $.ajax({
        type: "get",  // Request method: post, get
        url: serverLayoutURL+"/bienesdeusos/add/"+cliid+"/0/"+comid,

        // URL to request
        data: data,  // post data
        success: function(response) {
            $('#myModal').on('show.bs.modal', function() {
                $('#myModal').find('.modal-title').html('Editar Bien de uso de la compra');
                $('#myModal').find('.modal-body').html(response);
                // $('#myModal').find('.modal-footer').html("<button type='button' data-content='remove' class='btn btn-primary' id='editRowBtn'>Modificar</button>");
            });

            $('#myModal').modal('show');
            //perzonalizar formulario para tipo de Bien de uso
            var tipoPersona = $("#tipopersona").val();
            $("#Bienesdeuso0Tipo").on('change', function() {
                var selectedTipo = $(this).val();
                hideallinputsbdu();
                switch (selectedTipo) {
                    case 'Automotor':
                        $(".automotor").parent().show();
                        break;
                    case 'Rodado':
                        if(tipoPersona=="fisica"){
                            $(".rodadoPJ").parent().hide();
                            $(".rodadoPF").parent().show();
                            /*estos dos son para personas Fisica Empresa*/
                        }else{
                            $(".rodadoPF").parent().hide();
                            $(".rodadoPJ").parent().show();
                            /*estos dos son para personas Juridica Empresa*/
                        }
                        break;
                    case 'Inmueble':
                        if(tipoPersona=="fisica"){
                            $(".inmuebleFE").parent().show();
                            /*estos dos son para personas Fisica Empresa*/
                        }else{
                            $(".inmuebleJE").parent().show();
                            /*estos dos son para personas Juridica Empresa*/
                        }
                        break;
                    case 'Inmuebles':
                        /*estos dos son para personas Fisicas No Empresa*/
                        $(".inmuebleFNE").parent().show();
                        break;
                    case 'Aeronave':
                        $(".aeronaves").parent().show();
                        break;
                    case 'Naves, Yates y similares':
                        $(".naves").parent().show();
                        break;
                    case 'Instalaciones':
                        if(tipoPersona=="fisica"){
                            $(".instalacionesPF").parent().show();
                            /*estos dos son para personas Fisica Empresa*/
                        }else{
                            $(".instalacionesPJ").parent().show();
                            /*estos dos son para personas Juridica Empresa*/
                        }
                        break;
                    case 'Otros bienes de uso Muebles':
                    case 'Otros bienes de uso Maquinas':
                    case 'Otros bienes de uso Activos Biologicos':
                        if(tipoPersona=="fisica"){
                            $(".otrobiendeusoPF").parent().show();
                            /*estos dos son para personas Fisica Empresa*/
                        }else{
                            $(".otrobiendeusoPJ").parent().show();
                            /*estos dos son para personas Juridica Empresa*/
                        }
                        break;
                    case 'Bien mueble registrable':
                        $(".bienesmueblesregistrables").parent().show();
                        break;
                    case 'Otros bienes':
                        $(".otrosbienes").parent().show();
                        break;
                }
            });
            $("#Bienesdeuso0Tipo" ).trigger( "change" );
            $("#Bienesdeuso0Porcentajeamortizacion").on('change', function() {
                var valororiginal = $("#Bienesdeuso0Valororiginal").val();
                var amortizacionperiodo = valororiginal / $("#Bienesdeuso0Porcentajeamortizacion").val();
                if($("#Bienesdeuso0Importeamorteizaciondelperiodo is:visible")){
                    $("#Bienesdeuso0Importeamorteizaciondelperiodo").val(amortizacionperiodo);                                    
                }                
            });
            $("#Bienesdeuso0Porcentajeamortizacion" ).trigger( "change" );
            $('#BienesdeusoAddForm').submit(function(){
                    //serialize form data
                    var formData = $(this).serialize();
                    //get form action
                    var formUrl = $(this).attr('action');
                    $.ajax({
                      type: 'post',
                      url: formUrl,
                      data: formData,
                      success: function(data,textStatus,xhr){
                          var respuesta = JSON.parse(data);
                           callAlertPopint(respuesta.respuesta);
                          $('#myModal').modal('hide');
                        },
                      error: function(xhr,textStatus,error){
                        alert(textStatus);
                      }
                    });
                    return false;
                });
             $('.chosen-select').chosen({search_contains: true});
            $("#Bienesdeuso0LocalidadeId_chosen").css('width', 'auto');
            $("#Bienesdeuso0ModeloId_chosen").css('width', 'auto');
        reloadInputDates();
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

    function imprimirElemento(elemento){
        PopupPrint($(elem).html());
    }
    function realizarEventoCliente(periodo,clienteid,estadotarea){
        var datas =  "0/tarea3/"+periodo+"/"+clienteid;
        var data ="";
        $.ajax({
        type: "post",  // Request method: post, get
        url: serverLayoutURL+"/eventosclientes/realizareventocliente/0/comprascargadas/"+periodo+"/"+clienteid+"/"+estadotarea, // URL to request
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
    function PrintElem(elem){
        // PopupElement($(elem).html());
        window.print();
    }

