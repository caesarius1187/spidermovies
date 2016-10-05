<div class="cctxconceptos form">
<?php echo $this->Form->create('Cctxconcepto'); ?>
	<fieldset>
		<legend><?php echo __('Add Cctxconcepto'); ?></legend>
	<?php
		echo $this->Form->input('conveniocolectivotrabajo_id');
		echo $this->Form->input('concepto_id');
		echo $this->Form->input('nombre');
		echo $this->Form->input('funcionaaplicar');
		echo $this->Form->input('unidaddemedida');
		echo $this->Form->input('calculado');
		echo $this->Form->input('orden');
		echo $this->Form->input('campopersonalizado');
		echo $this->Form->input('cliente_id');
		echo $this->Form->input('codigopersonalizado');
		echo $this->Form->input('conporcentaje');
		echo $this->Form->input('porcentaje');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Cctxconceptos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Conveniocolectivotrabajos'), array('controller' => 'conveniocolectivotrabajos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Conveniocolectivotrabajo'), array('controller' => 'conveniocolectivotrabajos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Conceptos'), array('controller' => 'conceptos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Concepto'), array('controller' => 'conceptos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Valorrecibos'), array('controller' => 'valorrecibos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Valorrecibo'), array('controller' => 'valorrecibos', 'action' => 'add')); ?> </li>
	</ul>
</div>
