<div class="notifications form">
<?php echo $this->Form->create('Notification'); ?>
	<fieldset>
		<legend><?php echo __('Add Notification'); ?></legend>
	<?php
		echo $this->Form->input('estudio_id');
		echo $this->Form->input('cliente_id');
		echo $this->Form->input('texto');
		echo $this->Form->input('controller');
		echo $this->Form->input('impcli_id');
		echo $this->Form->input('periodo');
		echo $this->Form->input('action');
		echo $this->Form->input('params');
		echo $this->Form->input('fecha');
		echo $this->Form->input('readed');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Notifications'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Estudios'), array('controller' => 'estudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Estudio'), array('controller' => 'estudios', 'action' => 'add')); ?> </li>
	</ul>
</div>
