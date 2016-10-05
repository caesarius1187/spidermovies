<div class="contactos view">
<h2><?php echo __('Contacto'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($contacto['Contacto']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($contacto['Cliente']['id'], array('controller' => 'clientes', 'action' => 'view', $contacto['Cliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Razon'); ?></dt>
		<dd>
			<?php echo h($contacto['Contacto']['razon']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valor'); ?></dt>
		<dd>
			<?php echo h($contacto['Contacto']['valor']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valor2'); ?></dt>
		<dd>
			<?php echo h($contacto['Contacto']['valor2']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Contacto'), array('action' => 'edit', $contacto['Contacto']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Contacto'), array('action' => 'delete', $contacto['Contacto']['id']), null, __('Are you sure you want to delete # %s?', $contacto['Contacto']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Contactos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Contacto'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
	</ul>
</div>
