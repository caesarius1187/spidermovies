<div class="actividadclientes view">
<h2><?php echo __('Actividadcliente'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($actividadcliente['Actividadcliente']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($actividadcliente['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $actividadcliente['Cliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Actividade'); ?></dt>
		<dd>
			<?php echo $this->Html->link($actividadcliente['Actividade']['nombre'], array('controller' => 'actividades', 'action' => 'view', $actividadcliente['Actividade']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($actividadcliente['Actividadcliente']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($actividadcliente['Actividadcliente']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Actividadcliente'), array('action' => 'edit', $actividadcliente['Actividadcliente']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Actividadcliente'), array('action' => 'delete', $actividadcliente['Actividadcliente']['id']), null, __('Are you sure you want to delete # %s?', $actividadcliente['Actividadcliente']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Actividadclientes'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Actividadcliente'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Actividades'), array('controller' => 'actividades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Actividade'), array('controller' => 'actividades', 'action' => 'add')); ?> </li>
	</ul>
</div>
