<script type="text/javascript">
	$(document).ready(function() {
	    var iTamPantalla = $(window).height();
	    var iTamTabla = iTamPantalla - 160;
	    iTamTabla = (iTamTabla < 100) ? 100 : iTamTabla;
	    $("#divPlanCuentasCliente").attr("style", "max-height:" + iTamTabla + "px; width:100%; overflow:auto");	    
	});
	function EditarDescripcion(CuentaCliId)
	{
		var sDescripcion = $("#lblDescripcionCuenta_" + CuentaCliId).text();
		sDescripcion = (sDescripcion != "") ? sDescripcion.trim(): "";
		$("#lblDescripcionCuenta_" + CuentaCliId).attr("style","display:none");
		$("#txtDescripcionCuenta_" + CuentaCliId).val(sDescripcion);
		$("#txtDescripcionCuenta_" + CuentaCliId).attr("style","display:block; width:80%");		
		
		$("#lnkEditarDesc_" + CuentaCliId).attr("style","display:none");
		$("#lnkGuardarDesc_" + CuentaCliId).attr("style","display:block; cursor:pointer");
		$("#lnkCancelarDesc_" + CuentaCliId).attr("style","display:block; cursor:pointer");		
	}
	function CancelarDescripcion(CuentaCliId)
	{
		$("#lblDescripcionCuenta_" + CuentaCliId).attr("style","display:block");
		$("#txtDescripcionCuenta_" + CuentaCliId).attr("style","display:none");

		$("#lnkEditarDesc_" + CuentaCliId).attr("style","display:block;cursor:pointer");
		$("#lnkGuardarDesc_" + CuentaCliId).attr("style","display:none");
		$("#lnkCancelarDesc_" + CuentaCliId).attr("style","display:none;");
	}
	function GuardarDescripcion(CuentaCliId)
	{
		var sDescripcion = $("#txtDescripcionCuenta_" + CuentaCliId).val();
		sDescripcion = (sDescripcion != "") ? sDescripcion.trim(): "";		
		
		$.ajax({
				type: "post",  // Request method: post, get
				url: serverLayoutURL+"/Cuentasclientes/GuardarDescripcion/"+CuentaCliId+"/"+sDescripcion, 
				data: "",  // post data
				success: function(response) {
					var midata = jQuery.parseJSON(response);					
					callAlertPopint(midata.respuesta);

					$("#lblDescripcionCuenta_" + CuentaCliId).text(sDescripcion);
					CancelarDescripcion(CuentaCliId);
				},
				error:function (XMLHttpRequest, textStatus, errorThrown) {
					alert(textStatus);
					alert(XMLHttpRequest);
					alert(errorThrown);
				}
		});
	}
</script>

<div style="width:98%; margin:2%;">
	<div style='float:left; width:70%'> 
		Cliente: <?php echo $cliente['Cliente']['nombre']; ?>
	</div>
	<div style="float:right; width:28%;margin-bottom:10px">
		<!--<button class="btn_realizar_tarea" type="button" style="float:right;" onclick="">
			Agregar Cuentas
		</button>-->
		<?php
		echo $this->Html->link(
                                'Agregar Cuentas', 
                                array(
                                    'controller' => 'Cuentas', 
                                    'action' => 'view', $cliente['Cliente']['id']
                                ),
                                array(
                                	'style' => 'float:right; margin-right:40px'
                                )                               
                              );  
        ?>

	</div>
</div>
<div style="width:99%;">	
	<table style="margin-bottom:0px">			
		<tr>
			<th style="width:20%;text-align:left">Cuenta Nro.</th>
			<th style="width:50%;text-align:left">Descripcion</th>			
			<th style="width:10%;text-align:center">Inputable</th>			
			<th style="width:10%;text-align:center">Ajuste</th>
			<th style="width:10%;text-align:center">Acciones</th>
		</tr>		
	</table>	
</div>	
<div id="divPlanCuentasCliente"  style="width:100%;">	
	<table>			
		<?php foreach ($cuentasclientes as $cuentascliente)
		{ ?>
		<tr>
			<td style="width:20%;text-align:left">
				<?php echo h($cuentascliente['Cuenta']['numero']); ?>
			</td>
			<td style="width:50%;text-align:left"> 
				<?php 
					$DescCuenta = $cuentascliente['Cuentascliente']['descripcioncuenta'];
					$CuentaCliId = $cuentascliente['Cuentascliente']['id'];
				 ?>
				<label id="lblDescripcionCuenta_<?php echo $CuentaCliId;?>">
				<?php echo $DescCuenta;?>
				</label>				
				<input id="txtDescripcionCuenta_<?php echo $CuentaCliId;?>" type="text" value="" style="display:none;"/>				
			</td>		
			<td style="width:10%;text-align:center">
				<?php 
				if ($cuentascliente['Cuenta']['tipo'] == 'cuenta') 
					echo 'SI';
				else
					echo 'NO';
				?>
			</td>		
			<td style="width:10%;text-align:center">
				<?php 
				if ($cuentascliente['Cuenta']['ajuste'] == 0) 
					echo 'NO';
				else
					echo 'SI';
				?>
			</td>	
			<td style="width:10%;text-align:center">
				<a id="lnkEditarDesc_<?php echo $CuentaCliId;?>" style="cursor:pointer" onclick="EditarDescripcion('<?php echo $CuentaCliId;?>')">Editar</a>

				<a id="lnkGuardarDesc_<?php echo $CuentaCliId;?>" style="cursor:pointer; display:none" onclick="GuardarDescripcion('<?php echo $CuentaCliId;?>')">
					Guardar
				</a>
				<a id="lnkCancelarDesc_<?php echo $CuentaCliId;?>" style="cursor:pointer; display:none" onclick="CancelarDescripcion('<?php echo $CuentaCliId;?>')">Cancelar</a>
			</td>		
		</tr>		
		<?php 
		} ?>		
	</table>
</div>