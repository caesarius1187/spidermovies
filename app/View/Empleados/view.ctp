<div class="empleados view">
<h2><?php echo __('Empleado'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($empleado['Empleado']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($empleado['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $empleado['Cliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($empleado['Empleado']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha Ingreso'); ?></dt>
		<dd>
			<?php echo h($empleado['Empleado']['fecha ingreso']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha Egreso'); ?></dt>
		<dd>
			<?php echo h($empleado['Empleado']['fecha egreso']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Empleado'), array('action' => 'edit', $empleado['Empleado']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Empleado'), array('action' => 'delete', $empleado['Empleado']['id']), null, __('Are you sure you want to delete # %s?', $empleado['Empleado']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Empleados'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Empleado'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Valorrecibos'), array('controller' => 'valorrecibos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Valorrecibo'), array('controller' => 'valorrecibos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Valorrecibos'); ?></h3>
	<?php if (!empty($empleado['Valorrecibo'])): ?>
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
	<?php foreach ($empleado['Valorrecibo'] as $valorrecibo): ?>
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
