<div class="lugarpagos view">
<h2><?php echo __('Lugarpago'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($lugarpago['Lugarpago']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($lugarpago['Lugarpago']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Domicilio'); ?></dt>
		<dd>
			<?php echo h($lugarpago['Lugarpago']['domicilio']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Telefono'); ?></dt>
		<dd>
			<?php echo h($lugarpago['Lugarpago']['telefono']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($lugarpago['Lugarpago']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($lugarpago['Lugarpago']['estado']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Lugarpago'), array('action' => 'edit', $lugarpago['Lugarpago']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Lugarpago'), array('action' => 'delete', $lugarpago['Lugarpago']['id']), null, __('Are you sure you want to delete # %s?', $lugarpago['Lugarpago']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Lugarpagos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lugarpago'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Eventos'), array('controller' => 'eventos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Evento'), array('controller' => 'eventos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Eventos'); ?></h3>
	<?php if (!empty($lugarpago['Evento'])): ?>
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
	<?php foreach ($lugarpago['Evento'] as $evento): ?>
		<tr>
			<td><?php echo $evento['id']; ?></td>
			<td><?php echo $evento['plan']; ?></td>
			<td><?php echo $evento['cbu']; ?></td>
			<td><?php echo $evento['periodo']; ?></td>
			<td><?php echo $evento['fchvto']; ?></td>
			<td><?php echo $evento['montovto']; ?></td>
			<td><?php echo $evento['fchrealizado']; ?></td>
			<td><?php echo $evento['montorealizado']; ?></td>
			<td><?php echo $evento['monc']; ?></td>
			<td><?php echo $evento['user_id']; ?></td>
			<td><?php echo $evento['lugarpago_id']; ?></td>
			<td><?php echo $evento['montorecibido']; ?></td>
			<td><?php echo $evento['estado']; ?></td>
			<td><?php echo $evento['estadoanterior']; ?></td>
			<td><?php echo $evento['nombre']; ?></td>
			<td><?php echo $evento['descripcion']; ?></td>
			<td><?php echo $evento['publico']; ?></td>
			<td><?php echo $evento['archnombre']; ?></td>
			<td><?php echo $evento['archfecha']; ?></td>
			<td><?php echo $evento['informar']; ?></td>
			<td><?php echo $evento['impcli_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'eventos', 'action' => 'view', $evento['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'eventos', 'action' => 'edit', $evento['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'eventos', 'action' => 'delete', $evento['id']), null, __('Are you sure you want to delete # %s?', $evento['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Evento'), array('controller' => 'eventos', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
