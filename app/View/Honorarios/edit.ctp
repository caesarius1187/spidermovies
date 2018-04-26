
<?php
if(!$showTheForm){?>
    <td><?php echo $this->request->data['Honorario']['fecha']; ?></td>
    <td><?php echo $this->request->data['Honorario']['periodo']; ?></td>
    <td><?php echo $this->request->data['Honorario']['descripcion']; ?></td>
    <td><?php echo $this->request->data['Honorario']['monto']; ?></td>
    <td><?php echo $this->request->data['Honorario']['estado']; ?></td>
    <td >
       <a href="#"  onclick="loadFormHonorario(<?php echo $this->request->data['Honorario']['id']; ?>)" class="btn_edit"> 
         <?php echo $this->Html->image('edit.png', array('alt' => 'open','class'=>'img_edit'));?>
        </a>
    </td>
<?php 
}else{


    ?>
<td colspan="5" id="tdHonorario<?php echo $this->request->data['Honorario']['id'];?>">
    <?php   echo $this->Form->create('Honorario',array('controller'=>'Honorario','action'=>'edit',"id"=>"HonorarioEditForm".$this->request->data['Honorario']['id'])); 
            echo $this->Form->input('id',array('type'=>'hidden'));
         ?> 
    <table>    
        <tr>             
            <td><?php echo $this->Form->input('fecha'.$this->request->data['Honorario']['id'], array('div'=>false,
                                'class'=>'datepicker', 
                                'type'=>'text',
                                'label'=>false,  
                                'default'=>$this->request->data['Honorario']['fecha'],        
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
                    'value'=> substr($this->request->data['Honorario']['periodo'], 0, 2)

                )); ?>
            <?php 
                echo $this->Form->input('aniodesde', array(
                                                    'options' => array(
                                                        '2013'=>'2013', 
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
                                                    'value'=> substr($this->request->data['Honorario']['periodo'], 3, 4)

                                                    )
                                        );?>
            </td>           
            <td ><?php echo $this->Form->input('descripcion', array('label' => false,)); ?></td>
            <td ><?php echo $this->Form->input('monto',array('type'=>'text','div'=>false,'label'=>false));  ?></td>            
            <td ><?php echo $this->Form->input('estado', array(
                                                    'options' => array(
                                                        'pendiente'=>'pendiente', 
                                                        'no pagado'=>'no pagado'                                                            
                                                        ),
                                                    'label'=> false,
                                                    'value'=> $this->request->data['Honorario']['estado'],
                                                    )
                                        ); ?>
            </td>            
            <td>
                <?php echo $this->Form->end(__('Modificar')); ?>         
            </td>           
        </tr>   
    </table>
    
</td>                       
                    
<?php } ?>          
