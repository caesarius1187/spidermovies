<div class="conveniocolectivotrabajos index">
	<h2><?php echo __('Conveniocolectivotrabajos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('impcli_id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($conveniocolectivotrabajos as $conveniocolectivotrabajo): ?>
	<tr>
		<td><?php echo h($conveniocolectivotrabajo['Conveniocolectivotrabajo']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($conveniocolectivotrabajo['Impcli']['id'], array('controller' => 'impclis', 'action' => 'view', $conveniocolectivotrabajo['Impcli']['id'])); ?>
		</td>
		<td><?php echo h($conveniocolectivotrabajo['Conveniocolectivotrabajo']['nombre']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $conveniocolectivotrabajo['Conveniocolectivotrabajo']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $conveniocolectivotrabajo['Conveniocolectivotrabajo']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $conveniocolectivotrabajo['Conveniocolectivotrabajo']['id']), null, __('Are you sure you want to delete # %s?', $conveniocolectivotrabajo['Conveniocolectivotrabajo']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Conveniocolectivotrabajo'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('controller' => 'impclis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cctxconceptos'), array('controller' => 'cctxconceptos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cctxconcepto'), array('controller' => 'cctxconceptos', 'action' => 'add')); ?> </li>
	</ul>
</div>
