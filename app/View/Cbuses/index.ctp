<div class="cbuses index">
	<h2><?php echo __('Cbuses'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('bancosysindicato_id'); ?></th>
			<th><?php echo $this->Paginator->sort('tipo'); ?></th>
			<th><?php echo $this->Paginator->sort('numero'); ?></th>
			<th><?php echo $this->Paginator->sort('cbu'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($cbuses as $cbus): ?>
	<tr>
		<td><?php echo h($cbus['Cbus']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($cbus['Bancosysindicato'][''], array('controller' => 'bancosysindicatos', 'action' => 'view', $cbus['Bancosysindicato']['id'])); ?>
		</td>
		<td><?php echo h($cbus['Cbus']['tipo']); ?>&nbsp;</td>
		<td><?php echo h($cbus['Cbus']['numero']); ?>&nbsp;</td>
		<td><?php echo h($cbus['Cbus']['cbu']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $cbus['Cbus']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $cbus['Cbus']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $cbus['Cbus']['id']), null, __('Are you sure you want to delete # %s?', $cbus['Cbus']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Cbus'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Bancosysindicatos'), array('controller' => 'bancosysindicatos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Bancosysindicato'), array('controller' => 'bancosysindicatos', 'action' => 'add')); ?> </li>
	</ul>
</div>
