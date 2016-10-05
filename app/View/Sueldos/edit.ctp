<?php
$tdClass = "tdViewSueldo".$this->data['Sueldo']["id"];
if(!$mostrarForm) { ?>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Sueldo']["fecha"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Sueldo']["monto"]?></td>
    <td class="<?php echo $tdClass?>"> 
      <?php 
      $paramsSueldo=$this->data['Sueldo']["id"];
      echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarSueldo(".$paramsSueldo.")")); 
      echo $this->Html->image('eliminar.png',array('width' => '20', 'height' => '20','onClick'=>"eliminaSueldo(".$paramsSueldo.")"));?>
    </td>
<?php }else{ ?>
    <td colspan="20" id="tdsueldo<?php echo $sueid?>">
        <?php echo $this->Form->create('Sueldo',array('controller'=>'Sueldos','action'=>'edit','id'=>'SueldoFormEdit'.$sueid)); 
            echo $this->Form->input('id',array('type'=>'hidden'));
            echo $this->Form->input('cliente_id',array('type'=>'hidden'));
         ?> 
        <table class="tableSueldoEditForm">    
            <tr>             
                <td>
                    <?php
                    echo $this->Form->input('sueldofecha'.$this->data['Sueldo']["id"], array(
                            'class'=>'datepicker', 
                            'type'=>'text',
                            'label'=>false,                                       
                            'readonly'=>'readonly',
                            'default'=>$this->data['Sueldo']["fecha"])
                     );?>                                
                </td>
                <td>
                    <?php                   
                    echo $this->Form->input('monto',array('label'=>'' ));    
                    ?>
                </td>
                <td> 
                    <?php echo $this->Form->end(__('+'),array('div'=>false,),array('style'=>"display: inline-block")); ?>   
                </td> 
                <td> 
                    <a href="#" class="btn_cancelar" onClick="hideFormModSueldo('<?php echo $sueid;?>')" style="float: left;width: 45px;margin: 0;">X</a>
                </td>
            </tr>   
        </table>        
    </td>     
<?php } ?>                      
                    
                      
        