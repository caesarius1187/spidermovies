<div class="tareasclientes form">
<?php echo $this->Form->create('Tareascliente'); ?>
	<fieldset>
		<legend><?php echo __('Add Tareascliente'); ?></legend>
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

		<li><?php echo $this->Html->link(__('List Tareasclientes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Tareasxclientesxestudios'), array('controller' => 'tareasxclientesxestudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasxclientesxestudio'), array('controller' => 'tareasxclientesxestudios', 'action' => 'add')); ?> </li>
	</ul>
</div>
