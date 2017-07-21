<div class="estudios form">	
	<fieldset>
		<legend>
			<?php echo __('Editar Estudio'); ?>
		</legend>	
	</fieldset>
</div>
<div style="width:100%">	
	<div style="width:30%; float:left">
		<?php echo $this->Form->create('superadmin', array('controller' =>'Superadmins',
		 												'action' => 'edit')); ?>			

		<?php
			echo $this->Form->input('id');
			echo $this->Form->input('nombre',array('div' => false,
												   'style' => 'width:80%'));
		?>
	</div>
	<div style="width:30%; float:left">
		<?php
			echo $this->Form->input('propietario',array('div' => false,
														'style' => 'width:80%')
									);
		?>
	</div>
	<div style="width:30%; float:left">
		<?php
			echo $this->Form->input('direccion', array('div' => false, 
													   'style' => 'width:80%')
									);
		?>
	</div>
	<div style="width:30%; float:left">
		<?php
			echo $this->Form->input('cuit', array('div' => false, 
												  'style' => 'width:80%')
									);
		?>
	</div>
	<div style="width:30%; float:left">
		<?php
			echo $this->Form->input('ingresosbrutos', array('div' => false, 
															'style' => 'width:80%')
									);
		?>
	</div>
	<div style="width:30%; float:left">
		<?php
			echo $this->Form->input('inicioactividades', array('div' => false));
		?>
	</div>
</div>
<div style="width:100%; float:left">
	<?php echo $this->Form->end(__('Modificar'), array('div' => false)); ?>	
</div>
