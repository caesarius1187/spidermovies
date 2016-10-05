<div class="estudios form">
<?php echo $this->Form->create('Estudio'); ?>
	<fieldset>
		<legend><?php echo __('Edit Estudio'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('nombre');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Estudio.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Estudio.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Estudios'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Grupoclientes'), array('controller' => 'grupoclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Grupocliente'), array('controller' => 'grupoclientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tareasxclientesxestudios'), array('controller' => 'tareasxclientesxestudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasxclientesxestudio'), array('controller' => 'tareasxclientesxestudios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tareasximpxestudios'), array('controller' => 'tareasximpxestudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasximpxestudio'), array('controller' => 'tareasximpxestudios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
