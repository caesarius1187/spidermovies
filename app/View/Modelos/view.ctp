<div class="modelos view">
<h2><?php echo __('Modelo'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($modelo['Modelo']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marca'); ?></dt>
		<dd>
			<?php echo $this->Html->link($modelo['Marca']['nombre'], array('controller' => 'marcas', 'action' => 'view', $modelo['Marca']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($modelo['Modelo']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fabrica'); ?></dt>
		<dd>
			<?php echo h($modelo['Modelo']['fabrica']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marcaindice'); ?></dt>
		<dd>
			<?php echo h($modelo['Modelo']['marcaindice']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modelonumero'); ?></dt>
		<dd>
			<?php echo h($modelo['Modelo']['modelonumero']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tiponombre'); ?></dt>
		<dd>
			<?php echo h($modelo['Modelo']['tiponombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipo'); ?></dt>
		<dd>
			<?php echo h($modelo['Modelo']['tipo']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Modelo'), array('action' => 'edit', $modelo['Modelo']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Modelo'), array('action' => 'delete', $modelo['Modelo']['id']), array(), __('Are you sure you want to delete # %s?', $modelo['Modelo']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Modelos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Modelo'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Marcas'), array('controller' => 'marcas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Marca'), array('controller' => 'marcas', 'action' => 'add')); ?> </li>
	</ul>
</div>
