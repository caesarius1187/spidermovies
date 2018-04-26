<?php
echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('cuentasclientes/informesumaysaldo',array('inline'=>false));
echo $this->Html->script('jquery.table2excel',array('inline'=>false));

echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('dataTables.buttons.min.js',array('inline'=>false));
echo $this->Html->script('buttons.print.min.js',array('inline'=>false));
echo $this->Html->script('buttons.flash.min.js',array('inline'=>false));
echo $this->Html->script('jszip.min.js',array('inline'=>false));
echo $this->Html->script('pdfmake.min.js',array('inline'=>false));
echo $this->Html->script('vfs_fonts.js',array('inline'=>false));
echo $this->Html->script('buttons.html5.min.js',array('inline'=>false));?>
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>-->
<!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"/>-->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.2/css/buttons.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.1.2/css/select.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.0.2/css/responsive.dataTables.min.css"/>

<!--<script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>-->

<script src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.0.2/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$("#tblInformeAsiento").DataTable({});
	});
</script>
<div class="index" style="padding: 0px 1%; margin-bottom: 10px;">
	<div style="width:40%; float: left;padding-top:10px">
        Contribuyente: <?php echo $cliente["Cliente"]['nombre'];
        echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);
        echo $this->Form->input('cliid',['type'=>'hidden','value'=>$cliente["Cliente"]['id']]);?>
    </div>
    <div style="width:60%; float: left;padding-top:10px">
        Periodo: <?php echo $periodo;
        echo $this->Form->input('periodo',['type'=>'hidden','value'=>$periodo]);
        $peanio = substr($periodo, 3);
        echo $this->Form->input('peranio',['type'=>'hidden','value'=>$peanio]);?>
    </div>
</div>
<div class="index">
	<?php
	    echo "<h2>Informe de Asientos</h2>";
	    echo "<h3>del periodo  ".$fechaInicioConsulta." hasta ".$fechaFinConsulta."</h3>";
	 ?>
</div>
<div class="index">
	<table id="tblInformeAsiento">
		<thead>
			<tr>
				<td>Tipo Asiento</td>
			<?php
				$arrayPeriodos=[];
				$mesAMostrar = date('Y/m/d', strtotime($fechaInicioConsulta));
				while($mesAMostrar < $fechaFinConsulta){
				    $periodoMesAMostrar = date('m-Y', strtotime($mesAMostrar));
				    $arrayPeriodos[] = $periodoMesAMostrar;
				    echo "<td id='tdPeriodo_".$periodoMesAMostrar."'>". $periodoMesAMostrar."</td>";
				    $mesAMostrar = date('Y/m/d', strtotime($mesAMostrar." +1 months"));
				}
			?>
			</tr>
		</thead>
		<!--</tr>
	</table>-->
<!--</div>
<div>-->
		<tbody>
		<?php
			$arrayPeriodoTipoAsiento = [];
			//echo "<td></td>";
			$tiposdeasientos = [];
			foreach ($arrayPeriodos as $periodo) {
				//echo "<tr>";
				if(!isset($arrayPeriodoTipoAsiento[$periodo]))
					$arrayPeriodoTipoAsiento[$periodo]=[];
				//echo $value;	

				foreach ($cliente['Asiento'] as $asiento){
					//echo $asiento['tipoasiento'] . ' - ' . $asiento['fecha']. '<br>';
					//echo $asiento['tipoasiento'];

					$asientoPeriodo = date('m-Y',strtotime($asiento['fecha']));
					$bEstaEnElPeriodo = false;
					if ($periodo == $asientoPeriodo){
						$bEstaEnElPeriodo = true;				
					}

					$tipoasiento = $asiento['tipoasiento']."/".$asiento['impcli_id']."/".$asiento['cbu_id'];
					if(!in_array (  $tipoasiento , $tiposdeasientos )){
						$tiposdeasientos[] = $tipoasiento;
					}
					if(!isset($arrayPeriodoTipoAsiento[$periodo][$tipoasiento]))
					{
						if ($periodo == $asientoPeriodo)
						{
							$arrayPeriodoTipoAsiento[$periodo][$tipoasiento]=true;
							//echo ' - SII';
						}					
						//break;				
					}
					//echo '<br>';
					//$arrayPeriodoTipoAsiento[$value][$asiento] = $bEstaEnElPeriodo;

					/*				
					echo "<td>";
					if ($bEstaEnElPeriodo)
						echo "Esta!!";
					else
						echo "NOLA";
					echo "</td>";
					*/
				}							
				//echo "</tr>";
			}
			//Debugger::dump($arrayPeriodoTipoAsiento);
			//recorrido para controlar Asientos de Ventas
			//echo '<table>';
			foreach ($tiposdeasientos as $kta => $tipoasiento) {
				
				
				$variables =  explode("/", $tipoasiento);
				$nombreImpuesto = "";
				if($variables[1]!=""){
					foreach ($cliente['Impcli'] as $kimpcli => $impcli) {
						if($impcli['id']==$variables[1]){
							$nombreImpuesto .= $impcli['Impuesto']['nombre'];
						}							
					}
				}
				if($variables[2]!=""){
					foreach ($cliente['Impcli'] as $kimpcli => $impcli) {
						foreach ($impcli['Cbu'] as $kcbu => $cbu) {
							if($cbu['id']==$variables[2]){
								$nombreImpuesto .= $impcli['Impuesto']['nombre']." ".substr($cbu['numerocuenta'],-5);
							}							
						}							
					}
				}
				if(in_array($variables[0],["Devengamiento","impuestos2",'Registro','otros'])){
					continue;
				}	
				if($variables[0]=="impuestos"){
					if($variables[1]==""){
						//no vamos a mostrar los "devengamientos que no tengan impuestos relacionados"
						continue;
					}	
					$nombreAsiento = "Devengamiento";
				}else if($variables[0]=="bancos"){					
					$nombreAsiento = "Acreditaciones";
				}else if($variables[0]=="bancosretiros"){					
					$nombreAsiento = "Debitos";
				}else{
					$nombreAsiento = $variables[0];
				}
				echo '<tr>';
					echo '<td>'.$nombreImpuesto." ".$nombreAsiento."</td>";
				foreach ($arrayPeriodoTipoAsiento as $kp => $periodo) {
					if(isset($periodo[$tipoasiento])){
						echo "<td style='color:#0C0;'>SI</td>";
					}else{
						echo "<td style='color:#FF0000;'>NO</td>";
					}
				}
				echo '</tr>';			
			}
			
		?>		
	</tbody>
	<TFOOT>
		<tr>
			<td>Tipo Asiento</td>
		<?php
			$arrayPeriodos=[];
			$mesAMostrar = date('Y/m/d', strtotime($fechaInicioConsulta));
			while($mesAMostrar < $fechaFinConsulta){
			    echo "<td></td>";
			    $mesAMostrar = date('Y/m/d', strtotime($mesAMostrar." +1 months"));
			}
		?>
		</tr>
	</TFOOT>
	</table>	
	
</div>