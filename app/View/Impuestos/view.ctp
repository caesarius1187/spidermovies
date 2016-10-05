<div class="impuestos view">
<h2><?php echo __('Impuesto'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($impuesto['Impuesto']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($impuesto['Impuesto']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($impuesto['Impuesto']['estado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($impuesto['Impuesto']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipo'); ?></dt>
		<dd>
			<?php echo h($impuesto['Impuesto']['tipo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Organismo'); ?></dt>
		<dd>
			<?php echo h($impuesto['Impuesto']['organismo']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Impuesto'), array('action' => 'edit', $impuesto['Impuesto']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Impuesto'), array('action' => 'delete', $impuesto['Impuesto']['id']), null, __('Are you sure you want to delete # %s?', $impuesto['Impuesto']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Impuestos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impuesto'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('controller' => 'impclis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Vencimientos'), array('controller' => 'vencimientos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Vencimiento'), array('controller' => 'vencimientos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Impclis'); ?></h3>
	<?php if (!empty($impuesto['Impcli'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Cliente Id'); ?></th>
		<th><?php echo __('Impuesto Id'); ?></th>
		<th><?php echo __('Descripcion'); ?></th>
		<th><?php echo __('Estado'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($impuesto['Impcli'] as $impcli): ?>
		<tr>
			<td><?php echo $impcli['id']; ?></td>
			<td><?php echo $impcli['cliente_id']; ?></td>
			<td><?php echo $impcli['impuesto_id']; ?></td>
			<td><?php echo $impcli['descripcion']; ?></td>
			<td><?php echo $impcli['estado']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'impclis', 'action' => 'view', $impcli['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'impclis', 'action' => 'edit', $impcli['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'impclis', 'action' => 'delete', $impcli['id']), null, __('Are you sure you want to delete # %s?', $impcli['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Vencimientos'); ?></h3>
	<?php if (!empty($impuesto['Vencimiento'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Periodo'); ?></th>
		<th><?php echo __('Anio'); ?></th>
		<th><?php echo __('Dia'); ?></th>
		<th><?php echo __('Desde'); ?></th>
		<th><?php echo __('Hasta'); ?></th>
		<th><?php echo __('Impuesto Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($impuesto['Vencimiento'] as $vencimiento): ?>
		<tr>
			<td><?php echo $vencimiento['id']; ?></td>
			<td><?php echo $vencimiento['periodo']; ?></td>
			<td><?php echo $vencimiento['anio']; ?></td>
			<td><?php echo $vencimiento['dia']; ?></td>
			<td><?php echo $vencimiento['desde']; ?></td>
			<td><?php echo $vencimiento['hasta']; ?></td>
			<td><?php echo $vencimiento['impuesto_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'vencimientos', 'action' => 'view', $vencimiento['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'vencimientos', 'action' => 'edit', $vencimiento['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'vencimientos', 'action' => 'delete', $vencimiento['id']), null, __('Are you sure you want to delete # %s?', $vencimiento['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Vencimiento'), array('controller' => 'vencimientos', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
