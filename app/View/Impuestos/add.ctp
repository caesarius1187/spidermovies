<div class="impuestos form">
<?php echo $this->Form->create('Impuesto'); ?>
	<fieldset>
		<legend><?php echo __('Add Impuesto'); ?></legend>
	<?php
		echo $this->Form->input('nombre');
		echo $this->Form->input('estado');
		echo $this->Form->input('descripcion');
		echo $this->Form->input('tipo');
		echo $this->Form->input('organismo');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Impuestos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('controller' => 'impclis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Vencimientos'), array('controller' => 'vencimientos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Vencimiento'), array('controller' => 'vencimientos', 'action' => 'add')); ?> </li>
	</ul>
</div>
