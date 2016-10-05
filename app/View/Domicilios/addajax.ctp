<?php
if(isset($respuesta)){
	echo $domicilio_id;

}else{?>
	<tr>
		
		<td>
			<?php echo h($domicilio['Domicilio']['calle']); ?>			
		</td>
		<td>
			<?php echo h($domicilio['Localidade']['Partido']['nombre']); ?>
		</td>
		<td>
			<?php echo h($domicilio['Localidade']['nombre']); ?>
		</td>
		<td>
			<?php echo h($domicilio['Domicilio']['superficie']); ?>
		</td>
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
                                            'class'=>'deleteDomicilio',                                            
                                            'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
                                        ),
                                        __('Esta seguro que quiere eliminar este domicilio?')                                    
                                ); ?> 
        </td>
	</tr>
<?php } ?>