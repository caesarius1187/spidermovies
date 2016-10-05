<div class="estudios view">
<h2><?php echo __('Estudio'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($estudio['Estudio']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($estudio['Estudio']['nombre']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Estudio'), array('action' => 'edit', $estudio['Estudio']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Estudio'), array('action' => 'delete', $estudio['Estudio']['id']), null, __('Are you sure you want to delete # %s?', $estudio['Estudio']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Estudios'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Estudio'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Grupoclientes'), array('controller' => 'grupoclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Grupocliente'), array('controller' => 'grupoclientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tareasxclientesxestudios'), array('controller' => 'tareasxclientesxestudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasxclientesxestudio'), array('controller' => 'tareasxclientesxestudios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tareasximpxestudios'), array('controller' => 'tareasximpxestudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasximpxestudio'), array('controller' => 'tareasximpxestudios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Grupoclientes'); ?></h3>
	<?php if (!empty($estudio['Grupocliente'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Nombre'); ?></th>
		<th><?php echo __('Descripcion'); ?></th>
		<th><?php echo __('Estado'); ?></th>
		<th><?php echo __('Estudio Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($estudio['Grupocliente'] as $grupocliente): ?>
		<tr>
			<td><?php echo $grupocliente['id']; ?></td>
			<td><?php echo $grupocliente['nombre']; ?></td>
			<td><?php echo $grupocliente['descripcion']; ?></td>
			<td><?php echo $grupocliente['estado']; ?></td>
			<td><?php echo $grupocliente['estudio_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'grupoclientes', 'action' => 'view', $grupocliente['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'grupoclientes', 'action' => 'edit', $grupocliente['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'grupoclientes', 'action' => 'delete', $grupocliente['id']), null, __('Are you sure you want to delete # %s?', $grupocliente['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Grupocliente'), array('controller' => 'grupoclientes', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Tareasxclientesxestudios'); ?></h3>
	<?php if (!empty($estudio['Tareasxclientesxestudio'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Descripcion'); ?></th>
		<th><?php echo __('Tareascliente Id'); ?></th>
		<th><?php echo __('Estado'); ?></th>
		<th><?php echo __('Estudio Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($estudio['Tareasxclientesxestudio'] as $tareasxclientesxestudio): ?>
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
<div class="related">
	<h3><?php echo __('Related Tareasximpxestudios'); ?></h3>
	<?php if (!empty($estudio['Tareasximpxestudio'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Descripcion'); ?></th>
		<th><?php echo __('Tareasimpuesto Id'); ?></th>
		<th><?php echo __('Estado'); ?></th>
		<th><?php echo __('Estudio Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($estudio['Tareasximpxestudio'] as $tareasximpxestudio): ?>
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
<div class="related">
	<h3><?php echo __('Related Users'); ?></h3>
	<?php if (!empty($estudio['User'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Dni'); ?></th>
		<th><?php echo __('Telefono'); ?></th>
		<th><?php echo __('Cel'); ?></th>
		<th><?php echo __('Mail'); ?></th>
		<th><?php echo __('Estudio Id'); ?></th>
		<th><?php echo __('Nombre'); ?></th>
		<th><?php echo __('User'); ?></th>
		<th><?php echo __('Password'); ?></th>
		<th><?php echo __('Tipo'); ?></th>
		<th><?php echo __('Estado'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($estudio['User'] as $user): ?>
		<tr>
			<td><?php echo $user['id']; ?></td>
			<td><?php echo $user['dni']; ?></td>
			<td><?php echo $user['telefono']; ?></td>
			<td><?php echo $user['cel']; ?></td>
			<td><?php echo $user['mail']; ?></td>
			<td><?php echo $user['estudio_id']; ?></td>
			<td><?php echo $user['nombre']; ?></td>
			<td><?php echo $user['user']; ?></td>
			<td><?php echo $user['password']; ?></td>
			<td><?php echo $user['tipo']; ?></td>
			<td><?php echo $user['estado']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'users', 'action' => 'view', $user['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'users', 'action' => 'edit', $user['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'users', 'action' => 'delete', $user['id']), null, __('Are you sure you want to delete # %s?', $user['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
