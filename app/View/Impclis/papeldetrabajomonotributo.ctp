<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
 echo $this->Html->script('jquery.table2excel',array('inline'=>false));
 echo $this->Html->script('impclis/papeldetrabajomonotributo',array('inline'=>false));
 echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
 echo $this->Form->input('impcliidPDT',array('value'=>$impcliid,'type'=>'hidden'));
 echo $this->Form->input('impcliid',array('value'=>$impcliid,'type'=>'hidden'));
function validateDate($date, $format = 'Y-m-d H:i:s')
{
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) == $date;
}
?>
<div id="Formhead" class="clientes papeldetrabajoconveniomultilateral index" style="margin-bottom:10px;">
	<?php echo $this->Form->input('clinombre',array('value'=>$impcli['Cliente']['nombre'],'type'=>'hidden'));
	echo $this->Form->input('cliid',array('value'=>$impcli['Cliente']['id'],'type'=>'hidden'));
    $pemes = substr($periodo, 0, 2);
    $peanio = substr($periodo, 3);
    //vamos a versionar este Papel de trabajo
    //si la consulta es previa al 01-06-2018 entonces se hace por cuatrimestre
    //sino se hace por semestre 
    $version = 1;
    $fechaConsulta = $peanio.'-'.$pemes.'-01';
    if($fechaConsulta>='2018-06-01'){
        $version = 2;
    }
    
    ?>

	<div id="tabsTareaMonotributo" style="margin-left: 8px;">
		<div class="tabsTareaImpuesto_active" onClick="showRecategorizacion()" id="tab_Recategorizacion">
			<h2>Recategorizacion</h2>
		</div>
		<div class="tabsTareaImpuesto" onClick="showDDJJ()" id="tab_DDJJ"><h2>DDJJ</h2></div>
	</div>

	<div id="divRecategorizacion" class="">
            <!-- Solo para Excel Export -->
            <table id="tblExcelHeader" class="tbl_tareas" style="border-collapse: collapse; width:50%;">
            </table>
            <!-- Solo para Excel Export -->
            <div id="divLiquidarMonotributo">

            </div>
            <div id="divEditImpCliMonotributo" class="index">
            </div>
            <div id="divPapeldetrabajo" class="index">
                <b style="display: inline">Papel de Trabajo</b>
                <?php echo $this->Form->button('Imprimir',
                        array('type' => 'button',
                                'id'=>"btnImprimir",
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
                            <?php 
                            if($version==1){
                                echo "<td>Cuatrimestre</td>";
                            }else{
                               echo "<td>Semestre</td>";
                            } ?>
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
                        /*La mayor de estas dos variables decidir� si se aplica monotributo de Locacion o de Venta de cosa mueble*/
                        $TotalCuatrimestreIngresoBrutolocacion=0;
                        $TotalCuatrimestreIngresoBrutoMueble=0;
                        //Vamos a usar las dos de arriba para categorizar el monotributo pero las dos de abajo para elegir si aplica
                        // monotributo Locavion o Venta
                        $TotalCuatrimestreIngresoBrutolocacionActividadActiva=0;
                        $TotalCuatrimestreIngresoBrutoMuebleActividadActiva=0;
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
                                        $MesBajaActividad = "";
                                        $AnioBajaActividad = "";
                                        foreach ($actividadclientes as $actividadcliente) {
                                            if($actividadcliente['Actividadcliente']['id']==$venta['Venta']['actividadcliente_id']){
                                                $sumaen = $actividadcliente['Actividade']['generomonotributo'];
                                                if(validateDate($actividadcliente['Actividadcliente']['baja'])){
                                                    $MesBajaActividad = data('m',strtotime($actividadcliente['Actividadcliente']['baja']));
                                                    $AnioBajaActividad = data('Y',strtotime($actividadcliente['Actividadcliente']['baja']));
                                                }
                                            }
                                        }
                                        //si es factura C suma el total, si no suma el neto
                                        $campoaSumar = "total";
                                        if($venta['Comprobante']['tipo']=='C'){
                                            $campoaSumar = "total";
                                        }else{
                                            $campoaSumar = "neto";
                                        }
                                        if($venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso'){
                                            if($sumaen=='locacionservicio'){
                                                $subtotalVentaP0Locacion += $venta[0][$campoaSumar];
                                            }else{
                                                $subtotalVentaP0Mueble += $venta[0][$campoaSumar];
                                            }
                                            $subtotalVentaP0Total += $venta[0][$campoaSumar];
                                            if($AnioBajaActividad!=""){
                                                if(($peanio*1<$AnioBajaActividad*1)||
                                                    (($peanio*1==$AnioBajaActividad*1)&&($pemes*1<=$MesBajaActividad*1))
                                                )
                                                {
                                                    if($sumaen=='locacionservicio'){
                                                        $TotalCuatrimestreIngresoBrutolocacionActividadActiva += $venta[0][$campoaSumar];
                                                    }else{
                                                        $TotalCuatrimestreIngresoBrutoMuebleActividadActiva += $venta[0][$campoaSumar];
                                                    }
                                                }
                                            }else{
                                                if($sumaen=='locacionservicio'){
                                                    $TotalCuatrimestreIngresoBrutolocacionActividadActiva += $venta[0][$campoaSumar];
                                                }else{
                                                    $TotalCuatrimestreIngresoBrutoMuebleActividadActiva += $venta[0][$campoaSumar];
                                                }
                                            }

                                        }else{
                                            if($sumaen=='locacionservicio'){
                                                $subtotalVentaP0Locacion -= $venta[0][$campoaSumar];
                                            }else{
                                                $subtotalVentaP0Mueble -= $venta[0][$campoaSumar];
                                            }
                                            $subtotalVentaP0Total -= $venta[0][$campoaSumar];
                                            if($AnioBajaActividad!=""){
                                                if(($peanio*1<$AnioBajaActividad*1)||
                                                    (($peanio*1==$AnioBajaActividad*1)&&($pemes*1<=$MesBajaActividad*1))
                                                )
                                                {
                                                    if($sumaen=='locacionservicio'){
                                                        $TotalCuatrimestreIngresoBrutolocacionActividadActiva -= $venta[0][$campoaSumar];
                                                    }else{
                                                        $TotalCuatrimestreIngresoBrutoMuebleActividadActiva -= $venta[0][$campoaSumar];
                                                    }
                                                }
                                            }else{
                                                if($sumaen=='locacionservicio'){
                                                    $TotalCuatrimestreIngresoBrutolocacionActividadActiva -= $venta[0][$campoaSumar];
                                                }else{
                                                    $TotalCuatrimestreIngresoBrutoMuebleActividadActiva -= $venta[0][$campoaSumar];
                                                }
                                            }
                                        }
                                    }
                                }
                                ?>
                                <td >
                                        <?php echo $subtotalVentaP0Locacion;
                                        $TotalCuatrimestreIngresoBrutolocacion+=$subtotalVentaP0Locacion; ?>
                                </td>
                                <td >
                                        <?php echo $subtotalVentaP0Mueble;
                                        $TotalCuatrimestreIngresoBrutoMueble+=$subtotalVentaP0Mueble;?>
                                </td>
                                <td>
                                        <?php echo $subtotalVentaP0Total; ?>
                                </td>
                                <?php
                                if($version==1){
                                    if($i==0||$i==4||$i==8){
                                        for ($j=0; $j < 4; $j++) {
                                            foreach ($ventas as $venta) {
                                                $k=$j+$i;
                                                $periodoDeInicioj = $periodoDeInicio.' +'.$k.' months';
                                                //echo "PI:".$periodoDeInicio."%%PJ:".$periodoDeInicioj."//".$i."//".$j."$$</br>";
                                                $campoaSumar = "total";
                                                if($venta['Comprobante']['tipo']=='C'){
                                                    $campoaSumar = "total";
                                                }else{
                                                    $campoaSumar = "neto";
                                                }
                                                if($venta['Venta']['periodo']==date('m-Y',strtotime($periodoDeInicioj))){
                                                    if($venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso'){
                                                        $subtotalCuatrimestre += $venta[0][$campoaSumar];
                                                    }else{
                                                        $subtotalCuatrimestre -= $venta[0][$campoaSumar];
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
                                <?php }     
                                }else{
                                    if($i==0||$i==6){
                                        for ($j=0; $j < 6; $j++) {
                                            foreach ($ventas as $venta) {
                                                $k=$j+$i;
                                                $periodoDeInicioj = $periodoDeInicio.' +'.$k.' months';
                                                //echo "PI:".$periodoDeInicio."%%PJ:".$periodoDeInicioj."//".$i."//".$j."$$</br>";
                                                $campoaSumar = "total";
                                                if($venta['Comprobante']['tipo']=='C'){
                                                    $campoaSumar = "total";
                                                }else{
                                                    $campoaSumar = "neto";
                                                }
                                                if($venta['Venta']['periodo']==date('m-Y',strtotime($periodoDeInicioj))){
                                                    if($venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso'){
                                                        $subtotalCuatrimestre += $venta[0][$campoaSumar];
                                                    }else{
                                                        $subtotalCuatrimestre -= $venta[0][$campoaSumar];
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                        <td rowspan="6">
                                            <?php echo $subtotalCuatrimestre;
                                            $TotalCuatrimestreIngresoBruto+=$subtotalCuatrimestre;
                                            $subtotalCuatrimestre = 0;
                                            ?>
                                        </td>
                                        <td rowspan="6">
                                            <?php
                                            echo $subtotalCompraSuperficie;
                                            ?>
                                        </td>
                                <?php } 
                                }
                                ?>
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
                                <td title="Computable para Tipo Monotributo Locacion = <?php echo $TotalCuatrimestreIngresoBrutolocacionActividadActiva?>">
                                    <?php echo $TotalCuatrimestreIngresoBrutolocacion; ?>
                                </td>
                                <td title="Computable para Tipo Monotributo Venta = <?php echo $TotalCuatrimestreIngresoBrutoMuebleActividadActiva?>">
                                    <?php echo $TotalCuatrimestreIngresoBrutoMueble;
                                    if($TotalCuatrimestreIngresoBrutoMuebleActividadActiva>$TotalCuatrimestreIngresoBrutolocacionActividadActiva){
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
			<h1>Tabla Indicativa de la Categoria en la cual quedar� encuadrado el Monotibutista para el cuatrimestre siguiente (siempre se encuadra en la categoria con parámetro superior).</h1>
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
				$topCategoria=-1;
				foreach ($categoriamonotributos as $c => $categoriamonotributo) {
					$colorTDIngBrutos = "white";

					if($categoriamonotributo['Categoriamonotributo']['ingresobruto']*1 <= $TotalCuatrimestreIngresoBruto*1 ) {
						$colorTDIngBrutos = "lightgreen";
						$topOrden=$categoriamonotributo['Categoriamonotributo']['orden'];
						$topCategoria=$c;
					}

					$colorTDSuperficie = "white";
					if($categoriamonotributo['Categoriamonotributo']['superficiemaxima']*1 <= $subtotalCompraSuperficie*1 ) {
						$colorTDSuperficie = "lightgreen";
						$topOrden=$categoriamonotributo['Categoriamonotributo']['orden'];
						$topCategoria=$c;
					}

					$colorTDKW = "white";
					if($categoriamonotributo['Categoriamonotributo']['kwmaximo']*1 <= $subtotalCuatrimestreKW*1 ) {
						$colorTDKW = "lightgreen";
						$topOrden=$categoriamonotributo['Categoriamonotributo']['orden'];
						$topCategoria=$c;
					}
					$colorTDAlquiler = "white";
					if($categoriamonotributo['Categoriamonotributo']['alquileranualmaximo']*1 <= $subtotalCuatrimestreAlquiler*1 ) {
						$colorTDAlquiler = "lightgreen";
						$topOrden=$categoriamonotributo['Categoriamonotributo']['orden'];
						$topCategoria=$c;
					}
					?>
					<tr id="orden-<?php echo $categoriamonotributo['Categoriamonotributo']['orden'];?>">
						<td>
							<?php echo $categoriamonotributo['Categoriamonotributo']['categoria']; ?>
						</td>
						<td style="background-color:<?php echo $colorTDIngBrutos; ?>;">
							<?php echo $categoriamonotributo['Categoriamonotributo']['ingresobruto'];
							?>
						</td>
						<td style="background-color:<?php echo $colorTDSuperficie; ?>;">
							<?php echo $categoriamonotributo['Categoriamonotributo']['superficiemaxima']; ?>
						</td>
						<td style="background-color:<?php echo $colorTDKW; ?>;">
							<?php echo $categoriamonotributo['Categoriamonotributo']['kwmaximo']; ?>
						</td>
						<td style="background-color:<?php echo $colorTDAlquiler; ?>;">
							<?php echo $categoriamonotributo['Categoriamonotributo']['alquileranualmaximo']; ?>
						</td>
					</tr>
				<?php }

				echo $this->Form->input('mesParaProximaRecategorizacion',[
					'value'=>$mesParaProximaRecategorizacion,
					'type'=>'hidden'
				]);
                                echo $this->Form->input('version',[
					'value'=>$version,
					'type'=>'hidden'
				]);
                if($topCategoria == count($categoriamonotributos)-1){
                    $lastCategoriReach = $topCategoria;
                }else{
                    $lastCategoriReach = $topCategoria+1;
                }
                Debugger::dump($lastCategoriReach);
                Debugger::dump(count($categoriamonotributo));
				echo $this->Form->input('topCategoria',[
					'value'=>$categoriamonotributos[$lastCategoriReach]['Categoriamonotributo']['categoria'],
					'type'=>'hidden'
				]);
                echo $this->Form->input('topOrden',array('value'=>$categoriamonotributos[$lastCategoriReach]['Categoriamonotributo']['orden'],
                    'type'=>'hidden'
                ));
				?>
				</tbody>
			</table>
                        <?php
                        if($version==1){
                            ?>
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
                            <?php
                        }else{
                            ?>
                            <h1>Tabla indicativa del importe a facturar en el semestre siguiente y en el mes siguiente del semestre considerando una identica facturación para cada mes</h1>
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
                                            <td>Por Semestre</td>
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
                            <?php
                        }
                        ?>
		</div>
		<?php
		if(!$impuestosactivos['contabiliza']){ ?>
			<div id="divContenedorContabilidad" style="margin-top:10px">  </div>
			<?php
		}else{ ?>
                    <div id="divContenedorContabilidad" style="margin-top:10px;width:100%;">
                        <div class="index_pdt">
                            <b>Asiento de Devengamiento</b>
                            <?php
                            $Asientoid=0;
                            $movId=[];
                            if(isset($impcli['Asiento'])){
                                if(count($impcli['Asiento'])>0) {
                                    $Asientoid = $impcli['Asiento'][0]['id'];
                                    foreach ($impcli['Asiento'][0]['Movimiento'] as $mimovimiento) {
                                        $movId[$mimovimiento['Cuentascliente']['cuenta_id']] = $mimovimiento['id'];
                                    }
                                }
                            }
                            //ahora vamos a reccorer las cuentas relacionadas al IVA y las vamos a cargar en un formulario de Asiento nuevo
                            echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','controller'=>'asientos','action'=>'add']);
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
        //						'div' => false,
                                    'style'=> 'width:82px'
                            ));
                            echo $this->Form->input('Asiento.0.nombre',['readonly'=>'readonly','value'=>"Devengamiento Monotributo" ,'style'=>'width:250px']);
                            echo $this->Form->input('Asiento.0.descripcion',['readonly'=>'readonly','value'=>"Automatico",'style'=>'width:250px']);
                            echo $this->Form->input('Asiento.0.cliente_id',['value'=>$impcli['Cliente']['id'],'type'=>'hidden']);
                            echo $this->Form->input('Asiento.0.impcli_id',['value'=>$impcli['Impcli']['id'],'type'=>'hidden']);
                            echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo,'type'=>'hidden']);
                            echo $this->Form->input('Asiento.0.tipoasiento',['value'=>'impuestos','type'=>'hidden'])."</br>";
                            $i=0;
                            $totalDebe=0;
                            $totalHaber=0;
                            foreach ($impcli['Impuesto']['Asientoestandare'] as $asientoestandaractvs) {
                                    if(!isset($movId[$asientoestandaractvs['cuenta_id']])){
                                            $movId[$asientoestandaractvs['cuenta_id']]=0;
                                    }
                                    $cuentaclienteid=0;
                                    $cuentaclientenombre=$asientoestandaractvs['Cuenta']['nombre'];
                                    foreach ($impcli['Cliente']['Cuentascliente'] as $cuentaclientaIVA){
                                            if($cuentaclientaIVA['cuenta_id']==$asientoestandaractvs['cuenta_id']){
                                                    $cuentaclienteid=$cuentaclientaIVA['id'];
                                                    $cuentaclientenombre=$cuentaclientaIVA['nombre'];
                                                    break;
                                            }
                                    }
                                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['value'=>$movId[$asientoestandaractvs['cuenta_id']],]);
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
                                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuenta_id',['readonly'=>'readonly','type'=>'hidden','orden'=>$i,'value'=>$asientoestandaractvs['cuenta_id'],'id'=>'cuenta'.$asientoestandaractvs['cuenta_id']]);
                                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.numero',['label'=>false,'readonly'=>'readonly','value'=>$asientoestandaractvs['Cuenta']['numero'],'style'=>'width:82px']);
                                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.nombre',['label'=>false,'readonly'=>'readonly','value'=>$cuentaclientenombre,'type'=>'text','style'=>'width:250px']);
                                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
                                            'label'=>false,
                                            'value'=>0,
                                            'class'=>"inputDebe "
                                    ]);
                                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
                                                    'label'=>false,
                                                    'value'=>0,
                                                    'class'=>"inputHaber "
                                            ])."</br>";
                                    $i++;
                            }

                            echo $this->Form->submit('Contabilizar',['style'=>'display:none']);
                            echo $this->Form->end();
                            $totalDebe=0;
                            $totalHaber=0;
                            echo $this->Form->label('','&nbsp; ',[
                                    'style'=>"display: -webkit-inline-box;width:355px;"
                            ]);
                            echo $this->Form->label('lblTotalDebe',
                                    "$".number_format($totalDebe, 2, ".", ""),
                                    [
                                            'id'=>'lblTotalDebe',
                                            'style'=>"display: inline;"
                                    ]
                            );
                            echo $this->Form->label('','&nbsp;',['style'=>"display: -webkit-inline-box;width:100px;"]);
                            echo $this->Form->label('lblTotalHaber',
                                    "$".number_format($totalHaber, 2, ".", ""),
                                    [
                                            'id'=>'lblTotalHaber',
                                            'style'=>"display: inline;"
                                    ]
                            );
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
                            ?>
                        </div>
                    </div>
            <?php } ?>
	</div>
    <input type="hidden" id="haycambios" value="1">
	<div id="divDDJJ">

	</div>
</div>

