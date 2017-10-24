<div class="marcas view">
<h2><?php echo __('Marca'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($marca['Marca']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($marca['Marca']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Indice'); ?></dt>
		<dd>
			<?php echo h($marca['Marca']['indice']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipo'); ?></dt>
		<dd>
			<?php echo h($marca['Marca']['tipo']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Marca'), array('action' => 'edit', $marca['Marca']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Marca'), array('action' => 'delete', $marca['Marca']['id']), array(), __('Are you sure you want to delete # %s?', $marca['Marca']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Marcas'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Marca'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Modelos'), array('controller' => 'modelos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Modelo'), array('controller' => 'modelos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Modelos'); ?></h3>
	<?php if (!empty($marca['Modelo'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Marca Id'); ?></th>
		<th><?php echo __('Nombre'); ?></th>
		<th><?php echo __('Fabrica'); ?></th>
		<th><?php echo __('Marcaindice'); ?></th>
		<th><?php echo __('Modelonumero'); ?></th>
		<th><?php echo __('Tiponombre'); ?></th>
		<th><?php echo __('Tipo'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($marca['Modelo'] as $modelo): ?>
		<tr>
			<td><?php echo $modelo['id']; ?></td>
			<td><?php echo $modelo['marca_id']; ?></td>
			<td><?php echo $modelo['nombre']; ?></td>
			<td><?php echo $modelo['fabrica']; ?></td>
			<td><?php echo $modelo['marcaindice']; ?></td>
			<td><?php echo $modelo['modelonumero']; ?></td>
			<td><?php echo $modelo['tiponombre']; ?></td>
			<td><?php echo $modelo['tipo']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'modelos', 'action' => 'view', $modelo['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'modelos', 'action' => 'edit', $modelo['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'modelos', 'action' => 'delete', $modelo['id']), array(), __('Are you sure you want to delete # %s?', $modelo['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Modelo'), array('controller' => 'modelos', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
