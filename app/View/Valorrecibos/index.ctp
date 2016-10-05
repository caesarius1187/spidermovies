<div class="valorrecibos index">
	<h2><?php echo __('Valorrecibos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('cctxconcepto_id'); ?></th>
			<th><?php echo $this->Paginator->sort('empleado_id'); ?></th>
			<th><?php echo $this->Paginator->sort('periodo'); ?></th>
			<th><?php echo $this->Paginator->sort('unidademedida'); ?></th>
			<th><?php echo $this->Paginator->sort('valor'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($valorrecibos as $valorrecibo): ?>
	<tr>
		<td><?php echo h($valorrecibo['Valorrecibo']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($valorrecibo['Cctxconcepto']['id'], array('controller' => 'cctxconceptos', 'action' => 'view', $valorrecibo['Cctxconcepto']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($valorrecibo['Empleado']['nombre'], array('controller' => 'empleados', 'action' => 'view', $valorrecibo['Empleado']['id'])); ?>
		</td>
		<td><?php echo h($valorrecibo['Valorrecibo']['periodo']); ?>&nbsp;</td>
		<td><?php echo h($valorrecibo['Valorrecibo']['unidademedida']); ?>&nbsp;</td>
		<td><?php echo h($valorrecibo['Valorrecibo']['valor']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $valorrecibo['Valorrecibo']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $valorrecibo['Valorrecibo']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $valorrecibo['Valorrecibo']['id']), null, __('Are you sure you want to delete # %s?', $valorrecibo['Valorrecibo']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Valorrecibo'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Cctxconceptos'), array('controller' => 'cctxconceptos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cctxconcepto'), array('controller' => 'cctxconceptos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Empleados'), array('controller' => 'empleados', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Empleado'), array('controller' => 'empleados', 'action' => 'add')); ?> </li>
	</ul>
</div>
