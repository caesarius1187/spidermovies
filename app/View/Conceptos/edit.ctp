<div class="conceptos form">
<?php echo $this->Form->create('Concepto'); ?>
	<fieldset>
		<legend><?php echo __('Edit Concepto'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('nombre');
		echo $this->Form->input('codigo');
		echo $this->Form->input('calculado');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Concepto.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Concepto.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Conceptos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Cctxconceptos'), array('controller' => 'cctxconceptos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cctxconcepto'), array('controller' => 'cctxconceptos', 'action' => 'add')); ?> </li>
	</ul>
</div>
