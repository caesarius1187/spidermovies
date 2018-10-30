<div class="formatos index">
	<h2><?php echo __('Formatos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('precio'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($formatos as $formato): ?>
	<tr>
		<td><?php echo h($formato['Formato']['id']); ?>&nbsp;</td>
		<td><?php echo h($formato['Formato']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($formato['Formato']['precio']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $formato['Formato']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $formato['Formato']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $formato['Formato']['id']), array(), __('Are you sure you want to delete # %s?', $formato['Formato']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
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
		<li><?php echo $this->Html->link(__('New Formato'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Peliculas'), array('controller' => 'peliculas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Pelicula'), array('controller' => 'peliculas', 'action' => 'add')); ?> </li>
	</ul>
</div>
