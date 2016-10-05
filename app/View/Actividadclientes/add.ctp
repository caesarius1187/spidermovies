<?php 
if($error!=0||!isset($error)){
	echo "Error no se a podido guardar la actividad";
}else{?>
 <tr >    
    <td><?php echo h($actividad['Actividade']['descripcion']); ?></td> 
    <td><?php echo h($actividad['Actividade']['nombre']); ?></td> 
    <td class="">
       <?php echo $this->Form->postLink(
                                         $this->Html->image('ic_delete_black_24dp.png', array(
                                            'alt' => 'Eliminar',
                                        )),
                                        array(
                                            'controller' => 'Actividadclientes',
                                            'action' => 'delete',
                                            $actividad['Actividadcliente']['id'],
                                            $actividad['Actividadcliente']['cliente_id']
                                        ),
                                        array(
                                            'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
                                        ),
                                        __('Esta seguro que quiere eliminar esta actividad?')                                    
                                ); ?>              
    </td>
</tr> 
<?php } ?>     