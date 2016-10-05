<div class="organismosxclientes form">
<?php echo $this->Form->create('Organismosxcliente'); ?>
	<fieldset>
		<legend><?php echo __('Add Organismosxcliente'); ?></legend>
	<?php
		echo $this->Form->input('cliente_id');
		echo $this->Form->input('tipoorganismo');
		echo $this->Form->input('usuario');
		echo $this->Form->input('clave');
		echo $this->Form->input('codigoactividad');
		echo $this->Form->input('descripcionactividad');
		echo $this->Form->input('descripcion');
		echo $this->Form->input('vencimiento');
		echo $this->Form->input('expediente');
		echo $this->Form->input('estado');
		echo $this->Form->input('observaciones');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Organismosxclientes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
	</ul>
</div>
