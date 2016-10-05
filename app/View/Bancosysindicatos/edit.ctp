<div class="bancosysindicatos form">
<?php 

echo $this->Form->create('Bancosysindicato',array('controller'=>'bancosysindicatos','action' => 'edit', )); ?>
  <fieldset>
    <legend><?php echo __('Editar Entidad:'); ?></legend>
     <?php
                echo $this->Form->input('id');
                echo $this->Form->input('cliente_id',array('type'=>'hidden'));
                ?>
  <table>
                    <tr>
                        <td>
                        <?php   
                            echo $this->Form->input('nombre');?>
                         </td>   
                         <td><?php
                            echo $this->Form->input('usuario');?>
                         </td>  
                         <td><?php
                            echo $this->Form->input('clave');?>
                        </td> 
                    
                    </tr>
                    <td>
                          <?php
                            echo $this->Form->input('labeldatoadicional', array('label' => false));?>
                        </td>   
                        <td>
                        <?php       
                            echo $this->Form->input('datoadicional', array('label' => false));?>
                         </td>               
                </table>
  </fieldset>
<?php echo $this->Form->end(__('Guardar')); ?>
</div>