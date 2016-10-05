 <?php
if(isset($respuesta)){
	echo $respuesta;
}else{ ?>
	<tr id="rowpersonarelacionada<?php echo $persona['Personasrelacionada']['id']; ?>">    
	    <td><?php echo h(ucfirst ($persona['Personasrelacionada']['tipo'])); ?></td>
	    <td><?php echo h($persona['Personasrelacionada']['nombre']); ?></td> 
	    <td><?php echo h($persona['Personasrelacionada']['movil']); ?></td>
	    <td class="">
	        <a href="#"  onclick="loadFormPersonaRelacionada(<?php echo$persona['Personasrelacionada']['id']; ?>,<?php echo $persona['Personasrelacionada']['cliente_id'];?>,'rowpersonarelacionada<?php echo h($persona['Personasrelacionada']['id']); ?>')" class="button_view"> 
	            <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?> 
	        </a>      
	         <?php echo $this->Form->postLink(
	                     $this->Html->image('ic_delete_black_24dp.png', array(
	                        'alt' => 'Eliminar',
	                    )),
	                    array(
	                        'controller' => 'Personasrelacionadas',
	                        'action' => 'delete',
	                        $persona['Personasrelacionada']['id'],
	                        $persona['Personasrelacionada']['cliente_id']
	                    ),
	                    array(
	                        'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
	                    ),
	                    __('Esta seguro que quiere eliminar esta persona relacionada?')                                    
	            ); ?>               
	    </td>
	</tr>      
<?php } ?>