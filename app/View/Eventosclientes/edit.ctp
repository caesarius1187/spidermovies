<div class="eventosclientes form">
<?php echo $this->Form->create('Eventoscliente'); ?>
	<fieldset>
		<legend><?php echo __('Edit Eventoscliente'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('cliente_id');
		echo $this->Form->input('periodo');
		echo $this->Form->input('fchvto');
		echo $this->Form->input('fchrealizado');
		echo $this->Form->input('user_id');
		echo $this->Form->input('estado');
		echo $this->Form->input('estadoanterior');
		echo $this->Form->input('nombre');
		echo $this->Form->input('descripcion');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Eventoscliente.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Eventoscliente.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Eventosclientes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
