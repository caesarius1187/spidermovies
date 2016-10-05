<div class="tareasxclientesxestudios view">
<h2><?php echo __('Tareasxclientesxestudio'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($tareasxclientesxestudio['Tareasxclientesxestudio']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($tareasxclientesxestudio['Tareasxclientesxestudio']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tareascliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($tareasxclientesxestudio['Tareascliente']['id'], array('controller' => 'tareasclientes', 'action' => 'view', $tareasxclientesxestudio['Tareascliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($tareasxclientesxestudio['Tareasxclientesxestudio']['estado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estudio'); ?></dt>
		<dd>
			<?php echo $this->Html->link($tareasxclientesxestudio['Estudio']['id'], array('controller' => 'estudios', 'action' => 'view', $tareasxclientesxestudio['Estudio']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Tareasxclientesxestudio'), array('action' => 'edit', $tareasxclientesxestudio['Tareasxclientesxestudio']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Tareasxclientesxestudio'), array('action' => 'delete', $tareasxclientesxestudio['Tareasxclientesxestudio']['id']), null, __('Are you sure you want to delete # %s?', $tareasxclientesxestudio['Tareasxclientesxestudio']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tareasxclientesxestudios'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareasxclientesxestudio'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tareasclientes'), array('controller' => 'tareasclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareascliente'), array('controller' => 'tareasclientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Estudios'), array('controller' => 'estudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Estudio'), array('controller' => 'estudios', 'action' => 'add')); ?> </li>
	</ul>
</div>
