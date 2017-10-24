<?php
/**
 * Created by PhpStorm.
 * User: coto1
 * Date: 28/09/2017
 * Time: 02:08 PM
 */
?>
<div id="relacionarBienesdeuso" >
    <?php
    echo $this->Form->create('Bienesdeuso',['controller'=>'Bienesdeuso','action'=>'relacionarventa']);
    echo $this->Form->input('venta_id',[
        'type'=>'hidden',
        'value'=>$ventaid
    ]);
    echo $this->Form->input('bienesdeuso_id',[
        'style'=>'width:auto',
        'type'=>'select',
        'default'=>$biendeusoSeleccionado,
        'options'=>$bienesdeusos
    ]);
    //    echo $this->Form->submit('relacionar');
    echo $this->Form->end();
    ?>

</div>
