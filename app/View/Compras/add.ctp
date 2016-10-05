<div class="compras form">
<?php echo $this->Form->create('Compra'); ?>
	<fieldset>
		<legend><?php echo __('Add Compra'); ?></legend>
	<?php
		echo $this->Form->input('cliente_id');
		echo $this->Form->input('condicioniva');
		echo $this->Form->input('fecha');
		echo $this->Form->input('tipocomprobante');
		echo $this->Form->input('puntosdeventa_id');
		echo $this->Form->input('numerocomprobante');
		echo $this->Form->input('subcliente_id');
		echo $this->Form->input('localidade_id');
		echo $this->Form->input('tipogasto');
		echo $this->Form->input('imputacion');
		echo $this->Form->input('tipocredito');
		echo $this->Form->input('alicuota');
		echo $this->Form->input('neto');
		echo $this->Form->input('tipoiva');
		echo $this->Form->input('iva');
		echo $this->Form->input('ivapercep');
		echo $this->Form->input('iibbpercep');
		echo $this->Form->input('actvspercep');
		echo $this->Form->input('impinternos');
		echo $this->Form->input('impcombustible');
		echo $this->Form->input('nogravados');
		echo $this->Form->input('nogravadogeneral');
		echo $this->Form->input('total');
		echo $this->Form->input('asiento');
		echo $this->Form->input('periodo');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Compras'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Puntosdeventas'), array('controller' => 'puntosdeventas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Puntosdeventa'), array('controller' => 'puntosdeventas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Subclientes'), array('controller' => 'subclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Subcliente'), array('controller' => 'subclientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Localidades'), array('controller' => 'localidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Localidade'), array('controller' => 'localidades', 'action' => 'add')); ?> </li>
	</ul>
</div>
