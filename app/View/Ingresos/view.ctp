<div class="depositos view">
<h2><?php echo __('Deposito'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($deposito['Deposito']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Monto'); ?></dt>
		<dd>
			<?php echo h($deposito['Deposito']['monto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha'); ?></dt>
		<dd>
			<?php echo h($deposito['Deposito']['fecha']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($deposito['Deposito']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($deposito['Cliente']['id'], array('controller' => 'clientes', 'action' => 'view', $deposito['Cliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Periodo'); ?></dt>
		<dd>
			<?php echo h($deposito['Deposito']['periodo']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Deposito'), array('action' => 'edit', $deposito['Deposito']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Deposito'), array('action' => 'delete', $deposito['Deposito']['id']), null, __('Are you sure you want to delete # %s?', $deposito['Deposito']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Depositos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Deposito'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
	</ul>
</div>
