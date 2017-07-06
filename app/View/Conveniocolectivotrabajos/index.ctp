<div class="conveniocolectivotrabajos index">
	<h2><?php echo __('Convenio colectivo trabajos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th>id</th>
			<th>impuesto_id</th>
			<th>nombre</th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($conveniocolectivotrabajos as $conveniocolectivotrabajo): ?>
	<tr>
		<td><?php echo h($conveniocolectivotrabajo['Conveniocolectivotrabajo']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($conveniocolectivotrabajo['Impuesto']['nombre'], array('controller' => 'impuestos', 'action' => 'view', $conveniocolectivotrabajo['Impuesto']['id'])); ?>
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
