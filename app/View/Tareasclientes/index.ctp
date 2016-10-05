<div class="tareasclientes index">
	<h2><?php echo __('Tareasclientes'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('descripcion'); ?></th>
			<th><?php echo $this->Paginator->sort('estado'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($tareasclientes as $tareascliente): ?>
	<tr>
		<td><?php echo h($tareascliente['Tareascliente']['id']); ?>&nbsp;</td>
		<td><?php echo h($tareascliente['Tareascliente']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($tareascliente['Tareascliente']['descripcion']); ?>&nbsp;</td>
		<td><?php echo h($tareascliente['Tareascliente']['estado']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $tareascliente['Tareascliente']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $tareascliente['Tareascliente']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $tareascliente['Tareascliente']['id']), null, __('Are you sure you want to delete # %s?', $tareascliente['Tareascliente']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Tareascliente'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Tareasxclientesxestudios'), array('controller' => 'tareasxclientesxestudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasxclientesxestudio'), array('controller' => 'tareasxclientesxestudios', 'action' => 'add')); ?> </li>
	</ul>
</div>
