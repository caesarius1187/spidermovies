<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 21/10/2016
 * Time: 12:04 PM
 */

?>
<div id="divUsosaldoAdd" class="index">
    Agregar Uso del Saldo de Libre Disponibilidad del periodo : <?php echo $conceptosrestante['Conceptosrestante']['periodo'] ?>
</div>
<div id="divUsosaldoAdd" class="index">
    Monto Inicial:<?php echo $conceptosrestante['Conceptosrestante']['montoretenido'] ?>
    Saldo:<?php echo $conceptosrestante['Conceptosrestante']['monto'] ?>
    <?php
    echo $this->Form->create('Usosaldo',array('action'=>'add','class'=>'formTareaCarga'));
    echo $this->Form->input('eventosimpuesto_id',array('type'=>'hidden'));
    echo $this->Form->input('conceptosrestante_id',array('type'=>'hidden','value'=>$conceptosrestante['Conceptosrestante']['id']));
    echo $this->Form->input('fecha', array(
            'class'=>'datepicker-dia',
            'style'=>'width:90px',
            'type'=>'text',
            'default'=>date('d-m-Y'),
            'readonly'=>'readonly',
            'required'=>true
        )
    );
    echo $this->Form->input('importe',array(
        'max' =>$conceptosrestante['Conceptosrestante']['monto']?$conceptosrestante['Conceptosrestante']['monto']:0,
        'min' => 0,
    ));
    echo $this->Form->input('descripcion',array(
        'style'=>'width:90px',
    ));
    echo $this->Form->submit('Guardar');
    echo $this->Form->end();
    ?>
</div>
<div id="divUsosaldoIndex" class="index">
    Usos ya registrados del Saldo de Libre Disponibilidad
    <table>
        <thead>
            <tr>
                <td>
                    Impuesto
                </td>
                <td>
                    Fecha
                </td>
                <td>
                    Importe
                </td>
                <td>
                    Descripcion
                </td>
            </tr>
        </thead>
        <tbody>
        <?php
       // Debugger::dump($usosaldos);
        foreach ($usosaldos as $usosaldo) { ?>
            <tr>
                <td>
                    <?php
                    if(isset($usosaldo['Eventoimpuesto']['Impcli']['Impuesto']['nombre'])){
                        echo $usosaldo['Eventoimpuesto']['Impcli']['Impuesto']['nombre'];
                    }?>
                </td>
                <td>
                    <?php echo $usosaldo['Usosaldo']['fecha']?>
                </td>
                <td>
                    <?php echo $usosaldo['Usosaldo']['importe']?>
                </td>
                <td>
                    <?php echo $usosaldo['Usosaldo']['descripcion']?>
                </td>
            </tr>
        <?php }
        ?>
        </tbody>
    </table>
</div>
<a class="close" href="#close"></a>
