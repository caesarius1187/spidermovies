<div class="impuestos index">
	<h2><?php echo __('Impuestos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('estado'); ?></th>
			<th><?php echo $this->Paginator->sort('descripcion'); ?></th>
			<th><?php echo $this->Paginator->sort('tipo'); ?></th>
			<th><?php echo $this->Paginator->sort('organismo'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($impuestos as $impuesto): ?>
	<tr>
		<td><?php echo h($impuesto['Impuesto']['id']); ?>&nbsp;</td>
		<td><?php echo h($impuesto['Impuesto']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($impuesto['Impuesto']['estado']); ?>&nbsp;</td>
		<td><?php echo h($impuesto['Impuesto']['descripcion']); ?>&nbsp;</td>
		<td><?php echo h($impuesto['Impuesto']['tipo']); ?>&nbsp;</td>
		<td><?php echo h($impuesto['Impuesto']['organismo']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $impuesto['Impuesto']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $impuesto['Impuesto']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $impuesto['Impuesto']['id']), null, __('Are you sure you want to delete # %s?', $impuesto['Impuesto']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Impuesto'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('controller' => 'impclis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Vencimientos'), array('controller' => 'vencimientos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Vencimiento'), array('controller' => 'vencimientos', 'action' => 'add')); ?> </li>
	</ul>
</div>
