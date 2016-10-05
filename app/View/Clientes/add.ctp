<?php echo $this->Html->script('jquery-ui',array('inline'=>false));?>
<?php echo $this->Html->script('clientes/add',array('inline'=>false));?>

<div class="clientes form" style='width:98%'>	
		  <?php 
    echo $this->Form->create('Cliente',array('action'=>'add','id'=>'saveDatosPersonalesForm', 'class' => 'form_popin'));            
    echo $this->Form->input('id',array('type'=>'hidden'));?>

    <table cellspacing="0" cellpadding="0" id="tableDatosPersonalesEdit">
        <tr>
            <td>
                <?php echo $this->Form->input('grupocliente_id',array('label'=>'Grupo de Cliente','style'=>'width:200px')); ?>
            </td>
            <td><?php echo $this->Form->input('tipopersona',array(
                                                'label'=>'Tipo de Persona',
                                                'type'=>'select',
                                                'style'=>'width:200px',
                                                'options'=>array('juridica'=>'Juridica','fisica'=>'Fisica'),
                                                )
                                ); ?>
            </td>
            <td><?php echo $this->Form->input('tipopersonajuridica',array('label'=>'Tipo de Persona Jur&iacute;dica', 'style'=>'width:200px')); ?>
            </td>
        </tr>    
        <tr>
            <td>
                <?php echo $this->Form->input('nombre',array('label'=> 'Apellido y Nombre o Razon Social', 
                                                              'id' => 'clienteEditLabelNombre', 
                                                              'style' => 'width:200px')); 
                ?>
            </td>
            <td><?php echo $this->Form->input('cuitcontribullente',array('label'=>'CUIT', 'style'=>'width:200px')); ?></td>
            <td><?php echo $this->Form->input('dni',array('label'=>'DNI', 'style'=>'width:200px')); ?></td>
        </tr>    
        <tr> 
            <td>
             <?php 
                     echo $this->Form->input('fchcumpleanosconstitucion', array(
                                'class'=>'datepicker', 
                                'type'=>'text',
                                'label'=>'Fecha de Nac. o de Constituci&oacute;n',                                    
                                'style' => 'width:200px',
                                'readonly'=>'readonly')
                     );?>
            </td>
            <td>
                <?php 
                     echo $this->Form->input('fchcorteejerciciofiscal', array(
                                'class'=>'datepicker-day-month', 
                                'type'=>'text',
                                'label'=>'Fecha de Corte de Ejer. Fiscal',                                    
                                'style' => 'width:200px',
                                'readonly'=>'readonly')
                     );?>
            </td>    
            <td>
            </td>
        </tr>   
       
        <tr>    
            <td>
                <?php 
                     echo $this->Form->input('fchiniciocliente', array(
                                'class'=>'datepicker', 
                                'type'=>'text',
                                'label'=>'Cliente desde',    
                                'style' => 'width:200px',                                
                                'readonly'=>'readonly')
                     );?>
            </td>
            <td>
                <?php 
                     echo $this->Form->input('fchfincliente', array(
                                'class'=>'datepicker', 
                                'type'=>'text',
                                'label'=>'Cliente hasta',  
                                'style' => 'width:200px',                                  
                                'readonly'=>'readonly')
                     );?>
            </td>  
            
        </tr>
        <span style="display:none">
        </span>
        <tr id="rowButtonsDetallesPersonales" >                        
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="text-align:center">  
                <?php echo $this->Form->end(__('Aceptar')); ?>                                                  
            </td>                        
        </tr>
    </table>
</div>

