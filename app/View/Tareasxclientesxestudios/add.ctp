<div class="tareasxclientesxestudios form">
<?php echo $this->Form->create('Tareasxclientesxestudio'); ?>
	<fieldset>
		<legend><?php echo __('Add Tareasxclientesxestudio'); ?></legend>
	<?php
		echo $this->Form->input('descripcion');
		echo $this->Form->input('tareascliente_id');
		echo $this->Form->input('estado');
		echo $this->Form->input('estudio_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Tareasxclientesxestudios'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Tareasclientes'), array('controller' => 'tareasclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareascliente'), array('controller' => 'tareasclientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Estudios'), array('controller' => 'estudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Estudio'), array('controller' => 'estudios', 'action' => 'add')); ?> </li>
	</ul>
</div>
