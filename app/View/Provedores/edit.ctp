<?php
$tdClass = "provedore";
if(!$showform) { ?>
    
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Provedore']["dni"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Provedore']["nombre"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Provedore']["cuit"]?></td>    
    <td class="<?php echo $tdClass?>">
        <a href="#"  onclick="loadFormProvedore(<?php echo $this->data['Provedore']["id"]; ?>)" class="button_view">
         <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit'));?>
        </a>
        <?php echo $this->Form->postLink(
            $this->Html->image('ic_delete_black_24dp.png', array(
                'alt' => 'Eliminar',
                'class' => 'img_trash'
            )),
            array(
                'controller' => 'Provedores',
                'action' => 'delete',
                $this->data['Provedore']["id"],
            ),
            array(
                'class'=>'deleteProvedore',
                'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
            ),
            __('Esta seguro que quiere eliminar este provedor?')
        ); ?>
    </td>
<?php }else{ ?>
    <td colspan="20">
        <?php echo $this->Form->create('Provedore',array(
            'controller'=>'Provedores',
            'action'=>'edit',
            'id'=>'ProvedoreFormEdit'.$this->data['Provedore']['id']
            )
        ); 
            echo $this->Form->input('id',array('type'=>'hidden'));
            echo $this->Form->input('cliente_id',array('type'=>'hidden'));
         ?> 
        <table class="tableProvedoreEditForm">
            <tr>             
                
                <td><?php                   
                    echo $this->Form->input('dni',
                        array(
                            'label'=> '',
                            'maxlength'=>'8'
                        )
                    )    
                    ?>
                </td>
                <td><?php                   
                    echo $this->Form->input('nombre',array('label'=>'' ));    
                    ?>
                </td>
                <td><?php                   
                    echo $this->Form->input('cuit',array('label'=>'' ,'maxlength'=>'11'));
                    ?>
                </td>
                <td> 
                    <?php echo $this->Form->end(__('aceptar'),array('div'=>false)); ?>   
                    <a href="#" class="btn_cancelar" onClick="hideFormModProvedore('<?php echo $this->data['Provedore']['id'];?>')" style="float: left;width: 45px;margin: 0;">X</a>
                </td> 
            </tr>   
        </table>        
    </td>     
<?php } ?>                      
                    
                      
        