<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 03/11/2016
 * Time: 12:33 PM
 */
?>
<?php
if(isset($error)){ ?>
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
    $nombre = "Asiento devengamiento Bancos: ".$periodo;
    $descripcion = "Asiento automatico";
    $fecha = date('d-m-Y');
    $miAsiento=array();
    if(!isset($miAsiento['Movimiento'])){
        $miAsiento['Movimiento']=array();
    }
    if(isset($asientoyacargado['Asiento'])){
        $miAsiento = $asientoyacargado['Asiento'];
        $id = $miAsiento['id'];
        $nombre = $miAsiento['nombre'];
        $descripcion = $miAsiento['descripcion'];
        $fecha = date('d-m-Y',strtotime($miAsiento['fecha']));
    }

    echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','action'=>'add']);
    echo $this->Form->input('Asiento.0.id',['default'=>$id]);
    echo $this->Form->input('Asiento.0.nombre',['default'=>$nombre]);
    echo $this->Form->input('Asiento.0.descripcion',['default'=>$descripcion]);
    echo $this->Form->input('Asiento.0.fecha',['default'=>$fecha]);
    echo $this->Form->input('Asiento.0.cliente_id',['default'=>$cliid,'type'=>'hidden']);
    echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo]);
    echo $this->Form->input('Asiento.0.tipoasiento',['default'=>'bancos','type'=>'hidden']);
    /*1.Preguntar si existe la cuenta cliente que apunte a la cuenta 8(idDeCuenta) */
    /*2. Si no existe se la crea y la traigo*/
    /*3. Si existe la traigo*/
    $i=0;
    echo "</br>";
    $cuentaclienteid = 0;
    foreach ($movimientosbancarios as $movimiento) {
        $cuentaclienteid = $movimiento['Cuentascliente']['id'];
        $cuentaclientenombre = $movimiento['Cuentascliente']['nombre'];
        $cuentaid = $movimiento['Cuenta']['id'];
        $cuentanumero = $movimiento['Cuenta']['numero'];
        /*lo mismo que hicimos con el asiento vamos a hacer con los movimientos, si existe un movimiento
                con la cuentacliente_id que estamos queriendo armar rellenamos los datos*/
        $movid=0;
        $asiento_id=0;
        $debe=0;
        $haber=0;
        $key=0;

        if(isset($asientoyacargado['Movimiento'])) {
            foreach ($asientoyacargado['Movimiento'] as $movimientoyacargado){
                if($cuentaclienteid==$movimientoyacargado['cuentascliente_id']){
                    $movid=$movimientoyacargado['id'];
                    $asiento_id=$movimientoyacargado['asiento_id'];
                }
            }
        }
        //vamos a acumular los movimientos bancarios cargados con su debe y haber para cada cuenta
        $debito = 0;
        $credito = 0;
        foreach ($movimiento['Movimientosbancario'] as $movimientobancario ){
            $debito += str_replace(".",",",$movimientobancario['debito'])*1;
            $credito += str_replace(".",",",$movimientobancario['credito'])*1;
        }
        $saldo = $debito-$credito;
        if($saldo>0){
            $debe = $saldo;
            $haber = 0;
        }else{
            $debe = 0;
            $haber = $saldo*-1;
        }
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['default'=>$movid]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
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
            'label'=>($i!=0)?false:'Debe',
            'value'=>$debe,
            'readonly'=>'readonly',
        ]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
                'label'=>($i!=0)?false:'Haber',
                'readonly'=>'readonly',
                'value'=>$haber,
            ])."</br>";
        echo "</br>";
        $i++;
    }
    echo $this->Form->end('Guardar asiento');

    ?>
</div>
