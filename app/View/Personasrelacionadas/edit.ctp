<div class="personasrelacionadas" >
<?php echo $this->Form->create('Personasrelacionada',array('controller'=>'Personasrelacionadas','action'=>'edit')); ?>
    <h3><?php echo __('Editar persona relacionada'); ?></h3>
    <?php
        echo $this->Form->input('id',array('type'=>'hidden'));
        echo $this->Form->input('cliente_id',array('type'=>'hidden'));
    ?>
    <table cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td>
                <?php echo $this->Form->input('nombre',array('label'=>'Apellido y Nombre'));?>
            </td>
            <td>
                <?php echo $this->Form->input('documento');?>
            </td>  
            <td>
                <?php echo $this->Form->input('cuit');?>
            </td>                         
             <td><?php echo $this->Form->input('tipo',
                                                array('type'=>'select',
                                                      'class'=>'chosen-select',
                                                      'options'=>array(
                                                        'conyuge'=>'Conyuge',
                                                        'socio'=>'Socio',
                                                        'familiar'=>'Familiar',
                                                        'representante'=>'Representante',
                                                        'presidente'=>'Presidente',
                                                        'gerente'=>'Gerente',
                                                        'titular'=>'Titular',
                                                        'encargado'=>'Encargado',
                                                        'empleado'=>'Empleado',
                                                        )
                                                    )
                                                );?>
            </td>
                                   
        </tr>
        <tr> 
             <td><?php echo $this->Form->input('telefono', array('label'=>'Tel&eacute;fono', 'size' => '11'));?></td>  
             <td><?php echo $this->Form->input('movil', array('label'=>'M&oacute;vil', 'size' => '11'));?></td>
             <td><?php echo $this->Form->input('email',array('label'=>'E-mail'));?></td>
         </tr>
         <tr> 
            <td colspan="2"><?php echo $this->Form->input('observaciones', array('label'=>'Observaciones', 'size' => '35'));?></td>    
         </tr>    
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><a href="#close" onclick="" class="btn_cancelar" style="margin-top:15px">Cancelar</a></td>
            <td align="right">
                <?php echo $this->Form->end(__('Aceptar',array('class' =>'btn_aceptar'))); ?>                          
            </td>
        </tr>
    </table>
</div>

