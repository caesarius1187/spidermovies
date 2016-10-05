<div class="bancosysindicatos index">
	<h2><?php echo __('Bancosysindicatos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Id'); ?></th>
			<th><?php echo $this->Paginator->sort('clientes_id'); ?></th>
			<th><?php echo $this->Paginator->sort('razon'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('usuario'); ?></th>
			<th><?php echo $this->Paginator->sort('clave'); ?></th>
			<th><?php echo $this->Paginator->sort('labeldatoadicional'); ?></th>
			<th><?php echo $this->Paginator->sort('datoadicional'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($bancosysindicatos as $bancosysindicato): ?>
	<tr>
		<td><?php echo h($bancosysindicato['Bancosysindicato']['Id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($bancosysindicato['Clientes']['id'], array('controller' => 'clientes', 'action' => 'view', $bancosysindicato['Clientes']['id'])); ?>
		</td>
		<td><?php echo h($bancosysindicato['Bancosysindicato']['razon']); ?>&nbsp;</td>
		<td><?php echo h($bancosysindicato['Bancosysindicato']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($bancosysindicato['Bancosysindicato']['usuario']); ?>&nbsp;</td>
		<td><?php echo h($bancosysindicato['Bancosysindicato']['clave']); ?>&nbsp;</td>
		<td><?php echo h($bancosysindicato['Bancosysindicato']['labeldatoadicional']); ?>&nbsp;</td>
		<td><?php echo h($bancosysindicato['Bancosysindicato']['datoadicional']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $bancosysindicato['Bancosysindicato']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $bancosysindicato['Bancosysindicato']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $bancosysindicato['Bancosysindicato']['id']), null, __('Are you sure you want to delete # %s?', $bancosysindicato['Bancosysindicato']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Bancosysindicato'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clientes'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cbuses'), array('controller' => 'cbuses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cbus'), array('controller' => 'cbuses', 'action' => 'add')); ?> </li>
	</ul>
</div>
