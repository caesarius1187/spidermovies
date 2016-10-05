<div class="conveniocolectivotrabajos view">
<h2><?php echo __('Conveniocolectivotrabajo'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($conveniocolectivotrabajo['Conveniocolectivotrabajo']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Impcli'); ?></dt>
		<dd>
			<?php echo $this->Html->link($conveniocolectivotrabajo['Impcli']['id'], array('controller' => 'impclis', 'action' => 'view', $conveniocolectivotrabajo['Impcli']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($conveniocolectivotrabajo['Conveniocolectivotrabajo']['nombre']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Conveniocolectivotrabajo'), array('action' => 'edit', $conveniocolectivotrabajo['Conveniocolectivotrabajo']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Conveniocolectivotrabajo'), array('action' => 'delete', $conveniocolectivotrabajo['Conveniocolectivotrabajo']['id']), null, __('Are you sure you want to delete # %s?', $conveniocolectivotrabajo['Conveniocolectivotrabajo']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Conveniocolectivotrabajos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Conveniocolectivotrabajo'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('controller' => 'impclis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cctxconceptos'), array('controller' => 'cctxconceptos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cctxconcepto'), array('controller' => 'cctxconceptos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Cctxconceptos'); ?></h3>
	<?php if (!empty($conveniocolectivotrabajo['Cctxconcepto'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Conveniocolectivotrabajo Id'); ?></th>
		<th><?php echo __('Concepto Id'); ?></th>
		<th><?php echo __('Nombre'); ?></th>
		<th><?php echo __('FuncionAAplicar'); ?></th>
		<th><?php echo __('Unidaddemedida'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($conveniocolectivotrabajo['Cctxconcepto'] as $cctxconcepto): ?>
		<tr>
			<td><?php echo $cctxconcepto['id']; ?></td>
			<td><?php echo $cctxconcepto['conveniocolectivotrabajo_id']; ?></td>
			<td><?php echo $cctxconcepto['concepto_id']; ?></td>
			<td><?php echo $cctxconcepto['nombre']; ?></td>
			<td><?php echo $cctxconcepto['funcionAAplicar']; ?></td>
			<td><?php echo $cctxconcepto['unidaddemedida']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'cctxconceptos', 'action' => 'view', $cctxconcepto['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'cctxconceptos', 'action' => 'edit', $cctxconcepto['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'cctxconceptos', 'action' => 'delete', $cctxconcepto['id']), null, __('Are you sure you want to delete # %s?', $cctxconcepto['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Cctxconcepto'), array('controller' => 'cctxconceptos', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
