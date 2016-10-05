<div class="depositos form">
<?php echo $this->Form->create('Deposito'); ?>
	<fieldset>
		<legend><?php echo __('Add Deposito'); ?></legend>
	<?php
		echo $this->Form->input('monto');
		echo $this->Form->input('fecha');
		echo $this->Form->input('descripcion');
		echo $this->Form->input('cliente_id');
		echo $this->Form->input('periodo');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Depositos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
	</ul>
</div>
