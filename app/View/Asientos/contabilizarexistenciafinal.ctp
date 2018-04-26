<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 03/11/2016
 * Time: 12:33 PM
 */
?>
<div class="">
    <h3><?php echo __('Determinacion de Existencia Final'); ?></h3>
    <?php
    $id = 0;
    $nombre = "Determinacion de Existencia Final: ".$periodo;
    $descripcion = "Automatico";
    $fecha = date('t-m-Y', strtotime('01-'.$periodo));
    //esta fecha debe ser en funcion del periodo en el que estamos consultando
    
    $miAsiento=array();
    
    if(isset($asientoyacargado['Asiento'])){
        $miAsiento = $asientoyacargado;
        $id = $miAsiento['Asiento']['id'];
        $nombre = $miAsiento['Asiento']['nombre'];
        $descripcion = $miAsiento['Asiento']['descripcion'];
        $fecha = date('d-m-Y',strtotime($miAsiento['Asiento']['fecha']));
    }
    if(!isset($miAsiento['Movimiento'])){
        $miAsiento['Movimiento']=array();
    }
    $totalDebe=0;
    $totalHaber=0;
    echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','action'=>'add','style'=>' min-width: max-content;']);
    echo $this->Form->input('Asiento.0.id',['default'=>$id]);
    echo $this->Form->input('Asiento.0.nombre',['default'=>$nombre]);
    echo $this->Form->input('Asiento.0.descripcion',['default'=>$descripcion]);
    echo $this->Form->input('Asiento.0.fecha',['default'=>$fecha]);
    echo $this->Form->input('Asiento.0.cliente_id',['default'=>$cliente['Cliente']['id'],'type'=>'hidden']);
    echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo]);
    echo $this->Form->input('Asiento.0.tipoasiento',['default'=>'Existencia Final','type'=>'hidden']);
    /*1.Preguntar si existe la cuenta cliente que apunte a la cuenta 8(idDeCuenta) */
    /*2. Si no existe se la crea y la traigo*/
    /*3. Si existe la traigo*/
    $i=0;
    echo "</br>";
    $cuentaclienteid = 0;
    foreach ($asientoestandares as $asientoestandar) {
        $cuentaclienteid = $asientoestandar['Cuenta']['Cuentascliente'][0]['id'];
        /*lo mismo que hicimos con el asiento vamos a hacer con los movimientos, si existe un movimiento
                con la cuentacliente_id que estamos queriendo armar rellenamos los datos*/
        $movid=0;
        $asiento_id=0;
        $debe=0;
        $haber=0;
        $key=0;

         switch ($asientoestandar['Cuenta']['id']){
            case '371'/*Existencia Inicial*/:
            case '366'/*Existencia Inicial*/:
            case '361'/*Existencia Inicial*/:
            case '356'/*Existencia Inicial*/:
            case '3550'/*Existencia Inicial*/:
                foreach ($cliente['Asiento'] as $kas => $asientoapertura) {
                    foreach ($asientoapertura['Movimiento'] as $kmov => $movimientoapertura) {
                        if($movimientoapertura['Cuentascliente']['cuenta_id']==$asientoestandar['Cuenta']['id']){
                            $haber+=$movimientoapertura['debe'];
                            $haber-=$movimientoapertura['haber'];
                        }
                    }
                }
                break;
        }
        
        foreach ($miAsiento['Movimiento'] as $kMov => $movimiento){
            if(!isset($miAsiento['Movimiento'][$kMov]['cargado'])) {
                $miAsiento['Movimiento'][$kMov]['cargado'] = false;
            }
            if($cuentaclienteid==$movimiento['cuentascliente_id']){

                $key=$kMov;
                $movid=$movimiento['id'];
                $asiento_id=$movimiento['asiento_id'];
                $debe=$movimiento['debe'];
                $haber=$movimiento['haber'];
                $miAsiento['Movimiento'][$kMov]['cargado']=true;
            }
        }
       
        //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
        //echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.key',['default'=>$key]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['default'=>$movid]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',['default'=>$cuentaclienteid]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuenta_id',[
            'readonly'=>'readonly',
            'type'=>'hidden',
            'orden'=>$i,
            //'value'=>$asientoestandaractvs['cuenta_id'],
            'id'=>'cuenta'.$asientoestandar['Cuenta']['id']]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
            'default'=>number_format($debe, 2, ".", ""),
            'class'=>'inputDebe',
            ]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
            'default'=>number_format($haber, 2, ".", ""),
            'class'=>'inputHaber',
            ]);
        echo "</br>";
        $i++;
        $totalDebe+=$debe;
        $totalHaber+=$haber;
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
            $debe=$movimiento['debe'];
            $haber=$movimiento['haber'];
            $cuentaclienteid=$movimiento['cuentascliente_id'];
            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['default'=>$movid]);
            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',['default'=>$cuentaclienteid,'disabled'=>'disabled']);
            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
                'default'=>number_format($debe, 2, ".", ""),
                'class'=>'inputDebe',
                ]);
            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
                'default'=>number_format($haber, 2, ".", ""),
                'class'=>'inputHaber',
                ]);
            echo "</br>";
            $i++;
            $totalDebe+=$debe;
            $totalHaber+=haber;
        }
    }
    echo $this->Form->end();
    
    echo $this->Form->label('','Total ',[
        'style'=>"display: -webkit-inline-box;width:355px;"
    ]);
    ?>
    <div style="width:98px;margin-left: 300px">
        <?php
        echo $this->Form->label('lblTotalDebe',
            number_format($totalDebe, 2, ".", ""),
            [
                'id'=>'lblTotalDebe',
                'style'=>"display: inline;float:right"
            ]
        );
        ?>
    </div>
    <div style="width:124px;margin-left: 80px">
        <?php
        echo $this->Form->label('lblTotalHaber',
            number_format($totalHaber, 2, ".", ""),
            [
                'id'=>'lblTotalHaber',
                'style'=>"display: inline;float:right"
            ]
        );
        ?>
    </div>
    <?php
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
