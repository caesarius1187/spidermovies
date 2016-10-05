<div class="plandepagos form">
<?php echo $this->Form->create('Plandepago',array('action'=>'edit')); ?>
	<h3><?php echo __('Pagar plan de pago'); ?></h3>
		<table class="tbl_planpago_pagar" cellpadding="0" cellspacing="0">
			<?php echo $this->Form->input('id');?>
		<tr>
			<td><?php echo $this->Form->input('plan',array('disabled'=>'disabled','label'=>'Plan', 'div'=>false));?></td>
			<td><?php echo $this->Form->input('periodo',array('disabled'=>'disabled','label'=>'Periodo', 'div'=>false));?></td>
		</tr>
		<tr>
			<td><?php echo $this->Form->input('item',array('disabled'=>'disabled','label'=>'Item', 'div'=>false));?></td>
			<td><?php echo $this->Form->input('organismo',array('disabled'=>'disabled','label'=>'Organismo', 'div'=>false));?></td>
		</tr>
		<tr>
			<td><?php echo $this->Form->input('montovto',array('disabled'=>'disabled','label'=>'Monto vencimiento'));?></td>
			<td><?php echo $this->Form->input('fchrealizado', array(
                                            'required'=>true, 
                                            'div'=>false,
                                            'class'=>'datepicker', 
                                            'type'=>'text',
                                            'readonly'=>'readonly',
                                            'label' => 'Fecha realizado',

                                            'value'=>date('d-m-Y',strtotime($this->request->data['Plandepago']['fchrealizado']))
                                            )
                                 );?></td>
        </tr>
        <tr>
             <td><?php	echo $this->Form->input('montorealizado', array('label' => 'Monto realizado','div'=>false));?></td>
        </tr>
        <tr>
        	<td><a href="#close"  onclick="" class="btn_cancelar" style="margin-top:14px">Cancelar</a></td>
        	<td><?php echo $this->Form->end(__('Aceptar')); ?></td>
        </tr>
        </table>    
	

</div>
