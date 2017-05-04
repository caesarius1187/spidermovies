var tblTablaVentas;
var tblTablaCompras;
jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
    return this.flatten().reduce( function ( a, b ) {
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

    var nombrecliente = $('#clientenombre').val();
    var periodo = $('#periododefault').val();

    var tblTablaMovimientosBancarios = $('#tblTablaMovimientosBancarios').DataTable( {
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
                            text: 'Conmutar CBU',
                            action: function ( e, dt, node, config ) {
                                dt.column( 0 ).visible( ! dt.column( 0 ).visible() );
                            }
                        },
                        {
                            text: 'Conmutar Fecha',
                            action: function ( e, dt, node, config ) {
                                dt.column( 1 ).visible( ! dt.column( 1 ).visible() );
                            }
                        },
                        {
                            text: 'Conmutar Orden',
                            action: function ( e, dt, node, config ) {
                                dt.column( 2 ).visible( ! dt.column( 2 ).visible() );
                            }
                        },
                        {
                            text: 'Conmutar Concepto',
                            action: function ( e, dt, node, config ) {
                                dt.column( 3 ).visible( ! dt.column( 3 ).visible() );
                            }
                        },
                        {
                            text: 'Conmutar Debito',
                            action: function ( e, dt, node, config ) {
                                dt.column( 4 ).visible( ! dt.column( 4 ).visible() );
                            }
                        },
                        {
                            text: 'Conmutar Credito',
                            action: function ( e, dt, node, config ) {
                                dt.column( 5 ).visible( ! dt.column( 5 ).visible() );
                            }
                        },
                        {
                            text: 'Conmutar Saldo',
                            action: function ( e, dt, node, config ) {
                                dt.column( 6 ).visible( ! dt.column( 6 ).visible() );
                            }
                        },
                        {
                            text: 'Conmutar Cuenta',
                            action: function ( e, dt, node, config ) {
                                dt.column( 7 ).visible( ! dt.column( 7 ).visible() );
                            }
                        },
                        {
                            text: 'Conmutar Codigo AFIP',
                            action: function ( e, dt, node, config ) {
                                dt.column( 8 ).visible( ! dt.column( 8 ).visible() );
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
                    title: 'Movimientos Bancarios-'+nombrecliente+'-'+periodo,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    title: 'Movimientos Bancarios-'+nombrecliente+'-'+periodo,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: 'Movimientos Bancarios-'+nombrecliente+'-'+periodo,
                    exportOptions: {
                        columns: ':visible'
                    },
                    orientation: 'landscape',
                    download: 'open',
                    message: 'Movimientos Bancarios-'+nombrecliente+'-'+periodo,
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
                    message: 'Movimientos Bancarios-'+nombrecliente+'-'+periodo,
                },
            ],
            drawCallback: function () {
                var api = this.api();
                $( api.table().footer() ).html(
                    $('<tr>').append(
                        $('<td>').html(
                            "Debitos $" + api.column( 4, {page:'current'} ).data().sum() +
                            " Creditos $" + api.column( 5, {page:'current'} ).data().sum()
                        )
                    )
                );
            }
        }
    );
    //calcularFooterTotales(tblTablaMovimientosBancarios);
    $('.chosen-select').chosen({search_contains:true});

    addFormSubmitCatchs();
    reloadInputDates();
    function pad (str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }

    function addFormSubmitCatchs(){
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
                                conceptoCargado.ordencarga,
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
                                        movimientobancario.ordencarga,
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
        var r = confirm("Esta seguro que desea eliminar este movimiento bancario?. Es una accion que no podra deshacer.");
        if (r == true) {
            $.ajax({
                type: "post",  // Request method: post, get
                url: serverLayoutURL+"/movimientosbancarios/delete/"+movbid, // URL to request
                data: "",  // post data
                success: function(response) {
                    var mirespuesta = jQuery.parseJSON(response);
                    if(mirespuesta.error==0){
                        callAlertPopint(mirespuesta.respuesta);
                        $('#tblTablaMovimientosBancarios').dataTable().fnDeleteRow($("#rowmovimientosbancarios"+movbid));
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
    function realizarEventoCliente(periodo,clienteid,estadotarea){
    var datas =  "0/tarea3/"+periodo+"/"+clienteid;
    var data ="";
    $.ajax({
    type: "post",  // Request method: post, get
    url: serverLayoutURL+"/eventosclientes/realizareventocliente/0/bancoscargados/"+periodo+"/"+clienteid+"/"+estadotarea, // URL to request
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
