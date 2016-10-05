<div class="cctxconceptos view">
<h2><?php echo __('Cctxconcepto'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($cctxconcepto['Cctxconcepto']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Conveniocolectivotrabajo'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cctxconcepto['Conveniocolectivotrabajo']['id'], array('controller' => 'conveniocolectivotrabajos', 'action' => 'view', $cctxconcepto['Conveniocolectivotrabajo']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Concepto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cctxconcepto['Concepto']['id'], array('controller' => 'conceptos', 'action' => 'view', $cctxconcepto['Concepto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($cctxconcepto['Cctxconcepto']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Funcionaaplicar'); ?></dt>
		<dd>
			<?php echo h($cctxconcepto['Cctxconcepto']['funcionaaplicar']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Unidaddemedida'); ?></dt>
		<dd>
			<?php echo h($cctxconcepto['Cctxconcepto']['unidaddemedida']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Calculado'); ?></dt>
		<dd>
			<?php echo h($cctxconcepto['Cctxconcepto']['calculado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Orden'); ?></dt>
		<dd>
			<?php echo h($cctxconcepto['Cctxconcepto']['orden']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Campopersonalizado'); ?></dt>
		<dd>
			<?php echo h($cctxconcepto['Cctxconcepto']['campopersonalizado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente Id'); ?></dt>
		<dd>
			<?php echo h($cctxconcepto['Cctxconcepto']['cliente_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Codigopersonalizado'); ?></dt>
		<dd>
			<?php echo h($cctxconcepto['Cctxconcepto']['codigopersonalizado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Conporcentaje'); ?></dt>
		<dd>
			<?php echo h($cctxconcepto['Cctxconcepto']['conporcentaje']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Porcentaje'); ?></dt>
		<dd>
			<?php echo h($cctxconcepto['Cctxconcepto']['porcentaje']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cctxconcepto'), array('action' => 'edit', $cctxconcepto['Cctxconcepto']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Cctxconcepto'), array('action' => 'delete', $cctxconcepto['Cctxconcepto']['id']), null, __('Are you sure you want to delete # %s?', $cctxconcepto['Cctxconcepto']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Cctxconceptos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cctxconcepto'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Conveniocolectivotrabajos'), array('controller' => 'conveniocolectivotrabajos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Conveniocolectivotrabajo'), array('controller' => 'conveniocolectivotrabajos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Conceptos'), array('controller' => 'conceptos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Concepto'), array('controller' => 'conceptos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Valorrecibos'), array('controller' => 'valorrecibos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Valorrecibo'), array('controller' => 'valorrecibos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Valorrecibos'); ?></h3>
	<?php if (!empty($cctxconcepto['Valorrecibo'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Cctxconcepto Id'); ?></th>
		<th><?php echo __('Empleado Id'); ?></th>
		<th><?php echo __('Periodo'); ?></th>
		<th><?php echo __('Unidademedida'); ?></th>
		<th><?php echo __('Valor'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($cctxconcepto['Valorrecibo'] as $valorrecibo): ?>
		<tr>
			<td><?php echo $valorrecibo['id']; ?></td>
			<td><?php echo $valorrecibo['cctxconcepto_id']; ?></td>
			<td><?php echo $valorrecibo['empleado_id']; ?></td>
			<td><?php echo $valorrecibo['periodo']; ?></td>
			<td><?php echo $valorrecibo['unidademedida']; ?></td>
			<td><?php echo $valorrecibo['valor']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'valorrecibos', 'action' => 'view', $valorrecibo['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'valorrecibos', 'action' => 'edit', $valorrecibo['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'valorrecibos', 'action' => 'delete', $valorrecibo['id']), null, __('Are you sure you want to delete # %s?', $valorrecibo['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Valorrecibo'), array('controller' => 'valorrecibos', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
