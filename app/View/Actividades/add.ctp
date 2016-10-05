<?php
if(isset($respuesta)){ ?>
	<tr>		
		<td>
			<?php echo "Error al cargar la Actividad"; ?>			
		</td>
	</tr>
<?php }else{ ?>
	<tr>			
		<td>
			<?php echo h($actividade['Actividade']['descripcion']); ?>
		</td>	
		 <td class="">
            <?php echo $this->Form->postLink(
                         $this->Html->image('ic_delete_black_24dp.png', array(
                            'alt' => 'Eliminar',
                        )),
                        array(
                            'controller' => 'Actividades',
                            'action' => 'delete',
                            $actividade['Actividade']['id'],
                            $actividade['Actividade']['cliente_id']
                        ),
                        array(
                            'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
                        ),
                        __('Esta seguro que quiere eliminar esta actividad?')                                    
                ); ?>
        </td>
	</tr>
<?php } ?>