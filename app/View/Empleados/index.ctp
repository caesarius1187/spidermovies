<div class="empleados index">
	<h2><?php echo __('Empleados'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('cliente_id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha ingreso'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha egreso'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($empleados as $empleado): ?>
	<tr>
		<td><?php echo h($empleado['Empleado']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($empleado['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $empleado['Cliente']['id'])); ?>
		</td>
		<td><?php echo h($empleado['Empleado']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($empleado['Empleado']['fecha ingreso']); ?>&nbsp;</td>
		<td><?php echo h($empleado['Empleado']['fecha egreso']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $empleado['Empleado']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $empleado['Empleado']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $empleado['Empleado']['id']), null, __('Are you sure you want to delete # %s?', $empleado['Empleado']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Empleado'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Valorrecibos'), array('controller' => 'valorrecibos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Valorrecibo'), array('controller' => 'valorrecibos', 'action' => 'add')); ?> </li>
	</ul>
</div>
