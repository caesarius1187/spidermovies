<div class="form index">
	<?php echo $this->Form->create('Estudio',array('controller' =>'Superadmins',
			 									   'action' => 'add')); ?>
	<div class="estudios">
	
		<fieldset>
			<legend><?php echo __('Agregar Estudio'); ?></legend>
		</fieldset>
		<!--
		<?php
			echo $this->Form->input('nombre');
			echo $this->Form->input('propietario');
			echo $this->Form->input('direccion');
			echo $this->Form->input('cuit');
			echo $this->Form->input('ingresosbrutos');
			echo $this->Form->input('inicioactividades');		
		?>
		-->
	</div>
	<div style="width:100%">	
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
	<?php echo $this->Form->end(__('Submit')); ?>
</div>
