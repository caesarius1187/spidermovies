<div class="cbuses form">
<?php echo $this->Form->create('Cbus'); ?>
	<fieldset>
		<legend><?php echo __('Add Cbus'); ?></legend>
	<?php
		echo $this->Form->input('bancosysindicato_id');
		echo $this->Form->input('tipo');
		echo $this->Form->input('numero');
		echo $this->Form->input('cbu');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>