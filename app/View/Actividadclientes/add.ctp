<?php 
if($error!=0||!isset($error)){
	echo "Error no se a podido guardar la actividad";
}else{?>
 <tr >    
    <td><?php echo h($actividad['Actividade']['descripcion']); ?></td> 
    <td><?php echo h($actividad['Actividade']['nombre']); ?></td>
     $this->request->data('Actividadcliente.baja',);

    <td><?php echo $actividad['Actividadcliente']['descripcion']; ?></td>
    <td><?php
        if($actividad['Actividadcliente']['baja']!=""&&$actividad['Actividadcliente']['baja']!=null)
            echo date('d-m-Y',strtotime($actividad['Actividadcliente']['baja']));
        ?></td>
    <td class="">
        <a href="#"  onclick="loadFormActividadcliente(<?php echo $actividad['Actividadcliente']['id']; ?>,<?php echo $actividad['Actividadcliente']['cliente_id'];?>)" class="button_view">
            <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
        </a>
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