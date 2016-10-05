<div class="archivos view">
<h2><?php echo __('Archivo'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($archivo['Archivo']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($archivo['Archivo']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha'); ?></dt>
		<dd>
			<?php echo h($archivo['Archivo']['fecha']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($archivo['Archivo']['estado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Eventosimpuesto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($archivo['Eventosimpuesto']['id'], array('controller' => 'eventosimpuestos', 'action' => 'view', $archivo['Eventosimpuesto']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Archivo'), array('action' => 'edit', $archivo['Archivo']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Archivo'), array('action' => 'delete', $archivo['Archivo']['id']), null, __('Are you sure you want to delete # %s?', $archivo['Archivo']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Archivos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Archivo'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Eventosimpuestos'), array('controller' => 'eventosimpuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Eventosimpuesto'), array('controller' => 'eventosimpuestos', 'action' => 'add')); ?> </li>
	</ul>
</div>
