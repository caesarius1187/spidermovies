<script type="text/javascript">
	$(document).ready(function() {
	    var iTamPantalla = $(window).height();
	    var iTamTabla = iTamPantalla - 170;
	    iTamTabla = (iTamTabla < 100) ? 100 : iTamTabla;
	    $("#divPlanCuentasStandard").attr("style", "max-height:" + iTamTabla + "px; width:96%; overflow:auto");
		$("#tblPlanDeCuentas tr").click(function(){
			var levelClickeado = $(this).attr("levelCuenta")*1;
			$("#tblPlanDeCuentas tr").each(function(){
				var level = $(this).attr("levelCuenta")*1;
				if(level>levelClickeado){
					if( $(this).is(":visible")){
						$(this).hide();
					}else{
						$(this).show();
					}
				}
			});
		});
//		$("#tblPlanDeCuentas").DataTable();
//	    $('#txtBuscarCuenta').keyup(function () {
//            var valThis = this.value.toLowerCase();
//            //var lenght  = this.value.length;
//            //if (valThis.length > 2)
//            //{
//	            $('tr[id^="trCuenta_"]').each(function () {
//	                var oLabelObj = $(this).find('label');
//	                var text  = oLabelObj.html();
//	                var textL = text.toLowerCase();
//	                if (textL.indexOf(valThis) >= 0)
//	                {
//	                    //$(this).slideDown();
//	                    $(this).show();
//	                }
//	                else
//	                {
//	                    //$(this).slideUp();
//	                    $(this).hide();
//	                }
//	            });
//        	//}
//        });
	});

	function FnActivarCuenta(oObj)
	{
		//alert(oObj.checked)	;
		var sChecked = (oObj.checked) ? "1" : "0";
		var sClienteId = $("#hdnClienteId").val();
		var sCuentaId = (oObj.id).split("_")[1];
		var sCuentaclienteId = (oObj.id).split("_")[2];
		$.ajax({
				type: "post",  // Request method: post, get
				url: serverLayoutURL+"/cuentas/activar/"+sClienteId+"/"+sCuentaId+"/"+sChecked+"/"+sCuentaclienteId, // URL to request
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
<div id="divPlanCuentasStandard" class="index">
	<table id="tblPlanDeCuentas">
		<thead>
			<tr>
				<td style="text-align:left">Numero</td>
				<td style="text-align:left"> </td>
				<td style="background-color:#a236b1;color:white;text-align:left"> </td>
				<td style="text-align:left"> </td>
				<td style="text-align:left"> </td>
				<td style="text-align:left"> </td>
				<td style="text-align:left"> </td>
				<td style="width:60%;text-align:left">Descripcion</td>
				<td style="width:10%;text-align:left">
					<!--<input id="chkSelTodos" type="checkbox" value=""/>-->
					Activar
				</td>
			</tr>
		</thead>
		<tbody>
			<?php
			$trStyle="background-color:#2a36b1;color:white;";

			foreach ($cuentas as $cuenta)
			{
				switch ($cuenta['Cuenta']['level']){
					case 1:
						$trStyle="background-color:#2a36b1;color:white;";
						break;
					case 2:
						$trStyle="background-color:#455ede;color:white;";
						break;
					case 3:
						$trStyle="background-color:#5677fc;color:white;";
						break;
					case 4:
						$trStyle="background-color:#91a7ff;color:black;";
						break;
					case 5:
						$trStyle="background-color:#d0d9ff;color:black;";
						break;
					case 6:
						$trStyle="background-color:#e7e9fd;color:black;";
						break;
					case 7:
						$trStyle="background-color:#white;color:black;";
						break;
				}

				$CuentaId = $cuenta['Cuenta']['id'];
				$CuentaClienteId = $cuenta['cuentascliente']['id']
			?>
			<tr id="trCuenta_<?php echo $CuentaId ?>" style="<?php echo $trStyle?>" levelCuenta="<?php echo $cuenta['Cuenta']['level']?>">

				<td style="">
					<label><?php echo $cuenta['Cuenta']['level']==1?$cuenta['Cuenta']['numero']:""; ?></label>
				</td>
				<td style="">
					<label><?php echo $cuenta['Cuenta']['level']==2?$cuenta['Cuenta']['numero']:""; ?></label>
				</td>
				<td style="">
					<label><?php echo $cuenta['Cuenta']['level']==3?$cuenta['Cuenta']['numero']:""; ?></label>
				</td>
				<td style="">
					<label><?php echo $cuenta['Cuenta']['level']==4?$cuenta['Cuenta']['numero']:""; ?></label>
				</td>
				<td style="">
					<label><?php echo $cuenta['Cuenta']['level']==5?$cuenta['Cuenta']['numero']:""; ?></label>
				</td>
				<td style="">
					<label><?php echo $cuenta['Cuenta']['level']==6?$cuenta['Cuenta']['numero']:""; ?></label>
				</td>
				<td style="">
					<label><?php echo $cuenta['Cuenta']['level']==7?$cuenta['Cuenta']['numero']:""; ?></label>
				</td>
				<td style="width:60%">
					<label id="lblNombreCuenta_<?php echo $CuentaId ?>">
						<?php echo h($cuenta['cuentascliente']['nombre']==""?$cuenta['Cuenta']['nombre']:$cuenta['cuentascliente']['nombre']); ?>
					</label>
				</td>
				<td style="width:10%">
					<?php
					if($cuenta['Cuenta']['tipo']=='cuenta'){
						if ($CuentaId == $cuenta['cuentascliente']['cuenta_id'])
							echo '<input id="chkCuenta_'.$CuentaId.'_'.$CuentaClienteId.'" checked="checked" onclick="FnActivarCuenta(this)" type="checkbox" value="'.$CuentaId.'" />';
						else
							echo '<input id="chkCuenta_'.$CuentaId.'_'.$CuentaClienteId.'" type="checkbox" onclick="FnActivarCuenta(this)" value="'.$CuentaId.'" />';
					}
					?>
				</td>
			</tr>
			<?php
//				die("me mori");
			} ?>
		</tbody>
		<tfoot>
			<tr>
				<td style="">

				</td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
			</tr>
		</tfoot>
	</table>
</div>