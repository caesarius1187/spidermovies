<div class="cctxconceptos index">
	<h2><?php echo __('Cctxconceptos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('conveniocolectivotrabajo_id'); ?></th>
			<th><?php echo $this->Paginator->sort('concepto_id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('funcionaaplicar'); ?></th>
			<th><?php echo $this->Paginator->sort('unidaddemedida'); ?></th>
			<th><?php echo $this->Paginator->sort('calculado'); ?></th>
			<th><?php echo $this->Paginator->sort('orden'); ?></th>
			<th><?php echo $this->Paginator->sort('campopersonalizado'); ?></th>
			<th><?php echo $this->Paginator->sort('cliente_id'); ?></th>
			<th><?php echo $this->Paginator->sort('codigopersonalizado'); ?></th>
			<th><?php echo $this->Paginator->sort('conporcentaje'); ?></th>
			<th><?php echo $this->Paginator->sort('porcentaje'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($cctxconceptos as $cctxconcepto): ?>
	<tr>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($cctxconcepto['Conveniocolectivotrabajo']['id'], array('controller' => 'conveniocolectivotrabajos', 'action' => 'view', $cctxconcepto['Conveniocolectivotrabajo']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($cctxconcepto['Concepto']['id'], array('controller' => 'conceptos', 'action' => 'view', $cctxconcepto['Concepto']['id'])); ?>
		</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['funcionaaplicar']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['unidaddemedida']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['calculado']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['orden']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['campopersonalizado']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['cliente_id']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['codigopersonalizado']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['conporcentaje']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['porcentaje']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $cctxconcepto['Cctxconcepto']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $cctxconcepto['Cctxconcepto']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $cctxconcepto['Cctxconcepto']['id']), null, __('Are you sure you want to delete # %s?', $cctxconcepto['Cctxconcepto']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Cctxconcepto'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Conveniocolectivotrabajos'), array('controller' => 'conveniocolectivotrabajos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Conveniocolectivotrabajo'), array('controller' => 'conveniocolectivotrabajos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Conceptos'), array('controller' => 'conceptos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Concepto'), array('controller' => 'conceptos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Valorrecibos'), array('controller' => 'valorrecibos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Valorrecibo'), array('controller' => 'valorrecibos', 'action' => 'add')); ?> </li>
	</ul>
</div>
