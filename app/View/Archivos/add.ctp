<div class="archivos form">
<?php echo $this->Form->create('Archivo'); ?>
	<fieldset>
		<legend><?php echo __('Add Archivo'); ?></legend>
	<?php
		echo $this->Form->input('nombre');
		echo $this->Form->input('fecha');
		echo $this->Form->input('estado');
		echo $this->Form->input('eventosimpuesto_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Archivos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Eventosimpuestos'), array('controller' => 'eventosimpuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Eventosimpuesto'), array('controller' => 'eventosimpuestos', 'action' => 'add')); ?> </li>
	</ul>
</div>
