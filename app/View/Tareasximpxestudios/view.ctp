<div class="tareasximpxestudios view">
<h2><?php echo __('Tareasximpxestudio'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($tareasximpxestudio['Tareasximpxestudio']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($tareasximpxestudio['Tareasximpxestudio']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tareasimpuesto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($tareasximpxestudio['Tareasimpuesto']['id'], array('controller' => 'tareasimpuestos', 'action' => 'view', $tareasximpxestudio['Tareasimpuesto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($tareasximpxestudio['Tareasximpxestudio']['estado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estudio'); ?></dt>
		<dd>
			<?php echo $this->Html->link($tareasximpxestudio['Estudio']['id'], array('controller' => 'estudios', 'action' => 'view', $tareasximpxestudio['Estudio']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Tareasximpxestudio'), array('action' => 'edit', $tareasximpxestudio['Tareasximpxestudio']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Tareasximpxestudio'), array('action' => 'delete', $tareasximpxestudio['Tareasximpxestudio']['id']), null, __('Are you sure you want to delete # %s?', $tareasximpxestudio['Tareasximpxestudio']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tareasximpxestudios'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasximpxestudio'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tareasimpuestos'), array('controller' => 'tareasimpuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasimpuesto'), array('controller' => 'tareasimpuestos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Estudios'), array('controller' => 'estudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Estudio'), array('controller' => 'estudios', 'action' => 'add')); ?> </li>
	</ul>
</div>
