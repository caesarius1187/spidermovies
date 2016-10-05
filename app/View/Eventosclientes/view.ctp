<div class="eventosclientes view">
<h2><?php echo __('Eventoscliente'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($eventoscliente['Eventoscliente']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($eventoscliente['Cliente']['id'], array('controller' => 'clientes', 'action' => 'view', $eventoscliente['Cliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Periodo'); ?></dt>
		<dd>
			<?php echo h($eventoscliente['Eventoscliente']['periodo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fchvto'); ?></dt>
		<dd>
			<?php echo h($eventoscliente['Eventoscliente']['fchvto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fchrealizado'); ?></dt>
		<dd>
			<?php echo h($eventoscliente['Eventoscliente']['fchrealizado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($eventoscliente['User']['id'], array('controller' => 'users', 'action' => 'view', $eventoscliente['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($eventoscliente['Eventoscliente']['estado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estadoanterior'); ?></dt>
		<dd>
			<?php echo h($eventoscliente['Eventoscliente']['estadoanterior']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($eventoscliente['Eventoscliente']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($eventoscliente['Eventoscliente']['descripcion']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Eventoscliente'), array('action' => 'edit', $eventoscliente['Eventoscliente']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Eventoscliente'), array('action' => 'delete', $eventoscliente['Eventoscliente']['id']), null, __('Are you sure you want to delete # %s?', $eventoscliente['Eventoscliente']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Eventosclientes'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Eventoscliente'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
