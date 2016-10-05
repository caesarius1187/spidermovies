<div class="impclis view">
<h2><?php echo __('Impcli'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($impcli['Impcli']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($impcli['Cliente']['id'], array('controller' => 'clientes', 'action' => 'view', $impcli['Cliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Impuesto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($impcli['Impuesto']['id'], array('controller' => 'impuestos', 'action' => 'view', $impcli['Impuesto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($impcli['Impcli']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($impcli['Impcli']['estado']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Impcli'), array('action' => 'edit', $impcli['Impcli']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Impcli'), array('action' => 'delete', $impcli['Impcli']['id']), null, __('Are you sure you want to delete # %s?', $impcli['Impcli']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impuestos'), array('controller' => 'impuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impuesto'), array('controller' => 'impuestos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Eventosimpuestos'), array('controller' => 'eventosimpuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Eventosimpuesto'), array('controller' => 'eventosimpuestos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Eventosimpuestos'); ?></h3>
	<?php if (!empty($impcli['Eventosimpuesto'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Plan'); ?></th>
		<th><?php echo __('Cbu'); ?></th>
		<th><?php echo __('Periodo'); ?></th>
		<th><?php echo __('Fchvto'); ?></th>
		<th><?php echo __('Montovto'); ?></th>
		<th><?php echo __('Fchrealizado'); ?></th>
		<th><?php echo __('Montorealizado'); ?></th>
		<th><?php echo __('Monc'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Lugarpago Id'); ?></th>
		<th><?php echo __('Montorecibido'); ?></th>
		<th><?php echo __('Estado'); ?></th>
		<th><?php echo __('Estadoanterior'); ?></th>
		<th><?php echo __('Nombre'); ?></th>
		<th><?php echo __('Descripcion'); ?></th>
		<th><?php echo __('Publico'); ?></th>
		<th><?php echo __('Archnombre'); ?></th>
		<th><?php echo __('Archfecha'); ?></th>
		<th><?php echo __('Informar'); ?></th>
		<th><?php echo __('Impcli Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($impcli['Eventosimpuesto'] as $eventosimpuesto): ?>
		<tr>
			<td><?php echo $eventosimpuesto['id']; ?></td>
			<td><?php echo $eventosimpuesto['plan']; ?></td>
			<td><?php echo $eventosimpuesto['cbu']; ?></td>
			<td><?php echo $eventosimpuesto['periodo']; ?></td>
			<td><?php echo $eventosimpuesto['fchvto']; ?></td>
			<td><?php echo $eventosimpuesto['montovto']; ?></td>
			<td><?php echo $eventosimpuesto['fchrealizado']; ?></td>
			<td><?php echo $eventosimpuesto['montorealizado']; ?></td>
			<td><?php echo $eventosimpuesto['monc']; ?></td>
			<td><?php echo $eventosimpuesto['user_id']; ?></td>
			<td><?php echo $eventosimpuesto['lugarpago_id']; ?></td>
			<td><?php echo $eventosimpuesto['montorecibido']; ?></td>
			<td><?php echo $eventosimpuesto['estado']; ?></td>
			<td><?php echo $eventosimpuesto['estadoanterior']; ?></td>
			<td><?php echo $eventosimpuesto['nombre']; ?></td>
			<td><?php echo $eventosimpuesto['descripcion']; ?></td>
			<td><?php echo $eventosimpuesto['publico']; ?></td>
			<td><?php echo $eventosimpuesto['archnombre']; ?></td>
			<td><?php echo $eventosimpuesto['archfecha']; ?></td>
			<td><?php echo $eventosimpuesto['informar']; ?></td>
			<td><?php echo $eventosimpuesto['impcli_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'eventosimpuestos', 'action' => 'view', $eventosimpuesto['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'eventosimpuestos', 'action' => 'edit', $eventosimpuesto['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'eventosimpuestos', 'action' => 'delete', $eventosimpuesto['id']), null, __('Are you sure you want to delete # %s?', $eventosimpuesto['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Eventosimpuesto'), array('controller' => 'eventosimpuestos', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
