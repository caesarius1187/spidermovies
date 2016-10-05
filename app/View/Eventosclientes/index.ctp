<div class="eventosclientes index">
	<h2><?php echo __('Eventosclientes'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('cliente_id'); ?></th>
			<th><?php echo $this->Paginator->sort('periodo'); ?></th>
			<th><?php echo $this->Paginator->sort('fchvto'); ?></th>
			<th><?php echo $this->Paginator->sort('fchrealizado'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('estado'); ?></th>
			<th><?php echo $this->Paginator->sort('estadoanterior'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('descripcion'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($eventosclientes as $eventoscliente): ?>
	<tr>
		<td><?php echo h($eventoscliente['Eventoscliente']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($eventoscliente['Cliente']['id'], array('controller' => 'clientes', 'action' => 'view', $eventoscliente['Cliente']['id'])); ?>
		</td>
		<td><?php echo h($eventoscliente['Eventoscliente']['periodo']); ?>&nbsp;</td>
		<td><?php echo h($eventoscliente['Eventoscliente']['fchvto']); ?>&nbsp;</td>
		<td><?php echo h($eventoscliente['Eventoscliente']['fchrealizado']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($eventoscliente['User']['id'], array('controller' => 'users', 'action' => 'view', $eventoscliente['User']['id'])); ?>
		</td>
		<td><?php echo h($eventoscliente['Eventoscliente']['estado']); ?>&nbsp;</td>
		<td><?php echo h($eventoscliente['Eventoscliente']['estadoanterior']); ?>&nbsp;</td>
		<td><?php echo h($eventoscliente['Eventoscliente']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($eventoscliente['Eventoscliente']['descripcion']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $eventoscliente['Eventoscliente']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $eventoscliente['Eventoscliente']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $eventoscliente['Eventoscliente']['id']), null, __('Are you sure you want to delete # %s?', $eventoscliente['Eventoscliente']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Eventoscliente'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
