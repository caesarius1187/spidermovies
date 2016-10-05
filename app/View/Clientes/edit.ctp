<div class="clientes form">
<?php echo $this->Form->create('Cliente'); ?>
	<fieldset>
		<legend><?php echo __('Edit Cliente'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('nombre');
		echo $this->Form->input('cuitcontribullente');
		echo $this->Form->input('cuitautorizada');
		echo $this->Form->input('grupocliente_id');
		echo $this->Form->input('claveafip');
		echo $this->Form->input('clavedgr');
		echo $this->Form->input('clavedgrm');
		echo $this->Form->input('cpa');
		echo $this->Form->input('estado');
		echo $this->Form->input('localidad');
		echo $this->Form->input('accesos');
		echo $this->Form->input('dni');
		echo $this->Form->input('tipopersona');
		echo $this->Form->input('tipopersonajuridica');
		echo $this->Form->input('fchcumpleañosconstitucion');
		echo $this->Form->input('fchcorteejerciciofiscal');
		echo $this->Form->input('inscripcionregistrocomercio');
		echo $this->Form->input('descripcionactividad');
		echo $this->Form->input('fchiniciocliente');
		echo $this->Form->input('jurisdiccion');
		echo $this->Form->input('nombrefantasia');
		echo $this->Form->input('partido_id');
		echo $this->Form->input('localidade_id');
		echo $this->Form->input('calle');
		echo $this->Form->input('numerocasa');
		echo $this->Form->input('piso');
		echo $this->Form->input('ofidpto');
		echo $this->Form->input('torre');
		echo $this->Form->input('manzana');
		echo $this->Form->input('fchfincliente');
		echo $this->Form->input('entrecalles');
		echo $this->Form->input('codigopostal');
		echo $this->Form->input('telefono');
		echo $this->Form->input('movil');
		echo $this->Form->input('fax');
		echo $this->Form->input('email');
		echo $this->Form->input('personacontacto');
		echo $this->Form->input('observaciones');
		echo $this->Form->input('controladorfiscal');
		echo $this->Form->input('emitefacturaa');
		echo $this->Form->input('emitefacturab');
		echo $this->Form->input('emitefacturac');
		echo $this->Form->input('vtocaia');
		echo $this->Form->input('vtocaib');
		echo $this->Form->input('vtocaic');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Cliente.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Cliente.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Grupoclientes'), array('controller' => 'grupoclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Grupocliente'), array('controller' => 'grupoclientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Partidos'), array('controller' => 'partidos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Partido'), array('controller' => 'partidos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Localidades'), array('controller' => 'localidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Localidade'), array('controller' => 'localidades', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Contactos'), array('controller' => 'contactos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Contacto'), array('controller' => 'contactos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Depositos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Deposito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Eventosclientes'), array('controller' => 'eventosclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Eventoscliente'), array('controller' => 'eventosclientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Honorarios'), array('controller' => 'honorarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Honorario'), array('controller' => 'honorarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('controller' => 'impclis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Organismosxclientes'), array('controller' => 'organismosxclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Organismosxcliente'), array('controller' => 'organismosxclientes', 'action' => 'add')); ?> </li>
	</ul>
</div>
