<div class="encuadrealicuotas form">
<?php echo $this->Form->create('Encuadrealicuota'); ?>
	<fieldset>
		<legend><?php echo __('Add Encuadrealicuota'); ?></legend>
	<?php
		echo $this->Form->input('actividadcliente_id');
		echo $this->Form->input('impcliprovincia_id');
		echo $this->Form->input('alicuota');
		echo $this->Form->input('concepto');
		echo $this->Form->input('desde');
		echo $this->Form->input('hasta');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Encuadrealicuotas'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Actividadclientes'), array('controller' => 'actividadclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Actividadcliente'), array('controller' => 'actividadclientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impcliprovincias'), array('controller' => 'impcliprovincias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcliprovincia'), array('controller' => 'impcliprovincias', 'action' => 'add')); ?> </li>
	</ul>
</div>
