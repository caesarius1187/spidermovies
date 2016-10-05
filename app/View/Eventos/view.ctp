<div class="eventos view">
<h2><?php echo __('Evento'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Plan'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['plan']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cbu'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['cbu']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Periodo'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['periodo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fchvto'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['fchvto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Montovto'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['montovto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fchrealizado'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['fchrealizado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Montorealizado'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['montorealizado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Monc'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['monc']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($evento['User']['id'], array('controller' => 'users', 'action' => 'view', $evento['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Lugarpago'); ?></dt>
		<dd>
			<?php echo $this->Html->link($evento['Lugarpago']['id'], array('controller' => 'lugarpagos', 'action' => 'view', $evento['Lugarpago']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Montorecibido'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['montorecibido']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['estado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estadoanterior'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['estadoanterior']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Publico'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['publico']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Archnombre'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['archnombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Archfecha'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['archfecha']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Informar'); ?></dt>
		<dd>
			<?php echo h($evento['Evento']['informar']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Impcli'); ?></dt>
		<dd>
			<?php echo $this->Html->link($evento['Impcli']['id'], array('controller' => 'impclis', 'action' => 'view', $evento['Impcli']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Evento'), array('action' => 'edit', $evento['Evento']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Evento'), array('action' => 'delete', $evento['Evento']['id']), null, __('Are you sure you want to delete # %s?', $evento['Evento']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Eventos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Evento'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Lugarpagos'), array('controller' => 'lugarpagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lugarpago'), array('controller' => 'lugarpagos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('controller' => 'impclis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Archivos'), array('controller' => 'archivos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Archivo'), array('controller' => 'archivos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Archivos'); ?></h3>
	<?php if (!empty($evento['Archivo'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Nombre'); ?></th>
		<th><?php echo __('Fecha'); ?></th>
		<th><?php echo __('Estado'); ?></th>
		<th><?php echo __('Evento Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($evento['Archivo'] as $archivo): ?>
		<tr>
			<td><?php echo $archivo['id']; ?></td>
			<td><?php echo $archivo['nombre']; ?></td>
			<td><?php echo $archivo['fecha']; ?></td>
			<td><?php echo $archivo['estado']; ?></td>
			<td><?php echo $archivo['evento_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'archivos', 'action' => 'view', $archivo['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'archivos', 'action' => 'edit', $archivo['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'archivos', 'action' => 'delete', $archivo['id']), null, __('Are you sure you want to delete # %s?', $archivo['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Archivo'), array('controller' => 'archivos', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
