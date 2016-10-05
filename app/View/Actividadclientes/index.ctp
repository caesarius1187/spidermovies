<div class="actividadclientes index">
	<h2><?php echo __('Actividadclientes'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('cliente_id'); ?></th>
			<th><?php echo $this->Paginator->sort('actividade_id'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($actividadclientes as $actividadcliente): ?>
	<tr>
		<td><?php echo h($actividadcliente['Actividadcliente']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($actividadcliente['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $actividadcliente['Cliente']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($actividadcliente['Actividade']['nombre'], array('controller' => 'actividades', 'action' => 'view', $actividadcliente['Actividade']['id'])); ?>
		</td>
		<td><?php echo h($actividadcliente['Actividadcliente']['created']); ?>&nbsp;</td>
		<td><?php echo h($actividadcliente['Actividadcliente']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $actividadcliente['Actividadcliente']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $actividadcliente['Actividadcliente']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $actividadcliente['Actividadcliente']['id']), null, __('Are you sure you want to delete # %s?', $actividadcliente['Actividadcliente']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Actividadcliente'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Actividades'), array('controller' => 'actividades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Actividade'), array('controller' => 'actividades', 'action' => 'add')); ?> </li>
	</ul>
</div>
