<div class="tareasclientes view">
<h2><?php echo __('Tareascliente'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($tareascliente['Tareascliente']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($tareascliente['Tareascliente']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($tareascliente['Tareascliente']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($tareascliente['Tareascliente']['estado']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Tareascliente'), array('action' => 'edit', $tareascliente['Tareascliente']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Tareascliente'), array('action' => 'delete', $tareascliente['Tareascliente']['id']), null, __('Are you sure you want to delete # %s?', $tareascliente['Tareascliente']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tareasclientes'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareascliente'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tareasxclientesxestudios'), array('controller' => 'tareasxclientesxestudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasxclientesxestudio'), array('controller' => 'tareasxclientesxestudios', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Tareasxclientesxestudios'); ?></h3>
	<?php if (!empty($tareascliente['Tareasxclientesxestudio'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Descripcion'); ?></th>
		<th><?php echo __('Tareascliente Id'); ?></th>
		<th><?php echo __('Estado'); ?></th>
		<th><?php echo __('Estudio Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($tareascliente['Tareasxclientesxestudio'] as $tareasxclientesxestudio): ?>
		<tr>
			<td><?php echo $tareasxclientesxestudio['id']; ?></td>
			<td><?php echo $tareasxclientesxestudio['descripcion']; ?></td>
			<td><?php echo $tareasxclientesxestudio['tareascliente_id']; ?></td>
			<td><?php echo $tareasxclientesxestudio['estado']; ?></td>
			<td><?php echo $tareasxclientesxestudio['estudio_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'tareasxclientesxestudios', 'action' => 'view', $tareasxclientesxestudio['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'tareasxclientesxestudios', 'action' => 'edit', $tareasxclientesxestudio['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'tareasxclientesxestudios', 'action' => 'delete', $tareasxclientesxestudio['id']), null, __('Are you sure you want to delete # %s?', $tareasxclientesxestudio['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Tareasxclientesxestudio'), array('controller' => 'tareasxclientesxestudios', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
