<div class="actividades view">
<h2><?php echo __('Actividade'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($actividade['Actividade']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($actividade['Actividade']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($actividade['Actividade']['descripcion']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Actividade'), array('action' => 'edit', $actividade['Actividade']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Actividade'), array('action' => 'delete', $actividade['Actividade']['id']), null, __('Are you sure you want to delete # %s?', $actividade['Actividade']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Actividades'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Actividade'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Actividadclientes'), array('controller' => 'actividadclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Actividadcliente'), array('controller' => 'actividadclientes', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Actividadclientes'); ?></h3>
	<?php if (!empty($actividade['Actividadcliente'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Cliente Id'); ?></th>
		<th><?php echo __('Actividade Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($actividade['Actividadcliente'] as $actividadcliente): ?>
		<tr>
			<td><?php echo $actividadcliente['id']; ?></td>
			<td><?php echo $actividadcliente['cliente_id']; ?></td>
			<td><?php echo $actividadcliente['actividade_id']; ?></td>
			<td><?php echo $actividadcliente['created']; ?></td>
			<td><?php echo $actividadcliente['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'actividadclientes', 'action' => 'view', $actividadcliente['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'actividadclientes', 'action' => 'edit', $actividadcliente['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'actividadclientes', 'action' => 'delete', $actividadcliente['id']), null, __('Are you sure you want to delete # %s?', $actividadcliente['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Actividadcliente'), array('controller' => 'actividadclientes', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
