<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 03/11/2016
 * Time: 12:33 PM
 */
?>
<div class="index">
    <h3><?php echo __('Contabilizar Liquidacion de impuesto: '.$impCli['Impuesto']['nombre']); ?></h3>
    <?php
    echo $this->Form->create('Asiento',['class'=>'formTareaCarga ']);
    echo $this->Form->input('Asiento.0.fecha');
    echo $this->Form->input('Asiento.0.fecha');
    /*1.Preguntar si existe la cuenta cliente que apunte a la cuenta 8(idDeCuenta) */
    /*2. Si no existe se la crea y la traigo*/
    /*3. Si existe la traigo*/
    $i=0;
    echo "</br>";
    foreach ($impCli['Impuesto']['Asientoestandare'] as $asientoestandar) {
        $cuentaclienteid = $asientoestandar['Cuenta']['Cuentascliente'][0]['id'];
        //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',[]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',['default'=>$cuentaclienteid]);
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe');
        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber');
        echo "</br>";
    }
    ?>
    <div class="fab blue">
        <core-icon icon="add" align="center" style="margin: 8px 14px;">
            <?php echo $this->Form->button('+',
                array(
                    'title'=>'Agregar Movimiento',
                    'id'=>'btnAgregarMovimiento',
                    'type' => 'button',
                    'class' =>"btn_add",
                    'onClick' => "agregarMovimiento()'",
                )
            );?>
        </core-icon>
        <paper-ripple class="circle recenteringTouch" fit></paper-ripple>
    </div>
</div>
