<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false)); 
echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('impclis/papeldetrabajocooperadoraasistencial',array('inline'=>false)); 
echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
echo $this->Form->input('impcliidPDT',array('value'=>$impcliid,'type'=>'hidden'));
echo $this->Form->input('clinombre',array('value'=>$impcli['Cliente']['nombre'],'type'=>'hidden'));
echo $this->Form->input('impcliid',array('value'=>$impcli['Impcli']['id'],'type'=>'hidden'));
echo $this->Form->input('cliid',array('value'=>$impcli['Cliente']['id'],'type'=>'hidden'));
?>
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
    <div id="divLiquidarCooperadoraAsistencial">
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
                    $impuestoDeterminado = $impuestoTotal-$impuestoExento-$Retenciones-$OtrosPagosACuenta-$SaldoAFavor;
                    if($impuestoDeterminado>0){
                        if(count($impcli['Cliente']['Empleado'])<2){
                            //Este Impuesto se empeza a pagar cuando hay al menos 2 empleados
                            $impuestoDeterminado = 0;
                            $title ="El Contribuyente tiene 1 o menos empleados por lo que esta exento de pagar este impuesto";
                        }
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

    <div id="divContenedorContabilidad" style="margin-top:10px">
        <div class="index" id="AsientoAutomaticoDevengamientooperadoraAsistensial">
            <?php
            $Asientoid=0;
            $movId=[];
            if(isset($impcli['Asiento'])) {
                if (count($impcli['Asiento']) > 0) {
                    foreach ($impcli['Asiento'] as $asiento){
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
            echo $this->Form->input('Asiento.0.nombre',['readonly'=>'readonly','value'=>"Devengamiento Cooperadora Asistencial",'style'=>'width:250px']);
            echo $this->Form->input('Asiento.0.descripcion',['readonly'=>'readonly','value'=>"Automatico",'style'=>'width:250px']);
            echo $this->Form->input('Asiento.0.cliente_id',['value'=>$impcli['Cliente']['id'],'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.impcli_id',['value'=>$impcli['Impcli']['id'],'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo,'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.tipoasiento',['value'=>'impuestos','type'=>'hidden'])."</br>";
            $i=0;
            //aca vamos a recorrer las cuentas que estan en el asiento estandar tipo impuesto de este carahito
            $totalDebe=0;
            $totalHaber=0;
            foreach ($impcli['Impuesto']['Asientoestandare'] as $asientoestandarsindicato) {
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
                $debe=0;
                $haber=0;
                switch ($asientoestandarsindicato['Cuenta']['id']){
                    case '2579'/*506220001 Coop Asist*/:
                        $debe = $impuestoTotal>=0?$impuestoTotal:0;
                        break;
                    case '323'/*110404401 Coop. As. - Retenciones*/:
                        $haber = $Retenciones;
                        break;
                    case '3380'/*110404402 Coop. As -  Saldo a favor*/:
                        $nuevoSAF = 0;
                        if($impuestoDeterminado<0/*Hay SAF*/){
                            //vamos a acumular solo la diferencia entre estos dos
                            $nuevoSAF = $impuestoDeterminado*-1;
                        }
                        $difSAF = $nuevoSAF - $SaldoAFavor;
                        if($difSAF>0){
                            $debe = $difSAF;
                        }else{
                            $haber = $difSAF*-1;
                        }
                        break;
                    case '1500'/*210402201 Coop Asist a Pagar*/:
                        if($impuestoDeterminado>=0){
                            //vamos a acumular solo la diferencia entre estos dos
                            $haber = $impuestoDeterminado;
                        }
                        break;

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
</div>
