<div class="conceptosrestantes form">
<?php echo $this->Form->create('Conceptosrestante'); ?>
	<fieldset>
		<legend><?php echo __('Add Conceptosrestante'); ?></legend>
	<?php
		echo $this->Form->input('partido_id');
		echo $this->Form->input('cliente_id');
		echo $this->Form->input('impcli_id');
		echo $this->Form->input('periodo');
		echo $this->Form->input('comprobante_id');
		echo $this->Form->input('rectificativa');
		echo $this->Form->input('numerocomprobante');
		echo $this->Form->input('razonsocial');
		echo $this->Form->input('monto');
		echo $this->Form->input('montoretenido');
		echo $this->Form->input('cuit');
		echo $this->Form->input('fecha');
		echo $this->Form->input('conceptostipo_id');
		echo $this->Form->input('numerodespachoaduanero');
		echo $this->Form->input('anticipo');
		echo $this->Form->input('cbu');
		echo $this->Form->input('tipocuenta');
		echo $this->Form->input('tipomoneda');
		echo $this->Form->input('agente');
		echo $this->Form->input('enterecaudador');
		echo $this->Form->input('regimen');
		echo $this->Form->input('descripcion');
		echo $this->Form->input('numeropadron');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Conceptosrestantes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Partidos'), array('controller' => 'partidos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Partido'), array('controller' => 'partidos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('controller' => 'impclis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Comprobantes'), array('controller' => 'comprobantes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Comprobante'), array('controller' => 'comprobantes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Conceptostipos'), array('controller' => 'conceptostipos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Conceptostipo'), array('controller' => 'conceptostipos', 'action' => 'add')); ?> </li>
	</ul>
</div>
