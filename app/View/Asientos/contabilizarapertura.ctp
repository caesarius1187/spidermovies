<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 03/11/2016
 * Time: 12:33 PM
 */
?>
<div class="">
    <h3><?php echo __('Asiento de Apertura'); ?></h3>
    <?php
    $id = 0;
    $nombre = "Asiento de Apertura";
    $descripcion = "Manual";
    $fecha = date('t-m-Y');
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
    echo $this->Form->input('Asiento.0.tipoasiento',['default'=>'Apertura','type'=>'hidden']);
    /*1.Preguntar si existe la cuenta cliente que apunte a la cuenta 8(idDeCuenta) */
    /*2. Si no existe se la crea y la traigo*/
    /*3. Si existe la traigo*/
    $i=0;
    echo "</br>";
    $cuentaclienteid = 0;
    foreach ($miAsiento['Movimiento'] as $kMov => $movimiento){
        
        $key=$kMov;
        $movid=$movimiento['id'];
        $asiento_id=$movimiento['asiento_id'];
        $debe=$movimiento['debe'];
        $haber=$movimiento['haber'];
        $cuentaid=$movimiento['Cuentascliente']['cuenta_id'];
        $cuentaclienteid=$movimiento['Cuentascliente']['id'];
       
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
            'label' => ($i != 0) ? false : 'Cuenta',
            'id'=>'cuenta'.$cuentaid]);
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
