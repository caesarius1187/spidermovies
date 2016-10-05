<div class="bancosysindicatos view">
<h2><?php echo __('Bancosysindicato'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($bancosysindicato['Bancosysindicato']['Id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Clientes'); ?></dt>
		<dd>
			<?php echo $this->Html->link($bancosysindicato['Clientes']['id'], array('controller' => 'clientes', 'action' => 'view', $bancosysindicato['Clientes']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Razon'); ?></dt>
		<dd>
			<?php echo h($bancosysindicato['Bancosysindicato']['razon']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($bancosysindicato['Bancosysindicato']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo h($bancosysindicato['Bancosysindicato']['usuario']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Clave'); ?></dt>
		<dd>
			<?php echo h($bancosysindicato['Bancosysindicato']['clave']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Labeldatoadicional'); ?></dt>
		<dd>
			<?php echo h($bancosysindicato['Bancosysindicato']['labeldatoadicional']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Datoadicional'); ?></dt>
		<dd>
			<?php echo h($bancosysindicato['Bancosysindicato']['datoadicional']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Bancosysindicato'), array('action' => 'edit', $bancosysindicato['Bancosysindicato']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Bancosysindicato'), array('action' => 'delete', $bancosysindicato['Bancosysindicato']['id']), null, __('Are you sure you want to delete # %s?', $bancosysindicato['Bancosysindicato']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Bancosysindicatos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Bancosysindicato'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clientes'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cbuses'), array('controller' => 'cbuses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cbus'), array('controller' => 'cbuses', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Cbuses'); ?></h3>
	<?php if (!empty($bancosysindicato['Cbus'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Bancosysindicato Id'); ?></th>
		<th><?php echo __('Tipo'); ?></th>
		<th><?php echo __('Numero'); ?></th>
		<th><?php echo __('Cbu'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($bancosysindicato['Cbus'] as $cbus): ?>
		<tr>
			<td><?php echo $cbus['id']; ?></td>
			<td><?php echo $cbus['bancosysindicato_id']; ?></td>
			<td><?php echo $cbus['tipo']; ?></td>
			<td><?php echo $cbus['numero']; ?></td>
			<td><?php echo $cbus['cbu']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'cbuses', 'action' => 'view', $cbus['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'cbuses', 'action' => 'edit', $cbus['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'cbuses', 'action' => 'delete', $cbus['id']), null, __('Are you sure you want to delete # %s?', $cbus['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Cbus'), array('controller' => 'cbuses', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
