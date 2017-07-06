<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false)); 
echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('impclis/papeldetrabajosindicatos',array('inline'=>false)); 
echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
echo $this->Form->input('impcliidPDT',array('value'=>$impcliid,'type'=>'hidden'));
echo $this->Form->input('clinombre',array('value'=>$impcli['Cliente']['nombre'],'type'=>'hidden'));
echo $this->Form->input('impclinombre',array('value'=>$impcliSolicitado['Impuesto']['nombre'],'type'=>'hidden'));
echo $this->Form->input('impcliid',array('value'=>$impcliid,'type'=>'hidden'));
echo $this->Form->input('cliid',array('value'=>$impcli['Cliente']['id'],'type'=>'hidden'));?>

<div class="index">
	<div id="Formhead" class="clientes papeldetrabajosindicato index" style="margin-bottom:10px;">
		<h2>Sindicato: <?php echo $impcliSolicitado['Impuesto']['nombre']; ?></h2>
		Contribuyente: <?php echo $impcliSolicitado['Cliente']['nombre']; ?></br>
		CUIT: <?php echo $impcliSolicitado['Cliente']['cuitcontribullente']; ?></br>
		Periodo: <?php echo $periodo; ?>
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
	</div>
    <div id="divLiquidarSindicatos">
    </div>
	<div id="sheetSindicato" class="index">
		<!--Esta es la tabla original y vamos a recorrer todos los empleados por cada una de las
        rows por que -->
		<?php
		$empleadoDatos = array();
        $nombrecuotaSindical = "";
        $nombrecuotaSindical1 = "";
        $nombrecuotaSindical2 = "";
        $nombrecuotaSindical3 = "";
        $nombrecuotaSindical4 = "";

        $pagaINACAP = false;


        foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
            /*Aca vamos a ver si para o no paga algunas contribuciones sindicales segun que convenio tenga*/

            foreach ($conveniocolectivo['Empleado'] as $empleado) {
                $jornada = 0;
                $horasDias = 0;
                $afiliadoSindicato = 0;
                $remuneracionCD = 0;
                $SACremunerativo = 0;
                $remuneracionSD = 0;
                $remuneracionSDExceptoIndemnizatorio = 0;
                $remuneracionSDIndemnizatorio = 0;
                $remuneracionTotal = 0;
                $aportes = 0;
                $neto = 0;
                $miempleado = array();

                $cuotasindical = 0;
                $cuotasindical1 = 0;
                $cuotasindical2 = 0;
                $cuotasindical3 = 0;
                $cuotasindical4 = 0;

                if (!isset($miempleado['horasDias'])) {
                    $miempleado['jornada'] = 0;
                    $miempleado['horasDias'] = 0;
                    $miempleado['afiliadoSindicato'] = false;
                    $miempleado['remuneracionCD'] = 0;
                    $miempleado['SACremunerativo'] = 0;
                    $miempleado['remuneracionSD'] = 0;
                    $miempleado['remuneracionSDExceptoIndemnizatorio'] = 0;
                    $miempleado['remuneracionSDIndemnizatorio'] = 0;
                    $miempleado['remuneracionTotal'] = 0;
                    $miempleado['aportes'] = 0;
                    $miempleado['neto'] = 0;
                    $miempleado['cuotasindical'] = 0;
                    $miempleado['cuotasindical1'] = 0;
                    $miempleado['cuotasindical2'] = 0;
                    $miempleado['cuotasindical3'] = 0;
                    $miempleado['cuotasindical4'] = 0;
                }
                foreach ($empleado['Valorrecibo'] as $valorrecibo) {
                    //Horas Diarias
                    if ($valorrecibo['Cctxconcepto']['concepto_id'] == '11'/*Jornada*/) {
                        $jornada += $valorrecibo['valor'];
                    }
                    //Horas Diarias
                    if ($valorrecibo['Cctxconcepto']['concepto_id'] == '112'/*Horas*/) {
                        $horasDias += $valorrecibo['valor'];
                    }
                    //Afiliado al Sindicato
                    if ($valorrecibo['Cctxconcepto']['concepto_id'] == '39'/*Afiliado al Sindicato*/) {
                        $afiliadoSindicato += $valorrecibo['valor'];
                    }
                    //Remuneracion CD
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('27'/*Total Remunerativos C/D*/), true)
                    ) {
                        $remuneracionCD += $valorrecibo['valor'];
                    }
                    //SAC remunerativo
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('92'/*SAC remunerativo 1*/), true)
                    ) {
                        $SACremunerativo += $valorrecibo['valor'];
                    }
                    //Remuneracion SD
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('109'/*Total Remunerativos S/D*/), true)
                    ) {
                        $remuneracionSD += $valorrecibo['valor'];
                    }
                    //Remuneracion SD Excepto Indemnizatorios
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('103'/*Total Rem. S/D Excepto Indemnizatorio*/), true)
                    ) {
                        $remuneracionSDExceptoIndemnizatorio += $valorrecibo['valor'];
                    }
                    //Total Rem. S/D Indemnizatorios
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('108'/*Total Rem. S/D Indemnizatorio*/), true)
                    ) {
                        $remuneracionSDIndemnizatorio += $valorrecibo['valor'];
                    }
                    //Total Remuneracion
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('44'/*Remuneración Total	*/), true)
                    ) {
                        $remuneracionTotal += $valorrecibo['valor'];
                    }
                    //Total Aportes
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('45'/*Aportes	*/), true)
                    ) {
                        $aportes += $valorrecibo['valor'];
                    }

                    //Total Neto
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('46'/*Neto	*/), true)
                    ) {
                        $neto += $valorrecibo['valor'];
                    }
                    //Total Cuota Sindical
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('36'/*Cuota Sindical	*/), true)
                    ) {
                        $cuotasindical += $valorrecibo['valor'];
                    }
                    //Total Cuota Sindical1
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('37'/*Cuota Sindical Extra 1	*/), true)
                    ) {
                        $cuotasindical1 += $valorrecibo['valor'];
                    }
                    //Total Cuota Sindical 2
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('38'/*Cuota Sindical Extra 2	*/), true)
                    ) {
                        $cuotasindical2 += $valorrecibo['valor'];
                    }
                    //Total Cuota Sindical 3
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('114'/*Cuota Sindical Extra 3	*/), true)
                    ) {
                        $cuotasindical3 += $valorrecibo['valor'];
                    }
                    //Total Cuota Sindical 4
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('134'/*Cuota Sindical Extra 4	*/), true)
                    ) {
                        $cuotasindical4 += $valorrecibo['valor'];
                    }
                    //Nombre Cuota Sindical
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('36'/*Cuota Sindical	*/), true)
                    ) {
                        $nombrecuotaSindical = $valorrecibo['Cctxconcepto']['nombre'];
                    }
                    //Nombre Cuota Sindical 1
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('37'/*Cuota extra Sindical 1 	*/), true)
                    ) {
                        $nombrecuotaSindical1 = $valorrecibo['Cctxconcepto']['nombre'];
                    }
                    //Nombre Cuota Sindical 2
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('38'/*Cuota extra Sindical 2	*/), true)
                    ) {
                        $nombrecuotaSindical2 = $valorrecibo['Cctxconcepto']['nombre'];
                    }
                    //Nombre Cuota Sindical 3
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('114'/*Cuota extra Sindical 3	*/), true)
                    ) {
                        $nombrecuotaSindical3 = $valorrecibo['Cctxconcepto']['nombre'];
                    }
                    //Nombre Cuota Sindical 4
                    if (
                    in_array($valorrecibo['Cctxconcepto']['concepto_id'],
                        array('134'/*Cuota extra Sindical 4	*/), true)
                    ) {
                        $nombrecuotaSindical4 = $valorrecibo['Cctxconcepto']['nombre'];
                    }
                }
                $miempleado['jornada'] = $jornada;
                $miempleado['horasDias'] = $horasDias;
                $miempleado['afiliadoSindicato'] = $afiliadoSindicato;
                $miempleado['remuneracionCD'] = $remuneracionCD;
                $miempleado['SACremunerativo'] = $SACremunerativo;
                $miempleado['remuneracionSD'] = $remuneracionSD;
                $miempleado['remuneracionSDExceptoIndemnizatorio'] = $remuneracionSDExceptoIndemnizatorio;
                $miempleado['remuneracionSDIndemnizatorio'] = $remuneracionSDIndemnizatorio;
                $miempleado['remuneracionTotal'] = $remuneracionTotal;
                $miempleado['aportes'] = $aportes;
                $miempleado['neto'] = $neto;
                $miempleado['cuotasindical'] = $cuotasindical;
                $miempleado['cuotasindical1'] = $cuotasindical1;
                $miempleado['cuotasindical2'] = $cuotasindical2;
                $miempleado['cuotasindical3'] = $cuotasindical3;
                $miempleado['cuotasindical4'] = $cuotasindical4;

                $empleadoid = $empleado['id'];
                $empleadoDatos[$empleadoid] = $miempleado;
            }
        }
		unset($miempleado);
		if(count($conceptosrestantes)>0) {
			foreach ($conceptosrestantes['Conceptosrestante'] as $conceptosrestante) {
				switch ($conceptosrestante['conceptostipo_id']) {
					case '2':
					case '3':
						$Retenciones += $conceptosrestante['montoretenido'];
						break;
					case '4':
						$OtrosPagosACuenta += $conceptosrestante['montoretenido'];
						break;
				}
			}
			unset($conceptosrestante);
		}
		if(count($impcliSaldoAFavor)>0) {
			foreach ($impcliSaldoAFavor['Conceptosrestante'] as $saldosafavor) {
				$SaldoAFavor+=$saldosafavor['montoretenido'];
			}
			unset($saldosafavor);
		}
		?>
		<table class="tblInforme tbl_border" cellspacing="0" id="tblSindicatos">
			<tr>
				<td>Legajo</td>
				<?php
                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado) {
                        echo "<td>" . $empleado['legajo'] . "</td>";
                    }
				}
				?>
			</tr>
			<tr>
				<td>Apellido y Nombre</td>
				<?php
                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado) {
                        echo "<td>" . $empleado['nombre'] . "</td>";
                    }
				}
				?>
			</tr><!--1-->
			<tr>
				<td>CUIL</td>
				<?php
                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado) {
                        echo "<td>" . $empleado['cuit'] . "</td>";
                    }
				}
				?>
			</tr>
            <tr>
                <td>Categoria</td>
                <?php
                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado) {
                        echo "<td>";
                        echo $empleado['categoria'];
                        echo "</td>";
                    }
                }
                ?>
            </tr>
            <tr>
                <td>Fecha de Ingreso</td>
                <?php
                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado) {
                        echo "<td>";
                        echo $empleado['fechaingreso'];
                        echo "</td>";
                    }
                }
                ?>
            </tr>
			<tr>
				<td>Jornada</td>
				<?php
                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado){
                        $empleadoid = $empleado['id'];
                        //en este primer loop vamos a calcular todos los siguientes totales
                        echo "<td>";
                        echo $empleadoDatos[$empleadoid]['jornada'];
                        echo "</td>";
                    }
				}
				?>
			</tr>
            <tr>
                <td>Dias trabajados u horas</td>
                <?php
                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado){
                        $empleadoid = $empleado['id'];
                        //en este primer loop vamos a calcular todos los siguientes totales
                        echo "<td>";
                        echo $empleadoDatos[$empleadoid]['horasDias'];
                        echo "</td>";
                    }
                }
                ?>
            </tr>
            <tr>
                <td>Afiliado al sindicato</td>
                <?php
                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado){
                        $empleadoid = $empleado['id'];
                        //en este primer loop vamos a calcular todos los siguientes totales
                        echo "<td>";
                        echo $empleadoDatos[$empleadoid]['afiliadoSindicato']?'SI':'NO';
                        echo "</td>";
                    }
                }
                ?>
                <td>Totales</td>
            </tr>
            <tr>
                <td>Remuneracion C/D</td>
                <?php
                $totalRemCD = 0;
                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado){
                        $empleadoid = $empleado['id'];
                        //en este primer loop vamos a calcular todos los siguientes totales
                        echo "<td>";
                        echo number_format($empleadoDatos[$empleadoid]['remuneracionCD'], 2, ",", ".");
                        $totalRemCD += $empleadoDatos[$empleadoid]['remuneracionCD'];
                        echo "</td>";
                    }
                }
                ?>
                <td><?php  echo number_format($totalRemCD, 2, ",", "."); ?></td>

            </tr>
            <tr>
                <td>Remuneración S/D Excepto Indemnizatorio</td>
                <?php
                $totalRemSDEI = 0;
                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado){
                        $empleadoid = $empleado['id'];
                        //en este primer loop vamos a calcular todos los siguientes totales
                        echo "<td>";
                        echo $empleadoDatos[$empleadoid]['remuneracionSDExceptoIndemnizatorio'];
                        $totalRemSDEI += $empleadoDatos[$empleadoid]['remuneracionSDExceptoIndemnizatorio'];
                        echo "</td>";
                    }
                }
                ?>
                <td><?php  echo number_format($totalRemSDEI, 2, ",", "."); ?></td>
            </tr>
            <tr>
                <td>Remuneración S/D Indemnizatorio</td>
                <?php
                $totalRemSDI = 0;
                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado){
                        $empleadoid = $empleado['id'];
                        //en este primer loop vamos a calcular todos los siguientes totales
                        echo "<td>";
                        echo $empleadoDatos[$empleadoid]['remuneracionSDIndemnizatorio'];
                        $totalRemSDI += $empleadoDatos[$empleadoid]['remuneracionSDIndemnizatorio'];
                        echo "</td>";
                    }
                }
                ?>
                <td><?php  echo number_format($totalRemSDI, 2, ",", "."); ?></td>
            </tr>
            <tr>
                <td>Remuneración Total</td>
                <?php
                $totalTotal = 0;
                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado){
                        $empleadoid = $empleado['id'];
                        //en este primer loop vamos a calcular todos los siguientes totales
                        echo "<td>";
                        echo $empleadoDatos[$empleadoid]['remuneracionTotal'];
                        $totalTotal += $empleadoDatos[$empleadoid]['remuneracionTotal'];
                        echo "</td>";
                    }
                }
                ?>
                <td><?php  echo number_format($totalTotal, 2, ",", "."); ?></td>
            </tr>
            <tr>
                <td>Aportes</td>
                <?php
                $totalAportes = 0;
                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado){
                        $empleadoid = $empleado['id'];
                        //en este primer loop vamos a calcular todos los siguientes totales
                        echo "<td>";
                        echo $empleadoDatos[$empleadoid]['aportes'];
                        $totalAportes += $empleadoDatos[$empleadoid]['aportes'];
                        echo "</td>";
                    }
                }
                ?>
                <td><?php  echo number_format($totalAportes, 2, ",", "."); ?></td>
            </tr>
            <tr>
                <td>Neto</td>
                <?php
                $totalNeto = 0;
                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado){
                        $empleadoid = $empleado['id'];
                        //en este primer loop vamos a calcular todos los siguientes totales
                        echo "<td>";
                        echo $empleadoDatos[$empleadoid]['neto'];
                        $totalNeto += $empleadoDatos[$empleadoid]['neto'];
                        echo "</td>";
                    }
                }
                ?>
                <td><?php  echo number_format($totalNeto, 2, ",", "."); ?></td>
            </tr>
            <tr>
                <td colspan="150">Aportes Sindicales</td>
            </tr>
            <tr>
                <?php
                $totalCuotaSindical = 0;
                if($nombrecuotaSindical!=""){
                    echo "<td>";
                    echo $nombrecuotaSindical;
                    echo "</td>";

                    foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                        foreach ($conveniocolectivo['Empleado'] as $empleado){
                            $empleadoid = $empleado['id'];
                            //en este primer loop vamos a calcular todos los siguientes totales
                            echo "<td>";
                            echo $empleadoDatos[$empleadoid]['cuotasindical'];
                            $totalCuotaSindical += $empleadoDatos[$empleadoid]['cuotasindical'];
                            echo "</td>";
                        }
                    }
                }
                ?>
                <td><?php  echo number_format($totalCuotaSindical, 2, ",", "."); ?></td>
            </tr>
            <tr>
                <?php
                $totalCuotaSindical1 = 0;
                if($nombrecuotaSindical1!=""){
                    echo "<td>";
                    echo $nombrecuotaSindical1;
                    echo "</td>";

                    foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                        foreach ($conveniocolectivo['Empleado'] as $empleado){
                            $empleadoid = $empleado['id'];
                            //en este primer loop vamos a calcular todos los siguientes totales
                            echo "<td>";
                            echo $empleadoDatos[$empleadoid]['cuotasindical1'];
                            $totalCuotaSindical1 += $empleadoDatos[$empleadoid]['cuotasindical1'];
                            echo "</td>";
                        }

                    }
                ?>
                <td><?php  echo number_format($totalCuotaSindical1, 2, ",", "."); ?></td>
                <?php
                }
                ?>
            </tr>
            <tr>
                <?php
                $totalCuotaSindical2 = 0;
                if($nombrecuotaSindical2!=""){
                    echo "<td>";
                    echo $nombrecuotaSindical2;
                    echo "</td>";

                    foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                        foreach ($conveniocolectivo['Empleado'] as $empleado){
                            $empleadoid = $empleado['id'];
                            //en este primer loop vamos a calcular todos los siguientes totales
                            echo "<td>";
                            echo $empleadoDatos[$empleadoid]['cuotasindical2'];
                            $totalCuotaSindical2 += $empleadoDatos[$empleadoid]['cuotasindical2'];
                            echo "</td>";
                        }
                    }?>
                <td><?php  echo number_format($totalCuotaSindical2, 2, ",", "."); ?></td>
                <?php
                }
                ?>
            </tr>
            <tr>
                <?php
                $totalCuotaSindical3 = 0;
                if($nombrecuotaSindical3!="") {
                    echo "<td>";
                    echo $nombrecuotaSindical3;
                    echo "</td>";

                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado){
                        $empleadoid = $empleado['id'];
                        //en este primer loop vamos a calcular todos los siguientes totales
                        echo "<td>";
                        echo $empleadoDatos[$empleadoid]['cuotasindical3'];
                        $totalCuotaSindical3 += $empleadoDatos[$empleadoid]['cuotasindical3'];
                        echo "</td>";
                    }
                }
                ?>
                <td><?php  echo number_format($totalCuotaSindical3, 2, ",", "."); ?></td>
                <?php } ?>
            </tr>
            <tr>
                <?php
                $totalCuotaSindical4 = 0;
                if($nombrecuotaSindical4!="") {
                    echo "<td>";
                    echo $nombrecuotaSindical4;
                    echo "</td>";

                foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                    foreach ($conveniocolectivo['Empleado'] as $empleado){
                        $empleadoid = $empleado['id'];
                        //en este primer loop vamos a calcular todos los siguientes totales
                        echo "<td>";
                        echo $empleadoDatos[$empleadoid]['cuotasindical4'];
                        $totalCuotaSindical4 += $empleadoDatos[$empleadoid]['cuotasindical4'];
                        echo "</td>";
                    }
                }
                ?>
                <td><?php  echo number_format($totalCuotaSindical4, 2, ",", "."); ?></td>
                <?php } ?>
            </tr>
            <tr>
                <td colspan="150">
                    Contribuciones Sindicales</td>
            </tr>
            <?php
            $totalContribucion = 0;
            $apagarcontribuciones = 0;
            $totalConvenioFEGHRA =0;
            switch ($impcliSolicitado['Impuesto']['id']) {
                case '11':/*SEC*/

                    break;
                case '23':/*FAECYS*/
                    break;
                case '24':/*INACAP*/
                    ?>
                    <tr>
                        <td>
                            INACAP
                        </td>
                        <?php
                            foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                                foreach ($conveniocolectivo['Empleado'] as $empleado) {
                                    $empleadoid = $empleado['id'];
                                    //en este primer loop vamos a calcular todos los siguientes totales
                                    //Solo calcular este INACAP si es comercio
                                    if($empleado['conveniocolectivotrabajo_id']=='3'){
                                        $title="Cuota vigente a marzo 2017 (13658.32*0.005)= 68.29
+
Salarizacion del aumento del 12% aplicable desde octubre 2016 (1290.55*0.005) = 6.45 
Total = 74.74";
                                        echo "<td title='".$title."'>";
                                        echo (13658.32*0.005)+(1290.55*0.005);
                                        $totalContribucion += (13658.32*0.005)+(1290.55*0.005);
                                        $apagarcontribuciones +=(13658.32*0.005)+(1290.55*0.005);
                                        echo "</td>";
                                    }else{
                                        $title="No paga INACAP por que no es de Comercio";
                                        echo "<td title='".$title."'>0</td>";
                                    }

                                }
                            }
                        ?>
                        <td>
                            <?php echo number_format($totalContribucion, 2, ",", "."); ?>
                        </td>
                    </tr>
                <?php
                    break;
                case '25':/*UTHGRA*/
//                    UTHGRA Seguro Sepelio y Vida
//                    UTHGRA Fdo Convenio FEHGRA
//                    UTHGRA Contr. Asist Sind - Salta
//                    UTHGRA Contribución Especial
                    ?>
                    <tr>
                        <td>
                            UTHGRA Seguro Sepelio y Vida
                        </td>
                        <?php
                        $totalCuotasindical1 =0;
                        foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                            foreach ($conveniocolectivo['Empleado'] as $empleado) {
                                $empleadoid = $empleado['id'];
                                //en este primer loop vamos a calcular todos los siguientes totales
                                echo "<td>";
                                echo $empleadoDatos[$empleadoid]['cuotasindical1'];
                                $totalCuotasindical1 += $empleadoDatos[$empleadoid]['cuotasindical1'];
                                $apagarcontribuciones += $empleadoDatos[$empleadoid]['cuotasindical1'];
                                echo "</td>";
                            }
                        }
                        ?>
                        <td>
                            <?php echo number_format($totalCuotasindical1, 2, ",", "."); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            UTHGRA Fdo Convenio FEHGRA
                        </td>
                        <?php
                        foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                            foreach ($conveniocolectivo['Empleado'] as $empleado) {
                                $empleadoid = $empleado['id'];
                                //en este primer loop vamos a calcular todos los siguientes totales
                                echo "<td>";
                                $convFeg = $empleadoDatos[$empleadoid]['remuneracionCD'];
                                $convFeg = $convFeg*0.02;
                                echo number_format($convFeg, 2, ",", ".");
                                $totalConvenioFEGHRA += $convFeg ;
                                $apagarcontribuciones += $convFeg ;
                                echo "</td>";
                            }
                        }
                        ?>
                        <td>
                            <?php echo number_format($totalConvenioFEGHRA, 2, ",", "."); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            UTHGRA Contribución Especial
                        </td>
                        <?php
                        $totalContribucionEspecialUTHGRA =0;
                        foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                            foreach ($conveniocolectivo['Empleado'] as $empleado) {
                                $empleadoid = $empleado['id'];
                                //en este primer loop vamos a calcular todos los siguientes totales
                                echo "<td>";
                                $contUth = 0;
                                echo number_format($contUth, 2, ",", ".");
                                $totalContribucionEspecialUTHGRA += $contUth ;
                                $apagarcontribuciones += $contUth ;
                                echo "</td>";
                            }
                        }
                        ?>
                        <td>
                            <?php echo number_format($totalContribucionEspecialUTHGRA, 2, ",", "."); ?>
                        </td>
                    </tr>
                    <?php
                    break;
                case '41':/*UOCRA*/ ?>
                    <tr>
                        <td>
                            UOCRA Fdo Cese Laboral
                        </td>
                        <?php
                        $totalContribucionFdoCeseLaboral =0;
                        foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                            foreach ($conveniocolectivo['Empleado'] as $empleado) {
                                $empleadoid = $empleado['id'];
                                //El primer año se paga el 12% y despues el 8%
                                $periodoALiquidar = new DateTime(date('Y-m-d',strtotime('01-'.$periodo)));
                                $fechaIngreso = new DateTime(date('Y-m-d',strtotime($empleado['fechaingreso'])));
                                $diff = $periodoALiquidar->diff($fechaIngreso);
                                $titleUOCRAFdoCeseLaboral = "";
                                if($diff->y > 1){
                                    $titleUOCRAFdoCeseLaboral .= "Antiguedad: ".$diff->y ." =>(".$empleadoDatos[$empleadoid]['remuneracionCD']."-".$empleadoDatos[$empleadoid]['SACremunerativo'].")*0.08";
                                    $contUocraFdoCeseLaboral = ($empleadoDatos[$empleadoid]['remuneracionCD']*1-$empleadoDatos[$empleadoid]['SACremunerativo']*1)*0.08 ;
                                }else{
                                    $titleUOCRAFdoCeseLaboral .= "Antiguedad: ".$diff->y ." =>(".$empleadoDatos[$empleadoid]['remuneracionCD']."-".$empleadoDatos[$empleadoid]['SACremunerativo'].")*0.12";
                                    $contUocraFdoCeseLaboral = ($empleadoDatos[$empleadoid]['remuneracionCD']*1-$empleadoDatos[$empleadoid]['SACremunerativo']*1)*0.12 ;
                                }
                                echo '<td title="'.$titleUOCRAFdoCeseLaboral.'">';
                                echo number_format($contUocraFdoCeseLaboral, 2, ",", ".");
                                $totalContribucionFdoCeseLaboral += $contUocraFdoCeseLaboral ;
                                echo "</td>";
                            }
                        }
                        ?>
                        <td >
                            <?php echo number_format($totalContribucionFdoCeseLaboral, 2, ",", "."); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            UOCRA Aporte FICS
                        </td>
                        <?php
                        $totalContribucionUocraAporteFics =0;
                        foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                            foreach ($conveniocolectivo['Empleado'] as $empleado) {
                                $empleadoid = $empleado['id'];
                                //en este primer loop vamos a calcular todos los siguientes totales

                                $contUocraFdoCeseLaboral=0;
                                $periodoALiquidar = new DateTime(date('Y-m-d',strtotime('01-'.$periodo)));
                                $fechaIngreso = new DateTime(date('Y-m-d',strtotime($empleado['fechaingreso'])));
                                $diff = $periodoALiquidar->diff($fechaIngreso);
                                $titleUOCRAAporteFICS = "";
                                if($diff->y > 1){
                                    $titleUOCRAAporteFICS .= "Antiguedad: ".$diff->y ." =>(".$empleadoDatos[$empleadoid]['remuneracionCD']."-".$empleadoDatos[$empleadoid]['SACremunerativo'].")*0.08";
                                    $contUocraFdoCeseLaboral = ($empleadoDatos[$empleadoid]['remuneracionCD']*1-$empleadoDatos[$empleadoid]['SACremunerativo']*1)*0.08 ;
                                }else{
                                    $titleUOCRAAporteFICS .= "Antiguedad: ".$diff->y ." =>(".$empleadoDatos[$empleadoid]['remuneracionCD']."-".$empleadoDatos[$empleadoid]['SACremunerativo'].")*0.12";
                                    $contUocraFdoCeseLaboral = ($empleadoDatos[$empleadoid]['remuneracionCD']*1-$empleadoDatos[$empleadoid]['SACremunerativo']*1)*0.12 ;
                                }

                                $contUocraAporteFics = $contUocraFdoCeseLaboral*0.02 ;
                                echo '<td title="'.$titleUOCRAAporteFICS.'">';
                                echo number_format($contUocraAporteFics, 2, ",", ".");
                                $totalContribucionUocraAporteFics += $contUocraAporteFics ;
                                $apagarcontribuciones += $contUocraAporteFics ;
                                echo "</td>";
                            }
                        }
                        ?>
                        <td>
                            <?php echo number_format($totalContribucionUocraAporteFics, 2, ",", "."); ?>
                        </td>
                    </tr>

                    <?php
                    break;
                case '153':/*IERIC*/ ?>
                    <tr>
                        <td>
                            UOCRA Fdo Cese Laboral
                        </td>
                        <?php
                        $totalContribucionFdoCeseLaboral =0;
                        foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                            foreach ($conveniocolectivo['Empleado'] as $empleado) {
                                $empleadoid = $empleado['id'];
                                //El primer año se paga el 12% y despues el 8%
                                $periodoALiquidar = new DateTime(date('Y-m-d',strtotime('01-'.$periodo)));
                                $fechaIngreso = new DateTime(date('Y-m-d',strtotime($empleado['fechaingreso'])));
                                $diff = $periodoALiquidar->diff($fechaIngreso);
                                $titleUOCRAFdoCeseLaboral = "";
                                if($diff->y > 1){
                                    $titleUOCRAFdoCeseLaboral .= "Antiguedad: ".$diff->y ." =>(".$empleadoDatos[$empleadoid]['remuneracionCD']."-".$empleadoDatos[$empleadoid]['SACremunerativo'].")*0.08";
                                    $contUocraFdoCeseLaboral = ($empleadoDatos[$empleadoid]['remuneracionCD']*1-$empleadoDatos[$empleadoid]['SACremunerativo']*1)*0.08 ;
                                }else{
                                    $titleUOCRAFdoCeseLaboral .= "Antiguedad: ".$diff->y ." =>(".$empleadoDatos[$empleadoid]['remuneracionCD']."-".$empleadoDatos[$empleadoid]['SACremunerativo'].")*0.12";
                                    $contUocraFdoCeseLaboral = ($empleadoDatos[$empleadoid]['remuneracionCD']*1-$empleadoDatos[$empleadoid]['SACremunerativo']*1)*0.12 ;
                                }
                                echo '<td title="'.$titleUOCRAFdoCeseLaboral.'">';
                                echo number_format($contUocraFdoCeseLaboral, 2, ",", ".");
                                $totalContribucionFdoCeseLaboral += $contUocraFdoCeseLaboral ;
                                echo "</td>";
                            }
                        }
                        ?>
                        <td >
                            <?php echo number_format($totalContribucionFdoCeseLaboral, 2, ",", "."); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            IERIC
                        </td>
                        <?php
                        $totalContribucionIERIC =0;
                        foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                            foreach ($conveniocolectivo['Empleado'] as $empleado) {
                                $empleadoid = $empleado['id'];
                                //El primer año se paga el 12% y despues el 8%
                                $periodoALiquidar = new DateTime(date('Y-m-d',strtotime('01-'.$periodo)));
                                $fechaIngreso = new DateTime(date('Y-m-d',strtotime($empleado['fechaingreso'])));
                                $diff = $periodoALiquidar->diff($fechaIngreso);
                                $titleUOCRAFdoCeseLaboral = "";
                                if($diff->y > 1){
                                    $titleUOCRAFdoCeseLaboral .= "Antiguedad: ".$diff->y ." =>(".$empleadoDatos[$empleadoid]['remuneracionCD']."-".$empleadoDatos[$empleadoid]['SACremunerativo'].")*0.08";
                                    $contribucionIERIC = ($empleadoDatos[$empleadoid]['remuneracionCD']*1-$empleadoDatos[$empleadoid]['SACremunerativo']*1)*0.08 ;
                                }else{
                                    $titleUOCRAFdoCeseLaboral .= "Antiguedad: ".$diff->y ." =>(".$empleadoDatos[$empleadoid]['remuneracionCD']."-".$empleadoDatos[$empleadoid]['SACremunerativo'].")*0.12";
                                    $contribucionIERIC = ($empleadoDatos[$empleadoid]['remuneracionCD']*1-$empleadoDatos[$empleadoid]['SACremunerativo']*1)*0.12 ;
                                }
                                $contribucionIERIC = $contribucionIERIC*0.02;
                                echo '<td title="'.$titleUOCRAFdoCeseLaboral.'">';
                                echo number_format($contribucionIERIC, 2, ",", ".");
                                $totalContribucionIERIC += $contribucionIERIC ;
                                $apagarcontribuciones += $contribucionIERIC ;
                                echo "</td>";
                            }
                        }
                        ?>
                        <td >
                            <?php echo number_format($totalContribucionIERIC, 2, ",", "."); ?>
                        </td>
                    </tr>
                    <?php
                    break;
                case '178':/*ACARA*/ ?>
                    <tr>
                        <td>
                            Contribucion Empresarial
                        </td>
                        <?php
                        $totalContribucionEmpresarial =0;
                        foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
                            foreach ($conveniocolectivo['Empleado'] as $empleado) {
                                $empleadoid = $empleado['id'];

                                $contribucionEmpresarial = $empleadoDatos[$empleadoid]['cuotasindical1']+
                                $empleadoDatos[$empleadoid]['cuotasindical2']+
                                $empleadoDatos[$empleadoid]['cuotasindical3']+
                                $empleadoDatos[$empleadoid]['cuotasindical4'];

                                echo '<td>';
                                echo number_format($contribucionEmpresarial, 2, ",", ".");
                                $totalContribucionEmpresarial += $contribucionEmpresarial ;
                                echo "</td>";
                            }
                        }
                        ?>
                        <td >
                            <?php echo number_format($totalContribucionEmpresarial, 2, ",", "."); ?>
                        </td>
                    </tr>
                <?php
                    break;
            }
            ?>
                <?php
                //Aca vamos a insertar la logica de Cuando y con que datos generar el input @APagar@ que vamos a usar
                //despues en JavaScript para cargar el importe en el input @APagar@ del evento impuesto.
                //Tenemos que ver que impuesto(sindicato) es y en funcion de eso vamos a sacar tal o cual
                // cuota sindical para sumar al total.
                //Por defecto vamos a sumar solo la cuota sindical original al total, y si en algun caso tenemos
                // una configuracion extra vamos a ir agregando caso por caso
                $impuestoDeterminado = 0;
                switch ($impcliSolicitado['Impuesto']['id']){
                    case '11':/*SEC*/
                        $impuestoDeterminado =
                            $totalCuotaSindical+
                            $totalCuotaSindical1+
                            $totalCuotaSindical2
//                            $totalCuotaSindical3+
//                            $totalCuotaSindical4
                        ;
                        break;
                    case '23':/*FAECYS*/
                        $impuestoDeterminado = $totalCuotaSindical3;
                        break;
                    case '24':/*INACAP*/
                        $impuestoDeterminado = $totalContribucion;
                        break;
                    case '25':/*UTHGRA*/
                        $impuestoDeterminado = $totalCuotaSindical+$totalCuotaSindical1;
                        $impuestoDeterminado += $totalCuotasindical1+$totalConvenioFEGHRA+$totalContribucionEspecialUTHGRA;
                        break;
                    case '41':/*UOCRA*/
                        $impuestoDeterminado = $totalCuotaSindical+$totalCuotaSindical1+$totalCuotaSindical2;
                        $impuestoDeterminado += $totalContribucionUocraAporteFics;
                        break;
                    case '153':/*IERIC*/
                        $impuestoDeterminado += $totalContribucionIERIC;
                        break;
                    case '178':/*ACARA*/
                        $impuestoDeterminado = $totalCuotaSindical+$totalCuotaSindical1+$totalCuotaSindical2+$totalCuotaSindical3+$totalCuotaSindical4;
                        $impuestoDeterminado += $totalContribucionEmpresarial;
                        break;

                    default:
                        $impuestoDeterminado = $totalCuotaSindical+$totalCuotaSindical1+$totalCuotaSindical2+$totalCuotaSindical3+$totalCuotaSindical4;;
                    break;
                    /*
                     * INACAP
                        Seg Vida Oblig Comercio
                        UTHGRA Seguro Sepelio y Vida
                        UTHGRA Fdo Convenio FEHGRA
                        UTHGRA Contribución Especial
                        Total UTHGRA
                        UOCRA Aporte solidario extraordinario
                        UOCRA Fdo Cese Laboral
                        UOCRA Aporte FICS
                        Total UOCRA
                        UOM Seguro Vida y Sepelio
                    */
                }
                echo $this->Form->input(
                    'apagar',
                    array(
                        'type'=>'hidden',
                        'id'=> 'apagar',
                        'value'=>$impuestoDeterminado<0?0:$impuestoDeterminado
                    )
                );
                /*Tambien tengo que definir un campo hidden que me acumule el total de contribuciones de un impuesto*/
                echo $this->Form->input(
                    'apagarcontribuciones',
                    array(
                        'type'=>'hidden',
                        'id'=> 'apagarcontribuciones',
                        'value'=>$apagarcontribuciones
                    )
                );
                ?>

		</table>
	</div>

    <?php
    if(count($impcliSolicitado['Impuesto']['Asientoestandare'])>0){
        ?>
    <div id="divContenedorContabilidad" style="margin-top:10px">
        <div class="index" id="AsientoAutomaticoDevengamiento931">
            <?php
            $Asientoid=0;
            $movId=[];
            if(isset($impcliSolicitado['Asiento'])) {
                if (count($impcliSolicitado['Asiento']) > 0) {
                    foreach ($impcliSolicitado['Asiento'] as $asiento){
                        if($asiento['tipoasiento']=='impuestos'){
                            $Asientoid = $asiento['id'];
                            foreach ($asiento['Movimiento'] as $mimovimiento) {
                                $movId[$mimovimiento['Cuentascliente']['cuenta_id']] = $mimovimiento['id'];
                            }
                        }
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
                'div' => false,
                'style'=> 'height:9px;display:inline'
            ));
            echo $this->Form->input('Asiento.0.nombre',['readonly'=>'readonly','value'=>"Devengamiento contribuciones ".$impcliSolicitado['Impuesto']['nombre'] ,'style'=>'width:250px']);
            echo $this->Form->input('Asiento.0.descripcion',['readonly'=>'readonly','value'=>"Automatico",'style'=>'width:250px']);
            echo $this->Form->input('Asiento.0.cliente_id',['value'=>$impcli['Cliente']['id'],'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.impcli_id',['value'=>$impcliSolicitado['Impcli']['id'],'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo,'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.tipoasiento',['value'=>'impuestos','type'=>'hidden'])."</br>";
            $i=0;
            //aca vamos a recorrer las cuentas que estan en el asiento estandar tipo impuesto de este carahito
            $totalDebe=0;
            $totalHaber=0;
            foreach ($impcliSolicitado['Impuesto']['Asientoestandare'] as $asientoestandarsindicato) {
                if(!isset($movId[$asientoestandarsindicato['cuenta_id']])){
                    $movId[$asientoestandarsindicato['cuenta_id']]=0;
                }
                $cuentaclienteid=0;
                $cuentaclientenombre=$asientoestandarsindicato['Cuenta']['nombre'];
                foreach ($impcli['Cliente']['Cuentascliente'] as $cuentaclienta){
                    if($cuentaclienta['cuenta_id']==$asientoestandarsindicato['cuenta_id']){
                        $cuentaclienteid=$cuentaclienta['id'];
                        $cuentaclientenombre=$cuentaclienta['nombre'];
                        break;
                    }
                }

                $cuentasSindicatoPerdidas = [
                    '2258',
                    '2259',
                    '2260',
                    '2261',
                    '2262',
                    '2263',
                    '2264',
                    '2265',
                    '2266',
                    '2266',
                    '2267',
                    '2268',
                    '2269',
                    '2270',
                    '2271',
                    '2272',
                    '2273',
                    '2274',
                    '2275',
                    '2276',
                    '2277',
                    '2278',
                    '2279',
                    '2280',
                    '2281',
                    '2282',
                    '2283',
                ];
                $cuentasSindicatoPasivo = [
                    '1425',
                    '1426',
                    '1427',
                    '1428',
                    '1429',
                    '1430',
                    '1431',
                    '1432',
                    '1433',
                    '1434',
                    '1435',
                    '1436',
                    '1437',
                    '1438',
                    '1439',
                    '1440',
                    '1441',
                    '1442',
                    '1443',
                    '3437',
                ];
                //aca vamos a ver si el monto va al debe o al haber
                $debe=0;
                $haber=0;
                if(in_array($asientoestandarsindicato['cuenta_id'],$cuentasSindicatoPerdidas)){
                    $debe=$apagarcontribuciones;
                }
                if(in_array($asientoestandarsindicato['cuenta_id'],$cuentasSindicatoPasivo)){
                    $haber=$apagarcontribuciones;
                }

                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['value'=>$movId[$asientoestandarsindicato['cuenta_id']],]);
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
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuenta_id',['readonly'=>'readonly','type'=>'hidden','orden'=>$i,'value'=>$asientoestandarsindicato['cuenta_id'],'id'=>'cuenta'.$asientoestandarsindicato['cuenta_id']]);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.numero',['label'=>($i!=0)?false:'Numero','readonly'=>'readonly','value'=>$asientoestandarsindicato['Cuenta']['numero'],'style'=>'width:82px']);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.nombre',['label'=>($i!=0)?false:'Nombre','readonly'=>'readonly','value'=>$cuentaclientenombre,'type'=>'text','style'=>'width:250px']);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
                    'label'=>($i!=0)?false:'Debe',
                    'value'=>number_format($debe, 2, ".", ""),
                    'class'=>"inputDebe "
                ]);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
                        'label'=>($i!=0)?false:'Haber',
                        'value'=>number_format($haber, 2, ".", ""),
                        'class'=>"inputHaber "
                    ])."</br>";
                $i++;
                $totalDebe+=$debe;
                $totalHaber+=$haber;
            }

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
