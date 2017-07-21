<div class="form index">
	<?php echo $this->Form->create('Estudio',array('controller' =>'Estudios',
			 									   'action' => 'superadminestudioadd')); ?>
	<div>	
		<fieldset>
			<legend><?php echo __('Agregar Estudio'); ?></legend>
		</fieldset>		
	</div>
	<div class="estudios" style="width:100%">	
		<div style="width:30%; float:left">
			<?php			
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
				echo $this->Form->input('email', array('div' => false, 
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
				echo $this->Form->input('usuario', array('div' => false, 
														 'style' => 'width:80%')
										);
			?>
		</div>
		<div style="width:30%; float:left">
			<?php
				echo $this->Form->input('password', array('div' => false, 
										 				  'style' => 'width:80%',
										 				  'label' => 'Contraseña')
										);
			?>
		</div>
		<div style="width:30%; float:left">
			<?php
				echo $this->Form->input('inicioactividades', array('div' => false));
			?>
		</div>
	</div>	
	<?php echo $this->Form->end(__('Submit')); ?>
</div>
