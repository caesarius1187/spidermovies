<div class="tareasximpxestudios form">
<?php echo $this->Form->create('Tareasximpxestudio'); ?>
	<fieldset>
		<legend><?php echo __('Add Tareasximpxestudio'); ?></legend>
	<?php
		echo $this->Form->input('descripcion');
		echo $this->Form->input('tareasimpuesto_id');
		echo $this->Form->input('estado');
		echo $this->Form->input('estudio_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Tareasximpxestudios'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Tareasimpuestos'), array('controller' => 'tareasimpuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasimpuesto'), array('controller' => 'tareasimpuestos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Estudios'), array('controller' => 'estudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Estudio'), array('controller' => 'estudios', 'action' => 'add')); ?> </li>
	</ul>
</div>
