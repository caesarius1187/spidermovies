<div class="formatos form">
<?php echo $this->Form->create('Formato'); ?>
	<fieldset>
		<legend><?php echo __('Add Formato'); ?></legend>
	<?php
		echo $this->Form->input('nombre');
		echo $this->Form->input('precio');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Formatos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Peliculas'), array('controller' => 'peliculas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Pelicula'), array('controller' => 'peliculas', 'action' => 'add')); ?> </li>
	</ul>
</div>
