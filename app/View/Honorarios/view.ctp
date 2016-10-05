<div class="honorarios view">
<h2><?php echo __('Honorario'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($honorario['Honorario']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($honorario['Cliente']['id'], array('controller' => 'clientes', 'action' => 'view', $honorario['Cliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Monto'); ?></dt>
		<dd>
			<?php echo h($honorario['Honorario']['monto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha'); ?></dt>
		<dd>
			<?php echo h($honorario['Honorario']['fecha']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($honorario['Honorario']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Periodo'); ?></dt>
		<dd>
			<?php echo h($honorario['Honorario']['periodo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($honorario['Honorario']['estado']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Honorario'), array('action' => 'edit', $honorario['Honorario']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Honorario'), array('action' => 'delete', $honorario['Honorario']['id']), null, __('Are you sure you want to delete # %s?', $honorario['Honorario']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Honorarios'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Honorario'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
	</ul>
</div>
