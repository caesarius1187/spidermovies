<div class="contactos form">
<?php echo $this->Form->create('Contacto'); ?>
	<fieldset>
		<legend><?php echo __('Edit Contacto'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('cliente_id');
		echo $this->Form->input('razon');
		echo $this->Form->input('valor');
		echo $this->Form->input('valor2');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Contacto.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Contacto.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Contactos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
	</ul>
</div>
