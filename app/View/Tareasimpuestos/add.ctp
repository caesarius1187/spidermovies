<div class="tareasimpuestos form">
<?php echo $this->Form->create('Tareasimpuesto'); ?>
	<fieldset>
		<legend><?php echo __('Add Tareasimpuesto'); ?></legend>
	<?php
		echo $this->Form->input('nombre');
		echo $this->Form->input('descripcion');
		echo $this->Form->input('estado');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Tareasimpuestos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Tareasximpxestudios'), array('controller' => 'tareasximpxestudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasximpxestudio'), array('controller' => 'tareasximpxestudios', 'action' => 'add')); ?> </li>
	</ul>
</div>
