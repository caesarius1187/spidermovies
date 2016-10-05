

 <!-- Inicio Popin Nuevo CBU -->
<a href="#x" class="overlay" id="anuevoEventoCliente"></a>
<div id="creareventocliente" class="popup">
	<div class="eventosclientes form">
	<?php echo $this->Form->create('Eventoscliente'); ?>
		<fieldset>
			<legend><?php echo __('Agregar Evento Cliente'); ?></legend>
		<?php
			echo $this->Form->hidden('cliente_id', array('value' =>$cliid));
			echo $this->Form->hidden('periodo', array('value' =>$periodo));
			echo $this->Form->hidden('estado', array('value' =>'habilitado'));
		?>
		</fieldset>
	<?php echo $this->Form->end(__('Estoy seguro que deso crear el evento para el periodo '.$periodo)); ?>
	</div>
</div>
<a class="close" href="#close"></a>

<!-- Fin Popin Nuevo CBU --> 