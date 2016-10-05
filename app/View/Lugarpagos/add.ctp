<div class="lugarpagos form">
<?php echo $this->Form->create('Lugarpago'); ?>
	<fieldset>
		<legend><?php echo __('Add Lugarpago'); ?></legend>
	<?php
		echo $this->Form->input('nombre');
		echo $this->Form->input('domicilio');
		echo $this->Form->input('telefono');
		echo $this->Form->input('descripcion');
		echo $this->Form->input('estado');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Lugarpagos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Eventos'), array('controller' => 'eventos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Evento'), array('controller' => 'eventos', 'action' => 'add')); ?> </li>
	</ul>
</div>
