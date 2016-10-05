<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false)); ?>
<?php echo $this->Html->script('impclis/papeldetrabajosindicatos',array('inline'=>false)); ?>
<?php echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
echo $this->Form->input('impcliidPDT',array('value'=>$impcliid,'type'=>'hidden'));?>
<div class="index">
	<div id="Formhead" class="clientes papeldetrabajosindicato index" style="margin-bottom:10px;">
		<h2>Sindicato: <?php echo $impcli['Impuesto']['nombre']; ?></h2>
		Contribuyente: <?php echo $impcli['Cliente']['nombre']; ?></br>
		CUIT: <?php echo $impcli['Cliente']['cuitcontribullente']; ?></br>
		Periodo: <?php echo $periodo; ?>
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

        foreach ($impcli['Impuesto']['Conveniocolectivotrabajo'] as $conveniocolectivo) {
            foreach ($conveniocolectivo['Empleado'] as $empleado) {
                $jornada = 0;
                $horasDias = 0;
                $afiliadoSindicato = 0;
                $remuneracionCD = 0;
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

                if (!isset($miempleado['horasDias'])) {
                    $miempleado['jornada'] = 0;
                    $miempleado['horasDias'] = 0;
                    $miempleado['afiliadoSindicato'] = false;
                    $miempleado['remuneracionCD'] = 0;
                    $miempleado['remuneracionSDExceptoIndemnizatorio'] = 0;
                    $miempleado['remuneracionSDIndemnizatorio'] = 0;
                    $miempleado['remuneracionTotal'] = 0;
                    $miempleado['aportes'] = 0;
                    $miempleado['neto'] = 0;
                    $miempleado['cuotasindical'] = 0;
                    $miempleado['cuotasindical1'] = 0;
                    $miempleado['cuotasindical2'] = 0;
                    $miempleado['cuotasindical3'] = 0;
                }
                foreach ($empleado['Valorrecibo'] as $valorrecibo) {
                    //Horas Diarias
                    if ($valorrecibo['Cctxconcepto']['Concepto']['id'] == '11'/*Jornada*/) {
                        $jornada += $valorrecibo['valor'];
                    }
                    //Horas Diarias
                    if ($valorrecibo['Cctxconcepto']['Concepto']['id'] == '112'/*Horas*/) {
                        $horasDias += $valorrecibo['valor'];
                    }
                    //Afiliado al Sindicato
                    if ($valorrecibo['Cctxconcepto']['Concepto']['id'] == '39'/*Afiliado al Sindicato*/) {
                        $afiliadoSindicato += $valorrecibo['valor'];
                    }
                    //Remuneracion CD
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('27'/*Total Remunerativos C/D*/), true)
                    ) {
                        $remuneracionCD += $valorrecibo['valor'];
                    }
                    //Remuneracion SD Excepto Indemnizatorios
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('103'/*Total Rem. S/D Excepto Indemnizatorio*/), true)
                    ) {
                        $remuneracionSDExceptoIndemnizatorio += $valorrecibo['valor'];
                    }
                    //Total Rem. S/D Indemnizatorios
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('108'/*Total Rem. S/D Indemnizatorio*/), true)
                    ) {
                        $remuneracionSDIndemnizatorio += $valorrecibo['valor'];
                    }
                    //Total Remuneracion
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('44'/*Remuneración Total	*/), true)
                    ) {
                        $remuneracionTotal += $valorrecibo['valor'];
                    }
                    //Total Aportes
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('45'/*Aportes	*/), true)
                    ) {
                        $aportes += $valorrecibo['valor'];
                    }

                    //Total Neto
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('46'/*Neto	*/), true)
                    ) {
                        $neto += $valorrecibo['valor'];
                    }
                    //Total Cuota Sindical
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('36'/*Cuota Sindical	*/), true)
                    ) {
                        $cuotasindical += $valorrecibo['valor'];
                    }
                    //Total Cuota Sindical1
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('37'/*Cuota Sindical Extra 1	*/), true)
                    ) {
                        $cuotasindical1 += $valorrecibo['valor'];
                    }
                    //Total Cuota Sindical 2
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('38'/*Cuota Sindical Extra 2	*/), true)
                    ) {
                        $cuotasindical2 += $valorrecibo['valor'];
                    }
                    //Total Cuota Sindical 3
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('114'/*Cuota Sindical Extra 3	*/), true)
                    ) {
                        $cuotasindical3 += $valorrecibo['valor'];
                    }
                    //Nombre Cuota Sindical
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('36'/*Cuota Sindical	*/), true)
                    ) {
                        $nombrecuotaSindical = $valorrecibo['Cctxconcepto']['nombre'];
                    }
                    //Nombre Cuota Sindical 1
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('37'/*Cuota extra Sindical 1 	*/), true)
                    ) {
                        $nombrecuotaSindical1 = $valorrecibo['Cctxconcepto']['nombre'];
                    }
                    //Nombre Cuota Sindical 2
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('38'/*Cuota extra Sindical 2	*/), true)
                    ) {
                        $nombrecuotaSindical2 = $valorrecibo['Cctxconcepto']['nombre'];
                    }
                    //Nombre Cuota Sindical 3
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('114'/*Cuota extra Sindical 3	*/), true)
                    ) {
                        $nombrecuotaSindical3 = $valorrecibo['Cctxconcepto']['nombre'];
                    }
                }
                $miempleado['jornada'] = $jornada;
                $miempleado['horasDias'] = $horasDias;
                $miempleado['afiliadoSindicato'] = $afiliadoSindicato;
                $miempleado['remuneracionSDExceptoIndemnizatorio'] = $remuneracionSDExceptoIndemnizatorio;
                $miempleado['remuneracionSDIndemnizatorio'] = $remuneracionSDIndemnizatorio;
                $miempleado['remuneracionTotal'] = $remuneracionTotal;
                $miempleado['aportes'] = $aportes;
                $miempleado['neto'] = $neto;
                $miempleado['cuotasindical'] = $cuotasindical;
                $miempleado['cuotasindical1'] = $cuotasindical1;
                $miempleado['cuotasindical2'] = $cuotasindical2;
                $miempleado['cuotasindical3'] = $cuotasindical3;

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
		//Debugger::dump($empleadoDatos);
		?>
		<table id="tblDatosAIngresar" class="tblInforme tbl_border" cellspacing="0">
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
                if($nombrecuotaSindical3!=""){
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
                }?>
                <td><?php  echo number_format($totalCuotaSindical3, 2, ",", "."); ?></td>
                <?php
                }
                //Aca vamos a insertar la logica de Cuando y con que datos generar el input @APagar@ que vamos a usar
                //despues en JavaScript para cargar el importe en el input @APagar@ del evento impuesto.
                //Tenemos que ver que impuesto(sindicato) es y en funcion de eso vamos a sacar tal o cual
                // cuota sindical para sumar al total.
                //Por defecto vamos a sumar solo la cuota sindical original al total, y si en algun caso tenemos
                // una configuracion extra vamos a ir agregando caso por caso
                $impuestoDeterminado = 0;
                switch ($impcli['Impuesto']['id']){
                    case '11':
                        $impuestoDeterminado = $totalCuotaSindical+$totalCuotaSindical1+$totalCuotaSindical2;
                        break;
                    case '23':
                        $impuestoDeterminado = $totalCuotaSindical3;
                        break;
                    default:
                        $impuestoDeterminado = $totalCuotaSindical;
                    break;
                }
                echo $this->Form->input(
                    'apagar',
                    array(
                        'type'=>'hidden',
                        'id'=> 'apagar',
                        'value'=>$impuestoDeterminado<0?0:$impuestoDeterminado
                    )
                );
                /*echo $this->Form->input(
                    'afavor',
                    array(
                        'type'=>'hidden',
                        'id'=> 'afavor',
                        'value'=>$impuestoDeterminado<0?$impuestoDeterminado:0
                    )
                );*/
                ?>
            </tr>
            <tr>
                <td colspan="150">Contribuciones Sindicales</td>
            </tr>
            <?php

            ?>
		</table>
	</div>
	<div id="divLiquidarSindicatos">
	</div>
</div>
