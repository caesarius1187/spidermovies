<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 03/11/2016
 * Time: 12:33 PM
 */
?>
<div class="index">
    <h3><?php echo __('Contabilizar Liquidacion de impuesto: '.$impcli['Impuesto']['nombre']); ?></h3>
    <?php
    $id = 0;
    $nombre = "Devengamiento ".$impcli['Impuesto']['nombre'].": ".$periodo;
    $descripcion = "Automatico";
    $fecha = date('d-m-Y');
    $miAsiento=array();
    $numeroAsiento = 0;
    if(!isset($miAsiento['Movimiento'])){
        $miAsiento['Movimiento']=array();
    }
    if(isset($impcli['Asiento'][0])){
        $miAsiento = $impcli['Asiento'][0];
        $id = $miAsiento['id'];
        $nombre = $miAsiento['nombre'];
        $descripcion = $miAsiento['descripcion'];
        $fecha = date('d-m-Y',strtotime($miAsiento['fecha']));
    }

    echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','action'=>'add','style'=>' min-width: max-content;']);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.id',['default'=>$id]);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.nombre',['default'=>$nombre]);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.descripcion',['default'=>$descripcion]);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.fecha',['default'=>$fecha]);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.cliente_id',['default'=>$impcli['Impcli']['cliente_id'],'type'=>'hidden']);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.impcli_id',['default'=>$impcli['Impcli']['id'],'type'=>'hidden']);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.periodo',['value'=>$periodo]);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.tipoasiento',['default'=>'impuestos','type'=>'hidden']);
    /*1.Preguntar si existe la cuenta cliente que apunte a la cuenta 8(idDeCuenta) */
    /*2. Si no existe se la crea y la traigo*/
    /*3. Si existe la traigo*/
    $i=0;
    echo "</br>";
    $cuentaclienteid = 0;
    foreach ($impcli['Impuesto']['Asientoestandare'] as $asientoestandar) {
        $cuentaclienteid = $asientoestandar['Cuenta']['Cuentascliente'][0]['id'];
        $cuentaid = $asientoestandar['Cuenta']['id'];
        /*lo mismo que hicimos con el asiento vamos a hacer con los movimientos, si existe un movimiento
                con la cuentacliente_id que estamos queriendo armar rellenamos los datos*/
        $movid=0;
        $asiento_id=0;
        $debe=0;
        $haber=0;
        $key=0;

        foreach ($miAsiento['Movimiento'] as $kMov => $movimiento){
            if(!isset($miAsiento['Movimiento'][$kMov]['cargado'])) {
                $miAsiento['Movimiento'][$kMov]['cargado'] = false;
            }
            if($cuentaclienteid==$movimiento['cuentascliente_id']){
                $key=$kMov;
                $movid=$movimiento['id'];
                $asiento_id=$movimiento['asiento_id'];
                //$debe=$movimiento['debe'];
                //$haber=$movimiento['haber'];
                $miAsiento['Movimiento'][$kMov]['cargado']=true;
            }
        }
        //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
        //echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.key',['default'=>$key]);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.id',['default'=>$movid]);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.cuentascliente_id',['default'=>$cuentaclienteid]);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.cuenta_id',[
            'readonly'=>'readonly',
            'type'=>'hidden',
            'orden'=>$i,
            'value'=>$cuentaid,
            'id'=>$numeroAsiento.'cuenta'.$cuentaid]);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.debe',[
            'default'=>number_format($debe, 2, ".", ""),]);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.haber',[
            'default'=>number_format($haber, 2, ".", ""),]);
        echo "</br>";
        $i++;
    }
    /*aca sucede que pueden haber movimientos extras para este asieto estandar, digamos agregados a mano
    entonces tenemos que recorrer los movimientos y aquellos que esten marcados como cargado=false se deben mostrar*/
    foreach ($miAsiento['Movimiento'] as $kMov => $movimiento){
        $movid=0;
        $asiento_id=0;
        $debe=0;
        $haber=0;
        $cuentaclienteid=0;
        if( $miAsiento['Movimiento'][$kMov]['cargado']==false){
            $movid=$movimiento['id'];
            $asiento_id=$movimiento['asiento_id'];
            //$debe=$movimiento['debe'];
            //$haber=$movimiento['haber'];
            $cuentaclienteid=$movimiento['cuentascliente_id'];
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.id',['default'=>$movid]);
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.cuentascliente_id',['default'=>$cuentaclienteid,'disabled'=>'disabled']);
            
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.debe',[
                'default'=>number_format($debe, 2, ".", ""),]);
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.haber',[
                'default'=>number_format($haber, 2, ".", ""),]);
            echo "</br>";
            $i++;
        }
    }
    //Bueno aca vamos a poner el segundo asiento de impuesto
    if(count($impcli2['Impuesto']['Asientoestandare'])>0){
        $numeroAsiento++;
        ?>
        <h3><?php echo __('Contabilizar Cancelamiento de : '.$impcli2['Impuesto']['nombre']); ?></h3>
        <?php
        $id = 0;
        $nombre = "Cancelamiento ".$impcli2['Impuesto']['nombre'].": ".$periodo;
        $descripcion = "Automatico";
        $fecha = date('d-m-Y');
        $miAsiento=array();
        if(!isset($miAsiento['Movimiento'])){
            $miAsiento['Movimiento']=array();
        }
        if(isset($impcli2['Asiento'][0])){
            $miAsiento = $impcli2['Asiento'][0];
            $id = $miAsiento['id'];
            $nombre = $miAsiento['nombre'];
            $descripcion = $miAsiento['descripcion'];
            $fecha = date('d-m-Y',strtotime($miAsiento['fecha']));
        }

        echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','action'=>'add','style'=>' min-width: max-content;']);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.id',['default'=>$id]);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.nombre',['default'=>$nombre]);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.descripcion',['default'=>$descripcion]);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.fecha',['default'=>$fecha]);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.cliente_id',['default'=>$impcli2['Impcli']['cliente_id'],'type'=>'hidden']);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.impcli_id',['default'=>$impcli2['Impcli']['id'],'type'=>'hidden']);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.periodo',['value'=>$periodo]);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.tipoasiento',['default'=>'impuestos2','type'=>'hidden']);
        /*1.Preguntar si existe la cuenta cliente que apunte a la cuenta 8(idDeCuenta) */
        /*2. Si no existe se la crea y la traigo*/
        /*3. Si existe la traigo*/
        $i=0;
        echo "</br>";
        $cuentaclienteid = 0;
        foreach ($impcli2['Impuesto']['Asientoestandare'] as $asientoestandar) {
            $cuentaclienteid = $asientoestandar['Cuenta']['Cuentascliente'][0]['id'];
            $cuentaid = $asientoestandar['Cuenta']['id'];
            /*lo mismo que hicimos con el asiento vamos a hacer con los movimientos, si existe un movimiento
                    con la cuentacliente_id que estamos queriendo armar rellenamos los datos*/
            $movid=0;
            $asiento_id=0;
            $debe=0;
            $haber=0;
            $key=0;

            foreach ($miAsiento['Movimiento'] as $kMov => $movimiento){
                if(!isset($miAsiento['Movimiento'][$kMov]['cargado'])) {
                    $miAsiento['Movimiento'][$kMov]['cargado'] = false;
                }
                if($cuentaclienteid==$movimiento['cuentascliente_id']){
                    $key=$kMov;
                    $movid=$movimiento['id'];
                    $asiento_id=$movimiento['asiento_id'];
                    //$debe=$movimiento['debe'];
                    //$haber=$movimiento['haber'];
                    $miAsiento['Movimiento'][$kMov]['cargado']=true;
                }
            }
            //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
            //echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.key',['default'=>$key]);
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.id',['default'=>$movid]);
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.cuentascliente_id',['default'=>$cuentaclienteid]);
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.cuenta_id',[
                'readonly'=>'readonly',
                'type'=>'hidden',
                'orden'=>$i,
                'value'=>$cuentaid,
                'id'=>$numeroAsiento.'cuenta'.$cuentaid]);
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.debe',[
                'default'=>number_format($debe, 2, ".", ""),]);
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.haber',[
                'default'=>number_format($haber, 2, ".", ""),]);
            echo "</br>";
            $i++;
        }
        /*aca sucede que pueden haber movimientos extras para este asieto estandar, digamos agregados a mano
        entonces tenemos que recorrer los movimientos y aquellos que esten marcados como cargado=false se deben mostrar*/
        foreach ($miAsiento['Movimiento'] as $kMov => $movimiento){
            $movid=0;
            $asiento_id=0;
            $debe=0;
            $haber=0;
            $cuentaclienteid=0;
            if( $miAsiento['Movimiento'][$kMov]['cargado']==false){
                $movid=$movimiento['id'];
                $asiento_id=$movimiento['asiento_id'];
                //$debe=$movimiento['debe'];
                //$haber=$movimiento['haber'];
                $cuentaclienteid=$movimiento['cuentascliente_id'];
                echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.id',['default'=>$movid]);
                echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
                echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.cuentascliente_id',['default'=>$cuentaclienteid,'disabled'=>'disabled']);

                echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.debe',[
                    'default'=>number_format($debe, 2, ".", ""),]);
                echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.haber',[
                    'default'=>number_format($haber, 2, ".", ""),]);
                echo "</br>";
                $i++;
            }
        }
    }
    //Bueno aca vamos a poner el asiento de PAGO
    ?>
    <h3><?php echo __('Asiento de Pago de : '.$impclipago['Impuesto']['nombre']); ?></h3>
    <?php
    $numeroAsiento++;
    $id = 0;
    $nombre = "Pago de impuesto: ".$impcli['Impuesto']['nombre'];
    $descripcion = "Automatico";
    $fecha = date('t-m-Y',strtotime('01-'.$periodo));
    $miAsiento=array();
    $totalDebe=0;
    $totalHaber=0;
    if(!isset($miAsiento['Movimiento'])){
        $miAsiento['Movimiento']=array();
    }
    if(isset($impclipago['Asiento'][0])){
        $miAsiento = $impclipago['Asiento'][0];
        $id = $miAsiento['id'];
        $nombre = $miAsiento['nombre'];
        $descripcion = $miAsiento['descripcion'];
        $fecha = date('d-m-Y',strtotime($miAsiento['fecha']));
    }
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.id',['default'=>$id]);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.nombre',['default'=>$nombre]);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.descripcion',['default'=>$descripcion]);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.fecha',['default'=>$fecha]);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.cliente_id',['default'=>$impclipago['Impcli']['cliente_id'],'type'=>'hidden']);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.periodo',['value'=>$periodo]);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.impcli_id',['value'=>$impclipago['Impcli']['id'],'type'=>'hidden']);
    echo $this->Form->input('Asiento.'.$numeroAsiento.'.tipoasiento',['value'=>'pagos','type'=>'hidden']);
    /*1.Preguntar si existe la cuenta cliente que apunte a la cuenta 8(idDeCuenta) */
    /*2. Si no existe se la crea y la traigo*/
    /*3. Si existe la traigo*/
    $i=0;
    echo "</br>";
    $cuentaclienteid = 0;
    $asientoestandarespago=$impclipago['Impuesto']['Asientoestandare'];
    foreach ($asientoestandarespago as $asientoestandar) {
        $cuentaid = $asientoestandar['Cuenta']['id'];
        $cuentaclienteid = $asientoestandar['Cuenta']['Cuentascliente'][0]['id'];
        /*lo mismo que hicimos con el asiento vamos a hacer con los movimientos, si existe un movimiento
        con la cuentacliente_id que estamos queriendo armar rellenamos los datos*/
        $movid=0;
        $asiento_id=0;
        $debe=0;
        $haber=0;
        $key=0;

        foreach ($miAsiento['Movimiento'] as $kMov => $movimiento){
            if(!isset($miAsiento['Movimiento'][$kMov]['cargado'])) {
                $miAsiento['Movimiento'][$kMov]['cargado'] = false;
            }
            if($cuentaclienteid==$movimiento['cuentascliente_id']){
                $key=$kMov;
                $movid=$movimiento['id'];
                $asiento_id=$movimiento['asiento_id'];
                //$debe=$movimiento['debe'];
                //$haber=$movimiento['haber'];
                $miAsiento['Movimiento'][$kMov]['cargado']=true;
            }
        }
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.id',['default'=>$movid]);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.cuentascliente_id',[
            'label' => ($i != 0) ? false : 'Cuenta',
            'default'=>$cuentaclienteid,
                            'defaultoption'=>$cuentaclienteid,
                            'class'=>'chosen-select-cuenta',
                    ]);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.cuenta_id',[
            'readonly'=>'readonly',
            'type'=>'hidden',
            'orden'=>$i,
            'value'=>$cuentaid,
            'id'=>$numeroAsiento.'cuentaPago'.$cuentaid]);
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.fecha', array(
                'type'=>'hidden',
                'readonly','readonly',
                'value'=>date('d-m-Y'),
        ));
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.debe',[
            'label' => ($i != 0) ? false : 'Debe',
            'class'=>'inputDebe',
            'default'=>number_format($debe, 2, ".", ""),
            'orden'=>$i,
        ]);
        $totalDebe += $debe;
        echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.haber',[
            'label' => ($i != 0) ? false : 'Haber',
            'class'=>'inputHaber',
            'default'=>number_format($haber, 2, ".", "")
            ]);
        $totalHaber += $haber;
        echo "</br>";
                    $i++;
        }
        //cuentas comun a todos los pagos
        foreach ($cuentaspagoimpuestos as $cpi => $cuentaspagoimpuesto) {
            $movid = 0;
            $asiento_id = 0;
            $debe = 0;
            $haber = 0;
            $cuentaclienteid = $cuentaspagoimpuesto['Cuentascliente'][0]['id'];
            $cuentaid = $cuentaspagoimpuesto['Cuentascliente'][0]['cuenta_id'];
            if(isset($asientoyacargado['Movimiento'])) {
                foreach ($asientoyacargado['Movimiento'] as $kMov => $movimiento) {
                    if(!isset($asientoyacargado['Movimiento'][$kMov]['cargado'])) {
                        $asientoyacargado['Movimiento'][$kMov]['cargado'] = false;
                    }
                    if($cuentaclienteid==$movimiento['cuentascliente_id']) {
                        $movid = $movimiento['id'];
                        $asiento_id = $movimiento['asiento_id'];
                        //$debe = $movimiento['debe'];
                        //$haber = $movimiento['haber'];
                        $asientoyacargado['Movimiento'][$kMov]['cargado']=true;
                    }
                }
            }
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.' . $i . '.id', ['default' => $movid]);
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.' . $i . '.asiento_id', ['default' => $asiento_id, 'type' => 'hidden']);
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.' . $i . '.cuentascliente_id', [
                'label' => ($i != 0) ? false : 'Cuenta',
                'default' => $cuentaclienteid,
                'class'=>'chosen-select-cuenta',
                ]
            );
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.cuenta_id',[
                'readonly'=>'readonly',
                'type'=>'hidden',
                'orden'=>$i,
                'value'=>$cuentaid,
                'id'=>$numeroAsiento.'cuentaPago'.$cuentaid
            ]);
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.fecha', array(
                'type'=>'hidden',
                'readonly','readonly',
                'value'=>date('d-m-Y'),
            ));
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.' . $i . '.debe', [
                'label' => ($i != 0) ? false : 'Debe',
                'class'=>'inputDebe',
                'default' => $debe
            ]);
            $totalDebe += $debe;
            echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.' . $i . '.haber', [
                'label' => ($i != 0) ? false : 'Haber',
                'class'=>'inputHaber',
                'default' => $haber,
                'cuentaasientopago' => 'si',
                'cuentacontable' => $cuentaspagoimpuesto['Cuenta']['id'],
                'orden' => $i,
            ]);
            $totalHaber += $haber;
            echo "</br>";
                        $i++;
        }
        /*aca sucede que pueden haber movimientos extras para este asieto estandar, digamos agregados a mano
        entonces tenemos que recorrer los movimientos y aquellos que esten marcados como cargado=false se deben mostrar*/
        foreach ($miAsiento['Movimiento'] as $kMov => $movimiento){
            $movid=0;
            $asiento_id=0;
            $debe=0;
            $haber=0;
            $cuentaclienteid=0;
            if( $miAsiento['Movimiento'][$kMov]['cargado']==false){
                $movid=$movimiento['id'];
                $asiento_id=$movimiento['asiento_id'];
                //$debe=$movimiento['debe'];
                //$haber=$movimiento['haber'];
                $cuentaclienteid=$movimiento['cuentascliente_id'];
                echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.id',['default'=>$movid]);
                echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
                echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.'.$i.'.cuentascliente_id',['default'=>$cuentaclienteid,'disabled'=>'disabled']);
                echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.' . $i . '.debe', [
                    'label' => ($i != 0) ? false : 'Debe',
                    'class'=>'inputDebe',
                    'default' => $debe
                ]);
                $totalDebe += $debe;
                echo $this->Form->input('Asiento.'.$numeroAsiento.'.Movimiento.' . $i . '.haber', [
                    'label' => ($i != 0) ? false : 'Haber',
                    'class'=>'inputHaber',
                    'default' => $haber,
                    'orden' => $i,
                ]);
                echo "</br>";
                $i++;
            }
        }
        echo $this->Form->end();
        echo $this->Form->label('','&nbsp; ',[
            'style'=>"display: -webkit-inline-box;width:330px;"
        ]);
        echo $this->Form->label('lblTotalDebe',
            "$".number_format($totalDebe, 2, ".", ""),
            [
                'id'=>'lblTotalDebe',
                'style'=>"display: inline;"
            ]
        );
        echo $this->Form->label('','&nbsp;',['style'=>"display: -webkit-inline-box;width:70px;"]);
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
    echo $this->Form->end();
    ?>
</div>
