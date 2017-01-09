<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('jquery.table2excel',array('inline'=>false));?>

<script type="text/javascript">
	$(document).ready(function() {   
    	$("#divContenedorCompras").hide();
        $("#divContenedorLiquidacion").hide();
        $("#divContenedorContabilidad").hide();

        $("#tabVentas_Iva").attr("class", "cliente_view_tab_active");
    	$("#tabCompras_Iva").attr("class", "cliente_view_tab");
        papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
        cargarAsiento();
        catchAsientoIVA();

        $( "#clickExcel" ).click(function() {
            $("#contenedor").table2excel({
                // exclude CSS class
                exclude: ".noExl",
                name: "Conveniomultilateral",
                filename:$('#clinombre').val()+"-"+ $('#periodoPDT').val()+"-"+"IVA"
            });
        });
        papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
        var beforePrint = function() {
            console.log('Functionality to run before printing.');
            $('#header').hide();
            $('.Formhead').hide();
            $('#divContenedorVentas').show();
            $('#divContenedorCompras').show();
            $('#divContenedorLiquidacion').show();
            $('#divContenedorContabilidad').hide();
            $('#divLiquidarIVA').hide();
            $('#index').css('float','left');
            $('#padding').css('padding','0px');
            $('#index').css('font-size','10px');
            $('#index').css('border-color','#FFF');
        };
        var afterPrint = function() {
            console.log('Functionality to run after printing');
            $('#index').css('font-size','14px');
            $('#header').show();
            $('.Formhead').show();
            $('#divLiquidarIVA').show();
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
        CambiarTab('ventas')
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
            $("#divContenedorContabilidad").hide();
        }
		if(sTab == "compras")
		{
			$("#tabCompras_Iva").attr("class", "cliente_view_tab_active");
			$("#divContenedorVentas").hide();    
			$("#divContenedorCompras").show();    	
			$("#divContenedorLiquidacion").hide();
            $("#divContenedorContabilidad").hide();
        }
		if (sTab == "liquidacion")
		{

			$("#tabLiquidacion_Iva").attr("class", "cliente_view_tab_active");
			$("#divContenedorVentas").hide();
			$("#divContenedorCompras").hide();
			$("#divContenedorLiquidacion").show();
            $("#divContenedorContabilidad").hide();
        }
        if (sTab == "contabilidad")
        {

            $("#tabContabilidad_Iva").attr("class", "cliente_view_tab_active");
            $("#divContenedorVentas").hide();
            $("#divContenedorCompras").hide();
            $("#divContenedorLiquidacion").hide();
            $("#divContenedorContabilidad").show();
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
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'Eventosimpuesto0Conceptosrestante0ClienteId',
                            value: $('#cliid').val(),
                            name: 'data[Eventosimpuesto][0][Conceptosrestante][0][cliente_id]'
                        }).appendTo('#EventosimpuestoRealizartarea5Form');
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'Eventosimpuesto0Conceptosrestante0ImpcliId',
                            value: $('#impcliidPDT').val(),
                            name: 'data[Eventosimpuesto][0][Conceptosrestante][0][impcli_id]'
                        }).appendTo('#EventosimpuestoRealizartarea5Form');
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'Eventosimpuesto0Conceptosrestante0ConceptostipoId',
                            value: 1,
                            name: 'data[Eventosimpuesto][0][Conceptosrestante][0][conceptostipo_id]'
                        }).appendTo('#EventosimpuestoRealizartarea5Form');
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'Eventosimpuesto0Conceptosrestante0Periodo',
                            value: $('#periodoPDT').val(),
                            name: 'data[Eventosimpuesto][0][Conceptosrestante][0][periodo]'
                        }).appendTo('#EventosimpuestoRealizartarea5Form');
                        $('<label>').html("Saldo Libre Disponibilidad").appendTo('#EventosimpuestoRealizartarea5Form');
                        $('<img>').attr({
                            src: serverLayoutURL+'/img/ii.png',
                            style: 'width:15px;height:15px',
                            title: 'Este campo se cargara como un Pago a Cuenta del tipo Saldo de Libre Disponibilidad' +
                            ' en el periodo '+$('#periodoPDT').val(),
                            alt: ''
                        }).appendTo('#EventosimpuestoRealizartarea5Form');
                        $('<input>').attr({
                            type: 'text',
                            id: 'Eventosimpuesto0Conceptosrestante0Montoretenido',
                            value: saldoLD,
                            name: 'data[Eventosimpuesto][0][Conceptosrestante][0][montoretenido]'
                        }).appendTo('#EventosimpuestoRealizartarea5Form');
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'Eventosimpuesto0Conceptosrestante0Monto',
                            value: saldoLD,
                            name: 'data[Eventosimpuesto][0][Conceptosrestante][0][monto]'
                        }).appendTo('#EventosimpuestoRealizartarea5Form');
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'Eventosimpuesto0Conceptosrestante0Fecha',
                            value: $.datepicker.formatDate('yy/mm/dd', new Date()),
                            name: 'data[Eventosimpuesto][0][Conceptosrestante][0][fecha]'
                        }).appendTo('#EventosimpuestoRealizartarea5Form');
                    }
                }else{
                    $('#Eventosimpuesto0Conceptosrestante0Montoretenido').val(saldoLD);
                }
                if($('#Eventosimpuesto0Usosaldo0Id').length<=0) {
                    if (usoSLD * 1 > 0) {
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'Eventosimpuesto0Usosaldo0EventosimpuestoId',
                            value: $('#Eventosimpuesto0Id').val(),
                            name: 'data[Eventosimpuesto][0][Usosaldo][0][eventosimpuesto_id]'
                        }).appendTo('#EventosimpuestoRealizartarea5Form');
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'Eventosimpuesto0Usosaldo0ConceptosrestanteId',
                            value: usoSLDID,
                            name: 'data[Eventosimpuesto][0][Usosaldo][0][conceptosrestante_id]'
                        }).appendTo('#EventosimpuestoRealizartarea5Form');
                        $('<label>').html("Uso Saldo Libre Disponibilidad periodo anterior").appendTo('#EventosimpuestoRealizartarea5Form');
                        $('<img>').attr({
                            src: serverLayoutURL + '/img/ii.png',
                            style: 'width:15px;height:15px',
                            title: 'Se registrará el uso de este Saldo de libre disponibilidad(SLD) de periodos anteriores, ' +
                            'ya sea que se lo este acumulando o usando para pagar el impuesto, el saldo para periodos ' +
                            'anteriores quedará en 0. Si se recaulcula este informe se intentara tomar de nuevo este SLD, por lo ' +
                            'que se debe eliminar, en la tarea cargar del informe de avance, el Uso del saldo de libre disponibilidad',
                            alt: ''
                        }).appendTo('#EventosimpuestoRealizartarea5Form');
                        $('<input>').attr({
                            type: 'text',
                            id: 'Eventosimpuesto0Usosaldo0Importe',
                            value: usoSLD,
                            name: 'data[Eventosimpuesto][0][Usosaldo][0][importe]'
                        }).appendTo('#EventosimpuestoRealizartarea5Form');
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'Eventosimpuesto0Usosaldo0Fecha',
                            value: $.datepicker.formatDate('yy/mm/dd', new Date()),
                            name: 'data[Eventosimpuesto][0][Usosaldo][0][fecha]'
                        }).appendTo('#EventosimpuestoRealizartarea5Form');
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
            var saldotecnicoperiodoant = $("#spnSaldoAFavorPeriodoAnt").html();
            var difST = saldotecnico*1-saldotecnicoperiodoant*1;
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
            $('#Asiento0Movimiento'+orden+'Debe').val(saldoLD);
            var saldoLDperiodoant = $("#spnSaldoAFavorLibreDispPeriodoAnteriorNetousos").html();
            var difSLD = saldoLD*1-saldoLDperiodoant*1;
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
            $('#Asiento0Movimiento'+orden+'Haber').val(totalnocomputable);
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
</script>
<?php
echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
echo $this->Form->input('impcliidPDT',array('value'=>$cliente['Impcli'][0]['id'],'type'=>'hidden'));
echo $this->Form->input('clinombre',array('value'=>$cliente['Cliente']['nombre'],'type'=>'hidden'));
echo $this->Form->input('cliid',array('value'=>$cliente['Cliente']['id'],'type'=>'hidden'));?>
<div class="eventosclientes index" id="contenedor">
    <div class="Formhead" class="noExl">
        <label>INFORME IVA</label>
        <label>Contribuyente: <?php echo $cliente['Cliente']['nombre']; ?> </label>
        <label>Periodo: <?php echo $periodo; ?></label>
        <?php echo $this->Form->button('Imprimir',
            array('type' => 'button',
                'class' =>"btn_imprimir",
                'onClick' => "imprimir()"
            )
        );?>
        <?php echo $this->Form->button('Excel',
            array('type' => 'button',
                'id'=>"clickExcel",
                'class' =>"btn_imprimir",
            )
        );?>
    </div >
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
        <div id="tabContabilidad_Iva" class="cliente_view_tab" onclick="CambiarTab('contabilidad');" style="width:24%;">
            <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Contabilidad</label>
        </div>
    </div>
    <div style="width:100%; padding-top:5px" class="noExl"></div>
    <div id="divContenedorVentas">
        <?php
            $TotalDebitoFiscal_SumaTotal = 0;
            $TotalCreditoFiscal_SumaTotal = 0;
        ?>
        <?php foreach ($actividades as $actividad){ ?>
        <div style="width:100%; height: 10px"></div>
        <?php
        $ActividadCliente_id = $actividad['Actividadcliente']['actividade_id'];
        ?>
        <div id='divContenedorTablaActividad_<?php echo $ActividadCliente_id; ?>'>
            <table id='divTituloActividad_<?php echo $ActividadCliente_id; ?>'  style="margin-bottom:0; cursor: pointer" onclick="MostrarTabla(this, 'ventas');">
                <tr>
                    <td colspan="5" style='background-color:#76b5cd'>
                    <b>
                    <?php
                        echo 'ACTIVIDAD: '. $actividad['Actividade']['nombre'];
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
                $Alicuota0 = false;
                $TotalAlicuota0 = 0;
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
                foreach ($ventas as $venta){
                    if($venta['Actividadcliente']['actividade_id'] == $ActividadCliente_id)
                    {
                        if ($venta['Venta']['tipodebito'] == 'Debito Fiscal' && $venta['Venta']['condicioniva'] == 'responsableinscripto')
                        {
                            if ($venta['Venta']['alicuota'] == '0' || $venta['Venta']['alicuota'] == '')
                            {
                                $Alicuota0 = true;
                                $TotalAlicuota0 = $TotalAlicuota0 + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50')
                            {
                                $Alicuota2_5 = true;
                                $TotalAlicuota2_5 = $TotalAlicuota2_5 + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00')
                            {
                                $Alicuota5_0 = true;
                                $TotalAlicuota5_0  = $TotalAlicuota5_0  + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50')
                            {
                                $Alicuota10_5 = true;
                                $TotalAlicuota10_5 = $TotalAlicuota10_5 + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00')
                            {
                                $Alicuota21_0 = true;
                                $TotalAlicuota21_0 = $TotalAlicuota21_0 + + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00')
                            {
                                $Alicuota27_0 = true;
                                $TotalAlicuota27_0 = $TotalAlicuota27_0 + + $venta['Venta']['neto'];
                            }
                        }
                    }

                };
                if($Alicuota0 || $Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
                { ?>
                    <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_DebitoFiscal_RespInsc' style="">
                        <table   >
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
                                    if($Alicuota0)
                                    {
                                        echo '<tr>
                                                  <td style="width:20%">0</td>
                                                  <td style="width:20%">'.$TotalAlicuota0.'</td>
                                                  <td style="width:20%">'.$TotalAlicuota0 * 0.025.'</td>
                                                  <td style="width:20%">'.$TotalAlicuota0 * 0.025.'</td>
                                                  <td style="width:20%">0</td>
                                                  </tr>
                                            ';
                                    }
                                    if($Alicuota2_5)
                                    {
                                        echo '<tr>
                                              <td style="width:20%">2.5</td>
                                              <td style="width:20%">'.$TotalAlicuota2_5.'</td>
                                              <td style="width:20%">'.$TotalAlicuota2_5 * 0.025.'</td>
                                              <td style="width:20%">'.$TotalAlicuota2_5 * 0.025.'</td>
                                              <td style="width:20%">0</td>
                                              </tr>
                                        ';
                                    }
                                    if($Alicuota5_0)
                                    {
                                        echo '<tr>
                                              <td style="width:20%">5.0</td>
                                              <td style="width:20%">'.$TotalAlicuota5_0.'</td>
                                              <td style="width:20%">'.$TotalAlicuota5_0 * 0.05.'</td>
                                              <td style="width:20%">'.$TotalAlicuota5_0 * 0.05.'</td>
                                              <td style="width:20%">0</td>
                                              </tr>
                                        ';
                                    }
                                    if($Alicuota10_5)
                                    {
                                        echo '<tr>
                                              <td style="width:20%">10.5</td>
                                              <td style="width:20%">'.$TotalAlicuota10_5.'</td>
                                              <td style="width:20%">'.$TotalAlicuota10_5 * 0.105.'</td>
                                              <td style="width:20%">'.$TotalAlicuota10_5 * 0.105.'</td>
                                              <td style="width:20%">0</td>
                                              </tr>
                                        ';
                                    }
                                    if($Alicuota21_0)
                                    {
                                        echo '<tr>
                                              <td style="width:20%">21.0</td>
                                              <td style="width:20%">'.$TotalAlicuota21_0.'</td>
                                              <td style="width:20%">'.$TotalAlicuota21_0 * 0.21.'</td>
                                              <td style="width:20%">'.$TotalAlicuota21_0 * 0.21.'</td>
                                              <td style="width:20%">0</td>
                                              </tr>
                                        ';
                                    }
                                    if($Alicuota27_0)
                                    {
                                        echo '<tr>
                                              <td style="width:20%">27.0</td>
                                              <td style="width:20%">'.$TotalAlicuota27_0.'</td>
                                              <td style="width:20%">'.$TotalAlicuota27_0 * 0.27.'</td>
                                              <td style="width:20%">'.$TotalAlicuota27_0 * 0.27.'</td>
                                              <td style="width:20%">0</td>
                                              </tr>
                                        ';
                                    }
                                    if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
                                    {
                                        $TotalNeto = $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                                        $TotalDebitoFiscal = ($TotalAlicuota2_5 * 0.025) + ($TotalAlicuota5_0 * 0.05) + ($TotalAlicuota10_5 * 0.105) + ($TotalAlicuota21_0 * 0.21) + ($TotalAlicuota27_0 * 0.27);
                                        echo '<tr>
                                              <td style="width:20%">Totales</td>
                                              <td style="width:20%">'.$TotalNeto.'</td>
                                              <td style="width:20%">'.$TotalDebitoFiscal.'</td>
                                              <td style="width:20%">'.$TotalDebitoFiscal.'</td>
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
                $Alicuota0 = false;
                $TotalAlicuota0 = 0;
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
                foreach ($ventas as $venta){
                    //endforeach;
                    //echo $venta['Actividadcliente']['actividade_id']. ' - '.$ActividadCliente_id;
                    if($venta['Actividadcliente']['actividade_id'] == $ActividadCliente_id)
                    {
                        if ($venta['Venta']['tipodebito'] == 'Debito Fiscal' && $venta['Venta']['condicioniva'] == 'monotributista')
                        {
                            if ($venta['Venta']['alicuota'] == '0' || $venta['Venta']['alicuota'] == '')
                            {
                                $Alicuota0 = true;
                                $TotalAlicuota0 = $TotalAlicuota0 + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50')
                            {
                                $Alicuota2_5 = true;
                                $TotalAlicuota2_5 = $TotalAlicuota2_5 + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00')
                            {
                                $Alicuota5_0 = true;
                                $TotalAlicuota5_0  = $TotalAlicuota5_0  + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50')
                            {
                                $Alicuota10_5 = true;
                                $TotalAlicuota10_5 = $TotalAlicuota10_5 + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00')
                            {
                                $Alicuota21_0 = true;
                                $TotalAlicuota21_0 = $TotalAlicuota21_0 + $venta['Venta']['neto'];
                            }
                            if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00')
                            {
                                $Alicuota27_0 = true;
                                $TotalAlicuota27_0 = $TotalAlicuota27_0 + $venta['Venta']['neto'];
                            }
                        }
                    }
                };
                if($Alicuota0 || $Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0){
                ?>
                    <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_DebitoFiscal_Monotributista' style="">
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
                                if($Alicuota0)
                                {
                                    echo '<tr>
                                                      <td style="width:20%">0</td>
                                                      <td style="width:20%">'.$TotalAlicuota0.'</td>
                                                      <td style="width:20%">'.$TotalAlicuota0 * 0.025.'</td>
                                                      <td style="width:20%">'.$TotalAlicuota0 * 0.025.'</td>
                                                      <td style="width:20%">0</td>
                                                      </tr>
                                                ';
                                }
                                if($Alicuota2_5)
                                {
                                    echo '<tr>
                                          <td style="width:20%">2.5</td>
                                          <td style="width:20%">'.$TotalAlicuota2_5.'</td>
                                          <td style="width:20%">'.$TotalAlicuota2_5 * 0.025.'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota5_0)
                                {
                                    echo '<tr>
                                          <td style="width:20%">5.0</td>
                                          <td style="width:20%">'.$TotalAlicuota5_0.'</td>
                                          <td style="width:20%">'.$TotalAlicuota5_0 * 0.05.'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota10_5)
                                {
                                    echo '<tr>
                                          <td style="width:20%">10.5</td>
                                          <td style="width:20%">'.$TotalAlicuota10_5.'</td>
                                          <td style="width:20%">'.$TotalAlicuota10_5 * 0.105.'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota21_0)
                                {
                                    echo '<tr>
                                          <td style="width:20%">21.0</td>
                                          <td style="width:20%">'.$TotalAlicuota21_0.'</td>
                                          <td style="width:20%">'.$TotalAlicuota21_0 * 0.21.'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota27_0)
                                {
                                    echo '<tr>
                                          <td style="width:20%">27.0</td>
                                          <td style="width:20%">'.$TotalAlicuota27_0.'</td>
                                          <td style="width:20%">'.$TotalAlicuota27_0 * 0.27.'</td>
                                          <td style="width:20%"></td>
                                          <td style="width:20%"></td>
                                          </tr>
                                    ';
                                }
                                if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
                                {
                                    $TotalNeto = $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                                    $TotalDebitoFiscal = ($TotalAlicuota2_5 * 0.025) + ($TotalAlicuota5_0 * 0.05) + ($TotalAlicuota10_5 * 0.105) + ($TotalAlicuota21_0 * 0.21) + ($TotalAlicuota27_0 * 0.27);
                                    echo '<tr>
                                          <td style="width:20%">Totales</td>
                                          <td style="width:20%">'.$TotalNeto.'</td>
                                          <td style="width:20%">'.$TotalDebitoFiscal.'</td>
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
            foreach ($ventas as $venta){
                if($venta['Actividadcliente']['actividade_id'] == $ActividadCliente_id)
                {
                    if ($venta['Venta']['tipodebito'] == 'Debito Fiscal' && $venta['Venta']['condicioniva'] == 'consf/exento/noalcanza')
                    {
                        if ($venta['Venta']['alicuota'] == '0' || $venta['Venta']['alicuota'] == '')
                        {
                            $Alicuota0 = true;
                            $TotalAlicuota0 = $TotalAlicuota0 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50')
                        {
                            $Alicuota2_5 = true;
                            $TotalAlicuota2_5 = $TotalAlicuota2_5 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00')
                        {
                            $Alicuota5_0 = true;
                            $TotalAlicuota5_0  = $TotalAlicuota5_0  + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50')
                        {
                            $Alicuota10_5 = true;
                            $TotalAlicuota10_5 = $TotalAlicuota10_5 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00')
                        {
                            $Alicuota21_0 = true;
                            $TotalAlicuota21_0 = $TotalAlicuota21_0 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00')
                        {
                            $Alicuota27_0 = true;
                            $TotalAlicuota27_0 = $TotalAlicuota27_0 + $venta['Venta']['neto'];
                        }
                    }
                }
            };
            if($Alicuota0 || $Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
                ?>
                <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_DebitoFiscal_ConsF'
                     style="">
                    <table >
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
                            if($Alicuota0)
                            {
                                echo '<tr>
                                  <td style="width:20%">0</td>
                                  <td style="width:20%">'.$TotalAlicuota0.'</td>
                                  <td style="width:20%">'.$TotalAlicuota0 * 0.025.'</td>
                                  <td style="width:20%">'.$TotalAlicuota0 * 0.025.'</td>
                                  <td style="width:20%">0</td>
                                  </tr>
                            ';
                            }
                            if ($Alicuota2_5) {
                                echo '<tr>
                                  <td style="width:20%">2.5</td>
                                  <td style="width:20%">' . $TotalAlicuota2_5 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota2_5 * 0.025 . '</td>
                                  <td style="width:20%"></td>
                                  <td style="width:20%"></td>
                                  </tr>
                            ';
                            }
                            if ($Alicuota5_0) {
                                echo '<tr>
                                  <td style="width:20%">5.0</td>
                                  <td style="width:20%">' . $TotalAlicuota5_0 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota5_0 * 0.05 . '</td>
                                  <td style="width:20%"></td>
                                  <td style="width:20%"></td>
                                  </tr>
                            ';
                            }
                            if ($Alicuota10_5) {
                                echo '<tr>
                                  <td style="width:20%">10.5</td>
                                  <td style="width:20%">' . $TotalAlicuota10_5 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota10_5 * 0.105 . '</td>
                                  <td style="width:20%"></td>
                                  <td style="width:20%"></td>
                                  </tr>
                            ';
                            }
                            if ($Alicuota21_0) {
                                echo '<tr>
                                  <td style="width:20%">21.0</td>
                                  <td style="width:20%">' . $TotalAlicuota21_0 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota21_0 * 0.21 . '</td>
                                  <td style="width:20%"></td>
                                  <td style="width:20%"></td>
                                  </tr>
                            ';
                            }
                            if ($Alicuota27_0) {
                                echo '<tr>
                                  <td style="width:20%">27.0</td>
                                  <td style="width:20%">' . $TotalAlicuota27_0 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota27_0 * 0.27 . '</td>
                                  <td style="width:20%"></td>
                                  <td style="width:20%"></td>
                                  </tr>
                            ';
                            }
                            if ($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
                                $TotalNeto = $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                                $TotalDebitoFiscal = ($TotalAlicuota2_5 * 0.025) + ($TotalAlicuota5_0 * 0.05) + ($TotalAlicuota10_5 * 0.105) + ($TotalAlicuota21_0 * 0.21) + ($TotalAlicuota27_0 * 0.27);
                                echo '<tr>
                                  <td style="width:20%">Totales</td>
                                  <td style="width:20%">' . $TotalNeto . '</td>
                                  <td style="width:20%">' . $TotalDebitoFiscal . '</td>
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
            foreach ($ventas as $venta){
                if($venta['Actividadcliente']['actividade_id'] == $ActividadCliente_id)
                {
                    if ($venta['Venta']['tipodebito'] == 'Bien de uso' && $venta['Venta']['condicioniva'] == 'responsableinscripto')
                    {
                        if ($venta['Venta']['alicuota'] == '0' || $venta['Venta']['alicuota'] == '')
                        {
                            $Alicuota0 = true;
                            $TotalAlicuota0 = $TotalAlicuota0 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50')
                        {
                            $Alicuota2_5 = true;
                            $TotalAlicuota2_5 = $TotalAlicuota2_5 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00')
                        {
                            $Alicuota5_0 = true;
                            $TotalAlicuota5_0  = $TotalAlicuota5_0  + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50')
                        {
                            $Alicuota10_5 = true;
                            $TotalAlicuota10_5 = $TotalAlicuota10_5 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00')
                        {
                            $Alicuota21_0 = true;
                            $TotalAlicuota21_0 = $TotalAlicuota21_0 + + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00')
                        {
                            $Alicuota27_0 = true;
                            $TotalAlicuota27_0 = $TotalAlicuota27_0 + + $venta['Venta']['neto'];
                        }
                    }
                }
            };
            if($Alicuota0 || $Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
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
                            if($Alicuota0)
                            {
                                echo '<tr>
                                  <td style="width:20%">0</td>
                                  <td style="width:20%">'.$TotalAlicuota0.'</td>
                                  <td style="width:20%">'.$TotalAlicuota0 * 0.025.'</td>
                                  <td style="width:20%">'.$TotalAlicuota0 * 0.025.'</td>
                                  <td style="width:20%">0</td>
                                  </tr>
                            ';
                            }
                            if ($Alicuota2_5) {
                                echo '<tr>
                                  <td style="width:20%">2.5</td>
                                  <td style="width:20%">' . $TotalAlicuota2_5 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota2_5 * 0.025 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota2_5 * 0.025 . '</td>
                                  <td style="width:20%">0</td>
                                  </tr>
                            ';
                            }
                            if ($Alicuota5_0) {
                                echo '<tr>
                                  <td style="width:20%">5.0</td>
                                  <td style="width:20%">' . $TotalAlicuota5_0 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota5_0 * 0.05 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota5_0 * 0.05 . '</td>
                                  <td style="width:20%">0</td>
                                  </tr>
                            ';
                            }
                            if ($Alicuota10_5) {
                                echo '<tr>
                                  <td style="width:20%">10.5</td>
                                  <td style="width:20%">' . $TotalAlicuota10_5 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota10_5 * 0.105 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota10_5 * 0.105 . '</td>
                                  <td style="width:20%">0</td>
                                  </tr>
                            ';
                            }
                            if ($Alicuota21_0) {
                                echo '<tr>
                                  <td style="width:20%">21.0</td>
                                  <td style="width:20%">' . $TotalAlicuota21_0 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota21_0 * 0.21 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota21_0 * 0.21 . '</td>
                                  <td style="width:20%">0</td>
                                  </tr>
                            ';
                            }
                            if ($Alicuota27_0) {
                                echo '<tr>
                                  <td style="width:20%">27.0</td>
                                  <td style="width:20%">' . $TotalAlicuota27_0 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota27_0 * 0.27 . '</td>
                                  <td style="width:20%">' . $TotalAlicuota27_0 * 0.27 . '</td>
                                  <td style="width:20%">0</td>
                                  </tr>
                            ';
                            }
                            if ($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
                                $TotalNeto = $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                                $TotalDebitoFiscal = ($TotalAlicuota2_5 * 0.025) + ($TotalAlicuota5_0 * 0.05) + ($TotalAlicuota10_5 * 0.105) + ($TotalAlicuota21_0 * 0.21) + ($TotalAlicuota27_0 * 0.27);
                                echo '<tr>
                                  <td style="width:20%">Totales</td>
                                  <td style="width:20%">' . $TotalNeto . '</td>
                                  <td style="width:20%">' . $TotalDebitoFiscal . '</td>
                                  <td style="width:20%">' . $TotalDebitoFiscal . '</td>
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
            foreach ($ventas as $venta){
                if($venta['Actividadcliente']['actividade_id'] == $ActividadCliente_id)
                {
                    if ($venta['Venta']['tipodebito'] == 'Bien de uso' && $venta['Venta']['condicioniva'] == 'monotributista')
                    {
                        if ($venta['Venta']['alicuota'] == '0' || $venta['Venta']['alicuota'] == '')
                        {
                            $Alicuota0 = true;
                            $TotalAlicuota0 = $TotalAlicuota0 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50')
                        {
                            $Alicuota2_5 = true;
                            $TotalAlicuota2_5 = $TotalAlicuota2_5 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00')
                        {
                            $Alicuota5_0 = true;
                            $TotalAlicuota5_0  = $TotalAlicuota5_0  + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50')
                        {
                            $Alicuota10_5 = true;
                            $TotalAlicuota10_5 = $TotalAlicuota10_5 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00')
                        {
                            $Alicuota21_0 = true;
                            $TotalAlicuota21_0 = $TotalAlicuota21_0 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00')
                        {
                            $Alicuota27_0 = true;
                            $TotalAlicuota27_0 = $TotalAlicuota27_0 + $venta['Venta']['neto'];
                        }
                    }
                }
            };
            if($Alicuota0 || $Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
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
                            if($Alicuota0)
                            {
                                echo '<tr>
                                      <td style="width:20%">0</td>
                                      <td style="width:20%">'.$TotalAlicuota0.'</td>
                                      <td style="width:20%">'.$TotalAlicuota0 * 0.025.'</td>
                                      <td style="width:20%">'.$TotalAlicuota0 * 0.025.'</td>
                                      <td style="width:20%">0</td>
                                      </tr>
                                ';
                            }
                            if($Alicuota2_5)
                            {
                                echo '<tr>
                                     <td>2.5</td>
                                      <td>'.$TotalAlicuota2_5.'</td>
                                      <td>'.$TotalAlicuota2_5 * 0.025.'</td>
                                      <td></td>
                                      <td></td>
                                      </tr>
                                ';
                            }
                            if($Alicuota5_0)
                            {
                                echo '<tr>
                                      <td style="width:20%">5.0</td>
                                      <td style="width:20%">'.$TotalAlicuota5_0.'</td>
                                      <td style="width:20%">'.$TotalAlicuota5_0 * 0.05.'</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                      </tr>
                                ';
                            }
                            if($Alicuota10_5)
                            {
                                echo '<tr>
                                      <td style="width:20%">10.5</td>
                                      <td style="width:20%">'.$TotalAlicuota10_5.'</td>
                                      <td style="width:20%">'.$TotalAlicuota10_5 * 0.105.'</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                      </tr>
                                ';
                            }
                            if($Alicuota21_0)
                            {
                                echo '<tr>
                                      <td style="width:20%">21.0</td>
                                      <td style="width:20%">'.$TotalAlicuota21_0.'</td>
                                      <td style="width:20%">'.$TotalAlicuota21_0 * 0.21.'</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                      </tr>
                                ';
                            }
                            if($Alicuota27_0)
                            {
                                echo '<tr>
                                      <td style="width:20%">27.0</td>
                                      <td style="width:20%">'.$TotalAlicuota27_0.'</td>
                                      <td style="width:20%">'.$TotalAlicuota27_0 * 0.27.'</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                      </tr>
                                ';
                            }
                            if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
                            {
                                $TotalNeto = $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                                $TotalDebitoFiscal = ($TotalAlicuota2_5 * 0.025) + ($TotalAlicuota5_0 * 0.05) + ($TotalAlicuota10_5 * 0.105) + ($TotalAlicuota21_0 * 0.21) + ($TotalAlicuota27_0 * 0.27);
                                echo '<tr>
                                      <td style="width:20%">Totales</td>
                                      <td style="width:20%">'.$TotalNeto.'</td>
                                      <td style="width:20%">'.$TotalDebitoFiscal.'</td>
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

            foreach ($ventas as $venta){
                if($venta['Actividadcliente']['actividade_id'] == $ActividadCliente_id)
                {
                    if ($venta['Venta']['tipodebito'] == 'Bien de uso' && $venta['Venta']['condicioniva'] == 'consf/exento/noalcanza')
                    {
                        if ($venta['Venta']['alicuota'] == '0' || $venta['Venta']['alicuota'] == '')
                        {
                            $Alicuota0 = true;
                            $TotalAlicuota0 = $TotalAlicuota0 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '2.5' || $venta['Venta']['alicuota'] == '2.50')
                        {
                            $Alicuota2_5 = true;
                            $TotalAlicuota2_5 = $TotalAlicuota2_5 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '5.0' || $venta['Venta']['alicuota'] == '5.00')
                        {
                            $Alicuota5_0 = true;
                            $TotalAlicuota5_0  = $TotalAlicuota5_0  + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '10.5' || $venta['Venta']['alicuota'] == '10.50')
                        {
                            $Alicuota10_5 = true;
                            $TotalAlicuota10_5 = $TotalAlicuota10_5 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '21.0' || $venta['Venta']['alicuota'] == '21.00')
                        {
                            $Alicuota21_0 = true;
                            $TotalAlicuota21_0 = $TotalAlicuota21_0 + $venta['Venta']['neto'];
                        }
                        if ($venta['Venta']['alicuota'] == '27.0' || $venta['Venta']['alicuota'] == '27.00')
                        {
                            $Alicuota27_0 = true;
                            $TotalAlicuota27_0 = $TotalAlicuota27_0 + $venta['Venta']['neto'];
                        }
                    }
                }
            };
            if($Alicuota0 || $Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
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
                            if($Alicuota0)
                            {
                                echo '<tr>
                                          <td style="width:20%">0</td>
                                          <td style="width:20%">'.$TotalAlicuota0.'</td>
                                          <td style="width:20%">'.$TotalAlicuota0 * 0.025.'</td>
                                          <td style="width:20%">'.$TotalAlicuota0 * 0.025.'</td>
                                          <td style="width:20%">0</td>
                                          </tr>
                                    ';
                            }
                            if($Alicuota2_5)
                            {
                                echo '<tr>
                                      <td style="width:20%">2.5</td>
                                      <td style="width:20%">'.$TotalAlicuota2_5.'</td>
                                      <td style="width:20%">'.$TotalAlicuota2_5 * 0.025.'</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                      </tr>
                                ';
                            }
                            if($Alicuota5_0)
                            {
                                echo '<tr>
                                      <td style="width:20%">5.0</td>
                                      <td style="width:20%">'.$TotalAlicuota5_0.'</td>
                                      <td style="width:20%">'.$TotalAlicuota5_0 * 0.05.'</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                      </tr>
                                ';
                            }
                            if($Alicuota10_5)
                            {
                                echo '<tr>
                                      <td style="width:20%">10.5</td>
                                      <td style="width:20%">'.$TotalAlicuota10_5.'</td>
                                      <td style="width:20%">'.$TotalAlicuota10_5 * 0.105.'</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                      </tr>
                                ';
                            }
                            if($Alicuota21_0)
                            {
                                echo '<tr>
                                      <td style="width:20%">21.0</td>
                                      <td style="width:20%">'.$TotalAlicuota21_0.'</td>
                                      <td style="width:20%">'.$TotalAlicuota21_0 * 0.21.'</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                      </tr>
                                ';
                            }
                            if($Alicuota27_0)
                            {
                                echo '<tr>
                                      <td style="width:20%">27.0</td>
                                      <td style="width:20%">'.$TotalAlicuota27_0.'</td>
                                      <td style="width:20%">'.$TotalAlicuota27_0 * 0.27.'</td>
                                      <td style="width:20%"></td>
                                      <td style="width:20%"></td>
                                      </tr>
                                ';
                            }
                            if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
                            {
                                $TotalNeto = $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                                $TotalDebitoFiscal = ($TotalAlicuota2_5 * 0.025) + ($TotalAlicuota5_0 * 0.05) + ($TotalAlicuota10_5 * 0.105) + ($TotalAlicuota21_0 * 0.21) + ($TotalAlicuota27_0 * 0.27);
                                echo '<tr>
                                      <td style="width:20%">Totales</td>
                                      <td style="width:20%">'.$TotalNeto.'</td>
                                      <td style="width:20%">'.$TotalDebitoFiscal.'</td>
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
                    > TIPO CREDITO: Restitución de Crédito Fiscal
                </td>
            </tr>
        </table>

        <?php
            $TotalBsGral = 0;
            $TotalLocaciones = 0;
            $TotalPresServ = 0;
            $TotalBsUso = 0;
            $TotalOtrosConceptos = 0;
            $TotalDcto814 = 0;


        $TotalPercepcionesIVA = 0;
            foreach ($compras as $compra) {
                //Debugger::dump($compra);
                if ($compra['Actividadcliente']['actividade_id'] == $ActividadCliente_id) {
                    if ($compra['Compra']['tipocredito'] == 'Restitucion credito fiscal' ) {
                        if ($compra['Compra']['imputacion'] == 'Bs en Gral') {
                            $TotalBsGral = $TotalBsGral + $compra[0]['iva'];
                        }
                        if ($compra['Compra']['imputacion'] == 'Locaciones') {
                            $TotalLocaciones = $TotalLocaciones + $compra[0]['iva'];
                        }
                        if ($compra['Compra']['imputacion'] == 'Prest. Servicios') {
                            $TotalPresServ = $TotalPresServ + $compra[0]['iva'];
                        }
                        if ($compra['Compra']['imputacion'] == 'Bs Uso') {
                            $TotalBsUso = $TotalBsUso + $compra[0]['iva'];
                        }
                        if ($compra['Compra']['imputacion'] == 'Otros Conceptos') {
                            $TotalOtrosConceptos = $TotalOtrosConceptos + $compra[0]['
                            '];
                        }
                        /*
                         * No vamos a agrgar el Dcto 814 como una compra por que afectara otros impuestos, ahroa se agrega como un pago a cuenta
                         * if ($compra['Compra']['tipocredito'] == 'Restitucion credito fiscal' && $compra['Compra']['imputacion'] == 'Dcto 814')
                        {
                            //Aca se debe sumar solo si el Dcto814 es del Tipo Restitucion Credito Fiscal
                            $TotalDcto814 = $TotalDcto814 + $compra['Compra']['neto'];
                        }*/
                    }
                }
                //aca vamos a calcular las perceptiones tambien, acumulando una sola vez para todas las actividades
                $TotalPercepcionesIVA = $TotalPercepcionesIVA + $compra[0]['ivapercep'];
            };
            echo $this->Form->input('totalpercepciones',
                [
                    'type'=>'hidden',
                    'value'=>$TotalPercepcionesIVA
                ]
            );
            // ACA la restitucion de credito de compra SUMA AL DEBITO tal vez esto deberia restar del credito en realidad
            $TotalDebitoFiscal_SumaTotal = $TotalDebitoFiscal_SumaTotal + $TotalBsGral + $TotalLocaciones + $TotalPresServ + $TotalBsUso + $TotalOtrosConceptos + $TotalDcto814;
        if($TotalBsGral>0){
        ?>
        <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_RestCredFiscal_BsGral' style="">
            <table   >
                <tr>
                    <td colspan="5" style='background-color:#87cfeb'>
                        > > OPERACION: Bs en Gral
                    </td>
                </tr>
                <tr style='background-color:#f0f0f0'>
                    <td>Facturado</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><?php echo $TotalBsGral ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
        <?php }
        if($TotalLocaciones>0){
        ?>
        <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_RestCredFiscal_Locaciones' style="">
            <table   >
                <tr>
                    <td colspan="6" style='background-color:#87cfeb'>
                        > > OPERACION: Locaciones
                    </td>
                </tr>
                <tr style='background-color:#f0f0f0'>
                    <td>Facturado</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><?php echo $TotalLocaciones ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
        <?php }
        if($TotalPresServ>0){
        ?>
        <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_RestCredFiscal_PresServ' style="">
            <table   >
                <tr>
                    <td colspan="5" style='background-color:#87cfeb'>
                        > > OPERACION: Prest. Servicios
                    </td>
                </tr>
                <tr style='background-color:#f0f0f0'>
                    <td>Facturado</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><?php echo $TotalPresServ ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
        <?php }
        if($TotalBsUso>0){
        ?>
        <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_RestCredFiscal_BsUso' style="">
            <table   >
                <tr>
                    <td colspan="5" style='background-color:#87cfeb'>
                        > > OPERACION: Bs. Uso
                    </td>
                </tr>
                <tr style='background-color:#f0f0f0'>
                    <td>Facturado</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><?php echo $TotalBsUso ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
        <?php }
        if($TotalOtrosConceptos>0){
        ?>
        <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_RestCredFiscal_OtrosConc' style="">
            <table   >
                <tr>
                    <td colspan="5" style='background-color:#87cfeb'>
                        > > OPERACION: Otros Conceptos
                    </td>
                </tr>
                <tr style='background-color:#f0f0f0'>
                    <td>Facturado</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><?php echo $TotalOtrosConceptos ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
        <?php }
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
                    <td>Facturado</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><?php echo $TotalDcto814 ?></td>
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
    <div id="divContenedorCompras" style="height: 500px">
        <div style="margin-top:10px">(Coeficiente de Apropiacion 0.5000)</div>
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
        $TotalBnGral['mostrar']=false;
        $TotalBnGral['Directo']=[];
        $TotalBnGral['Prorateable']=[];
        $TotalBnGral['Neto']=[];
        $TotalBnGral['Neto']['total']=0;
        $TotalBnGral['Neto']['0']=0;
        $TotalBnGral['Neto']['2.5']=0;
        $TotalBnGral['Neto']['50']=0;
        $TotalBnGral['Neto']['10.5']=0;
        $TotalBnGral['Neto']['21']=0;
        $TotalBnGral['Neto']['27']=0;
        $TotalBnGral['Directo']['total']=0;
        $TotalBnGral['Directo']['0']=0;
        $TotalBnGral['Directo']['2.5']=0;
        $TotalBnGral['Directo']['50']=0;
        $TotalBnGral['Directo']['10.5']=0;
        $TotalBnGral['Directo']['21']=0;
        $TotalBnGral['Directo']['27']=0;
        $TotalBnGral['Prorateable']['total']=0;
        $TotalBnGral['Prorateable']['0']=0;
        $TotalBnGral['Prorateable']['2.5']=0;
        $TotalBnGral['Prorateable']['50']=0;
        $TotalBnGral['Prorateable']['10.5']=0;
        $TotalBnGral['Prorateable']['21']=0;
        $TotalBnGral['Prorateable']['27']=0;
        $TotalLocaciones=[];
        $TotalLocaciones['mostrar']=false;
        $TotalLocaciones['Directo']=[];
        $TotalLocaciones['Prorateable']=[];
        $TotalLocaciones['Neto']=[];
        $TotalLocaciones['Neto']['total']=0;
        $TotalLocaciones['Neto']['0']=0;
        $TotalLocaciones['Neto']['2.5']=0;
        $TotalLocaciones['Neto']['50']=0;
        $TotalLocaciones['Neto']['10.5']=0;
        $TotalLocaciones['Neto']['21']=0;
        $TotalLocaciones['Neto']['27']=0;
        $TotalLocaciones['Directo']['total']=0;
        $TotalLocaciones['Directo']['0']=0;
        $TotalLocaciones['Directo']['2.5']=0;
        $TotalLocaciones['Directo']['50']=0;
        $TotalLocaciones['Directo']['10.5']=0;
        $TotalLocaciones['Directo']['21']=0;
        $TotalLocaciones['Directo']['27']=0;
        $TotalLocaciones['Prorateable']['total']=0;
        $TotalLocaciones['Prorateable']['0']=0;
        $TotalLocaciones['Prorateable']['2.5']=0;
        $TotalLocaciones['Prorateable']['50']=0;
        $TotalLocaciones['Prorateable']['10.5']=0;
        $TotalLocaciones['Prorateable']['21']=0;
        $TotalLocaciones['Prorateable']['27']=0;
        $TotalLocaciones['Directo']['total']=0;
        $TotalPresServ=[];
        $TotalPresServ['mostrar']=false;
        $TotalPresServ['Directo']=[];
        $TotalPresServ['Prorateable']=[];
        $TotalPresServ['Neto']=[];
        $TotalPresServ['Neto']['total']=0;
        $TotalPresServ['Neto']['0']=0;
        $TotalPresServ['Neto']['2.5']=0;
        $TotalPresServ['Neto']['50']=0;
        $TotalPresServ['Neto']['10.5']=0;
        $TotalPresServ['Neto']['21']=0;
        $TotalPresServ['Neto']['27']=0;
        $TotalPresServ['Directo']['total']=0;
        $TotalPresServ['Directo']['0']=0;
        $TotalPresServ['Directo']['2.5']=0;
        $TotalPresServ['Directo']['50']=0;
        $TotalPresServ['Directo']['10.5']=0;
        $TotalPresServ['Directo']['21']=0;
        $TotalPresServ['Directo']['27']=0;
        $TotalPresServ['Prorateable']['total']=0;
        $TotalPresServ['Prorateable']['0']=0;
        $TotalPresServ['Prorateable']['2.5']=0;
        $TotalPresServ['Prorateable']['50']=0;
        $TotalPresServ['Prorateable']['10.5']=0;
        $TotalPresServ['Prorateable']['21']=0;
        $TotalPresServ['Prorateable']['27']=0;
        $TotalBsUso=[];
        $TotalBsUso['mostrar']=false;
        $TotalBsUso['Directo']=[];
        $TotalBsUso['Prorateable']=[];
        $TotalBsUso['Neto']=[];
        $TotalBsUso['Neto']['total']=0;
        $TotalBsUso['Neto']['0']=0;
        $TotalBsUso['Neto']['2.5']=0;
        $TotalBsUso['Neto']['50']=0;
        $TotalBsUso['Neto']['10.5']=0;
        $TotalBsUso['Neto']['21']=0;
        $TotalBsUso['Neto']['27']=0;
        $TotalBsUso['Directo']['total']=0;
        $TotalBsUso['Directo']['0']=0;
        $TotalBsUso['Directo']['2.5']=0;
        $TotalBsUso['Directo']['50']=0;
        $TotalBsUso['Directo']['10.5']=0;
        $TotalBsUso['Directo']['21']=0;
        $TotalBsUso['Directo']['27']=0;
        $TotalBsUso['Prorateable']['total']=0;
        $TotalBsUso['Prorateable']['0']=0;
        $TotalBsUso['Prorateable']['2.5']=0;
        $TotalBsUso['Prorateable']['50']=0;
        $TotalBsUso['Prorateable']['10.5']=0;
        $TotalBsUso['Prorateable']['21']=0;
        $TotalBsUso['Prorateable']['27']=0;
        $TotalOtrosConceptos=[];
        $TotalOtrosConceptos['mostrar']=false;
        $TotalOtrosConceptos['Directo']=[];
        $TotalOtrosConceptos['Prorateable']=[];
        $TotalOtrosConceptos['Neto']=[];
        $TotalOtrosConceptos['Neto']['total']=0;
        $TotalOtrosConceptos['Neto']['0']=0;
        $TotalOtrosConceptos['Neto']['2.5']=0;
        $TotalOtrosConceptos['Neto']['50']=0;
        $TotalOtrosConceptos['Neto']['10.5']=0;
        $TotalOtrosConceptos['Neto']['21']=0;
        $TotalOtrosConceptos['Neto']['27']=0;
        $TotalOtrosConceptos['Directo']['total']=0;
        $TotalOtrosConceptos['Directo']['0']=0;
        $TotalOtrosConceptos['Directo']['2.5']=0;
        $TotalOtrosConceptos['Directo']['50']=0;
        $TotalOtrosConceptos['Directo']['10.5']=0;
        $TotalOtrosConceptos['Directo']['21']=0;
        $TotalOtrosConceptos['Directo']['27']=0;
        $TotalOtrosConceptos['Prorateable']['total']=0;
        $TotalOtrosConceptos['Prorateable']['0']=0;
        $TotalOtrosConceptos['Prorateable']['2.5']=0;
        $TotalOtrosConceptos['Prorateable']['50']=0;
        $TotalOtrosConceptos['Prorateable']['10.5']=0;
        $TotalOtrosConceptos['Prorateable']['21']=0;
        $TotalOtrosConceptos['Prorateable']['27']=0;
        $TotalDcto814=[];
        $TotalDcto814['mostrar']=false;
        $TotalDcto814['Directo']=[];
        $TotalDcto814['Prorateable']=[];
        $TotalDcto814['Neto']=[];
        $TotalDcto814['Neto']['total']=0;
        $TotalDcto814['Neto']['0']=0;
        $TotalDcto814['Neto']['2.5']=0;
        $TotalDcto814['Neto']['50']=0;
        $TotalDcto814['Neto']['10.5']=0;
        $TotalDcto814['Neto']['21']=0;
        $TotalDcto814['Neto']['27']=0;
        $TotalDcto814['Directo']['total']=0;
        $TotalDcto814['Directo']['0']=0;
        $TotalDcto814['Directo']['2.5']=0;
        $TotalDcto814['Directo']['50']=0;
        $TotalDcto814['Directo']['10.5']=0;
        $TotalDcto814['Directo']['21']=0;
        $TotalDcto814['Directo']['27']=0;
        $TotalDcto814['Prorateable']['total']=0;
        $TotalDcto814['Prorateable']['0']=0;
        $TotalDcto814['Prorateable']['2.5']=0;
        $TotalDcto814['Prorateable']['50']=0;
        $TotalDcto814['Prorateable']['10.5']=0;
        $TotalDcto814['Prorateable']['21']=0;
        $TotalDcto814['Prorateable']['27']=0;

        $TotalNoComputable = 0;
        foreach ($compras as $compra){
            $suma = 1;
            if($compra['Compra']['tipocredito'] == 'Restitucion credito fiscal')
            {
                //$suma = -1;
                break;
            }
            if ($compra['Compra']['imputacion'] == 'Bs en Gral')
            {
                $TotalBnGral['mostrar']=true;
                $TotalBnGral['Neto']['total'] += $compra[0]['neto']*$suma;
                Debugger::dump($compra['Compra']['alicuota']);
                if($compra['Compra']['alicuota']=='0'){
                    $TotalBnGral['Neto']['0'] += $compra[0]['neto']*$suma;
                    Debugger::dump($TotalBnGral['Neto']['0']);
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
                    <td><?php echo $TotalBnGral['Neto']['0'] ?></td>
                    <td><?php echo $TotalBnGral['Directo']['0'] ?></td>
                    <td><?php echo $TotalBnGral['Prorateable']['0'] ?></td>
                    <td><?php echo $TotalBnGral['Prorateable']['0'] * 0.5000 ?></td>
                    <td><?php echo $TotalBnGral['Directo']['0'] + $TotalBnGral['Prorateable']['0'] ?></td>
                    <td><?php echo $TotalBnGral['Directo']['0'] + ($TotalBnGral['Prorateable']['0'] * 0.5000) ?></td>
                </tr>
                <?php }
                if($TotalBnGral['Neto']['2.5']+$TotalBnGral['Directo']['2.5']+$TotalBnGral['Prorateable']['2.5'] != 0) { ?>
                    <tr>
                        <td><?php echo "2.5%" ?></td>
                        <td><?php echo $TotalBnGral['Neto']['2.5'] ?></td>
                        <td><?php echo $TotalBnGral['Directo']['2.5'] ?></td>
                        <td><?php echo $TotalBnGral['Prorateable']['2.5'] ?></td>
                        <td><?php echo $TotalBnGral['Prorateable']['2.5'] * 0.5000 ?></td>
                        <td><?php echo $TotalBnGral['Directo']['2.5'] + $TotalBnGral['Prorateable']['2.5'] ?></td>
                        <td><?php echo $TotalBnGral['Directo']['2.5'] + ($TotalBnGral['Prorateable']['2.5'] * 0.5000) ?></td>
                    </tr>
                <?php
                }
                if($TotalBnGral['Neto']['50']+$TotalBnGral['Directo']['50']+$TotalBnGral['Prorateable']['50'] != 0){ ?>
                <tr>
                    <td><?php echo "5.0%" ?></td>
                    <td><?php echo $TotalBnGral['Neto']['50'] ?></td>
                    <td><?php echo $TotalBnGral['Directo']['50'] ?></td>
                    <td><?php echo $TotalBnGral['Prorateable']['50'] ?></td>
                    <td><?php echo $TotalBnGral['Prorateable']['50'] * 0.5000 ?></td>
                    <td><?php echo $TotalBnGral['Directo']['50'] + $TotalBnGral['Prorateable']['50'] ?></td>
                    <td><?php echo $TotalBnGral['Directo']['50'] + ($TotalBnGral['Prorateable']['50'] * 0.5000) ?></td>
                </tr>
                    <?php
                }
                if($TotalBnGral['Neto']['10.5']+$TotalBnGral['Directo']['10.5']+$TotalBnGral['Prorateable']['10.5'] != 0){ ?>
                <tr>
                    <td><?php echo "10.5%" ?></td>
                    <td><?php echo $TotalBnGral['Neto']['10.5'] ?></td>
                    <td><?php echo $TotalBnGral['Directo']['10.5'] ?></td>
                    <td><?php echo $TotalBnGral['Prorateable']['10.5'] ?></td>
                    <td><?php echo $TotalBnGral['Prorateable']['10.5'] * 0.5000 ?></td>
                    <td><?php echo $TotalBnGral['Directo']['10.5'] + $TotalBnGral['Prorateable']['10.5'] ?></td>
                    <td><?php echo $TotalBnGral['Directo']['10.5'] + ($TotalBnGral['Prorateable']['10.5'] * 0.5000) ?></td>
                </tr>
                    <?php
                }
                if($TotalBnGral['Neto']['21']+$TotalBnGral['Directo']['21']+$TotalBnGral['Prorateable']['21'] != 0){ ?>
                <tr>
                    <td><?php echo "21%" ?></td>
                    <td><?php echo $TotalBnGral['Neto']['21'] ?></td>
                    <td><?php echo $TotalBnGral['Directo']['21'] ?></td>
                    <td><?php echo $TotalBnGral['Prorateable']['21'] ?></td>
                    <td><?php echo $TotalBnGral['Prorateable']['21'] * 0.5000 ?></td>
                    <td><?php echo $TotalBnGral['Directo']['21'] + $TotalBnGral['Prorateable']['21'] ?></td>
                    <td><?php echo $TotalBnGral['Directo']['21'] + ($TotalBnGral['Prorateable']['21'] * 0.5000) ?></td>
                </tr>
                    <?php
                }
                if($TotalBnGral['Neto']['27']+$TotalBnGral['Directo']['27']+$TotalBnGral['Prorateable']['27'] != 0){ ?>
                <tr>
                    <td><?php echo "27%" ?></td>
                    <td><?php echo $TotalBnGral['Neto']['27'] ?></td>
                    <td><?php echo $TotalBnGral['Directo']['27'] ?></td>
                    <td><?php echo $TotalBnGral['Prorateable']['27'] ?></td>
                    <td><?php echo $TotalBnGral['Prorateable']['27'] * 0.5000 ?></td>
                    <td><?php echo $TotalBnGral['Directo']['27'] + $TotalBnGral['Prorateable']['27'] ?></td>
                    <td><?php echo $TotalBnGral['Directo']['27'] + ($TotalBnGral['Prorateable']['27'] * 0.5000) ?></td>
                </tr>
                    <?php
                }
                if($TotalBnGral['Neto']['total']+$TotalBnGral['Directo']['total']+$TotalBnGral['Prorateable']['total'] != 0) { ?>
                    <tr>
                        <td>Totales</td>
                        <td><?php echo $TotalBnGral['Neto']['total'] ?></td>
                        <td><?php echo $TotalBnGral['Directo']['total'] ?></td>
                        <td><?php echo $TotalBnGral['Prorateable']['total'] ?></td>
                        <td><?php echo $TotalBnGral['Prorateable']['total'] * 0.5000 ?></td>
                        <td><?php echo $TotalBnGral['Directo']['total'] + $TotalBnGral['Prorateable']['total'] ?></td>
                        <td><?php echo $TotalBnGral['Directo']['total'] + ($TotalBnGral['Prorateable']['total'] * 0.5000) ?></td>
                    </tr>

                    <?php
                }
            }
            $TotalNoComputable += ($TotalBnGral['Directo']['total'] + $TotalBnGral['Prorateable']['total'])-$TotalBnGral['Directo']['total'] + ($TotalBnGral['Prorateable']['total'] * 0.5000)?>
            <?php $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalBnGral['Directo']['total'] + ($TotalBnGral['Prorateable']['total'] * 0.5000));
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
                        <td><?php echo $TotalLocaciones['Neto']['0'] ?></td>
                        <td><?php echo $TotalLocaciones['Directo']['0'] ?></td>
                        <td><?php echo $TotalLocaciones['Prorateable']['0'] ?></td>
                        <td><?php echo $TotalLocaciones['Prorateable']['0'] * 0.5000 ?></td>
                        <td><?php echo $TotalLocaciones['Directo']['0'] + $TotalLocaciones['Prorateable']['0'] ?></td>
                        <td><?php echo $TotalLocaciones['Directo']['0'] + ($TotalLocaciones['Prorateable']['0'] * 0.5000) ?></td>
                    </tr>
                    <?php
                }
                if($TotalLocaciones['Neto']['2.5']+$TotalLocaciones['Directo']['2.5']+$TotalLocaciones['Prorateable']['2.5'] != 0) { ?>
                <tr>
                    <td><?php echo "2.5%" ?></td>
                    <td><?php echo $TotalLocaciones['Neto']['2.5'] ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['2.5'] ?></td>
                    <td><?php echo $TotalLocaciones['Prorateable']['2.5'] ?></td>
                    <td><?php echo $TotalLocaciones['Prorateable']['2.5'] * 0.5000 ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['2.5'] + $TotalLocaciones['Prorateable']['2.5'] ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['2.5'] + ($TotalLocaciones['Prorateable']['2.5'] * 0.5000) ?></td>
                </tr>
                    <?php
                }
                if($TotalLocaciones['Neto']['50']+$TotalLocaciones['Directo']['50']+$TotalLocaciones['Prorateable']['50'] != 0) { ?>
                <tr>
                    <td><?php echo "5.0%" ?></td>
                    <td><?php echo $TotalLocaciones['Neto']['50'] ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['50'] ?></td>
                    <td><?php echo $TotalLocaciones['Prorateable']['50'] ?></td>
                    <td><?php echo $TotalLocaciones['Prorateable']['50'] * 0.5000 ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['50'] + $TotalLocaciones['Prorateable']['50'] ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['50'] + ($TotalLocaciones['Prorateable']['50'] * 0.5000) ?></td>
                </tr>
                    <?php
                }
                if($TotalLocaciones['Neto']['10.5']+$TotalLocaciones['Directo']['10.5']+$TotalLocaciones['Prorateable']['10.5'] != 0) { ?>
                <tr>
                    <td><?php echo "10.5%" ?></td>
                    <td><?php echo $TotalLocaciones['Neto']['10.5'] ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['10.5'] ?></td>
                    <td><?php echo $TotalLocaciones['Prorateable']['10.5'] ?></td>
                    <td><?php echo $TotalLocaciones['Prorateable']['10.5'] * 0.5000 ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['10.5'] + $TotalLocaciones['Prorateable']['10.5'] ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['10.5'] + ($TotalLocaciones['Prorateable']['10.5'] * 0.5000) ?></td>
                </tr>
                    <?php
                }
                if($TotalLocaciones['Neto']['21']+$TotalLocaciones['Directo']['21']+$TotalLocaciones['Prorateable']['21'] != 0) { ?>
                <tr>
                    <td><?php echo "21%" ?></td>
                    <td><?php echo $TotalLocaciones['Neto']['21'] ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['21'] ?></td>
                    <td><?php echo $TotalLocaciones['Prorateable']['21'] ?></td>
                    <td><?php echo $TotalLocaciones['Prorateable']['21'] * 0.5000 ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['21'] + $TotalLocaciones['Prorateable']['21'] ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['21'] + ($TotalLocaciones['Prorateable']['21'] * 0.5000) ?></td>
                </tr>
                    <?php
                }
                if($TotalLocaciones['Neto']['27']+$TotalLocaciones['Directo']['27']+$TotalLocaciones['Prorateable']['27'] != 0) { ?>
                <tr>
                    <td><?php echo "27%" ?></td>
                    <td><?php echo $TotalLocaciones['Neto']['27'] ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['27'] ?></td>
                    <td><?php echo $TotalLocaciones['Prorateable']['27'] ?></td>
                    <td><?php echo $TotalLocaciones['Prorateable']['27'] * 0.5000 ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['27'] + $TotalLocaciones['Prorateable']['27'] ?></td>
                    <td><?php echo $TotalLocaciones['Directo']['27'] + ($TotalLocaciones['Prorateable']['27'] * 0.5000) ?></td>
                </tr>
                    <?php
                }
                if($TotalLocaciones['Neto']['total']+$TotalLocaciones['Directo']['total']+$TotalLocaciones['Prorateable']['total'] != 0) { ?>
                    <tr>
                        <td>Totales</td>
                        <td><?php echo $TotalLocaciones['Neto']['total'] ?></td>
                        <td><?php echo $TotalLocaciones['Directo']['total'] ?></td>
                        <td><?php echo $TotalLocaciones['Prorateable']['total'] ?></td>
                        <td><?php echo $TotalLocaciones['Prorateable']['total'] * 0.5000 ?></td>
                        <td><?php echo $TotalLocaciones['Directo']['total'] + $TotalLocaciones['Prorateable']['total'] ?></td>
                        <td><?php echo $TotalLocaciones['Directo']['total'] + ($TotalLocaciones['Prorateable']['total'] * 0.5000) ?></td>
                    </tr>
                    <?php
                }
            }
            $TotalNoComputable += ($TotalLocaciones['Directo']['total'] + $TotalLocaciones['Prorateable']['total'])-$TotalLocaciones['Directo']['total'] + ($TotalLocaciones['Prorateable']['total'] * 0.5000)?>
            <?php $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalLocaciones['Directo']['total'] + ($TotalLocaciones['Prorateable']['total'] * 0.5000));
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
                        <td><?php echo $TotalPresServ['Neto']['0'] ?></td>
                        <td><?php echo $TotalPresServ['Directo']['0'] ?></td>
                        <td><?php echo $TotalPresServ['Prorateable']['0'] ?></td>
                        <td><?php echo $TotalPresServ['Prorateable']['0'] * 0.5000 ?></td>
                        <td><?php echo $TotalPresServ['Directo']['0'] + $TotalPresServ['Prorateable']['0'] ?></td>
                        <td><?php echo $TotalPresServ['Directo']['0'] + ($TotalPresServ['Prorateable']['0'] * 0.5000) ?></td>
                    </tr>
                    <?php
                }
                if($TotalPresServ['Neto']['2.5']+$TotalPresServ['Directo']['2.5']+$TotalPresServ['Prorateable']['2.5'] != 0) { ?>
                    <tr>
                        <td><?php echo "2.5%" ?></td>
                        <td><?php echo $TotalPresServ['Neto']['2.5'] ?></td>
                        <td><?php echo $TotalPresServ['Directo']['2.5'] ?></td>
                        <td><?php echo $TotalPresServ['Prorateable']['2.5'] ?></td>
                        <td><?php echo $TotalPresServ['Prorateable']['2.5'] * 0.5000 ?></td>
                        <td><?php echo $TotalPresServ['Directo']['2.5'] + $TotalPresServ['Prorateable']['2.5'] ?></td>
                        <td><?php echo $TotalPresServ['Directo']['2.5'] + ($TotalPresServ['Prorateable']['2.5'] * 0.5000) ?></td>
                    </tr>
                    <?php
                }
                if($TotalPresServ['Neto']['50']+$TotalPresServ['Directo']['50']+$TotalPresServ['Prorateable']['50'] != 0) { ?>
                    <tr>
                        <td><?php echo "5.0%" ?></td>
                        <td><?php echo $TotalPresServ['Neto']['50'] ?></td>
                        <td><?php echo $TotalPresServ['Directo']['50'] ?></td>
                        <td><?php echo $TotalPresServ['Prorateable']['50'] ?></td>
                        <td><?php echo $TotalPresServ['Prorateable']['50'] * 0.5000 ?></td>
                        <td><?php echo $TotalPresServ['Directo']['50'] + $TotalPresServ['Prorateable']['50'] ?></td>
                        <td><?php echo $TotalPresServ['Directo']['50'] + ($TotalPresServ['Prorateable']['50'] * 0.5000) ?></td>
                    </tr>
                    <?php
                }
                if($TotalPresServ['Neto']['10.5']+$TotalPresServ['Directo']['10.5']+$TotalPresServ['Prorateable']['10.5'] != 0) { ?>
                    <tr>
                        <td><?php echo "10.5%" ?></td>
                        <td><?php echo $TotalPresServ['Neto']['10.5'] ?></td>
                        <td><?php echo $TotalPresServ['Directo']['10.5'] ?></td>
                        <td><?php echo $TotalPresServ['Prorateable']['10.5'] ?></td>
                        <td><?php echo $TotalPresServ['Prorateable']['10.5'] * 0.5000 ?></td>
                        <td><?php echo $TotalPresServ['Directo']['10.5'] + $TotalPresServ['Prorateable']['10.5'] ?></td>
                        <td><?php echo $TotalPresServ['Directo']['10.5'] + ($TotalPresServ['Prorateable']['10.5'] * 0.5000) ?></td>
                    </tr>
                    <?php
                }
                if($TotalPresServ['Neto']['21']+$TotalPresServ['Directo']['21']+$TotalPresServ['Prorateable']['21'] != 0) { ?>
                    <tr>
                        <td><?php echo "21%" ?></td>
                        <td><?php echo $TotalPresServ['Neto']['21'] ?></td>
                        <td><?php echo $TotalPresServ['Directo']['21'] ?></td>
                        <td><?php echo $TotalPresServ['Prorateable']['21'] ?></td>
                        <td><?php echo $TotalPresServ['Prorateable']['21'] * 0.5000 ?></td>
                        <td><?php echo $TotalPresServ['Directo']['21'] + $TotalPresServ['Prorateable']['21'] ?></td>
                        <td><?php echo $TotalPresServ['Directo']['21'] + ($TotalPresServ['Prorateable']['21'] * 0.5000) ?></td>
                    </tr>
                    <?php
                }
                if($TotalPresServ['Neto']['27']+$TotalPresServ['Directo']['27']+$TotalPresServ['Prorateable']['27'] != 0) { ?>
                    <tr>
                        <td><?php echo "27%" ?></td>
                        <td><?php echo $TotalPresServ['Neto']['27'] ?></td>
                        <td><?php echo $TotalPresServ['Directo']['27'] ?></td>
                        <td><?php echo $TotalPresServ['Prorateable']['27'] ?></td>
                        <td><?php echo $TotalPresServ['Prorateable']['27'] * 0.5000 ?></td>
                        <td><?php echo $TotalPresServ['Directo']['27'] + $TotalPresServ['Prorateable']['27'] ?></td>
                        <td><?php echo $TotalPresServ['Directo']['27'] + ($TotalPresServ['Prorateable']['27'] * 0.5000) ?></td>
                    </tr>
                    <?php
                }
                if($TotalPresServ['Neto']['total']+$TotalPresServ['Directo']['total']+$TotalPresServ['Prorateable']['total'] != 0) { ?>
                    <tr>
                        <td>Totales</td>
                        <td><?php echo $TotalPresServ['Neto']['total'] ?></td>
                        <td><?php echo $TotalPresServ['Directo']['total'] ?></td>
                        <td><?php echo $TotalPresServ['Prorateable']['total'] ?></td>
                        <td><?php echo $TotalPresServ['Prorateable']['total'] * 0.5000 ?></td>
                        <td><?php echo $TotalPresServ['Directo']['total'] + $TotalPresServ['Prorateable']['total'] ?></td>
                        <td><?php echo $TotalPresServ['Directo']['total'] + ($TotalPresServ['Prorateable']['total'] * 0.5000) ?></td>
                    </tr>
                    <?php
                }
            }
            $TotalNoComputable += ($TotalPresServ['Directo']['total'] + $TotalPresServ['Prorateable']['total'])-$TotalPresServ['Directo']['total'] + ($TotalPresServ['Prorateable']['total'] * 0.5000)?>
            <?php $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalPresServ['Directo']['total'] + ($TotalPresServ['Prorateable']['total'] * 0.5000));
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
                        <td><?php echo $TotalBsUso['Neto']['0'] ?></td>
                        <td><?php echo $TotalBsUso['Directo']['0'] ?></td>
                        <td><?php echo $TotalBsUso['Prorateable']['0'] ?></td>
                        <td><?php echo $TotalBsUso['Prorateable']['0'] * 0.5000 ?></td>
                        <td><?php echo $TotalBsUso['Directo']['0'] + $TotalBsUso['Prorateable']['0'] ?></td>
                        <td><?php echo $TotalBsUso['Directo']['0'] + ($TotalBsUso['Prorateable']['0'] * 0.5000) ?></td>
                    </tr>
                    <?php
                }
                if($TotalBsUso['Neto']['2.5']+$TotalBsUso['Directo']['2.5']+$TotalBsUso['Prorateable']['2.5'] != 0) { ?>
                <tr>
                    <td><?php echo "2.5%" ?></td>
                    <td><?php echo $TotalBsUso['Neto']['2.5'] ?></td>
                    <td><?php echo $TotalBsUso['Directo']['2.5'] ?></td>
                    <td><?php echo $TotalBsUso['Prorateable']['2.5'] ?></td>
                    <td><?php echo $TotalBsUso['Prorateable']['2.5'] * 0.5000 ?></td>
                    <td><?php echo $TotalBsUso['Directo']['2.5'] + $TotalBsUso['Prorateable']['2.5'] ?></td>
                    <td><?php echo $TotalBsUso['Directo']['2.5'] + ($TotalBsUso['Prorateable']['2.5'] * 0.5000) ?></td>
                </tr>
                    <?php
                }
                if($TotalBsUso['Neto']['50']+$TotalBsUso['Directo']['50']+$TotalBsUso['Prorateable']['50'] != 0) { ?>
                <tr>
                    <td><?php echo "5.0%" ?></td>
                    <td><?php echo $TotalBsUso['Neto']['27'] ?></td>
                    <td><?php echo $TotalBsUso['Directo']['50'] ?></td>
                    <td><?php echo $TotalBsUso['Prorateable']['50'] ?></td>
                    <td><?php echo $TotalBsUso['Prorateable']['50'] * 0.5000 ?></td>
                    <td><?php echo $TotalBsUso['Directo']['50'] + $TotalBsUso['Prorateable']['50'] ?></td>
                    <td><?php echo $TotalBsUso['Directo']['50'] + ($TotalBsUso['Prorateable']['50'] * 0.5000) ?></td>
                </tr>
                    <?php
                }
                if($TotalBsUso['Neto']['10.5']+$TotalBsUso['Directo']['10.5']+$TotalBsUso['Prorateable']['10.5'] != 0) { ?>
                <tr>
                    <td><?php echo "10.5%" ?></td>
                    <td><?php echo $TotalBsUso['Neto']['10.5'] ?></td>
                    <td><?php echo $TotalBsUso['Directo']['10.5'] ?></td>
                    <td><?php echo $TotalBsUso['Prorateable']['10.5'] ?></td>
                    <td><?php echo $TotalBsUso['Prorateable']['10.5'] * 0.5000 ?></td>
                    <td><?php echo $TotalBsUso['Directo']['10.5'] + $TotalBsUso['Prorateable']['10.5'] ?></td>
                    <td><?php echo $TotalBsUso['Directo']['10.5'] + ($TotalBsUso['Prorateable']['10.5'] * 0.5000) ?></td>
                </tr>
                    <?php
                }
                if($TotalBsUso['Neto']['21']+$TotalBsUso['Directo']['21']+$TotalBsUso['Prorateable']['21'] != 0) { ?>
                <tr>
                    <td><?php echo "21%" ?></td>
                    <td><?php echo $TotalBsUso['Neto']['21'] ?></td>
                    <td><?php echo $TotalBsUso['Directo']['21'] ?></td>
                    <td><?php echo $TotalBsUso['Prorateable']['21'] ?></td>
                    <td><?php echo $TotalBsUso['Prorateable']['21'] * 0.5000 ?></td>
                    <td><?php echo $TotalBsUso['Directo']['21'] + $TotalBsUso['Prorateable']['21'] ?></td>
                    <td><?php echo $TotalBsUso['Directo']['21'] + ($TotalBsUso['Prorateable']['21'] * 0.5000) ?></td>
                </tr>
                    <?php
                }
                if($TotalBsUso['Neto']['27']+$TotalBsUso['Directo']['27']+$TotalBsUso['Prorateable']['27'] != 0) { ?>
                <tr>
                    <td><?php echo "27%" ?></td>
                    <td><?php echo $TotalBsUso['Neto']['27'] ?></td>
                    <td><?php echo $TotalBsUso['Directo']['27'] ?></td>
                    <td><?php echo $TotalBsUso['Prorateable']['27'] ?></td>
                    <td><?php echo $TotalBsUso['Prorateable']['27'] * 0.5000 ?></td>
                    <td><?php echo $TotalBsUso['Directo']['27'] + $TotalBsUso['Prorateable']['27'] ?></td>
                    <td><?php echo $TotalBsUso['Directo']['27'] + ($TotalBsUso['Prorateable']['27'] * 0.5000) ?></td>
                </tr>
                    <?php
                }
                if($TotalBsUso['Neto']['total']+$TotalBsUso['Directo']['total']+$TotalBsUso['Prorateable']['total'] != 0) { ?>
                    <tr>
                        <td>Totales</td>
                        <td><?php echo $TotalBsUso['Neto']['total'] ?></td>
                        <td><?php echo $TotalBsUso['Directo']['total'] ?></td>
                        <td><?php echo $TotalBsUso['Prorateable']['total'] ?></td>
                        <td><?php echo $TotalBsUso['Prorateable']['total'] * 0.5000 ?></td>
                        <td><?php echo $TotalBsUso['Directo']['total'] + $TotalBsUso['Prorateable']['total'] ?></td>
                        <td><?php echo $TotalBsUso['Directo']['total'] + ($TotalBsUso['Prorateable']['total'] * 0.5000) ?></td>
                    </tr>
                    <?php
                }
            }
            $TotalNoComputable += ($TotalBsUso['Directo']['total'] + $TotalBsUso['Prorateable']['total'])-$TotalBsUso['Directo']['total'] + ($TotalBsUso['Prorateable']['total'] * 0.5000)?>
            <?php $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalBsUso['Directo']['total'] + ($TotalBsUso['Prorateable']['total'] * 0.5000));
            if($TotalOtrosConceptos['mostrar']) {
                ?>
                <tr>
                    <td colspan="7" style='background-color:#87cfeb'>
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
                        <td><?php echo $TotalOtrosConceptos['Neto']['total'] ?></td>
                        <td><?php echo $TotalOtrosConceptos['Directo']['0'] ?></td>
                        <td><?php echo $TotalOtrosConceptos['Prorateable']['0'] ?></td>
                        <td><?php echo $TotalOtrosConceptos['Prorateable']['0'] * 0.5000 ?></td>
                        <td><?php echo $TotalOtrosConceptos['Directo']['0'] + $TotalOtrosConceptos['Prorateable']['0'] ?></td>
                        <td><?php echo $TotalOtrosConceptos['Directo']['0'] + ($TotalOtrosConceptos['Prorateable']['0'] * 0.5000) ?></td>
                    </tr>
                    <?php
                }
                if($TotalOtrosConceptos['Neto']['2.5']+$TotalOtrosConceptos['Directo']['2.5']+$TotalOtrosConceptos['Prorateable']['2.5'] != 0) { ?>
                <tr>
                    <td><?php echo "2.5%" ?></td>
                    <td><?php echo $TotalOtrosConceptos['Neto']['2.5'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['2.5'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Prorateable']['2.5'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Prorateable']['2.5'] * 0.5000 ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['2.5'] + $TotalOtrosConceptos['Prorateable']['2.5'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['2.5'] + ($TotalOtrosConceptos['Prorateable']['2.5'] * 0.5000) ?></td>
                </tr>
                 <?php
                }
                if($TotalOtrosConceptos['Neto']['50']+$TotalOtrosConceptos['Directo']['50']+$TotalOtrosConceptos['Prorateable']['50'] != 0) { ?>
                <tr>
                    <td><?php echo "5.0%" ?></td>
                    <td><?php echo $TotalOtrosConceptos['Neto']['50'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['50'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Prorateable']['50'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Prorateable']['50'] * 0.5000 ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['50'] + $TotalOtrosConceptos['Prorateable']['50'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['50'] + ($TotalOtrosConceptos['Prorateable']['50'] * 0.5000) ?></td>
                </tr>
                 <?php
                }
                if($TotalOtrosConceptos['Neto']['10.5']+$TotalOtrosConceptos['Directo']['10.5']+$TotalOtrosConceptos['Prorateable']['10.5'] != 0) { ?>
                <tr>
                    <td><?php echo "10.5%" ?></td>
                    <td><?php echo $TotalOtrosConceptos['Neto']['10.5'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['10.5'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Prorateable']['10.5'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Prorateable']['10.5'] * 0.5000 ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['10.5'] + $TotalOtrosConceptos['Prorateable']['10.5'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['10.5'] + ($TotalOtrosConceptos['Prorateable']['10.5'] * 0.5000) ?></td>
                </tr>
                 <?php
                }
                if($TotalOtrosConceptos['Neto']['21']+$TotalOtrosConceptos['Directo']['21']+$TotalOtrosConceptos['Prorateable']['21'] != 0) { ?>
                <tr>
                    <td><?php echo "21%" ?></td>
                    <td><?php echo $TotalOtrosConceptos['Neto']['21'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['21'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Prorateable']['21'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Prorateable']['21'] * 0.5000 ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['21'] + $TotalOtrosConceptos['Prorateable']['21'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['21'] + ($TotalOtrosConceptos['Prorateable']['21'] * 0.5000) ?></td>
                </tr>
                 <?php
                }
                if($TotalOtrosConceptos['Neto']['27']+$TotalOtrosConceptos['Directo']['27']+$TotalOtrosConceptos['Prorateable']['27'] != 0) { ?>
                <tr>
                    <td><?php echo "27%" ?></td>
                    <td><?php echo $TotalOtrosConceptos['Neto']['27'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['27'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Prorateable']['27'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Prorateable']['27'] * 0.5000 ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['27'] + $TotalOtrosConceptos['Prorateable']['27'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['27'] + ($TotalOtrosConceptos['Prorateable']['27'] * 0.5000) ?></td>
                </tr>
                 <?php
                }
                if($TotalOtrosConceptos['Neto']['total']+$TotalOtrosConceptos['Directo']['total']+$TotalOtrosConceptos['Prorateable']['total'] != 0) { ?>
                <tr>
                    <td>Totales</td>
                    <td><?php echo $TotalOtrosConceptos['Neto']['total'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['total'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Prorateable']['total'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Prorateable']['total'] * 0.5000 ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['total'] + $TotalOtrosConceptos['Prorateable']['total'] ?></td>
                    <td><?php echo $TotalOtrosConceptos['Directo']['total'] + ($TotalOtrosConceptos['Prorateable']['total'] * 0.5000) ?></td>
                </tr>
                <?php
                }
            }
            $TotalNoComputable += ($TotalOtrosConceptos['Directo']['total'] + $TotalOtrosConceptos['Prorateable']['total'])-$TotalOtrosConceptos['Directo']['total'] + ($TotalOtrosConceptos['Prorateable']['total'] * 0.5000)?>
            <?php $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalOtrosConceptos['Directo']['total'] + ($TotalOtrosConceptos['Prorateable']['total'] * 0.5000));
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
                            echo $TotalDcto814['Directo']['total'] ?></td>
                        <td><?php echo $TotalDcto814['Prorateable']['total'] ?></td>
                        <td><?php echo $TotalDcto814['Prorateable']['total'] * 0.5000 ?></td>
                        <td><?php echo $TotalDcto814['Directo']['total'] + $TotalDcto814['Prorateable']['total'] ?></td>
                        <td colspan="2"><?php echo $TotalDcto814['Directo']['total'] + ($TotalDcto814['Prorateable']['total'] * 0.5000) ?></td>
                    </tr>
                    <?php
                }
            }

            $TotalNoComputable += ($TotalDcto814['Directo']['total'] + $TotalDcto814['Prorateable']['total'])-$TotalDcto814['Directo']['total'] + ($TotalDcto814['Prorateable']['total'] * 0.5000);
            echo $this->Form->input('totalnocomputable',
                [
                    'type'=>'hidden',
                    'value'=>$TotalNoComputable
                ]
            );
             $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalDcto814['Directo']['total'] + ($TotalDcto814['Prorateable']['total'] * 0.5000)); ?>
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
                if ($venta['Venta']['tipodebito'] == 'Restitucion debito fiscal') {
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
                      <td>'.$TotalAlicuota2_5.'</td>
                      <td>'.$TotalAlicuota2_5 * 0.025.'</td>
                      <td>'.$TotalAlicuota2_5 * 0.025.'</td>
                      <td>0</td>
                      </tr>
                ';
            }
            if($Alicuota5_0)
            {
                echo '<tr>
                      <td>5.0</td>
                      <td>'.$TotalAlicuota5_0.'</td>
                      <td>'.$TotalAlicuota5_0 * 0.05.'</td>
                      <td>'.$TotalAlicuota5_0 * 0.05.'</td>
                      <td>0</td>
                      </tr>
                ';
            }
            if($Alicuota10_5)
            {
                echo '<tr>
                      <td>10.5</td>
                      <td>'.$TotalAlicuota10_5.'</td>
                      <td>'.$TotalAlicuota10_5 * 0.105.'</td>
                      <td>'.$TotalAlicuota10_5 * 0.105.'</td>
                      <td>0</td>
                      </tr>
                ';
            }
            if($Alicuota21_0)
            {
                echo '<tr>
                      <td>21.0</td>
                      <td>'.$TotalAlicuota21_0.'</td>
                      <td>'.$TotalAlicuota21_0 * 0.21.'</td>
                      <td>'.$TotalAlicuota21_0 * 0.21.'</td>
                      <td>0</td>
                      </tr>
                ';
            }
            if($Alicuota27_0)
            {
                echo '<tr>
                      <td>27.0</td>
                      <td>'.$TotalAlicuota27_0.'</td>
                      <td>'.$TotalAlicuota27_0 * 0.27.'</td>
                      <td>'.$TotalAlicuota27_0 * 0.27.'</td>
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
                      <td>'.$TotalNeto.'</td>
                      <td>'.$TotalDebitoFiscal.'</td>
                      <td>'.$TotalDebitoFiscal.'</td>
                      <td>0</td>
                    </tr>
                ';
                $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + $TotalDebitoFiscal;
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

                if ($venta['Venta']['tipodebito'] == 'Restitucion debito fiscal') {
                    if ($venta['Venta']['condicioniva'] == 'monotributista') {
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
                      <td>'.$TotalAlicuota2_5 * 1.025.'</td>
                      <td>'.$TotalAlicuota2_5 * 0.025 .'</td>
                      <td></td>
                      <td></td>
                      </tr>
                ';
            }
            if($Alicuota5_0)
            {
                echo '<tr>
                      <td>5.0</td>
                      <td>'.$TotalAlicuota5_0 * 1.05.'</td>
                      <td>'.$TotalAlicuota5_0 * 0.05.'</td>
                      <td></td>
                      <td></td>
                      </tr>
                ';
            }
            if($Alicuota10_5)
            {
                echo '<tr>
                      <td>10.5</td>
                      <td>'.$TotalAlicuota10_5.'</td>
                      <td>'.$TotalAlicuota10_5 * 0.105.'</td>
                      <td></td>
                      <td></td>
                      </tr>
                ';
            }
            if($Alicuota21_0)
            {
                echo '<tr>
                      <td>21.0</td>
                      <td>'.$TotalAlicuota21_0 * 1.21.'</td>
                      <td>'.$TotalAlicuota21_0 * 0.21.'</td>
                      <td></td>
                      <td></td>
                      </tr>
                ';
            }
            if($Alicuota27_0)
            {
                echo '<tr>
                      <td>27.0</td>
                      <td>'.$TotalAlicuota27_0 * 1.27.'</td>
                      <td>'.$TotalAlicuota27_0 * 0.27.'</td>
                      <td></td>
                      <td></td>
                      </tr>
                ';
            }
            if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
            {
                $TotalNeto = $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                $TotalDebitoFiscal = ($TotalAlicuota2_5 * 0.025) + ($TotalAlicuota5_0 * 0.05) + ($TotalAlicuota10_5 * 0.105) + ($TotalAlicuota21_0 * 0.21) + ($TotalAlicuota27_0 * 0.27);
                echo '<tr>
                      <td>Totales</td>
                      <td>'.$TotalNeto.'</td>
                      <td>'.$TotalDebitoFiscal.'</td>
                      <td></td>
                      <td></td>
                    </tr>
                ';
                $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + $TotalDebitoFiscal;
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

                if ($venta['Venta']['tipodebito'] == 'Restitucion debito fiscal') {
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
                      <td>'.$TotalAlicuota2_5 * 1.025.'</td>
                      <td>'.$TotalAlicuota2_5 * 0.025.'</td>
                      <td></td>
                      <td></td>
                      </tr>
                ';
            }
            if($Alicuota5_0)
            {
                echo '<tr>
                      <td>5.0</td>
                      <td>'.$TotalAlicuota5_0 * 1.05.'</td>
                      <td>'.$TotalAlicuota5_0 * 0.05.'</td>
                      <td></td>
                      <td></td>
                      </tr>
                ';
            }
            if($Alicuota10_5)
            {
                echo '<tr>
                      <td>10.5</td>
                      <td>'.$TotalAlicuota10_5 * 1.105.'</td>
                      <td>'.$TotalAlicuota10_5 * 0.105.'</td>
                      <td></td>
                      <td></td>
                      </tr>
                ';
            }
            if($Alicuota21_0)
            {
                echo '<tr>
                      <td>21.0</td>
                      <td>'.$TotalAlicuota21_0 * 1.21.'</td>
                      <td>'.$TotalAlicuota21_0 * 0.21.'</td>
                      <td></td>
                      <td></td>
                      </tr>
                ';
            }
            if($Alicuota27_0)
            {
                echo '<tr>
                      <td>27.0</td>
                      <td>'.$TotalAlicuota27_0 * 1.27.'</td>
                      <td>'.$TotalAlicuota27_0 * 0.27.'</td>
                      <td></td>
                      <td></td>
                      </tr>
                ';
            }
            if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
            {
                $TotalNeto = $TotalAlicuota2_5 + $TotalAlicuota5_0 + $TotalAlicuota10_5 + $TotalAlicuota21_0 + $TotalAlicuota27_0;
                $TotalDebitoFiscal = ($TotalAlicuota2_5 * 0.025) + ($TotalAlicuota5_0 * 0.05) + ($TotalAlicuota10_5 * 0.105) + ($TotalAlicuota21_0 * 0.21) + ($TotalAlicuota27_0 * 0.27);
                echo '<tr>
                      <td>Totales</td>
                      <td>'.$TotalNeto.'</td>
                      <td>'.$TotalDebitoFiscal.'</td>
                      <td></td>
                      <td></td>
                    </tr>
                ';
                $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + $TotalDebitoFiscal;
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
            $TotalSaldoLibreDisponibilidadAFavorRespPeriodo=0;
            $TotalPagosACuenta = 0;
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
                }else{
                    $TotalPagosACuenta += $conceptosrestante['montoretenido'];
                }
                //echo $TotalPagosACuenta ."//";
            }
            $TotalPagosACuenta += $TotalPercepcionesIVA ;
            $CreditoGeneral = 0;
            $CreditoGeneral = $TotalCreditoFiscal_SumaTotal +  $TotalSaldoTecnicoAFavorRespPeriodoAnterior + $AjusteAnualAFavorResponsable ;
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
		<table style="margin-top: 60px">
			<tr style='background-color:#f0f0f0'>
				<td>Descripción</td>
				<td style="width:180px">Valor</td>
			</tr>
			<tr>
				<td>Total del Débito Fiscal</td>
				<td style="width:180px">
					<spam id="spnTotalDeditoFiscal"><?php echo $TotalDebitoFiscal_SumaTotal; ?><spam>
                    <?php
                    echo $this->Form->input('totaldebitofiscal',
                        [
                            'type'=>'hidden',
                            'value'=>$TotalDebitoFiscal_SumaTotal
                        ]
                    );
                    ?>
                </td>
			</tr>
			<tr>
				<td>Total del Crédito Fiscal</td>
				<td style="width:180px">
					<span id="spnTotalCreditoFiscal">
					    <?php echo $TotalCreditoFiscal_SumaTotal; ?>
					</span>
                    <?php
                    echo $this->Form->input('totalcreditofiscal',
                        [
                            'type'=>'hidden',
                            'value'=>$TotalCreditoFiscal_SumaTotal
                        ]
                    );
                    ?>
				</td>
			</tr>
			<tr>
				<td>Ajuste Anual de Crédito Fiscal por Operaciones Exentas - A favor del Responsable</td>
				<td style="width:180px">
                    <span id="spnCredFiscalxOpExcResp">
					    <?php echo $AjusteAnualAFavorResponsable; ?>
					</span>
				</td>
			</tr>
			<tr>
				<td>Ajuste Anual de Crédito Fiscal por Operaciones Exentas - A favor de AFIP</td>
				<td style="width:180px">
                     <span id=spnCredFiscalxOpExcAFIP">
					    <?php echo $AjusteAnualAFavorAFIP; ?>
					</span>
				</td>
			</tr>
			<tr>
				<td>Saldo Técnico a Favor del Responsable del Periodo anterior</td>
				<td style="width:180px">
                    <span id="spnSaldoAFavorPeriodoAnt">
					    <?php echo $TotalSaldoTecnicoAFavorRespPeriodoAnterior; ?>
					</span>
				</td>
			</tr>
            <tr>
                <td>Saldo Técnico a Favor del Responsable del Periodo</td>
                <td style="width:180px">
                    <span id="spnTotalSaldoTecnicoAFavorResp">
                        <?php echo $TotalSaldoTecnicoAFavorRespPeriodo;?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Subtotal Saldo Técnico a favor de la AFIP del Periodo</td>
                <td style="width:180px">
                    <span id="spnTotalSaldoTecnicoAFavorAFIP">
                        <?php echo $TotalSaldoTecnicoAPagarPeriodo?>
                    </span>
                </td>
            </tr>
			<tr>
				<td>Diferimiento F. 518</td>
				<td style="width:180px">
                    <span id="spnDiferimientoF518">
                        <?php echo $Diferimiento518?>
                    </span>
				</td>
			</tr>
			<tr>
				<td>Bonos Fiscales - Decreto 1145/09 y/o Decreto 852/14</td>
				<td style="width:180px">
                     <span id="spnBonosFiscales">
                        <?php echo $BonosFiscales?>
                    </span>
				</td>
			</tr>
			<tr>
				<td>Saldo Técnico a Favor de la AFIP del Periodo</td>
				<td style="width:180px">
					<span id="spnSaldoTecnicoAFavorAFIPPeriodo">
                        <?php echo $TotalSaldoTecnicoAPagarPeriodo;?>
                    </span>
				</td>
			</tr>
			<tr>
				<td>Saldo Tecnico a Favor del Responsable del Periodo</td>
				<td style="width:180px">
					<span id="spnSaldoTecnicoAfavorContribuyentePeriodo">
                        <?php echo
                        $TotalSaldoTecnicoAFavorRespPeriodo>0?$TotalSaldoTecnicoAFavorRespPeriodo:0;
                        echo $this->Form->input('saldoTecnico', array('type'=>'hidden','value'=>$TotalSaldoTecnicoAFavorRespPeriodo>0?$TotalSaldoTecnicoAFavorRespPeriodo:0));
                        ?>
                        </span>

				</td>
			</tr>
            <tr>
                <td>Saldo a favor de libre disponibilidad del periodo anterior neto de usos</td>
                <td style="width:180px">
                    <span id="spnSaldoAFavorLibreDispPeriodoAnteriorNetousos">
                        <?php
                        echo $TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnterior>0?$TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnterior:0;
                        //resulta que esto es un Uso del saldo de libre disponibilidad del periodo anterior asi que aca vamos
                        //a generar un input, el cual va a activar la creacion de un formulario para registrar este uso y disminuir
                        //el saldo del periodo anterior a 0

                        ?>
                    </span>
                    <?php
                    echo $this->Form->input('usoSLD', array('type'=>'hidden','value'=>$TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnterior));
                    echo $this->Form->input('usoSLDID', array('type'=>'hidden','value'=>$sldID));
                    ?>
                </td>
            </tr>
            <tr>
                <td>Total Retenciones, Percepciones y Pagos a cuenta computables en el periodo neto de restituciones</td>
                <td style="width:180px">
                    <span id="spnTotalRetencionesyPercepciones"><?php echo $TotalPagosACuenta>0?$TotalPagosACuenta:0;?></span>
                    <?php
                    echo $this->Form->input('totalretenciones', array('type'=>'hidden','value'=>$TotalPagosACuenta-$TotalPercepcionesIVA));
                    ?>
                </td>
            </tr>
            <tr>
                <td>Saldo a favor de libre disponibilidad del periodo</td>
                <td style="width:180px">
                    <span id="spnSaldoAFavorLibreDispNetousos">
                        <?php
                        echo $TotalSaldoLibreDisponibilidadAFavorRespPeriodo>0?$TotalSaldoLibreDisponibilidadAFavorRespPeriodo:0;
                        echo $this->Form->input('saldoLD', array('type'=>'hidden','value'=>$TotalSaldoLibreDisponibilidadAFavorRespPeriodo>0?$TotalSaldoLibreDisponibilidadAFavorRespPeriodo:0));
                        ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Saldo del Impuesto a Favor de la AFIP</td>
                <td style="width:180px">
                    <span id="spnSaldoAFavorLibreDispNetousos">
                        <?php
                            echo $TotalSaldoImpuestoAFavorAFIP>0?$TotalSaldoImpuestoAFavorAFIP:0;
						    echo $this->Form->input('apagar', array('type'=>'hidden','value'=>$TotalSaldoImpuestoAFavorAFIP>0?$TotalSaldoImpuestoAFavorAFIP:0));
						?>
                    </span>
                </td>
            </tr>
		</table>
		</div>
        <div id="divLiquidarIVA" class="noExl">

        </div>
	</div>
    <div id="divContenedorContabilidad" style="margin-top:10px"  class="noExl">
        <div class="index">
            <?php
            $Asientoid=0;
            $movId=[];
            if(count($cliente['Impcli'][0]['Asiento'])>0){
                $Asientoid=$cliente['Impcli'][0]['Asiento'][0]['id'];
                foreach ($cliente['Impcli'][0]['Asiento'][0]['Movimiento'] as $mimovimiento){
                    $movId[$mimovimiento['Cuentascliente']['cuenta_id']] = $mimovimiento['id'];
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
                'div' => false,
                'style'=> 'height:9px;display:inline'
            ));
            echo $this->Form->input('Asiento.0.nombre',['readonly'=>'readonly','value'=>"Asiento Devengamiento IVA" ,'style'=>'width:250px']);
            echo $this->Form->input('Asiento.0.descripcion',['readonly'=>'readonly','value'=>"Asiento Automatico periodo: ".$periodo,'style'=>'width:250px']);
            echo $this->Form->input('Asiento.0.cliente_id',['value'=>$cliente['Cliente']['id'],'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.impcli_id',['value'=>$cliente['Impcli'][0]['id'],'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo,'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.tipoasiento',['value'=>'impuestos','type'=>'hidden'])."</br>";
            $i=0;

            foreach ($cliente['Impcli'][0]['Impuesto']['Asientoestandare'] as $asientoestandarIVA) {
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
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.numero',['label'=>($i!=0)?false:'Numero','readonly'=>'readonly','value'=>$asientoestandarIVA['Cuenta']['numero'],'style'=>'width:82px']);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.nombre',['label'=>($i!=0)?false:'Nombre','readonly'=>'readonly','value'=>$cuentaclientenombre,'type'=>'text','style'=>'width:250px']);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',['label'=>($i!=0)?false:'Debe','readonly'=>'readonly','value'=>0,]);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',['label'=>($i!=0)?false:'Haber','readonly'=>'readonly','value'=>0,])."</br>";
                $i++;
            }

            echo $this->Form->submit('Contabilizar',['style'=>'']);
            echo $this->Form->end();
            ?>
        </div>
    </div>
</div>
