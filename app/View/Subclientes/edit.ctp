<?php
$tdClass = "subcliente";
if(!$showform) { ?>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Subcliente']["cuit"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Subcliente']["dni"]?></td>    
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Subcliente']["nombre"]?>-
    <td class="<?php echo $tdClass?>">
        <a href="#"  onclick="loadFormSubcliente(<?php echo $this->data['Subcliente']["id"]; ?>)" class="button_view"> 
         <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
        </a>
        <?php echo $this->Form->postLink(
            $this->Html->image('ic_delete_black_24dp.png', array(
                'alt' => 'Eliminar',
            )),
            array(
                'controller' => 'Subclientes',
                'action' => 'delete',
                $this->data['Subcliente']["id"],
            ),
            array(
                'class'=>'deleteSubcliente',
                'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
            ),
            __('Esta seguro que quiere eliminar este subcliente?')
        ); ?>
    </td>
<?php }else{ ?>
    <td colspan="20">
        <?php echo $this->Form->create('Subcliente',array(
            'controller'=>'Subclientes',
            'action'=>'edit',
            'id'=>'SubclienteFormEdit'.$this->data['Subcliente']['id']
            )
        ); 
            echo $this->Form->input('id',array('type'=>'hidden'));
            echo $this->Form->input('cliente_id',array('type'=>'hidden'));
         ?> 
        <table class="tableSubclienteEditForm">    
            <tr>             
                <td><?php                   
                    echo $this->Form->input('cuit',array('label'=>'' ,'maxlength'=>'11'));
                    ?>
                </td>
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
                <td>
                    <?php echo $this->Form->end(__('aceptar'),array('div'=>false)); ?>   
                    <a href="#" class="btn_cancelar" onClick="hideFormModSubcliente('<?php echo $this->data['Subcliente']['id'];?>')" style="float: left;width: 45px;margin: 0;">X</a>
                </td> 
            </tr>   
        </table>        
    </td>     
<?php } ?>                      
                    
                      
        