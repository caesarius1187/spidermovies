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
    <h2><?php echo __('Contabilizar Retenciones: '.$cliente['Cliente']['nombre'] ); ?></h2>


    <?php
    $j=0;
    $totalDebe=0;
    $totalHaber=0;
    foreach ($cliente['Impcli'] as $impcli) {
        //para cada impcli vamos a traer los asientos estandares de retenciones sufridas
        if(count($impcli['Impuesto']['Asientoestandare'])==0){continue;}
        //si esto es real es porque este impuesto esta configurado para hacer un asiento estandar de retenciones sufridas
        $asientoNumero=0;
        $id = 0;
        $nombre = "Retenciones sufridas: ".$impcli['Impuesto']['nombre'];
        $descripcion = "Automatico";
        $fecha = date('t-m-Y',strtotime('01-'.$periodo));
        $miAsiento=array();
        $misMovimientos=array();
        foreach ($asientosyacargados as $asientoyacargado) {
            if($asientoyacargado['Asiento']['impcli_id']==$impcli['id']){
                $miAsiento = $asientoyacargado['Asiento'];
                $misMovimientos = $asientoyacargado['Movimiento'];
                $id = $miAsiento['id'];
                $nombre = $miAsiento['nombre'];
                $descripcion = $miAsiento['descripcion'];
                $fecha = date('d-m-Y',strtotime($miAsiento['fecha']));
                
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
                
            }
        }
        if(!isset($miAsiento['Movimiento'])){
            $miAsiento['Movimiento']=array();
        }

        //Asiento de acreditaciones
        echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','action'=>'add']);
        echo $this->Form->input('Asiento.'.$j.'.id',['value'=>$id]);
        echo $this->Form->input('Asiento.'.$j.'.nombre',['value'=>$nombre,'style'=>'width:300px']);
        echo $this->Form->input('Asiento.'.$j.'.descripcion',['value'=>$descripcion]);
        echo $this->Form->input('Asiento.'.$j.'.fecha',['value'=>$fecha]);
        echo $this->Form->input('Asiento.'.$j.'.cliente_id',['value'=>$cliid,'type'=>'hidden']);
        echo $this->Form->input('Asiento.'.$j.'.impcli_id',['value'=>$impcli['id'],'type'=>'hidden']);
        echo $this->Form->input('Asiento.'.$j.'.periodo',['value'=>$periodo]);
        echo $this->Form->input('Asiento.'.$j.'.tipoasiento',['default'=>'retencionessufridas','type'=>'hidden'])."</br>";
        $conceptosTotal=0;

        foreach ($impcli['Conceptosrestante'] as $conceptorestante) {
            $conceptosTotal+=$conceptorestante['montoretenido'];
        }

        $i=0;
        foreach ($impcli['Impuesto']['Asientoestandare'] as $asientoestandarCM) {

            if(!isset($movId[$asientoestandarCM['cuenta_id']])){
                $movId[$asientoestandarCM['cuenta_id']]=0;
            }
            $cuentaclienteid=0;
            $cuentaclientenombre=$asientoestandarCM['Cuenta']['nombre'];

            if(isset($cuentaxcuentacliente[$asientoestandarCM['cuenta_id']])){
                //si existe esto es por que la cuentacliente esta dada de alta
                $cuentaclienteid=$cuentaxcuentacliente[$asientoestandarCM['cuenta_id']];
                //saco el nombre de la lista de [cuentascliente.id => cuenta.nombre]
                if(isset($cuentasclientes[$cuentaclienteid])){
                    $cuentaclientenombre= $cuentasclientes[$cuentaclienteid];
                }
//                $cuentaclientenombre=$cuentaclientaCm['nombre'];
            }
            
//            foreach ($impcli['Cliente']['Cuentascliente'] as $cuentaclienta){
//                if($cuentaclientaCm['cuenta_id']==$asientoestandarCM['cuenta_id']){
//                    $cuentaclienteid=$cuentaclientaCm['id'];
//                    $cuentaclientenombre=$cuentaclientaCm['nombre'];
//                    break;
//                }
//            }
            
            //ahora vamos a sumar las retenciones de este impuesto
            $debe=0;
            $haber=0;
            if($asientoestandarCM['cuenta_id']=='5'){
                //movimiento estandar que suma to do al haber
                $haber=$conceptosTotal;
            }else{
                $debe=$conceptosTotal;
            }
            $movimientoConValor = "movimientoSinValor";
            if(($debe*1) != 0 || ($haber*1) != 0){
                $movimientoConValor = "movimientoConValor";
            }
            echo $this->Form->input('Asiento.'.$j.'.Movimiento.'.$i.'.id',['value'=>$movId[$asientoestandarCM['cuenta_id']],]);
            echo $this->Form->input('Asiento.'.$j.'.Movimiento.'.$i.'.fecha', array(
                'type'=>'hidden',
                'readonly','readonly',
                'value'=>date('d-m-Y'),
            ));
            echo $this->Form->input('Asiento.'.$j.'.Movimiento.'.$i.'.cuentascliente_id',[
                'readonly'=>'readonly','type'=>'hidden','value'=>$cuentaclienteid]);
            echo $this->Form->input('Asiento.'.$j.'.Movimiento.'.$i.'.cuenta_id',['readonly'=>'readonly','type'=>'hidden','orden'=>$i,'value'=>$asientoestandarCM['cuenta_id'],'id'=>'cuenta'.$asientoestandarCM['cuenta_id']]);
            echo $this->Form->input('Asiento.'.$j.'.Movimiento.'.$i.'.numero',['label'=>($i!=0)?false:'Numero','readonly'=>'readonly','value'=>$asientoestandarCM['Cuenta']['numero'],'style'=>'width:82px']);
            echo $this->Form->input('Asiento.'.$j.'.Movimiento.'.$i.'.nombre',['label'=>($i!=0)?false:'Nombre','readonly'=>'readonly','value'=>$cuentaclientenombre,'type'=>'text','style'=>'width:250px']);
            echo $this->Form->input('Asiento.'.$j.'.Movimiento.'.$i.'.debe',[
                'label'=>($i!=0)?false:'Debe',
                'class'=>"inputDebe ".$movimientoConValor,
                'value'=>$debe,
            ]);
            echo $this->Form->input('Asiento.'.$j.'.Movimiento.'.$i.'.haber',[
                    'label'=>($i!=0)?false:'Haber',
                    'class'=>"inputHaber ".$movimientoConValor,
                    'value'=>$haber,
                ])."</br>";
            $totalDebe +=$debe;
            $totalHaber +=$haber;
            $i++;
        }
        $j++;

        
    }
    echo $this->Form->end('Guardar asiento');
    echo $this->Form->label('','&nbsp; ',[
        'style'=>"display: -webkit-inline-box;width:350px;"
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
    ?>
</div>
