<div class="vencimientos form">
<?php echo $this->Form->create('Vencimiento'); ?>
	<fieldset>
		<legend><?php echo __('Edit Vencimiento'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('periodo');
		echo $this->Form->input('anio');
		echo $this->Form->input('dia');
		echo $this->Form->input('desde');
		echo $this->Form->input('hasta');
		echo $this->Form->input('impuesto_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Vencimiento.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Vencimiento.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Vencimientos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Impuestos'), array('controller' => 'impuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impuesto'), array('controller' => 'impuestos', 'action' => 'add')); ?> </li>
	</ul>
</div>
