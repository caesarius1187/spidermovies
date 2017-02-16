<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false)); 
echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('impclis/papeldetrabajocooperadoraasistencial',array('inline'=>false)); 
echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
echo $this->Form->input('impcliidPDT',array('value'=>$impcliid,'type'=>'hidden'));
echo $this->Form->input('clinombre',array('value'=>$impcli['Cliente']['nombre'],'type'=>'hidden'));?>
<div class="index">
    <div id="Formhead" class="clientes papeldetrabajosuss index" style="margin-bottom:10px;">
        <h2>Cooperadora Asistencial:</h2>
        Contribuyente: <?php echo $impcli['Cliente']['nombre']; ?></br>
        CUIT: <?php echo $impcli['Cliente']['cuitcontribullente']; ?></br>
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
    <div id="sheetCooperadoraAsistencial" class="index">
        <!--Esta es la tabla original y vamos a recorrer todos los empleados por cada una de las
        rows por que -->
        <?php
        $empleadoDatos = array();
        $baseimponible = 0 ;
        $baseimponibleexenta = 0 ;
        $SaldoAFavor = 0 ;
        $OtrosPagosACuenta = 0 ;
        $Retenciones = 0 ;

        $cantidadDeEmpleados = 0;
        foreach ($impcli['Cliente']['Empleado'] as $empleado) {
            $miempleado = array();
            if(!isset($miempleado['horasDias'])) {
                $miempleado['rem1'] = 0;
                $miempleado['total'] = 0;
            }
            $rem1=0;
            foreach ($empleado['Valorrecibo'] as $valorrecibo) {
                //Remuneracion 1
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('27'/*Total Remunerativos C/D*/,
//                        '109'/*Total Remunerativos S/D*/
                    ), true )
                ){
                    $rem1 += $valorrecibo['valor'];
                }
            }
            $baseimponible += $rem1;
            if($empleado['exentocooperadoraasistencial']) {
                $baseimponibleexenta += $rem1;
            }
            $miempleado['rem1']=$rem1;
            $empleadoid = $empleado['id'];
            if(!isset($empleadoDatos[$empleadoid]))
                $empleadoDatos[$empleadoid]=array();
            $empleadoDatos[$empleadoid] = $miempleado;
        }
        unset($miempleado);
        if(count($conceptosrestantes)>0) {
            foreach ($conceptosrestantes as $conceptosrestante) {
                switch ($conceptosrestante['Conceptosrestante']['conceptostipo_id']) {
                    case '2':
                    case '3':
                        $Retenciones += $conceptosrestante['Conceptosrestante']['montoretenido'];
                        break;
                    case '4':
                        $OtrosPagosACuenta += $conceptosrestante['Conceptosrestante']['montoretenido'];
                        break;
                }
            }
            unset($conceptosrestante);
        }
        if(count($impcliSaldoAFavor)>0) {
            foreach ($impcliSaldoAFavor as $saldosafavor) {
                    $SaldoAFavor+=$saldosafavor['Conceptosrestante']['montoretenido'];
            }
            unset($saldosafavor);
        }
        //Debugger::dump($empleadoDatos);
        ?>
        <table id="tblDatosAIngresar" class="tblInforme tbl_border" cellspacing="0" >
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
                <td>Exento</td>
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
                    if(isset($empleado['Domicilio']))
                        echo $empleado['Domicilio']['Localidade']['nombre'];
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
            <tr>
                <td colspan="50">
                    Impuesto</br>
                    Cantidad de empleados : <?php echo count($impcli['Cliente']['Empleado']) ?></br>
                    Base imponible: $<?php echo number_format($baseimponible, 2, ",", ".") ?></br>
                    Alicuota: 2% </br>
                    <?php
                    $impuestoTotal = 0;
                    $impuestoTotal = ($baseimponible)*0.02;
                    $impuestoExento = ($baseimponibleexenta)*0.02;
                    ?>
                    Impuesto: $<?php echo number_format($impuestoTotal, 2, ",", ".") ?></br></br>
                    Exento: $<?php echo number_format($impuestoExento, 2, ",", ".") ?></br>
                    Retenciones: $<?php echo number_format($Retenciones, 2, ",", ".") ?></br>
                    Otros Pagos a Cuenta: $<?php echo number_format($OtrosPagosACuenta, 2, ",", ".") ?></br>
                    Saldo A Favor del Periodo Anterior: $<?php echo number_format($SaldoAFavor, 2, ",", ".") ?></br></br>

                    <?php
                    $title ="";
                    if(count($impcli['Cliente']['Empleado'])<2){
                        //Este Impuesto se empeza a pagar cuando hay al menos 2 empleados
                        $impuestoDeterminado = 0;
                        $title ="El Contribuyente tiene 1 o menos empleados por lo que esta exento de pagar este impuesto";
                    }else{
                        $impuestoDeterminado = $impuestoTotal-$impuestoExento-$Retenciones-$OtrosPagosACuenta-$SaldoAFavor;
                    }
                    echo '<label title="'.$title.'">';
                    echo $impuestoDeterminado<0?'Saldo a Favor':'Impuesto determinado';
                    ?>
                    $<?php echo number_format($impuestoDeterminado*-1, 2, ",", ".") ?></br></br>
                    <?php
                    echo "</label>";
                    echo $this->Form->input(
                        'apagar',
                        array(
                            'type'=>'hidden',
                            'id'=> 'apagar',
                            'value'=>$impuestoDeterminado<0?0:$impuestoDeterminado,
                        )
                    );
                    echo $this->Form->input(
                        'afavor',
                        array(
                            'type'=>'hidden',
                            'id'=> 'afavor',
                            'value'=>$impuestoDeterminado<0?$impuestoDeterminado*-1:0
                        )
                    );

                    $provinciasSubtotal = array();
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        $empleadoid = $empleado['id'];
                        $domicilioEmpleadoID = 0;
                        if(isset($empleado['Domicilio']))
                            $domicilioEmpleadoID = $empleado['Domicilio']['Localidade']['nombre'];
                        if(!isset($provinciasSubtotal[$domicilioEmpleadoID])){
                            $provinciasSubtotal[$domicilioEmpleadoID]=0;
                        }
                        if(!$empleado['exentocooperadoraasistencial']) {
                            $provinciasSubtotal[$domicilioEmpleadoID] += $empleadoDatos[$empleadoid]['rem1'];
                        }
                    }
                    foreach ($provinciasSubtotal as $key => $item) {
                        if($baseimponible!=0){
                            $subtotalprov = ($item/$baseimponible*100);
                        }else{
                            $subtotalprov = 0;
                        }
                        echo $key.": $".number_format($item, 2, ",", ".")." - ".number_format($subtotalprov, 2, ",", ".")." %";
                    }
                    //Debugger::dump($provinciasSubtotal);
                    //Debugger::dump($empleadoDatos);
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <div id="divLiquidarCooperadoraAsistencial">
    </div>
</div>
