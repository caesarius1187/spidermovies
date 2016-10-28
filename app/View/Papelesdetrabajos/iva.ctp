<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));?>
<script type="text/javascript">
	$(document).ready(function() {   
    	$("#divContenedorCompras").hide();  
    	$("#divContenedorLiquidacion").hide();  
    	$("#tabVentas_Iva").attr("class", "cliente_view_tab_active");
    	$("#tabCompras_Iva").attr("class", "cliente_view_tab");
        papelesDeTrabajo($('#periodoPDT').val(),$('#impcliidPDT').val());
    });
	function CambiarTab(sTab)
	{
		$("#tabVentas_Iva").attr("class", "cliente_view_tab");
		$("#tabCompras_Iva").attr("class", "cliente_view_tab");
		$("#tabLiquidacion_Iva").attr("class", "cliente_view_tab");		

		if(sTab == "ventas")
		{
			$("#tabVentas_Iva").attr("class", "cliente_view_tab_active");
			$("#divContenedorVentas").show();    
			$("#divContenedorCompras").hide();    	
			$("#divContenedorLiquidacion").hide();  
		}
		if(sTab == "compras")
		{
			$("#tabCompras_Iva").attr("class", "cliente_view_tab_active");
			$("#divContenedorVentas").hide();    
			$("#divContenedorCompras").show();    	
			$("#divContenedorLiquidacion").hide();  
		}
		if (sTab == "liquidacion")
		{

			$("#tabLiquidacion_Iva").attr("class", "cliente_view_tab_active");
			$("#divContenedorVentas").hide();    
			$("#divContenedorCompras").hide();    	
			$("#divContenedorLiquidacion").show();  
		}
	}
	function MostrarTabla(oObj, tipo)
	{		
		if (tipo == "ventas")
		{
			var iActId = (oObj.id).split("_")[1];					
			var siD = "divTablaTipoDebito_" + iActId;
			$('div[id^='+siD+']').each(function(){ 
				$(this).show();
			});			
			$("#"+oObj.id).attr("onclick", "Ocultartablas(this,'ventas');");
		}
		if (tipo == "compras")
		{
			var sTipoCredito = (oObj.id).split("_")[1];
			$("#divTablaComprasCredito_" + sTipoCredito).show();
			$("#"+oObj.id).attr("onclick", "Ocultartablas(this,'compras');");
		}
	}
	function Ocultartablas(oObj, tipo)
	{		
		if (tipo == "ventas")
		{
			var iActId = (oObj.id).split("_")[1];					
			var siD = "divTablaTipoDebito_" + iActId;
			$('div[id^='+siD+']').each(function(){ 
				$(this).hide();
			});
			$("#"+oObj.id).attr("onclick", "MostrarTabla(this,'ventas');");
		}
		if (tipo == "compras")
		{
			var sTipoCredito = (oObj.id).split("_")[1];
			$("#divTablaComprasCredito_" + sTipoCredito).hide();
			$("#"+oObj.id).attr("onclick", "MostrarTabla(this,'compras');");
		}
	}
	function MostrarOperaciones(oObj, tipo)
	{
		if (tipo == "ventas")
		{
			var iActId = (oObj.id).split("_")[2];		
			var sTipoDebito = (oObj.id).split("_")[1];	
			var sId = "divTablaOperaciones_"+iActId+"_"+sTipoDebito+"_";

			$('div[id^='+sId+']').each(function(){ 
				$(this).show();
			});
			
			$("#"+oObj.id).attr("onclick", "OcultarOperaciones(this,'ventas');");
		}
	}
	function OcultarOperaciones(oObj, tipo)
	{		
		if (tipo == "ventas")
		{
			var iActId = (oObj.id).split("_")[2];		
			var sTipoDebito = (oObj.id).split("_")[1];	
			var sId = "divTablaOperaciones_"+iActId+"_"+sTipoDebito+"_";

			$('div[id^='+sId+']').each(function(){ 
				$(this).hide();
			});
			$("#"+oObj.id).attr("onclick", "MostrarOperaciones(this,'ventas');");
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
                            name: 'data[Eventosimpuesto][0][Usosaldo][0][conceptosrestantes_id]'
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
                                $('#divLiquidarActividadesVariar').hide();
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
</script>
<?php
echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
echo $this->Form->input('impcliidPDT',array('value'=>$cliente['Impcli'][0]['id'],'type'=>'hidden'));
echo $this->Form->input('clinombre',array('value'=>$cliente['Cliente']['nombre'],'type'=>'hidden'));
echo $this->Form->input('cliid',array('value'=>$cliente['Cliente']['id'],'type'=>'hidden'));?>
<div class="eventosclientes index">
	<div>
		<label>INFORME IVA</label>
		<label>Contribuyente: <?php echo $cliente['Cliente']['nombre']; ?> </label>
		<label>Periodo: </label>
	</div>
	<div style="width:100%;height:30px;">
		<div id="tabVentas_Iva" class="cliente_view_tab_active" onclick="CambiarTab('ventas');" style="width:25%;">
			<label style="text-align:center;margin-top:5px;cursor:pointer" for="">Debito</label>
		</div>
		<div id="tabCompras_Iva" class="cliente_view_tab" onclick="CambiarTab('compras');" style="width:25%;">
			<label style="text-align:center;margin-top:5px;cursor:pointer" for="">Credito</label>
		</div>
		<div id="tabLiquidacion_Iva" class="cliente_view_tab" onclick="CambiarTab('liquidacion');" style="width:25%;">
			<label style="text-align:center;margin-top:5px;cursor:pointer" for="">Liquidacion</label>
		</div>
	</div>
	<div style="width:100%; border-bottom: 1px solid; padding-top:5px"></div>
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
            <table id='divTituloActividad_<?php echo $ActividadCliente_id; ?>' border="1px solid" style="margin-bottom:0; cursor: pointer" onclick="MostrarTabla(this, 'ventas');">
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
		    <div id='divTablaTipoDebito_<?php echo $ActividadCliente_id; ?>_DebitoFiscal' style="display:none">
			    <table id='divTablaActividad_DebitoFiscal_<?php echo $ActividadCliente_id; ?>' onclick="MostrarOperaciones(this,'ventas')" border="1px solid" style="margin-bottom:0; cursor: pointer">
                    <!---------- TIPO DEBITo: Debito Fiscal ---------->
                    <tr>
                        <td colspan="5" style='background-color:#76b5cd'>
                            > TIPO DEBITO: Debito Fiscal
                        </td>
                    </tr>
			    </table>
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
                foreach ($ventas as $venta){
                    if($venta['Actividadcliente']['actividade_id'] == $ActividadCliente_id)
                    {
                        if ($venta['Venta']['tipodebito'] == 'Debito Fiscal' && $venta['Venta']['condicioniva'] == 'responsableinscripto')
                        {
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

                if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0)
                { ?>
                    <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_DebitoFiscal_RespInsc' style="display:none">
                        <table  border="1px solid" >
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
                if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0){
                ?>
                    <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_DebitoFiscal_Monotributista' style="display:none">
                        <table  border="1px solid" >
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
			if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
				?>
				<div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_DebitoFiscal_ConsF'
					 style="display:none">
					<table border="1px solid">
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
		    <div id='divTablaTipoDebito_<?php echo $ActividadCliente_id; ?>_BsUso' style="display:none">
            <table id='divTablaActividad_BsUso_<?php echo $ActividadCliente_id; ?>' onclick="MostrarOperaciones(this,'ventas')" border="1px solid" style="margin-bottom:0; cursor: pointer" >
            <tr>
                <td colspan="5" style='background-color:#76b5cd'>
                    > TIPO DEBITO: Bienes de Uso
                </td>
            </tr>
            </table>
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
            foreach ($ventas as $venta){
                if($venta['Actividadcliente']['actividade_id'] == $ActividadCliente_id)
                {
                    if ($venta['Venta']['tipodebito'] == 'Bien de uso' && $venta['Venta']['condicioniva'] == 'responsableinscripto')
                    {
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
            if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
                ?>
                <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_BsUso_RespInsc' style="display:none">
                    <table border="1px solid">
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
            if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
            ?>
                <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_BsUso_Monotributista' style="display:none">
                    <table  border="1px solid" >
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
            if($Alicuota2_5 || $Alicuota5_0 || $Alicuota10_5 || $Alicuota21_0 || $Alicuota27_0) {
                ?>
                <div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_BsUso_ConsF' style="display:none">
                    <table  border="1px solid" >
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
		    <div id='divTablaTipoDebito_<?php echo $ActividadCliente_id; ?>_RestCredFiscal' style="display:none">

		<table id='divTablaActividad_RestCredFiscal_<?php echo $ActividadCliente_id; ?>' onclick="MostrarOperaciones(this,'ventas')" border="1px solid" style="margin-bottom:0; cursor: pointer" >			
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
                if ($compra['Actividadcliente']['actividade_id'] == $ActividadCliente_id) {
                    if ($compra['Compra']['tipocredito'] == 'Restitucion credito fiscal' && $compra['Compra']['imputacion'] == 'Bs en Gral') {
                        $TotalBsGral = $TotalBsGral + $compra['Compra']['iva'];
                    }
                    if ($compra['Compra']['tipocredito'] == 'Restitucion credito fiscal' && $compra['Compra']['imputacion'] == 'Locaciones') {
                        $TotalLocaciones = $TotalLocaciones + $compra['Compra']['iva'];
                    }
                    if ($compra['Compra']['tipocredito'] == 'Restitucion credito fiscal' && $compra['Compra']['imputacion'] == 'Prest. Servicios') {
                        $TotalPresServ = $TotalPresServ + $compra['Compra']['iva'];
                    }
                    if ($compra['Compra']['tipocredito'] == 'Restitucion credito fiscal' && $compra['Compra']['imputacion'] == 'Bs Uso') {
                        $TotalBsUso = $TotalBsUso + $compra['Compra']['iva'];
                    }
                    if ($compra['Compra']['tipocredito'] == 'Restitucion credito fiscal' && $compra['Compra']['imputacion'] == 'Otros Conceptos') {
                        $TotalOtrosConceptos = $TotalOtrosConceptos + $compra['Compra']['iva'];
                    }
                    /*
                     * No vamos a agrgar el Dcto 814 como una compra por que afectara otros impuestos, ahroa se agrega como un pago a cuenta
                     * if ($compra['Compra']['tipocredito'] == 'Restitucion credito fiscal' && $compra['Compra']['imputacion'] == 'Dcto 814')
                    {
                        //Aca se debe sumar solo si el Dcto814 es del Tipo Restitucion Credito Fiscal
                        $TotalDcto814 = $TotalDcto814 + $compra['Compra']['neto'];
                    }*/
                }
                //aca vamos a calcular las perceptiones tambien, acumulando una sola vez para todas las actividades
                $TotalPercepcionesIVA = $TotalPercepcionesIVA + $compra['Compra']['ivapercep'];
            };
			$TotalDebitoFiscal_SumaTotal = $TotalDebitoFiscal_SumaTotal + $TotalBsGral + $TotalLocaciones + $TotalPresServ + $TotalBsUso + $TotalOtrosConceptos + $TotalDcto814;
        if($TotalBsGral>0){
        ?>
		<div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_RestCredFiscal_BsGral' style="display:none">
            <table  border="1px solid" >
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
		<div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_RestCredFiscal_Locaciones' style="display:none"> 
            <table  border="1px solid" >
                <tr>
                    <td colspan="5" style='background-color:#87cfeb'>
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
		<div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_RestCredFiscal_PresServ' style="display:none"> 
            <table  border="1px solid" >
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
		<div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_RestCredFiscal_BsUso' style="display:none"> 
            <table  border="1px solid" >
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
		<div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_RestCredFiscal_OtrosConc' style="display:none"> 
            <table  border="1px solid" >
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
		<div id='divTablaOperaciones_<?php echo $ActividadCliente_id; ?>_RestCredFiscal_Dcto814' style="display:none"> 
            <table  border="1px solid" >
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
		</div>
		<!----------- Fin Restitucion Credito Fiscal ---------->
        <?php } ?>

	</div> <!-- fin divContenedorTablaActividad_ -->		
	<?php } ; ?>
    </div>
</div>  <!-- fin divContenedorVentas -->
</div>
	<!--COMPRAS-->
	<div id="divContenedorCompras" style="height: 500px">
		<div style="margin-top:10px">(Coeficiente de Apropiacion 0.5000)</div>	
		
		<div style="width:100%; height: 10px"></div>

		<table id='divTablaCompras_CreditoFiscal' border="1px solid" onclick="MostrarTabla(this,'compras');" style="margin-bottom:0; cursor: pointer">
		<!------------- Credito Fiscal ---------->
		<tr>
			<td colspan="5" style='background-color:#76b5cd'>
				<b>
				TIPO CREDITO: Crédito Fiscal		
				</b>
			</td>
		</tr>	
		</table>
		<table id='divTablaComprasCredito_CreditoFiscal' border="1px solid" style="display:none">
		<?php 		
			$TotalBsGral_Directo = 0;	
			$TotalBsGral_Prorateable = 0;	
			$TotalLocaciones_Directo = 0;	
			$TotalLocaciones_Prorateable = 0;	
			$TotalPresServ_Directo = 0;		
			$TotalPresServ_Prorateable = 0;		
			$TotalBsUso_Directo = 0;
			$TotalBsUso_Prorateable = 0;
			$TotalOtrosConceptos_Directo = 0;
			$TotalOtrosConceptos_Prorateable = 0;
			$TotalDcto814_Directo = 0;
			$TotalDcto814_Prorateable = 0;

			foreach ($compras as $compra){
			
			if($compra['Compra']['tipocredito'] == 'Credito Fiscal')
			{			
				if ($compra['Compra']['imputacion'] == 'Bs en Gral')
				{	
					if ($compra['Compra']['tipoiva'] == 'directo')
					{
						$TotalBsGral_Directo = $TotalBsGral_Directo + $compra['Compra']['iva'];
					}
					if ($compra['Compra']['tipoiva'] == 'prorateable')
					{
						$TotalBsGral_Prorateable = $TotalBsGral_Prorateable + $compra['Compra']['iva'];
					}					
				}
				if ($compra['Compra']['imputacion'] == 'Locaciones')
				{	
					if ($compra['Compra']['tipoiva'] == 'directo')
					{
						$TotalLocaciones_Directo = $TotalLocaciones_Directo + $compra['Compra']['iva'];
					}
					if ($compra['Compra']['tipoiva'] == 'prorateable')
					{
						$TotalLocaciones_Prorateable = $TotalLocaciones_Prorateable + $compra['Compra']['iva'];
					}					
				}
				if ($compra['Compra']['imputacion'] == 'Prest. Servicios')			
				{	
					if ($compra['Compra']['tipoiva'] == 'directo')
					{
						$TotalPresServ_Directo = $TotalPresServ_Directo + $compra['Compra']['iva'];
					}
					if ($compra['Compra']['tipoiva'] == 'prorateable')
					{
						$TotalPresServ_Prorateable = $TotalPresServ_Prorateable + $compra['Compra']['iva'];
					}						
				}
				if ($compra['Compra']['imputacion'] == 'Bs Uso')			
				{	
					if ($compra['Compra']['tipoiva'] == 'directo')
					{
						$TotalBsUso_Directo = $TotalBsUso_Directo + $compra['Compra']['iva'];
					}
					if ($compra['Compra']['tipoiva'] == 'prorateable')
					{
						$TotalBsUso_Prorateable = $TotalBsUso_Prorateable + $compra['Compra']['iva'];
					}	
				}
				if ($compra['Compra']['imputacion'] == 'Otros Conceptos')			
				{	
					if ($compra['Compra']['tipoiva'] == 'directo')
					{
						$TotalOtrosConceptos_Directo = $TotalOtrosConceptos_Directo + $compra['Compra']['iva'];
					}
					if ($compra['Compra']['tipoiva'] == 'prorateable')
					{
						$TotalOtrosConceptos_Prorateable = $TotalOtrosConceptos_Prorateable + $compra['Compra']['iva'];
					}	
				}

				/*if ($compra['Compra']['imputacion'] == 'Dcto 814')	
				{
					if ($compra['Compra']['tipoiva'] == 'directo')
					{
						$TotalDcto814_Directo = $TotalDcto814_Directo + $compra['Compra']['iva'];
					}
					if ($compra['Compra']['tipoiva'] == 'prorateable')
					{
						$TotalDcto814_Prorateable = $TotalDcto814_Prorateable + $compra['Compra']['iva'];
					}	
				}*/
			}

        }
        foreach ($cliente['Impcli'][0]['Conceptosrestante'] as $key => $conceptosrestante) {
            if($conceptosrestante['conceptostipo_id']=='12'/*Decreto 814*/){
                //tenemos que agregar campos como
//                directo
//                prorrateable
//                creditofiscal/restirucioncreditofiscal
                $TotalDcto814_Directo += $conceptosrestante['montoretenido'];
            }
        }
		?>
		<tr>
			<td colspan="5" style='background-color:#87cfeb'>
				> OPERACION: Bs en Gral	
			</td>
		</tr>			
		<tr style='background-color:#f0f0f0'>
			<td>Directo</td>
			<td>Prorrateable</td>
			<td>Computable</td>
			<td>Facturado</td>
			<td>Computable Total</td>
		</tr>	
		<tr>
			<td><?php echo $TotalBsGral_Directo ?></td>
			<td><?php echo $TotalBsGral_Prorateable ?></td>
			<td><?php echo $TotalBsGral_Prorateable * 0.5000 ?></td>
			<td><?php echo $TotalBsGral_Directo + $TotalBsGral_Prorateable ?></td>
			<td><?php echo $TotalBsGral_Directo + ($TotalBsGral_Prorateable * 0.5000) ?></td>
		</tr>	
		<?php $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalBsGral_Directo + ($TotalBsGral_Prorateable * 0.5000)); ?>
		<tr>
			<td colspan="5" style='background-color:#87cfeb'>
				> OPERACION: Locaciones
			</td>
		</tr>			
		<tr style='background-color:#f0f0f0'>
			<td>Directo</td>
			<td>Prorrateable</td>
			<td>Computable</td>
			<td>Facturado</td>
			<td>Computable Total</td>
		</tr>	
		<tr>
			<td><?php echo $TotalLocaciones_Directo ?></td>
			<td><?php echo $TotalLocaciones_Prorateable ?></td>
			<td><?php echo $TotalLocaciones_Prorateable * 0.5000 ?></td>
			<td><?php echo $TotalLocaciones_Directo + $TotalLocaciones_Prorateable ?></td>
			<td><?php echo $TotalLocaciones_Directo + ($TotalLocaciones_Prorateable * 0.5000) ?></td>
		</tr>	
			<?php $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalLocaciones_Directo + ($TotalLocaciones_Prorateable * 0.5000)); ?>
		<tr>
			<td colspan="5" style='background-color:#87cfeb'>
				> OPERACION: Prest. Servicios
			</td>
		</tr>							
		<tr style='background-color:#f0f0f0'>
			<td>Directo</td>
			<td>Prorrateable</td>
			<td>Computable</td>
			<td>Facturado</td>
			<td>Computable Total</td>
		</tr>	
		<tr>
			<td><?php echo $TotalPresServ_Directo ?></td>
			<td><?php echo $TotalPresServ_Prorateable ?></td>
			<td><?php echo $TotalPresServ_Prorateable * 0.5000 ?></td>
			<td><?php echo $TotalPresServ_Directo + $TotalPresServ_Prorateable ?></td>
			<td><?php echo $TotalPresServ_Directo + ($TotalPresServ_Prorateable * 0.5000) ?></td>
		</tr>
		<?php $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalPresServ_Directo + ($TotalPresServ_Prorateable * 0.5000)); ?>
		<tr>
			<td colspan="5" style='background-color:#87cfeb'>
				> OPERACION: Bs. Uso
			</td>
		</tr>			
		<tr style='background-color:#f0f0f0'>
			<td>Directo</td>
			<td>Prorrateable</td>
			<td>Computable</td>
			<td>Facturado</td>
			<td>Computable Total</td>
		</tr>	
		<tr>
			<td><?php echo $TotalBsUso_Directo ?></td>
			<td><?php echo $TotalBsUso_Prorateable ?></td>
			<td><?php echo $TotalBsUso_Prorateable * 0.5000 ?></td>
			<td><?php echo $TotalBsUso_Directo + $TotalBsUso_Prorateable ?></td>
			<td><?php echo $TotalBsUso_Directo + ($TotalBsUso_Prorateable * 0.5000) ?></td>
		</tr>
		<?php $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalBsUso_Directo + ($TotalBsUso_Prorateable * 0.5000)); ?>
		<tr>
			<td colspan="5" style='background-color:#87cfeb'>
				> OPERACION: Otros Conceptos
			</td>
		</tr>			
		<tr style='background-color:#f0f0f0'>
			<td>Directo</td>
			<td>Prorrateable</td>
			<td>Computable</td>
			<td>Facturado</td>
			<td>Computable Total</td>
		</tr>	
		<tr>
			<td><?php echo $TotalOtrosConceptos_Directo ?></td>
			<td><?php echo $TotalOtrosConceptos_Prorateable ?></td>
			<td><?php echo $TotalOtrosConceptos_Prorateable * 0.5000 ?></td>
			<td><?php echo $TotalOtrosConceptos_Directo + $TotalOtrosConceptos_Prorateable ?></td>
			<td><?php echo $TotalOtrosConceptos_Directo + ($TotalOtrosConceptos_Prorateable * 0.5000) ?></td>
		</tr>
		<?php $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalOtrosConceptos_Directo + ($TotalOtrosConceptos_Prorateable * 0.5000)); ?>
		<tr>
			<td colspan="5" style='background-color:#87cfeb'>
				> OPERACION: Dcto. 814
			</td>
		</tr>			
		<tr style='background-color:#f0f0f0'>
			<td>Directo</td>
			<td>Prorrateable</td>
			<td>Computable</td>
			<td>Facturado</td>
			<td>Computable Total</td>
		</tr>	
		<tr>
			<td><?php echo $TotalDcto814_Directo ?></td>
			<td><?php echo $TotalDcto814_Prorateable ?></td>
			<td><?php echo $TotalDcto814_Prorateable * 0.5000 ?></td>
			<td><?php echo $TotalDcto814_Directo + $TotalDcto814_Prorateable ?></td>
			<td><?php echo $TotalDcto814_Directo + ($TotalDcto814_Prorateable * 0.5000) ?></td>
		</tr>
		<?php $TotalCreditoFiscal_SumaTotal = $TotalCreditoFiscal_SumaTotal + ($TotalDcto814_Directo + ($TotalDcto814_Prorateable * 0.5000)); ?>
		<tr>
			<td colspan="5" style='background-color:#87cfeb'>
				> OPERACION: Contrib. Seg. Social (Dto 814/01)	
			</td>
		</tr>			
		<tr style='background-color:#f0f0f0'>
			<td>Crédito Fiscal Facturado</td>
			<td>Crédito Fiscal Computable</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>	
		<tr>
			<td>Ver Calculo</td>
			<td>Ver Calculo</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<!----------- Fin Credito Fiscal ---------->
		</table>

		<div style="width:100%; height: 10px"></div>

		<table id='divTablaCompras_RestDebFiscal' border="1px solid" onclick="MostrarTabla(this,'compras');" style="margin-bottom:0; cursor: pointer">
		<!------------- Credito Fiscal ---------->
		<tr>
			<td colspan="5" style='background-color:#76b5cd'>
				<b>
				TIPO DEBITO: Restitucion debito fiscal
				</b>
			</td>
		</tr>	
		</table>
		<table id='divTablaComprasCredito_RestDebFiscal' border="1px solid" style="display:none">		
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
					  <td>5.0</td>
					  <td>'.$TotalAlicuota5_0.'</td>
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
					  <td>'.$TotalAlicuota21_0.'</td>
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
					  <td>'.$TotalAlicuota27_0.'</td>
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
					  <td>5.0</td>
					  <td>'.$TotalAlicuota5_0.'</td>
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
					  <td>'.$TotalAlicuota21_0.'</td>
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
					  <td>'.$TotalAlicuota27_0.'</td>
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
		<table border="1px solid">
			<tr style='background-color:#f0f0f0'>
				<td>Descripción</td>
				<td style="width:180px">Valor</td>
			</tr>
			<tr>
				<td>Total del Débito Fiscal</td>
				<td style="width:180px">
					<spam id="spnTotalDeditoFiscal"><?php echo $TotalDebitoFiscal_SumaTotal; ?><spam>
				</td>
			</tr>
			<tr>
				<td>Total del Crédito Fiscal</td>
				<td style="width:180px">
					<span id="spnTotalCreditoFiscal">
					    <?php echo $TotalCreditoFiscal_SumaTotal; ?>
					</span>
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
                        echo $this->Form->input('usoSLD', array('type'=>'hidden','value'=>$TotalSaldoLibreDisponibilidadAFavorRespPeriodoAnterior));
                        echo $this->Form->input('usoSLDID', array('type'=>'hidden','value'=>$sldID));
                        ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Total Retenciones, Percepciones y Pagos a cuenta computables en el periodo neto de restituciones</td>
                <td style="width:180px">
                    <span id="spnTotalRetencionesyPercepciones"><?php echo $TotalPagosACuenta>0?$TotalPagosACuenta:0;?></span>
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
        <div id="divLiquidarIVA">

        </div>
	</div>

</div>
