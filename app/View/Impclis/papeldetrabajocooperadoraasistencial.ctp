<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false)); ?>
<?php echo $this->Html->script('impclis/papeldetrabajocooperadoraasistencial',array('inline'=>false)); ?>
<?php echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
echo $this->Form->input('impcliidPDT',array('value'=>$impcliid,'type'=>'hidden'));?>
<div class="index">
    <div id="Formhead" class="clientes papeldetrabajosuss index" style="margin-bottom:10px;">
        <h2>Cooperadora Asistencial:</h2>
        Contribuyente: <?php echo $impcli['Cliente']['nombre']; ?></br>
        CUIT: <?php echo $impcli['Cliente']['cuitcontribullente']; ?></br>
        Periodo: <?php echo $periodo; ?>
    </div>
    <div id="sheetCooperadoraAsistencial" class="index">
        <!--Esta es la tabla original y vamos a recorrer todos los empleados por cada una de las
        rows por que -->
        <?php
        $empleadoDatos = array();
        $baseimponible = 0 ;
        $SaldoAFavor = 0 ;
        $OtrosPagosACuenta = 0 ;
        $Retenciones = 0 ;

        foreach ($impcli['Cliente']['Empleado'] as $empleado) {
            $miempleado = array();
            if(!isset($miempleado['horasDias'])) {
                $miempleado['rem1'] = 0;
                $miempleado['total'] = 0;
            }
            $rem1=0;
            if(!$empleado['exentocooperadoraasistencial']){
                foreach ($empleado['Valorrecibo'] as $valorrecibo) {
                    //Remuneracion 1
                    if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                        array('27'/*Total Remunerativos C/D*/,'109'/*Total Remunerativos S/D*/), true )
                    ){
                        $rem1 += $valorrecibo['valor'];
                    }
                }
            }

            $baseimponible+=$rem1;

            $miempleado['rem1']=$rem1;
            $empleadoid = $empleado['id'];
            if(!isset($empleadoDatos[$empleadoid]))
                $empleadoDatos[$empleadoid]=array();
            $empleadoDatos[$empleadoid] = $miempleado;
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
                foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                    echo "<td>".$empleado['legajo']."</td>";
                }
                ?>
            </tr>
            <tr>
                <td>Apellido y Nombre</td>
                <?php
                foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                    echo "<td>".$empleado['nombre']."</td>";
                }
                ?>
            </tr><!--1-->
            <tr>
                <td>CUIL</td>
                <?php
                foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                    echo "<td>".$empleado['cuit']."</td>";
                }
                ?>
            </tr>
            <tr>
                <td>Excento</td>
                <?php
                foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                    echo "<td>";
                    echo $empleado['exentocooperadoraasistencial']?'SI':'NO';
                    echo "</td>";
                }
                ?>
            </tr>
            <tr>
                <td>Municipio</td>
                <?php
                foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                    echo "<td>";
                    echo $empleado['Puntosdeventa']['Domicilio']['Localidade']['Partido']['nombre'];
                    echo "</td>";
                }
                ?>
            </tr>
            <tr>
                <td>Rem. 1 (SIPA)</td>
                <?php
                $rem1=0;
                foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                    $empleadoid = $empleado['id'];
                    //en este primer loop vamos a calcular todos los siguientes totales
                    echo "<td>";
                    echo $empleadoDatos[$empleadoid]['rem1']."</td>";
                }
                ?>
            </tr>
        </table>
        Impuesto</br>
        Cantidad de empleados : <?php echo count($impcli['Cliente']['Empleado']) ?></br>
        Base imponible: $<?php echo number_format($baseimponible, 2, ",", ".") ?></br>
        Alicuota: 2% </br>
        <?php
        $impuestoTotal = 0;
        $impuestoTotal = $baseimponible*0.2;
        ?>
        Impuesto: $<?php echo number_format($impuestoTotal, 2, ",", ".") ?></br></br>

        Retenciones: $<?php echo number_format($Retenciones, 2, ",", ".") ?></br>
        Otros Pagos a Cuenta: $<?php echo number_format($OtrosPagosACuenta, 2, ",", ".") ?></br>
        Saldo A Favor del Periodo Anterior: $<?php echo number_format($SaldoAFavor, 2, ",", ".") ?></br></br>

        <?php
        $impuestoDeterminado = $impuestoTotal-$Retenciones-$OtrosPagosACuenta-$SaldoAFavor;
        echo $impuestoDeterminado<0?'Saldo a Favor':'Impuesto determinado';

        echo $this->Form->input(
            'apagar',
            array(
                'type'=>'hidden',
                'id'=> 'apagar',
                'value'=>$impuestoDeterminado<0?0:$impuestoDeterminado
            )
        );
        echo $this->Form->input(
            'afavor',
            array(
                'type'=>'hidden',
                'id'=> 'afavor',
                'value'=>$impuestoDeterminado<0?$impuestoDeterminado:0
            )
        );

        ?>
        $<?php echo number_format($impuestoDeterminado, 2, ",", ".") ?></br></br>
        <?php
        $provinciasSubtotal = array();
        foreach ($impcli['Cliente']['Empleado'] as $empleado) {
            $empleadoid = $empleado['id'];
            $domicilioEmpleadoID = $empleado['Puntosdeventa']['Domicilio']['Localidade']['Partido']['nombre'];
            if(!isset($provinciasSubtotal[$domicilioEmpleadoID])){
                $provinciasSubtotal[$domicilioEmpleadoID]=0;
            }
            $provinciasSubtotal[$domicilioEmpleadoID] += $empleadoDatos[$empleadoid]['rem1'];
        }
        foreach ($provinciasSubtotal as $key => $item) {
            $subtotalprov = ($item/$baseimponible*100);
            echo $key.": $".number_format($item, 2, ",", ".")." - ".number_format($subtotalprov, 2, ",", ".")." %";
        }
        //Debugger::dump($provinciasSubtotal);
        //Debugger::dump($empleadoDatos);
        ?>
    </div>
    <div id="divLiquidarCooperadoraAsistencial">
    </div>
</div>
