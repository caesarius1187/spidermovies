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
    echo $this->Form->create('Usosaldos',array('action'=>'add'));
    echo $this->Form->input('eventosimpuesto_id',array('type'=>'hidden'));
    echo $this->Form->input('conceptosrestante_id',array('type'=>'hidden','value'=>$conceptosrestante['Conceptosrestante']['id']));
    echo $this->Form->input('fecha', array(
            'class'=>'datepicker-dia',
            'style'=>'width:40px',
            'type'=>'text',
            'default'=>date('d-m-Y'),
            'readonly'=>'readonly',
            'required'=>true
        )
    );
    echo $this->Form->input('importe',array(
        'max' =>$saldo,
        'min' => 0,
    ));
    echo $this->Form->input('descripcion',array(
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
                    Fecha
                </td>
                <td>
                    Importe
                </td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($usosaldos as $usosaldo) { ?>
            <tr>
                <td>

                </td>
            </tr>
        <?php }
        ?>
        </tbody>
    </table>
</div>
<a class="close" href="#close"></a>
