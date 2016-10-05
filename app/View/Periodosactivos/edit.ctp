<div class="periodosactivos form">
<?php echo $this->Form->create('Periodosactivo'); ?>
	<fieldset>
		<legend><?php echo __('Edit Periodosactivo'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('impcli_id');
		echo $this->Form->input('desde');
		echo $this->Form->input('hasta');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Periodosactivo.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Periodosactivo.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Periodosactivos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('controller' => 'impclis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
	</ul>
</div>
