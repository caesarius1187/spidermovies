<div class="archivos index">
	<h2><?php echo __('Archivos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha'); ?></th>
			<th><?php echo $this->Paginator->sort('estado'); ?></th>
			<th><?php echo $this->Paginator->sort('eventosimpuesto_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($archivos as $archivo): ?>
	<tr>
		<td><?php echo h($archivo['Archivo']['id']); ?>&nbsp;</td>
		<td><?php echo h($archivo['Archivo']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($archivo['Archivo']['fecha']); ?>&nbsp;</td>
		<td><?php echo h($archivo['Archivo']['estado']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($archivo['Eventosimpuesto']['id'], array('controller' => 'eventosimpuestos', 'action' => 'view', $archivo['Eventosimpuesto']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $archivo['Archivo']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $archivo['Archivo']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $archivo['Archivo']['id']), null, __('Are you sure you want to delete # %s?', $archivo['Archivo']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Archivo'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Eventosimpuestos'), array('controller' => 'eventosimpuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Eventosimpuesto'), array('controller' => 'eventosimpuestos', 'action' => 'add')); ?> </li>
	</ul>
</div>
