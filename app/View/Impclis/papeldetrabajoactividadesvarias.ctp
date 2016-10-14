<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('impclis/papeldetrabajoactividadesvarias',array('inline'=>false));
echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
echo $this->Form->input('impcliidPDT',array('value'=>$impcliid,'type'=>'hidden'));
echo $this->Form->input('clinombre',array('value'=>$impcli['Cliente']['nombre'],'type'=>'hidden'));?>
<div id="Formhead" class="clientes papeldetrabajoactividadesvarias index" style="margin-bottom:10px;">
    <table class="tbl_actividadesvarias tblInforme">
        <tr>
            <td>
            	Contribuyente:
            </td>
            <td>
            	<?php echo $impcli['Cliente']['nombre'];?>
            </td>
        </tr>
        <tr>
            <td>
            	Periodo:
            </td>
            <td>
            	<?php echo $periodo;?>
            </td>
			<td rowspan="1">
				<?php echo $this->Form->button('Imprimir',
					array('type' => 'button',
						'class' =>"btn_imprimir",
						'onClick' => "imprimir()"
					)
				);?>
			</td>
			<td rowspan="1">
				<?php echo $this->Form->button('Excel',
					array('type' => 'button',
						'id'=>"clickExcel",
						'class' =>"btn_imprimir",
					)
				);?>
			</td>
        </tr>
    </table>
</div> <!--End Clietenes_avance-->
<?php if (count($provinciasVentasDiff)!=0||count($provinciasComprasDiff)!=0){ ?>
<div class="index">
	Las localidades dadas de alta, actualmente, son: </br><?php 
	foreach ($provinciasActivadas as $key => $value) {
		 echo $this->Html->image('test-pass-icon.png',array(
							'alt' => 'open',
							'class' => 'btn_exit',						
                            )
	 	);
		echo $value."</br>";
	}
	if(count($provinciasVentasDiff)!=0){	
	?>
		Las localidades que no estan dadas de alta, y que tienen ventas son: </br>
		<?php 
		foreach ($provinciasVentasDiff as $key => $value) {
			echo $this->Html->image('test-fail-icon.png',array(
							'alt' => 'open',
							'class' => 'btn_exit',						
                            )
	 		);
			echo $value."</br>";
		}
	}
	?></br>
	<?php
	if(count($provinciasComprasDiff)!=0){	
	?>
		Las localidades que no estan dadas de alta, y que tienen compras son: </br>
		<?php 
		foreach ($provinciasComprasDiff as $key => $value) {
			echo $this->Html->image('test-fail-icon.png',array(
							'alt' => 'open',
							'class' => 'btn_exit',						
                            )
	 		);
			echo $value."</br>";
		}
	}
	?></br>
	Por favor haga click <?php echo
							$this->Html->link("aca",
												array(
													'controller' => 'clientes', 
													'action' => 'view', 
													$impcli['Cliente']['id']),
												array(
														'target' => '_blank', 
														'escape' => false
														)
									); 	
						?>	
			          y dé de alta las localidades listadas anteriormente para poder visualizar el informe correctamente</br>
</div>
<?php } else { 
	$provinciasArecorrer = array();
	$totalesProvincia = array();
	foreach ($impcli['Impcliprovincia'] as $impcliprovincia) { 
		$impcliprovinciaid='localidad'.$impcliprovincia['id'];
		$provinciaid='provincia'.$impcliprovincia['Localidade']['Partido']['id'];
		if (!in_array($impcliprovincia['Localidade']['Partido']['id'], $provinciasArecorrer))
		{
			array_push($provinciasArecorrer,$impcliprovincia['Localidade']['Partido']['id']);
		}
		if(!isset($totalesProvincia[$provinciaid])){
			$totalesProvincia[$provinciaid]=array();
			$totalesProvincia[$provinciaid]['Total']=array();
			$totalesProvincia[$provinciaid]['TotalOtrosArticulos']=array();
			$totalesProvincia[$provinciaid]['TotalArticulo2']=array();
			$totalesProvincia[$provinciaid]['porcentajes']=array();
			$totalesProvincia[$provinciaid]['TotalBaseDeterminada']=array();
			$totalesProvincia[$provinciaid]['TotalImpuesto']=array();
			$totalesProvincia[$provinciaid]['TotalAPagar']=0;
			$totalesProvincia[$provinciaid]['TotalAFavor']=0;
		}
		$subtotalVentaxActividad[$provinciaid][$impcliprovinciaid]=array();
		foreach ($actividadclientes as $actividadcliente) { 
			$subTotalVenta=0;
			$actividadclienteActividadclienteid=$actividadcliente['Actividadcliente']['id'];
			$subtotalVentaxActividad[$provinciaid][$impcliprovinciaid][$actividadclienteActividadclienteid]=array();
			$subtotalVentaxActividad[$provinciaid][$impcliprovinciaid][$actividadclienteActividadclienteid]['subTotalVenta']=0;

			if(!isset($totalesProvincia[$provinciaid]['Total'][$actividadclienteActividadclienteid])){
				$totalesProvincia[$provinciaid]['Total'][$actividadclienteActividadclienteid]=0;
			}

			foreach ($actividadcliente['Venta'] as $venta) {
				//calculo subTotalLocalidadxActividadVenta
				if(($venta['Localidade']['id']==$impcliprovincia['Localidade']['id'])){
					if($venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso'){
						$subTotalVenta +=$venta['neto']+$venta['nogravados']+$venta['excentos']-$venta['exentosactividadesvarias'];
					}else if($venta['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
						$subTotalVenta -=$venta['neto']+$venta['nogravados']+$venta['excentos']-$venta['exentosactividadesvarias'];
					}			
				}
			}
			$subtotalVentaxActividad[$provinciaid][$impcliprovinciaid][$actividadclienteActividadclienteid]['subTotalVenta']=$subTotalVenta;
			//no puedo seguir calculando por que necesito los totales por provincia primero
			$totalesProvincia[$provinciaid]['Total'][$actividadclienteActividadclienteid] += $subTotalVenta;	
		}	
	}
	?>
	<div class="index" style="overflow:scroll;">
		<?php
		$arrayBasesProrrateadas=array();
		foreach ($provinciasArecorrer as $miProvincia) { 
			$arrayBasesProrrateadas[$miProvincia]=array(); ?>
		<table class="tbl_ActividadesEconomicasProvincia tbl_tareas aimprimir" style="border-collapse: collapse;" id="pdtactividadesvarias">
			<tr id="1">
				<td colspan="2"> </td>
				<?php 
				foreach ($actividadclientes as $actividadcliente) { 
					$arrayBasesProrrateadas[$miProvincia][$actividadcliente['Actividade']['id']]=0;
					?>
				<td colspan="7" class="thActividad<?php echo $actividadcliente['Actividade']['id']; ?>">
					<span style="color:deepskyblue" onclick="showhideactividad('<?php echo $actividadcliente['Actividade']['id']; ?>');"  >
						<label class="lbl_trunc"><?php echo $actividadcliente['Actividade']['nombre']; ?></label>
					</span>
				</td>
					<?php 
					$baseprorrateadaImpCLI = 0;
					//echo json_encode($actividadcliente['Basesprorrateada']);
					foreach ($actividadcliente['Basesprorrateada'] as $key => $basesprorrateada) {
						if($basesprorrateada['Impcliprovincia']['Partido']['id']==$miProvincia&&$impcliprovincia['ejercicio']!='Primero'){
							//esta es la base que se uso para calcular Actividad Economica en esta provincia
							$baseprorrateadaImpCLI=$basesprorrateada['baseprorrateada'];
							echo "<td >".number_format($baseprorrateadaImpCLI, 2, ",", ".")."</td>";
							$arrayBasesProrrateadas[$miProvincia][$actividadcliente['Actividade']['id']]=$baseprorrateadaImpCLI;
						}
					}
			 	}
				?>	
				<td colspan="2">Impuesto</td>
				<td colspan="5">Conceptos que restan</td>
				<td colspan="2">A Favor</td>
			</tr>
			<tr id="2">
				<td ><?php
				foreach ($impcli['Impcliprovincia'] as $impcliprovincia) { 
					$provinciaid=$impcliprovincia['Localidade']['Partido']['id'];	
					$provinciaid=$impcliprovincia['Localidade']['Partido']['id'];	
					if($miProvincia==$provinciaid){
						echo $impcliprovincia['Localidade']['Partido']['nombre'];
						break;
					}

				}?></td>
				<td>Sede?</td>
				<?php 
				foreach ($actividadclientes as $actividadcliente) { ?>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>"><?php echo $actividadcliente['Actividade']['articulo']; ?></td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>">%</td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>" title="Art 6, 7, 9, 10, 11, 12">Art 6, 7,.. </td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>">Art 2</td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>" title="Diferencia Administración">Dif. Admin.</td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>" title="Base Determinada">Base</td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>" title="Alicuota por Municipio">Alicuota</td>
				<td class="tdImpuestoDeterminado<?php echo $actividadcliente['Actividade']['id']; ?>" title="Impuesto Determinado">Impuesto</td>
				<?php }
				?>	
				<td>Mínimo</td>
				<td title="Impuesto o Mínimo el Mayor">Impuesto</td>
				<td>Retención</td>
				<td title="Act Vs Percep">Percep.</td>
				<td>x</td>
				<td title="Saldo a Favor Período Ant">A Favor</td>
				<td>Total</td>
				<td>Fisco</td>
				<td>Contrib.</td>
			</tr>
			<?php 
			$provinciaAMostrar=array();
			$localidadAMostrar=array();
			foreach ($impcli['Impcliprovincia'] as $impcliprovincia) { 
				
				$provinciaid=$impcliprovincia['Localidade']['Partido']['id'];	
				$impcliprovinciaid = 'localidad'.$impcliprovincia['id'];
				$myimpcliprovincia = $impcliprovincia;
				if($miProvincia==$provinciaid){
					$totalPagoImpuestoLocalidad=0;
					$provinciaAMostrar = $impcliprovincia['Localidade']['Partido'];
					$localidadAMostrar = $impcliprovincia['Localidade'];
					$provinciaid='provincia'.$impcliprovincia['Localidade']['Partido']['id'];	
				}else{
					continue;
				}
				?>
			<tr id="3">
				<td>
					<?php echo $localidadAMostrar['nombre'];  ?>
				</td>
				<td><?php echo $impcliprovincia['sede']?  "SI": "NO";?>
					</td>
				<?php 
				foreach ($actividadclientes as $actividadcliente) { 
					$actividadclienteActividadclienteid=$actividadcliente['Actividadcliente']['id'];?>
				
			
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>"><?php
				$subTotalVenta=$subtotalVentaxActividad[$provinciaid][$impcliprovinciaid][$actividadclienteActividadclienteid]['subTotalVenta'];			
				echo  number_format($subTotalVenta, 2, ",", ".");?></td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>"><?php
					$porcentajeVentas = 0;
					if($totalesProvincia[$provinciaid]['Total'][$actividadclienteActividadclienteid]!=0){
						$porcentajeVentas = $subTotalVenta/$totalesProvincia[$provinciaid]['Total'][$actividadclienteActividadclienteid];					
					}
					if(!isset($totalesProvincia[$provinciaid]['porcentajes'][$actividadclienteActividadclienteid])){
						$totalesProvincia[$provinciaid]['porcentajes'][$actividadclienteActividadclienteid]=0;
					}
					$totalesProvincia[$provinciaid]['porcentajes'][$actividadclienteActividadclienteid]+=$porcentajeVentas;

					echo  number_format($porcentajeVentas*100, 2, ",", ".");
				?></td>
				<?php
				$totalOtrosArticulos = 0;
				$totalArticulo2 = 0;						
				switch ($actividadcliente['Actividade']['articulo']) {
					case '2':
						if($myimpcliprovincia['id']!='Primero'){
							//esta es la base que se uso para calcular Actividad Economica en esta provincia
							$totalArticulo2 = $porcentajeVentas * $arrayBasesProrrateadas[$miProvincia][$actividadcliente['Actividade']['id']];
						}	
					break;
					case '7':
					case '10':
					case '11':
					case '12':
						$totalOtrosArticulos = $subTotalVenta*0.8;
						
						break;
					case '6':
						$totalOtrosArticulos = $subTotalVenta*0.9;
						break;
					case '9':
						$totalOtrosArticulos = $subTotalVenta*1;
						break;
					default:
						echo 0;
						break;
				}
				$alicuotaAMostrar=0;
				$minimoAMostrar=0;
				foreach ($actividadcliente['Encuadrealicuota'] as $encuadrealicuota) { 
					if($myimpcliprovincia['id']==$encuadrealicuota['impcliprovincia_id']){
						$alicuotaAMostrar = $encuadrealicuota['alicuota'];
					}
				} 
				$impuestodeterminado= 0;
				if(!isset($totalesProvincia[$provinciaid]['TotalOtrosArticulos'][$actividadcliente['Actividadcliente']['id']])){
					$totalesProvincia[$provinciaid]['TotalOtrosArticulos'][$actividadcliente['Actividadcliente']['id']]=0;
				}
				if(!isset($totalesProvincia[$provinciaid]['TotalArticulo2'][$actividadcliente['Actividadcliente']['id']])){
					$totalesProvincia[$provinciaid]['TotalArticulo2'][$actividadcliente['Actividadcliente']['id']]=0;
				}
				$totalesProvincia[$provinciaid]['TotalOtrosArticulos'][$actividadcliente['Actividadcliente']['id']]+=$totalOtrosArticulos;
				$totalesProvincia[$provinciaid]['TotalArticulo2'][$actividadcliente['Actividadcliente']['id']]+=$totalArticulo2;
				?>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>"><?php echo number_format($totalOtrosArticulos, 2, ",", "."); ?></td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>"><?php echo number_format($totalArticulo2, 2, ",", "."); ?></td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>">0</td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>">
					<span style="color:#0C0">
					<?php 
					$totalBaseDeterminada = $totalArticulo2 + $totalOtrosArticulos;
					echo number_format($totalBaseDeterminada, 2, ",", "."); 
					$impuestodeterminado=($alicuotaAMostrar/100)*$totalBaseDeterminada;
					if(!isset($totalesProvincia[$provinciaid]['TotalBaseDeterminada'][$actividadcliente['Actividadcliente']['id']])){
						$totalesProvincia[$provinciaid]['TotalBaseDeterminada'][$actividadcliente['Actividadcliente']['id']]=0;
					}
					if(!isset($totalesProvincia[$provinciaid]['TotalImpuesto'][$actividadcliente['Actividadcliente']['id']])){
						$totalesProvincia[$provinciaid]['TotalImpuesto'][$actividadcliente['Actividadcliente']['id']]=0;
					}
					if(!isset($totalesProvincia[$provinciaid]['TotalElMayor'])){
						$totalesProvincia[$provinciaid]['TotalElMayor']=0;
					}
					$totalesProvincia[$provinciaid]['TotalBaseDeterminada'][$actividadcliente['Actividadcliente']['id']]+=$totalBaseDeterminada;
					$totalesProvincia[$provinciaid]['TotalImpuesto'][$actividadcliente['Actividadcliente']['id']]+=$impuestodeterminado;
					$totalesProvincia[$provinciaid]['TotalElMayor']+=$impuestodeterminado;

					$totalPagoImpuestoLocalidad += $impuestodeterminado;
					?>
					</span>
				</td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>"><?php echo number_format($alicuotaAMostrar, 2, ",", "."); ?></td>
				<td class="tdImpuestoDeterminado<?php echo $actividadcliente['Actividade']['id']; ?>"><span style="color:red"><?php echo number_format($impuestodeterminado, 2, ",", "."); ?></span></td>
				<?php 
				} ?>
				<td><?php 
					$minimoAMostrar += $myimpcliprovincia['minimo'];
                    echo number_format($minimoAMostrar, 2, ",", ".");
				?></td>
				<td><span style="color:red">
						<?php
                        if($minimoAMostrar>$totalPagoImpuestoLocalidad){
                            $totalPagoImpuestoLocalidad = $minimoAMostrar;
                        }
                        echo number_format($totalPagoImpuestoLocalidad, 2, ",", "."); ?></span>
				</td>
				<?php 
				$totalRetenciones=0;
				$totalPercepciones=0;
				$totalSaldoAFavor=0;
				$afavorSubtotal=0;
				$retencionSubtotal=0;
				$percepionSubtotal=0;
				$percepionBancariaSubtotal=0;
				$otrosSubtotal=0;
				$totalConceptos = 0;
				foreach ($actividadclientes as $actividadcliente) {
					foreach ($actividadcliente['Compra'] as $compra) {
						if($impcliprovincia['Localidade']['id']==$compra['localidade_id']){
							if($compra['tipocredito']=='Credito Fiscal'){
								$percepionSubtotal+=$compra['actvspercep'];

							}else if($compra['tipocredito']=='Restitucion credito fiscal'){
								$percepionSubtotal-=$compra['actvspercep'];
							}
						}
					}
				}
				foreach ($actividadcliente['Cliente']['Impcli'][0]['Conceptosrestante'] as $key => $conceptosrestante) {
					switch ($conceptosrestante['conceptostipo_id']) {
						case '1'/*Saldo a Favor*/:
							if($impcliprovincia['Localidade']['id']==$conceptosrestante['localidade_id'])
								$afavorSubtotal+=$conceptosrestante['montoretenido'];
							break;	
						case '2'/*Retencion*/:
							if($impcliprovincia['Localidade']['id']==$conceptosrestante['localidade_id'])
								$retencionSubtotal+=$conceptosrestante['montoretenido'];
							break;
						case '3'/*Recaudacion Bancaria*/:
							if($impcliprovincia['Localidade']['id']==$conceptosrestante['localidade_id'])
								$percepionBancariaSubtotal+=$conceptosrestante['montoretenido'];
							break;
						case '4'/*Otros*/:
							if($impcliprovincia['Localidade']['id']==$conceptosrestante['localidade_id'])
								$otrosSubtotal+=$conceptosrestante['montoretenido'];
							break;
						default:
							break;
					}
				}
				$totalConceptos = $retencionSubtotal + $percepionSubtotal + $percepionBancariaSubtotal + $afavorSubtotal;
				if(!isset($totalesProvincia[$provinciaid]['TotalRetencion'])){
					$totalesProvincia[$provinciaid]['TotalRetencion']=0;
				}
				if(!isset($totalesProvincia[$provinciaid]['TotalPercepionBancariaS'])){
					$totalesProvincia[$provinciaid]['TotalPercepionBancariaS']=0;
				}
				if(!isset($totalesProvincia[$provinciaid]['TotalAFavorSaldo'])){
					$totalesProvincia[$provinciaid]['TotalAFavorSaldo']=0;
				}
				if(!isset($totalesProvincia[$provinciaid]['TotalConceptos'])){
					$totalesProvincia[$provinciaid]['TotalConceptos']=0;
				}
				if(!isset($totalesProvincia[$provinciaid]['TotalRetenciones'])){
					$totalesProvincia[$provinciaid]['TotalRetenciones']=0;
				}
				$totalesProvincia[$provinciaid]['TotalRetencion']+=$retencionSubtotal;
				$totalesProvincia[$provinciaid]['TotalPercepionBancariaS']+=$percepionBancariaSubtotal;
				$totalesProvincia[$provinciaid]['TotalAFavorSaldo']+=$afavorSubtotal;
				$totalesProvincia[$provinciaid]['TotalConceptos']+=$totalConceptos;
				$totalesProvincia[$provinciaid]['TotalRetenciones']+=$percepionSubtotal;
				?>
				<td><?php echo number_format($retencionSubtotal, 2, ",", "."); ?></td>
				<td><?php echo number_format(0, 2, ",", "."); ?></td>
				<td><?php echo number_format($percepionBancariaSubtotal, 2, ",", "."); ?></td>
				<td><?php echo number_format($afavorSubtotal, 2, ",", "."); ?></td>
				<td><?php echo number_format($totalConceptos, 2, ",", "."); ?></td>
				<?php 
				$subtotalAPagar = 0;
				$subtotalAFavor = 0;
				$subtotalAPagar = $totalConceptos - $totalPagoImpuestoLocalidad  ;
				if($subtotalAPagar<=0){
					$subtotalAPagar = $subtotalAPagar*-1;
				}else{
					$subtotalAFavor = $subtotalAPagar;
					$subtotalAPagar = 0;
				}
				$totalesProvincia[$provinciaid]['TotalAPagar']+=$subtotalAPagar;
				$totalesProvincia[$provinciaid]['TotalAFavor']+=$subtotalAFavor;
				?>
				<td><?php 
					echo number_format($subtotalAPagar, 2, ",", ".");
					echo $this->Form->input('apagar'.$impcliprovincia['Localidade']['id'], array('type'=>'hidden','value'=>$subtotalAPagar));  ?>
				</td>
				<td><?php 
					echo number_format($subtotalAFavor, 2, ",", "."); 
					echo $this->Form->input('afavor'.$impcliprovincia['Localidade']['id'], array('type'=>'hidden','value'=>$subtotalAFavor));  ?>
				</td>
			</tr>	
			<?php }?>
			<tr><?php //row de totales?>   
				<td>Suma</td>
				<td></td>
				<?php 

				foreach ($impcli['Impcliprovincia'] as $impcliprovincia) { 
					$impcliprovinciaid = 'localidad'.$impcliprovincia['id'];
					$provinciaid='provincia'.$miProvincia;
					$partidoid='provincia'.$impcliprovincia['Localidade']['Partido']['id'];
					if(!isset($totalesProvincia[$provinciaid]['ventas'])) {
						$totalesProvincia[$provinciaid]['ventas'] = array();
					}
					$myimpcliprovincia = $impcliprovincia;

					if($miProvincia==$impcliprovincia['Localidade']['Partido']['id']){
						foreach ($actividadclientes as $actividadcliente) { 
							$actividadclienteActividadclienteid = $actividadcliente['Actividadcliente']['id'];
							$subTotalVenta = $subtotalVentaxActividad[$partidoid][$impcliprovinciaid][$actividadclienteActividadclienteid]['subTotalVenta'];			
							if(!isset($totalesProvincia[$provinciaid]['ventas'][$actividadclienteActividadclienteid])){
								$totalesProvincia[$provinciaid]['ventas'][$actividadclienteActividadclienteid] = $subTotalVenta;
							}else{
								$totalesProvincia[$provinciaid]['ventas'][$actividadclienteActividadclienteid] += $subTotalVenta;
							}
						}
					}else{
						//continue;
					}
				}
			
				$totalesporprovincia = $totalesProvincia['provincia'.$miProvincia];
				$ventasporprovincia = $totalesporprovincia['ventas']; 
				$porcentajesporprovincia = $totalesporprovincia['porcentajes']; 
				$OtrosArticulosporprovincia = $totalesporprovincia['TotalOtrosArticulos']; 
				$Articulo2porprovincia = $totalesporprovincia['TotalArticulo2']; 
				$BaseDeterminadaporprovincia = $totalesporprovincia['TotalBaseDeterminada']; 
				$Impuestoporprovincia = $totalesporprovincia['TotalImpuesto']; 
				foreach ($actividadclientes as $actividadcliente) { 
					$actividadclienteActividadclienteid = $actividadcliente['Actividadcliente']['id'];
					$ventasporactividad = $ventasporprovincia[$actividadclienteActividadclienteid];
					$porcentajesporactividad = $porcentajesporprovincia[$actividadclienteActividadclienteid];
					$OtrosArticulosporactividad = $OtrosArticulosporprovincia[$actividadclienteActividadclienteid];
					$Articulo2poractividad = $Articulo2porprovincia[$actividadclienteActividadclienteid];
					$BaseDeterminadaporactividad = $BaseDeterminadaporprovincia[$actividadclienteActividadclienteid];
					$Impuestoporactividad = $Impuestoporprovincia[$actividadclienteActividadclienteid]; ?>    
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>"><?php echo number_format($ventasporactividad, 2, ",", "."); ?></td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>"><?php echo number_format($porcentajesporactividad, 2, ",", "."); ?></td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>"><?php echo number_format($OtrosArticulosporactividad, 2, ",", "."); ?></td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>"><?php echo number_format($Articulo2poractividad, 2, ",", "."); ?></td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>">0</td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>"><?php echo number_format($BaseDeterminadaporactividad, 2, ",", "."); ?></td>
				<td class="tdActividad<?php echo $actividadcliente['Actividade']['id']; ?>">&nbsp;</td>
				<td class="tdImpuestoDeterminado<?php echo $actividadcliente['Actividade']['id']; ?>"><?php echo number_format($Impuestoporactividad, 2, ",", "."); ?></td>
				<?php } ?>
				<td><?php echo number_format(0, 2, ",", "."); ?></td>
				<td><?php echo number_format($totalesProvincia[$provinciaid]['TotalElMayor'], 2, ",", "."); ?></td>
				<td><?php echo number_format($totalesProvincia[$provinciaid]['TotalRetencion'], 2, ",", "."); ?></td>
				<td><?php echo number_format($totalesProvincia[$provinciaid]['TotalRetenciones'], 2, ",", "."); ?></td>
				<td><?php echo number_format($totalesProvincia[$provinciaid]['TotalPercepionBancariaS'], 2, ",", "."); ?></td>
				<td><?php echo number_format($totalesProvincia[$provinciaid]['TotalAFavorSaldo'], 2, ",", "."); ?></td>
				<td><?php echo number_format($totalesProvincia[$provinciaid]['TotalConceptos'], 2, ",", "."); ?></td>
				
				<td><?php echo number_format($totalesProvincia[$provinciaid]['TotalAPagar'], 2, ",", "."); ?></td>
				<td><?php echo number_format($totalesProvincia[$provinciaid]['TotalAFavor'], 2, ",", "."); ?></td>
			</tr>
		</table>
	<?php 
		}
	?>	
	</div>
	<div id="divLiquidarActividadesVariar">
			 
	</div>
<?php } ?>
