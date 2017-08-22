<div class="bienesdeusos form">
<?php echo $this->Form->create('Bienesdeuso'); ?>
	<fieldset>
		<legend><?php echo __('Edit Bienesdeuso'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('compra_id');
		echo $this->Form->input('tipo');
		echo $this->Form->input('periodo');
		echo $this->Form->input('titularidad');
		echo $this->Form->input('marca');
		echo $this->Form->input('modelo');
		echo $this->Form->input('fabrica');
		echo $this->Form->input('aniofabricacion');
		echo $this->Form->input('patente');
		echo $this->Form->input('valor');
		echo $this->Form->input('amortizado');
		echo $this->Form->input('tipoautomotor');
		echo $this->Form->input('fechaadquisicion');
		echo $this->Form->input('tipoinmueble');
		echo $this->Form->input('calle');
		echo $this->Form->input('destino');
		echo $this->Form->input('numero');
		echo $this->Form->input('piso');
		echo $this->Form->input('depto');
		echo $this->Form->input('localidade_id');
		echo $this->Form->input('codigopostal');
		echo $this->Form->input('catastro');
		echo $this->Form->input('partido');
		echo $this->Form->input('partida');
		echo $this->Form->input('digito');
		echo $this->Form->input('tipoembarcacion');
		echo $this->Form->input('nombre');
		echo $this->Form->input('eslora');
		echo $this->Form->input('manga');
		echo $this->Form->input('tonelajeneto');
		echo $this->Form->input('registro');
		echo $this->Form->input('registrojur');
		echo $this->Form->input('otroregistro');
		echo $this->Form->input('matricula');
		echo $this->Form->input('cantidadmotores');
		echo $this->Form->input('marcamotor');
		echo $this->Form->input('modelomotor');
		echo $this->Form->input('potencia');
		echo $this->Form->input('numeromotor');
		echo $this->Form->input('origen');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Bienesdeuso.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Bienesdeuso.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Bienesdeusos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Compras'), array('controller' => 'compras', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Compra'), array('controller' => 'compras', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Localidades'), array('controller' => 'localidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Localidade'), array('controller' => 'localidades', 'action' => 'add')); ?> </li>
	</ul>
</div>
