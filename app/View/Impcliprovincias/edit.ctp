<div class="impcliprovincias form">
<?php echo $this->Form->create('Impcliprovincia'); ?>
	<fieldset>
		<legend><?php echo __('Edit Impcliprovincia'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('partido_id');
		echo $this->Form->input('ano');
		echo $this->Form->input('coeficiente');
		echo $this->Form->input('ejercicio');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Impcliprovincia.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Impcliprovincia.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Impcliprovincias'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Partidos'), array('controller' => 'partidos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Partido'), array('controller' => 'partidos', 'action' => 'add')); ?> </li>
	</ul>
</div>
