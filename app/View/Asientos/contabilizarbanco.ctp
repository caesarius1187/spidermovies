<?php
 //echo $this->Html->script('movimientos/index',array('inline'=>false));
    if(isset($error)){
    ?>
    <h2><?php echo $error; ?></h2>
    <?php
    return;
}
?>
<div class="index">
    <h2><?php echo __('Contabilizar Bancos del cliente: '.$cliente['Cliente']['nombre']." para el periodo: ".$periodo); ?></h2>
    <h3><?php echo __('Banco '.$cliente['Impcli'][0]['Impuesto']['nombre'].' CBU: '.$cliente['Impcli'][0]['Cbu'][0]['cbu']); ?></h3>
    <?php
    $id = 0;
    $nombre = "Devengamiento: ".$cliente['Impcli'][0]['Cbu'][0]['cbu']."-".$cliente['Impcli'][0]['Cbu'][0]['tipocuenta'];
    $descripcion = "Automatico de Acreditaciones Bancarias";
    $fecha = date('d-m-Y');
    $miAsiento=array();
    $misMovimientos=array();
    if(!isset($miAsiento['Movimiento'])){
        $miAsiento['Movimiento']=array();
    }
    foreach ($asientoyacargado as $asiento){
        if($asiento['Asiento']['tipoasiento']=='bancos'){
            $miAsiento = $asiento['Asiento'];
            $misMovimientos = $asiento['Movimiento'];
            $id = $miAsiento['id'];
            $nombre = $miAsiento['nombre'];
            $descripcion = $miAsiento['descripcion'];
            $fecha = date('d-m-Y',strtotime($miAsiento['fecha']));
        }
    }

    //Asiento de acreditaciones
    echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','action'=>'add']);
    echo $this->Form->input('Asiento.0.id',['value'=>$id]);
    echo $this->Form->input('Asiento.0.nombre',['value'=>$nombre]);
    echo $this->Form->input('Asiento.0.descripcion',['value'=>$descripcion]);
    echo $this->Form->input('Asiento.0.fecha',['value'=>$fecha]);
    echo $this->Form->input('Asiento.0.cliente_id',['value'=>$cliid,'type'=>'hidden']);
    echo $this->Form->input('Asiento.0.cbu_id',['value'=>$cbuid,'type'=>'hidden']);
    echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo]);
    echo $this->Form->input('Asiento.0.tipoasiento',['default'=>'bancos','type'=>'hidden']);
    /*1.Preguntar si existe la cuenta cliente que apunte a la cuenta 8(idDeCuenta) */
    /*2. Si no existe se la crea y la traigo*/
    /*3. Si existe la traigo*/
    $i=0;
    echo "</br>";
    $cuentaclienteid = 0;
    $totalDebe=0;
    $totalHaber=0;
    $totalDebito=0;
    $totalCredito=0;
    foreach ($miscuentasclientes as $movimiento) {
        foreach ($movimiento['Movimientosbancario'] as $movimientobancario ){
            $totalDebito += $movimientobancario['debito']*1;
            $totalCredito += $movimientobancario['credito']*1;
        }
    }
    foreach ($miscuentasclientes as $micuentacliente) {
        $mostrar = false;//este mostrar se va a hacer true solo si existe un movimiento ya cargado o si el debito o el credito son > 0
        $cuentadelCBU = false;
        $cuentaclienteid = $micuentacliente['Cuentascliente']['id'];
        $cuentaclientenombre = $micuentacliente['Cuentascliente']['nombre'];
        $cuentaid = $micuentacliente['Cuenta']['id'];
        $cuentanumero = $micuentacliente['Cuenta']['numero'];
        /*lo mismo que hicimos con el asiento vamos a hacer con los movimientos, si existe un movimiento
                con la cuentacliente_id que estamos queriendo armar rellenamos los datos*/
        $movid=0;
        $asiento_id=0;
        $key=0;
        foreach ($misMovimientos as $movimientoyacargado) {
            if ($cuentaclienteid == $movimientoyacargado['cuentascliente_id']) {
                $movid = $movimientoyacargado['id'];
                $asiento_id = $id;
                $mostrar = true;
            }

        }
        if ($cuentaclienteid == $cliente['Impcli'][0]['Cbu'][0]['cuentascliente_id']) {
            $mostrar = true;
            $cuentadelCBU = true;
        }
        //vamos a acumular los movimientos bancarios cargados con su debe y haber para cada cuenta
        $debito = 0;
        $credito = 0;
        if($cuentadelCBU){
            $debito = $totalCredito;
            $credito = 0;
        }else{
            foreach ($micuentacliente['Movimientosbancario'] as $movimientobancario ){
                //Como este es el asiento de acreditaciones vamos a cargar solo el HABER
                if($movimientobancario['debito']*1==0){
                    $credito += $movimientobancario['credito']*1;
                    $mostrar = true;
                }
            }
        }

        if($mostrar){

            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['default'=>$movid]);
            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.asiento_id',['default'=>$id,'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuenta_id',[
                'readonly'=>'readonly',
                'type'=>'hidden',
                'orden'=>$i,
                'value'=>$cuentaid,
                'id'=>'cuenta'.$cuentaid
            ]);
            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',['type'=>'hidden','value'=>$cuentaclienteid]);
            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.numero',[
                'label'=>($i!=0)?false:'Numero',
                'readonly'=>'readonly',
                'value'=>$cuentanumero,
                'style'=>'width:82px'
            ]);
            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.nombre',[
                'label'=>($i!=0)?false:'Cuenta',
                'readonly'=>'readonly',
                'value'=>$cuentaclientenombre,
                'type'=>'text',
                'style'=>'width:250px']);
            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha', array(
                'type'=>'hidden',
                'readonly','readonly',
                'value'=>date('d-m-Y'),
            ));

                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
                'class'=>"inputDebe",
                'label'=>($i!=0)?false:'Debe',
                'value'=>number_format($debito, 2, ".", ""),
            ]);
            $totalDebe+=number_format($debito, 2,'.','');
            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
                    'label'=>($i!=0)?false:'Haber',
                    'class'=>"inputHaber",
                    'value'=>number_format($credito, 2, ".", ""),
                ])."</br>";
            $totalHaber+=number_format($credito, 2, ".", "");
            echo "</br>";
            $i++;
        }
    }

    //Asiento de retiros
    $id = 0;
    $nombre = "Devengamiento: ".$cliente['Impcli'][0]['Cbu'][0]['cbu']."-".$cliente['Impcli'][0]['Cbu'][0]['tipocuenta'];
    $descripcion = "Automatico de Retiros Bancarios";
    $fecha = date('d-m-Y');
    $miAsiento=array();
    $misMovimientos=array();
    if(!isset($miAsiento['Movimiento'])){
        $miAsiento['Movimiento']=array();
    }
    foreach ($asientoyacargado as $asiento){
        if($asiento['Asiento']['tipoasiento']=='bancosretiros'){
            $miAsiento = $asiento['Asiento'];
            $misMovimientos = $asiento['Movimiento'];
            $id = $miAsiento['id'];
            $nombre = $miAsiento['nombre'];
            $descripcion = $miAsiento['descripcion'];
            $fecha = date('d-m-Y',strtotime($miAsiento['fecha']));
        }
    }
    echo $this->Form->input('Asiento.1.id',['default'=>$id]);
    echo $this->Form->input('Asiento.1.nombre',['default'=>$nombre]);
    echo $this->Form->input('Asiento.1.descripcion',['default'=>$descripcion]);
    echo $this->Form->input('Asiento.1.fecha',['default'=>$fecha]);
    echo $this->Form->input('Asiento.1.cliente_id',['default'=>$cliid,'type'=>'hidden']);
    echo $this->Form->input('Asiento.1.cbu_id',['value'=>$cbuid,'type'=>'hidden']);
    echo $this->Form->input('Asiento.1.periodo',['value'=>$periodo]);
    echo $this->Form->input('Asiento.1.tipoasiento',['default'=>'bancosretiros','type'=>'hidden']);
    /*1.Preguntar si existe la cuenta cliente que apunte a la cuenta 8(idDeCuenta) */
    /*2. Si no existe se la crea y la traigo*/
    /*3. Si existe la traigo*/
    $i=0;
    echo "</br>";
    $cuentaclienteid = 0;
    foreach ($miscuentasclientes as $micuentacliente) {
        $mostrar = false;//este mostrar se va a hacer true solo si existe un movimiento ya cargado o si el debito o el credito son > 0
        $cuentaclienteid = $micuentacliente['Cuentascliente']['id'];
        $cuentaclientenombre = $micuentacliente['Cuentascliente']['nombre'];
        $cuentaid = $micuentacliente['Cuenta']['id'];
        $cuentanumero = $micuentacliente['Cuenta']['numero'];
        /*lo mismo que hicimos con el asiento vamos a hacer con los movimientos, si existe un movimiento
                con la cuentacliente_id que estamos queriendo armar rellenamos los datos*/
        $movid=0;
        $asiento_id=0;
        $key=0;
        $cuentadelCBU=false;
        foreach ($misMovimientos as $movimientoyacargado) {
            if($cuentaclienteid==$movimientoyacargado['cuentascliente_id']){
                $movid=$movimientoyacargado['id'];
                $asiento_id=$id;
                $mostrar = true;
            }
        }
        if ($cuentaclienteid == $cliente['Impcli'][0]['Cbu'][0]['cuentascliente_id']) {
            $mostrar = true;
            $cuentadelCBU = true;
        }
        //vamos a acumular los movimientos bancarios cargados con su debe y haber para cada cuenta
        $debito = 0;
        $credito = 0;
        if($cuentadelCBU){
            $debito = 0;
            $credito = $totalDebito;
        }else{
            foreach ($micuentacliente['Movimientosbancario'] as $movimientobancario ){
                //Como este es el asiento de acreditaciones vamos a cargar solo el HABER
                if($movimientobancario['credito']*1==0){
                    $debito += $movimientobancario['debito']*1;
                    $mostrar = true;
                }
            }
        }

        if($mostrar){
            echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.id',['default'=>$movid]);
            echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.asiento_id',['value'=>$id,'type'=>'hidden']);
            echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.cuenta_id',[
                'readonly'=>'readonly',
                'type'=>'hidden',
                'orden'=>$i,
                'value'=>$cuentaid,
                'id'=>'cuenta'.$cuentaid
            ]);
            echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.cuentascliente_id',['type'=>'hidden','value'=>$cuentaclienteid]);
            echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.numero',[
                'label'=>($i!=0)?false:'Numero',
                'readonly'=>'readonly',
                'value'=>$cuentanumero,
                'style'=>'width:82px'
            ]);
            echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.nombre',[
                'label'=>($i!=0)?false:'Cuenta',
                'readonly'=>'readonly',
                'value'=>$cuentaclientenombre,
                'type'=>'text',
                'style'=>'width:450px']);
            echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.fecha', array(
                'type'=>'hidden',
                'readonly','readonly',
                'value'=>date('d-m-Y'),
            ));
            echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.debe',[
                'class'=>"inputDebe",
                'label'=>($i!=0)?false:'Debe',
                'value'=>number_format($debito, 2, ".", ""),
            ]);
            $totalDebe+=number_format($debito, 2,'.','');
            echo $this->Form->input('Asiento.1.Movimiento.'.$i.'.haber',[
                    'label'=>($i!=0)?false:'Haber',
                    'class'=>"inputHaber",
                    'value'=>number_format($credito, 2, ".", ""),
                ])."</br>";
            $totalHaber+=number_format($credito, 2, ".", "");
            echo "</br>";
            $i++;
        }
    }
    echo $this->Form->end('Guardar asiento');
    echo $this->Form->label('','Total a debe: $',[
        'style'=>"display: inline;"
    ]);
    echo $this->Form->label('lblTotalDebe',
        number_format($totalDebe, 2, ".", ""),
        [
            'id'=>'lblTotalDebe',
            'style'=>"display: inline;"
        ]
    );
    echo $this->Form->label('',' Total Haber: $',['style'=>"display: inline;"]);
    echo $this->Form->label('lblTotalHaber',
        number_format($totalHaber, 2, ".", ""),
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
