<div class="lugarpagos index">
	<h2><?php echo __('Lugarpagos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('domicilio'); ?></th>
			<th><?php echo $this->Paginator->sort('telefono'); ?></th>
			<th><?php echo $this->Paginator->sort('descripcion'); ?></th>
			<th><?php echo $this->Paginator->sort('estado'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($lugarpagos as $lugarpago): ?>
	<tr>
		<td><?php echo h($lugarpago['Lugarpago']['id']); ?>&nbsp;</td>
		<td><?php echo h($lugarpago['Lugarpago']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($lugarpago['Lugarpago']['domicilio']); ?>&nbsp;</td>
		<td><?php echo h($lugarpago['Lugarpago']['telefono']); ?>&nbsp;</td>
		<td><?php echo h($lugarpago['Lugarpago']['descripcion']); ?>&nbsp;</td>
		<td><?php echo h($lugarpago['Lugarpago']['estado']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $lugarpago['Lugarpago']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $lugarpago['Lugarpago']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $lugarpago['Lugarpago']['id']), null, __('Are you sure you want to delete # %s?', $lugarpago['Lugarpago']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Lugarpago'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Eventos'), array('controller' => 'eventos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Evento'), array('controller' => 'eventos', 'action' => 'add')); ?> </li>
	</ul>
</div>
