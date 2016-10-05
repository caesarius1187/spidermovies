<div class="eventos index">
	<h2><?php echo __('Eventos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('plan'); ?></th>
			<th><?php echo $this->Paginator->sort('cbu'); ?></th>
			<th><?php echo $this->Paginator->sort('periodo'); ?></th>
			<th><?php echo $this->Paginator->sort('fchvto'); ?></th>
			<th><?php echo $this->Paginator->sort('montovto'); ?></th>
			<th><?php echo $this->Paginator->sort('fchrealizado'); ?></th>
			<th><?php echo $this->Paginator->sort('montorealizado'); ?></th>
			<th><?php echo $this->Paginator->sort('monc'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('lugarpago_id'); ?></th>
			<th><?php echo $this->Paginator->sort('montorecibido'); ?></th>
			<th><?php echo $this->Paginator->sort('estado'); ?></th>
			<th><?php echo $this->Paginator->sort('estadoanterior'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('descripcion'); ?></th>
			<th><?php echo $this->Paginator->sort('publico'); ?></th>
			<th><?php echo $this->Paginator->sort('archnombre'); ?></th>
			<th><?php echo $this->Paginator->sort('archfecha'); ?></th>
			<th><?php echo $this->Paginator->sort('informar'); ?></th>
			<th><?php echo $this->Paginator->sort('impcli_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($eventos as $evento): ?>
	<tr>
		<td><?php echo h($evento['Evento']['id']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['plan']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['cbu']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['periodo']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['fchvto']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['montovto']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['fchrealizado']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['montorealizado']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['monc']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($evento['User']['id'], array('controller' => 'users', 'action' => 'view', $evento['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($evento['Lugarpago']['id'], array('controller' => 'lugarpagos', 'action' => 'view', $evento['Lugarpago']['id'])); ?>
		</td>
		<td><?php echo h($evento['Evento']['montorecibido']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['estado']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['estadoanterior']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['descripcion']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['publico']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['archnombre']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['archfecha']); ?>&nbsp;</td>
		<td><?php echo h($evento['Evento']['informar']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($evento['Impcli']['id'], array('controller' => 'impclis', 'action' => 'view', $evento['Impcli']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $evento['Evento']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $evento['Evento']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $evento['Evento']['id']), null, __('Are you sure you want to delete # %s?', $evento['Evento']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Evento'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Lugarpagos'), array('controller' => 'lugarpagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lugarpago'), array('controller' => 'lugarpagos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('controller' => 'impclis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Archivos'), array('controller' => 'archivos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Archivo'), array('controller' => 'archivos', 'action' => 'add')); ?> </li>
	</ul>
</div>
