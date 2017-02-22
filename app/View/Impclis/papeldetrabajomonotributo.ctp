<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
 echo $this->Html->script('jquery.table2excel',array('inline'=>false));
 echo $this->Html->script('impclis/papeldetrabajomonotributo',array('inline'=>false));
 echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
 echo $this->Form->input('impcliidPDT',array('value'=>$impcliid,'type'=>'hidden'));?>
<div id="Formhead" class="clientes papeldetrabajoconveniomultilateral index" style="margin-bottom:10px;">
	<h2>Monotributo:</h2>
	Contribuyente: <?php echo $impcli['Cliente']['nombre']; ?></br>
	<?php echo $this->Form->input('clinombre',array('value'=>$impcli['Cliente']['nombre'],'type'=>'hidden'));?>
	<?php echo $this->Form->input('cliid',array('value'=>$impcli['Cliente']['id'],'type'=>'hidden'));?>
    CUIT: <?php echo $impcli['Cliente']['cuitcontribullente']; ?></br>
	Periodo: <?php echo $periodo; ?> </br>
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
	<div id="tabsTareaMonotributo" style="margin-left: 8px;">
		<div class="tabsTareaImpuesto_active" onClick="showRecategorizacion()" id="tab_Recategorizacion"><h2>Recatecotizacion</h2></div>
		<div class="tabsTareaImpuesto" onClick="showDDJJ()" id="tab_DDJJ"><h2>DDJJ</h2></div>
	</div>
	<div id="divRecategorizacion" class="index">
        <table class="tbl_tareas" style="border-collapse: collapse; width:50%;">
			<thead>
                <tr>
                    <td colspan ="20">
                        <h1>Tabla para Recategorizar Monotributistas</h1>
                    </td>
                </tr>
				<tr>
					<td>Periodo</td>
					<td>Locaciones y Ss</td>
					<td>Venta de cosas Muebles</td>
					<td>Total</td>
					<td>Cuatrimestre</td>
					<td>Sup.</td>
					<td>KW</td>
					<td>Alquiler</td>
				</tr>
			</thead>
			<tbody>
			<?php 
			$subtotalCuatrimestre = 0;
			$subtotalCuatrimestreCompra = 0;
			$subtotalCuatrimestreKW=0;
			$subtotalCuatrimestreAlquiler=0;
	
			$TotalCuatrimestreIngresoBruto=0;
			/*La mayor de estas dos variables decidirá si se aplica monotributo de Locacion o de Venta de cosa mueble*/
			$TotalCuatrimestreIngresoBrutolocacion=0;
			$TotalCuatrimestreIngresoBrutoMueble=0;
			$generoMonotributoAplicado= 'locacion-servicio';/*por defecto locacion servicio (arbitrario)*/
	
			$subtotalCompraSuperficie=0;
			foreach ($domicilios as $domicilio) {
				$subtotalCompraSuperficie += $domicilio[0]['superficie'];		
			}
			for ($i=0; $i < 12; $i++) { 
				$periodoDeInicioi =$periodoDeInicio.' +'.$i.' months';					
				?>
				<tr>
					<td><?php /*Periodo de Inicio*/ 
						echo date('m-Y',strtotime($periodoDeInicioi)); ?>
					</td>
					<?php /*Ingresos Brutos*/ 
						$subtotalVentaP0Total = 0;
						$subtotalVentaP0Locacion = 0;
						$subtotalVentaP0Mueble = 0;
						foreach ($ventas as $venta) {
							if($venta['Venta']['periodo']==date('m-Y',strtotime($periodoDeInicioi))){
								$sumaen = '';
								foreach ($actividadclientes as $actividadcliente) {
									if($actividadcliente['Actividadcliente']['id']==$venta['Venta']['actividadcliente_id']){
										$sumaen = $actividadcliente['Actividade']['generomonotributo'];
									}
								}
								if($venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso'){
									
									if($sumaen=='locacionservicio'){
										$subtotalVentaP0Locacion += $venta[0]['total'];							
									}else{
										$subtotalVentaP0Mueble += $venta[0]['total'];					
									}
									$subtotalVentaP0Total += $venta[0]['total'];
								}else{
									if($sumaen=='locacionservicio'){
										$subtotalVentaP0Locacion -= $venta[0]['total'];
									}else{
										$subtotalVentaP0Mueble -= $venta[0]['total'];
									}
									$subtotalVentaP0Total -= $venta[0]['total'];
								}
							}
						}
						?>
					<td>
						<?php echo $subtotalVentaP0Locacion;
						$TotalCuatrimestreIngresoBrutolocacion+=$subtotalVentaP0Locacion; ?>
					</td>
					<td>
						<?php echo $subtotalVentaP0Mueble; 
						$TotalCuatrimestreIngresoBrutoMueble+=$subtotalVentaP0Mueble;?>
					</td>
					<td>
						<?php echo $subtotalVentaP0Total; ?>
					</td>
					<?php 
					if($i==0||$i==4||$i==8){
						for ($j=0; $j < 4; $j++) {
							foreach ($ventas as $venta) {
								$k=$j+$i;
								$periodoDeInicioj = $periodoDeInicio.' +'.$k.' months';
								//echo "PI:".$periodoDeInicio."%%PJ:".$periodoDeInicioj."//".$i."//".$j."$$</br>";					
								if($venta['Venta']['periodo']==date('m-Y',strtotime($periodoDeInicioj))){
									if($venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso'){
										$subtotalCuatrimestre += $venta[0]['total'];
									}else{
										$subtotalCuatrimestre -= $venta[0]['total'];
									}
								}
							}
						}
						?>	
						<td rowspan="4">
						<?php echo $subtotalCuatrimestre; 
						$TotalCuatrimestreIngresoBruto+=$subtotalCuatrimestre;
						$subtotalCuatrimestre = 0;
						?>
						</td>
						<td rowspan="4">
						 <?php 
							echo $subtotalCompraSuperficie;
						 ?>
						</td>	
	
				<?php } ?>
						<?php
						$subtotalCompraP0 = 0;
						$subtotalCompraKW = 0;
						$subtotalCompraAlquiler=0;
						foreach ($compras as $compra) {
							if($compra['Compra']['periodo']==date('m-Y',strtotime($periodoDeInicioi))){
								if($compra['Compra']['tipogasto_id']=='19'/*Luz*/){
									$subtotalCompraP0 += $compra[0]['total'];							
									$subtotalCompraKW += $compra[0]['kw'];							
								}
								if($compra['Compra']['tipogasto_id']=='21'/*Alquiler*/){
									$subtotalCompraAlquiler += $compra[0]['total'];							
								}
							}
						}
	
						?>
					<td>
						<?php
						echo $subtotalCompraKW;
						$subtotalCuatrimestreKW += $subtotalCompraKW;							
						$subtotalCuatrimestreCompra += $subtotalCompraP0;							
						?>
					</td>
					<td>
						<?php
						echo $subtotalCompraAlquiler;
						$subtotalCuatrimestreAlquiler += $subtotalCompraAlquiler;							
						?>
					</td>
				</tr>		
	
			<?php } ?>
                <tr>
                    <td>
                        Totales
                    </td>
                    <td>
                        <?php echo $TotalCuatrimestreIngresoBrutolocacion; ?>
                    </td>
                    <td>
                        <?php echo $TotalCuatrimestreIngresoBrutoMueble;
                        if($TotalCuatrimestreIngresoBrutoMueble>$TotalCuatrimestreIngresoBrutolocacion){
                            $generoMonotributoAplicado = 'ventamueble';
                        }else{
                            $generoMonotributoAplicado = 'locacion-servicio';
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $TotalCuatrimestreIngresoBruto; ?>
                    </td>
                    <td>
                        <?php echo $TotalCuatrimestreIngresoBruto; ?>
                    </td>
                    <td>
                        <?php echo $subtotalCompraSuperficie; ?>
                    </td>
                    <td>
                        <?php echo $subtotalCuatrimestreKW; ?>
                    </td>
                    <td>
                        <?php echo $subtotalCuatrimestreAlquiler; ?>
                    </td>
                </tr>
			</tbody>
		</table> 
		<h1>Tabla Indicativa de la Categoria en la cual quedará encuadrado el Monotibutista para el cuatrimestre siguiente (siempre se encuadra en la categoria con parámetro superior).</h1>
		<table id="categoriamonotributo" class="tbl_tareas" style="border-collapse: collapse; width:50%">
			<thead>
				<td>Categoria</td>
				<td>Ingreso Bruto</td>
				<td>Superficie</td>
				<td>KW</td>
				<td>Alquiler</td>
			</thead>	
			<tbody>
			<?php
			$topOrden=0;
            foreach ($categoriamonotributos as $categoriamonotributo) {
				$colorTDIngBrutos = "white";
				if($categoriamonotributo['Categoriamonotributo']['ingresobruto']*1 <= $TotalCuatrimestreIngresoBruto*1 ) {
					$colorTDIngBrutos = "lightgreen";
                    $topOrden=$categoriamonotributo['Categoriamonotributo']['orden'];
				}
				$colorTDSuperficie = "white";
				if($categoriamonotributo['Categoriamonotributo']['superficiemaxima']*1 <= $subtotalCompraSuperficie*1 ) {
					$colorTDSuperficie = "lightgreen";
					$topOrden=$categoriamonotributo['Categoriamonotributo']['orden'];
                }
				$colorTDKW = "white";
				if($categoriamonotributo['Categoriamonotributo']['kwmaximo']*1 <= $subtotalCuatrimestreKW*1 ) {
					$colorTDKW = "lightgreen";
					$topOrden=$categoriamonotributo['Categoriamonotributo']['orden'];
                }
					$colorTDAlquiler = "white";
				if($categoriamonotributo['Categoriamonotributo']['alquileranualmaximo']*1 <= $subtotalCuatrimestreAlquiler*1 ) {
					$colorTDAlquiler = "lightgreen";
					$topOrden=$categoriamonotributo['Categoriamonotributo']['orden'];
                }
				?>
				<tr id="orden-<?php echo $categoriamonotributo['Categoriamonotributo']['orden'];?>">
					<td>
						 <?php echo $categoriamonotributo['Categoriamonotributo']['categoria']; ?>
					</td>
					<td style="background-color:<?php echo ($topOrden==1)?"lightgreen":$colorTDIngBrutos; ?>;">
						 <?php echo $categoriamonotributo['Categoriamonotributo']['ingresobruto']; 
						 ?>
					</td>
					<td style="background-color:<?php echo ($topOrden==1)?"lightgreen":$colorTDSuperficie; ?>;">
						 <?php echo $categoriamonotributo['Categoriamonotributo']['superficiemaxima']; ?>
					</td>
					<td style="background-color:<?php echo ($topOrden==1)?"lightgreen":$colorTDKW; ?>;">
						 <?php echo $categoriamonotributo['Categoriamonotributo']['kwmaximo']; ?>
					</td>
					<td style="background-color:<?php echo ($topOrden==1)?"lightgreen":$colorTDAlquiler; ?>;">
						 <?php echo $categoriamonotributo['Categoriamonotributo']['alquileranualmaximo']; ?>
					</td>
				</tr>
			<?php } ?>
				<?php 
				$topOrden++;
                echo $this->Form->input('topOrden',array('value'=>$topOrden,'type'=>'hidden'));
                echo $this->Form->input('mesParaProximaRecategorizacion',array('value'=>$mesParaProximaRecategorizacion,'type'=>'hidden'));
				$selectedCategory = ($topOrden==1)?1:$topOrden-1;
                echo $this->Form->input('topCategoria',array('value'=>$categoriamonotributos[$selectedCategory]['Categoriamonotributo']['categoria'],'type'=>'hidden'));
				?>
			</tbody>
		</table>
		<h1>Tabla indicativa del importe a facturar en el cuatrimestre siguiente y en el mes siguiente del cuatrimestre considerando una identica facturación para cada mes</h1>
		<table class="tbl_tareas" style="border-collapse: collapse; width:50%">
			<tr>
				<td>Categoria al: <?php echo $periodo; ?></td>
				<td>Ingresos Brutos</td>
				<td>Superficie Afectada</td>
				<td>KW</td>
				<td>Alquiler Devengado</td>
			</tr>
			<tr>
				<?php
				$categoriaencuadrada = array();
				$categoriaActual = array();
				foreach ($categoriamonotributos as $categoriamonotributo) {
					if($topOrden==$categoriamonotributo['Categoriamonotributo']['orden']){
						$categoriaencuadrada = $categoriamonotributo;
					}
					if($impcli['Impcli']['categoriamonotributo']==$categoriamonotributo['Categoriamonotributo']['categoria']){
						$categoriaActual = $categoriamonotributo;
					}
				}
				
				?>
				<td><?php echo $categoriaActual['Categoriamonotributo']['categoria']; ?></td>
				<td><?php echo $categoriaActual['Categoriamonotributo']['ingresobruto']; ?></td>
				<td><?php echo $categoriaActual['Categoriamonotributo']['superficiemaxima']; ?></td>
				<td><?php echo $categoriaActual['Categoriamonotributo']['kwmaximo']; ?></td>
				<td><?php echo $categoriaActual['Categoriamonotributo']['alquileranualmaximo']; ?></td>
			</tr>
			<tr>
				<td>Hasta: <?php echo $periodo; ?></td>
				<td>
					<?php echo $TotalCuatrimestreIngresoBruto; ?>
				</td>
				<td>
					<?php echo $subtotalCompraSuperficie; ?>
				</td>
				<td>
					<?php echo $subtotalCuatrimestreKW; ?>
				</td>
				<td>
					<?php echo $subtotalCuatrimestreAlquiler; ?>
				</td>
			</tr>
			<tr>
				<td>Por Cuatrimestre</td>
				<td>
					<?php echo $categoriaActual['Categoriamonotributo']['ingresobruto']-$TotalCuatrimestreIngresoBruto; ?>
				</td>
				<td>
					<?php echo $categoriaActual['Categoriamonotributo']['superficiemaxima']-$subtotalCompraSuperficie; ?>
				</td>
				<td>
					<?php echo $categoriaActual['Categoriamonotributo']['kwmaximo']-$subtotalCuatrimestreKW; ?>
				</td>
				<td>
					<?php echo $categoriaActual['Categoriamonotributo']['alquileranualmaximo']-$subtotalCuatrimestreAlquiler; 
					/*Aca vamos a generar inputs con los valores que se deben cargar en el formulario de eventosimpuestos, si estos no fueron cargados antes(se carga por JS)*/
					$montoAPagar=0;
					$montoSIPA=0;
					$montoObraSocial=0;
	
					if($generoMonotributoAplicado=='ventamueble'){
						$montoAPagar=$categoriaActual['Categoriamonotributo']['ventamueble'];
					}else{
						$montoAPagar=$categoriaActual['Categoriamonotributo']['locacion-servicio'];
					}
					$montoSIPA=$categoriaActual['Categoriamonotributo']['sipa'];
					$montoObraSocial=$categoriaActual['Categoriamonotributo']['obrasocial'];

					echo $this->Form->input('apagarMonotributo',array('value' => $montoAPagar,'type'=>'hidden' ));
					//solo si el impcli esta marcado como "paga jubilacion"
					if(!($impcli['Impcli']['monotributojubilacion']*1)){
						$montoSIPA = 0;
					}
					echo $this->Form->input('apagarAutonomo',array('value' => $montoSIPA,'type'=>'hidden' ));
					//Solo si el impcli esta marcado como "paga obra social" y x cant adherentes
					if(!($impcli['Impcli']['monotributoobrasocial']*1)){
						$montoObraSocial = 0;
					}else{
						$montoObraSocial = $montoObraSocial*$impcli['Impcli']['monotributoadherentes'];
					}echo $this->Form->input('apagarObrasocial',array('value' => $montoObraSocial,'type'=>'hidden' ));

					?>
				</td>
			</tr>
			<tr><td colspan="5">Faltan <?php echo $mesParaProximaRecategorizacion-1; ?> meses para recategorizacion </td></tr>
			<?php
			//tenemos que ver cuanto falta para la proxima recategorizacion!
			for ($i=$mesParaProximaRecategorizacion-1; $i > 0 ; $i--) { ?>
			<tr>
				<td><?php
					$nextPeriodo = '01-'.$periodo.' +'.($mesParaProximaRecategorizacion-$i).' months';
					echo date('m-Y',strtotime($nextPeriodo)); ?></td>
				<td>
					<?php echo ($categoriaActual['Categoriamonotributo']['ingresobruto']-$TotalCuatrimestreIngresoBruto)/$mesParaProximaRecategorizacion; ?>
				</td>
				<td>
					<?php echo ($categoriaActual['Categoriamonotributo']['superficiemaxima']-$subtotalCompraSuperficie)/$mesParaProximaRecategorizacion; ?>
				</td>
				<td>
					<?php echo ($categoriaActual['Categoriamonotributo']['kwmaximo']-$subtotalCuatrimestreKW)/$mesParaProximaRecategorizacion; ?>
				</td>
				<td>
					<?php echo ($categoriaActual['Categoriamonotributo']['alquileranualmaximo']-$subtotalCuatrimestreAlquiler)/$mesParaProximaRecategorizacion; ?>
				</td>
			</tr>
			<?php }
			?>
		</table>
		<div id="divEditImpCliMonotributo">


		</div>
		<div id="divLiquidarMonotributo">
			 
		</div>
	</div>
    <input type="hidden" id="haycambios" value="1">
	<div id="divDDJJ">

	</div>
</div>

