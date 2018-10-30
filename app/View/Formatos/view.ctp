<div class="formatos view">
<h2><?php echo __('Formato'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($formato['Formato']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($formato['Formato']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Precio'); ?></dt>
		<dd>
			<?php echo h($formato['Formato']['precio']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Formato'), array('action' => 'edit', $formato['Formato']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Formato'), array('action' => 'delete', $formato['Formato']['id']), array(), __('Are you sure you want to delete # %s?', $formato['Formato']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Formatos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Formato'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Peliculas'), array('controller' => 'peliculas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Pelicula'), array('controller' => 'peliculas', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Peliculas'); ?></h3>
	<?php if (!empty($formato['Pelicula'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Formato Id'); ?></th>
		<th><?php echo __('Nombre'); ?></th>
		<th><?php echo __('Reseña'); ?></th>
		<th><?php echo __('Video'); ?></th>
		<th><?php echo __('Imagen'); ?></th>
		<th><?php echo __('Actores'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($formato['Pelicula'] as $pelicula): ?>
		<tr>
			<td><?php echo $pelicula['id']; ?></td>
			<td><?php echo $pelicula['formato_id']; ?></td>
			<td><?php echo $pelicula['nombre']; ?></td>
			<td><?php echo $pelicula['reseña']; ?></td>
			<td><?php echo $pelicula['video']; ?></td>
			<td><?php echo $pelicula['imagen']; ?></td>
			<td><?php echo $pelicula['actores']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'peliculas', 'action' => 'view', $pelicula['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'peliculas', 'action' => 'edit', $pelicula['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'peliculas', 'action' => 'delete', $pelicula['id']), array(), __('Are you sure you want to delete # %s?', $pelicula['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Pelicula'), array('controller' => 'peliculas', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
