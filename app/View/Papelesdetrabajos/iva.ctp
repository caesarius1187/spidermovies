<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('jquery.table2excel',array('inline'=>false));?>

<script type="text/javascript">
	$(document).ready(function() {   
    	$("#divContenedorCompras").hide();
        $("#divContenedorLiquidacion").hide();

        $("#tabVentas_Iva").attr("class", "cliente_view_tab_active");
    	$("#tabCompras_Iva").attr("class", "cliente_view_tab");
        papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
        cargarAsiento();
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
        catchAsientoIVA();

        $( "#clickExcel" ).click(function() {

            if (!document.getElementById("pdtIVA_tr1"))
            {
                $("#tblExcelHeader").prepend(
                    $("<tr id='pdtIVA_tr1'>").append(
                        $("<td style='display:none'>")
                            .attr("colspan","25")
                            .html("Contribuyente: " + $('#clinombre').val() + " - CUIT: " + $('#nroCuitContribuyente').html())                      
                        )
                    );
            }
            if (!document.getElementById("pdtIVA_tr2"))
            {
                $("#tblExcelHeader").prepend(
                    $("<tr id='pdtIVA_tr2'>").append(
                        $("<td style='display:none'>")
                            .attr("colspan","25")
                            .html($('#tipoorganismoyNombre').html() + " - Periodo: "+ $('#periodoPDT').val())               
                        )
                    );
            }

            $("#contenedor").table2excel({
                // exclude CSS class
                exclude: ".noExl",
                name: "Conveniomultilateral",
                filename:($('#clinombre').val()).replace(/ /g,"_").replace(".","")+"_"+$('#periodoPDT').val().replace(/-/g,"_")+"_IVA"
            });
        });
        var beforePrint = function() {
            $('#header').hide();
            $('.Formhead').hide();
            $('#divContenedorVentas').show();
            $('#divContenedorCompras').show();
            $('#divContenedorLiquidacion').show();            
            $('#divPrepararPapelesDeTrabajo').hide();            
            $('#btnImprimir').hide();
            $('#clickExcel').hide();

            $('#index').css('float','left');
            $('#padding').css('padding','0px');
            $('#index').css('font-size','10px');
            $('#index').css('border-color','#FFF');
        };
        var afterPrint = function() {
            $('#index').css('font-size','14px');
            $('#header').show();
            $('.Formhead').show();
            $('#divPrepararPapelesDeTrabajo').show();            
            $('#btnImprimir').show();
            $('#clickExcel').show();
            $('#index').css('float','right');
            $('#padding').css('padding','10px 1%');
            CambiarTab('ventas')
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
        CambiarTab('ventas');
    });
   
	function CambiarTab(sTab)	{
		$("#tabVentas_Iva").attr("class", "cliente_view_tab");
		$("#tabCompras_Iva").attr("class", "cliente_view_tab");
        $("#tabLiquidacion_Iva").attr("class", "cliente_view_tab");
        $("#tabContabilidad_Iva").attr("class", "cliente_view_tab");

        if(sTab == "ventas")
		{
			$("#tabVentas_Iva").attr("class", "cliente_view_tab_active");
			$("#divContenedorVentas").show();    
			$("#divContenedorCompras").hide();    	
			$("#divContenedorLiquidacion").hide();
//            $("#divContenedorContabilidad").hide();
        }
		if(sTab == "compras")
		{
			$("#tabCompras_Iva").attr("class", "cliente_view_tab_active");
			$("#divContenedorVentas").hide();    
			$("#divContenedorCompras").show();    	
			$("#divContenedorLiquidacion").hide();
//            $("#divContenedorContabilidad").hide();
        }
		if (sTab == "liquidacion")
		{

			$("#tabLiquidacion_Iva").attr("class", "cliente_view_tab_active");
			$("#divContenedorVentas").hide();
			$("#divContenedorCompras").hide();
			$("#divContenedorLiquidacion").show();
//            $("#divContenedorContabilidad").hide();
        }
	}
    function papelesDeTrabajo(periodo,impcli){
        var data = "";
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/eventosimpuestos/getpapelestrabajo/"+periodo+"/"+impcli, // URL to request
            data: data,  // post data
            success: function(response) {
                //alert(response);
                $('#divLiquidarIVA').html(response);
                //esconder elementos que no necesitamos en este momento
                $('#tabsTareaImpuesto').hide();
                $('#divPagar').hide();
                $('#buttonPDT').hide();
                $('.btn_cancelar').hide();
                //Cargar Resultados de Saldo Tecnico A Favor Resp y Saldo Tecnico A Favor AFIP
                if($('#Eventosimpuesto0Id').val()==0){
                    //El Evento Impuesto no a sido creado previamente entonces vamos a guardar el monto que calculamos
                    var apagar = $('#apagar').val();
                    var saldotecnico = $('#saldoTecnico').val();
                    $('#Eventosimpuesto0Montovto').val(apagar);
                    $('#Eventosimpuesto0Monc').val(saldotecnico);
                }
                //Cargar Resultado Saldo Libre Disponibilidad
                var saldoLD = $('#saldoLD').val();
                var usoSLD = $('#usoSLD').val();
                var usoSLDID = $('#usoSLDID').val();
                if($('#Eventosimpuesto0Conceptosrestante0Id').length<=0){
                    if(saldoLD*1>0){
                        $('<div>').attr({
                            style:'width:260px',
                        }).append(
                            $('<input>').attr({
                                type: 'hidden',
                                id: 'Eventosimpuesto0Conceptosrestante0ClienteId',
                                value: $('#cliid').val(),
                                name: 'data[Eventosimpuesto][0][Conceptosrestante][0][cliente_id]'
                            })
                        ).append(
                            $('<input>').attr({
                                type: 'hidden',
                                id: 'Eventosimpuesto0Conceptosrestante0ImpcliId',
                                value: $('#impcliidPDT').val(),
                                name: 'data[Eventosimpuesto][0][Conceptosrestante][0][impcli_id]'
                            })
                        ).append(
                            $('<input>').attr({
                                type: 'hidden',
                                id: 'Eventosimpuesto0Conceptosrestante0ConceptostipoId',
                                value: 1,
                                name: 'data[Eventosimpuesto][0][Conceptosrestante][0][conceptostipo_id]'
                            })
                        ).append(
                                $('<input>').attr({
                                    type: 'hidden',
                                    id: 'Eventosimpuesto0Conceptosrestante0Periodo',
                                    value: $('#periodoPDT').val(),
                                    name: 'data[Eventosimpuesto][0][Conceptosrestante][0][periodo]'
                                })
                        ).append(
                                $('<label>')
                                    .html("Saldo Libre Disponibilidad")
                                    .attr({
                                        style:'display:inline',
                                    })

                        ).append(
                                $('<input>').attr({
                                    type: 'text',
                                    id: 'Eventosimpuesto0Conceptosrestante0Montoretenido',
                                    value: saldoLD,
                                    name: 'data[Eventosimpuesto][0][Conceptosrestante][0][montoretenido]'
                                })
                        ).append(
                            $('<img>').attr({
                                src: serverLayoutURL+'/img/ii.png',
                                style: 'width:15px;height:15px',
                                title: 'Este campo se cargara como un Pago a Cuenta del tipo Saldo de Libre Disponibilidad' +
                                ' en el periodo '+$('#periodoPDT').val(),
                                alt: ''
                            })
                        ).append(
                                $('<input>').attr({
                                    type: 'hidden',
                                    id: 'Eventosimpuesto0Conceptosrestante0Monto',
                                    value: saldoLD,
                                    name: 'data[Eventosimpuesto][0][Conceptosrestante][0][monto]'
                                })
                        ).append(
                            $('<input>').attr({
                                type: 'hidden',
                                id: 'Eventosimpuesto0Conceptosrestante0Fecha',
                                value: $.datepicker.formatDate('yy/mm/dd', new Date()),
                                name: 'data[Eventosimpuesto][0][Conceptosrestante][0][fecha]'
                            })
                        )
                        .appendTo('#EventosimpuestoRealizartarea5Form')
                        .append(
                            $('</br>')
                        );
                    }
                }else{
                    $('#Eventosimpuesto0Conceptosrestante0Montoretenido').val(saldoLD);
                }
                if($('#Eventosimpuesto0Usosaldo0Id').length<=0) {
                    if (usoSLD * 1 > 0) {
                        $('<div>').attr({
                            style:'width:370px',
                        }).append(
                            $('<input>').attr({
                                type: 'hidden',
                                id: 'Eventosimpuesto0Usosaldo0EventosimpuestoId',
                                value: $('#Eventosimpuesto0Id').val(),
                                name: 'data[Eventosimpuesto][0][Usosaldo][0][eventosimpuesto_id]'
                            })
                        ).append(
                            $('<input>').attr({
                                type: 'hidden',
                                id: 'Eventosimpuesto0Usosaldo0ConceptosrestanteId',
                                value: usoSLDID,
                                name: 'data[Eventosimpuesto][0][Usosaldo][0][conceptosrestante_id]'
                            })
                        ).append(
                            $('<label>')
                                .html("Uso Saldo Libre Disponibilidad periodo anterior")
                                .attr({
                                    style:'display:inline',
                                })
                        ).append(
                            $('<input>').attr({
                                type: 'text',
                                id: 'Eventosimpuesto0Usosaldo0Importe',
                                value: usoSLD,
                                name: 'data[Eventosimpuesto][0][Usosaldo][0][importe]'
                            })
                        ).append(
                            $('<img>').attr({
                                src: serverLayoutURL + '/img/ii.png',
                                style: 'width:15px;height:15px',
                                title: 'Se registrará el uso de este Saldo de libre disponibilidad(SLD) de periodos anteriores, ' +
                                'ya sea que se lo este acumulando o usando para pagar el impuesto, el saldo para periodos ' +
                                'anteriores quedará en 0. Si se recaulcula este informe se intentara tomar de nuevo este SLD, por lo ' +
                                'que se debe eliminar, en la tarea cargar del informe de avance, el Uso del saldo de libre disponibilidad',
                                alt: ''
                            })
                        ).append(
                            $('<input>').attr({
                                type: 'hidden',
                                id: 'Eventosimpuesto0Usosaldo0Fecha',
                                value: $.datepicker.formatDate('yy/mm/dd', new Date()),
                                name: 'data[Eventosimpuesto][0][Usosaldo][0][fecha]'
                            })
                        ).appendTo('#EventosimpuestoRealizartarea5Form');
                    }
                }
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
                            }else{
                                $('#AsientoAddForm').submit();
                                $('#divLiquidarIVA').hide();
                            }
                        },
                        error: function(xhr,textStatus,error){
                            callAlertPopint(textStatus);
                            return false;
                        }
                    });
                    return false;
                });
                //aca vamos a mover el div de asientos al de eventos impuesto
                $('#divContenedorContabilidad').detach().appendTo('#EventosimpuestoRealizartarea5Form');
                $('.btn_aceptar').detach().appendTo('#EventosimpuestoRealizartarea5Form');

            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);

            }
        });
        return false;
    }
    function cargarAsiento(){
        //aca vamos a buscar los valores guardados en los inputs que estan hidden yt que tienen los valores que hay que guardar en los movimientos
        //los movimientos que tenemos que guardar son los siguentes
//        210401401	IVA - Débito Fiscal General
        if($('#cuenta1467').length > 0){
            var orden = $('#cuenta1467').attr('orden');
            var debitoFiscalGeneral = $("#totaldebitofiscal").val();
            $('#Asiento0Movimiento'+orden+'Debe').val(debitoFiscalGeneral);
        }
//        110403402	IVA - Saldo a Favor Técnico
        if($('#cuenta287').length > 0){
            var orden = $('#cuenta287').attr('orden');
            var saldotecnico = $("#saldoTecnico").val();
            var saldotecnicoperiodoant = $("#spnSaldoAFavorPeriodoAnt").html().replace(".", "").replace(",", ".");
            var difST = parseFloat(saldotecnico)-parseFloat(saldotecnicoperiodoant);
            if(difST>=0){
                $('#Asiento0Movimiento'+orden+'Debe').val(difST);
            }else{
                $('#Asiento0Movimiento'+orden+'Haber').val(difST*-1);
            }
        }
//        110403406	IVA - Saldo a Favor Libre Disp
        if($('#cuenta290').length > 0){
            var orden = $('#cuenta290').attr('orden');
            var saldoLD = $("#saldoLD").val();
//            $('#Asiento0Movimiento'+orden+'Debe').val(saldoLD);
            var saldoLDperiodoant = $("#spnSaldoAFavorLibreDispPeriodoAnteriorNetousos").html().replace(".", "").replace(",", ".");
            var difSLD = parseFloat(saldoLD)-parseFloat(saldoLDperiodoant);
            if(difSLD>=0){
                $('#Asiento0Movimiento'+orden+'Debe').val(difSLD);
            }else{
                $('#Asiento0Movimiento'+orden+'Haber').val(difSLD*-1);
            }
        }
//        110403401	IVA - Credito Fiscal General
        if($('#cuenta286').length > 0){
            var orden = $('#cuenta286').attr('orden');
            var totalcreditofiscal = $("#totalcreditofiscal").val();
            $('#Asiento0Movimiento'+orden+'Haber').val(totalcreditofiscal);
        }
//        504990015	IVA - No Computable
        if($('#cuenta2344').length > 0){
            var orden = $('#cuenta2344').attr('orden');
            var totalnocomputable = $("#totalnocomputable").val();
            $('#Asiento0Movimiento'+orden+'Debe').val(totalnocomputable);
        }
//        110403403	IVA - Percepciones
        if($('#cuenta288').length > 0){
            var orden = $('#cuenta288').attr('orden');
            var totalpercepciones = $("#totalpercepciones").val();
            $('#Asiento0Movimiento'+orden+'Haber').val(totalpercepciones);
        }
//        110403404	IVA - Reteniones
        if($('#cuenta289').length > 0){
            var orden = $('#cuenta289').attr('orden');
            var totalretenciones = $("#totalretenciones").val();
            $('#Asiento0Movimiento'+orden+'Haber').val(totalretenciones);
        }
//        110403404	IVA - Pagos a cuenta
        if($('#cuenta292').length > 0){
            var orden = $('#cuenta292').attr('orden');
            var totalpagosacuenta = $("#totalpagosacuenta").val();
            $('#Asiento0Movimiento'+orden+'Haber').val(totalpagosacuenta);
        }
//        110403405	IVA - Decreto 814
// todo Agregar Cuenta Decreto 814
// /**Este concepto falta no se por que no esta en el plan de cuentas**/
//        if($('#cuenta289').length > 0){
//            var orden = $('#cuenta289').attr('orden');
//            var totalretenciones = $("#totalretenciones").val();
//            $('#Asiento0Movimiento'+orden+'Haber').val(totalretenciones);
//        }
//        210401402	IVA - a Pagarapagar
        if($('#cuenta1468').length > 0){
            var orden = $('#cuenta1468').attr('orden');
            var apagar = $("#apagar").val();
            $('#Asiento0Movimiento'+orden+'Haber').val(apagar);
        }
        /*************/
        //Carga del 2do asiento que es el de Devengamiento del Decreto 814
        //  608100054   IVA - Dto 814 Contrapartida
        var totaldecreto814 = $("#totaldecreto814").val()*1;
        if($('#cuenta3332').length > 0){
            var orden = $('#cuenta3332').attr('orden');
            $('#Asiento1Movimiento'+orden+'Haber').val(totaldecreto814);
        }
        //  110403401   IVA - Credito Fiscal
        if($('#cuenta286').length > 0){
            var orden=0
            $('#cuenta286').each(function () {
                var asiento = $('#cuenta286').attr('asiento');
                if(asiento=='1'){
                    orden = $('#cuenta286').attr('orden');
                }
            });
            $('#Asiento1Movimiento'+orden+'Debe').val(totaldecreto814);
        }
    }
    function catchAsientoIVA(){
        $('#AsientoAddForm').submit(function(){
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
                    callAlertPopint(resp);
                },
                error: function(xhr,textStatus,error){
                    callAlertPopint(textStatus);
                    return false;
                }
            });
            return false;
        });
        
    }
    function imprimir(){
        window.print();
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
</script>
<?php
echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
echo $this->Form->input('impcliidPDT',array('value'=>$cliente['Impcli'][0]['id'],'type'=>'hidden'));
echo $this->Form->input('clinombre',array('value'=>$cliente['Cliente']['nombre'],'type'=>'hidden'));
echo $this->Form->input('cliid',array('value'=>$cliente['Cliente']['id'],'type'=>'hidden'));?>
<div class="eventosclientes index" id="contenedor">
    <div id="divLiquidarIVA" class="noExl">

    </div>

    <div class="index">
        <b style="display: inline">Papel de Trabajo</b>
        <?php
        echo $this->Form->button('Imprimir',
            array('type' => 'button',
                'id'=>"btnImprimir",
                'class' =>"btn_imprimir",
                'onClick' => "imprimir()"
            )
        );?>
        <?php
        echo $this->Form->button('Excel',
            array('type' => 'button',
                'id'=>"clickExcel",
                'class' =>"btn_imprimir",
            )
        );?>
        </br>
        <!-- Solo para Excel Export -->
        <table id="tblExcelHeader" class="tbl_tareas" style="border-collapse: collapse; width:100%;">
        </table>
        <!-- Solo para Excel Export -->
        <div style="width:100%; padding-top:5px" class="noExl"></div>
        <div style="width:100%;height:30px;"  class="Formhead noExl" >
            <div id="tabVentas_Iva" class="cliente_view_tab_active" onclick="CambiarTab('ventas');" style="width:24%;">
                <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Debito</label>
            </div>
            <div id="tabCompras_Iva" class="cliente_view_tab" onclick="CambiarTab('compras');" style="width:24%;">
                <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Credito</label>
            </div>
            <div id="tabLiquidacion_Iva" class="cliente_view_tab" onclick="CambiarTab('liquidacion');" style="width:24%;">
                <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Liquidacion</label>
            </div>
        </div>
        <div id="divContenedorVentas">

            <?php
                $TotalDebitoFiscal_SumaTotal = 0;
                $TotalCreditoFiscal_SumaTotal = 0;
                $TotalCreditoFiscalComputable_SumaTotal = 0;
            ?>

            <?php foreach ($actividades as $actividad){ ?>
            <div style="width:100%; height: 10px"></div>
            <?php
            $ActividadCliente_id = $actividad['Actividadcliente']['id'];
            ?>
            <div id='divContenedorTablaActividad_<?php echo $ActividadCliente_id; ?>'>
                <table id='divTituloActividad_<?php echo $ActividadCliente_id; ?>'  style="margin-bottom:0; cursor: pointer" onclick="MostrarTabla(this, 'ventas');">
                    <tr>
                        <td colspan="5" style='background-color:#76b5cd'>
                        <b>
                        <?php
                            echo 'ACTIVIDAD: '. $actividad['Actividade']['nombre']."-".$actividad['Actividadcliente']['descripcion'];
                        ?>
                        </b>
                        </td>
                    </tr>
                </table>
                <div id='divTablaTipoDebito_<?php echo $ActividadCliente_id; ?>_DebitoFiscal' style="">
                    <table id='divTablaActividad_DebitoFiscal_<?php echo $ActividadCliente_id; ?>' onclick="MostrarOperaciones(this,'ventas')"  style="margin-bottom:0; cursor: pointer">
                        <!---------- TIPO DEBITo: Debito Fiscal ---------->
                        <tr>
                            <td colspan="5" style='background-color:#76b5cd'>
                                > TIPO DEBITO: Debito Fiscal
                            </td>
                        </tr>
                    </table>
                    <?php
                    // > > Operaciones con Responsables Inscriptos
                    $Alicuota0 = false;
                    $TotalAlicuota0 = 0;
                    $TotalAlicuota0IVA = 0;
                    $Alicuota2_5 = false;
                    $TotalAlicuota2_5 = 0;
                    $TotalAlicuota2_5IVA = 0;
                    $Alicuota5_0 = false;
                    $TotalAlicuota5_0 = 0;
                    $TotalAlicuota5_0IVA = 0;
                    $Alicuota10_5 = false;
                    $TotalAlicuota10_5 = 0;
                    $TotalAlicuota10_5IVA = 0;
                    $Alicuota21_0 = false;
                    $TotalAlicuota21_0 = 0;
                    $TotalAlicuota21_Title = "";
                    $TotalAlicuota21_0IVA = 0;
                    $Alicuota27_0 = false;
                    $TotalAlicuota27_0 = 0;
                    $TotalAlicuota27_0IVA = 0;
                    foreach ($ventas as $venta){
                        if($venta['Actividadcliente']['id'] == $ActividadCliente_id)
                        {
                            if (!in_array($venta['Venta']['tipogasto_id'],$ingresosBienDeUso) && $venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso' && $venta['Venta']['condicioniva'] == 'responsableinscripto')
                            {
                                if ($venta['Venta']['alicuota'] == '0' || $venta['Venta']['alicuota'] == '')
                                {
                                    $Alicuota0 = true;
                                    $TotalAlicuota0 = $TotalAlicuota0 + $venta['Venta']['neto'];
                                    $TotalAlicuota0IVA += $venta['Venta']['iva'];
                                }
                                if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50')
                                {
                                    $Alicuota2_5 = true;
                                    $TotalAlicuota2_5 = $TotalAlicuota2_5 + $venta['Venta']['neto'];
                                    $TotalAlicuota2_5IVA += $venta['Venta']['iva'];
                                }
                                if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00')
                                {
                                    $Alicuota5_0 = true;
                                    $TotalAlicuota5_0  = $TotalAlicuota5_0  + $venta['Venta']['neto'];
                                    $TotalAlicuota5_0IVA += $venta['Venta']['iva'];
                                }
                                if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50')
                                {
                                    $Alicuota10_5 = true;
                                    $TotalAlicuota10_5 = $TotalAlicuota10_5 + $venta['Venta']['neto'];
                                    $TotalAlicuota10_5IVA += $venta['Venta']['iva'];
                                }
                                if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00')
                                {
                                    $Alicuota21_0 = true;
                                    $TotalAlicuota21_0 = $TotalAlicuota21_0 + $venta['Venta']['neto'];
                                    $TotalAlicuota21_0IVA += + $venta['Venta']['iva'];
                                    $TotalAlicuota21_Title .="Neto: ".$venta['Venta']['neto']
                                ." IVA: ".$venta['Venta']['iva']." // ";
                                }
                                if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00')
                                {
                                    $Alicuota27_0 = true;
                                    $TotalAlicuota27_0 = $TotalAlicuota27_0 + + $venta['Venta']['neto'];
                                    $TotalAlicuota27_0IVA += + $venta['Venta']['iva'];
                                }
                            }
                        }
                    };
                    if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
                    {
                        // > > Operaciones con Responsables Inscriptos?>
                        <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_DebitoFiscal_RespInsc' style="">
                            <table   >
                                <tr>
                                    <td colspan="5" style='background-color:#87cfeb'>
                                        > > Operaciones con Responsables Inscriptos
                                    </td>
                                </tr>
                                <tr style='background-color:#f0f0f0'>
                                    <td style="width:20%">Alicuota</td>
                                    <td style="width:20%">Monto Neto Grabado</td>
                                    <td style="width:20%">Debito Fiscal</td>
                                    <td style="width:20%">Debito Fiscal Facturado</td>
                                    <td style="width:20%">Debito Fiscal - Operaciones dación en pago decreto 1145/09</td>
                                </tr>
                                <tr>
                                    <?php
    //                                    if($Alicuota0)
    //                                    {
    //                                        echo '<tr>
    //                                                  <td style="width:20%">0</td>
    //                                                  <td style="width:20%">'.number_format($TotalAlicuota0, 2, ",", ".").'</td>
    //                                                  <td style="width:20%"></td>
    //                                                  <td style="width:20%"></td>
    //                                                  <td style="width:20%"></td>
    //                                                  </tr>
    //                                            ';
    //                                    }
                                        if($Alicuota2_5)
                                        {
                                            echo '<tr>
                                                  <td style="width:20%">2.5</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota2_5, 2, ",", ".").'</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota2_5IVA, 2, ",", ".").'</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota2_5IVA, 2, ",", ".").'</td>
                                                  <td style="width:20%">0</td>
                                                  </tr>
                                            ';
                                        }
                                        if($Alicuota5_0)
                                        {
                                            echo '<tr>
                                                  <td style="width:20%">5.0</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota5_0, 2, ",", ".").'</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota5_0IVA, 2, ",", ".").'</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota5_0IVA, 2, ",", ".").'</td>
                                                  <td style="width:20%">0</td>
                                                  </tr>
                                            ';
                                        }
                                        if($Alicuota10_5)
                                        {
                                            echo '<tr>
                                                  <td style="width:20%">10.5</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota10_5, 2, ",", ".").'</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota10_5IVA, 2, ",", ".").'</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota10_5IVA, 2, ",", ".").'</td>
                                                  <td style="width:20%">0</td>
                                                  </tr>
                                            ';
                                        }
                                        if($Alicuota21_0)
                                        {
                                            echo '<tr title="'.$TotalAlicuota21_Title.'">
                                                  <td style="width:20%">21.0</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota21_0, 2, ",", ".").'</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota21_0IVA, 2, ",", ".").'</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota21_0IVA, 2, ",", ".").'</td>
                                                  <td style="width:20%">0</td>
                                                  </tr>
                                            ';
                                        }
                                        if($Alicuota27_0)
                                        {
                                            echo '<tr>
                                                  <td style="width:20%">27.0</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota27_0, 2, ",", ".").'</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota27_0IVA, 2, ",", ".").'</td>
                                                  <td style="width:20%">'.number_format($TotalAlicuota27_0IVA, 2, ",", ".").'</td>
                                                  <td style="width:20%">0</td>
                                                  </tr>
                                            ';
                                        }
                                        if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
                                        {
                                            $TotalNeto = $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                                            $TotalDebitoFiscal = $TotalAlicuota2_5IVA + $TotalAlicuota5_0IVA + $TotalAlicuota10_5IVA + $TotalAlicuota21_0IVA + $TotalAlicuota27_0IVA ;
                                            echo '<tr>
                                                  <td style="width:20%">Totales</td>
                                                  <td style="width:20%">'.number_format($TotalNeto, 2, ",", ".").'</td>
                                                  <td style="width:20%">'.number_format($TotalDebitoFiscal, 2, ",", ".").'</td>
                                                  <td style="width:20%">'.number_format($TotalDebitoFiscal, 2, ",", ".").'</td>
                                                  <td style="width:20%">0</td>
                                                </tr>
                                            ';
                                            $TotalDebitoFiscal_SumaTotal = $TotalDebitoFiscal_SumaTotal + $TotalDebitoFiscal;
                                        }
                                    ?>
                                    </tr>
                            </table>
                        </div>
                    <?php }

                    //> > Operaciones con Consumidores finales, Exentos y No alcanzados
                    $Alicuota0 = false;
                    $TotalAlicuota0 = 0;
                    $TotalAlicuota0IVA = 0;
                    $Alicuota2_5 = false;
                    $TotalAlicuota2_5 = 0;
                    $TotalAlicuota2_5IVA = 0;
                    $Alicuota5_0 = false;
                    $TotalAlicuota5_0 = 0;
                    $TotalAlicuota5_0IVA = 0;
                    $Alicuota10_5 = false;
                    $TotalAlicuota10_5 = 0;
                    $TotalAlicuota10_5IVA = 0;
                    $Alicuota21_0 = false;
                    $TotalAlicuota21_0 = 0;
                    $TotalAlicuota21_0IVA = 0;
                    $Alicuota27_0 = false;
                    $TotalAlicuota27_0 = 0;
                    $TotalAlicuota27_0IVA = 0;

                    foreach ($ventas as $venta){
                        if($venta['Actividadcliente']['id'] == $ActividadCliente_id)
                        {
                            if (!in_array($venta['Venta']['tipogasto_id'],$ingresosBienDeUso) && $venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso' && $venta['Venta']['condicioniva'] == 'consf/exento/noalcanza')
                            {
                                $totalacomputar = $venta['Venta']['total']-$venta['Venta']['excentos']-$venta['Venta']['nogravados'];
                                if ($venta['Venta']['alicuota'] == '0' || $venta['Venta']['alicuota'] == '')
                                {
                                    $Alicuota0 = true;
                                    $TotalAlicuota0 += $totalacomputar;
                                    $TotalAlicuota0IVA += $venta['Venta']['iva'];
                                }
                                if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50')
                                {
                                    $Alicuota2_5 = true;
                                    $TotalAlicuota2_5 += $totalacomputar;
                                    $TotalAlicuota2_5IVA += $venta['Venta']['iva'];
                                }
                                if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00')
                                {
                                    $Alicuota5_0 = true;
                                    $TotalAlicuota5_0  += $totalacomputar;
                                    $TotalAlicuota5_0IVA  += $venta['Venta']['iva'];
                                }
                                if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50')
                                {
                                    $Alicuota10_5 = true;
                                    $TotalAlicuota10_5 += $totalacomputar;
                                    $TotalAlicuota10_5IVA += $venta['Venta']['iva'];
                                }
                                if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00')
                                {
                                    $Alicuota21_0 = true;
                                    $TotalAlicuota21_0 +=$totalacomputar;
                                    $TotalAlicuota21_0IVA +=$venta['Venta']['iva'];
                                }
                                if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00')
                                {
                                    $Alicuota27_0 = true;
                                    $TotalAlicuota27_0 += $totalacomputar;
                                    $TotalAlicuota27_0IVA += $venta['Venta']['iva'];
                                }
                            }
                        }
                    };


                    if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
                        //> > Operaciones con Consumidores finales, Exentos y No alcanzados?>
                        <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_DebitoFiscal_ConsF'
                             style="">
                            <table >
                                <tr>
                                    <td colspan="5" style='background-color:#87cfeb'>
                                        > > Operaciones con Consumidores finales, Exentos y No alcanzados
                                    </td>
                                </tr>
                                <tr style='background-color:#f0f0f0'>
                                    <td style="width:20%">Alicuota</td>
                                    <td style="width:20%">Monto total facturado</td>
                                    <td style="width:20%">Debito Fiscal</td>
                                    <td style="width:20%"></td>
                                    <td style="width:20%"></td>
                                </tr>
                                <tr>

                                    <?php
    //                                if($Alicuota0)
    //                                {
    //                                    echo '<tr>
    //                                  <td style="width:20%">0</td>
    //                                  <td style="width:20%">'.number_format($TotalAlicuota0, 2, ",", ".").'</td>
    //                                  <td style="width:20%">0</td>
    //                                  <td style="width:20%"></td>
    //                                  <td style="width:20%">0</td>
    //                                  </tr>
    //                            ';
    //                                }
                                    if ($Alicuota2_5) {
                                        echo '<tr>
                                      <td style="width:20%">2.5</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota2_5 , 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota2_5IVA, 2, ",", "."). '</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                      </tr>
                                ';
                                    }
                                    if ($Alicuota5_0) {
                                        echo '<tr>
                                      <td style="width:20%">5.0</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota5_0 , 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota5_0IVA , 2, ",", "."). '</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                      </tr>
                                ';
                                    }
                                    if ($Alicuota10_5) {
                                        echo '<tr>
                                      <td style="width:20%">10.5</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota10_5  , 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota10_5IVA  , 2, ",", "."). '</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                      </tr>
                                ';
                                    }
                                    if ($Alicuota21_0) {
                                        echo '<tr>
                                      <td style="width:20%">21.0</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota21_0  , 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota21_0IVA , 2, ",", "."). '</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                      </tr>
                                ';
                                    }
                                    if ($Alicuota27_0) {
                                        echo '<tr>
                                      <td style="width:20%">27.0</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota27_0  , 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota27_0IVA  , 2, ",", "."). '</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                      </tr>
                                ';
                                    }
                                    if ($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
                                        $TotalNeto = $TotalAlicuota0 + $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                                        $TotalDebitoFiscal = $TotalAlicuota2_5IVA + $TotalAlicuota5_0IVA + $TotalAlicuota10_5IVA +
                                            $TotalAlicuota21_0IVA + $TotalAlicuota27_0IVA ;
                                        echo '<tr>
                                      <td style="width:20%">Totales</td>
                                      <td style="width:20%">' . number_format($TotalNeto  , 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalDebitoFiscal  , 2, ",", "."). '</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                    </tr>
                                ';
                                        $TotalDebitoFiscal_SumaTotal = $TotalDebitoFiscal_SumaTotal + $TotalDebitoFiscal;
                                    }
                                    ?>
                                </tr>
                            </table>
                        </div>
                        <?php
                    }


                    $Alicuota0 = false;
                    $TotalAlicuota0 = 0;
                    $TotalAlicuota0IVA = 0;
                    $Alicuota2_5 = false;
                    $TotalAlicuota2_5 = 0;
                    $TotalAlicuota2_5IVA = 0;
                    $Alicuota5_0 = false;
                    $TotalAlicuota5_0 = 0;
                    $TotalAlicuota5_0IVA = 0;
                    $Alicuota10_5 = false;
                    $TotalAlicuota10_5 = 0;
                    $TotalAlicuota10_5IVA = 0;
                    $Alicuota21_0 = false;
                    $TotalAlicuota21_0 = 0;
                    $TotalAlicuota21_0IVA = 0;
                    $Alicuota27_0 = false;
                    $TotalAlicuota27_0 = 0;
                    $TotalAlicuota27_0IVA = 0;
                    foreach ($ventas as $venta){
                        //endforeach;
                        //echo $venta['Actividadcliente']['actividade_id']. ' - '.$ActividadCliente_id;
                        if($venta['Actividadcliente']['id'] == $ActividadCliente_id)
                        {
                            if (!in_array($venta['Venta']['tipogasto_id'],$ingresosBienDeUso) && $venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso' && $venta['Venta']['condicioniva'] == 'monotributista')
                            {
                                if ($venta['Venta']['alicuota'] == '0' || $venta['Venta']['alicuota'] == '')
                                {
                                    $Alicuota0 = true;
                                    $TotalAlicuota0 += $venta['Venta']['total'];
                                    $TotalAlicuota0IVA += $venta['Venta']['iva'];
                                }
                                if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50')
                                {
                                    $Alicuota2_5 = true;
                                    $TotalAlicuota2_5 += $venta['Venta']['total'];
                                    $TotalAlicuota2_5IVA += $venta['Venta']['iva'];
                                }
                                if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00')
                                {
                                    $Alicuota5_0 = true;
                                    $TotalAlicuota5_0  += $venta['Venta']['total'];
                                    $TotalAlicuota5_0IVA  += $venta['Venta']['iva'];
                                }
                                if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50')
                                {
                                    $Alicuota10_5 = true;
                                    $TotalAlicuota10_5 += $venta['Venta']['total'];
                                    $TotalAlicuota10_5IVA += $venta['Venta']['iva'];
                                }
                                if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00')
                                {
                                    $Alicuota21_0 = true;
                                    $TotalAlicuota21_0 +=$venta['Venta']['total'];
                                    $TotalAlicuota21_0IVA +=$venta['Venta']['iva'];
                                }
                                if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00')
                                {
                                    $Alicuota27_0 = true;
                                    $TotalAlicuota27_0 += $venta['Venta']['total'];
                                    $TotalAlicuota27_0IVA += $venta['Venta']['iva'];
                                }
                            }
                        }
                    };
                    if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0){
                        // > > Operaciones con Monotributistas - Régimen Simplificado?>
                        <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_DebitoFiscal_Monotributista' style="">
                            <table   >
                                <tr>
                                    <td colspan="5" style='background-color:#87cfeb'>
                                        > > Operaciones con Monotributistas - Régimen Simplificado
                                    </td>
                                </tr>
                                <tr style='background-color:#f0f0f0'>
                                    <td style="width:20%">Alicuota</td>
                                    <td style="width:20%">Monto total facturado</td>
                                    <td style="width:20%">Debito Fiscal</td>
                                    <td style="width:20%"></td>
                                    <td style="width:20%"></td>
                                </tr>
                                <tr>

                                <?php
                                    //no debo mostrar alicuota 0
    //                                if($Alicuota0)
    //                                {
    //                                    echo '<tr>
    //                                              <td style="width:20%">0</td>
    //                                              <td style="width:20%">'.number_format($TotalAlicuota0, 2, ",", ".").'</td>
    //                                              <td style="width:20%"></td>
    //                                              <td style="width:20%"></td>
    //                                              <td style="width:20%"></td>
    //                                          </tr>
    //                                                ';
    //                                }
                                    if($Alicuota2_5)
                                    {
                                        echo '<tr>
                                              <td style="width:20%">2.5</td>
                                              <td style="width:20%">'.number_format($TotalAlicuota2_5, 2, ",", ".").'</td>
                                              <td style="width:20%">'.number_format($TotalAlicuota2_5IVA , 2, ",", ".").'</td>
                                              <td style="width:20%"></td>
                                              <td style="width:20%"></td>
                                              </tr>
                                        ';
                                    }
                                    if($Alicuota5_0)
                                    {
                                        echo '<tr>
                                              <td style="width:20%">5.0</td>
                                              <td style="width:20%">'.number_format($TotalAlicuota5_0, 2, ",", ".").'</td>
                                              <td style="width:20%">'.number_format($TotalAlicuota5_0IVA, 2, ",", ".").'</td>
                                              <td style="width:20%"></td>
                                              <td style="width:20%"></td>
                                              </tr>
                                        ';
                                    }
                                    if($Alicuota10_5)
                                    {
                                        echo '<tr>
                                              <td style="width:20%">10.5</td>
                                              <td style="width:20%">'.number_format($TotalAlicuota10_5, 2, ",", ".").'</td>
                                              <td style="width:20%">'.number_format($TotalAlicuota10_5IVA, 2, ",", ".").'</td>
                                              <td style="width:20%"></td>
                                              <td style="width:20%"></td>
                                              </tr>
                                        ';
                                    }
                                    if($Alicuota21_0)
                                    {
                                        echo '<tr>
                                              <td style="width:20%">21.0</td>
                                              <td style="width:20%">'.number_format($TotalAlicuota21_0, 2, ",", ".").'</td>
                                              <td style="width:20%">'.number_format($TotalAlicuota21_0IVA, 2, ",", ".").'</td>
                                              <td style="width:20%"></td>
                                              <td style="width:20%"></td>
                                              </tr>
                                        ';
                                    }
                                    if($Alicuota27_0)
                                    {
                                        echo '<tr>
                                              <td style="width:20%">27.0</td>
                                              <td style="width:20%">'.number_format($TotalAlicuota27_0, 2, ",", ".").'</td>
                                              <td style="width:20%">'.number_format($TotalAlicuota27_0IVA, 2, ",", ".").'</td>
                                              <td style="width:20%"></td>
                                              <td style="width:20%"></td>
                                              </tr>
                                        ';
                                    }
                                    if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
                                    {
                                        $TotalNeto = $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                                        $TotalDebitoFiscal = $TotalAlicuota2_5IVA + $TotalAlicuota5_0IVA + $TotalAlicuota10_5IVA
                                            + $TotalAlicuota21_0IVA + $TotalAlicuota27_0IVA;
                                        echo '<tr>
                                              <td style="width:20%">Totales</td>
                                              <td style="width:20%">'.number_format($TotalNeto, 2, ",", ".").'</td>
                                              <td style="width:20%">'.number_format($TotalDebitoFiscal, 2, ",", ".").'</td>
                                              <td style="width:20%"></td>
                                              <td style="width:20%"></td>
                                            </tr>
                                        ';
                                        $TotalDebitoFiscal_SumaTotal = $TotalDebitoFiscal_SumaTotal + $TotalDebitoFiscal;
                                    }
                                ?>
                                </tr>
                            </table>
                        </div>
                    <?php
                    }


                    $ExentoYNoGravado = false;
                    $TotalExentoYNoGravado  = 0;

                    foreach ($ventas as $venta){
                        if($venta['Actividadcliente']['id'] == $ActividadCliente_id)
                        {
                            if (!in_array($venta['Venta']['tipogasto_id'],$ingresosBienDeUso) && $venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso')
                            {
                                if(
                                    (($venta['Venta']['nogravados']*1)>0)
                                    ||
                                    (($venta['Venta']['excentos']*1)>0)
                                ){
                                    $ExentoYNoGravado = true;
                                }
                                $TotalExentoYNoGravado += $venta['Venta']['nogravados'];
                                $TotalExentoYNoGravado += $venta['Venta']['excentos'];
                            }
                            else{
                                if(
                                    (($venta['Venta']['nogravados']*1)>0)
                                    ||
                                    (($venta['Venta']['excentos']*1)>0)
                                ){
                                    $ExentoYNoGravado = true;
                                }
                                $TotalExentoYNoGravado -= $venta['Venta']['nogravados'];
                                $TotalExentoYNoGravado -= $venta['Venta']['excentos'];
                            }
                        }
                    };
                    if($ExentoYNoGravado) {
                    //> > OPERACION: No gravadas y exentas?>
                    <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_DebitoFiscal_ConsF'
                         style="">
                        <table >
                            <tr>
                                <td colspan="5" style='background-color:#87cfeb'>
                                    > > Operaciones No Gravadas y Exentas
                                </td>
                            </tr>
                            <tr style='background-color:#f0f0f0'>
                                <td style="width:20%">Monto total facturado</td>
                                <td style="width:20%"></td>
                                <td style="width:20%"></td>
                                <td style="width:20%"></td>
                                <td style="width:20%"></td>
                            </tr>
                            <tr>
                                <?php
                                echo '<tr>
                                  <td style="width:20%">'.number_format($TotalExentoYNoGravado, 2, ",", ".").'</td>
                                  <td style="width:20%"></td>
                                  <td style="width:20%"></td>
                                  <td style="width:20%"></td>
                                  <td style="width:20%"></td>
                                  </tr>
                                ';
                                ?>
                            </tr>
                        </table>
                    </div>
                    <?php
                    }
                    ?>
            </div>
            <!---------- FIN TIPO DEBITo: Debito Fiscal ---------->
            <!---------- TIPO DEBITo: Bines de Uso ---------->
                <div id='divTablaTipoDebito_<?php echo $ActividadCliente_id; ?>_BsUso' style="">
                <table id='divTablaActividad_BsUso_<?php echo $ActividadCliente_id; ?>' onclick="MostrarOperaciones(this,'ventas')"  style="margin-bottom:0; cursor: pointer" >
                <tr>
                    <td colspan="5" style='background-color:#76b5cd'>
                        > TIPO DEBITO: Bienes de Uso
                    </td>
                </tr>
                </table>
                <?php
                $Alicuota0 = false;
                $TotalAlicuota0 = 0;
                $TotalAlicuota0IVA = 0;
                $Alicuota2_5 = false;
                $TotalAlicuota2_5 = 0;
                $TotalAlicuota2_5IVA = 0;
                $Alicuota5_0 = false;
                $TotalAlicuota5_0 = 0;
                $TotalAlicuota5_0IVA = 0;
                $Alicuota10_5 = false;
                $TotalAlicuota10_5 = 0;
                $TotalAlicuota10_5IVA = 0;
                $Alicuota21_0 = false;
                $TotalAlicuota21_0 = 0;
                $TotalAlicuota21_0IVA = 0;
                $Alicuota27_0 = false;
                $TotalAlicuota27_0 = 0;
                $TotalAlicuota27_0IVA = 0;
                foreach ($ventas as $venta){
                    if($venta['Actividadcliente']['id'] == $ActividadCliente_id)
                    {
                        if (in_array($venta['Venta']['tipogasto_id'],$ingresosBienDeUso) && $venta['Venta']['condicioniva'] == 'responsableinscripto')
                        {
                            if ($venta['Venta']['alicuota'] == '0' || $venta['Venta']['alicuota'] == '')
                            {
                                $Alicuota0 = true;
                                $TotalAlicuota0 += $venta['Venta']['total'];
                                $TotalAlicuota0IVA += $venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50')
                            {
                                $Alicuota2_5 = true;
                                $TotalAlicuota2_5 += $venta['Venta']['total'];
                                $TotalAlicuota2_5IVA += $venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00')
                            {
                                $Alicuota5_0 = true;
                                $TotalAlicuota5_0  += $venta['Venta']['total'];
                                $TotalAlicuota5_0IVA  += $venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50')
                            {
                                $Alicuota10_5 = true;
                                $TotalAlicuota10_5 += $venta['Venta']['total'];
                                $TotalAlicuota10_5IVA += $venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00')
                            {
                                $Alicuota21_0 = true;
                                $TotalAlicuota21_0 +=$venta['Venta']['total'];
                                $TotalAlicuota21_0IVA +=$venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00')
                            {
                                $Alicuota27_0 = true;
                                $TotalAlicuota27_0 += $venta['Venta']['total'];
                                $TotalAlicuota27_0IVA += $venta['Venta']['iva'];
                            }
                        }
                    }
                };
                if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
                    ?>
                    <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_BsUso_RespInsc' style="">
                        <table >
                            <tr>
                                <td colspan="5" style='background-color:#87cfeb'>
                                    > > OPERACION: Responsable Inscripto
                                </td>
                            </tr>
                            <tr style='background-color:#f0f0f0'>
                                <td style="width:20%">Alicuota</td>
                                <td style="width:20%">Monto Neto Grabado</td>
                                <td style="width:20%">Debito Fiscal</td>
                                <td style="width:20%">Debito Fiscal Facturado</td>
                                <td style="width:20%">Debito Fiscal - Operaciones dación en pago decreto 1145/09</td>
                            </tr>
                            <tr>

                                <?php
    //                            if($Alicuota0)
    //                            {
    //                                echo '<tr>
    //                                  <td style="width:20%">0</td>
    //                                  <td style="width:20%">'.number_format($TotalAlicuota0  , 2, ",", ".").'</td>
    //                                  <td style="width:20%">0</td>
    //                                  <td style="width:20%">0</td>
    //                                  <td style="width:20%">0</td>
    //                                  </tr>
    //                            ';
    //                            }
                                if ($Alicuota2_5) {
                                    echo '<tr>
                                      <td style="width:20%">2.5</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota2_5   , 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota2_5IVA, 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota2_5IVA, 2, ",", "."). '</td>
                                      <td style="width:20%">0</td>
                                      </tr>
                                ';
                                }
                                if ($Alicuota5_0) {
                                    echo '<tr>
                                      <td style="width:20%">5.0</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota5_0, 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota5_0IVA, 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota5_0IVA, 2, ",", "."). '</td>
                                      <td style="width:20%">0</td>
                                      </tr>
                                ';
                                }
                                if ($Alicuota10_5) {
                                    echo '<tr>
                                      <td style="width:20%">10.5</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota10_5 , 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota10_5IVA, 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota10_5IVA, 2, ",", "."). '</td>
                                      <td style="width:20%">0</td>
                                      </tr>
                                ';
                                }
                                if ($Alicuota21_0) {
                                    echo '<tr>
                                      <td style="width:20%">21.0</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota21_0 , 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota21_0IVA, 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota21_0IVA, 2, ",", "."). '</td>
                                      <td style="width:20%">0</td>
                                      </tr>
                                ';
                                }
                                if ($Alicuota27_0) {
                                    echo '<tr>
                                      <td style="width:20%">27.0</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota27_0 , 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota27_0IVA, 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalAlicuota27_0IVA, 2, ",", "."). '</td>
                                      <td style="width:20%">0</td>
                                      </tr>
                                ';
                                }
                                if ($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
                                    $TotalNeto = $TotalAlicuota0 + $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                                    $TotalDebitoFiscal = $TotalAlicuota2_5IVA + $TotalAlicuota5_0IVA + $TotalAlicuota10_5IVA
                                        + $TotalAlicuota21_0IVA + $TotalAlicuota27_0IVA;
                                    echo '<tr>
                                      <td style="width:20%">Totales</td>
                                      <td style="width:20%">' . number_format($TotalNeto, 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalDebitoFiscal , 2, ",", "."). '</td>
                                      <td style="width:20%">' . number_format($TotalDebitoFiscal , 2, ",", "."). '</td>
                                      <td style="width:20%">0</td>
                                    </tr>
                                ';
                                    $TotalDebitoFiscal_SumaTotal = $TotalDebitoFiscal_SumaTotal + $TotalDebitoFiscal;
                                }
                                ?>
                            </tr>
                        </table>
                    </div>
                    <?php
                }
                $Alicuota0 = false;
                $TotalAlicuota0 = 0;
                $TotalAlicuota0IVA = 0;
                $Alicuota2_5 = false;
                $TotalAlicuota2_5 = 0;
                $TotalAlicuota2_5IVA = 0;
                $Alicuota5_0 = false;
                $TotalAlicuota5_0 = 0;
                $TotalAlicuota5_0IVA = 0;
                $Alicuota10_5 = false;
                $TotalAlicuota10_5 = 0;
                $TotalAlicuota10_5IVA = 0;
                $Alicuota21_0 = false;
                $TotalAlicuota21_0 = 0;
                $TotalAlicuota21_0IVA = 0;
                $Alicuota27_0 = false;
                $TotalAlicuota27_0 = 0;
                $TotalAlicuota27_0IVA = 0;
                foreach ($ventas as $venta){
                    if($venta['Actividadcliente']['id'] == $ActividadCliente_id)
                    {
                        if (in_array($venta['Venta']['tipogasto_id'],$ingresosBienDeUso)  && $venta['Venta']['condicioniva'] == 'monotributista')
                        {
                            if ($venta['Venta']['alicuota'] == '0' || $venta['Venta']['alicuota'] == '')
                            {
                                $Alicuota0 = true;
                                $TotalAlicuota0 += $venta['Venta']['total'];
                                $TotalAlicuota0IVA += $venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50')
                            {
                                $Alicuota2_5 = true;
                                $TotalAlicuota2_5 += $venta['Venta']['total'];
                                $TotalAlicuota2_5IVA += $venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00')
                            {
                                $Alicuota5_0 = true;
                                $TotalAlicuota5_0  += $venta['Venta']['total'];
                                $TotalAlicuota5_0IVA  += $venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50')
                            {
                                $Alicuota10_5 = true;
                                $TotalAlicuota10_5 += $venta['Venta']['total'];
                                $TotalAlicuota10_5IVA += $venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00')
                            {
                                $Alicuota21_0 = true;
                                $TotalAlicuota21_0 +=$venta['Venta']['total'];
                                $TotalAlicuota21_0IVA +=$venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00')
                            {
                                $Alicuota27_0 = true;
                                $TotalAlicuota27_0 += $venta['Venta']['total'];
                                $TotalAlicuota27_0IVA += $venta['Venta']['iva'];
                            }
                        }
                    }
                };
                if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
                ?>
                    <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_BsUso_Monotributista' style="">
                        <table   >
                            <tr>
                                <td colspan="5" style='background-color:#87cfeb'>
                                    > > OPERACION: Monotributista
                                </td>
                            </tr>
                            <tr style='background-color:#f0f0f0'>
                                <td style="width:20%">Alicuota</td>
                                <td style="width:20%">Monto total facturado</td>
                                <td style="width:20%">Debito Fiscal</td>
                                <td style="width:20%"></td>
                                <td style="width:20%"></td>
                            </tr>
                            <tr>

                            <?php
    //                            if($Alicuota0)
    //                            {
    //                                echo '<tr>
    //                                      <td style="width:20%">0</td>
    //                                      <td style="width:20%">'.number_format($TotalAlicuota0, 2, ",", ".").'</td>
    //                                      <td style="width:20%"></td>
    //                                      <td style="width:20%"></td>
    //                                      <td style="width:20%"></td>
    //                                      </tr>
    //                                ';
    //                            }
                                if($Alicuota2_5)
                                {
                                    echo '<tr>
                                         <td>2.5</td>
                                          <td>'.number_format($TotalAlicuota2_5, 2, ",", ".").'</td>
                                          <td>'.number_format($TotalAlicuota2_5IVA, 2, ",", ".").'</td>
                                          <td></td>
                                          <td></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota5_0)
                                {
                                    echo '<tr>
                                          <td style="width:20%">5.0</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota5_0, 2, ",", ".").'</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota5_0IVA, 2, ",", ".").'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota10_5)
                                {
                                    echo '<tr>
                                          <td style="width:20%">10.5</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota10_5, 2, ",", ".").'</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota10_5IVA, 2, ",", ".").'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota21_0)
                                {
                                    echo '<tr>
                                          <td style="width:20%">21.0</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota21_0, 2, ",", ".").'</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota21_0IVA, 2, ",", ".").'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota27_0)
                                {
                                    echo '<tr>
                                          <td style="width:20%">27.0</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota27_0, 2, ",", ".").'</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota27_0IVA, 2, ",", ".").'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
                                {
                                    $TotalNeto = $TotalAlicuota0 + $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                                    $TotalDebitoFiscal = $TotalAlicuota2_5IVA + $TotalAlicuota5_0IVA + $TotalAlicuota10_5IVA
                                        + $TotalAlicuota21_0IVA + $TotalAlicuota27_0IVA;
                                    echo '<tr>
                                          <td style="width:20%">Totales</td>
                                          <td style="width:20%">'.number_format($TotalNeto, 2, ",", ".").'</td>
                                          <td style="width:20%">'.number_format($TotalDebitoFiscal, 2, ",", ".").'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                        </tr>
                                    ';
                                    $TotalDebitoFiscal_SumaTotal = $TotalDebitoFiscal_SumaTotal + $TotalDebitoFiscal;
                                }
                            ?>
                            </tr>
                        </table>
                    </div>
                <?php
                }
                $Alicuota0 = false;
                $TotalAlicuota0 = 0;
                $TotalAlicuota0IVA = 0;
                $Alicuota2_5 = false;
                $TotalAlicuota2_5 = 0;
                $TotalAlicuota2_5IVA = 0;
                $Alicuota5_0 = false;
                $TotalAlicuota5_0 = 0;
                $TotalAlicuota5_0IVA = 0;
                $Alicuota10_5 = false;
                $TotalAlicuota10_5 = 0;
                $TotalAlicuota10_5IVA = 0;
                $Alicuota21_0 = false;
                $TotalAlicuota21_0 = 0;
                $TotalAlicuota21_0IVA = 0;
                $Alicuota27_0 = false;
                $TotalAlicuota27_0 = 0;
                $TotalAlicuota27_0IVA = 0;

                foreach ($ventas as $venta){
                    if($venta['Actividadcliente']['id'] == $ActividadCliente_id)
                    {
                        if (in_array($venta['Venta']['tipogasto_id'],$ingresosBienDeUso) && $venta['Venta']['condicioniva'] == 'consf/exento/noalcanza')
                        {
                            if ($venta['Venta']['alicuota'] == '0' || $venta['Venta']['alicuota'] == '')
                            {
                                $Alicuota0 = true;
                                $TotalAlicuota0 += $venta['Venta']['total'];
                                $TotalAlicuota0IVA += $venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50')
                            {
                                $Alicuota2_5 = true;
                                $TotalAlicuota2_5 += $venta['Venta']['total'];
                                $TotalAlicuota2_5IVA += $venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00')
                            {
                                $Alicuota5_0 = true;
                                $TotalAlicuota5_0  += $venta['Venta']['total'];
                                $TotalAlicuota5_0IVA  += $venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50')
                            {
                                $Alicuota10_5 = true;
                                $TotalAlicuota10_5 += $venta['Venta']['total'];
                                $TotalAlicuota10_5IVA += $venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00')
                            {
                                $Alicuota21_0 = true;
                                $TotalAlicuota21_0 +=$venta['Venta']['total'];
                                $TotalAlicuota21_0IVA +=$venta['Venta']['iva'];
                            }
                            if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00')
                            {
                                $Alicuota27_0 = true;
                                $TotalAlicuota27_0 += $venta['Venta']['total'];
                                $TotalAlicuota27_0IVA += $venta['Venta']['iva'];
                            }
                        }
                    }
                };
                if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
                    ?>
                    <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_BsUso_ConsF' style="">
                        <table   >
                            <tr>
                                <td colspan="5" style='background-color:#87cfeb'>
                                    > > OPERACION: Cons. F, Exent. y No Alcan.
                                </td>
                            </tr>
                            <tr style='background-color:#f0f0f0'>
                                <td style="width:20%">Alicuota</td>
                                <td style="width:20%">Monto total facturado</td>
                                <td style="width:20%">Debito Fiscal</td>
                                <td style="width:20%"></td>
                                <td style="width:20%"></td>
                            </tr>
                            <tr>

                            <?php
    //                            if($Alicuota0)
    //                            {
    //                                echo '<tr>
    //                                          <td style="width:20%">0</td>
    //                                          <td style="width:20%">'.number_format($TotalAlicuota0, 2, ",", ".").'</td>
    //                                          <td style="width:20%"></td>
    //                                          <td style="width:20%"></td>
    //                                          <td style="width:20%"></td>
    //                                          </tr>
    //                                    ';
    //                            }
                                if($Alicuota2_5)
                                {
                                    echo '<tr>
                                          <td style="width:20%">2.5</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota2_5, 2, ",", ".").'</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota2_5IVA, 2, ",", ".").'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota5_0)
                                {
                                    echo '<tr>
                                          <td style="width:20%">5.0</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota5_0, 2, ",", ".").'</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota5_0IVA, 2, ",", ".").'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota10_5)
                                {
                                    echo '<tr>
                                          <td style="width:20%">10.5</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota10_5, 2, ",", ".").'</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota10_5IVA, 2, ",", ".").'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota21_0)
                                {
                                    echo '<tr>
                                          <td style="width:20%">21.0</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota21_0, 2, ",", ".").'</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota21_0IVA, 2, ",", ".").'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota27_0)
                                {
                                    echo '<tr>
                                          <td style="width:20%">27.0</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota27_0, 2, ",", ".").'</td>
                                          <td style="width:20%">'.number_format($TotalAlicuota27_0IVA, 2, ",", ".").'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
                                {
                                    $TotalNeto = $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                                    $TotalDebitoFiscal = $TotalAlicuota2_5IVA + $TotalAlicuota5_0IVA + $TotalAlicuota10_5IVA
                                        + $TotalAlicuota21_0IVA + $TotalAlicuota27_0IVA;
                                    echo '<tr>
                                          <td style="width:20%">Totales</td>
                                          <td style="width:20%">'.number_format($TotalNeto, 2, ",", ".").'</td>
                                          <td style="width:20%">'.number_format($TotalDebitoFiscal, 2, ",", ".").'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                        </tr>
                                    ';
                                    $TotalDebitoFiscal_SumaTotal = $TotalDebitoFiscal_SumaTotal + $TotalDebitoFiscal;
                                }
                            ?>
                            </tr>
                        </table>
                    </div>
                    <?php
                } ?>
            </div>
            <!---------- FIN TIPO DEBITo: Bines de Uso ---------->

            <!------------- Restitucion Credito Fiscal ---------->
                <div id='divTablaTipoDebito_<?php echo $ActividadCliente_id; ?>_RestCredFiscal' style="">

            <table id='divTablaActividad_RestCredFiscal_<?php echo $ActividadCliente_id; ?>' onclick="MostrarOperaciones(this,'ventas')"  style="margin-bottom:0; cursor: pointer" >
                <tr>
                    <td colspan="5" style='background-color:#76b5cd'>
                        > TIPO CREDITO: Restituci&oacute;n de Cr&eacute;dito Fiscal
                    </td>
                </tr>
            </table>

            <?php

                $TotalRestCredFiscal=[];
                $TotalDcto814 = 0;
                $TotalPercepcionesIVA = 0;
                foreach ($compras as $compra) {
                    //aca vamos a calcular las perceptiones tambien, acumulando una sola vez para todas las actividades
                    if ($compra['Compra']['tipocredito'] == 'Restitucion credito fiscal' ) {
                        $TotalPercepcionesIVA -= $compra[0]['ivapercep'];
                    }else{
                        $TotalPercepcionesIVA += $compra[0]['ivapercep'];
                    }

                    //Debugger::dump($compra);
                    if ($compra['Actividadcliente']['id'] == $ActividadCliente_id) {
                        if ($compra['Compra']['tipocredito'] == 'Restitucion credito fiscal' ) {
                            $imputacion = $compra['Compra']['imputacion'];
                            $alicuota = $compra['Compra']['alicuota'];
                            if(!isset($TotalRestCredFiscal[$imputacion])){
                                $TotalRestCredFiscal[$imputacion]=[];
                                $TotalRestCredFiscal[$imputacion]['iva']=[];
                                $TotalRestCredFiscal[$imputacion]['neto']=[];
                                $TotalRestCredFiscal[$imputacion]['iva']=inicializarAlicuotas($TotalRestCredFiscal[$imputacion]['iva']);
                                $TotalRestCredFiscal[$imputacion]['neto']=inicializarAlicuotas($TotalRestCredFiscal[$imputacion]['neto']);
                            }
                            if ($compra['Compra']['alicuota'] == '')
                            { continue; }
                            $TotalRestCredFiscal[$imputacion]['iva'][$alicuota] += $compra[0]['iva'];
                            $TotalRestCredFiscal[$imputacion]['neto'][$alicuota] += $compra[0]['neto'];
                            $TotalDebitoFiscal_SumaTotal += $compra[0]['iva'];
                        }
                    }
                };
                echo $this->Form->input('totalpercepciones',
                    [
                        'type'=>'hidden',
                        'value'=>number_format($TotalPercepcionesIVA, 2, ".", "")
                    ]
                );
                // ACA la restitucion de credito de compra SUMA AL DEBITO tal vez esto deberia restar del credito en realidad

            foreach ($TotalRestCredFiscal as $tr => $tiporestitucion) {?>
                <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_RestCredFiscal' style="">
                    <table   >
                    <tr>
                        <td colspan="5" style='background-color:#87cfeb'>
                            > > <?php echo $tr ?>
                        </td>
                    </tr>
                    <tr style='background-color:#f0f0f0'>
                        <td>Alicuota</td>
                        <td>Monto Neto Grabado</td>
                        <td>IVA</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                    foreach ($tiporestitucion['neto'] as $n => $netos) {
                        if($netos*1==0){ continue; }?>
                        <tr>
                            <td><?php echo $n ?>%</td>
                            <td><?php echo number_format($tiporestitucion['neto'][$n], 2, ",", ".") ?></td>
                            <td><?php echo number_format($tiporestitucion['iva'][$n], 2, ",", ".") ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php
                    }
                    ?>
                    </table>
                </div>
                <?php
            }
            ?>
            <?php
            if($TotalDcto814>0){
            ?>
            <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_RestCredFiscal_Dcto814' style="">
                <table   >
                    <tr>
                        <td colspan="5" style='background-color:#87cfeb'>
                            > > OPERACION: Dcto. 814
                        </td>
                    </tr>
                    <tr style='background-color:#f0f0f0'>
                        <td>Monto Neto Grabado</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><?php echo number_format($TotalDcto814 , 2, ",", ".")?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <!----------- Fin Restitucion Credito Fiscal ---------->
            <?php } ?>
                </div>
            </div> <!-- fin divContenedorTablaActividad_ -->
        <?php } ; ?>
        </div>  <!-- fin divContenedorVentas -->
        <!--COMPRAS-->
        <div id="divContenedorCompras" style="/*height: 500px*/">
            <div style="margin-top:10px">(Coeficiente de Apropiacion <?php
                $coeficienteapropiacion = $cliente['Impcli'][0]['coeficienteapropiacion']  ;
                echo $coeficienteapropiacion;?> )
            </div>
            <div style="width:100%; height: 10px"></div>
            <table id='divTablaCompras_CreditoFiscal'  onclick="MostrarTabla(this,'compras');" style="margin-bottom:0; cursor: pointer">
            <!------------- Credito Fiscal ---------->
                <tr>
                    <td colspan="5" style='background-color:#76b5cd'>
                        <b>
                        TIPO CREDITO: Crédito Fiscal
                        </b>
                    </td>
                </tr>
            </table>
            <table id='divTablaComprasCredito_CreditoFiscal'  style="">
            <?php
            $Alicuota2_5 = false;
            $TotalAlicuota2_5 = 0;
            $Alicuota5_0 = false;
            $TotalAlicuota5_0 = 0;
            $Alicuota10_5 = false;
            $TotalAlicuota10_5 = 0;
            $Alicuota21_0 = false;
            $TotalAlicuota21_0 = 0;
            $Alicuota27_0 = false;
            $TotalAlicuota27_0 = 0;

            $TotalBnGral=[];
            $TotalComprasBienesConsFinales=[];
            $TotalLocaciones=[];
            $TotalPresServ=[];
            $TotalBsUso=[];
            $TotalOtrosConceptos=[];
            $TotalOperacionesExentasYNoGravadas=[];
            $TotalDcto814=[];

            $TotalComprasBienesConsFinales=inicializarArrayCompras($TotalComprasBienesConsFinales);
            $TotalComprasBienesConsFinales['mostrar']=false;

            $TotalBnGral=inicializarArrayCompras($TotalBnGral);
            $TotalBnGral['mostrar']=false;

            $TotalLocaciones=inicializarArrayCompras($TotalLocaciones);
            $TotalLocaciones['mostrar']=false;

            $TotalPresServ=inicializarArrayCompras($TotalPresServ);
            $TotalPresServ['mostrar']=false;

            $TotalBsUso=inicializarArrayCompras($TotalBsUso);
            $TotalBsUso['mostrar']=false;

            $TotalOtrosConceptos=inicializarArrayCompras($TotalOtrosConceptos);
            $TotalOtrosConceptos['mostrar']=false;

            $TotalOperacionesExentasYNoGravadas=inicializarArrayCompras($TotalOperacionesExentasYNoGravadas);
            $TotalOperacionesExentasYNoGravadas['mostrar']=false;

            $TotalDcto814=inicializarArrayCompras($TotalDcto814);
            $TotalDcto814['mostrar']=false;

            $TotalNoComputable = 0;
            $CreditoFiscalOtrosConceptosTitle="";

            foreach ($compras as $compra){
                $suma = 1;
                if($compra['Compra']['tipocredito'] == 'Restitucion credito fiscal')
                {
                    //$suma = -1;
                    continue;
                }
                if ($compra['Compra']['imputacion'] == 'Bs en Gral')
                {

                    if($compra['Compra']['condicioniva'] == 'consf/exento/noalcanza')
                    {
                        $TotalComprasBienesConsFinales['mostrar']=true;
                        $TotalComprasBienesConsFinales['Neto']['total'] += $compra[0]['neto']*$suma;
                        //Si es bienes en general y la condicion es exento vamos a mostrarlo en otra seccion
                        if($compra['Compra']['alicuota']=='0'){
                            $TotalComprasBienesConsFinales['Neto']['0'] += $compra[0]['neto']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='2.5'){
                            $TotalComprasBienesConsFinales['Neto']['2.5'] += $compra[0]['neto']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='5'){
                            $TotalComprasBienesConsFinales['Neto']['50'] += $compra[0]['neto']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='10.5'){
                            $TotalComprasBienesConsFinales['Neto']['10.5'] += $compra[0]['neto']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='21'){
                            $TotalComprasBienesConsFinales['Neto']['21'] += $compra[0]['neto']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='27'){
                            $TotalComprasBienesConsFinales['Neto']['27'] += $compra[0]['neto']*$suma;
                        }
                        if ($compra['Compra']['tipoiva'] == 'directo')
                        {
                            $TotalComprasBienesConsFinales['Directo']['total'] += $compra[0]['iva']*$suma;
                            if($compra['Compra']['alicuota']=='0'){
                                $TotalComprasBienesConsFinales['Directo']['0'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='2.5'){
                                $TotalComprasBienesConsFinales['Directo']['2.5'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='5'){
                                $TotalComprasBienesConsFinales['Directo']['50'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='10.5'){
                                $TotalComprasBienesConsFinales['Directo']['10.5'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='21'){
                                $TotalComprasBienesConsFinales['Directo']['21'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='27'){
                                $TotalComprasBienesConsFinales['Directo']['27'] += $compra[0]['iva']*$suma;
                            }
                        }
                        if ($compra['Compra']['tipoiva'] == 'prorateable')
                        {
                            $TotalComprasBienesConsFinales['Prorateable']['total'] += $compra[0]['iva']*$suma;
                            if($compra['Compra']['alicuota']=='0'){
                                $TotalComprasBienesConsFinales['Prorateable']['0'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='2,5'){
                                $TotalComprasBienesConsFinales['Prorateable']['2.5'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='5'){
                                $TotalComprasBienesConsFinales['Prorateable']['50'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='10.5'){
                                $TotalComprasBienesConsFinales['Prorateable']['10.5'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='21'){
                                $TotalComprasBienesConsFinales['Prorateable']['21'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='27'){
                                $TotalComprasBienesConsFinales['Prorateable']['27'] += $compra[0]['iva']*$suma;
                            }
                        }
                        continue;
                    }
                    $TotalBnGral['mostrar']=true;
                    $TotalBnGral['Neto']['total'] += $compra[0]['neto']*$suma;
                    if($compra['Compra']['alicuota']=='0'){
                        $TotalBnGral['Neto']['0'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='2.5'){
                        $TotalBnGral['Neto']['2.5'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='5'){
                        $TotalBnGral['Neto']['50'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='10.5'){
                        $TotalBnGral['Neto']['10.5'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='21'){
                        $TotalBnGral['Neto']['21'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='27'){
                        $TotalBnGral['Neto']['27'] += $compra[0]['neto']*$suma;
                    }
                    if ($compra['Compra']['tipoiva'] == 'directo')
                    {
                        $TotalBnGral['Directo']['total'] += $compra[0]['iva']*$suma;
                        if($compra['Compra']['alicuota']=='0'){
                            $TotalBnGral['Directo']['0'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='2.5'){
                            $TotalBnGral['Directo']['2.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='5'){
                            $TotalBnGral['Directo']['50'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='10.5'){
                            $TotalBnGral['Directo']['10.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='21'){
                            $TotalBnGral['Directo']['21'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='27'){
                            $TotalBnGral['Directo']['27'] += $compra[0]['iva']*$suma;
                        }
                    }
                    if ($compra['Compra']['tipoiva'] == 'prorateable')
                    {
                        $TotalBnGral['Prorateable']['total'] += $compra[0]['iva']*$suma;
                        if($compra['Compra']['alicuota']=='0'){
                            $TotalBnGral['Prorateable']['0'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='2,5'){
                            $TotalBnGral['Prorateable']['2.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='5'){
                            $TotalBnGral['Prorateable']['50'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='10.5'){
                            $TotalBnGral['Prorateable']['10.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='21'){
                            $TotalBnGral['Prorateable']['21'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='27'){
                            $TotalBnGral['Prorateable']['27'] += $compra[0]['iva']*$suma;
                        }
                    }
                }
                if ($compra['Compra']['imputacion'] == 'Locaciones')
                {
                    $TotalLocaciones['mostrar']=true;

                    $TotalLocaciones['Neto']['total'] += $compra[0]['neto']*$suma;
                    if($compra['Compra']['alicuota']=='0'){
                        $TotalLocaciones['Neto']['0'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='2.5'){
                        $TotalLocaciones['Neto']['2.5'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='5'){
                        $TotalLocaciones['Neto']['50'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='10.5'){
                        $TotalLocaciones['Neto']['10.5'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='21'){
                        $TotalLocaciones['Neto']['21'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='27'){
                        $TotalLocaciones['Neto']['27'] += $compra[0]['neto']*$suma;
                    }
                    if ($compra['Compra']['tipoiva'] == 'directo')
                    {
                        $TotalLocaciones['Directo']['total'] += $compra[0]['iva']*$suma;
                        if($compra['Compra']['alicuota']=='0'){
                            $TotalLocaciones['Directo']['0'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='2,5'){
                            $TotalLocaciones['Directo']['2.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='5'){
                            $TotalLocaciones['Directo']['50'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='10.5'){
                            $TotalLocaciones['Directo']['10.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='21'){
                            $TotalLocaciones['Directo']['21'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='27'){
                            $TotalLocaciones['Directo']['27'] += $compra[0]['iva']*$suma;
                        }
                    }
                    if ($compra['Compra']['tipoiva'] == 'prorateable')
                    {
                        $TotalLocaciones['Prorateable']['total'] += $compra[0]['iva']*$suma;
                        if($compra['Compra']['alicuota']=='0'){
                            $TotalLocaciones['Prorateable']['0'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='2,5'){
                            $TotalLocaciones['Prorateable']['2.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='5'){
                            $TotalLocaciones['Prorateable']['50'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='10.5'){
                            $TotalLocaciones['Prorateable']['10.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='21'){
                            $TotalLocaciones['Prorateable']['21'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='27'){
                            $TotalLocaciones['Prorateable']['27'] += $compra[0]['iva']*$suma;
                        }
                    }
                }
                if ($compra['Compra']['imputacion'] == 'Prest. Servicios')
                {
                    $TotalPresServ['mostrar']=true;

                    $TotalPresServ['Neto']['total'] += $compra[0]['neto']*$suma;
                    if($compra['Compra']['alicuota']=='0'){
                        $TotalPresServ['Neto']['0'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='2.5'){
                        $TotalPresServ['Neto']['2.5'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='5'){
                        $TotalPresServ['Neto']['50'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='10.5'){
                        $TotalPresServ['Neto']['10.5'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='21'){
                        $TotalPresServ['Neto']['21'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='27'){
                        $TotalPresServ['Neto']['27'] += $compra[0]['neto']*$suma;
                    }
                    if ($compra['Compra']['tipoiva'] == 'directo')
                    {
                        $TotalPresServ['Directo']['total'] += $compra[0]['iva']*$suma;
                        if($compra['Compra']['alicuota']=='0'){
                            $TotalPresServ['Directo']['0'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='2,5'){
                            $TotalPresServ['Directo']['2.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='5'){
                            $TotalPresServ['Directo']['50'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='10.5'){
                            $TotalPresServ['Directo']['10.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='21'){
                            $TotalPresServ['Directo']['21'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='27'){
                            $TotalPresServ['Directo']['27'] += $compra[0]['iva']*$suma;
                        }
                    }
                    if ($compra['Compra']['tipoiva'] == 'prorateable')
                    {
                        $TotalPresServ['Prorateable']['total'] += $compra[0]['iva']*$suma;
                        if($compra['Compra']['alicuota']=='0'){
                            $TotalPresServ['Prorateable']['0'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='2,5'){
                            $TotalPresServ['Prorateable']['2.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='5'){
                            $TotalPresServ['Prorateable']['50'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='10.5'){
                            $TotalPresServ['Prorateable']['10.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='21'){
                            $TotalPresServ['Prorateable']['21'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='27'){
                            $TotalPresServ['Prorateable']['27'] += $compra[0]['iva']*$suma;
                        }
                    }
                }
                if ($compra['Compra']['imputacion'] == 'Bs Uso')
                {
                    $TotalBsUso['mostrar']=true;

                    $TotalBsUso['Neto']['total'] += $compra[0]['neto']*$suma;
                    if($compra['Compra']['alicuota']=='0'){
                        $TotalBsUso['Neto']['0'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='2.5'){
                        $TotalBsUso['Neto']['2.5'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='5'){
                        $TotalBsUso['Neto']['50'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='10.5'){
                        $TotalBsUso['Neto']['10.5'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='21'){
                        $TotalBsUso['Neto']['21'] += $compra[0]['neto']*$suma;
                    }elseif ($compra['Compra']['alicuota']=='27'){
                        $TotalBsUso['Neto']['27'] += $compra[0]['neto']*$suma;
                    }
                    if ($compra['Compra']['tipoiva'] == 'directo')
                    {
                        $TotalBsUso['Directo']['total'] += $compra[0]['iva']*$suma;
                        if($compra['Compra']['alicuota']=='0'){
                            $TotalBsUso['Directo']['0'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='2,5'){
                            $TotalBsUso['Directo']['2.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='5'){
                            $TotalBsUso['Directo']['50'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='10.5'){
                            $TotalBsUso['Directo']['10.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='21'){
                            $TotalBsUso['Directo']['21'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='27'){
                            $TotalBsUso['Directo']['27'] += $compra[0]['iva']*$suma;
                        }
                    }
                    if ($compra['Compra']['tipoiva'] == 'prorateable')
                    {
                        $TotalBsUso['Prorateable']['total'] += $compra[0]['iva']*$suma;
                        if($compra['Compra']['alicuota']=='0'){
                            $TotalBsUso['Prorateable']['0'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='2,5'){
                            $TotalBsUso['Prorateable']['2.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='5'){
                            $TotalBsUso['Prorateable']['50'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='10.5'){
                            $TotalBsUso['Prorateable']['10.5'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='21'){
                            $TotalBsUso['Prorateable']['21'] += $compra[0]['iva']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='27'){
                            $TotalBsUso['Prorateable']['27'] += $compra[0]['iva']*$suma;
                        }
                    }
                }
                if ($compra['Compra']['imputacion'] == 'Otros Conceptos')
                    {
                        $TotalOtrosConceptos['mostrar']=true;
                        $CreditoFiscalOtrosConceptosTitle.="Se agrego la compra N° ". $compra['Compra']['numerocomprobante']."
                        ";

                        $TotalOtrosConceptos['Neto']['total'] += $compra[0]['neto']*$suma;
                        if($compra['Compra']['alicuota']=='0'){
                            $TotalOtrosConceptos['Neto']['0'] += $compra[0]['neto']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='2.5'){
                            $TotalOtrosConceptos['Neto']['2.5'] += $compra[0]['neto']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='5'){
                            $TotalOtrosConceptos['Neto']['50'] += $compra[0]['neto']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='10.5'){
                            $TotalOtrosConceptos['Neto']['10.5'] += $compra[0]['neto']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='21'){
                            $TotalOtrosConceptos['Neto']['21'] += $compra[0]['neto']*$suma;
                        }elseif ($compra['Compra']['alicuota']=='27'){
                            $TotalOtrosConceptos['Neto']['27'] += $compra[0]['neto']*$suma;
                        }
                        if ($compra['Compra']['tipoiva'] == 'directo')
                        {
                            $TotalOtrosConceptos['Directo']['total'] += $compra[0]['iva']*$suma;
                            if($compra['Compra']['alicuota']=='0'){
                                $TotalOtrosConceptos['Directo']['0'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='2,5'){
                                $TotalOtrosConceptos['Directo']['2.5'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='5'){
                                $TotalOtrosConceptos['Directo']['50'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='10.5'){
                                $TotalOtrosConceptos['Directo']['10.5'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='21'){
                                $TotalOtrosConceptos['Directo']['21'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='27'){
                                $TotalOtrosConceptos['Directo']['27'] += $compra[0]['iva']*$suma;
                            }
                        }
                        if ($compra['Compra']['tipoiva'] == 'prorateable')
                        {
                            $TotalOtrosConceptos['Prorateable']['total'] += $compra[0]['iva']*$suma;
                            if($compra['Compra']['alicuota']=='0'){
                                $TotalOtrosConceptos['Prorateable']['0'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='2,5'){
                                $TotalOtrosConceptos['Prorateable']['2.5'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='5'){
                                $TotalOtrosConceptos['Prorateable']['50'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='10.5'){
                                $TotalOtrosConceptos['Prorateable']['10.5'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='21'){
                                $TotalOtrosConceptos['Prorateable']['21'] += $compra[0]['iva']*$suma;
                            }elseif ($compra['Compra']['alicuota']=='27'){
                                $TotalOtrosConceptos['Prorateable']['27'] += $compra[0]['iva']*$suma;
                            }
                        }
                    }
                if ((($compra[0]['exentos']*1)>0)||(($compra[0]['nogravados']*1)>0)){
                    $TotalOperacionesExentasYNoGravadas['Neto']['total'] += $compra[0]['exentos']*$suma;
                    $TotalOperacionesExentasYNoGravadas['Neto']['total'] += $compra[0]['nogravados']*$suma;
                    $TotalOperacionesExentasYNoGravadas['mostrar']=true;
                }
            }
            //en 'Otros Conceptos' tengo q acumular los DEBE de los movimientos bancarios
            //que serian los movimientos que apuntan a la cuentacliente de la cuenta
            //286   110403401   IVA - Credito Fiscal General
            if(count($cuentascliente[0])>0){
                foreach ($cuentascliente[0]['Movimientosbancario'] as $movimientosbancario) {
                    $TotalOtrosConceptos['mostrar'] = true;
                    $subsaldo = $movimientosbancario['debito']-$movimientosbancario['credito'];
                    $CreditoFiscalOtrosConceptosTitle.="Se agrego el Mov Bancario N° "
                        . $movimientosbancario['ordencarga']."-"
                        .$movimientosbancario['concepto']." $"
                        .($subsaldo)."
                    ";
                    if($movimientosbancario['alicuota']=='0'){
    //                $TotalOtrosConceptos['Neto']['total'] += $subsaldo*1.0;
    //                $TotalOtrosConceptos['Neto']['0'] += $subsaldo*1.0;
                    }elseif ($movimientosbancario['alicuota']=='2.5'){
                        $TotalOtrosConceptos['Neto']['total'] += $subsaldo/0.025;
                        $TotalOtrosConceptos['Neto']['2.5'] += $subsaldo/0.025;
                    }elseif ($movimientosbancario['alicuota']=='5'){
                        $TotalOtrosConceptos['Neto']['total'] += $subsaldo/0.05;
                        $TotalOtrosConceptos['Neto']['50'] += $subsaldo/0.05;
                    }elseif ($movimientosbancario['alicuota']=='10.5'){
                        $TotalOtrosConceptos['Neto']['total'] += $subsaldo/0.105;
                        $TotalOtrosConceptos['Neto']['10.5'] += $subsaldo/0.105;
                    }elseif ($movimientosbancario['alicuota']=='21'){
                        $TotalOtrosConceptos['Neto']['total'] += $subsaldo/0.21;
                        $TotalOtrosConceptos['Neto']['21'] += $subsaldo/0.21;
                    }elseif ($movimientosbancario['alicuota']=='27'){
                        $TotalOtrosConceptos['Neto']['total'] += $subsaldo/0.27;
                        $TotalOtrosConceptos['Neto']['27'] += $subsaldo/0.27;
                    }

                    if($movimientosbancario['alicuota']=='0'){
    //                $TotalOtrosConceptos['Directo']['total'] += $subsaldo*0;
    //                $TotalOtrosConceptos['Directo']['0'] += $subsaldo;
                    }elseif ($movimientosbancario['alicuota']=='2.5'){
                        $TotalOtrosConceptos['Directo']['total'] += $subsaldo;
                        $TotalOtrosConceptos['Directo']['2.5'] += $subsaldo;
                    }elseif ($movimientosbancario['alicuota']=='5'){
                        $TotalOtrosConceptos['Directo']['total'] += $subsaldo;
                        $TotalOtrosConceptos['Directo']['50'] += $subsaldo;
                    }elseif ($movimientosbancario['alicuota']=='10.5'){
                        $TotalOtrosConceptos['Directo']['total'] += $subsaldo;
                        $TotalOtrosConceptos['Directo']['10.5'] += $subsaldo;
                    }elseif ($movimientosbancario['alicuota']=='21'){
                        $TotalOtrosConceptos['Directo']['total'] += $subsaldo;
                        $TotalOtrosConceptos['Directo']['21'] += $subsaldo;
                    }elseif ($movimientosbancario['alicuota']=='27'){
                        $TotalOtrosConceptos['Directo']['total'] += $subsaldo;
                        $TotalOtrosConceptos['Directo']['27'] += $subsaldo;
                    }
                }
            }
                $TotalDcto814_Directo=0;
                foreach ($cliente['Impcli'][0]['Conceptosrestante'] as $key => $conceptosrestante) {
                    if($conceptosrestante['conceptostipo_id']=='12'/*Decreto 814*/){
                        //tenemos que agregar campos como
            //                directo
            //                prorrateable
            //                creditofiscal/restirucioncreditofiscal
                        $TotalDcto814_Directo += $conceptosrestante['montoretenido'];
                        $TotalDcto814['mostrar']=true;
                    }
                }

            ?>
                <?php
                if($TotalBnGral['mostrar']) {
                    ?>
                    <tr>
                        <td colspan="7" style='background-color:#87cfeb'>
                            > OPERACION: Bs en Gral
                        </td>
                    </tr>
                    <tr style='background-color:#f0f0f0'>
                        <td>Alicuota</td>
                        <td>Monto Neto</td>
                        <td>Directo</td>
                        <td>Prorrateable</td>
                        <td>Computable</td>
                        <td>Facturado</td>
                        <td>Computable Total</td>
                    </tr>
                    <?php
                    if($TotalBnGral['Neto']['0']+$TotalBnGral['Directo']['0']+$TotalBnGral['Prorateable']['0'] != 0){ ?>
                        <tr>
                            <td><?php echo "0%" ?></td>
                            <td><?php echo number_format($TotalBnGral['Neto']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Prorateable']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Prorateable']['0'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['0'] + $TotalBnGral['Prorateable']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['0'] + ($TotalBnGral['Prorateable']['0'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                    <?php }
                    if($TotalBnGral['Neto']['2.5']+$TotalBnGral['Directo']['2.5']+$TotalBnGral['Prorateable']['2.5'] != 0) { ?>
                        <tr>
                            <td><?php echo "2.5%" ?></td>
                            <td><?php echo number_format($TotalBnGral['Neto']['2.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['2.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Prorateable']['2.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Prorateable']['2.5'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['2.5'] + $TotalBnGral['Prorateable']['2.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['2.5'] + ($TotalBnGral['Prorateable']['2.5'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalBnGral['Neto']['50']+$TotalBnGral['Directo']['50']+$TotalBnGral['Prorateable']['50'] != 0){ ?>
                        <tr>
                            <td><?php echo "5.0%" ?></td>
                            <td><?php echo number_format($TotalBnGral['Neto']['50'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['50'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Prorateable']['50'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Prorateable']['50'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['50'] + $TotalBnGral['Prorateable']['50'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['50'] + ($TotalBnGral['Prorateable']['50'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalBnGral['Neto']['10.5']+$TotalBnGral['Directo']['10.5']+$TotalBnGral['Prorateable']['10.5'] != 0){ ?>
                        <tr>
                            <td><?php echo "10.5%" ?></td>
                            <td><?php echo number_format($TotalBnGral['Neto']['10.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['10.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Prorateable']['10.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Prorateable']['10.5'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['10.5'] + $TotalBnGral['Prorateable']['10.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['10.5'] + ($TotalBnGral['Prorateable']['10.5'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalBnGral['Neto']['21']+$TotalBnGral['Directo']['21']+$TotalBnGral['Prorateable']['21'] != 0){ ?>
                        <tr>
                            <td><?php echo "21%" ?></td>
                            <td><?php echo number_format($TotalBnGral['Neto']['21'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['21'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Prorateable']['21'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Prorateable']['21'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['21'] + $TotalBnGral['Prorateable']['21'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['21'] + ($TotalBnGral['Prorateable']['21'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalBnGral['Neto']['27']+$TotalBnGral['Directo']['27']+$TotalBnGral['Prorateable']['27'] != 0){ ?>
                        <tr>
                            <td><?php echo "27%" ?></td>
                            <td><?php echo number_format($TotalBnGral['Neto']['27'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['27'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Prorateable']['27'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Prorateable']['27'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['27'] + $TotalBnGral['Prorateable']['27'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['27'] + ($TotalBnGral['Prorateable']['27'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalBnGral['Neto']['total']+$TotalBnGral['Directo']['total']+$TotalBnGral['Prorateable']['total'] != 0) { ?>
                        <tr>
                            <td>Totales</td>
                            <td><?php echo number_format($TotalBnGral['Neto']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Prorateable']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Prorateable']['total'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['total'] + $TotalBnGral['Prorateable']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBnGral['Directo']['total'] + ($TotalBnGral['Prorateable']['total'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>

                        <?php
                    }
                }
                $TotalNoComputable += ($TotalBnGral['Prorateable']['total'])- ($TotalBnGral['Prorateable']['total'] * $coeficienteapropiacion);
                $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalBnGral['Directo']['total'] + ($TotalBnGral['Prorateable']['total']));
                $TotalCreditoFiscalComputable_SumaTotal = $TotalCreditoFiscalComputable_SumaTotal + ($TotalBnGral['Directo']['total'] + ($TotalBnGral['Prorateable']['total'] * $coeficienteapropiacion));
                if($TotalComprasBienesConsFinales['mostrar']) {
                    ?>
                    <tr>
                        <td colspan="7" style='background-color:#87cfeb'>
                            > OPERACION: Compras Bienes a Consumidor Final
                        </td>
                    </tr>
                    <tr style='background-color:#f0f0f0'>
                        <td>Alicuota</td>
                        <td>Monto Neto</td>
                        <td>Directo</td>
                        <td>Prorrateable</td>
                        <td>Computable</td>
                        <td>Facturado</td>
                        <td>Computable Total</td>
                    </tr>
                    <?php
                    if($TotalComprasBienesConsFinales['Neto']['0']+$TotalComprasBienesConsFinales['Directo']['0']+$TotalComprasBienesConsFinales['Prorateable']['0'] != 0){ ?>
                        <tr>
                            <td><?php echo "0%" ?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Neto']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Prorateable']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Prorateable']['0'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['0'] + $TotalComprasBienesConsFinales['Prorateable']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['0'] + ($TotalComprasBienesConsFinales['Prorateable']['0'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                    <?php }
                    if($TotalComprasBienesConsFinales['Neto']['2.5']+$TotalComprasBienesConsFinales['Directo']['2.5']+$TotalComprasBienesConsFinales['Prorateable']['2.5'] != 0) { ?>
                        <tr>
                            <td><?php echo "2.5%" ?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Neto']['2.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['2.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Prorateable']['2.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Prorateable']['2.5'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['2.5'] + $TotalComprasBienesConsFinales['Prorateable']['2.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['2.5'] + ($TotalComprasBienesConsFinales['Prorateable']['2.5'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalComprasBienesConsFinales['Neto']['50']+$TotalComprasBienesConsFinales['Directo']['50']+$TotalComprasBienesConsFinales['Prorateable']['50'] != 0){ ?>
                        <tr>
                            <td><?php echo "5.0%" ?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Neto']['50'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['50'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Prorateable']['50'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Prorateable']['50'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['50'] + $TotalComprasBienesConsFinales['Prorateable']['50'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['50'] + ($TotalComprasBienesConsFinales['Prorateable']['50'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalComprasBienesConsFinales['Neto']['10.5']+$TotalComprasBienesConsFinales['Directo']['10.5']+$TotalComprasBienesConsFinales['Prorateable']['10.5'] != 0){ ?>
                        <tr>
                            <td><?php echo "10.5%" ?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Neto']['10.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['10.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Prorateable']['10.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Prorateable']['10.5'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['10.5'] + $TotalComprasBienesConsFinales['Prorateable']['10.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['10.5'] + ($TotalComprasBienesConsFinales['Prorateable']['10.5'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalComprasBienesConsFinales['Neto']['21']+$TotalComprasBienesConsFinales['Directo']['21']+$TotalComprasBienesConsFinales['Prorateable']['21'] != 0){ ?>
                        <tr>
                            <td><?php echo "21%" ?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Neto']['21'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['21'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Prorateable']['21'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Prorateable']['21'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['21'] + $TotalComprasBienesConsFinales['Prorateable']['21'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['21'] + ($TotalComprasBienesConsFinales['Prorateable']['21'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalComprasBienesConsFinales['Neto']['27']+$TotalComprasBienesConsFinales['Directo']['27']+$TotalComprasBienesConsFinales['Prorateable']['27'] != 0){ ?>
                        <tr>
                            <td><?php echo "27%" ?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Neto']['27'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['27'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Prorateable']['27'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Prorateable']['27'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['27'] + $TotalComprasBienesConsFinales['Prorateable']['27'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['27'] + ($TotalComprasBienesConsFinales['Prorateable']['27'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalComprasBienesConsFinales['Neto']['total']+$TotalComprasBienesConsFinales['Directo']['total']+$TotalComprasBienesConsFinales['Prorateable']['total'] != 0) { ?>
                        <tr>
                            <td>Totales</td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Neto']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Prorateable']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Prorateable']['total'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['total'] + $TotalComprasBienesConsFinales['Prorateable']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalComprasBienesConsFinales['Directo']['total'] + ($TotalComprasBienesConsFinales['Prorateable']['total'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>

                        <?php
                    }
                }
                $TotalNoComputable += ($TotalComprasBienesConsFinales['Prorateable']['total'])-($TotalComprasBienesConsFinales['Prorateable']['total'] * $coeficienteapropiacion);
                $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalComprasBienesConsFinales['Directo']['total'] + ($TotalComprasBienesConsFinales['Prorateable']['total']));
                $TotalCreditoFiscalComputable_SumaTotal = $TotalCreditoFiscalComputable_SumaTotal + ($TotalComprasBienesConsFinales['Directo']['total'] + ($TotalComprasBienesConsFinales['Prorateable']['total'] * $coeficienteapropiacion));
                //fin copiado
                if($TotalLocaciones['mostrar']) {
                    ?>
                    <tr>
                        <td colspan="7" style='background-color:#87cfeb'>
                            > OPERACION: Locaciones
                        </td>
                    </tr>
                    <tr style='background-color:#f0f0f0'>
                        <td>Alicuota</td>
                        <td>Monto Neto</td>
                        <td>Directo</td>
                        <td>Prorrateable</td>
                        <td>Computable</td>
                        <td>Facturado</td>
                        <td>Computable Total</td>
                    </tr>
                    <?php
                    if($TotalLocaciones['Neto']['0']+$TotalLocaciones['Directo']['0']+$TotalLocaciones['Prorateable']['0'] != 0) { ?>
                        <tr>
                            <td><?php echo "0%" ?></td>
                            <td><?php echo number_format($TotalLocaciones['Neto']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalLocaciones['Directo']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalLocaciones['Prorateable']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalLocaciones['Prorateable']['0'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalLocaciones['Directo']['0'] + $TotalLocaciones['Prorateable']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalLocaciones['Directo']['0'] + ($TotalLocaciones['Prorateable']['0'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalLocaciones['Neto']['2.5']+$TotalLocaciones['Directo']['2.5']+$TotalLocaciones['Prorateable']['2.5'] != 0) { ?>
                    <tr>
                        <td><?php echo "2.5%" ?></td>
                        <td><?php echo number_format($TotalLocaciones['Neto']['2.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['2.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Prorateable']['2.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Prorateable']['2.5'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['2.5'] + $TotalLocaciones['Prorateable']['2.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['2.5'] + ($TotalLocaciones['Prorateable']['2.5'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                        <?php
                    }
                    if($TotalLocaciones['Neto']['50']+$TotalLocaciones['Directo']['50']+$TotalLocaciones['Prorateable']['50'] != 0) { ?>
                    <tr>
                        <td><?php echo "5.0%" ?></td>
                        <td><?php echo number_format($TotalLocaciones['Neto']['50'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['50'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Prorateable']['50'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Prorateable']['50'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['50'] + $TotalLocaciones['Prorateable']['50'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['50'] + ($TotalLocaciones['Prorateable']['50'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                        <?php
                    }
                    if($TotalLocaciones['Neto']['10.5']+$TotalLocaciones['Directo']['10.5']+$TotalLocaciones['Prorateable']['10.5'] != 0) { ?>
                    <tr>
                        <td><?php echo "10.5%" ?></td>
                        <td><?php echo number_format($TotalLocaciones['Neto']['10.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['10.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Prorateable']['10.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Prorateable']['10.5'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['10.5'] + $TotalLocaciones['Prorateable']['10.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['10.5'] + ($TotalLocaciones['Prorateable']['10.5'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                        <?php
                    }
                    if($TotalLocaciones['Neto']['21']+$TotalLocaciones['Directo']['21']+$TotalLocaciones['Prorateable']['21'] != 0) { ?>
                    <tr>
                        <td><?php echo "21%" ?></td>
                        <td><?php echo number_format($TotalLocaciones['Neto']['21'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['21'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Prorateable']['21'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Prorateable']['21'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['21'] + $TotalLocaciones['Prorateable']['21'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['21'] + ($TotalLocaciones['Prorateable']['21'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                        <?php
                    }
                    if($TotalLocaciones['Neto']['27']+$TotalLocaciones['Directo']['27']+$TotalLocaciones['Prorateable']['27'] != 0) { ?>
                    <tr>
                        <td><?php echo "27%" ?></td>
                        <td><?php echo number_format($TotalLocaciones['Neto']['27'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['27'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Prorateable']['27'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Prorateable']['27'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['27'] + $TotalLocaciones['Prorateable']['27'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalLocaciones['Directo']['27'] + ($TotalLocaciones['Prorateable']['27'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                        <?php
                    }
                    if($TotalLocaciones['Neto']['total']+$TotalLocaciones['Directo']['total']+$TotalLocaciones['Prorateable']['total'] != 0) { ?>
                        <tr>
                            <td>Totales</td>
                            <td><?php echo number_format($TotalLocaciones['Neto']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalLocaciones['Directo']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalLocaciones['Prorateable']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalLocaciones['Prorateable']['total'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalLocaciones['Directo']['total'] + $TotalLocaciones['Prorateable']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalLocaciones['Directo']['total'] + ($TotalLocaciones['Prorateable']['total'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                }
                $TotalNoComputable += ($TotalLocaciones['Prorateable']['total'])-($TotalLocaciones['Prorateable']['total'] * $coeficienteapropiacion);
                $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalLocaciones['Directo']['total'] + ($TotalLocaciones['Prorateable']['total']));
                $TotalCreditoFiscalComputable_SumaTotal = $TotalCreditoFiscalComputable_SumaTotal + ($TotalLocaciones['Directo']['total'] + ($TotalLocaciones['Prorateable']['total'] * $coeficienteapropiacion));
                if($TotalPresServ['mostrar']) {
                    ?>
                    <tr>
                        <td colspan="7" style='background-color:#87cfeb'>
                            > OPERACION: Prest. Servicios
                        </td>
                    </tr>
                    <tr style='background-color:#f0f0f0'>
                        <td>Alicuota</td>
                        <td>Monto Neto</td>
                        <td>Directo</td>
                        <td>Prorrateable</td>
                        <td>Computable</td>
                        <td>Facturado</td>
                        <td>Computable Total</td>
                    </tr>
                    <?php
                    if($TotalPresServ['Neto']['0']+$TotalPresServ['Directo']['0']+$TotalPresServ['Prorateable']['0'] != 0) { ?>
                        <tr>
                            <td><?php echo "0%" ?></td>
                            <td><?php echo number_format($TotalPresServ['Neto']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Prorateable']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Prorateable']['0'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['0'] + $TotalPresServ['Prorateable']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['0'] + ($TotalPresServ['Prorateable']['0'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalPresServ['Neto']['2.5']+$TotalPresServ['Directo']['2.5']+$TotalPresServ['Prorateable']['2.5'] != 0) { ?>
                        <tr>
                            <td><?php echo "2.5%" ?></td>
                            <td><?php echo number_format($TotalPresServ['Neto']['2.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['2.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Prorateable']['2.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Prorateable']['2.5'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['2.5'] + $TotalPresServ['Prorateable']['2.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['2.5'] + ($TotalPresServ['Prorateable']['2.5'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalPresServ['Neto']['50']+$TotalPresServ['Directo']['50']+$TotalPresServ['Prorateable']['50'] != 0) { ?>
                        <tr>
                            <td><?php echo "5.0%" ?></td>
                            <td><?php echo number_format($TotalPresServ['Neto']['50'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['50'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Prorateable']['50'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Prorateable']['50'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['50'] + $TotalPresServ['Prorateable']['50'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['50'] + ($TotalPresServ['Prorateable']['50'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalPresServ['Neto']['10.5']+$TotalPresServ['Directo']['10.5']+$TotalPresServ['Prorateable']['10.5'] != 0) { ?>
                        <tr>
                            <td><?php echo "10.5%" ?></td>
                            <td><?php echo number_format($TotalPresServ['Neto']['10.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['10.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Prorateable']['10.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Prorateable']['10.5'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['10.5'] + $TotalPresServ['Prorateable']['10.5'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['10.5'] + ($TotalPresServ['Prorateable']['10.5'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalPresServ['Neto']['21']+$TotalPresServ['Directo']['21']+$TotalPresServ['Prorateable']['21'] != 0) { ?>
                        <tr>
                            <td><?php echo "21%" ?></td>
                            <td><?php echo number_format($TotalPresServ['Neto']['21'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['21'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Prorateable']['21'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Prorateable']['21'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['21'] + $TotalPresServ['Prorateable']['21'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['21'] + ($TotalPresServ['Prorateable']['21'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalPresServ['Neto']['27']+$TotalPresServ['Directo']['27']+$TotalPresServ['Prorateable']['27'] != 0) { ?>
                        <tr>
                            <td><?php echo "27%" ?></td>
                            <td><?php echo number_format($TotalPresServ['Neto']['27'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['27'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Prorateable']['27'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Prorateable']['27'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['27'] + $TotalPresServ['Prorateable']['27'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['27'] + ($TotalPresServ['Prorateable']['27'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalPresServ['Neto']['total']+$TotalPresServ['Directo']['total']+$TotalPresServ['Prorateable']['total'] != 0) { ?>
                        <tr>
                            <td>Totales</td>
                            <td><?php echo number_format($TotalPresServ['Neto']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Prorateable']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Prorateable']['total'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['total'] + $TotalPresServ['Prorateable']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalPresServ['Directo']['total'] + ($TotalPresServ['Prorateable']['total'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                }
                $TotalNoComputable += ($TotalPresServ['Prorateable']['total'])-($TotalPresServ['Prorateable']['total'] * $coeficienteapropiacion);
                $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalPresServ['Directo']['total'] + ($TotalPresServ['Prorateable']['total']));
                $TotalCreditoFiscalComputable_SumaTotal = $TotalCreditoFiscalComputable_SumaTotal + ($TotalPresServ['Directo']['total'] + ($TotalPresServ['Prorateable']['total'] * $coeficienteapropiacion));
                if($TotalBsUso['mostrar']) {
                    ?>
                    <tr>
                        <td colspan="7" style='background-color:#87cfeb'>
                            > OPERACION: Bs. Uso
                        </td>
                    </tr>
                    <tr style='background-color:#f0f0f0'>
                        <td>Alicuota</td>
                        <td>Monto Neto</td>
                        <td>Directo</td>
                        <td>Prorrateable</td>
                        <td>Computable</td>
                        <td>Facturado</td>
                        <td>Computable Total</td>
                    </tr>
                    <?php
                    if($TotalBsUso['Neto']['0']+$TotalBsUso['Directo']['0']+$TotalBsUso['Prorateable']['0'] != 0) { ?>
                        <tr>
                            <td><?php echo "0%" ?></td>
                            <td><?php echo number_format($TotalBsUso['Neto']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBsUso['Directo']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBsUso['Prorateable']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBsUso['Prorateable']['0'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBsUso['Directo']['0'] + $TotalBsUso['Prorateable']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBsUso['Directo']['0'] + ($TotalBsUso['Prorateable']['0'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalBsUso['Neto']['2.5']+$TotalBsUso['Directo']['2.5']+$TotalBsUso['Prorateable']['2.5'] != 0) { ?>
                    <tr>
                        <td><?php echo "2.5%" ?></td>
                        <td><?php echo number_format($TotalBsUso['Neto']['2.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['2.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Prorateable']['2.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Prorateable']['2.5'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['2.5'] + $TotalBsUso['Prorateable']['2.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['2.5'] + ($TotalBsUso['Prorateable']['2.5'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                        <?php
                    }
                    if($TotalBsUso['Neto']['50']+$TotalBsUso['Directo']['50']+$TotalBsUso['Prorateable']['50'] != 0) { ?>
                    <tr>
                        <td><?php echo "5.0%" ?></td>
                        <td><?php echo number_format($TotalBsUso['Neto']['27'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['50'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Prorateable']['50'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Prorateable']['50'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['50'] + $TotalBsUso['Prorateable']['50'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['50'] + ($TotalBsUso['Prorateable']['50'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                        <?php
                    }
                    if($TotalBsUso['Neto']['10.5']+$TotalBsUso['Directo']['10.5']+$TotalBsUso['Prorateable']['10.5'] != 0) { ?>
                    <tr>
                        <td><?php echo "10.5%" ?></td>
                        <td><?php echo number_format($TotalBsUso['Neto']['10.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['10.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Prorateable']['10.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Prorateable']['10.5'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['10.5'] + $TotalBsUso['Prorateable']['10.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['10.5'] + ($TotalBsUso['Prorateable']['10.5'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                        <?php
                    }
                    if($TotalBsUso['Neto']['21']+$TotalBsUso['Directo']['21']+$TotalBsUso['Prorateable']['21'] != 0) { ?>
                    <tr>
                        <td><?php echo "21%" ?></td>
                        <td><?php echo number_format($TotalBsUso['Neto']['21'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['21'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Prorateable']['21'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Prorateable']['21'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['21'] + $TotalBsUso['Prorateable']['21'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['21'] + ($TotalBsUso['Prorateable']['21'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                        <?php
                    }
                    if($TotalBsUso['Neto']['27']+$TotalBsUso['Directo']['27']+$TotalBsUso['Prorateable']['27'] != 0) { ?>
                    <tr>
                        <td><?php echo "27%" ?></td>
                        <td><?php echo number_format($TotalBsUso['Neto']['27'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['27'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Prorateable']['27'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Prorateable']['27'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['27'] + $TotalBsUso['Prorateable']['27'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalBsUso['Directo']['27'] + ($TotalBsUso['Prorateable']['27'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                        <?php
                    }
                    if($TotalBsUso['Neto']['total']+$TotalBsUso['Directo']['total']+$TotalBsUso['Prorateable']['total'] != 0) { ?>
                        <tr>
                            <td>Totales</td>
                            <td><?php echo number_format($TotalBsUso['Neto']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBsUso['Directo']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBsUso['Prorateable']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBsUso['Prorateable']['total'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBsUso['Directo']['total'] + $TotalBsUso['Prorateable']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalBsUso['Directo']['total'] + ($TotalBsUso['Prorateable']['total'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                }
                $TotalNoComputable += ($TotalBsUso['Prorateable']['total'])-($TotalBsUso['Prorateable']['total'] * $coeficienteapropiacion);
                $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalBsUso['Directo']['total'] + ($TotalBsUso['Prorateable']['total']));
                $TotalCreditoFiscalComputable_SumaTotal = $TotalCreditoFiscalComputable_SumaTotal + ($TotalBsUso['Directo']['total'] + ($TotalBsUso['Prorateable']['total'] * $coeficienteapropiacion));
                if($TotalOtrosConceptos['mostrar']) {
                    ?>
                    <tr>
                        <td colspan="7" style='background-color:#87cfeb' title="<?php echo $CreditoFiscalOtrosConceptosTitle;?>">
                            > OPERACION: Otros Conceptos
                        </td>
                    </tr>
                    <tr style='background-color:#f0f0f0'>
                        <td>Alicuota</td>
                        <td>Monto Neto</td>
                        <td>Directo</td>
                        <td>Prorrateable</td>
                        <td>Computable</td>
                        <td>Facturado</td>
                        <td>Computable Total</td>
                    </tr>
                    <?php
                    if($TotalOtrosConceptos['Neto']['0']+$TotalOtrosConceptos['Directo']['0']+$TotalOtrosConceptos['Prorateable']['0'] != 0) { ?>
                        <tr>
                            <td><?php echo "0%" ?></td>
                            <td><?php echo number_format($TotalOtrosConceptos['Neto']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalOtrosConceptos['Directo']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalOtrosConceptos['Prorateable']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalOtrosConceptos['Prorateable']['0'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalOtrosConceptos['Directo']['0'] + $TotalOtrosConceptos['Prorateable']['0'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalOtrosConceptos['Directo']['0'] + ($TotalOtrosConceptos['Prorateable']['0'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                        </tr>
                        <?php
                    }
                    if($TotalOtrosConceptos['Neto']['2.5']+$TotalOtrosConceptos['Directo']['2.5']+$TotalOtrosConceptos['Prorateable']['2.5'] != 0) { ?>
                    <tr>
                        <td><?php echo "2.5%" ?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Neto']['2.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['2.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Prorateable']['2.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Prorateable']['2.5'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['2.5'] + $TotalOtrosConceptos['Prorateable']['2.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['2.5'] + ($TotalOtrosConceptos['Prorateable']['2.5'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                     <?php
                    }
                    if($TotalOtrosConceptos['Neto']['50']+$TotalOtrosConceptos['Directo']['50']+$TotalOtrosConceptos['Prorateable']['50'] != 0) { ?>
                    <tr>
                        <td><?php echo "5.0%" ?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Neto']['50'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['50'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Prorateable']['50'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Prorateable']['50'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['50'] + $TotalOtrosConceptos['Prorateable']['50'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['50'] + ($TotalOtrosConceptos['Prorateable']['50'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                     <?php
                    }
                    if($TotalOtrosConceptos['Neto']['10.5']+$TotalOtrosConceptos['Directo']['10.5']+$TotalOtrosConceptos['Prorateable']['10.5'] != 0) { ?>
                    <tr>
                        <td><?php echo "10.5%" ?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Neto']['10.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['10.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Prorateable']['10.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Prorateable']['10.5'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['10.5'] + $TotalOtrosConceptos['Prorateable']['10.5'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['10.5'] + ($TotalOtrosConceptos['Prorateable']['10.5'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                     <?php
                    }
                    if($TotalOtrosConceptos['Neto']['21']+$TotalOtrosConceptos['Directo']['21']+$TotalOtrosConceptos['Prorateable']['21'] != 0) { ?>
                    <tr>
                        <td><?php echo "21%" ?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Neto']['21'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['21'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Prorateable']['21'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Prorateable']['21'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['21'] + $TotalOtrosConceptos['Prorateable']['21'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['21'] + ($TotalOtrosConceptos['Prorateable']['21'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                     <?php
                    }
                    if($TotalOtrosConceptos['Neto']['27']+$TotalOtrosConceptos['Directo']['27']+$TotalOtrosConceptos['Prorateable']['27'] != 0) { ?>
                    <tr>
                        <td><?php echo "27%" ?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Neto']['27'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['27'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Prorateable']['27'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Prorateable']['27'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['27'] + $TotalOtrosConceptos['Prorateable']['27'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['27'] + ($TotalOtrosConceptos['Prorateable']['27'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                     <?php
                    }
                    if($TotalOtrosConceptos['Neto']['total']+$TotalOtrosConceptos['Directo']['total']+$TotalOtrosConceptos['Prorateable']['total'] != 0) { ?>
                    <tr>
                        <td>Totales</td>
                        <td><?php echo number_format($TotalOtrosConceptos['Neto']['total'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['total'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Prorateable']['total'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Prorateable']['total'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['total'] + $TotalOtrosConceptos['Prorateable']['total'] , 2, ",", ".")?></td>
                        <td><?php echo number_format($TotalOtrosConceptos['Directo']['total'] + ($TotalOtrosConceptos['Prorateable']['total'] * $coeficienteapropiacion) , 2, ",", ".")?></td>
                    </tr>
                    <?php
                    }
                }
                $TotalNoComputable += ($TotalOtrosConceptos['Prorateable']['total'])-($TotalOtrosConceptos['Prorateable']['total'] * $coeficienteapropiacion);
                $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalOtrosConceptos['Directo']['total'] + ($TotalOtrosConceptos['Prorateable']['total']));
                $TotalCreditoFiscalComputable_SumaTotal = $TotalCreditoFiscalComputable_SumaTotal + ($TotalOtrosConceptos['Directo']['total'] + ($TotalOtrosConceptos['Prorateable']['total'] * $coeficienteapropiacion));
                if($TotalDcto814['mostrar']) {
                    ?>
                    <tr>
                        <td colspan="7" style='background-color:#87cfeb'>
                            > OPERACION: Dcto. 814
                        </td>
                    </tr>
                    <tr style='background-color:#f0f0f0'>
                        <td>Directo</td>
                        <td>Prorrateable</td>
                        <td>Computable</td>
                        <td>Facturado</td>
                        <td colspan="2">Computable Total</td>
                    </tr>
                    <?php
                    $TotalDcto814['Directo']['total'] = $TotalDcto814_Directo;
                    if ($TotalDcto814['Neto']['total'] + $TotalDcto814['Directo']['total'] + $TotalDcto814['Prorateable']['total'] != 0) { ?>
                        <tr>
                            <td><?php
                                echo number_format($TotalDcto814['Directo']['total'] , 2, ",", ".");
                                echo $this->Form->input('totaldecreto814',
                                    [
                                        'type'=>'hidden',
                                        'value'=>number_format($TotalDcto814_Directo, 2, ".", "")
                                    ]
                                );
                                ?></td>
                            <td><?php echo number_format($TotalDcto814['Prorateable']['total'] , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalDcto814['Prorateable']['total'] * $coeficienteapropiacion , 2, ",", ".")?></td>
                            <td><?php echo number_format($TotalDcto814['Directo']['total'] + $TotalDcto814['Prorateable']['total'] , 2, ",", ".")?></td>
                            <td colspan="2"><?php echo number_format($TotalDcto814['Directo']['total'] + ($TotalDcto814['Prorateable']['total'] * $coeficienteapropiacion), 2, ",", ".") ?></td>
                        </tr>
                        <?php
                    }
                }
                $TotalNoComputable += ($TotalDcto814['Prorateable']['total'])-($TotalDcto814['Prorateable']['total'] * $coeficienteapropiacion);
                echo $this->Form->input('totalnocomputable',
                    [
                        'type'=>'hidden',
                        'value'=>number_format($TotalNoComputable, 2, ".", "")
                    ]
                );
                //el decreto 814 es indirecto por defecto por lo tanto deberia tener un campo donde podamos cargar el monto prorrateable
                //pero no lo tenemos asi que vamos a tomar por defecto como si fuese directo (todo corregir prorrateo 814)
                $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalDcto814['Directo']['total'] + ($TotalDcto814['Prorateable']['total']));
                $TotalCreditoFiscalComputable_SumaTotal = $TotalCreditoFiscalComputable_SumaTotal + ($TotalDcto814['Directo']['total'] + ($TotalDcto814['Prorateable']['total'] * $coeficienteapropiacion)); ?>
    <!--            <tr>-->
    <!--                <td colspan="7" style='background-color:#87cfeb'>-->
    <!--                    > OPERACION: Contrib. Seg. Social (Dto 814/01)-->
    <!--                </td>-->
    <!--            </tr>-->
    <!--            <tr style='background-color:#f0f0f0'>-->
    <!--                <td>Crédito Fiscal Facturado</td>-->
    <!--                <td>Crédito Fiscal Computable</td>-->
    <!--                <td></td>-->
    <!--                <td></td>-->
    <!--                <td></td>-->
    <!--                <td></td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <td>Ver Calculo</td>-->
    <!--                <td>Ver Calculo</td>-->
    <!--                <td></td>-->
    <!--                <td></td>-->
    <!--                <td></td>-->
    <!--                <td></td>-->
    <!--            </tr>-->
                <?php
                if($TotalOperacionesExentasYNoGravadas['mostrar']) {
                ?>
                <tr>
                    <td colspan="7" style='background-color:#87cfeb'>
                        > OPERACION: Exentas y No Gravadas
                    </td>
                </tr>
                <tr style='background-color:#f0f0f0'>
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><?php
                        echo number_format($TotalOperacionesExentasYNoGravadas['Neto']['total'] , 2, ",", ".");
                        ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php
                }
                ?>

            <!----------- Fin Credito Fiscal ---------->
            </table>

            <div style="width:100%; height: 10px"></div>

            <table id='divTablaCompras_RestDebFiscal'  onclick="MostrarTabla(this,'compras');" style="margin-bottom:0; cursor: pointer">
            <!------------- Credito Fiscal ---------->
            <tr>
                <td colspan="5" style='background-color:#76b5cd'>
                    <b>
                    TIPO DEBITO: Restitucion debito fiscal
                    </b>
                </td>
            </tr>
            </table>
            <table id='divTablaComprasCredito_RestDebFiscal'  style="">
            <!---------- TIPO DEBITO: Restitucion debito fiscal ---------->
            <tr>
                <td colspan="5" style='background-color:#87cfeb'>
                    > OPERACION: Responsable Inscripto
                </td>
            </tr>
            <tr style='background-color:#f0f0f0'>
                <td>Alicuota</td>
                <td>Monto Neto Grabado</td>
                <td>Debito Fiscal</td>
                <td>Debito Fiscal Facturado</td>
                <td>Debito Fiscal - Operaciones dación en pago decreto 1145/09</td>
            </tr>
            <tr>
            <?php
                $Alicuota2_5 = false;
                $TotalAlicuota2_5 = 0;
                $Alicuota5_0 = false;
                $TotalAlicuota5_0 = 0;
                $Alicuota10_5 = false;
                $TotalAlicuota10_5 = 0;
                $Alicuota21_0 = false;
                $TotalAlicuota21_0 = 0;
                $Alicuota27_0 = false;
                $TotalAlicuota27_0 = 0;

                foreach ($ventas as $venta) {
                    if ($venta['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal') {
                        if ($venta['Venta']['condicioniva'] == 'responsableinscripto') {
                            if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50') {
                                $Alicuota2_5 = true;
                                $TotalAlicuota2_5 = $TotalAlicuota2_5 + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00') {
                                $Alicuota5_0 = true;
                                $TotalAlicuota5_0 = $TotalAlicuota5_0 + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50') {
                                $Alicuota10_5 = true;
                                $TotalAlicuota10_5 = $TotalAlicuota10_5 + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00') {
                                $Alicuota21_0 = true;
                                $TotalAlicuota21_0 = $TotalAlicuota21_0 + +$venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00') {
                                $Alicuota27_0 = true;
                                $TotalAlicuota27_0 = $TotalAlicuota27_0 + +$venta['Venta']['neto'];
                            }
                        }
                    }
                }
            ?>
            <?php
                if($Alicuota2_5)
                {
                    echo '<tr>
                         <td>2.5</td>
                          <td>'.number_format($TotalAlicuota2_5, 2, ",", ".").'</td>
                          <td>'.number_format($TotalAlicuota2_5 * 0.025, 2, ",", ".").'</td>
                          <td>'.number_format($TotalAlicuota2_5 * 0.025, 2, ",", ".").'</td>
                          <td>0</td>
                          </tr>
                    ';
                }
                if($Alicuota5_0)
                {
                    echo '<tr>
                          <td>5.0</td>
                          <td>'.number_format($TotalAlicuota5_0, 2, ",", ".").'</td>
                          <td>'.number_format($TotalAlicuota5_0 * 0.05, 2, ",", ".").'</td>
                          <td>'.number_format($TotalAlicuota5_0 * 0.05, 2, ",", ".").'</td>
                          <td>0</td>
                          </tr>
                    ';
                }
                if($Alicuota10_5)
                {
                    echo '<tr>
                          <td>10.5</td>
                          <td>'.number_format($TotalAlicuota10_5, 2, ",", ".").'</td>
                          <td>'.number_format($TotalAlicuota10_5 * 0.105, 2, ",", ".").'</td>
                          <td>'.number_format($TotalAlicuota10_5 * 0.105, 2, ",", ".").'</td>
                          <td>0</td>
                          </tr>
                    ';
                }
                if($Alicuota21_0)
                {
                    echo '<tr>
                          <td>21.0</td>
                          <td>'.number_format($TotalAlicuota21_0, 2, ",", ".").'</td>
                          <td>'.number_format($TotalAlicuota21_0 * 0.21, 2, ",", ".").'</td>
                          <td>'.number_format($TotalAlicuota21_0 * 0.21, 2, ",", ".").'</td>
                          <td>0</td>
                          </tr>
                    ';
                }
                if($Alicuota27_0)
                {
                    echo '<tr>
                          <td>27.0</td>
                          <td>'.number_format($TotalAlicuota27_0, 2, ",", ".").'</td>
                          <td>'.number_format($TotalAlicuota27_0 * 0.27, 2, ",", ".").'</td>
                          <td>'.number_format($TotalAlicuota27_0 * 0.27, 2, ",", ".").'</td>
                          <td>0</td>
                          </tr>
                    ';
                }
                if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
                {
                    $TotalNeto = $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                    $TotalDebitoFiscal = ($TotalAlicuota2_5 * 0.025) + ($TotalAlicuota5_0 * 0.05) + ($TotalAlicuota10_5 * 0.105) + ($TotalAlicuota21_0 * 0.21) + ($TotalAlicuota27_0 * 0.27);
                    echo '<tr>
                          <td>Totales</td>
                          <td>'.number_format($TotalNeto, 2, ",", ".").'</td>
                          <td>'.number_format($TotalDebitoFiscal, 2, ",", ".").'</td>
                          <td>'.number_format($TotalDebitoFiscal, 2, ",", ".").'</td>
                          <td>0</td>
                        </tr>
                    ';
                    $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + $TotalDebitoFiscal;
                    $TotalCreditoFiscalComputable_SumaTotal = $TotalCreditoFiscalComputable_SumaTotal + $TotalDebitoFiscal;
                }
            ?>
            </tr>
            <tr>
                <td colspan="5" style='background-color:#87cfeb'>
                    > OPERACION: Monotributista
                </td>
            </tr>
            <tr style='background-color:#f0f0f0'>
                <td>Alicuota</td>
                <td>Monto total facturado</td>
                <td>Debito Fiscal</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
            <?php
                $Alicuota2_5 = false;
                $TotalAlicuota2_5 = 0;
                $Alicuota5_0 = false;
                $TotalAlicuota5_0 = 0;
                $Alicuota10_5 = false;
                $TotalAlicuota10_5 = 0;
                $Alicuota21_0 = false;
                $TotalAlicuota21_0 = 0;
                $Alicuota27_0 = false;
                $TotalAlicuota27_0 = 0;

                foreach ($ventas as $venta) {
                    if ($venta['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal') {
                        if ($venta['Venta']['condicioniva'] == 'monotributista') {
                            if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50') {
                                $Alicuota2_5 = true;
                                $TotalAlicuota2_5 += $venta['Venta']['total'];
                            }
                            if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00') {
                                $Alicuota5_0 = true;
                                $TotalAlicuota5_0 += $venta['Venta']['total'];
                            }
                            if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50') {
                                $Alicuota10_5 = true;
                                $TotalAlicuota10_5 += $venta['Venta']['total'];
                            }
                            if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00') {
                                $Alicuota21_0 = true;
                                $TotalAlicuota21_0 += $venta['Venta']['total'];
                            }
                            if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00') {
                                $Alicuota27_0 = true;
                                $TotalAlicuota27_0 += $venta['Venta']['total'];
                            }
                        }
                    }
                }
            ?>
            <?php
                if($Alicuota2_5)
                {
                    echo '
                    <tr>
                        <td>2.5</td>
                        <td>'.number_format($TotalAlicuota2_5 , 2, ",", ".").'</td>
                        <td>'.number_format(($TotalAlicuota2_5/1.025) , 2, ",", ".").'</td>
                        <td></td>
                        <td></td>
                    </tr>
                    ';
                }
                if($Alicuota5_0)
                {
                    echo '<tr>
                          <td>5.0</td>
                          <td>'.number_format($TotalAlicuota5_0 , 2, ",", ".").'</td>
                          <td>'.number_format(($TotalAlicuota5_0 /1.05)* 0.05, 2, ",", ".").'</td>
                          <td></td>
                          <td></td>
                          </tr>
                    ';
                }
                if($Alicuota10_5)
                {
                    echo '<tr>
                          <td>10.5</td>
                          <td>'.number_format($TotalAlicuota10_5, 2, ",", ".").'</td>
                          <td>'.number_format(($TotalAlicuota10_5/1.105) * 0.105, 2, ",", ".").'</td>
                          <td></td>
                          <td></td>
                          </tr>
                    ';
                }
                if($Alicuota21_0)
                {
                    echo '<tr>
                          <td>21.0</td>
                          <td>'.number_format($TotalAlicuota21_0 , 2, ",", ".").'</td>
                          <td>'.number_format(($TotalAlicuota21_0/1.21) * 0.21, 2, ",", ".").'</td>
                          <td></td>
                          <td></td>
                          </tr>
                    ';
                }
                if($Alicuota27_0)
                {
                    echo '<tr>
                          <td>27.0</td>
                          <td>'.number_format($TotalAlicuota27_0 , 2, ",", ".").'</td>
                          <td>'.number_format(($TotalAlicuota27_0/1.27) * 0.27, 2, ",", ".").'</td>
                          <td></td>
                          <td></td>
                          </tr>
                    ';
                }
                if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
                {
                    $TotalNeto = $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                    $TotalDebitoFiscal = (($TotalAlicuota2_5/1.025) * 0.025) + (($TotalAlicuota5_0 /1.05)* 0.05) + (($TotalAlicuota10_5/1.105) * 0.105) + (($TotalAlicuota21_0/1.21) * 0.21) + (($TotalAlicuota27_0/1.27) * 0.27);
                    echo '<tr>
                          <td>Totales</td>
                          <td>'.number_format($TotalNeto, 2, ",", ".").'</td>
                          <td>'.number_format($TotalDebitoFiscal, 2, ",", ".").'</td>
                          <td></td>
                          <td></td>
                        </tr>
                    ';
                    $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + $TotalDebitoFiscal;
                    $TotalCreditoFiscalComputable_SumaTotal = $TotalCreditoFiscalComputable_SumaTotal + $TotalDebitoFiscal;
                }
            ?>
            </tr>
            <tr>
                <td colspan="5" style='background-color:#87cfeb'>
                    > OPERACION: Cons. F, Exent. y No Alcan.
                </td>
            </tr>
            <tr style='background-color:#f0f0f0'>
                <td>Alicuota</td>
                <td>Monto total facturado</td>
                <td>Debito Fiscal</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
            <?php
                $Alicuota2_5 = false;
                $TotalAlicuota2_5 = 0;
                $Alicuota5_0 = false;
                $TotalAlicuota5_0 = 0;
                $Alicuota10_5 = false;
                $TotalAlicuota10_5 = 0;
                $Alicuota21_0 = false;
                $TotalAlicuota21_0 = 0;
                $Alicuota27_0 = false;
                $TotalAlicuota27_0 = 0;

                foreach ($ventas as $venta) {
                    if ($venta['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal') {
                        if ($venta['Venta']['condicioniva'] == 'consf/exento/noalcanza') {
                            if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50') {
                                $Alicuota2_5 = true;
                                $TotalAlicuota2_5 = $TotalAlicuota2_5 + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00') {
                                $Alicuota5_0 = true;
                                $TotalAlicuota5_0 = $TotalAlicuota5_0 + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50') {
                                $Alicuota10_5 = true;
                                $TotalAlicuota10_5 = $TotalAlicuota10_5 + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00') {
                                $Alicuota21_0 = true;
                                $TotalAlicuota21_0 = $TotalAlicuota21_0 + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00') {
                                $Alicuota27_0 = true;
                                $TotalAlicuota27_0 = $TotalAlicuota27_0 + $venta['Venta']['neto'];
                            }
                        }
                    }

                }
            ?>
            <?php
                if($Alicuota2_5)
                {
                    echo '<tr>
                         <td>2.5</td>
                          <td>'.number_format($TotalAlicuota2_5 * 1.025, 2, ",", ".").'</td>
                          <td>'.number_format(($TotalAlicuota2_5) * 0.025, 2, ",", ".").'</td>
                          <td></td>
                          <td></td>
                          </tr>
                    ';
                }
                if($Alicuota5_0)
                {
                    echo '<tr>
                          <td>5.0</td>
                          <td>'.number_format($TotalAlicuota5_0 * 1.05, 2, ",", ".").'</td>
                          <td>'.number_format(($TotalAlicuota5_0 )* 0.05, 2, ",", ".").'</td>
                          <td></td>
                          <td></td>
                          </tr>
                    ';
                }
                if($Alicuota10_5)
                {
                    echo '<tr>
                          <td>10.5</td>
                          <td>'.number_format($TotalAlicuota10_5 * 1.105, 2, ",", ".").'</td>
                          <td>'.number_format($TotalAlicuota10_5 * 0.105, 2, ",", ".").'</td>
                          <td></td>
                          <td></td>
                          </tr>
                    ';
                }
                if($Alicuota21_0)
                {
                    echo '<tr>
                          <td>21.0</td>
                          <td>'.number_format($TotalAlicuota21_0 * 1.21, 2, ",", ".").'</td>
                          <td>'.number_format($TotalAlicuota21_0 * 0.21, 2, ",", ".").'</td>
                          <td></td>
                          <td></td>
                          </tr>
                    ';
                }
                if($Alicuota27_0)
                {
                    echo '<tr>
                          <td>27.0</td>
                          <td>'.number_format($TotalAlicuota27_0 * 1.27, 2, ",", ".").'</td>
                          <td>'.number_format($TotalAlicuota27_0 * 0.27, 2, ",", ".").'</td>
                          <td></td>
                          <td></td>
                          </tr>
                    ';
                }
                if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
                {
                    $TotalNeto = $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                    $TotalDebitoFiscal = (($TotalAlicuota2_5) * 0.025) + (($TotalAlicuota5_0 )* 0.05) + ($TotalAlicuota10_5 * 0.105) + ($TotalAlicuota21_0 * 0.21) + ($TotalAlicuota27_0 * 0.27);
                    echo '<tr>
                          <td>Totales</td>
                          <td>'.number_format($TotalNeto, 2, ",", ".").'</td>
                          <td>'.number_format($TotalDebitoFiscal, 2, ",", ".").'</td>
                          <td></td>
                          <td></td>
                        </tr>
                    ';
                    $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + $TotalDebitoFiscal;
                    $TotalCreditoFiscalComputable_SumaTotal = $TotalCreditoFiscalComputable_SumaTotal + $TotalDebitoFiscal;
                }
            ?>
            </tr>
            <!---------- FIN TIPO DEBITO: Restitucion debito fiscal ---------->
            </table>

        </div> <!--COMPRAS-->
        <?php //endforeach; ?>
        <div id="divContenedorLiquidacion" style="margin-top:10px">
            <div>
                <?php
                $TotalSaldoTecnicoAFavorRespPeriodoAnterior=0;
                $TotalSaldoTecnicoAFavorRespPeriodo=0;
                $TotalSaldoTecnicoAPagarPeriodo=0;
                $TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnterior=0;
                $TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnteriorNetousos=0;
                $TotalSaldoLibreDisponibilidadAFavorRespPeriodo=0;
                $TotalPagosACuenta = 0;
                $PagosACuenta = 0;
                $AjusteAnualAFavorResponsable = 0;
                $AjusteAnualAFavorAFIP = 0;
                $Diferimiento518 = 0;
                $BonosFiscales = 0;
                $eventosImpuestoId = -1;
                foreach ($cliente['Impcli'] as $key => $impcli) {
                    foreach ($impcli['Eventosimpuesto'] as $key => $eventosimpuesto) {
                        /*
                        'Saldo Tecnico' => 'Saldo Tecnico',
                        'Saldo de Libre Disponibilidad'=>'Saldo de Libre Disponibilidad' ,
                        */
                        if($eventosimpuesto['item']=='Saldo Tecnico'&&$eventosimpuesto['periodo']==$periodoPrev){//aca controlamos que el ITEM a sumar sea el correspondiente a Saldos Tecnicos
                            $TotalSaldoTecnicoAFavorRespPeriodoAnterior += $eventosimpuesto['monc'];
                        }else if($eventosimpuesto['periodo']==$periodo){
                            $eventosImpuestoId = $eventosimpuesto['id'];
                        }
                    }
                }
                $sldID=0;
                foreach ($saldosLibreDisponibilidad as $sldpa) {
                    $TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnterior += $sldpa['Conceptosrestante']['monto'];
                    $TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnteriorNetousos += $sldpa['Conceptosrestante']['montoretenido'];
                    $sldID = $sldpa['Conceptosrestante']['id'];
                    foreach ($sldpa['Usosaldo'] as $usosaldoDeIVAPeriodoAnterior){
                        if($usosaldoDeIVAPeriodoAnterior['eventosimpuesto_id']==$eventosImpuestoId){
                            $TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnterior += $usosaldoDeIVAPeriodoAnterior['importe'];
                        }
                    }
                }
                foreach ($cliente['Impcli'][0]['Conceptosrestante'] as $key => $conceptosrestante) {
                    if($conceptosrestante['conceptostipo_id']=='1'/*Saldo A Favor*/){
    //                    Esto no lo vamos a sumar por que tenemos que traer el SLD del periodo anterior no del actual
    //                    $TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnterior += $conceptosrestante['montoretenido'];
    //                    foreach ($conceptosrestante['Usosaldo'] as $usosaldo){
    //                        if($usosaldo['Eventosimpuesto']['impcli_id']==$cliente['Impcli'][0]['id']){
    //                            $TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnterior += $usosaldoDeIVAPeriodoAnterior['importe'];
    //                        }
    //                    }
                    }else if($conceptosrestante['conceptostipo_id']=='14'/*Ajuste anual a favor Responable*/){
                        $AjusteAnualAFavorResponsable += $conceptosrestante['montoretenido'];
                    }else if($conceptosrestante['conceptostipo_id']=='15'/*Ajuste anual a favor AFIP*/){
                        $AjusteAnualAFavorAFIP += $conceptosrestante['montoretenido'];
                    }else if($conceptosrestante['conceptostipo_id']=='16'/*Diferimiento 518*/){
                        $Diferimiento518 += $conceptosrestante['montoretenido'];
                    }else if($conceptosrestante['conceptostipo_id']=='17'/*Bonos Fiscales - Decreto 1145/09 y/o Decreto 852/14*/){
                        $BonosFiscales += $conceptosrestante['montoretenido'];
                    }else if($conceptosrestante['conceptostipo_id']=='12'/**Decreto 814**/){
                        //$BonosFiscales += $conceptosrestante['montoretenido'];
                    }else if($conceptosrestante['conceptostipo_id']=='10'/**Decreto 814**/){
                        $PagosACuenta += $conceptosrestante['montoretenido'];
                    }else{
                        $TotalPagosACuenta += $conceptosrestante['montoretenido'];
                    }
                    //echo $TotalPagosACuenta ."//";
                }
                $TotalPagosACuenta += $TotalPercepcionesIVA +$PagosACuenta ;
                $CreditoGeneral = 0;
                $CreditoGeneral = $TotalCreditoFiscalComputable_SumaTotal +  $TotalSaldoTecnicoAFavorRespPeriodoAnterior + $AjusteAnualAFavorResponsable ;
                $DebitoGeneral = 0;

                if($TotalDebitoFiscal_SumaTotal<$CreditoGeneral){
                    $TotalSaldoTecnicoAFavorRespPeriodo = $CreditoGeneral - $TotalDebitoFiscal_SumaTotal ;
                }else{
                    $TotalSaldoTecnicoAFavorRespPeriodo = 0;
                }
                if($TotalDebitoFiscal_SumaTotal>$CreditoGeneral){
                    $TotalSaldoTecnicoAPagarPeriodo = $TotalDebitoFiscal_SumaTotal + $AjusteAnualAFavorAFIP - $CreditoGeneral ;
                }else{
                    $TotalSaldoTecnicoAPagarPeriodo = 0;
                }
                $TotalSaldoLibreDisponibilidadAFavorRespPeriodo = $TotalPagosACuenta + $TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnterior - $TotalSaldoTecnicoAPagarPeriodo;
                $TotalSaldoImpuestoAFavorAFIP = 0;
                $TotalSaldoImpuestoAFavorAFIP = $TotalSaldoTecnicoAPagarPeriodo - $TotalPagosACuenta - $TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnterior  ;
                ?>
                <table style="margin-top: 60px;" class="toExcelTable tbl_border tblEstadoContable splitForPrint" >
                <tr style='background-color:#f0f0f0'>
                    <td>Descripción</td>
                    <td style="width:180px">Valor</td>
                </tr>
                <tr>
                    <td>Total del Débito Fiscal</td>
                    <td style="width:180px">
                        <spam id="spnTotalDeditoFiscal"><?php echo number_format($TotalDebitoFiscal_SumaTotal, 2, ",", "."); ?><spam>
                        <?php
                        echo $this->Form->input('totaldebitofiscal',
                            [
                                'type'=>'hidden',
                                'value'=>number_format($TotalDebitoFiscal_SumaTotal, 2, ".", "")
                            ]
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Total del Crédito Fiscal</td>
                    <td style="width:180px">
                        <span id="spnTotalCreditoFiscal">
                            <?php echo number_format($TotalCreditoFiscalComputable_SumaTotal, 2, ",", "."); ?>
                        </span>
                        <?php
                        echo $this->Form->input('totalcreditofiscal',
                            [
                                'type'=>'hidden',
                                'value'=>number_format($TotalCreditoFiscal_SumaTotal, 2, ".", "")
                            ]
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Ajuste Anual de Crédito Fiscal por Operaciones Exentas - A favor del Responsable</td>
                    <td style="width:180px">
                        <span id="spnCredFiscalxOpExcResp">
                            <?php echo number_format($AjusteAnualAFavorResponsable, 2, ",", "."); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Ajuste Anual de Crédito Fiscal por Operaciones Exentas - A favor de AFIP</td>
                    <td style="width:180px">
                         <span id=spnCredFiscalxOpExcAFIP">
                            <?php echo number_format($AjusteAnualAFavorAFIP, 2, ",", "."); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Saldo Técnico a Favor del Responsable del Periodo anterior</td>
                    <td style="width:180px">
                        <span id="spnSaldoAFavorPeriodoAnt">
                            <?php echo number_format($TotalSaldoTecnicoAFavorRespPeriodoAnterior, 2, ",", "."); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Saldo Técnico a Favor del Responsable del Periodo</td>
                    <td style="width:180px">
                        <span id="spnTotalSaldoTecnicoAFavorResp">
                            <?php echo number_format($TotalSaldoTecnicoAFavorRespPeriodo, 2, ",", ".");?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Subtotal Saldo Técnico a favor de la AFIP del Periodo</td>
                    <td style="width:180px">
                        <span id="spnTotalSaldoTecnicoAFavorAFIP">
                            <?php echo number_format($TotalSaldoTecnicoAPagarPeriodo, 2, ",", ".")?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Diferimiento F. 518</td>
                    <td style="width:180px">
                        <span id="spnDiferimientoF518">
                            <?php echo number_format($Diferimiento518, 2, ",", ".")?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Bonos Fiscales - Decreto 1145/09 y/o Decreto 852/14</td>
                    <td style="width:180px">
                         <span id="spnBonosFiscales">
                            <?php echo number_format($BonosFiscales, 2, ",", ".")?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Saldo Técnico a Favor de la AFIP del Periodo</td>
                    <td style="width:180px">
                        <span id="spnSaldoTecnicoAFavorAFIPPeriodo">
                            <?php echo number_format($TotalSaldoTecnicoAPagarPeriodo, 2, ",", ".");?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Saldo Tecnico a Favor del Responsable del Periodo</td>
                    <td style="width:180px">
                        <span id="spnSaldoTecnicoAfavorContribuyentePeriodo">
                            <?php echo
                            $TotalSaldoTecnicoAFavorRespPeriodo>0?number_format($TotalSaldoTecnicoAFavorRespPeriodo, 2, ",", "."):0;
                            echo $this->Form->input('saldoTecnico', array(
                                'type'=>'hidden',
                                'value'=>number_format($TotalSaldoTecnicoAFavorRespPeriodo>0?$TotalSaldoTecnicoAFavorRespPeriodo:0, 2, ".", "")));
                            ?>
                            </span>

                    </td>
                </tr>
                <tr>
                    <td>Saldo a favor de libre disponibilidad del periodo anterior neto de usos</td>
                    <td style="width:180px">
                        <span id="">
                            <?php
                            echo number_format($TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnteriorNetousos, 2, ",", ".");
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Saldo a favor de libre disponibilidad del periodo anterior despues de usos</td>
                    <td style="width:180px">
                        <span id="spnSaldoAFavorLibreDispPeriodoAnteriorNetousos">
                            <?php
                            echo $TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnterior>0?number_format($TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnterior, 2, ",", "."):0;
                            //resulta que esto es un Uso del saldo de libre disponibilidad del periodo anterior asi que aca vamos
                            //a generar un input, el cual va a activar la creacion de un formulario para registrar este uso y disminuir
                            //el saldo del periodo anterior a 0

                            ?>
                        </span>
                        <?php
                        echo $this->Form->input('usoSLD', array(
                            'type'=>'hidden',
                            'value'=>number_format($TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnterior, 2, ".", "")));
                        echo $this->Form->input('usoSLDID', array(
                            'type'=>'hidden',
                            'value'=>number_format($sldID, 2, ".", "")));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Total Retenciones, Percepciones y Pagos a cuenta computables en el periodo neto de restituciones</td>
                    <td style="width:180px">
                        <span id="spnTotalRetencionesyPercepciones"><?php echo number_format($TotalPagosACuenta, 2, ",", ".");?></span>
                        <?php
                        echo $this->Form->input('totalretenciones', array(
                            'type'=>'hidden',
                            'value'=>number_format($TotalPagosACuenta - $TotalPercepcionesIVA - $PagosACuenta, 2, ".", "")));
                        echo $this->Form->input('totalpagosacuenta', array(
                            'type'=>'hidden',
                            'value'=>number_format($PagosACuenta, 2, ".", "")));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Saldo a favor de libre disponibilidad del periodo</td>
                    <td style="width:180px">
                        <span id="spnSaldoAFavorLibreDispNetousos">
                            <?php
                            echo $TotalSaldoLibreDisponibilidadAFavorRespPeriodo>0?number_format($TotalSaldoLibreDisponibilidadAFavorRespPeriodo, 2, ",", "."):0;
                            echo $this->Form->input('saldoLD', array(
                                'type'=>'hidden',
                                'value'=>number_format($TotalSaldoLibreDisponibilidadAFavorRespPeriodo>0?$TotalSaldoLibreDisponibilidadAFavorRespPeriodo:0, 2, ".", "")));
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Saldo del Impuesto a Favor de la AFIP</td>
                    <td style="width:180px">
                        <span id="spnSaldoAFavorLibreDispNetousos">
                            <?php
                                echo $TotalSaldoImpuestoAFavorAFIP>0?number_format($TotalSaldoImpuestoAFavorAFIP, 2, ",", "."):0;
                                echo $this->Form->input('apagar', array(
                                    'type'=>'hidden',
                                    'value'=>number_format($TotalSaldoImpuestoAFavorAFIP>0?$TotalSaldoImpuestoAFavorAFIP:0, 2, ".", "")));
                            ?>
                        </span>
                    </td>
                </tr>
            </table>
            </div>

        </div>
    </div>
    <div id="divContenedorContabilidad" style="margin-top:10px;width:100%;"  class="noExl">
        <div class="index_pdt">
            <?php
            //Asiento Devengamiento del IVA
            $Asientoid=0;
            $movId=[];
            foreach ($cliente['Impcli'][0]['Asiento'] as $asientodevengamientoIVA) {
                if($asientodevengamientoIVA['tipoasiento']=="impuestos"){
                    $Asientoid=$asientodevengamientoIVA['id'];
                    foreach ($asientodevengamientoIVA['Movimiento'] as $mimovimiento){
                        $movId[$mimovimiento['Cuentascliente']['cuenta_id']] = $mimovimiento['id'];
                    }
                }
            }
            //ahora vamos a reccorer las cuentas relacionadas al IVA y las vamos a cargar en un formulario de Asiento nuevo
            echo $this->Form->create('Asiento',[
                'class'=>'formTareaCarga formAsiento',
                'controller'=>'asientos',
                'action'=>'add'
            ]);
            echo $this->Form->input('Asiento.0.id',['value'=>$Asientoid]);
            $d = new DateTime( '01-'.$periodo );
            echo $this->Form->input('Asiento.0.fecha',array(
                'class'=>'datepicker',
                'type'=>'text',
                'label'=>array(
                    'text'=>"Fecha:",
                ),
                'readonly','readonly',
                'value'=>$d->format( 't-m-Y' ),
//                'div' => false,
                'style'=> 'width:82px'
            ));
            echo $this->Form->input('Asiento.0.nombre',['readonly'=>'readonly','value'=>"Asiento Devengamiento IVA" ,'style'=>'width:250px']);
            echo $this->Form->input('Asiento.0.descripcion',['readonly'=>'readonly','value'=>"Asiento Automatico periodo: ".$periodo,'style'=>'width:250px']);
            echo $this->Form->input('Asiento.0.cliente_id',['value'=>$cliente['Cliente']['id'],'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.impcli_id',['value'=>$cliente['Impcli'][0]['id'],'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo,'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.tipoasiento',['value'=>'impuestos','type'=>'hidden'])."</br>";
            $i=0;
            $totalDebe=0;
            $totalHaber=0;
            foreach ($cliente['Impcli'][0]['Impuesto']['Asientoestandare'] as $asientoestandarIVA) {
                if($asientoestandarIVA['tipoasiento']!='impuestos')continue;
                if(!isset($movId[$asientoestandarIVA['cuenta_id']])){
                    $movId[$asientoestandarIVA['cuenta_id']]=0;
                }
                $cuentaclienteid=0;
                $cuentaclientenombre=$asientoestandarIVA['Cuenta']['nombre'];
                foreach ($cliente['Cuentascliente'] as $cuentaclientaIVA){
                    if($cuentaclientaIVA['cuenta_id']==$asientoestandarIVA['cuenta_id']){
                        $cuentaclienteid=$cuentaclientaIVA['id'];
                        $cuentaclientenombre=$cuentaclientaIVA['nombre'];
                        break;
                    }
                }
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['value'=>$movId[$asientoestandarIVA['cuenta_id']],]);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha', array(
                    'readonly'=>'readonly',
                    'class'=>'datepicker',
                    'type'=>'hidden',
                    'label'=>array(
                        'text'=>"Vencimiento:",
                        "style"=>"display:inline",
                    ),
                    'readonly','readonly',
                    'value'=>date('d-m-Y'),
                    'div' => false,
                    'style'=> 'height:9px;display:inline'
                ));
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',['readonly'=>'readonly','type'=>'hidden','value'=>$cuentaclienteid]);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuenta_id',['readonly'=>'readonly','type'=>'hidden','orden'=>$i,'value'=>$asientoestandarIVA['cuenta_id'],'id'=>'cuenta'.$asientoestandarIVA['cuenta_id']]);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.numero',['label'=>false,'readonly'=>'readonly','value'=>$asientoestandarIVA['Cuenta']['numero'],'style'=>'width:82px']);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.nombre',['label'=>false,'readonly'=>'readonly','value'=>$cuentaclientenombre,'type'=>'text','style'=>'width:250px']);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
                    'label'=>false,'value'=>0,
                    'class'=>"inputDebe",
                ]);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
                        'label'=>false,'value'=>0,
                        'class'=>"inputHaber "
                    ])."</br>";
                $i++;
            }

            //Asiento Devengamiento del Decreto 814
            $Asientoid=0;
            $movId=[];
            foreach ($cliente['Impcli'][0]['Asiento'] as $asientodevengamientoIVA) {
                if($asientodevengamientoIVA['tipoasiento']=="impuestos2"){
                    $Asientoid=$asientodevengamientoIVA['id'];
                    foreach ($asientodevengamientoIVA['Movimiento'] as $mimovimiento){
                        $movId[$mimovimiento['Cuentascliente']['cuenta_id']] = $mimovimiento['id'];
                    }
                }
            }
            echo $this->Form->input('Asiento.1.id',['value'=>$Asientoid]);
            $d = new DateTime( '01-'.$periodo );
            echo $this->Form->input('Asiento.1.fecha',array(
                'class'=>'datepicker',
                'type'=>'text',
                'label'=>array(
                    'text'=>"Fecha:",
                ),
                'readonly','readonly',
                'value'=>$d->format( 't-m-Y' ),
//                'div' => false,
                'style'=> 'width:82px'
            ));
            echo $this->Form->input('Asiento.1.nombre',['readonly'=>'readonly','value'=>"Asiento Devengamiento Decreto 814" ,'style'=>'width:250px']);
            echo $this->Form->input('Asiento.1.descripcion',['readonly'=>'readonly','value'=>"Asiento Automatico periodo: ".$periodo,'style'=>'width:250px']);
            echo $this->Form->input('Asiento.1.cliente_id',['value'=>$cliente['Cliente']['id'],'type'=>'hidden']);
            echo $this->Form->input('Asiento.1.impcli_id',['value'=>$cliente['Impcli'][0]['id'],'type'=>'hidden']);
            echo $this->Form->input('Asiento.1.periodo',['value'=>$periodo,'type'=>'hidden']);
            echo $this->Form->input('Asiento.1.tipoasiento',['value'=>'impuestos2','type'=>'hidden'])."</br>";
            $i=0;

            foreach ($cliente['Impcli'][0]['Impuesto']['Asientoestandare'] as $asientoestandarIVA) {
                if($asientoestandarIVA['tipoasiento']!='impuestos2')continue;
                if(!isset($movId[$asientoestandarIVA['cuenta_id']])){
                    $movId[$asientoestandarIVA['cuenta_id']]=0;
                }
                $cuentaclienteid=0;
                $cuentaclientenombre="0";
                foreach ($cliente['Cuentascliente'] as $cuentaclientaIVA){
                    if($cuentaclientaIVA['cuenta_id']==$asientoestandarIVA['cuenta_id']){
                        $cuentaclienteid=$cuentaclientaIVA['id'];
                        $cuentaclientenombre=$cuentaclientaIVA['nombre'];
                        break;
                    }
                }
                echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.id',['value'=>$movId[$asientoestandarIVA['cuenta_id']],]);
                echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.fecha', array(
                    'readonly'=>'readonly',
                    'class'=>'datepicker',
                    'type'=>'hidden',
                    'label'=>array(
                        'text'=>"Vencimiento:",
                        "style"=>"display:inline",
                    ),
                    'readonly','readonly',
                    'value'=>date('d-m-Y'),
                    'div' => false,
                    'style'=> 'height:9px;display:inline'
                ));
                echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.cuentascliente_id',['readonly'=>'readonly','type'=>'hidden','value'=>$cuentaclienteid]);
                echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.cuenta_id',
                    [
                        'asiento'=>'1',
                        'readonly'=>'readonly',
                        'type'=>'hidden',
                        'orden'=>$i,'value'=>$asientoestandarIVA['cuenta_id'],
                        'id'=>'cuenta'.$asientoestandarIVA['cuenta_id']
                    ]);
                echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.numero',['label'=>false,'readonly'=>'readonly','value'=>$asientoestandarIVA['Cuenta']['numero'],'style'=>'width:82px']);
                echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.nombre',['label'=>false,'readonly'=>'readonly','value'=>$cuentaclientenombre,'type'=>'text','style'=>'width:250px']);
                echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.debe',[
                    'label'=>false,'value'=>0,
                    'class'=>"inputDebe "
                ]);
                echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.haber',[
                        'label'=>false,'value'=>0,
                        'class'=>"inputHaber "
                    ])."</br>";
                $i++;

            }
            echo $this->Form->label('','Total ',[
                'style'=>"display: -webkit-inline-box;width:355px;"
            ]);
            ?>
            <div style="width:98px;">
                <?php
                echo $this->Form->label('lblTotalDebe',
                    "$".number_format($totalDebe, 2, ".", ""),
                    [
                        'id'=>'lblTotalDebe',
                        'style'=>"display: inline;float:right"
                    ]
                );
                ?>
            </div>
            <div style="width:124px;">
                <?php
                echo $this->Form->label('lblTotalHaber',
                    "$".number_format($totalHaber, 2, ".", ""),
                    [
                        'id'=>'lblTotalHaber',
                        'style'=>"display: inline;float:right"
                    ]
                );
                ?>
            </div>
            <?php
            if(number_format($totalDebe, 2, ".", "")==number_format($totalHaber, 2, ".", "")){
                echo $this->Html->image('test-pass-icon.png',array(
                        'id' => 'iconDebeHaber',
                        'alt' => 'open',
                        'class' => 'btn_exit',
                        'title' => 'Debe igual al Haber diferencia: '.number_format(($totalDebe-$totalHaber), 2, ".", ""),
                    )
                );
            }else{
                echo $this->Html->image('test-fail-icon.png',array(
                        'id' => 'iconDebeHaber',
                        'alt' => 'open',
                        'class' => 'btn_exit',
                        'title' => 'Debe distinto al Haber diferencia: '.number_format(($totalDebe-$totalHaber), 2, ".", ""),
                    )
                );
            }
            echo $this->Form->end();
            ?>
        </div>
    </div>
</div>
<?php
    function inicializarAlicuotas($miarray){
        $miarray['0']=0;
        $miarray['2,5']=0;
        $miarray['2.5']=0;
        $miarray['5']=0;
        $miarray['50']=0;
        $miarray['10.5']=0;
        $miarray['21']=0;
        $miarray['27']=0;
        $miarray['total']=0;
        return $miarray;
    }
    function inicializarArrayCompras($miArray){
        $miArray=[];
        $miArray['Directo']=[];
        $miArray['Prorateable']=[];
        $miArray['Neto']=[];
        $miArray['Directo']=inicializarAlicuotas($miArray['Directo']);
        $miArray['Prorateable']=inicializarAlicuotas($miArray['Prorateable']);
        $miArray['Neto']=inicializarAlicuotas($miArray['Neto']);
        return $miArray;
    }
?>