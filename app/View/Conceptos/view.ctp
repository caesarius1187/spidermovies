<div class="conceptos view">
<h2><?php echo __('Concepto'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($concepto['Concepto']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($concepto['Concepto']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Codigo'); ?></dt>
		<dd>
			<?php echo h($concepto['Concepto']['codigo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Calculado'); ?></dt>
		<dd>
			<?php echo h($concepto['Concepto']['calculado']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Concepto'), array('action' => 'edit', $concepto['Concepto']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Concepto'), array('action' => 'delete', $concepto['Concepto']['id']), null, __('Are you sure you want to delete # %s?', $concepto['Concepto']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Conceptos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Concepto'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cctxconceptos'), array('controller' => 'cctxconceptos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cctxconcepto'), array('controller' => 'cctxconceptos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Cctxconceptos'); ?></h3>
	<?php if (!empty($concepto['Cctxconcepto'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Conveniocolectivotrabajo Id'); ?></th>
		<th><?php echo __('Concepto Id'); ?></th>
		<th><?php echo __('Nombre'); ?></th>
		<th><?php echo __('Funcionaaplicar'); ?></th>
		<th><?php echo __('Unidaddemedida'); ?></th>
		<th><?php echo __('Calculado'); ?></th>
		<th><?php echo __('Orden'); ?></th>
		<th><?php echo __('Campopersonalizado'); ?></th>
		<th><?php echo __('Cliente Id'); ?></th>
		<th><?php echo __('Codigopersonalizado'); ?></th>
		<th><?php echo __('Conporcentaje'); ?></th>
		<th><?php echo __('Porcentaje'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($concepto['Cctxconcepto'] as $cctxconcepto): ?>
		<tr>
			<td><?php echo $cctxconcepto['id']; ?></td>
			<td><?php echo $cctxconcepto['conveniocolectivotrabajo_id']; ?></td>
			<td><?php echo $cctxconcepto['concepto_id']; ?></td>
			<td><?php echo $cctxconcepto['nombre']; ?></td>
			<td><?php echo $cctxconcepto['funcionaaplicar']; ?></td>
			<td><?php echo $cctxconcepto['unidaddemedida']; ?></td>
			<td><?php echo $cctxconcepto['calculado']; ?></td>
			<td><?php echo $cctxconcepto['orden']; ?></td>
			<td><?php echo $cctxconcepto['campopersonalizado']; ?></td>
			<td><?php echo $cctxconcepto['cliente_id']; ?></td>
			<td><?php echo $cctxconcepto['codigopersonalizado']; ?></td>
			<td><?php echo $cctxconcepto['conporcentaje']; ?></td>
			<td><?php echo $cctxconcepto['porcentaje']; ?></td>
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
