<div class="personasrelacionadas form">
<?php echo $this->Form->create('Personasrelacionada'); ?>
	<fieldset>
		<legend><?php echo __('Add Personasrelacionada'); ?></legend>
	<?php
		echo $this->Form->input('cliente_id');
		echo $this->Form->input('tipo');
		echo $this->Form->input('Vto. del Mandat');
		echo $this->Form->input('porcentajeparticipacion');
		echo $this->Form->input('sede');
		echo $this->Form->input('nombrefantasia');
		echo $this->Form->input('puntodeventa');
		echo $this->Form->input('localidade_id');
		echo $this->Form->input('calle');
		echo $this->Form->input('numero');
		echo $this->Form->input('piso');
		echo $this->Form->input('ofidepto');
		echo $this->Form->input('ruta');
		echo $this->Form->input('kilometro');
		echo $this->Form->input('torre');
		echo $this->Form->input('manzana');
		echo $this->Form->input('entrecalles');
		echo $this->Form->input('codigopostal');
		echo $this->Form->input('telefono');
		echo $this->Form->input('movil');
		echo $this->Form->input('fax');
		echo $this->Form->input('email');
		echo $this->Form->input('personacontacto');
		echo $this->Form->input('observaciones');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Personasrelacionadas'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Localidades'), array('controller' => 'localidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Localidade'), array('controller' => 'localidades', 'action' => 'add')); ?> </li>
	</ul>
</div>
