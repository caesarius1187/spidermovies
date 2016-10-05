<div class="tareasimpuestos view">
<h2><?php echo __('Tareasimpuesto'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($tareasimpuesto['Tareasimpuesto']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($tareasimpuesto['Tareasimpuesto']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($tareasimpuesto['Tareasimpuesto']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($tareasimpuesto['Tareasimpuesto']['estado']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Tareasimpuesto'), array('action' => 'edit', $tareasimpuesto['Tareasimpuesto']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Tareasimpuesto'), array('action' => 'delete', $tareasimpuesto['Tareasimpuesto']['id']), null, __('Are you sure you want to delete # %s?', $tareasimpuesto['Tareasimpuesto']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tareasimpuestos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasimpuesto'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tareasximpxestudios'), array('controller' => 'tareasximpxestudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasximpxestudio'), array('controller' => 'tareasximpxestudios', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Tareasximpxestudios'); ?></h3>
	<?php if (!empty($tareasimpuesto['Tareasximpxestudio'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Descripcion'); ?></th>
		<th><?php echo __('Tareasimpuesto Id'); ?></th>
		<th><?php echo __('Estado'); ?></th>
		<th><?php echo __('Estudio Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($tareasimpuesto['Tareasximpxestudio'] as $tareasximpxestudio): ?>
		<tr>
			<td><?php echo $tareasximpxestudio['id']; ?></td>
			<td><?php echo $tareasximpxestudio['descripcion']; ?></td>
			<td><?php echo $tareasximpxestudio['tareasimpuesto_id']; ?></td>
			<td><?php echo $tareasximpxestudio['estado']; ?></td>
			<td><?php echo $tareasximpxestudio['estudio_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'tareasximpxestudios', 'action' => 'view', $tareasximpxestudio['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'tareasximpxestudios', 'action' => 'edit', $tareasximpxestudio['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'tareasximpxestudios', 'action' => 'delete', $tareasximpxestudio['id']), null, __('Are you sure you want to delete # %s?', $tareasximpxestudio['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Tareasximpxestudio'), array('controller' => 'tareasximpxestudios', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
