<div class="bancosysindicatos form">
<?php echo $this->Form->create('Bancosysindicato'); ?>
	<fieldset>
		<legend><?php echo __('Add Bancosysindicato'); ?></legend>
	<?php
		echo $this->Form->input('Id');
		echo $this->Form->input('clientes_id');
		echo $this->Form->input('razon');
		echo $this->Form->input('nombre');
		echo $this->Form->input('usuario');
		echo $this->Form->input('clave');
		echo $this->Form->input('labeldatoadicional');
		echo $this->Form->input('datoadicional');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>