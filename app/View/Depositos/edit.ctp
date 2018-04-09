
<?php
if(!$showTheForm){?>
    <td><?php echo $this->request->data['Deposito']['fecha']; ?></td>
    <td><?php echo $this->request->data['Deposito']['periodo']; ?></td>
    <td><?php echo $this->request->data['Deposito']['descripcion']; ?></td>
    <td><?php echo $this->request->data['Deposito']['monto']; ?></td>
    <td >
        <a href="#"  onclick="loadFormRecibo(<?php echo $this->request->data['Deposito']['id']; ?>)" class="btn_edit"> 
         <?php echo $this->Html->image('edit.png', array('alt' => 'open','class'=>'img_edit'));?>
        </a>
    </td>
<?php 
}else{
    ?>
<td colspan="5" id="tddeposito<?php echo $this->request->data['Deposito']['id'];?>">
    <?php   echo $this->Form->create('Deposito',array('controller'=>'Deposito','action'=>'edit',"id"=>"DepositoEditForm".$this->request->data['Deposito']['id'])); 
            echo $this->Form->input('id',array('type'=>'hidden'));
         ?> 
    <table>    
        <tr>             
            <td><?php echo $this->Form->input('fecha'.$this->request->data['Deposito']['id'], array('div'=>false,
                                'class'=>'datepicker', 
                                'type'=>'text',
                                'label'=>false,  
                                'default'=>$this->request->data['Deposito']['fecha'],        
                                'readonly'=>'readonly')); ?> 
            </td>
            <?php echo $this->Form->input('cliente_id',array('type'=>'hidden')); ?>
            <td><?php echo $this->Form->input('mesdesde', array(
                    'options' => array(
                        '01'=>'Enero', 
                        '02'=>'Febrero', 
                        '03'=>'Marzo', 
                        '04'=>'Abril', 
                        '05'=>'Mayo', 
                        '06'=>'Junio', 
                        '07'=>'Julio', 
                        '08'=>'Agosto', 
                        '09'=>'Septiembre', 
                        '10'=>'Octubre', 
                        '11'=>'Noviembre', 
                        '12'=>'Diciembre', 
                        ),
                    'empty' => 'Elegir Mes',
                    'label'=> false,
                    'value'=> substr($this->request->data['Deposito']['periodo'], 0, 2)

                )); ?>
            <?php 
                echo $this->Form->input('aniodesde', array(
                                                    'options' => array(
                                                        '2014'=>'2014', 
                                                        '2015'=>'2015',
                                                        '2016'=>'2016',
                                                        '2017'=>'2017',
                                                        '2018'=>'2018',   
                                                        '2010'=>'2019',
                                                        '2020'=>'2020',           
                                                        ),
                                                    'empty' => 'Elegir',
                                                    'label'=> false,
                                                    'value'=> substr($this->request->data['Deposito']['periodo'], 3, 4)

                                                    )
                                        );?>
            </td>           
            <td ><?php echo $this->Form->input('descripcion', array('label' => false,)); ?></td>
            <td ><?php echo $this->Form->input('monto',array('type'=>'text','div'=>false,'label'=>false));  ?></td>            
            <td>
                <?php echo $this->Form->end(__('Modificar')); ?>         
            </td>           
        </tr>   
    </table>    
</td>                                       
<?php } ?>          
