<div class="eventos form">
<?php echo $this->Form->create('Evento'); ?>
	<fieldset>
		<legend><?php echo __('Edit Evento'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('plan');
		echo $this->Form->input('cbu');
		echo $this->Form->input('periodo');
		echo $this->Form->input('fchvto');
		echo $this->Form->input('montovto');
		echo $this->Form->input('fchrealizado');
		echo $this->Form->input('montorealizado');
		echo $this->Form->input('monc');
		echo $this->Form->input('user_id');
		echo $this->Form->input('lugarpago_id');
		echo $this->Form->input('montorecibido');
		echo $this->Form->input('estado');
		echo $this->Form->input('estadoanterior');
		echo $this->Form->input('nombre');
		echo $this->Form->input('descripcion');
		echo $this->Form->input('publico');
		echo $this->Form->input('archnombre');
		echo $this->Form->input('archfecha');
		echo $this->Form->input('informar');
		echo $this->Form->input('impcli_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Evento.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Evento.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Eventos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Lugarpagos'), array('controller' => 'lugarpagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lugarpago'), array('controller' => 'lugarpagos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('controller' => 'impclis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Archivos'), array('controller' => 'archivos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Archivo'), array('controller' => 'archivos', 'action' => 'add')); ?> </li>
	</ul>
</div>
