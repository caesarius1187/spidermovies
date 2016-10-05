<div class="tareasimpuestos index">
	<h2><?php echo __('Tareasimpuestos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('descripcion'); ?></th>
			<th><?php echo $this->Paginator->sort('estado'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($tareasimpuestos as $tareasimpuesto): ?>
	<tr>
		<td><?php echo h($tareasimpuesto['Tareasimpuesto']['id']); ?>&nbsp;</td>
		<td><?php echo h($tareasimpuesto['Tareasimpuesto']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($tareasimpuesto['Tareasimpuesto']['descripcion']); ?>&nbsp;</td>
		<td><?php echo h($tareasimpuesto['Tareasimpuesto']['estado']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $tareasimpuesto['Tareasimpuesto']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $tareasimpuesto['Tareasimpuesto']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $tareasimpuesto['Tareasimpuesto']['id']), null, __('Are you sure you want to delete # %s?', $tareasimpuesto['Tareasimpuesto']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Tareasimpuesto'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Tareasximpxestudios'), array('controller' => 'tareasximpxestudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasximpxestudio'), array('controller' => 'tareasximpxestudios', 'action' => 'add')); ?> </li>
	</ul>
</div>
