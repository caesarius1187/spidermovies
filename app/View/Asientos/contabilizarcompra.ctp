<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 03/11/2016
 * Time: 12:33 PM
 */
?>
<div class="index">
    <h3><?php echo __('Contabilizar Compras del cliente: '.$cliente['Cliente']['nombre']." para el periodo: ".$periodo); ?></h3>
    <?php
    $id = 0;
    $nombre = "Asiento devengamiento Compra: ".$periodo;
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

    echo $this->Form->create('Asiento',['class'=>'formTareaCarga ','action'=>'add']);
    echo $this->Form->input('Asiento.0.id',['default'=>$id]);
    echo $this->Form->input('Asiento.0.nombre',['default'=>$nombre]);
    echo $this->Form->input('Asiento.0.descripcion',['default'=>$descripcion]);
    echo $this->Form->input('Asiento.0.fecha',['default'=>$fecha]);
    echo $this->Form->input('Asiento.0.cliente_id',['default'=>$cliid,'type'=>'hidden']);
    echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo]);
    echo $this->Form->input('Asiento.0.tipoasiento',['default'=>'ventas','type'=>'hidden']);
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
        if(isset($asientoyacargado['Movimiento'])) {
            foreach ($asientoyacargado['Movimiento'] as $kMov => $movimiento) {
                if (!isset($asientoyacargado['Movimiento'][$kMov]['cargado'])) {
                    $asientoyacargado['Movimiento'][$kMov]['cargado'] = false;
                }
                if ($cuentaclienteid == $movimiento['cuentascliente_id']) {

                    $key = $kMov;
                    $movid = $movimiento['id'];
                    $asiento_id = $movimiento['asiento_id'];
                    $debe = $movimiento['debe'];
                    $haber = $movimiento['haber'];
                    $asientoyacargado['Movimiento'][$kMov]['cargado'] = true;
                }
            }
        }
        //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
        //echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.key',['default'=>$key]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['default'=>$movid]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',['default'=>$cuentaclienteid,'disabled'=>'disabled']);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',['default'=>$debe,'disabled'=>'disabled']);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',['default'=>$haber,'disabled'=>'disabled']);
        echo "</br>";
        $i++;
    }
    /*aca sucede que pueden haber movimientos extras para este asieto estandar, digamos agregados a mano
    entonces tenemos que recorrer los movimientos y aquellos que esten marcados como cargado=false se deben mostrar*/
    if(isset($asientoyacargado['Asiento'])) {
        foreach ($asientoyacargado['Movimiento'] as $kMov => $movimiento) {
            $movid = 0;
            $asiento_id = 0;
            $debe = 0;
            $haber = 0;
            $cuentaclienteid = 0;
            if ($asientoyacargado['Movimiento'][$kMov]['cargado'] == false) {
                $movid = $movimiento['id'];
                $asiento_id = $movimiento['asiento_id'];
                $debe = $movimiento['debe'];
                $haber = $movimiento['haber'];
                $cuentaclienteid = $movimiento['cuentascliente_id'];
                echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.id', ['default' => $movid]);
                echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.asiento_id', ['default' => $asiento_id, 'type' => 'hidden']);
                echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.cuentascliente_id', ['default' => $cuentaclienteid, 'disabled' => 'disabled']);
                echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.debe', ['default' => $debe, 'disabled ' => 'disabled']);
                echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.haber', ['default' => $haber, 'disabled' => 'disabled']);
                echo "</br>";
                $i++;
            }
        }
    }
    echo $this->Form->end('Guardar asiento');
    //Debugger::dump($miAsiento['Movimiento']);
    ?>
</div>
