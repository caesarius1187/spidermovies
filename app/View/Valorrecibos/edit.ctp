<div class="valorrecibos form">
<?php echo $this->Form->create('Valorrecibo'); ?>
	<fieldset>
		<legend><?php echo __('Edit Valorrecibo'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('cctxconcepto_id');
		echo $this->Form->input('empleado_id');
		echo $this->Form->input('periodo');
		echo $this->Form->input('unidademedida');
		echo $this->Form->input('valor');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Valorrecibo.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Valorrecibo.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Valorrecibos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Cctxconceptos'), array('controller' => 'cctxconceptos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cctxconcepto'), array('controller' => 'cctxconceptos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Empleados'), array('controller' => 'empleados', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Empleado'), array('controller' => 'empleados', 'action' => 'add')); ?> </li>
	</ul>
</div>
