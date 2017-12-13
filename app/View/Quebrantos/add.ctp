<div class="impcliprovincias form" style="width: 100%;">
<?php if(isset($error)){
    echo $error;
}else{	
    echo $this->Form->create('Quebranto',array('class'=>'formTareaCarga formAddImpcliprovincia','type' => 'post')); ?>
    <h3><?php
    echo __('Cargar Quebrantos para Ganancias PF');
?></h3>
<table class="tabla">
    <tr>
        <td colspan=3>
            <div class="div_view">
                <?php
                echo $this->Form->input('Quebranto.id',array('type'=>'hidden'));
                echo  $this->Form->input('Quebranto.cliente_id',array('type'=>'hidden','value'=>$impcliid));
                $optiontipoDeduccion=[
                  // 'general'=>'general',
                   'personal'=>'personal'
                ];
                
                echo $this->Form->input('Deduccione.periodo', array(
                    'class'=>'datepicker', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>'Periodo de aplicacion del quebranto',                                    
                    'readonly'=>'readonly'
                    )
                );
                echo $this->Form->input('Deduccione.anio', array(
                    'class'=>'datepicker', 
                    'type'=>'text',
                    'value'=>"",
                    'label'=>'Periodo de generacion del quebranto',                                    
                    'readonly'=>'readonly'
                    )
                );
                echo $this->Form->input('Quebranto.monto', array(
                    )
                );
                echo $this->Form->input('Quebranto.saldo', array(
                    )
                );
            ?>
            </div>
	</td>
    </tr>
    <tr>
            <td>&nbsp;</td>
            <td>
                    <a href="#close"  onclick="" class="btn_cancelar" style="margin-top:14px">Cancelar</a>  
            </td>
            <td>
                    <?php echo $this->Form->end(__('Aceptar')); ?>
            </td>
    </tr>
</table>
		
<?php	
}
if(!isset($error)){ ?>
    <table cellpadding="0" cellspacing="0" border="0">
        <thead>
            <td>Clase</td>
            <td>Descripcion</td>
            <td>Acciones</td>
        </thead>
        <tbody>
            <?php
            foreach ($deducciones as $deduccion) { ?>
                <tr id="#rowDeduccion<?php echo $deduccion['Deduccione']['id']; ?>">  
                        <td><?php echo $deduccion['Deduccione']['clase'];?></td>
                        <td><?php echo $deduccion['Deduccione']['nombre']
                                ." ".$deduccion['Deduccione']['cuit']
                                ." ".$deduccion['Deduccione']['documento']
                                ?></td>
                        <td>
                            <a href="#"  onclick="deleteDeduccion(<?php echo $deduccion['Deduccione']['id']; ?>)" class="button_view"> 
                                <?php echo $this->Html->image('delete.png', array('alt' => 'open','title' => 'Eliminar','class'=>'imgedit'));?>
                            </a>
                        </td>
                </tr>            
                <?php } ?>
        </tbody>
    </table>
<?php } ?>
</div>
