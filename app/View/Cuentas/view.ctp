<script type="text/javascript">
	$(document).ready(function() {
	    var iTamPantalla = $(window).height();
	    var iTamTabla = iTamPantalla - 170;
	    iTamTabla = (iTamTabla < 100) ? 100 : iTamTabla;
	    $("#divPlanCuentasStandard").attr("style", "max-height:" + iTamTabla + "px; width:96%; overflow:auto");

	    $('#txtBuscarCuenta').keyup(function () {
            var valThis = this.value.toLowerCase();
            //var lenght  = this.value.length;
            //if (valThis.length > 2)
            //{
	            $('tr[id^="trCuenta_"]').each(function () {
	                var oLabelObj = $(this).find('label');
	                var text  = oLabelObj.html();
	                var textL = text.toLowerCase();
	                if (textL.indexOf(valThis) >= 0)
	                {
	                    //$(this).slideDown();
	                    $(this).show();
	                }
	                else
	                {
	                    //$(this).slideUp();
	                    $(this).hide();
	                }
	            });            
        	//}
        });
	});

	function FnActivarCuenta(oObj)
	{
		//alert(oObj.checked)	;
		var sChecked = (oObj.checked) ? "1" : "0";
		var sClienteId = $("#hdnClienteId").val();
		var sCuentaId = (oObj.id).split("_")[1];
		$.ajax({
				type: "post",  // Request method: post, get
				url: serverLayoutURL+"/cuentas/activar/"+sClienteId+"/"+sCuentaId+"/"+sChecked, // URL to request
				data: "",  // post data
				success: function(response) {
					var midata = jQuery.parseJSON(response);
					//callAlertPopint(midata.respuesta);
					//$('#rowImpcli'+impcliid).hide();
					callAlertPopint(midata.respuesta);

				},
				error:function (XMLHttpRequest, textStatus, errorThrown) {
					alert(textStatus);
					alert(XMLHttpRequest);
					alert(errorThrown);
				}
		});
	}
</script>

<div class="index">
	<div style="width:40%;float:left">
		<?php
			echo $this->Html->link(
	                                'Volver', 
	                                array(
	                                    'controller' => 'cuentasclientes', 
	                                    'action' => 'plancuentas', $clienteId
	                                ),
	                                array(
	                                	'style' => 'float:left; margin-left:10px;margin-top:15px'
	                                )                               
	    				        )
			;  
	    ?>
	    <input type="hidden" id="hdnClienteId" value="<?php echo $clienteId; ?>"/>
	</div>
	<div style="width:60%;float:right">
		<input placeholder="Buscar Nro. Cuenta" id="txtBuscarCuenta" type="text" style="float:right; width:80%; margin:5px" />
	</div>
</div>
<div class="index">
	<table style="margin-bottom:0px"> 		
		<tr>
			<td style="width:10%;text-align:left">				
				<!--<input id="chkSelTodos" type="checkbox" value=""/>-->
				Activar
			</td>			
			<td style="width:30%;text-align:left">Numero</td>			
			<td style="width:60%;text-align:left">Descripcion</td>						
		</tr>		
	</table>		
</div>
<div id="divPlanCuentasStandard" class="index">
	<table>
		<?php foreach ($cuentas as $cuenta)
		{ 
			$CuentaId = $cuenta['Cuenta']['id'];
		?>
		<tr id="trCuenta_<?php echo $CuentaId ?>">
			<td style="width:10%">

				<?php 
				if ($CuentaId == $cuenta['cuentascliente']['cuenta_id']) 
					echo '<input id="chkCuenta_'.$CuentaId.'" checked="checked" onclick="FnActivarCuenta(this)" type="checkbox" value="'.$CuentaId.'"/>';
				else
					echo '<input id="chkCuenta_'.$CuentaId.'" type="checkbox" onclick="FnActivarCuenta(this)" value="'.$CuentaId.'"/>';
				?>		
				
			</td>
			<td style="width:30%">
				<label><?php echo h($cuenta['Cuenta']['numero']); ?></label>
			</td>			
			<td style="width:60%">
				<?php echo h($cuenta['Cuenta']['nombre']); ?>
			</td>			
		</tr>		
		<?php 
		} ?>
	</table>
</div>