<?php 
if(!$mostrarFormulario){ ?>      
    <td><?php echo h($domicilio['Domicilio']['calle']); ?></td> 
    <td><?php echo h($domicilio['Localidade']['Partido']['nombre']); ?></td>
    <td><?php echo h($domicilio['Localidade']['nombre']); ?></td> 
    <td><?php echo h($domicilio['Domicilio']['superficie']); ?></td> 
    <td class="">
        <a href="#"  onclick="loadFormDomicilio(<?php echo$domicilio['Domicilio']['id']; ?>,<?php echo $domicilio['Domicilio']['cliente_id'];?>)" class="button_view"> 
         <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
        </a> 
        <?php echo $this->Form->postLink(
                     $this->Html->image('ic_delete_black_24dp.png', array(
                        'alt' => 'Eliminar',
                    )),
                    array(
                        'controller' => 'Domicilios',
                        'action' => 'delete',
                        $domicilio['Domicilio']['id'],
                        $domicilio['Domicilio']['cliente_id']
                    ),
                    array(
                        'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
                    ),
                    __('Esta seguro que quiere eliminar este domicilio?')                                    
            ); ?>
    </td>      
<?php
}else{
?>
<?php echo $this->Form->create('Domicilio',array('action' => 'edit', )); ?>
	<?php
		 echo $this->Form->input('id');
		echo $this->Form->input('cliente_id',array('type'=>'hidden'));
	?>
	<h3><?php echo __('Editar Domicilio'); ?></h3>
    <table cellpadding="0" cellspacing="0" border="0">
        <tr>  
            <td style="width: 150px;"><?php  echo $this->Form->input('tipo', array('label'=>'Tipo','type'=>'select','class'=>'chosen-select','options'=>array('comercial'=>'Comercial','fiscal'=>'Fiscal','personal'=>'Personal','laboral'=>'Laboral'),'default'=>$this->request->data['Domicilio']['tipo']));?></td>
            <td><?php echo $this->Form->input('localidade_id', array(
                                                            'label'=>'Localidad',
                                                            'class'=>'chosen-select'
                                                            )
                                            );?>
            </td>
            <td><?php echo $this->Form->input('codigopostal', array('label'=>'C&oacute;d. Postal', 'size' => '3'));?></td>            
            <td><?php echo $this->Form->input('superficie', array('label'=>'Superficie', 'style'=>'width:85%','title'=>'Este valor sera utilizado al generar la DDJJ para Monotributo'));?></td>            
        <tr>
            <td colspan="5"><?php echo $this->Form->input('calle', array('label'=>'Domicilio','style'=>'width:95%'));?></td> 
        </tr>
        <tr>
            <td colspan="5"><?php echo $this->Form->input('observaciones', array('label'=>'Observaciones','style'=>'width:95%', 'size' => '35'));?></td>    
        </tr>    
        <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>    
            <td>
                <a href="#close"  onclick="" class="btn_cancelar" style="margin-top:14px">Cancelar</a>  
            </td>  
            <td>
                <?php echo $this->Form->end(__('Aceptar')); ?>
            </td>    
        </tr> 
    </table>	
<?php } ?>