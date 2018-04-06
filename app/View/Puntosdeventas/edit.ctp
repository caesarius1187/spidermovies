<?php
$tdClass = "tdpuntosdeventa";
if(!$showform) { ?>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Puntosdeventa']["nombre"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Puntosdeventa']["sistemafacturacion"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Domicilio']['calle']?></td> 
    <td class="<?php echo $tdClass?>">
        <a href="#"  onclick="loadFormPuntoDeVenta(<?php echo $this->data['Puntosdeventa']["id"]; ?>)" class="button_view" id="editLinkPuntoVenta<?php echo $this->data['Puntosdeventa']["id"]; ?>"> 
         <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit'));?>
        </a>
    </td>
<?php }else{ ?>
    <td colspan="20" id="tdpuntosdeventa<?php echo $this->data['Puntosdeventa']["id"]?>">
        <?php echo $this->Form->create('Puntosdeventa',array('controller'=>'Puntosdeventa','action'=>'edit','id'=>'PuntodeVentaFormEdit'.$this->data['Puntosdeventa']['id'])); 
            echo $this->Form->input('id',array('type'=>'hidden'));
            echo $this->Form->input('cliente_id',array('type'=>'hidden'));
         ?> 
        <table class="tablePuntodeVentaEditForm">    
            <tr>             
                <td><?php                   
                    echo $this->Form->input('nombre',array('label'=>'','maxlength'=>'5' ));
                    ?>
                </td>
                    <td><?php echo $this->Form->input('sistemafacturacion', array(
                    'label' => '', 
                    'div' => false,
                    'type'=>'select',
                    'options'=>$optionSisFact));  ?></td>   
                <td><?php                   
                    echo $this->Form->input('domicilio_id',array('label'=>'' ));    
                    ?>
                </td>
                <td> 
                    <?php echo $this->Form->end(__('aceptar'),array('div'=>false)); ?>   
                    <a href="#" class="btn_cancelar" onClick="hideFormModPuntoDeVenta('<?php echo $this->data['Puntosdeventa']['id'];?>')" style="float: left;width: 45px;margin: 0;">X</a>
                </td> 
            </tr>   
        </table>        
    </td>     
<?php } ?>                      
                    
                      
        