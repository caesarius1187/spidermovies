<div class="actividadclientes form">
<?php echo $this->Form->create('Actividadcliente'); ?>
	<fieldset>
		<legend><?php echo __('Edit Actividadcliente'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('cliente_id');
		echo $this->Form->input('actividade_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Actividadcliente.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Actividadcliente.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Actividadclientes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Actividades'), array('controller' => 'actividades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Actividade'), array('controller' => 'actividades', 'action' => 'add')); ?> </li>
	</ul>
</div>
